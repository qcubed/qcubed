/*
Here is the Child QDataGrid...
*/

<?php
	// Load the QCubed Development Framework
	require_once('../../qcubed.inc.php');
	require ('AddressListPanel.class.php');
	// Load User Controls -> Load Other QDataGrids Child of this one
	// Is this not awesome? QdataGrid can do everything!!
	
	// As you can see thie QDataGrid extends the QPanel not a QForm
	class RecordsSummary extends QPanel {		
		// QDatagrid Records Summary
		public $dtgRecordsSummary;
		
		// dtgProjects -> this is our Master QDataGrid
		protected $objParentObject;
		
		// Protected Objects
		protected $objProject;
		protected $strType;
		
		// in the contructor pass the item bounded too just for other process
		public function __construct($objParentObject, Project $objProject, $strControlId = null) {			
			try {
				parent::__construct($objParentObject, $strControlId);
				
				// Watch out for template later gonna talk about it,
				// need a trick to look good 
				// (insert the child content as row in table already present for Master
				//   close colums -insert row - insert child - close row - open column 
				//  </td> <tr><td> render content of this child </td> </tr> <td> )
				$this->Template = 'records.summary.tpl.php';
				
				// Setting local the MAster QDataGrid to refresh on
				// Saves on the Child DataGrid..
				$this->objParentObject = $objParentObject;
				$this->objProject = $objProject;
				
				// Create the child DataGrid as a normal QDataGrid 
				$this->dtgRecordsSummary = new PersonDataGrid($this);
				$this->dtgRecordsSummary->ShowFilter = false;
				// pagination
				$this->dtgRecordsSummary->Paginator = new QPaginator($this->dtgRecordsSummary);

				$this->dtgRecordsSummary->ItemsPerPage = 5;

				$this->dtgRecordsSummary->SetDataBinder('dtgRecordsSummary_Bind', $this);
				
				// Need another Child QDataGrid? ok, add their toogle button...
				/*****************************/
				// we have now another child  in our example (Team member addresses)
				/*****************************/
				$this->dtgRecordsSummary->AddColumn(
								new QDataGridColumn('',
												'<?= $_CONTROL->ParentControl->render_btnToggleRecords($_CONTROL, $_ITEM) ?>',
												'HtmlEntities=false', 
												'Width=1px'));

				// Add some QDataGrid data to show...
				$this->dtgRecordsSummary->AddColumn(
									new QDataGridColumn('Person', '<?= $_ITEM->FirstName ?> '. '<?= $_ITEM->LastName ?> '));
				$this->dtgRecordsSummary->AddColumn(
									new QDataGridColumn('Id', '<?= $_ITEM->Id ?>', 'Width=120' ));
				
				// we don't need another child  in our example 
				/*************************************/
				// Add if you need another column with the other child QDataGrid to show if you need
				// now we have a child - addresses Qpanel  
				$this->dtgRecordsSummary->AddColumn(
									new QDataGridColumn('','<?= $_CONTROL->ParentControl->render_ucRecords($_CONTROL, $_ITEM) ?>','HtmlEntities=false','Width=1px'));
				/**************************************/
				
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function dtgRecordsSummary_Bind() {
			$objConditions = $this->dtgRecordsSummary->Conditions;

			// setup $objClauses array 
			$objClauses = array();

			// add OrderByClause to the $objClauses array 
			// if ($objClause = $this->dtgRecordsSummary->OrderByClause){
			if ($objClause = $this->dtgRecordsSummary->OrderByClause) {
				array_push($objClauses, $objClause);             
			}

			// add LimitByClause to the $objClauses array 
			//if ($objClause = $this->dtgRecordsSummary->LimitClause)
			if ($objClause = $this->dtgRecordsSummary->LimitClause)	
				array_push($objClauses, $objClause);


			$team_array = $this->objProject->GetPersonAsTeamMemberArray
												(QQ::Clause($this->dtgRecordsSummary->OrderByClause));
			$this->dtgRecordsSummary->TotalItemCount = count($team_array);

			$this->dtgRecordsSummary->DataSource = $this->objProject->GetPersonAsTeamMemberArray
												(QQ::Clause($this->dtgRecordsSummary->OrderByClause), $objClauses);

		}
          
		/***************************************
		/ 
		/  Addresses of Team Member of project
		/
		****************************************/
		
		// Render other toggle..
		public function render_btnToggleRecords($parControl, $strType) {
			
			$strControlId = 'btnToggleRecordsforaddressofperson'.$strType->Id .'ofproject'.$this->objProject->Id;
			
			if (!$objControl = $parControl->GetChildControl($strControlId)) {
				// But in this case the parent control of the button
				// would be this child QDataGrid, don't forget that...
				
				$person_addresses = Address::QueryCount(
					QQ::Equal(QQN::Address()->PersonId, $strType->Id));
				
				if ($person_addresses > 0 ) {
					$objControl = new QButton($parControl, $strControlId);
					$objControl->Width = 20;
					$objControl->Text = '+';
					$objControl->CssClass = 'inputbutton';
					$objControl->ActionParameter = "$strType->Id";
				
					// Important! for a better coding we want to all
					// actions referer to the child QdataGris stay
					// in the child Qdatagrid, so the actions are now
					// QAjaxControlAction or QServerControlAction, were the
					// controlId parameter is $this, becaouse in $this class
					// is defined the event for this button... kind of easy,
					// and clean.
				
					$objControl->AddAction(	
									new QClickEvent(), 
									new QAjaxControlAction($this,
														'btnToggleRecords_Click',
														$this->objParentObject->WaitIcon)
										);
			    }else {
					// No addresses to expand; we'll use an empty label control to signify that 
					$objControl = new QLabel($parControl, $strControlId);
					$objControl->Text = '';
				}

				
			}
			
			// We pass the parameter of "false" to make sure the control doesn't render
			// itself RIGHT HERE - that it instead returns its string rendering result.
			return $objControl->Render(false);
         
		}

		// Button press make other child QDataGrid Appear..
		public function btnToggleRecords_Click($strFormId, $strControlId, $strParameter) {
			
			$srcControl = $this->Form->GetControl($strControlId);
			$parControl = $srcControl->ParentControl;
			$strType = $strParameter;
			
			$objControlId = 'ucRecords' . $this->objProject->Id . $strType;
			if ($objControl = $parControl->GetChildControl($objControlId)) {
				if ($objControl->Visible) {
					$objControl->Visible = false;
					$srcControl->Text = '+';
				} else {
					$objControl->Visible = true;
					$srcControl->Text = '-';
				}
				
				// And refresh the Child QdataGrid this time...
				// we need set pagenumber = 1 ? I will try remove next line ...
				$this->dtgRecordsSummary->PageNumber = 1;

				$this->dtgRecordsSummary->Refresh();
			}
		}
		
		// Create another child if you want... follow the same exactly
		// idea as applid in Master QdataGrid...
		public function render_ucRecords($parControl, $strType) {
			
			$this->strType = $strType;
			//Qfirebug::warn('ucRecords_'.$strType);
			$strControlId = 'ucRecords' . $this->objProject->Id . $strType->Id;
			if (!$objControl = $parControl->GetChildControl($strControlId)) {
				$objControl = new AddressListPanel($this->dtgRecordsSummary,$this->strType,$strControlId);
				$objControl->Visible = false;
			     	
			}
			return $objControl->Render(false);
		}
	}
?>
