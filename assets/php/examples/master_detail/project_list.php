<?php
	// Load the QCubed Development Framework
	require('../qcubed.inc.php');
	
	// our child QPanel
	require ('./records.summary.php');
		
	class ProjectListForm extends QForm {
		// Local instance of the Meta DataGrid to list Projects
		protected $dtgProjects;
					
		protected function Form_Create() {
			// Instantiate the DataGrid
			$this->dtgProjects = new QDataGrid($this);

			// Style the DataGrid
			//$this->dtgProjects->CssClass = 'datagrid';
			$this->dtgProjects->AlternateRowCssClass = 'alternate';

			// Add Pagination
			$this->dtgProjects->Paginator = new QPaginator($this->dtgProjects);
			$this->dtgProjects->ItemsPerPage = 3;

			// Add columns
			
			// Create a column that will hold a toggle button. We will need to manually draw the content of the cell.
			$col = $this->dtgProjects->CreateCallableColumn('', [$this, 'render_btnToggleRecordsSummary']);
			$col->HtmlEntities = false;
			$col->CellStyler->Width = "1%";

			$this->dtgProjects->CreateNodeColumn('Id', QQN::Project()->Id);
			$this->dtgProjects->CreateNodeColumn('Name', QQN::Project()->Name);
			$this->dtgProjects->CreateNodeColumn('Status', QQN::Project()->ProjectStatusType);
			$this->dtgProjects->CreateNodeColumn('Description', QQN::Project()->Description);
			$this->dtgProjects->CreateNodeColumn('Start Date', QQN::Project()->StartDate);
			$this->dtgProjects->CreateNodeColumn('End Date', QQN::Project()->EndDate);
			$this->dtgProjects->CreateNodeColumn('Budget', QQN::Project()->Budget);
			$this->dtgProjects->CreateNodeColumn('Spent', QQN::Project()->Spent);

			// Create a column that will hold a child datagrid

			$col = $this->dtgProjects->CreateCallableColumn('', [$this, 'render_ucRecordsSummary']);
			$col->HtmlEntities = false;
			$col->CellStyler->Width = 0;

			// Specify the Datagrid's Data Binder method
			$this->dtgProjects->SetDataBinder('dtgProjects_Bind');

			// For purposes of this example, add a css file that styles the table.
			// Normally you would include your global style sheets in your tpl file or header.inc.php file.
			$this->dtgProjects->AddCssFile(__QCUBED_ASSETS__ . '/php/examples/master_detail/styles.css');
		}

		protected function dtgProjects_Bind() {
			$this->dtgProjects->TotalItemCount = Project::QueryCount(QQ::All());

			// If a column is selected to be sorted, and if that column has a OrderByClause set on it, then let's add
			// the OrderByClause to the $objClauses array
			if ($objClause = $this->dtgProjects->OrderByClause) {
				$objClauses[] = $objClause;
			}

			// Add the LimitClause information, as well
			if ($objClause = $this->dtgProjects->LimitClause) {
				$objClauses[] = $objClause;
			}

			$this->dtgProjects->DataSource = Project::LoadAll($objClauses);
		}
		
		// Function to render our toggle button column
		// As you can see we pass as a parameter the item binded in the
		// row of QDataGrid
		public function render_btnToggleRecordsSummary(Project $objProject) {
			// Create their unique id...
			$objControlId = 'btnToggleRecordsSummary' . $objProject->Id;
			
			if (!$objControl = $this->GetControl($objControlId)) {			
				$intTeamMemberCount = Person::CountByProjectAsTeamMember($objProject->Id);
				if ($intTeamMemberCount > 0) {

					// If not exists create our toggle button who his parent
					// is our master QDataGrid...
					$objControl = new QButton($this->dtgProjects, $objControlId);
					$objControl->Width = 25;
					$objControl->Text = '+' . $intTeamMemberCount;
					$objControl->CssClass = 'inputbutton';
				
					// Pass the id of the bounded item just for other process
					// on click event
				
					$objControl->ActionParameter = $objProject->Id;
				
					// Add event on click the toogle button
					$objControl->AddAction(new QClickEvent(), new QAjaxAction( 'btnToggleRecordsSummary_Click'));
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
			
			// Look for our child datagrid if is render...
			$objControlId = 'ucRecordsSummary' . $intProjectId;
			$objControl = $this->GetControl($objControlId);

			$intTeamMemberCount = Person::CountByProjectAsTeamMember($intProjectId);
			if ($intTeamMemberCount > 0) {
				if ($objControl) {
				// Ask if our child datagrid is visible...
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
			
		// Draw the child datagrid inside of a cell of the parent datagrid
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