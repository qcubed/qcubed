<?php
	/**
	 * This is a MetaControl class, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality
	 * of the PersonWithLock class.  This code-generated class
	 * contains all the basic elements to help a QPanel or QForm display an HTML form that can
	 * manipulate a single PersonWithLock object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a PersonWithLockMetaControl
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent
	 * code re-generation.
	 *
	 * @package My QCubed Application
	 * @subpackage MetaControls
	 * @property-read PersonWithLock $PersonWithLock the actual PersonWithLock data class being edited
	 * @property QLabel $IdControl
	 * @property-read QLabel $IdLabel
	 * @property QTextBox $FirstNameControl
	 * @property-read QLabel $FirstNameLabel
	 * @property QTextBox $LastNameControl
	 * @property-read QLabel $LastNameLabel
	 * @property QLabel $SysTimestampControl
	 * @property-read QLabel $SysTimestampLabel
	 * @property-read string $TitleVerb a verb indicating whether or not this is being edited or created
	 * @property-read boolean $EditMode a boolean indicating whether or not this is being edited or created
	 */

	class PersonWithLockMetaControlGen extends QBaseClass {
		// General Variables
		/**
		 * @var PersonWithLock objPersonWithLock
		 * @access protected
		 */
		protected $objPersonWithLock;
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

		// Controls that allow the editing of PersonWithLock's individual data fields
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
		/**
		 * @var QLabel lblSysTimestamp
		 * @access protected
		 */
		protected $lblSysTimestamp;

		// Controls that allow the viewing of PersonWithLock's individual data fields
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

		// QLabel Controls (if applicable) to view Unique ReverseReferences and ManyToMany References


		/**
		 * Main constructor.  Constructor OR static create methods are designed to be called in either
		 * a parent QPanel or the main QForm when wanting to create a
		 * PersonWithLockMetaControl to edit a single PersonWithLock object within the
		 * QPanel or QForm.
		 *
		 * This constructor takes in a single PersonWithLock object, while any of the static
		 * create methods below can be used to construct based off of individual PK ID(s).
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this PersonWithLockMetaControl
		 * @param PersonWithLock $objPersonWithLock new or existing PersonWithLock object
		 */
		 public function __construct($objParentObject, PersonWithLock $objPersonWithLock) {
			// Setup Parent Object (e.g. QForm or QPanel which will be using this PersonWithLockMetaControl)
			$this->objParentObject = $objParentObject;

			// Setup linked PersonWithLock object
			$this->objPersonWithLock = $objPersonWithLock;

			// Figure out if we're Editing or Creating New
			if ($this->objPersonWithLock->__Restored) {
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
		 * @param mixed $objParentObject QForm or QPanel which will be using this PersonWithLockMetaControl
		 * @param integer $intId primary key value
		 * @param QMetaControlCreateType $intCreateType rules governing PersonWithLock object creation - defaults to CreateOrEdit
 		 * @return PersonWithLockMetaControl
		 */
		public static function Create($objParentObject, $intId = null, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			// Attempt to Load from PK Arguments
			if (strlen($intId)) {
				$objPersonWithLock = PersonWithLock::Load($intId);

				// PersonWithLock was found -- return it!
				if ($objPersonWithLock)
					return new PersonWithLockMetaControl($objParentObject, $objPersonWithLock);

				// If CreateOnRecordNotFound not specified, throw an exception
				else if ($intCreateType != QMetaControlCreateType::CreateOnRecordNotFound)
					throw new QCallerException('Could not find a PersonWithLock object with PK arguments: ' . $intId);

			// If EditOnly is specified, throw an exception
			} else if ($intCreateType == QMetaControlCreateType::EditOnly)
				throw new QCallerException('No PK arguments specified');

			// If we are here, then we need to create a new record
			return new PersonWithLockMetaControl($objParentObject, new PersonWithLock());
		}

		/**
		 * Static Helper Method to Create using PathInfo arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this PersonWithLockMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing PersonWithLock object creation - defaults to CreateOrEdit
		 * @return PersonWithLockMetaControl
		 */
		public static function CreateFromPathInfo($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::PathInfo(0);
			return PersonWithLockMetaControl::Create($objParentObject, $intId, $intCreateType);
		}

		/**
		 * Static Helper Method to Create using QueryString arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this PersonWithLockMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing PersonWithLock object creation - defaults to CreateOrEdit
		 * @return PersonWithLockMetaControl
		 */
		public static function CreateFromQueryString($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::QueryString('intId');
			return PersonWithLockMetaControl::Create($objParentObject, $intId, $intCreateType);
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
				$this->lblId->Text = $this->objPersonWithLock->Id;
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
			$this->txtFirstName->Text = $this->objPersonWithLock->FirstName;
			$this->txtFirstName->Required = true;
			$this->txtFirstName->MaxLength = PersonWithLock::FirstNameMaxLength;
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
			$this->lblFirstName->Text = $this->objPersonWithLock->FirstName;
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
			$this->txtLastName->Text = $this->objPersonWithLock->LastName;
			$this->txtLastName->Required = true;
			$this->txtLastName->MaxLength = PersonWithLock::LastNameMaxLength;
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
			$this->lblLastName->Text = $this->objPersonWithLock->LastName;
			$this->lblLastName->Required = true;
			return $this->lblLastName;
		}

		/**
		 * Create and setup QLabel lblSysTimestamp
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblSysTimestamp_Create($strControlId = null) {
			$this->lblSysTimestamp = new QLabel($this->objParentObject, $strControlId);
			$this->lblSysTimestamp->Name = QApplication::Translate('Sys Timestamp');
			if ($this->blnEditMode)
				$this->lblSysTimestamp->Text = $this->objPersonWithLock->SysTimestamp;
			else
				$this->lblSysTimestamp->Text = 'N/A';
			return $this->lblSysTimestamp;
		}



		/**
		 * Refresh this MetaControl with Data from the local PersonWithLock object.
		 * @param boolean $blnReload reload PersonWithLock from the database
		 * @return void
		 */
		public function Refresh($blnReload = false) {
			if ($blnReload)
				$this->objPersonWithLock->Reload();

			if ($this->lblId) if ($this->blnEditMode) $this->lblId->Text = $this->objPersonWithLock->Id;

			if ($this->txtFirstName) $this->txtFirstName->Text = $this->objPersonWithLock->FirstName;
			if ($this->lblFirstName) $this->lblFirstName->Text = $this->objPersonWithLock->FirstName;

			if ($this->txtLastName) $this->txtLastName->Text = $this->objPersonWithLock->LastName;
			if ($this->lblLastName) $this->lblLastName->Text = $this->objPersonWithLock->LastName;

			if ($this->lblSysTimestamp) if ($this->blnEditMode) $this->lblSysTimestamp->Text = $this->objPersonWithLock->SysTimestamp;

		}



		///////////////////////////////////////////////
		// PROTECTED UPDATE METHODS for ManyToManyReferences (if any)
		///////////////////////////////////////////////





		///////////////////////////////////////////////
		// PUBLIC PERSONWITHLOCK OBJECT MANIPULATORS
		///////////////////////////////////////////////

		/**
		 * This will save this object's PersonWithLock instance,
		 * updating only the fields which have had a control created for it.
		 */
		public function SavePersonWithLock() {
			try {
				// Update any fields for controls that have been created
				if ($this->txtFirstName) $this->objPersonWithLock->FirstName = $this->txtFirstName->Text;
				if ($this->txtLastName) $this->objPersonWithLock->LastName = $this->txtLastName->Text;

				// Update any UniqueReverseReferences (if any) for controls that have been created for it

				// Save the PersonWithLock object
				$this->objPersonWithLock->Save();

				// Finally, update any ManyToManyReferences (if any)
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * This will DELETE this object's PersonWithLock instance from the database.
		 * It will also unassociate itself from any ManyToManyReferences.
		 */
		public function DeletePersonWithLock() {
			$this->objPersonWithLock->Delete();
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
				case 'PersonWithLock': return $this->objPersonWithLock;
				case 'TitleVerb': return $this->strTitleVerb;
				case 'EditMode': return $this->blnEditMode;

				// Controls that point to PersonWithLock fields -- will be created dynamically if not yet created
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
				case 'SysTimestampControl':
					if (!$this->lblSysTimestamp) return $this->lblSysTimestamp_Create();
					return $this->lblSysTimestamp;
				case 'SysTimestampLabel':
					if (!$this->lblSysTimestamp) return $this->lblSysTimestamp_Create();
					return $this->lblSysTimestamp;
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
					// Controls that point to PersonWithLock fields
					case 'IdControl':
						return ($this->lblId = QType::Cast($mixValue, 'QControl'));
					case 'FirstNameControl':
						return ($this->txtFirstName = QType::Cast($mixValue, 'QControl'));
					case 'LastNameControl':
						return ($this->txtLastName = QType::Cast($mixValue, 'QControl'));
					case 'SysTimestampControl':
						return ($this->lblSysTimestamp = QType::Cast($mixValue, 'QControl'));
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