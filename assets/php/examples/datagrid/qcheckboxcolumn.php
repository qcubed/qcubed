<?php
	require_once('../qcubed.inc.php');

	class ExampleCheckColumn1 extends QDataGrid_CheckBoxColumn {
		protected function GetAllIds()
		{
			return Person::QueryPrimaryKeys();
		}
	}


	class ExampleCheckColumn2 extends QDataGrid_CheckBoxColumn {
		protected function GetItemCheckedState ($item) {
			if(null !== $item->GetVirtualAttribute('assn_item')) {
				return true;
			}
			else {
				return false;
			}
		}

		public function SetItemCheckedState($itemId, $blnChecked) {
			$objProject = Project::Load($itemId);
			if($blnChecked)
			{
				// Simulate an associating with the project
				QApplication::DisplayAlert('Associating '.$objProject->Name);

				// To actually do the association, we would execute the following:
				/*
				$objParentProject = Project::Load(1);	// We were associating the ACME project
				$objParentProject->AssociateProjectAsRelated ($objProject);
				 */
			}
			else
			{
				// Simulate unassociating the Project
				QApplication::DisplayAlert('Unassociating '.$objProject->Name);
			}


		}
	}



class ExampleForm extends QForm {
		// Declare the DataGrid and Response Label
		protected $dtgPersons;
		protected $lblResponse;

		/** @var  QDataGrid_CheckBoxColumn */
		protected $colSelect;
		
		protected $dtgProjects;
		protected $colProjectSelected;

		protected $btnGo;

		protected function Form_Create() {

			$this->dtgPersons_Create();
			$this->dtgProjects_Create();

			
			// Define the Label -- keep it blank for now
			$this->lblResponse = new QLabel($this);
			$this->lblResponse->HtmlEntities = false;
		}

		protected function dtgPersons_Create() {
			// Define the DataGrid
			$this->dtgPersons = new QDataGrid($this);

			// Specify Pagination with 10 items per page
			$objPaginator = new QPaginator($this->dtgPersons);
			$this->dtgPersons->Paginator = $objPaginator;
			$this->dtgPersons->ItemsPerPage = 10;

			// Define Columns
			$col = $this->dtgPersons->CreateNodeColumn('Person ID', QQN::Person()->Id);
			$col->CellStyler->Width = 100;
			$col = $this->dtgPersons->CreateNodeColumn('First Name', [QQN::Person()->FirstName, QQN::Person()->LastName]);
			$col->CellStyler->Width = 200;
			$col = $this->dtgPersons->CreateNodeColumn('Last Name', [QQN::Person()->LastName, QQN::Person()->LastName]);
			$col->CellStyler->Width = 200;

			//Create the select column, a subclass of QDataGrid_CheckBoxColumn
			$this->colSelect = new ExampleCheckColumn1('');
			$this->colSelect->ShowCheckAll = true;
			$this->colSelect->CellStyler->Width = 20;

			$this->dtgPersons->AddColumnAt(0, $this->colSelect);

			// Let's pre-default the sorting by last name (column index #2)
			$this->dtgPersons->SortColumnIndex = 2;

			// Specify the DataBinder method for the DataGrid
			$this->dtgPersons->SetDataBinder('dtgPersons_Bind');

			$this->dtgPersons->AddAction(new QHtmlTableCheckBoxColumn_ClickEvent(), new QAjaxAction ('chkSelected_Click'));

			// Make sure changes to the database by other users are reflected in the datagrid on the next event
			$this->dtgPersons->Watch(QQN::Person());

		}

		protected function dtgPersons_Bind() {
			// Let the datagrid know how many total items and then get the data source
			$this->dtgPersons->TotalItemCount = Person::CountAll();
			$this->dtgPersons->DataSource = Person::LoadAll(QQ::Clause(
				$this->dtgPersons->OrderByClause,
				$this->dtgPersons->LimitClause
			));
		}

