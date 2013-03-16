<?php
	/**
	 * This is a quick-and-dirty draft QForm object to do Create, Edit, and Delete functionality
	 * of the Project class.  It uses the code-generated
	 * ProjectMetaControl class, which has meta-methods to help with
	 * easily creating/defining controls to modify the fields of a Project columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both project_edit.php AND
	 * project_edit.tpl.php out of this Form Drafts directory.
	 *
	 * @package My QCubed Application
	 * @subpackage FormBaseObjects
	 */
	abstract class ProjectEditFormBase extends QForm {
		// Local instance of the ProjectMetaControl
		/**
		 * @var ProjectMetaControlGen mctProject
		 */
		protected $mctProject;

		// Controls for Project's Data Fields
		protected $lblId;
		protected $lstProjectStatusType;
		protected $lstManagerPerson;
		protected $txtName;
		protected $txtDescription;
		protected $calStartDate;
		protected $calEndDate;
		protected $txtBudget;
		protected $txtSpent;

		// Other ListBoxes (if applicable) via Unique ReverseReferences and ManyToMany References
		protected $dtgProjectsAsRelated;
		protected $dtgParentProjectsAsRelated;
		protected $dtgPeopleAsTeamMember;

		// Other Controls
		/**
		 * @var QButton Save
		 */
		protected $btnSave;
		/**
		 * @var QButton Delete
		 */
		protected $btnDelete;
		/**
		 * @var QButton Cancel
		 */
		protected $btnCancel;

		// Create QForm Event Handlers as Needed

//		protected function Form_Exit() {}
//		protected function Form_Load() {}
//		protected function Form_PreRender() {}

		protected function Form_Run() {
			parent::Form_Run();
		}

		protected function Form_Create() {
			parent::Form_Create();

			// Use the CreateFromPathInfo shortcut (this can also be done manually using the ProjectMetaControl constructor)
			// MAKE SURE we specify "$this" as the MetaControl's (and thus all subsequent controls') parent
			$this->mctProject = ProjectMetaControl::CreateFromPathInfo($this);

			// Call MetaControl's methods to create qcontrols based on Project's data fields
			$this->lblId = $this->mctProject->lblId_Create();
			$this->lstProjectStatusType = $this->mctProject->lstProjectStatusType_Create();
			$this->lstManagerPerson = $this->mctProject->lstManagerPerson_Create();
			$this->txtName = $this->mctProject->txtName_Create();
			$this->txtDescription = $this->mctProject->txtDescription_Create();
			$this->calStartDate = $this->mctProject->calStartDate_Create();
			$this->calEndDate = $this->mctProject->calEndDate_Create();
			$this->txtBudget = $this->mctProject->txtBudget_Create();
			$this->txtSpent = $this->mctProject->txtSpent_Create();
			$this->dtgProjectsAsRelated = $this->mctProject->dtgProjectsAsRelated_Create();
			$this->dtgParentProjectsAsRelated = $this->mctProject->dtgParentProjectsAsRelated_Create();
			$this->dtgPeopleAsTeamMember = $this->mctProject->dtgPeopleAsTeamMember_Create();

			// Create Buttons and Actions on this Form
			$this->btnSave = new QButton($this);
			$this->btnSave->Text = QApplication::Translate('Save');
			$this->btnSave->AddAction(new QClickEvent(), new QAjaxAction('btnSave_Click'));
			$this->btnSave->CausesValidation = true;

			$this->btnCancel = new QButton($this);
			$this->btnCancel->Text = QApplication::Translate('Cancel');
			$this->btnCancel->AddAction(new QClickEvent(), new QAjaxAction('btnCancel_Click'));

			$this->btnDelete = new QButton($this);
			$this->btnDelete->Text = QApplication::Translate('Delete');
			$this->btnDelete->AddAction(new QClickEvent(), new QConfirmAction(sprintf(QApplication::Translate('Are you SURE you want to DELETE this %s?'), QApplication::Translate('Project'))));
			$this->btnDelete->AddAction(new QClickEvent(), new QAjaxAction('btnDelete_Click'));
			$this->btnDelete->Visible = $this->mctProject->EditMode;
		}

		/**
		 * This Form_Validate event handler allows you to specify any custom Form Validation rules.
		 * It will also Blink() on all invalid controls, as well as Focus() on the top-most invalid control.
		 */
		protected function Form_Validate() {
			// By default, we report the result of validation from the parent
			$blnToReturn = parent::Form_Validate();

			// Custom Validation Rules
			// TODO: Be sure to set $blnToReturn to false if any custom validation fails!
			
			$blnFocused = false;
			foreach ($this->GetErrorControls() as $objControl) {
				// Set Focus to the top-most invalid control
				if (!$blnFocused) {
					$objControl->Focus();
					$blnFocused = true;
				}

				// Blink on ALL invalid controls
				$objControl->Blink();
			}

			return $blnToReturn;
		}

		// Button Event Handlers

		protected function btnSave_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Save" processing to the ProjectMetaControl
			$this->mctProject->SaveProject();
			$this->RedirectToListPage();
		}

		protected function btnDelete_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Delete" processing to the ProjectMetaControl
			$this->mctProject->DeleteProject();
			$this->RedirectToListPage();
		}

		protected function btnCancel_Click($strFormId, $strControlId, $strParameter) {
			$this->RedirectToListPage();
		}

		// Other Methods

		protected function RedirectToListPage() {
			QApplication::Redirect(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/project_list.php');
		}
	}
?>
