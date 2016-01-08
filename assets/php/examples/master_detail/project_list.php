<?php
	// Load the QCubed Development Framework
	require('../qcubed.inc.php');
	
	// our child QPanel
	require ('./records.summary.php');
		
	class ProjectListForm extends QForm {
		// Local instance of the Meta DataGrid to list Projects
		protected $dtgProjects;
					
		protected function Form_Create() {
			//$this->objDefaultWaitIcon = new QWaitIcon($this);

			// Instantiate the DataGrid
			// It is a simple QDataGrid because the generated class ProjectList
			// is inherited from the QDataGrid2 now
			$this->dtgProjects = new QDataGrid($this);
			//$this->dtgProjects->WaitIcon = $this->objDefaultWaitIcon;
					
			// Style the DataGrid (if desired)
			$this->dtgProjects->CssClass = 'datagrid';
			$this->dtgProjects->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgProjects->Paginator = new QPaginator($this->dtgProjects);
			$this->dtgProjects->ItemsPerPage = 3;
			
			/************************************************************************* 
			/  to  use old filter  
			/  uncomment the line  below ($this->dtgProjects->ShowFilter = false;)
			**************************************************************************/
			// $this->dtgProjects->ShowFilter = false;
			/************************************************************************/
			
			
			// Use the MetaDataGrid functionality to add Columns for this datagrid
			
			// Here we start, create our first control a simple toogle button
			$this->dtgProjects->AddColumn(
							new QDataGridColumn(
								'',
								'<?= $_FORM->render_btnToggleRecordsSummary($_ITEM) ?>',
								'HtmlEntities=false',
								'Width=1px'));
			
			// Create the Other Columns 
			// Note that you can use strings for project's properties,
			// or you can traverse down QQN::project() to display fields 
			// that are down the hierarchy)
			
			// now is time to add some trick to filter only by some field
			 
			// remove filter from ID column
			$colId = new QDataGridColumn('Id', '<?= $_ITEM->Id ?>');
			$colId->FilterType = "";
			$this->dtgProjects->AddColumn($colId);

			$this->dtgProjects->AddColumn(new QDataGridColumn('Project', '<?= $_ITEM->Name ?>'));
			$this->MetaAddTypeColumn(QQN::Project()->ProjectStatusTypeId, 'ProjectStatusType');
			//$this->dtgProjects->AddConnectedColumn(QQN::Project()->ManagerPerson);
			$this->dtgProjects->AddColumn(new QDataGridColumn('Description', '<?= $_ITEM->Description ?>'));
			
			// remove filter from field
			//$this->dtgProjects->AddConnectedColumn('StartDate');
			$colStartDate = new QDataGridColumn('StartDate', '<?= $_ITEM->StartDate ?>');
			$colStartDate->FilterType = "";
			$this->dtgProjects->AddColumn($colStartDate);
			
			// remove filter from field
			//$this->dtgProjects->AddConnectedColumn('EndDate');
			$colEndDate = new QDataGridColumn('EndDate', '<?= $_ITEM->EndDate ?>');
			$colEndDate->FilterType = "";
			$this->dtgProjects->AddColumn($colEndDate);
			
			// remove filter from field
			// $this->dtgProjects->AddConnectedColumn('Budget');
			$colBudget = new QDataGridColumn('Budget', '<?= $_ITEM->Budget ?>');
			$colBudget->FilterType = "";
			$this->dtgProjects->AddColumn($colBudget);
			
			// remove filter from field
			// $this->dtgProjects->AddConnectedColumn('Spent');
			$colSpent = new QDataGridColumn('Spent', '<?= $_ITEM->Spent ?>');
			$colSpent->FilterType = ""; 
			$this->dtgProjects->AddColumn($colSpent);
			
			// Second... 
			// we need to create out Child QDataGrid 
			// at moment we put them hide in a column.
			$this->dtgProjects->AddColumn(
			new QDataGridColumn('',
							'<?= $_FORM->render_ucRecordsSummary($_ITEM) ?>',
							'HtmlEntities=false','Width=1px'));
			
			// Specify the Datagrid's Data Binder method
			//$this->dtgProjects->SetDataBinder('dtgProjects_Bind');
			
			// Make the DataGrid look nice
			$objStyle = $this->dtgProjects->RowStyle;
			$objStyle->FontSize = 12;
			
			$objStyle = $this->dtgProjects->AlternateRowStyle;
			$objStyle->BackColor = '#f6f6f6';

			$objStyle = $this->dtgProjects->HeaderRowStyle;
			$objStyle->ForeColor = 'white';
			$objStyle->BackColor = '#780000';

			// Because browsers will apply different styles/colors for LINKs
			// We must explicitly define the ForeColor for the HeaderLink.
			// The header row turns into links when the column can be sorted.
			$objStyle = $this->dtgProjects->HeaderLinkStyle;
			$objStyle->ForeColor = 'white';
			
			$this->dtgProjects->SetDataBinder('dtgProjects_Bind', $this);
		}
		
		// This hack is here because dtgProjects is not ProjectDataGrid anymore, but just a QDataGrid
		public function MetaAddTypeColumn($objNode, $strTypeClassName, $objOverrideParameters = null) {
			// Validate TypeClassName
			if (!class_exists($strTypeClassName) || !property_exists($strTypeClassName, 'NameArray')) {
				throw new QCallerException('Invalid TypeClass Name: ' . $strTypeClassName);
			}

			// Create the Column
			$strName = QConvertNotation::WordsFromCamelCase($objNode->_PropertyName);
			if (strtolower(substr($strName, strlen($strName) - 3)) == ' id') {
				$strName = substr($strName, 0, strlen($strName) - 3);
			}
			$strProperty = $objNode->GetDataGridHtml();
			$objNewColumn = new QDataGridColumn(
				QApplication::Translate($strName),
				sprintf('<?=(%s) ? %s::$NameArray[%s] : null;?>', $strProperty, $strTypeClassName, $strProperty),
				array(
					'OrderByClause' => QQ::OrderBy($objNode),
					'ReverseOrderByClause' => QQ::OrderBy($objNode, false)
				)
			);

			// Perform Overrides
			$objOverrideArray = func_get_args();
			if (count($objOverrideArray) > 2) {
				try {
					unset($objOverrideArray[0]);
					unset($objOverrideArray[1]);
					$objNewColumn->OverrideAttributes($objOverrideArray);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}

			$this->dtgProjects->AddColumn($objNewColumn);
			return $objNewColumn;
		}
		
		public function dtgProjects_Bind() {
			// We must first let the datagrid know how many total items there are
			$this->dtgProjects->TotalItemCount = Project::CountAll();

			// Next, we must be sure to load the data source, passing in the datagrid's
			// limit info into our loadall method.
			$this->dtgProjects->DataSource = Project::LoadAll(QQ::Clause(
				$this->dtgProjects->OrderByClause, $this->dtgProjects->LimitClause
			));
		}
		
		// Function to render our toggle button column
		// As you can see we pass as a parameter the item binded in the
		// row of QDataGrid
		public function render_btnToggleRecordsSummary(Project $objProject) {			
			// Create their unique id...
			$objControlId = 'btnToggleRecordsSummary' . $objProject->Id;
			
			if (!$objControl = $this->GetControl($objControlId)) {			
				// magia 2011-07  add in button info of child presence
				$team_member = Person::LoadArrayByProjectAsTeamMember($objProject->Id);
				if (count($team_member)> 0) {

					// If not exists create our toggle button who his parent
					// is our master QDataGrid...
					$objControl = new QButton($this->dtgProjects, $objControlId);
					$objControl->Width = 25;
					$objControl->Text = '+'.count($team_member);
					$objControl->CssClass = 'inputbutton';
				
					// Pass the id of the bounded item just for other process
					// on click event
				
					$objControl->ActionParameter = $objProject->Id;
				
					// Add event on click the toogle button
					$objControl->AddAction(new QClickEvent(), 
								   new QAjaxAction(
								   'btnToggleRecordsSummary_Click',
								   $this->dtgProjects->WaitIcon));
				}
			}
			// We pass the parameter of "false" to make sure the control doesn't render
			// itself RIGHT HERE - that it instead returns its string rendering result.
			return $objControl->Render(false);
		}
			
			
		// Clicking the toogle button...
		public function btnToggleRecordsSummary_Click($strFormId, $strControlId, $strParameter) {			
			// First get the button himself for change '+' to '-'
			$srcControl = $this->GetControl($strControlId);
			
			$intProjectId = intval($strParameter);
			
			// Look for our child QDatagrid if is render...
			$objControlId = 'ucRecordsSummary' . $intProjectId;
			$objControl = $this->GetControl($objControlId);
			
			// magia 2011-07  inserito test per presenza child
			$team_member = Person::LoadArrayByProjectAsTeamMember($intProjectId);
			if (count($team_member)> 0) {
				if ($objControl) {
				// Ask if our Child DataGrid is visible...
					if ($objControl->Visible) {
						// Make it desapear ...
						$objControl->Visible = false;
						$srcControl->Text = '+';
					} else {
						// Or make it appear...
						$objControl->Visible = true;
						$srcControl->Text = '-';
					}

					// Important! Refresh the parent QDataGrid...
					$this->dtgProjects->Refresh();
				}
			}
		}
			
		// Ladies and Gentlemen... Our Child QDataGrid...
		public function render_ucRecordsSummary(Project $objProject) {
			$objControlId = 'ucRecordsSummary' . $objProject->Id;
			
			if (!$objControl = $this->GetControl($objControlId)) {
				// Create the User Control Child QDataGrid passing the
				// parent, in this case Master QDataGrid and the unique id.
				$objControl = new RecordsSummary($this->dtgProjects, $objProject, $objControlId);
				
				// Put invisible at the begging, the toogle button is gonna do the job
				// test - $objControl->Visible = true;
				$objControl->Visible = false;
			}
			
			return $objControl->Render(false);
		}	
	}

	// Go ahead and run this form object to generate the page and event handlers, 
	// implicitly using project_list.tpl.php as the included HTML template file
	ProjectListForm::Run('ProjectListForm');
?>