		// This btnCopy_Click action will actually perform the copy of the person row being copied
		protected function chkSelected_Click($strFormId, $strControlId, $params) {
			$blnChecked = $params['checked'];

			// The database record primary key is embedded after the last underscore in the id of the checkbox
			$idItems = explode('_', $params['id']);
			$intPersonId = end($idItems);

			if ($intPersonId == 'all') {
				$strResponse = QApplication::HtmlEntities('You just selected all. ');
			}
			else {
				$objPerson = Person::Load($intPersonId);

				// Let's respond to the user what just happened
				if ($blnChecked)
					$strResponse = QApplication::HtmlEntities('You just selected ' . $objPerson->FirstName . ' ' . $objPerson->LastName . '.');
				else
					$strResponse = QApplication::HtmlEntities('You just deselected ' . $objPerson->FirstName . ' ' . $objPerson->LastName . '.');
				$strResponse .= '<br/>';
			}
			// Let's get the selected person

			// Now, let's go through all the checkboxes and list everyone who has been selected
			$arrIds = $this->colSelect->GetCheckedItemIds();
			$strNameArray = array();
			foreach($arrIds as $strId) {
				$objPerson = Person::Load($strId);
				$strName = QApplication::HtmlEntities($objPerson->FirstName . ' ' . $objPerson->LastName);
				$strNameArray[] = $strName;
			}

			$strResponse .= 'The list of people who are currently selected: ' . implode(', ', $strNameArray);

			// Provide feedback to the user by updating the Response label
			$this->lblResponse->Text = $strResponse;
		}

		protected function dtgProjects_Create() {
			// Setup DataGrid
			$this->dtgProjects = new QDataGrid($this);
			$this->dtgProjects->CssClass = 'datagrid';

			// Datagrid Paginator
			$this->dtgProjects->Paginator = new QPaginator($this->dtgProjects);

			// If desired, use this to set the numbers of items to show per page
			//$this->lstProjectsAsRelated->ItemsPerPage = 20;

			// Specify Whether or Not to Refresh using Ajax
			$this->dtgProjects->UseAjax = true;

			// Specify the local databind method this datagrid will use
			$this->dtgProjects->SetDataBinder('dtgProjects_Bind', $this);

			// Setup DataGridColumns
			$this->colProjectSelected = new ExampleCheckColumn2(QApplication::Translate('Select'));
			
			$this->dtgProjects->AddColumn($this->colProjectSelected);

			$this->dtgProjects->CreateNodeColumn(QApplication::Translate('Name'), QQN::Project()->Name);

			// Make sure changes to the database by other users are reflected in the datagrid on the next event
			$this->dtgProjects->Watch(QQN::Project());
		}
		
		
		public function colProjectSelectedCheckbox_Created(Project $_ITEM, QCheckBox $ctl)
		{
			//If it's related to ACME, start it off checked
			if(null !== $_ITEM->GetVirtualAttribute('assn_item'))
				$ctl->Checked = true;
			//You could perform an IsProjectAsRelatedAssociated call here instead, but
			//that would cause a database hit
		}

		public function dtgProjects_Bind() {
			// Get Total Count b/c of Pagination
			$this->dtgProjects->TotalItemCount = Project::CountAll();

			$objClauses = array();
			if ($objClause = $this->dtgProjects->OrderByClause)
				$objClauses[] = $objClause;
			if ($objClause = $this->dtgProjects->LimitClause)
				$objClauses[] = $objClause;

			// Create a virtual attribute that lets us know if this Project is related to ACME
			$objClauses[] = QQ::Expand(
				QQ::Virtual('assn_item', 
					QQ::SubSql(
						'select 
							project_id
					 	from 
					 		related_project_assn
					 	where 
							child_project_id = {1} 
							 and project_id = 1', 
							QQN::Project()->Id)
				)
			);
			
			$this->dtgProjects->DataSource = Project::LoadAll($objClauses);
		}
	}

	ExampleForm::Run('ExampleForm');
?>
