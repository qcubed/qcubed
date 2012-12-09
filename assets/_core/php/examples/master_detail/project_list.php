<?php
	// Load the QCubed Development Framework
	require('../qcubed.inc.php');
	
	// our child QPanel
	require ('./records.summary.php');
		
	class ProjectListForm extends QForm {
		// Local instance of the Meta DataGrid to list Projects
		protected $dtgProjects;
					
		protected function Form_Create() {
			// Instantiate the Meta DataGrid
			//$this->objDefaultWaitIcon = new QWaitIcon($this);
			$this->dtgProjects = new ProjectDataGrid($this);
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
			$colId = $this->dtgProjects->MetaAddColumn('Id');
			$colId->FilterType = "";

			$this->dtgProjects->MetaAddColumn('Name', 'Name=Project');
			$this->dtgProjects->MetaAddTypeColumn('ProjectStatusTypeId', 'ProjectStatusType');
			//$this->dtgProjects->MetaAddColumn(QQN::Project()->ManagerPerson);
			$this->dtgProjects->MetaAddColumn('Description');
			
			// remove filter from field
			//$this->dtgProjects->MetaAddColumn('StartDate');
			$colStartDate = $this->dtgProjects->MetaAddColumn('StartDate');
			$colStartDate->FilterType = "";
			
			// remove filter from field
			//$this->dtgProjects->MetaAddColumn('EndDate');
			$colEndDate = $this->dtgProjects->MetaAddColumn('EndDate');
			$colEndDate->FilterType = "";
			
			// remove filter from field
			// $this->dtgProjects->MetaAddColumn('Budget');
			$colBudget = $this->dtgProjects->MetaAddColumn('Budget');
			$colBudget->FilterType = "";
			
			// remove filter from field
			// $this->dtgProjects->MetaAddColumn('Spent');
			$colSpent = $this->dtgProjects->MetaAddColumn('Spent');
			$colSpent->FilterType = ""; 
			
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
			$objStyle->BackColor = '#eaeaea';

			$objStyle = $this->dtgProjects->HeaderRowStyle;
			$objStyle->ForeColor = 'white';
			$objStyle->BackColor = '#000066';

			// Because browsers will apply different styles/colors for LINKs
			// We must explicitly define the ForeColor for the HeaderLink.
			// The header row turns into links when the column can be sorted.
			$objStyle = $this->dtgProjects->HeaderLinkStyle;
			$objStyle->ForeColor = 'white';
		}
		
		// Function to render our toggle button column
		// As you can see we pass as a parameter the item binded in the
		// row of QDataGrid
		public function render_btnToggleRecordsSummary(Project $objProject) {			
			// Create their unique id...
			$objControlId = 'btnToggleRecordsSummary' . $objProject->Id;
			
			if (!$objControl = $this->GetControl($objControlId)) {			
				// If not exists create our toggle button who his parent
				// is our master QDataGrid...
				$objControl = new QButton($this->dtgProjects, $objControlId);
				$objControl->Width = 20;
				$objControl->Text = '+';
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
			
		// Ladies and Gentlemen... Our Child QDataGrid...
		public function render_ucRecordsSummary(Project $objProject) {
			$objControlId = 'ucRecordsSummary' . $objProject->Id;
			
			if (!$objControl = $this->GetControl($objControlId)) {
				// Create the User Control Child QDataGrid passing the
				// parent, in this case Master QDataGrid and the unique id.
				$objControl = new RecordsSummary($this->dtgProjects, $objProject, $objControlId);
				
				// Put invisible at the begging, the toogle button is gonna do the job
				$objControl->Visible = false;
			}
			
			return $objControl->Render(false);
		}	
	}

	// Go ahead and run this form object to generate the page and event handlers, 
	// implicitly using project_list.tpl.php as the included HTML template file
	ProjectListForm::Run('ProjectListForm');
?>