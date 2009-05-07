<?php
	/**
	 * This is a MetaControl class, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality
	 * of the Login class.  This code-generated class
	 * contains all the basic elements to help a QPanel or QForm display an HTML form that can
	 * manipulate a single Login object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a LoginMetaControl
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent
	 * code re-generation.
	 * 
	 * @package My Application
	 * @subpackage MetaControls
	 * property-read Login $Login the actual Login data class being edited
	 * property QLabel $IdControl
	 * property-read QLabel $IdLabel
	 * property QListBox $PersonIdControl
	 * property-read QLabel $PersonIdLabel
	 * property QTextBox $UsernameControl
	 * property-read QLabel $UsernameLabel
	 * property QTextBox $PasswordControl
	 * property-read QLabel $PasswordLabel
	 * property QIntegerTextBox $IsEnabledControl
	 * property-read QLabel $IsEnabledLabel
	 * property-read string $TitleVerb a verb indicating whether or not this is being edited or created
	 * property-read boolean $EditMode a boolean indicating whether or not this is being edited or created
	 */

	class LoginMetaControlGen extends QBaseClass {
		// General Variables
		protected $objLogin;
		protected $objParentObject;
		protected $strTitleVerb;
		protected $blnEditMode;

		// Controls that allow the editing of Login's individual data fields
		protected $lblId;
		protected $lstPerson;
		protected $txtUsername;
		protected $txtPassword;
		protected $txtIsEnabled;

		// Controls that allow the viewing of Login's individual data fields
		protected $lblPersonId;
		protected $lblUsername;
		protected $lblPassword;
		protected $lblIsEnabled;

		// QListBox Controls (if applicable) to edit Unique ReverseReferences and ManyToMany References

		// QLabel Controls (if applicable) to view Unique ReverseReferences and ManyToMany References


		/**
		 * Main constructor.  Constructor OR static create methods are designed to be called in either
		 * a parent QPanel or the main QForm when wanting to create a
		 * LoginMetaControl to edit a single Login object within the
		 * QPanel or QForm.
		 *
		 * This constructor takes in a single Login object, while any of the static
		 * create methods below can be used to construct based off of individual PK ID(s).
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this LoginMetaControl
		 * @param Login $objLogin new or existing Login object
		 */
		 public function __construct($objParentObject, Login $objLogin) {
			// Setup Parent Object (e.g. QForm or QPanel which will be using this LoginMetaControl)
			$this->objParentObject = $objParentObject;

			// Setup linked Login object
			$this->objLogin = $objLogin;

			// Figure out if we're Editing or Creating New
			if ($this->objLogin->__Restored) {
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
		 * @param mixed $objParentObject QForm or QPanel which will be using this LoginMetaControl
		 * @param integer $intId primary key value
		 * @param QMetaControlCreateType $intCreateType rules governing Login object creation - defaults to CreateOrEdit
 		 * @return LoginMetaControl
		 */
		public static function Create($objParentObject, $intId = null, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			// Attempt to Load from PK Arguments
			if (strlen($intId)) {
				$objLogin = Login::Load($intId);

				// Login was found -- return it!
				if ($objLogin)
					return new LoginMetaControl($objParentObject, $objLogin);

				// If CreateOnRecordNotFound not specified, throw an exception
				else if ($intCreateType != QMetaControlCreateType::CreateOnRecordNotFound)
					throw new QCallerException('Could not find a Login object with PK arguments: ' . $intId);

			// If EditOnly is specified, throw an exception
			} else if ($intCreateType == QMetaControlCreateType::EditOnly)
				throw new QCallerException('No PK arguments specified');

			// If we are here, then we need to create a new record
			return new LoginMetaControl($objParentObject, new Login());
		}

		/**
		 * Static Helper Method to Create using PathInfo arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this LoginMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing Login object creation - defaults to CreateOrEdit
		 * @return LoginMetaControl
		 */
		public static function CreateFromPathInfo($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::PathInfo(0);
			return LoginMetaControl::Create($objParentObject, $intId, $intCreateType);
		}

		/**
		 * Static Helper Method to Create using QueryString arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this LoginMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing Login object creation - defaults to CreateOrEdit
		 * @return LoginMetaControl
		 */
		public static function CreateFromQueryString($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::QueryString('intId');
			return LoginMetaControl::Create($objParentObject, $intId, $intCreateType);
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
				$this->lblId->Text = $this->objLogin->Id;
			else
				$this->lblId->Text = 'N/A';
			return $this->lblId;
		}

		/**
		 * Create and setup QListBox lstPerson
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function lstPerson_Create($strControlId = null) {
			$this->lstPerson = new QListBox($this->objParentObject, $strControlId);
			$this->lstPerson->Name = QApplication::Translate('Person');
			$this->lstPerson->Required = true;
			if (!$this->blnEditMode)
				$this->lstPerson->AddItem(QApplication::Translate('- Select One -'), null);
			$objPersonArray = Person::LoadAll();
			if ($objPersonArray) foreach ($objPersonArray as $objPerson) {
				$objListItem = new QListItem($objPerson->__toString(), $objPerson->Id);
				if (($this->objLogin->Person) && ($this->objLogin->Person->Id == $objPerson->Id))
					$objListItem->Selected = true;
				$this->lstPerson->AddItem($objListItem);
			}
			return $this->lstPerson;
		}

		/**
		 * Create and setup QLabel lblPersonId
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblPersonId_Create($strControlId = null) {
			$this->lblPersonId = new QLabel($this->objParentObject, $strControlId);
			$this->lblPersonId->Name = QApplication::Translate('Person');
			$this->lblPersonId->Text = ($this->objLogin->Person) ? $this->objLogin->Person->__toString() : null;
			$this->lblPersonId->Required = true;
			return $this->lblPersonId;
		}

		/**
		 * Create and setup QTextBox txtUsername
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function txtUsername_Create($strControlId = null) {
			$this->txtUsername = new QTextBox($this->objParentObject, $strControlId);
			$this->txtUsername->Name = QApplication::Translate('Username');
			$this->txtUsername->Text = $this->objLogin->Username;
			$this->txtUsername->Required = true;
			$this->txtUsername->MaxLength = Login::UsernameMaxLength;
			return $this->txtUsername;
		}

		/**
		 * Create and setup QLabel lblUsername
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblUsername_Create($strControlId = null) {
			$this->lblUsername = new QLabel($this->objParentObject, $strControlId);
			$this->lblUsername->Name = QApplication::Translate('Username');
			$this->lblUsername->Text = $this->objLogin->Username;
			$this->lblUsername->Required = true;
			return $this->lblUsername;
		}

		/**
		 * Create and setup QTextBox txtPassword
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function txtPassword_Create($strControlId = null) {
			$this->txtPassword = new QTextBox($this->objParentObject, $strControlId);
			$this->txtPassword->Name = QApplication::Translate('Password');
			$this->txtPassword->Text = $this->objLogin->Password;
			$this->txtPassword->MaxLength = Login::PasswordMaxLength;
			return $this->txtPassword;
		}

		/**
		 * Create and setup QLabel lblPassword
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblPassword_Create($strControlId = null) {
			$this->lblPassword = new QLabel($this->objParentObject, $strControlId);
			$this->lblPassword->Name = QApplication::Translate('Password');
			$this->lblPassword->Text = $this->objLogin->Password;
			return $this->lblPassword;
		}

		/**
		 * Create and setup QIntegerTextBox txtIsEnabled
		 * @param string $strControlId optional ControlId to use
		 * @return QIntegerTextBox
		 */
		public function txtIsEnabled_Create($strControlId = null) {
			$this->txtIsEnabled = new QIntegerTextBox($this->objParentObject, $strControlId);
			$this->txtIsEnabled->Name = QApplication::Translate('Is Enabled');
			$this->txtIsEnabled->Text = $this->objLogin->IsEnabled;
			$this->txtIsEnabled->Required = true;
			return $this->txtIsEnabled;
		}

		/**
		 * Create and setup QLabel lblIsEnabled
		 * @param string $strControlId optional ControlId to use
		 * @param string $strFormat optional sprintf format to use
		 * @return QLabel
		 */
		public function lblIsEnabled_Create($strControlId = null, $strFormat = null) {
			$this->lblIsEnabled = new QLabel($this->objParentObject, $strControlId);
			$this->lblIsEnabled->Name = QApplication::Translate('Is Enabled');
			$this->lblIsEnabled->Text = $this->objLogin->IsEnabled;
			$this->lblIsEnabled->Required = true;
			$this->lblIsEnabled->Format = $strFormat;
			return $this->lblIsEnabled;
		}



		/**
		 * Refresh this MetaControl with Data from the local Login object.
		 * @param boolean $blnReload reload Login from the database
		 * @return void
		 */
		public function Refresh($blnReload = false) {
			if ($blnReload)
				$this->objLogin->Reload();

			if ($this->lblId) if ($this->blnEditMode) $this->lblId->Text = $this->objLogin->Id;

			if ($this->lstPerson) {
					$this->lstPerson->RemoveAllItems();
				if (!$this->blnEditMode)
					$this->lstPerson->AddItem(QApplication::Translate('- Select One -'), null);
				$objPersonArray = Person::LoadAll();
				if ($objPersonArray) foreach ($objPersonArray as $objPerson) {
					$objListItem = new QListItem($objPerson->__toString(), $objPerson->Id);
					if (($this->objLogin->Person) && ($this->objLogin->Person->Id == $objPerson->Id))
						$objListItem->Selected = true;
					$this->lstPerson->AddItem($objListItem);
				}
			}
			if ($this->lblPersonId) $this->lblPersonId->Text = ($this->objLogin->Person) ? $this->objLogin->Person->__toString() : null;

			if ($this->txtUsername) $this->txtUsername->Text = $this->objLogin->Username;
			if ($this->lblUsername) $this->lblUsername->Text = $this->objLogin->Username;

			if ($this->txtPassword) $this->txtPassword->Text = $this->objLogin->Password;
			if ($this->lblPassword) $this->lblPassword->Text = $this->objLogin->Password;

			if ($this->txtIsEnabled) $this->txtIsEnabled->Text = $this->objLogin->IsEnabled;
			if ($this->lblIsEnabled) $this->lblIsEnabled->Text = $this->objLogin->IsEnabled;

		}



		///////////////////////////////////////////////
		// PROTECTED UPDATE METHODS for ManyToManyReferences (if any)
		///////////////////////////////////////////////





		///////////////////////////////////////////////
		// PUBLIC LOGIN OBJECT MANIPULATORS
		///////////////////////////////////////////////

		/**
		 * This will save this object's Login instance,
		 * updating only the fields which have had a control created for it.
		 */
		public function SaveLogin() {
			try {
				// Update any fields for controls that have been created
				if ($this->lstPerson) $this->objLogin->PersonId = $this->lstPerson->SelectedValue;
				if ($this->txtUsername) $this->objLogin->Username = $this->txtUsername->Text;
				if ($this->txtPassword) $this->objLogin->Password = $this->txtPassword->Text;
				if ($this->txtIsEnabled) $this->objLogin->IsEnabled = $this->txtIsEnabled->Text;

				// Update any UniqueReverseReferences (if any) for controls that have been created for it

				// Save the Login object
				$this->objLogin->Save();

				// Finally, update any ManyToManyReferences (if any)
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * This will DELETE this object's Login instance from the database.
		 * It will also unassociate itself from any ManyToManyReferences.
		 */
		public function DeleteLogin() {
			$this->objLogin->Delete();
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
				case 'Login': return $this->objLogin;
				case 'TitleVerb': return $this->strTitleVerb;
				case 'EditMode': return $this->blnEditMode;

				// Controls that point to Login fields -- will be created dynamically if not yet created
				case 'IdControl':
					if (!$this->lblId) return $this->lblId_Create();
					return $this->lblId;
				case 'IdLabel':
					if (!$this->lblId) return $this->lblId_Create();
					return $this->lblId;
				case 'PersonIdControl':
					if (!$this->lstPerson) return $this->lstPerson_Create();
					return $this->lstPerson;
				case 'PersonIdLabel':
					if (!$this->lblPersonId) return $this->lblPersonId_Create();
					return $this->lblPersonId;
				case 'UsernameControl':
					if (!$this->txtUsername) return $this->txtUsername_Create();
					return $this->txtUsername;
				case 'UsernameLabel':
					if (!$this->lblUsername) return $this->lblUsername_Create();
					return $this->lblUsername;
				case 'PasswordControl':
					if (!$this->txtPassword) return $this->txtPassword_Create();
					return $this->txtPassword;
				case 'PasswordLabel':
					if (!$this->lblPassword) return $this->lblPassword_Create();
					return $this->lblPassword;
				case 'IsEnabledControl':
					if (!$this->txtIsEnabled) return $this->txtIsEnabled_Create();
					return $this->txtIsEnabled;
				case 'IsEnabledLabel':
					if (!$this->lblIsEnabled) return $this->lblIsEnabled_Create();
					return $this->lblIsEnabled;
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
					// Controls that point to Login fields
					case 'IdControl':
						return ($this->lblId = QType::Cast($mixValue, 'QControl'));
					case 'PersonIdControl':
						return ($this->lstPerson = QType::Cast($mixValue, 'QControl'));
					case 'UsernameControl':
						return ($this->txtUsername = QType::Cast($mixValue, 'QControl'));
					case 'PasswordControl':
						return ($this->txtPassword = QType::Cast($mixValue, 'QControl'));
					case 'IsEnabledControl':
						return ($this->txtIsEnabled = QType::Cast($mixValue, 'QControl'));
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