<?php
	/**
	 * This is a MetaControl class, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality
	 * of the Post class.  This code-generated class
	 * contains all the basic elements to help a QPanel or QForm display an HTML form that can
	 * manipulate a single Post object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a PostMetaControl
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent
	 * code re-generation.
	 *
	 * @package My QCubed Application
	 * @subpackage MetaControls
	 * @property-read Post $Post the actual Post data class being edited
	 * @property QLabel $IdControl
	 * @property-read QLabel $IdLabel
	 * @property QTextBox $TitleControl
	 * @property-read QLabel $TitleLabel
	 * @property QTextBox $BodyControl
	 * @property-read QLabel $BodyLabel
	 * @property-read string $TitleVerb a verb indicating whether or not this is being edited or created
	 * @property-read boolean $EditMode a boolean indicating whether or not this is being edited or created
	 */

	class PostMetaControlGen extends QBaseClass {
		// General Variables
		/**
		 * @var Post objPost
		 * @access protected
		 */
		protected $objPost;
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

		// Controls that allow the editing of Post's individual data fields
		/**
		 * @var QLabel lblId
		 * @access protected
		 */
		protected $lblId;
		/**
		 * @var QTextBox txtTitle
		 * @access protected
		 */
		protected $txtTitle;
		/**
		 * @var QTextBox txtBody
		 * @access protected
		 */
		protected $txtBody;

		// Controls that allow the viewing of Post's individual data fields
		/**
		 * @var QLabel lblTitle
		 * @access protected
		 */
		protected $lblTitle;
		/**
		 * @var QLabel lblBody
		 * @access protected
		 */
		protected $lblBody;

		// QListBox Controls (if applicable) to edit Unique ReverseReferences and ManyToMany References

		// QLabel Controls (if applicable) to view Unique ReverseReferences and ManyToMany References


		/**
		 * Main constructor.  Constructor OR static create methods are designed to be called in either
		 * a parent QPanel or the main QForm when wanting to create a
		 * PostMetaControl to edit a single Post object within the
		 * QPanel or QForm.
		 *
		 * This constructor takes in a single Post object, while any of the static
		 * create methods below can be used to construct based off of individual PK ID(s).
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this PostMetaControl
		 * @param Post $objPost new or existing Post object
		 */
		 public function __construct($objParentObject, Post $objPost) {
			// Setup Parent Object (e.g. QForm or QPanel which will be using this PostMetaControl)
			$this->objParentObject = $objParentObject;

			// Setup linked Post object
			$this->objPost = $objPost;

			// Figure out if we're Editing or Creating New
			if ($this->objPost->__Restored) {
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
		 * @param mixed $objParentObject QForm or QPanel which will be using this PostMetaControl
		 * @param integer $intId primary key value
		 * @param QMetaControlCreateType $intCreateType rules governing Post object creation - defaults to CreateOrEdit
 		 * @return PostMetaControl
		 */
		public static function Create($objParentObject, $intId = null, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			// Attempt to Load from PK Arguments
			if (strlen($intId)) {
				$objPost = Post::Load($intId);

				// Post was found -- return it!
				if ($objPost)
					return new PostMetaControl($objParentObject, $objPost);

				// If CreateOnRecordNotFound not specified, throw an exception
				else if ($intCreateType != QMetaControlCreateType::CreateOnRecordNotFound)
					throw new QCallerException('Could not find a Post object with PK arguments: ' . $intId);

			// If EditOnly is specified, throw an exception
			} else if ($intCreateType == QMetaControlCreateType::EditOnly)
				throw new QCallerException('No PK arguments specified');

			// If we are here, then we need to create a new record
			return new PostMetaControl($objParentObject, new Post());
		}

		/**
		 * Static Helper Method to Create using PathInfo arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this PostMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing Post object creation - defaults to CreateOrEdit
		 * @return PostMetaControl
		 */
		public static function CreateFromPathInfo($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::PathInfo(0);
			return PostMetaControl::Create($objParentObject, $intId, $intCreateType);
		}

		/**
		 * Static Helper Method to Create using QueryString arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this PostMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing Post object creation - defaults to CreateOrEdit
		 * @return PostMetaControl
		 */
		public static function CreateFromQueryString($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::QueryString('intId');
			return PostMetaControl::Create($objParentObject, $intId, $intCreateType);
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
				$this->lblId->Text = $this->objPost->Id;
			else
				$this->lblId->Text = 'N/A';
			return $this->lblId;
		}

		/**
		 * Create and setup QTextBox txtTitle
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function txtTitle_Create($strControlId = null) {
			$this->txtTitle = new QTextBox($this->objParentObject, $strControlId);
			$this->txtTitle->Name = QApplication::Translate('Title');
			$this->txtTitle->Text = $this->objPost->Title;
			$this->txtTitle->Required = true;
			$this->txtTitle->MaxLength = Post::TitleMaxLength;
			return $this->txtTitle;
		}

		/**
		 * Create and setup QLabel lblTitle
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblTitle_Create($strControlId = null) {
			$this->lblTitle = new QLabel($this->objParentObject, $strControlId);
			$this->lblTitle->Name = QApplication::Translate('Title');
			$this->lblTitle->Text = $this->objPost->Title;
			$this->lblTitle->Required = true;
			return $this->lblTitle;
		}

		/**
		 * Create and setup QTextBox txtBody
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function txtBody_Create($strControlId = null) {
			$this->txtBody = new QTextBox($this->objParentObject, $strControlId);
			$this->txtBody->Name = QApplication::Translate('Body');
			$this->txtBody->Text = $this->objPost->Body;
			$this->txtBody->Required = true;
			$this->txtBody->MaxLength = Post::BodyMaxLength;
			return $this->txtBody;
		}

		/**
		 * Create and setup QLabel lblBody
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblBody_Create($strControlId = null) {
			$this->lblBody = new QLabel($this->objParentObject, $strControlId);
			$this->lblBody->Name = QApplication::Translate('Body');
			$this->lblBody->Text = $this->objPost->Body;
			$this->lblBody->Required = true;
			return $this->lblBody;
		}



		/**
		 * Refresh this MetaControl with Data from the local Post object.
		 * @param boolean $blnReload reload Post from the database
		 * @return void
		 */
		public function Refresh($blnReload = false) {
			if ($blnReload)
				$this->objPost->Reload();

			if ($this->lblId) if ($this->blnEditMode) $this->lblId->Text = $this->objPost->Id;

			if ($this->txtTitle) $this->txtTitle->Text = $this->objPost->Title;
			if ($this->lblTitle) $this->lblTitle->Text = $this->objPost->Title;

			if ($this->txtBody) $this->txtBody->Text = $this->objPost->Body;
			if ($this->lblBody) $this->lblBody->Text = $this->objPost->Body;

		}



		///////////////////////////////////////////////
		// PROTECTED UPDATE METHODS for ManyToManyReferences (if any)
		///////////////////////////////////////////////





		///////////////////////////////////////////////
		// PUBLIC POST OBJECT MANIPULATORS
		///////////////////////////////////////////////

		/**
		 * This will save this object's Post instance,
		 * updating only the fields which have had a control created for it.
		 */
		public function SavePost() {
			try {
				// Update any fields for controls that have been created
				if ($this->txtTitle) $this->objPost->Title = $this->txtTitle->Text;
				if ($this->txtBody) $this->objPost->Body = $this->txtBody->Text;

				// Update any UniqueReverseReferences (if any) for controls that have been created for it

				// Save the Post object
				$this->objPost->Save();

				// Finally, update any ManyToManyReferences (if any)
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * This will DELETE this object's Post instance from the database.
		 * It will also unassociate itself from any ManyToManyReferences.
		 */
		public function DeletePost() {
			$this->objPost->Delete();
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
				case 'Post': return $this->objPost;
				case 'TitleVerb': return $this->strTitleVerb;
				case 'EditMode': return $this->blnEditMode;

				// Controls that point to Post fields -- will be created dynamically if not yet created
				case 'IdControl':
					if (!$this->lblId) return $this->lblId_Create();
					return $this->lblId;
				case 'IdLabel':
					if (!$this->lblId) return $this->lblId_Create();
					return $this->lblId;
				case 'TitleControl':
					if (!$this->txtTitle) return $this->txtTitle_Create();
					return $this->txtTitle;
				case 'TitleLabel':
					if (!$this->lblTitle) return $this->lblTitle_Create();
					return $this->lblTitle;
				case 'BodyControl':
					if (!$this->txtBody) return $this->txtBody_Create();
					return $this->txtBody;
				case 'BodyLabel':
					if (!$this->lblBody) return $this->lblBody_Create();
					return $this->lblBody;
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
					// Controls that point to Post fields
					case 'IdControl':
						return ($this->lblId = QType::Cast($mixValue, 'QControl'));
					case 'TitleControl':
						return ($this->txtTitle = QType::Cast($mixValue, 'QControl'));
					case 'BodyControl':
						return ($this->txtBody = QType::Cast($mixValue, 'QControl'));
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