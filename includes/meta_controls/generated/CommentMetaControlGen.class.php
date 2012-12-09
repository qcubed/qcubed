<?php
	/**
	 * This is a MetaControl class, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality
	 * of the Comment class.  This code-generated class
	 * contains all the basic elements to help a QPanel or QForm display an HTML form that can
	 * manipulate a single Comment object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a CommentMetaControl
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent
	 * code re-generation.
	 *
	 * @package My QCubed Application
	 * @subpackage MetaControls
	 * @property-read Comment $Comment the actual Comment data class being edited
	 * @property QLabel $IdControl
	 * @property-read QLabel $IdLabel
	 * @property QListBox $PostIdControl
	 * @property-read QLabel $PostIdLabel
	 * @property QTextBox $CommentBodyControl
	 * @property-read QLabel $CommentBodyLabel
	 * @property-read string $TitleVerb a verb indicating whether or not this is being edited or created
	 * @property-read boolean $EditMode a boolean indicating whether or not this is being edited or created
	 */

	class CommentMetaControlGen extends QBaseClass {
		// General Variables
		/**
		 * @var Comment objComment
		 * @access protected
		 */
		protected $objComment;
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

		// Controls that allow the editing of Comment's individual data fields
		/**
		 * @var QLabel lblId
		 * @access protected
		 */
		protected $lblId;
		/**
		 * @var QListBox lstPost
		 * @access protected
		 */
		protected $lstPost;
		/**
		 * @var QTextBox txtCommentBody
		 * @access protected
		 */
		protected $txtCommentBody;

		// Controls that allow the viewing of Comment's individual data fields
		/**
		 * @var QLabel lblPostId
		 * @access protected
		 */
		protected $lblPostId;
		/**
		 * @var QLabel lblCommentBody
		 * @access protected
		 */
		protected $lblCommentBody;

		// QListBox Controls (if applicable) to edit Unique ReverseReferences and ManyToMany References

		// QLabel Controls (if applicable) to view Unique ReverseReferences and ManyToMany References


		/**
		 * Main constructor.  Constructor OR static create methods are designed to be called in either
		 * a parent QPanel or the main QForm when wanting to create a
		 * CommentMetaControl to edit a single Comment object within the
		 * QPanel or QForm.
		 *
		 * This constructor takes in a single Comment object, while any of the static
		 * create methods below can be used to construct based off of individual PK ID(s).
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this CommentMetaControl
		 * @param Comment $objComment new or existing Comment object
		 */
		 public function __construct($objParentObject, Comment $objComment) {
			// Setup Parent Object (e.g. QForm or QPanel which will be using this CommentMetaControl)
			$this->objParentObject = $objParentObject;

			// Setup linked Comment object
			$this->objComment = $objComment;

			// Figure out if we're Editing or Creating New
			if ($this->objComment->__Restored) {
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
		 * @param mixed $objParentObject QForm or QPanel which will be using this CommentMetaControl
		 * @param integer $intId primary key value
		 * @param QMetaControlCreateType $intCreateType rules governing Comment object creation - defaults to CreateOrEdit
 		 * @return CommentMetaControl
		 */
		public static function Create($objParentObject, $intId = null, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			// Attempt to Load from PK Arguments
			if (strlen($intId)) {
				$objComment = Comment::Load($intId);

				// Comment was found -- return it!
				if ($objComment)
					return new CommentMetaControl($objParentObject, $objComment);

				// If CreateOnRecordNotFound not specified, throw an exception
				else if ($intCreateType != QMetaControlCreateType::CreateOnRecordNotFound)
					throw new QCallerException('Could not find a Comment object with PK arguments: ' . $intId);

			// If EditOnly is specified, throw an exception
			} else if ($intCreateType == QMetaControlCreateType::EditOnly)
				throw new QCallerException('No PK arguments specified');

			// If we are here, then we need to create a new record
			return new CommentMetaControl($objParentObject, new Comment());
		}

		/**
		 * Static Helper Method to Create using PathInfo arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this CommentMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing Comment object creation - defaults to CreateOrEdit
		 * @return CommentMetaControl
		 */
		public static function CreateFromPathInfo($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::PathInfo(0);
			return CommentMetaControl::Create($objParentObject, $intId, $intCreateType);
		}

		/**
		 * Static Helper Method to Create using QueryString arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this CommentMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing Comment object creation - defaults to CreateOrEdit
		 * @return CommentMetaControl
		 */
		public static function CreateFromQueryString($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intId = QApplication::QueryString('intId');
			return CommentMetaControl::Create($objParentObject, $intId, $intCreateType);
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
				$this->lblId->Text = $this->objComment->Id;
			else
				$this->lblId->Text = 'N/A';
			return $this->lblId;
		}

		/**
		 * Create and setup QListBox lstPost
		 * @param string $strControlId optional ControlId to use
		 * @param QQCondition $objConditions override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] $objOptionalClauses additional optional QQClause object or array of QQClause objects for the query
		 * @return QListBox
		 */
		public function lstPost_Create($strControlId = null, QQCondition $objCondition = null, $objOptionalClauses = null) {
			$this->lstPost = new QListBox($this->objParentObject, $strControlId);
			$this->lstPost->Name = QApplication::Translate('Post');
			$this->lstPost->Required = true;
			if (!$this->blnEditMode)
				$this->lstPost->AddItem(QApplication::Translate('- Select One -'), null);

			// Setup and perform the Query
			if (is_null($objCondition)) $objCondition = QQ::All();
			$objPostCursor = Post::QueryCursor($objCondition, $objOptionalClauses);

			// Iterate through the Cursor
			while ($objPost = Post::InstantiateCursor($objPostCursor)) {
				$objListItem = new QListItem($objPost->__toString(), $objPost->Id);
				if (($this->objComment->Post) && ($this->objComment->Post->Id == $objPost->Id))
					$objListItem->Selected = true;
				$this->lstPost->AddItem($objListItem);
			}

			// Return the QListBox
			return $this->lstPost;
		}

		/**
		 * Create and setup QLabel lblPostId
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblPostId_Create($strControlId = null) {
			$this->lblPostId = new QLabel($this->objParentObject, $strControlId);
			$this->lblPostId->Name = QApplication::Translate('Post');
			$this->lblPostId->Text = ($this->objComment->Post) ? $this->objComment->Post->__toString() : null;
			$this->lblPostId->Required = true;
			return $this->lblPostId;
		}

		/**
		 * Create and setup QTextBox txtCommentBody
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function txtCommentBody_Create($strControlId = null) {
			$this->txtCommentBody = new QTextBox($this->objParentObject, $strControlId);
			$this->txtCommentBody->Name = QApplication::Translate('Comment Body');
			$this->txtCommentBody->Text = $this->objComment->CommentBody;
			$this->txtCommentBody->Required = true;
			$this->txtCommentBody->MaxLength = Comment::CommentBodyMaxLength;
			return $this->txtCommentBody;
		}

		/**
		 * Create and setup QLabel lblCommentBody
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblCommentBody_Create($strControlId = null) {
			$this->lblCommentBody = new QLabel($this->objParentObject, $strControlId);
			$this->lblCommentBody->Name = QApplication::Translate('Comment Body');
			$this->lblCommentBody->Text = $this->objComment->CommentBody;
			$this->lblCommentBody->Required = true;
			return $this->lblCommentBody;
		}



		/**
		 * Refresh this MetaControl with Data from the local Comment object.
		 * @param boolean $blnReload reload Comment from the database
		 * @return void
		 */
		public function Refresh($blnReload = false) {
			if ($blnReload)
				$this->objComment->Reload();

			if ($this->lblId) if ($this->blnEditMode) $this->lblId->Text = $this->objComment->Id;

			if ($this->lstPost) {
					$this->lstPost->RemoveAllItems();
				if (!$this->blnEditMode)
					$this->lstPost->AddItem(QApplication::Translate('- Select One -'), null);
				$objPostArray = Post::LoadAll();
				if ($objPostArray) foreach ($objPostArray as $objPost) {
					$objListItem = new QListItem($objPost->__toString(), $objPost->Id);
					if (($this->objComment->Post) && ($this->objComment->Post->Id == $objPost->Id))
						$objListItem->Selected = true;
					$this->lstPost->AddItem($objListItem);
				}
			}
			if ($this->lblPostId) $this->lblPostId->Text = ($this->objComment->Post) ? $this->objComment->Post->__toString() : null;

			if ($this->txtCommentBody) $this->txtCommentBody->Text = $this->objComment->CommentBody;
			if ($this->lblCommentBody) $this->lblCommentBody->Text = $this->objComment->CommentBody;

		}



		///////////////////////////////////////////////
		// PROTECTED UPDATE METHODS for ManyToManyReferences (if any)
		///////////////////////////////////////////////





		///////////////////////////////////////////////
		// PUBLIC COMMENT OBJECT MANIPULATORS
		///////////////////////////////////////////////

		/**
		 * This will save this object's Comment instance,
		 * updating only the fields which have had a control created for it.
		 */
		public function SaveComment() {
			try {
				// Update any fields for controls that have been created
				if ($this->lstPost) $this->objComment->PostId = $this->lstPost->SelectedValue;
				if ($this->txtCommentBody) $this->objComment->CommentBody = $this->txtCommentBody->Text;

				// Update any UniqueReverseReferences (if any) for controls that have been created for it

				// Save the Comment object
				$this->objComment->Save();

				// Finally, update any ManyToManyReferences (if any)
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * This will DELETE this object's Comment instance from the database.
		 * It will also unassociate itself from any ManyToManyReferences.
		 */
		public function DeleteComment() {
			$this->objComment->Delete();
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
				case 'Comment': return $this->objComment;
				case 'TitleVerb': return $this->strTitleVerb;
				case 'EditMode': return $this->blnEditMode;

				// Controls that point to Comment fields -- will be created dynamically if not yet created
				case 'IdControl':
					if (!$this->lblId) return $this->lblId_Create();
					return $this->lblId;
				case 'IdLabel':
					if (!$this->lblId) return $this->lblId_Create();
					return $this->lblId;
				case 'PostIdControl':
					if (!$this->lstPost) return $this->lstPost_Create();
					return $this->lstPost;
				case 'PostIdLabel':
					if (!$this->lblPostId) return $this->lblPostId_Create();
					return $this->lblPostId;
				case 'CommentBodyControl':
					if (!$this->txtCommentBody) return $this->txtCommentBody_Create();
					return $this->txtCommentBody;
				case 'CommentBodyLabel':
					if (!$this->lblCommentBody) return $this->lblCommentBody_Create();
					return $this->lblCommentBody;
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
					// Controls that point to Comment fields
					case 'IdControl':
						return ($this->lblId = QType::Cast($mixValue, 'QControl'));
					case 'PostIdControl':
						return ($this->lstPost = QType::Cast($mixValue, 'QControl'));
					case 'CommentBodyControl':
						return ($this->txtCommentBody = QType::Cast($mixValue, 'QControl'));
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