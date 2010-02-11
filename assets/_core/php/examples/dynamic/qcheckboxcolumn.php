<?php
	require_once('../qcubed.inc.php');
	
	class ExampleForm extends QForm {
		// Declare the DataGrid and Response Label
		protected $dtgPersons;
		protected $lblResponse;
		protected $colSelect;
		
		protected $dtgProjects;
		protected $colProjectSelected;

		protected $btnGo;

		protected function Form_Create() {
			// Define the DataGrid
			$this->dtgPersons = new QDataGrid($this);
			$this->dtgPersons->CellPadding = 5;
			$this->dtgPersons->CellSpacing = 0;
			
			// Specify Pagination with 10 items per page
			$objPaginator = new QPaginator($this->dtgPersons);
			$this->dtgPersons->Paginator = $objPaginator;
			$this->dtgPersons->ItemsPerPage = 10;

			// Define Columns
			$this->dtgPersons->AddColumn(new QDataGridColumn('Person ID', '<?= $_ITEM->Id ?>', 'Width=100',
				array('OrderByClause' => QQ::OrderBy(QQN::Person()->Id), 'ReverseOrderByClause' => QQ::OrderBy(QQN::Person()->Id, false))));
			
			$this->dtgPersons->AddColumn(new QDataGridColumn('First Name', '<?= $_ITEM->FirstName ?>', 'Width=200',
				array('OrderByClause' => QQ::OrderBy(QQN::Person()->FirstName), 'ReverseOrderByClause' => QQ::OrderBy(QQN::Person()->FirstName, false))));
			
			$this->dtgPersons->AddColumn(new QDataGridColumn('Last Name', '<?= $_ITEM->LastName ?>', 'Width=200',
				array('OrderByClause' => QQ::OrderBy(QQN::Person()->LastName), 'ReverseOrderByClause' => QQ::OrderBy(QQN::Person()->LastName, false))));

			//Create the select column
			$this->colSelect = new QCheckBoxColumn(QApplication::Translate('Select All'), $this->dtgPersons);
			// And we make sure to set HtmlEntities to "false" so that our checkbox doesn't get HTML Escaped
			$this->colSelect->HtmlEntities = false;
			//and in this case, we want to interceed whenever a checkbox is rendered so that we can assign
			//an action to it
			$this->colSelect->SetCheckboxCallback($this, 'chkSelected_Render');
			$this->dtgPersons->AddColumnAt(0, $this->colSelect);

			// Let's pre-default the sorting by last name (column index #2)
			$this->dtgPersons->SortColumnIndex = 2;

			// Specify the DataBinder method for the DataGrid
			$this->dtgPersons->SetDataBinder('dtgPersons_Bind');

			// Make the DataGrid look nice
			$objStyle = $this->dtgPersons->RowStyle;
			$objStyle->FontSize = 12;

			$objStyle = $this->dtgPersons->AlternateRowStyle;
			$objStyle->BackColor = '#eaeaea';

			$objStyle = $this->dtgPersons->HeaderRowStyle;
			$objStyle->ForeColor = 'white';
			$objStyle->BackColor = '#000066';

			// Because browsers will apply different styles/colors for LINKs
			// We must explicitly define the ForeColor for the HeaderLink.
			// The header row turns into links when the column can be sorted.
			$objStyle = $this->dtgPersons->HeaderLinkStyle;
			$objStyle->ForeColor = 'white';
			
			$this->dtgProjects_Create();
			$this->btnGo_Create();
			
			
			// Define the Label -- keep it blank for now
			$this->lblResponse = new QLabel($this);
			$this->lblResponse->HtmlEntities = false;
		}

		protected function dtgPersons_Bind() {
			// Let the datagrid know how many total items and then get the data source
			$this->dtgPersons->TotalItemCount = Person::CountAll();
			$this->dtgPersons->DataSource = Person::LoadAll(QQ::Clause(
				$this->dtgPersons->OrderByClause,
				$this->dtgPersons->LimitClause
			));
		}

		// This method (declared as public) will set up the action on the generated checkboxes
		public function chkSelected_Render(Person $objPerson, QCheckBox $chkSelected) {
				// Let's assign a server action on click
				$chkSelected->AddAction(new QClickEvent(), new QServerAction('chkSelected_Click'));
		}
		
		// This btnCopy_Click action will actually perform the copy of the person row being copied
		protected function chkSelected_Click($strFormId, $strControlId, $strParameter) {
			// We look to the Parameter for the ID of the person being checked
			$arrParameters = explode(',', $strParameter);
			$intPersonId = $arrParameters[0];
			
			// Let's get the selected person
			$objPerson = Person::Load($intPersonId);
			$chkSelected = $this->GetControl($strControlId);
			
			// Let's respond to the user what just happened
			if ($chkSelected->Checked)
				$strResponse = QApplication::HtmlEntities('You just selected ' . $objPerson->FirstName . ' ' . $objPerson->LastName . '.');
			else
				$strResponse = QApplication::HtmlEntities('You just deselected ' . $objPerson->FirstName . ' ' . $objPerson->LastName . '.');
			$strResponse .= '<br/>';

			// Now, let's go through all the checkboxes and list everyone who has been selected
			$arrPeople = $this->colSelect->GetSelectedItems('Person');
			$strNameArray = array();
			foreach($arrPeople as $objPerson) {
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
			$this->colProjectSelected = new QCheckBoxColumn(QApplication::Translate('Select'), $this->dtgProjects);
			
			// Set a callback on the checkbox creation so we can check those that are already associated
			$this->colProjectSelected->SetCheckboxCallback($this, 'colProjectSelectedCheckbox_Created');
			$this->dtgProjects->AddColumn($this->colProjectSelected);

			$this->dtgProjects->AddColumn(new QDataGridColumn(QApplication::Translate('Name'), '<?= $_ITEM->Name; ?>'));
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

			$objDatabase = Project::GetDatabase();

			$objClauses = array();
			if ($objClause = $this->dtgProjects->OrderByClause)
				$objClauses[] = $objClause;
			if ($objClause = $this->dtgProjects->LimitClause)
				$objClauses[] = $objClause;

			// Create a virtual attribute that lets us know if this Project is related to ACME
			$objClauses[] = QQ::Expand(
				QQ::Virtual('assn_item', 
					QQ::SubSql(
						"select 
							`project_id`
					 	from 
					 		`related_project_assn`
					 	where 
							`child_project_id` = {1} 
							 and `project_id` = 1", 
							QQN::Project()->Id)
				)
			);
			
			$this->dtgProjects->DataSource = Project::LoadAll($objClauses);
		}
		
		public function btnGo_Create() {
			$this->btnGo = new QButton($this);
			$this->btnGo->Text = 'Go';
			$this->btnGo->AddAction(new QClickEvent(), new QServerAction('btnGo_Click'));
		}
		
		public function btnGo_Click($strFormId, $strControlId, $strParameter) {
			//get a list of project ids that have had their status changed
			$changedIds = $this->colProjectSelected->GetChangedIds();
			
			//load all the changed project objects at once so we can avoid multiple DB hits
			$temp = Project::QueryArray(QQ::In(QQN::Project()->Id, array_keys($changedIds)));
			
			//Put them in an associated list so we can find the needed ones easily later
			$changedItems = array();
			foreach($temp as $item)
				$changedItems[$item->Id] = $item;
			
			foreach($changedIds as $id=>$blnSelected)
			{
				//look up the appropriate item using the handily indexed array we built earlier
				$item = $changedItems[$id];
				if($blnSelected)
				{
					//Associate this Project
					QApplication::DisplayAlert('Associating '.$item->Name);
				}
				else
				{
					//Unassociate this Project
					QApplication::DisplayAlert('Unassociating '.$item->Name);
				}
			}
		}
	}

	ExampleForm::Run('ExampleForm');
?>
