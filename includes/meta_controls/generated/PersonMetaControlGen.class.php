<?php
	/**
	 * This is a MetaControl class, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality
	 * of the Person class.  This code-generated class
	 * contains all the basic elements to help a QPanel or QForm display an HTML form that can
	 * manipulate a single Person object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a PersonMetaControl
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent
	 * code re-generation.
	 *
	 * @package My QCubed Application
	 * @subpackage MetaControls
	 * @property-read Person $Person the actual Person data class being edited
	 * @property QLabel $IdControl
	 * @property-read QLabel $IdLabel
	 * @property QTextBox $FirstNameControl
	 * @property-read QLabel $FirstNameLabel
	 * @property QTextBox $LastNameControl
	 * @property-read QLabel $LastNameLabel
	 * @property QListBox $LoginControl
	 * @property-read QLabel $LoginLabel
	 * @property QListBox $ProjectAsTeamMemberControl
	 * @property-read QLabel $ProjectAsTeamMemberLabel
	 * @property-read string $TitleVerb a verb indicating whether or not this is being edited or created
	 * @property-read boolean $EditMode a boolean indicating whether or not this is being edited or created
	 */

	class PersonMetaControlGen extends QBaseClass {
		// General Variables
		/**
		 * @var Person objPerson
		 * @access protected
		 */
		protected $objPerson;
		/**
		 * @var QForm|QControl objParentObject
		 * @access protected
		 */
		protected $objParentObject;
		/**
		 * @var string strTitleVerb
		 * @access protected
		 */
		protected $strTitleVerb;
		/**
		 * @var boolean blnEditMode
		 * @access protected
		 */
		protected $blnEditMode;

		// Controls that allow the editing of Person's individual data fields
		/**
		 * @var QLabel lblId
		 * @access protected
		 */
		protected $lblId;
		/**
		 * @var QTextBox txtFirstName
		 * @access protected
		 */
		protected $txtFirstName;
		/**
		 * @var QTextBox txtLastName
		 * @access protected
		 */
		protected $txtLastName;

		// Controls that allow the viewing of Person's individual data fields
		/**
		 * @var QLabel lblFirstName
		 * @access protected
		 */
		protected $lblFirstName;
		/**
		 * @var QLabel lblLastName
		 * @access protected
		 */
		protected $lblLastName;

		// QListBox Controls (if applicable) to edit Unique ReverseReferences and ManyToMany References
		/**
		 * @var QListBox lstLogin
		 * @access protected
		 */
		protected $lstLogin;
		protected $dtgProjectsAsTeamMember;
		protected $strProjectAsTeamMemberGlue;

		// QLabel Controls (if applicable) to view Unique ReverseReferences and ManyToMany References
		/**
		 * @var QLabel lblLogin
		 * @access protected
		 */
		protected $lblLogin;
		protected $lblProjectsAsTeamMember;


		/**
		 * Main constructor.  Constructor OR static create methods are designed to be called in either
		 * a parent QPanel or the main QForm when wanting to create a
		 * PersonMetaControl to edit a single Person object within the
		 * QPanel or QForm.
		 *
		 * This constructor takes in a single Person object, while any of the static
		 * create methods below can be used to construct based off of individual PK ID(s).
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this PersonMetaControl
		 * @param Person $objPerson new or existing Person object
		 */
		 public function __construct($objParentObject, Person $objPerson) {
			// Setup Parent Object (e.g. QForm or QPanel which will be using this PersonMetaControl)
			$this->objParentObject = $objParentObject;

			// Setup linked Person object
			$this->objPerson = $objPerson;

			// Figure out if we're Editing or Creating New
			if ($this->objPerson->__Restored) {
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
		 * @param mixed $objParentObject QForm or QPanel which will be using this PersonMetaControl
		 * @param integer $intId primary key value
		 * @param QMetaControlCreateType $intCreateType rules governing Person object creation - defaults to CreateOrEdit
 		 * @return PersonMetaControl
		 */
		public static function Create($objParentObject, $intId = null, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			// Attempt to Load from PK Arguments
			if (strlen($intId)) {
				$objPerson = Person::Load($intId);

				// Person was found -- return it!
				if ($objPerson)
					return new PersonMetaControl($objParentObject, $objPerson);

				// If CreateOnRecordNotFound not specified, throw an exception
				else if ($intCreateType != QMetaControlCreateType::CreateOnRecordNotFound)
					throw new QCallerException('Could not find a Person object with PK arguments: ' . $intId);

			// If EditOnly is specified, throw an exception
			} else if ($intCreateType == QMetaControlCreateType::EditOnly)
				throw new QCallerException('No PK arguments specified');

			// If we are here, then we need to create a new record
			return new PersonMetaControl($objParentObject, new Person());
		}

		/**
		 * Static Helper Method to Create using PathInfo arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this PersonMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing Person object creation - defaults to CreateOrEdit
		 * @return PersonMetaControl
		 */
		public static function CreateFromPathInfo($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::PathInfo(0);
			return PersonMetaControl::Create($objParentObject, $intId, $intCreateType);
		}

		/**
		 * Static Helper Method to Create using QueryString arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this PersonMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing Person object creation - defaults to CreateOrEdit
		 * @return PersonMetaControl
		 */
		public static function CreateFromQueryString($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::QueryString('intId');
			return PersonMetaControl::Create($objParentObject, $intId, $intCreateType);
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
				$this->lblId->Text = $this->objPerson->Id;
			else
				$this->lblId->Text = 'N/A';
			return $this->lblId;
		}

		/**
		 * Create and setup QTextBox txtFirstName
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function txtFirstName_Create($strControlId = null) {
			$this->txtFirstName = new QTextBox($this->objParentObject, $strControlId);
			$this->txtFirstName->Name = QApplication::Translate('First Name');
			$this->txtFirstName->Text = $this->objPerson->FirstName;
			$this->txtFirstName->Required = true;
			$this->txtFirstName->MaxLength = Person::FirstNameMaxLength;
			return $this->txtFirstName;
		}

		/**
		 * Create and setup QLabel lblFirstName
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblFirstName_Create($strControlId = null) {
			$this->lblFirstName = new QLabel($this->objParentObject, $strControlId);
			$this->lblFirstName->Name = QApplication::Translate('First Name');
			$this->lblFirstName->Text = $this->objPerson->FirstName;
			$this->lblFirstName->Required = true;
			return $this->lblFirstName;
		}

		/**
		 * Create and setup QTextBox txtLastName
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function txtLastName_Create($strControlId = null) {
			$this->txtLastName = new QTextBox($this->objParentObject, $strControlId);
			$this->txtLastName->Name = QApplication::Translate('Last Name');
			$this->txtLastName->Text = $this->objPerson->LastName;
			$this->txtLastName->Required = true;
			$this->txtLastName->MaxLength = Person::LastNameMaxLength;
			return $this->txtLastName;
		}

		/**
		 * Create and setup QLabel lblLastName
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblLastName_Create($strControlId = null) {
			$this->lblLastName = new QLabel($this->objParentObject, $strControlId);
			$this->lblLastName->Name = QApplication::Translate('Last Name');
			$this->lblLastName->Text = $this->objPerson->LastName;
			$this->lblLastName->Required = true;
			return $this->lblLastName;
		}

		/**
		 * Create and setup QListBox lstLogin
		 * @param string $strControlId optional ControlId to use
		 * @param QQCondition $objConditions override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] $objOptionalClauses additional optional QQClause object or array of QQClause objects for the query
		 * @return QListBox
		 */
		public function lstLogin_Create($strControlId = null, QQCondition $objCondition = null, $objOptionalClauses = null) {
			$this->lstLogin = new QListBox($this->objParentObject, $strControlId);
			$this->lstLogin->Name = QApplication::Translate('Login');
			$this->lstLogin->AddItem(QApplication::Translate('- Select One -'), null);

			// Setup and perform the Query
			if (is_null($objCondition)) $objCondition = QQ::All();
			$objLoginCursor = Login::QueryCursor($objCondition, $objOptionalClauses);

			// Iterate through the Cursor
			while ($objLogin = Login::InstantiateCursor($objLoginCursor)) {
				$objListItem = new QListItem($objLogin->__toString(), $objLogin->Id);
				if ($objLogin->PersonId == $this->objPerson->Id)
					$objListItem->Selected = true;
				$this->lstLogin->AddItem($objListItem);
			}

			// Because Login's Login is not null, if a value is already selected, it cannot be changed.
			if ($this->lstLogin->SelectedValue)
				$this->lstLogin->Enabled = false;

			// Return the QListBox
			return $this->lstLogin;
		}

		/**
		 * Create and setup QLabel lblLogin
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblLogin_Create($strControlId = null) {
			$this->lblLogin = new QLabel($this->objParentObject, $strControlId);
			$this->lblLogin->Name = QApplication::Translate('Login');
			$this->lblLogin->Text = ($this->objPerson->Login) ? $this->objPerson->Login->__toString() : null;
			return $this->lblLogin;
		}

		protected $colProjectAsTeamMemberSelected;

		/**
		 * Create and setup QDataGrid dtgProjectsAsTeamMember
		 * @param string $strControlId optional ControlId to use
		 * @param string $strURL the URL to go to when clicking on the label. Note that the object ID will be appended to this URL.
		 * @return QDataGrid
		 */
		public function dtgProjectsAsTeamMember_Create($strControlId = null, $strURL = null) {
			// Setup DataGrid
			$this->dtgProjectsAsTeamMember = new QDataGrid($this->objParentObject, $strControlId);
			$this->dtgProjectsAsTeamMember->CssClass = 'datagrid';
			$this->dtgProjectsAsTeamMember->Owner = $this;

			// Datagrid Paginator
			$this->dtgProjectsAsTeamMember->Paginator = new QPaginator($this->dtgProjectsAsTeamMember);
			//If desired, use this to set the numbers of items to show per page
			//$this->dtgProjectsAsTeamMember->ItemsPerPage = 20;

			// Specify Whether or Not to Refresh using Ajax
			$this->dtgProjectsAsTeamMember->UseAjax = true;

			// Specify the local databind method this datagrid will use
			$this->dtgProjectsAsTeamMember->SetDataBinder('dtgProjectsAsTeamMember_Bind', $this);

			// Setup DataGridColumns
			$this->colProjectAsTeamMemberSelected = new QCheckBoxColumn(QApplication::Translate('Select'), $this->dtgProjectsAsTeamMember);
			$this->colProjectAsTeamMemberSelected->PrimaryKey = 'Id';
			$this->colProjectAsTeamMemberSelected->SetCheckboxCallback($this, 'dtgProjectsAsTeamMemberSelect_Created');
			$this->dtgProjectsAsTeamMember->AddColumn($this->colProjectAsTeamMemberSelected);

			$colName = new QDataGridColumn(QApplication::Translate('Name'), '<?= $_OWNER->dtgProjectsAsTeamMember_Name_Render($_ITEM, "'.$strURL.'"); ?>');
			//, array('OrderByClause' => QQ::OrderBy(Project::GetNameOrderByNode()),
			//	'ReverseOrderByClause' => QQ::OrderBy(Project::GetNameOrderByNode(), false)));
			//$colName->Filter = QQ::Like(Project::GetNameOrderByNode(), null);
			//$colName->FilterPrefix = $colName->FilterPostfix = "%";

			$colName->HtmlEntities = false;
			$this->dtgProjectsAsTeamMember->AddColumn($colName);

			$this->dtgProjectsAsTeamMember->Name = QApplication::Translate('Projects As Team Member');

			return $this->dtgProjectsAsTeamMember;
		}

		public function dtgProjectsAsTeamMemberSelect_Created(Project $_ITEM, QCheckBox $ctl) {
			if(null !== $_ITEM->GetVirtualAttribute('assn_item')) {
				$ctl->Checked = true;
			}
		}

		public function dtgProjectsAsTeamMember_Name_Render(Project $_ITEM, $strURL = '') {
			$strName = QApplication::Translate(QApplication::HtmlEntities($_ITEM->__toString()));

			// Link to the edit page for this object
			if('' != $strURL)
				return sprintf('<a href="%s%s">%s</a>',
					$strURL,
					$_ITEM->Id,
					$strName);
			return $strName;
		}

		protected function GetdtgProjectsAsTeamMemberConditions() {
			// Override this function if there are additional limitations on this list.
			return null;
		}

		public function dtgProjectsAsTeamMember_Bind() {
			// Get Total Count b/c of Pagination
			$conditions = $this->dtgProjectsAsTeamMember->Conditions;

			$moreConditions = $this->GetdtgProjectsAsTeamMemberConditions();
			if($moreConditions !== null) {
				$conditions = QQ::AndCondition($conditions, $moreConditions);
			}

			$this->dtgProjectsAsTeamMember->TotalItemCount = Project::QueryCount($conditions);

			$objClauses = array();
			if ($objClause = $this->dtgProjectsAsTeamMember->OrderByClause) {
				array_push($objClauses, $objClause);
			}
			if ($objClause = $this->dtgProjectsAsTeamMember->LimitClause) {
				array_push($objClauses, $objClause);
			}

			$objDatabase = Person::GetDatabase();
			$objClauses[] = QQ::Expand(
				QQ::Virtual('assn_item',
					QQ::SubSql("select `person_id`
					 from `team_member_project_assn`
					 where
					`project_id` = {1}
					and `person_id` = ".
					$objDatabase->SqlVariable($this->objPerson->Id),
						QQN::Project()->Id)
						)
					);

			$this->dtgProjectsAsTeamMember->DataSource = Project::QueryArray($conditions, $objClauses);
		}

		/**
		 * Create and setup QLabel lblProjectsAsTeamMember
		 * @param string $strControlId optional ControlId to use
		 * @param string $strGlue glue to display in-between each associated object
		 * @return QLabel
		 */
		public function lblProjectsAsTeamMember_Create($strControlId = null, $strGlue = ', ') {
			$this->lblProjectsAsTeamMember = new QLabel($this->objParentObject, $strControlId);
			$this->lblProjectsAsTeamMember->Name = QApplication::Translate('Projects As Team Member');
			$this->strProjectAsTeamMemberGlue = $strGlue;

			$objAssociatedArray = $this->objPerson->GetProjectAsTeamMemberArray();
			$strItems = array();
			foreach ($objAssociatedArray as $objAssociated) {
				$strItems[] = $objAssociated->__toString();
			}
			$this->lblProjectsAsTeamMember->Text = implode($this->strProjectAsTeamMemberGlue, $strItems);
			return $this->lblProjectsAsTeamMember;
		}




		/**
		 * Refresh this MetaControl with Data from the local Person object.
		 * @param boolean $blnReload reload Person from the database
		 * @return void
		 */
		public function Refresh($blnReload = false) {
			if ($blnReload)
				$this->objPerson->Reload();

			if ($this->lblId) if ($this->blnEditMode) $this->lblId->Text = $this->objPerson->Id;

			if ($this->txtFirstName) $this->txtFirstName->Text = $this->objPerson->FirstName;
			if ($this->lblFirstName) $this->lblFirstName->Text = $this->objPerson->FirstName;

			if ($this->txtLastName) $this->txtLastName->Text = $this->objPerson->LastName;
			if ($this->lblLastName) $this->lblLastName->Text = $this->objPerson->LastName;

			if ($this->lstLogin) {
				$this->lstLogin->RemoveAllItems();
				$this->lstLogin->AddItem(QApplication::Translate('- Select One -'), null);
				$objLoginArray = Login::LoadAll();
				if ($objLoginArray) foreach ($objLoginArray as $objLogin) {
					$objListItem = new QListItem($objLogin->__toString(), $objLogin->Id);
					if ($objLogin->PersonId == $this->objPerson->Id)
						$objListItem->Selected = true;
					$this->lstLogin->AddItem($objListItem);
				}
				// Because Login's Login is not null, if a value is already selected, it cannot be changed.
				if ($this->lstLogin->SelectedValue)
					$this->lstLogin->Enabled = false;
				else
					$this->lstLogin->Enabled = true;
			}
			if ($this->lblLogin) $this->lblLogin->Text = ($this->objPerson->Login) ? $this->objPerson->Login->__toString() : null;

			if ($this->dtgProjectsAsTeamMember) {
				$this->dtgProjectsAsTeamMember->RemoveAllItems();
				$objAssociatedArray = $this->objPerson->GetProjectAsTeamMemberArray();
				$objProjectArray = Project::LoadAll();
				if ($objProjectArray) foreach ($objProjectArray as $objProject) {
					$objListItem = new QListItem($objProject->__toString(), $objProject->Id);
					foreach ($objAssociatedArray as $objAssociated) {
						if ($objAssociated->Id == $objProject->Id)
							$objListItem->Selected = true;
					}
					$this->dtgProjectsAsTeamMember->AddItem($objListItem);
				}
			}
			if ($this->lblProjectsAsTeamMember) {
				$objAssociatedArray = $this->objPerson->GetProjectAsTeamMemberArray();
				$strItems = array();
				foreach ($objAssociatedArray as $objAssociated)
					$strItems[] = $objAssociated->__toString();
				$this->lblProjectsAsTeamMember->Text = implode($this->strProjectAsTeamMemberGlue, $strItems);
			}

		}



		///////////////////////////////////////////////
		// PROTECTED UPDATE METHODS for ManyToManyReferences (if any)
		///////////////////////////////////////////////


		protected function dtgProjectsAsTeamMember_Update() {
			if ($this->dtgProjectsAsTeamMember) {
				$changedIds = $this->colProjectAsTeamMemberSelected->GetChangedIds();
				$temp = Project::QueryArray(QQ::In(QQN::Project()->Id, array_keys($changedIds)));
				$changedItems = array();
				foreach($temp as $item) {
					$changedItems[$item->Id] = $item;
				}

				foreach($changedIds as $id=>$blnSelected) {
					$item = $changedItems[$id];
					if($blnSelected) {
						// Associate this Project
						$this->objPerson->AssociateProjectAsTeamMember($item);
					} else {
						// Unassociate this Project
						$results = $this->objPerson->UnassociateProjectAsTeamMember($item);
					}
				}
			}
		}




		///////////////////////////////////////////////
		// PUBLIC PERSON OBJECT MANIPULATORS
		///////////////////////////////////////////////

		/**
		 * This will save this object's Person instance,
		 * updating only the fields which have had a control created for it.
		 */
		public function SavePerson() {
			try {
				// Update any fields for controls that have been created
				if ($this->txtFirstName) $this->objPerson->FirstName = $this->txtFirstName->Text;
				if ($this->txtLastName) $this->objPerson->LastName = $this->txtLastName->Text;

				// Update any UniqueReverseReferences (if any) for controls that have been created for it
				if ($this->lstLogin) $this->objPerson->Login = Login::Load($this->lstLogin->SelectedValue);

				// Save the Person object
				$this->objPerson->Save();

				// Finally, update any ManyToManyReferences (if any)
				$this->dtgProjectsAsTeamMember_Update();
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * This will DELETE this object's Person instance from the database.
		 * It will also unassociate itself from any ManyToManyReferences.
		 */
		public function DeletePerson() {
			$this->objPerson->UnassociateAllProjectsAsTeamMember();
			$this->objPerson->Delete();
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
				case 'Person': return $this->objPerson;
				case 'TitleVerb': return $this->strTitleVerb;
				case 'EditMode': return $this->blnEditMode;

				// Controls that point to Person fields -- will be created dynamically if not yet created
				case 'IdControl':
					if (!$this->lblId) return $this->lblId_Create();
					return $this->lblId;
				case 'IdLabel':
					if (!$this->lblId) return $this->lblId_Create();
					return $this->lblId;
				case 'FirstNameControl':
					if (!$this->txtFirstName) return $this->txtFirstName_Create();
					return $this->txtFirstName;
				case 'FirstNameLabel':
					if (!$this->lblFirstName) return $this->lblFirstName_Create();
					return $this->lblFirstName;
				case 'LastNameControl':
					if (!$this->txtLastName) return $this->txtLastName_Create();
					return $this->txtLastName;
				case 'LastNameLabel':
					if (!$this->lblLastName) return $this->lblLastName_Create();
					return $this->lblLastName;
				case 'LoginControl':
					if (!$this->lstLogin) return $this->lstLogin_Create();
					return $this->lstLogin;
				case 'LoginLabel':
					if (!$this->lblLogin) return $this->lblLogin_Create();
					return $this->lblLogin;
				case 'ProjectAsTeamMemberControl':
					if (!$this->dtgProjectsAsTeamMember) return $this->dtgProjectsAsTeamMember_Create();
					return $this->dtgProjectsAsTeamMember;
				case 'ProjectAsTeamMemberLabel':
					if (!$this->lblProjectsAsTeamMember) return $this->lblProjectsAsTeamMember_Create();
					return $this->lblProjectsAsTeamMember;
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
					// Controls that point to Person fields
					case 'IdControl':
						return ($this->lblId = QType::Cast($mixValue, 'QControl'));
					case 'FirstNameControl':
						return ($this->txtFirstName = QType::Cast($mixValue, 'QControl'));
					case 'LastNameControl':
						return ($this->txtLastName = QType::Cast($mixValue, 'QControl'));
					case 'LoginControl':
						return ($this->lstLogin = QType::Cast($mixValue, 'QControl'));
					case 'ProjectAsTeamMemberControl':
						return ($this->dtgProjectsAsTeamMember = QType::Cast($mixValue, 'QControl'));
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