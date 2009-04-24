<?php
	/**
	 * This is a MetaControl class, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality
	 * of the Project class.  This code-generated class
	 * contains all the basic elements to help a QPanel or QForm display an HTML form that can
	 * manipulate a single Project object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a ProjectMetaControl
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent
	 * code re-generation.
	 * 
	 * @package My Application
	 * @subpackage MetaControls
	 * property-read Project $Project the actual Project data class being edited
	 * property QLabel $IdControl
	 * property-read QLabel $IdLabel
	 * property QListBox $ProjectStatusTypeIdControl
	 * property-read QLabel $ProjectStatusTypeIdLabel
	 * property QListBox $ManagerPersonIdControl
	 * property-read QLabel $ManagerPersonIdLabel
	 * property QTextBox $NameControl
	 * property-read QLabel $NameLabel
	 * property QTextBox $DescriptionControl
	 * property-read QLabel $DescriptionLabel
	 * property QDateTimePicker $StartDateControl
	 * property-read QLabel $StartDateLabel
	 * property QDateTimePicker $EndDateControl
	 * property-read QLabel $EndDateLabel
	 * property QFloatTextBox $BudgetControl
	 * property-read QLabel $BudgetLabel
	 * property QFloatTextBox $SpentControl
	 * property-read QLabel $SpentLabel
	 * property QListBox $ParentProjectAsRelatedControl
	 * property-read QLabel $ParentProjectAsRelatedLabel
	 * property QListBox $ProjectAsRelatedControl
	 * property-read QLabel $ProjectAsRelatedLabel
	 * property QListBox $PersonAsTeamMemberControl
	 * property-read QLabel $PersonAsTeamMemberLabel
	 * property-read string $TitleVerb a verb indicating whether or not this is being edited or created
	 * property-read boolean $EditMode a boolean indicating whether or not this is being edited or created
	 */

	class ProjectMetaControlGen extends QBaseClass {
		// General Variables
		protected $objProject;
		protected $objParentObject;
		protected $strTitleVerb;
		protected $blnEditMode;

		// Controls that allow the editing of Project's individual data fields
		protected $lblId;
		protected $lstProjectStatusType;
		protected $lstManagerPerson;
		protected $txtName;
		protected $txtDescription;
		protected $calStartDate;
		protected $calEndDate;
		protected $txtBudget;
		protected $txtSpent;

		// Controls that allow the viewing of Project's individual data fields
		protected $lblProjectStatusTypeId;
		protected $lblManagerPersonId;
		protected $lblName;
		protected $lblDescription;
		protected $lblStartDate;
		protected $lblEndDate;
		protected $lblBudget;
		protected $lblSpent;

		// QListBox Controls (if applicable) to edit Unique ReverseReferences and ManyToMany References
		protected $lstParentProjectsAsRelated;
		protected $strParentProjectAsRelatedGlue;
		protected $lstProjectsAsRelated;
		protected $strProjectAsRelatedGlue;
		protected $lstPeopleAsTeamMember;
		protected $strPersonAsTeamMemberGlue;

		// QLabel Controls (if applicable) to view Unique ReverseReferences and ManyToMany References
		protected $lblParentProjectsAsRelated;
		protected $lblProjectsAsRelated;
		protected $lblPeopleAsTeamMember;


		/**
		 * Main constructor.  Constructor OR static create methods are designed to be called in either
		 * a parent QPanel or the main QForm when wanting to create a
		 * ProjectMetaControl to edit a single Project object within the
		 * QPanel or QForm.
		 *
		 * This constructor takes in a single Project object, while any of the static
		 * create methods below can be used to construct based off of individual PK ID(s).
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this ProjectMetaControl
		 * @param Project $objProject new or existing Project object
		 */
		 public function __construct($objParentObject, Project $objProject) {
			// Setup Parent Object (e.g. QForm or QPanel which will be using this ProjectMetaControl)
			$this->objParentObject = $objParentObject;

			// Setup linked Project object
			$this->objProject = $objProject;

			// Figure out if we're Editing or Creating New
			if ($this->objProject->__Restored) {
				$this->strTitleVerb = QApplication::Translate('Edit');
				$this->blnEditMode = true;
			} else {
				$this->strTitleVerb = QApplication::Translate('Create');
				$this->blnEditMode = false;
			}
		 }

		/**
		 * Static Helper Method to Create using PK arguments
		 * You must pass in the PK arguments on an object to load, or leave it blank to create a new one.
		 * If you want to load via QueryString or PathInfo, use the CreateFromQueryString or CreateFromPathInfo
		 * static helper methods.  Finally, specify a CreateType to define whether or not we are only allowed to 
		 * edit, or if we are also allowed to create a new one, etc.
		 * 
		 * @param mixed $objParentObject QForm or QPanel which will be using this ProjectMetaControl
		 * @param integer $intId primary key value
		 * @param QMetaControlCreateType $intCreateType rules governing Project object creation - defaults to CreateOrEdit
 		 * @return ProjectMetaControl
		 */
		public static function Create($objParentObject, $intId = null, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			// Attempt to Load from PK Arguments
			if (strlen($intId)) {
				$objProject = Project::Load($intId);

				// Project was found -- return it!
				if ($objProject)
					return new ProjectMetaControl($objParentObject, $objProject);

				// If CreateOnRecordNotFound not specified, throw an exception
				else if ($intCreateType != QMetaControlCreateType::CreateOnRecordNotFound)
					throw new QCallerException('Could not find a Project object with PK arguments: ' . $intId);

			// If EditOnly is specified, throw an exception
			} else if ($intCreateType == QMetaControlCreateType::EditOnly)
				throw new QCallerException('No PK arguments specified');

			// If we are here, then we need to create a new record
			return new ProjectMetaControl($objParentObject, new Project());
		}

		/**
		 * Static Helper Method to Create using PathInfo arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this ProjectMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing Project object creation - defaults to CreateOrEdit
		 * @return ProjectMetaControl
		 */
		public static function CreateFromPathInfo($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::PathInfo(0);
			return ProjectMetaControl::Create($objParentObject, $intId, $intCreateType);
		}

		/**
		 * Static Helper Method to Create using QueryString arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this ProjectMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing Project object creation - defaults to CreateOrEdit
		 * @return ProjectMetaControl
		 */
		public static function CreateFromQueryString($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::QueryString('intId');
			return ProjectMetaControl::Create($objParentObject, $intId, $intCreateType);
		}



		///////////////////////////////////////////////
		// PUBLIC CREATE and REFRESH METHODS
		///////////////////////////////////////////////

		/**
		 * Create and setup QLabel lblId
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblId_Create($strControlId = null) {
			$this->lblId = new QLabel($this->objParentObject, $strControlId);
			$this->lblId->Name = QApplication::Translate('Id');
			if ($this->blnEditMode)
				$this->lblId->Text = $this->objProject->Id;
			else
				$this->lblId->Text = 'N/A';
			return $this->lblId;
		}

		/**
		 * Create and setup QListBox lstProjectStatusType
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function lstProjectStatusType_Create($strControlId = null) {
			$this->lstProjectStatusType = new QListBox($this->objParentObject, $strControlId);
			$this->lstProjectStatusType->Name = QApplication::Translate('Project Status Type');
			$this->lstProjectStatusType->Required = true;
			foreach (ProjectStatusType::$NameArray as $intId => $strValue)
				$this->lstProjectStatusType->AddItem(new QListItem($strValue, $intId, $this->objProject->ProjectStatusTypeId == $intId));
			return $this->lstProjectStatusType;
		}

		/**
		 * Create and setup QLabel lblProjectStatusTypeId
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblProjectStatusTypeId_Create($strControlId = null) {
			$this->lblProjectStatusTypeId = new QLabel($this->objParentObject, $strControlId);
			$this->lblProjectStatusTypeId->Name = QApplication::Translate('Project Status Type');
			$this->lblProjectStatusTypeId->Text = ($this->objProject->ProjectStatusTypeId) ? ProjectStatusType::$NameArray[$this->objProject->ProjectStatusTypeId] : null;
			$this->lblProjectStatusTypeId->Required = true;
			return $this->lblProjectStatusTypeId;
		}

		/**
		 * Create and setup QListBox lstManagerPerson
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function lstManagerPerson_Create($strControlId = null) {
			$this->lstManagerPerson = new QListBox($this->objParentObject, $strControlId);
			$this->lstManagerPerson->Name = QApplication::Translate('Manager Person');
			$this->lstManagerPerson->AddItem(QApplication::Translate('- Select One -'), null);
			$objManagerPersonArray = Person::LoadAll();
			if ($objManagerPersonArray) foreach ($objManagerPersonArray as $objManagerPerson) {
				$objListItem = new QListItem($objManagerPerson->__toString(), $objManagerPerson->Id);
				if (($this->objProject->ManagerPerson) && ($this->objProject->ManagerPerson->Id == $objManagerPerson->Id))
					$objListItem->Selected = true;
				$this->lstManagerPerson->AddItem($objListItem);
			}
			return $this->lstManagerPerson;
		}

		/**
		 * Create and setup QLabel lblManagerPersonId
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblManagerPersonId_Create($strControlId = null) {
			$this->lblManagerPersonId = new QLabel($this->objParentObject, $strControlId);
			$this->lblManagerPersonId->Name = QApplication::Translate('Manager Person');
			$this->lblManagerPersonId->Text = ($this->objProject->ManagerPerson) ? $this->objProject->ManagerPerson->__toString() : null;
			return $this->lblManagerPersonId;
		}

		/**
		 * Create and setup QTextBox txtName
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function txtName_Create($strControlId = null) {
			$this->txtName = new QTextBox($this->objParentObject, $strControlId);
			$this->txtName->Name = QApplication::Translate('Name');
			$this->txtName->Text = $this->objProject->Name;
			$this->txtName->Required = true;
			$this->txtName->MaxLength = Project::NameMaxLength;
			return $this->txtName;
		}

		/**
		 * Create and setup QLabel lblName
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblName_Create($strControlId = null) {
			$this->lblName = new QLabel($this->objParentObject, $strControlId);
			$this->lblName->Name = QApplication::Translate('Name');
			$this->lblName->Text = $this->objProject->Name;
			$this->lblName->Required = true;
			return $this->lblName;
		}

		/**
		 * Create and setup QTextBox txtDescription
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function txtDescription_Create($strControlId = null) {
			$this->txtDescription = new QTextBox($this->objParentObject, $strControlId);
			$this->txtDescription->Name = QApplication::Translate('Description');
			$this->txtDescription->Text = $this->objProject->Description;
			$this->txtDescription->TextMode = QTextMode::MultiLine;
			return $this->txtDescription;
		}

		/**
		 * Create and setup QLabel lblDescription
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblDescription_Create($strControlId = null) {
			$this->lblDescription = new QLabel($this->objParentObject, $strControlId);
			$this->lblDescription->Name = QApplication::Translate('Description');
			$this->lblDescription->Text = $this->objProject->Description;
			return $this->lblDescription;
		}

		/**
		 * Create and setup QDateTimePicker calStartDate
		 * @param string $strControlId optional ControlId to use
		 * @return QDateTimePicker
		 */
		public function calStartDate_Create($strControlId = null) {
			$this->calStartDate = new QDateTimePicker($this->objParentObject, $strControlId);
			$this->calStartDate->Name = QApplication::Translate('Start Date');
			$this->calStartDate->DateTime = $this->objProject->StartDate;
			$this->calStartDate->DateTimePickerType = QDateTimePickerType::Date;
			return $this->calStartDate;
		}

		/**
		 * Create and setup QLabel lblStartDate
		 * @param string $strControlId optional ControlId to use
		 * @param string $strDateTimeFormat optional DateTimeFormat to use
		 * @return QLabel
		 */
		public function lblStartDate_Create($strControlId = null, $strDateTimeFormat = null) {
			$this->lblStartDate = new QLabel($this->objParentObject, $strControlId);
			$this->lblStartDate->Name = QApplication::Translate('Start Date');
			$this->strStartDateDateTimeFormat = $strDateTimeFormat;
			$this->lblStartDate->Text = sprintf($this->objProject->StartDate) ? $this->objProject->StartDate->__toString($this->strStartDateDateTimeFormat) : null;
			return $this->lblStartDate;
		}

		protected $strStartDateDateTimeFormat;


		/**
		 * Create and setup QDateTimePicker calEndDate
		 * @param string $strControlId optional ControlId to use
		 * @return QDateTimePicker
		 */
		public function calEndDate_Create($strControlId = null) {
			$this->calEndDate = new QDateTimePicker($this->objParentObject, $strControlId);
			$this->calEndDate->Name = QApplication::Translate('End Date');
			$this->calEndDate->DateTime = $this->objProject->EndDate;
			$this->calEndDate->DateTimePickerType = QDateTimePickerType::Date;
			return $this->calEndDate;
		}

		/**
		 * Create and setup QLabel lblEndDate
		 * @param string $strControlId optional ControlId to use
		 * @param string $strDateTimeFormat optional DateTimeFormat to use
		 * @return QLabel
		 */
		public function lblEndDate_Create($strControlId = null, $strDateTimeFormat = null) {
			$this->lblEndDate = new QLabel($this->objParentObject, $strControlId);
			$this->lblEndDate->Name = QApplication::Translate('End Date');
			$this->strEndDateDateTimeFormat = $strDateTimeFormat;
			$this->lblEndDate->Text = sprintf($this->objProject->EndDate) ? $this->objProject->EndDate->__toString($this->strEndDateDateTimeFormat) : null;
			return $this->lblEndDate;
		}

		protected $strEndDateDateTimeFormat;


		/**
		 * Create and setup QFloatTextBox txtBudget
		 * @param string $strControlId optional ControlId to use
		 * @return QFloatTextBox
		 */
		public function txtBudget_Create($strControlId = null) {
			$this->txtBudget = new QFloatTextBox($this->objParentObject, $strControlId);
			$this->txtBudget->Name = QApplication::Translate('Budget');
			$this->txtBudget->Text = $this->objProject->Budget;
			return $this->txtBudget;
		}

		/**
		 * Create and setup QLabel lblBudget
		 * @param string $strControlId optional ControlId to use
		 * @param string $strFormat optional sprintf format to use
		 * @return QLabel
		 */
		public function lblBudget_Create($strControlId = null, $strFormat = null) {
			$this->lblBudget = new QLabel($this->objParentObject, $strControlId);
			$this->lblBudget->Name = QApplication::Translate('Budget');
			$this->lblBudget->Text = $this->objProject->Budget;
			$this->lblBudget->Format = $strFormat;
			return $this->lblBudget;
		}

		/**
		 * Create and setup QFloatTextBox txtSpent
		 * @param string $strControlId optional ControlId to use
		 * @return QFloatTextBox
		 */
		public function txtSpent_Create($strControlId = null) {
			$this->txtSpent = new QFloatTextBox($this->objParentObject, $strControlId);
			$this->txtSpent->Name = QApplication::Translate('Spent');
			$this->txtSpent->Text = $this->objProject->Spent;
			return $this->txtSpent;
		}

		/**
		 * Create and setup QLabel lblSpent
		 * @param string $strControlId optional ControlId to use
		 * @param string $strFormat optional sprintf format to use
		 * @return QLabel
		 */
		public function lblSpent_Create($strControlId = null, $strFormat = null) {
			$this->lblSpent = new QLabel($this->objParentObject, $strControlId);
			$this->lblSpent->Name = QApplication::Translate('Spent');
			$this->lblSpent->Text = $this->objProject->Spent;
			$this->lblSpent->Format = $strFormat;
			return $this->lblSpent;
		}

		/**
		 * Create and setup QListBox lstParentProjectsAsRelated
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function lstParentProjectsAsRelated_Create($strControlId = null) {
			$this->lstParentProjectsAsRelated = new QListBox($this->objParentObject, $strControlId);
			$this->lstParentProjectsAsRelated->Name = QApplication::Translate('Parent Projects As Related');
			$this->lstParentProjectsAsRelated->SelectionMode = QSelectionMode::Multiple;
			$objAssociatedArray = $this->objProject->GetParentProjectAsRelatedArray();
			$objProjectArray = Project::LoadAll();
			if ($objProjectArray) foreach ($objProjectArray as $objProject) {
				$objListItem = new QListItem($objProject->__toString(), $objProject->Id);
				foreach ($objAssociatedArray as $objAssociated) {
					if ($objAssociated->Id == $objProject->Id)
						$objListItem->Selected = true;
				}
				$this->lstParentProjectsAsRelated->AddItem($objListItem);
			}
			return $this->lstParentProjectsAsRelated;
		}

		/**
		 * Create and setup QLabel lblParentProjectsAsRelated
		 * @param string $strControlId optional ControlId to use
		 * @param string $strGlue glue to display in between each associated object
		 * @return QLabel
		 */
		public function lblParentProjectsAsRelated_Create($strControlId = null, $strGlue = ', ') {
			$this->lblParentProjectsAsRelated = new QLabel($this->objParentObject, $strControlId);
			$this->lblParentProjectsAsRelated->Name = QApplication::Translate('Parent Projects As Related');
			$this->strParentProjectAsRelatedGlue = $strGlue;
			
			$objAssociatedArray = $this->objProject->GetParentProjectAsRelatedArray();
			$strItems = array();
			foreach ($objAssociatedArray as $objAssociated)
				$strItems[] = $objAssociated->__toString();
			$this->lblParentProjectsAsRelated->Text = implode($this->strParentProjectAsRelatedGlue, $strItems);
			return $this->lblParentProjectsAsRelated;
		}


		/**
		 * Create and setup QListBox lstProjectsAsRelated
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function lstProjectsAsRelated_Create($strControlId = null) {
			$this->lstProjectsAsRelated = new QListBox($this->objParentObject, $strControlId);
			$this->lstProjectsAsRelated->Name = QApplication::Translate('Projects As Related');
			$this->lstProjectsAsRelated->SelectionMode = QSelectionMode::Multiple;
			$objAssociatedArray = $this->objProject->GetProjectAsRelatedArray();
			$objProjectArray = Project::LoadAll();
			if ($objProjectArray) foreach ($objProjectArray as $objProject) {
				$objListItem = new QListItem($objProject->__toString(), $objProject->Id);
				foreach ($objAssociatedArray as $objAssociated) {
					if ($objAssociated->Id == $objProject->Id)
						$objListItem->Selected = true;
				}
				$this->lstProjectsAsRelated->AddItem($objListItem);
			}
			return $this->lstProjectsAsRelated;
		}

		/**
		 * Create and setup QLabel lblProjectsAsRelated
		 * @param string $strControlId optional ControlId to use
		 * @param string $strGlue glue to display in between each associated object
		 * @return QLabel
		 */
		public function lblProjectsAsRelated_Create($strControlId = null, $strGlue = ', ') {
			$this->lblProjectsAsRelated = new QLabel($this->objParentObject, $strControlId);
			$this->lblProjectsAsRelated->Name = QApplication::Translate('Projects As Related');
			$this->strProjectAsRelatedGlue = $strGlue;
			
			$objAssociatedArray = $this->objProject->GetProjectAsRelatedArray();
			$strItems = array();
			foreach ($objAssociatedArray as $objAssociated)
				$strItems[] = $objAssociated->__toString();
			$this->lblProjectsAsRelated->Text = implode($this->strProjectAsRelatedGlue, $strItems);
			return $this->lblProjectsAsRelated;
		}


		/**
		 * Create and setup QListBox lstPeopleAsTeamMember
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function lstPeopleAsTeamMember_Create($strControlId = null) {
			$this->lstPeopleAsTeamMember = new QListBox($this->objParentObject, $strControlId);
			$this->lstPeopleAsTeamMember->Name = QApplication::Translate('People As Team Member');
			$this->lstPeopleAsTeamMember->SelectionMode = QSelectionMode::Multiple;
			$objAssociatedArray = $this->objProject->GetPersonAsTeamMemberArray();
			$objPersonArray = Person::LoadAll();
			if ($objPersonArray) foreach ($objPersonArray as $objPerson) {
				$objListItem = new QListItem($objPerson->__toString(), $objPerson->Id);
				foreach ($objAssociatedArray as $objAssociated) {
					if ($objAssociated->Id == $objPerson->Id)
						$objListItem->Selected = true;
				}
				$this->lstPeopleAsTeamMember->AddItem($objListItem);
			}
			return $this->lstPeopleAsTeamMember;
		}

		/**
		 * Create and setup QLabel lblPeopleAsTeamMember
		 * @param string $strControlId optional ControlId to use
		 * @param string $strGlue glue to display in between each associated object
		 * @return QLabel
		 */
		public function lblPeopleAsTeamMember_Create($strControlId = null, $strGlue = ', ') {
			$this->lblPeopleAsTeamMember = new QLabel($this->objParentObject, $strControlId);
			$this->lblPeopleAsTeamMember->Name = QApplication::Translate('People As Team Member');
			$this->strPersonAsTeamMemberGlue = $strGlue;
			
			$objAssociatedArray = $this->objProject->GetPersonAsTeamMemberArray();
			$strItems = array();
			foreach ($objAssociatedArray as $objAssociated)
				$strItems[] = $objAssociated->__toString();
			$this->lblPeopleAsTeamMember->Text = implode($this->strPersonAsTeamMemberGlue, $strItems);
			return $this->lblPeopleAsTeamMember;
		}




		/**
		 * Refresh this MetaControl with Data from the local Project object.
		 * @param boolean $blnReload reload Project from the database
		 * @return void
		 */
		public function Refresh($blnReload = false) {
			if ($blnReload)
				$this->objProject->Reload();

			if ($this->lblId) if ($this->blnEditMode) $this->lblId->Text = $this->objProject->Id;

			if ($this->lstProjectStatusType) $this->lstProjectStatusType->SelectedValue = $this->objProject->ProjectStatusTypeId;
			if ($this->lblProjectStatusTypeId) $this->lblProjectStatusTypeId->Text = ($this->objProject->ProjectStatusTypeId) ? ProjectStatusType::$NameArray[$this->objProject->ProjectStatusTypeId] : null;

			if ($this->lstManagerPerson) {
					$this->lstManagerPerson->RemoveAllItems();
				$this->lstManagerPerson->AddItem(QApplication::Translate('- Select One -'), null);
				$objManagerPersonArray = Person::LoadAll();
				if ($objManagerPersonArray) foreach ($objManagerPersonArray as $objManagerPerson) {
					$objListItem = new QListItem($objManagerPerson->__toString(), $objManagerPerson->Id);
					if (($this->objProject->ManagerPerson) && ($this->objProject->ManagerPerson->Id == $objManagerPerson->Id))
						$objListItem->Selected = true;
					$this->lstManagerPerson->AddItem($objListItem);
				}
			}
			if ($this->lblManagerPersonId) $this->lblManagerPersonId->Text = ($this->objProject->ManagerPerson) ? $this->objProject->ManagerPerson->__toString() : null;

			if ($this->txtName) $this->txtName->Text = $this->objProject->Name;
			if ($this->lblName) $this->lblName->Text = $this->objProject->Name;

			if ($this->txtDescription) $this->txtDescription->Text = $this->objProject->Description;
			if ($this->lblDescription) $this->lblDescription->Text = $this->objProject->Description;

			if ($this->calStartDate) $this->calStartDate->DateTime = $this->objProject->StartDate;
			if ($this->lblStartDate) $this->lblStartDate->Text = sprintf($this->objProject->StartDate) ? $this->objProject->StartDate->__toString($this->strStartDateDateTimeFormat) : null;

			if ($this->calEndDate) $this->calEndDate->DateTime = $this->objProject->EndDate;
			if ($this->lblEndDate) $this->lblEndDate->Text = sprintf($this->objProject->EndDate) ? $this->objProject->EndDate->__toString($this->strEndDateDateTimeFormat) : null;

			if ($this->txtBudget) $this->txtBudget->Text = $this->objProject->Budget;
			if ($this->lblBudget) $this->lblBudget->Text = $this->objProject->Budget;

			if ($this->txtSpent) $this->txtSpent->Text = $this->objProject->Spent;
			if ($this->lblSpent) $this->lblSpent->Text = $this->objProject->Spent;

			if ($this->lstParentProjectsAsRelated) {
				$this->lstParentProjectsAsRelated->RemoveAllItems();
				$objAssociatedArray = $this->objProject->GetParentProjectAsRelatedArray();
				$objProjectArray = Project::LoadAll();
				if ($objProjectArray) foreach ($objProjectArray as $objProject) {
					$objListItem = new QListItem($objProject->__toString(), $objProject->Id);
					foreach ($objAssociatedArray as $objAssociated) {
						if ($objAssociated->Id == $objProject->Id)
							$objListItem->Selected = true;
					}
					$this->lstParentProjectsAsRelated->AddItem($objListItem);
				}
			}
			if ($this->lblParentProjectsAsRelated) {
				$objAssociatedArray = $this->objProject->GetParentProjectAsRelatedArray();
				$strItems = array();
				foreach ($objAssociatedArray as $objAssociated)
					$strItems[] = $objAssociated->__toString();
				$this->lblParentProjectsAsRelated->Text = implode($this->strParentProjectAsRelatedGlue, $strItems);
			}

			if ($this->lstProjectsAsRelated) {
				$this->lstProjectsAsRelated->RemoveAllItems();
				$objAssociatedArray = $this->objProject->GetProjectAsRelatedArray();
				$objProjectArray = Project::LoadAll();
				if ($objProjectArray) foreach ($objProjectArray as $objProject) {
					$objListItem = new QListItem($objProject->__toString(), $objProject->Id);
					foreach ($objAssociatedArray as $objAssociated) {
						if ($objAssociated->Id == $objProject->Id)
							$objListItem->Selected = true;
					}
					$this->lstProjectsAsRelated->AddItem($objListItem);
				}
			}
			if ($this->lblProjectsAsRelated) {
				$objAssociatedArray = $this->objProject->GetProjectAsRelatedArray();
				$strItems = array();
				foreach ($objAssociatedArray as $objAssociated)
					$strItems[] = $objAssociated->__toString();
				$this->lblProjectsAsRelated->Text = implode($this->strProjectAsRelatedGlue, $strItems);
			}

			if ($this->lstPeopleAsTeamMember) {
				$this->lstPeopleAsTeamMember->RemoveAllItems();
				$objAssociatedArray = $this->objProject->GetPersonAsTeamMemberArray();
				$objPersonArray = Person::LoadAll();
				if ($objPersonArray) foreach ($objPersonArray as $objPerson) {
					$objListItem = new QListItem($objPerson->__toString(), $objPerson->Id);
					foreach ($objAssociatedArray as $objAssociated) {
						if ($objAssociated->Id == $objPerson->Id)
							$objListItem->Selected = true;
					}
					$this->lstPeopleAsTeamMember->AddItem($objListItem);
				}
			}
			if ($this->lblPeopleAsTeamMember) {
				$objAssociatedArray = $this->objProject->GetPersonAsTeamMemberArray();
				$strItems = array();
				foreach ($objAssociatedArray as $objAssociated)
					$strItems[] = $objAssociated->__toString();
				$this->lblPeopleAsTeamMember->Text = implode($this->strPersonAsTeamMemberGlue, $strItems);
			}

		}



		///////////////////////////////////////////////
		// PROTECTED UPDATE METHODS for ManyToManyReferences (if any)
		///////////////////////////////////////////////

		protected function lstParentProjectsAsRelated_Update() {
			if ($this->lstParentProjectsAsRelated) {
				$this->objProject->UnassociateAllParentProjectsAsRelated();
				$objSelectedListItems = $this->lstParentProjectsAsRelated->SelectedItems;
				if ($objSelectedListItems) foreach ($objSelectedListItems as $objListItem) {
					$this->objProject->AssociateParentProjectAsRelated(Project::Load($objListItem->Value));
				}
			}
		}

		protected function lstProjectsAsRelated_Update() {
			if ($this->lstProjectsAsRelated) {
				$this->objProject->UnassociateAllProjectsAsRelated();
				$objSelectedListItems = $this->lstProjectsAsRelated->SelectedItems;
				if ($objSelectedListItems) foreach ($objSelectedListItems as $objListItem) {
					$this->objProject->AssociateProjectAsRelated(Project::Load($objListItem->Value));
				}
			}
		}

		protected function lstPeopleAsTeamMember_Update() {
			if ($this->lstPeopleAsTeamMember) {
				$this->objProject->UnassociateAllPeopleAsTeamMember();
				$objSelectedListItems = $this->lstPeopleAsTeamMember->SelectedItems;
				if ($objSelectedListItems) foreach ($objSelectedListItems as $objListItem) {
					$this->objProject->AssociatePersonAsTeamMember(Person::Load($objListItem->Value));
				}
			}
		}





		///////////////////////////////////////////////
		// PUBLIC PROJECT OBJECT MANIPULATORS
		///////////////////////////////////////////////

		/**
		 * This will save this object's Project instance,
		 * updating only the fields which have had a control created for it.
		 */
		public function SaveProject() {
			try {
				// Update any fields for controls that have been created
				if ($this->lstProjectStatusType) $this->objProject->ProjectStatusTypeId = $this->lstProjectStatusType->SelectedValue;
				if ($this->lstManagerPerson) $this->objProject->ManagerPersonId = $this->lstManagerPerson->SelectedValue;
				if ($this->txtName) $this->objProject->Name = $this->txtName->Text;
				if ($this->txtDescription) $this->objProject->Description = $this->txtDescription->Text;
				if ($this->calStartDate) $this->objProject->StartDate = $this->calStartDate->DateTime;
				if ($this->calEndDate) $this->objProject->EndDate = $this->calEndDate->DateTime;
				if ($this->txtBudget) $this->objProject->Budget = $this->txtBudget->Text;
				if ($this->txtSpent) $this->objProject->Spent = $this->txtSpent->Text;

				// Update any UniqueReverseReferences (if any) for controls that have been created for it

				// Save the Project object
				$this->objProject->Save();

				// Finally, update any ManyToManyReferences (if any)
				$this->lstParentProjectsAsRelated_Update();
				$this->lstProjectsAsRelated_Update();
				$this->lstPeopleAsTeamMember_Update();
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * This will DELETE this object's Project instance from the database.
		 * It will also unassociate itself from any ManyToManyReferences.
		 */
		public function DeleteProject() {
			$this->objProject->UnassociateAllParentProjectsAsRelated();
			$this->objProject->UnassociateAllProjectsAsRelated();
			$this->objProject->UnassociateAllPeopleAsTeamMember();
			$this->objProject->Delete();
		}		



		///////////////////////////////////////////////
		// PUBLIC GETTERS and SETTERS
		///////////////////////////////////////////////

		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				// General MetaControlVariables
				case 'Project': return $this->objProject;
				case 'TitleVerb': return $this->strTitleVerb;
				case 'EditMode': return $this->blnEditMode;

				// Controls that point to Project fields -- will be created dynamically if not yet created
				case 'IdControl':
					if (!$this->lblId) return $this->lblId_Create();
					return $this->lblId;
				case 'IdLabel':
					if (!$this->lblId) return $this->lblId_Create();
					return $this->lblId;
				case 'ProjectStatusTypeIdControl':
					if (!$this->lstProjectStatusType) return $this->lstProjectStatusType_Create();
					return $this->lstProjectStatusType;
				case 'ProjectStatusTypeIdLabel':
					if (!$this->lblProjectStatusTypeId) return $this->lblProjectStatusTypeId_Create();
					return $this->lblProjectStatusTypeId;
				case 'ManagerPersonIdControl':
					if (!$this->lstManagerPerson) return $this->lstManagerPerson_Create();
					return $this->lstManagerPerson;
				case 'ManagerPersonIdLabel':
					if (!$this->lblManagerPersonId) return $this->lblManagerPersonId_Create();
					return $this->lblManagerPersonId;
				case 'NameControl':
					if (!$this->txtName) return $this->txtName_Create();
					return $this->txtName;
				case 'NameLabel':
					if (!$this->lblName) return $this->lblName_Create();
					return $this->lblName;
				case 'DescriptionControl':
					if (!$this->txtDescription) return $this->txtDescription_Create();
					return $this->txtDescription;
				case 'DescriptionLabel':
					if (!$this->lblDescription) return $this->lblDescription_Create();
					return $this->lblDescription;
				case 'StartDateControl':
					if (!$this->calStartDate) return $this->calStartDate_Create();
					return $this->calStartDate;
				case 'StartDateLabel':
					if (!$this->lblStartDate) return $this->lblStartDate_Create();
					return $this->lblStartDate;
				case 'EndDateControl':
					if (!$this->calEndDate) return $this->calEndDate_Create();
					return $this->calEndDate;
				case 'EndDateLabel':
					if (!$this->lblEndDate) return $this->lblEndDate_Create();
					return $this->lblEndDate;
				case 'BudgetControl':
					if (!$this->txtBudget) return $this->txtBudget_Create();
					return $this->txtBudget;
				case 'BudgetLabel':
					if (!$this->lblBudget) return $this->lblBudget_Create();
					return $this->lblBudget;
				case 'SpentControl':
					if (!$this->txtSpent) return $this->txtSpent_Create();
					return $this->txtSpent;
				case 'SpentLabel':
					if (!$this->lblSpent) return $this->lblSpent_Create();
					return $this->lblSpent;
				case 'ParentProjectAsRelatedControl':
					if (!$this->lstParentProjectsAsRelated) return $this->lstParentProjectsAsRelated_Create();
					return $this->lstParentProjectsAsRelated;
				case 'ParentProjectAsRelatedLabel':
					if (!$this->lblParentProjectsAsRelated) return $this->lblParentProjectsAsRelated_Create();
					return $this->lblParentProjectsAsRelated;
				case 'ProjectAsRelatedControl':
					if (!$this->lstProjectsAsRelated) return $this->lstProjectsAsRelated_Create();
					return $this->lstProjectsAsRelated;
				case 'ProjectAsRelatedLabel':
					if (!$this->lblProjectsAsRelated) return $this->lblProjectsAsRelated_Create();
					return $this->lblProjectsAsRelated;
				case 'PersonAsTeamMemberControl':
					if (!$this->lstPeopleAsTeamMember) return $this->lstPeopleAsTeamMember_Create();
					return $this->lstPeopleAsTeamMember;
				case 'PersonAsTeamMemberLabel':
					if (!$this->lblPeopleAsTeamMember) return $this->lblPeopleAsTeamMember_Create();
					return $this->lblPeopleAsTeamMember;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					// Controls that point to Project fields
					case 'IdControl':
						return ($this->lblId = QType::Cast($mixValue, 'QControl'));
					case 'ProjectStatusTypeIdControl':
						return ($this->lstProjectStatusType = QType::Cast($mixValue, 'QControl'));
					case 'ManagerPersonIdControl':
						return ($this->lstManagerPerson = QType::Cast($mixValue, 'QControl'));
					case 'NameControl':
						return ($this->txtName = QType::Cast($mixValue, 'QControl'));
					case 'DescriptionControl':
						return ($this->txtDescription = QType::Cast($mixValue, 'QControl'));
					case 'StartDateControl':
						return ($this->calStartDate = QType::Cast($mixValue, 'QControl'));
					case 'EndDateControl':
						return ($this->calEndDate = QType::Cast($mixValue, 'QControl'));
					case 'BudgetControl':
						return ($this->txtBudget = QType::Cast($mixValue, 'QControl'));
					case 'SpentControl':
						return ($this->txtSpent = QType::Cast($mixValue, 'QControl'));
					case 'ParentProjectAsRelatedControl':
						return ($this->lstParentProjectsAsRelated = QType::Cast($mixValue, 'QControl'));
					case 'ProjectAsRelatedControl':
						return ($this->lstProjectsAsRelated = QType::Cast($mixValue, 'QControl'));
					case 'PersonAsTeamMemberControl':
						return ($this->lstPeopleAsTeamMember = QType::Cast($mixValue, 'QControl'));
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}
?>