<?php
	/**
	 * QControlBase is the base class of all QControls and shares their common properties
	 * 
	 * Please note that not every control will utilize every single one of these properties.
	 * Keep in mind that Controls that are not Enabled or not Visible will not go through the form's
	 * Validation routine.
	 * All Controls must implement the following abstract functions:
	 * <ul>
	 * 		<li>{@link QControlBase::GetControlHtml()}</li>
	 * 		<li>{@link QControlBase::ParsePostData()}</li>
	 * 		<li>{@link QControlBase::Validate()}</li>
	 * </ul>
	 * 
	 * @package Controls
	 * 
	 * @property string $AccessKey allows you to specify what Alt-Letter combination will automatically focus that control on the form
	 * @property boolean $ActionsMustTerminate Prevent the default action from happenning upon an event trigger. See documentation for "protected $blnActionsMustTerminate" below.
	 * @property mixed $ActionParameter This property allows you to pass your own parameters to the handlers for actions applied to this control.
	 *			 this can be a string or an object of type QJsClosure. If you pass in a QJsClosure it is possible to return javascript objects/arrays 
	 *			 when using an ajax or server action.
	 * @property string $BackColor sets the CSS background-color of the control
	 * @property string $BorderColor sets the CSS border-color of the control
	 * @property string $BorderWidth sets the CSS border-width of the control
	 * @property string $BorderStyle is used to set CSS border-style by {@link QBorderStyle}
	 * @property mixed $CausesValidation flag says whether or not the form should run through its validation routine if this control has an action defined and is acted upon
	 * @property-read string $ControlId returns the id of this control
	 * @property string $CssClass sets or returns the CSS class for this control
	 * @property string $Cursor is used to set CSS cursor property by {@link QCursor}
	 * @property boolean $Display shows or hides the control using the CSS display property.  In either case, the control is still rendered on the page. See the Visible property if you wish to not render a control.
	 * @property string $DisplayStyle is used to set CSS display property by {@link QDisplayStyle}
	 * @property boolean $Enabled specifies whether or not this is enabled (it will grey out the control and make it inoperable if set to true)
	 * @property boolean $FontBold sets the font bold or normal
	 * @property boolean $FontItalic sets the Font italic or normal
	 * @property string $FontNames sets the name of used fonts
	 * @property boolean $FontOverline 
	 * @property string $FontSize sets the font-size of the control
	 * @property boolean $FontStrikeout  
	 * @property boolean $FontUnderline sets the font underlined
	 * @property string $ForeColor sets the forecolor of the control (like fontcolor)
	 * @property-read QForm $Form returns the parent form object
	 * @property-read string $FormAttributes
	 * @property string $Height
	 * @property string $HtmlAfter HTML that is shown after the control {@link QControl::RenderWithName}
	 * @property string $HtmlBefore HTML that is shown before the control {@link QControl::RenderWithName}
	 * @property string $Instructions instructions that is shown next to the control's name label {@link QControl::RenderWithName}
	 * @property-read string $JavaScripts
	 * @property string $Left CSS left property
	 * @property-read boolean $Modified indicates if the control has been changed. Used to tell Qcubed to rerender the control or not (Ajax calls).
	 * @property boolean $Moveable
	 * @property boolean $Resizable
	 * @property string $Name sets the Name of the Control (see {@link QControl::RenderWithName})
	 * @property-read boolean $OnPage is true if the control is connected to the form
	 * @property integer $Opacity sets the opacity of the control (0-100)
	 * @property string $Overflow is used to set CSS overflow property by {@link QOverflow}
	 * @property-read QForm|QControl $ParentControl returns the parent control
	 * @property string $Position is used to set CSS position property by {@link QPosition}
	 * @property-read boolean $Rendered
	 * @property-read boolean $Rendering
	 * @property-read string $RenderMethod carries the name of the function, which were initially used for rendering
	 * @property string $PreferedRenderMethod carries the name of the function, which were initially used for rendering
	 * @property boolean $Required specifies whether or not this is required (will cause a validation error if the form is trying to be validated and this control is left blank)
	 * @property-read string $StyleSheets
	 * @property integer $TabIndex specifies the index/tab order on a form
	 * @property string $ToolTip specifies the text to be displayed when the mouse is hovering over the control
	 * @property string $Top
	 * @property-read string $ValidationError is the string that contains the validation error (if applicable) or will be blank if (1) the form did not undergo its validation routine or (2) this control had no error
	 * @property boolean $Visible specifies whether or not the control should be rendered in the page.  This is in contrast to Display, which will just hide the control via CSS styling.
	 * @property string $Warning is warning text (looks like an error, but it can be user defined) that will be shown next to the control's name label {@link QControl::RenderWithName}
	 * @property string $Width
	 * @property boolean $UseWrapper defaults to true
	 * @property-read boolean $WrapperModified
	 * @property string $WrapperCssClass
	 */
	abstract class QControlBase extends QBaseClass {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		/** @var string Background color for the control */
		protected $strBackColor = null;
		/** @var string Border color for the control */
		protected $strBorderColor = null;
		/** @var QBorderStyle|string The border style for the control */
		protected $strBorderStyle = QBorderStyle::NotSet;
		/** @var string Border width - can be specified in numbers(will add pixels for that) or a number with unit attached to it */
		protected $strBorderWidth = null;
		/** @var string CSS class for the control */
		protected $strCssClass = null;
		/** @var bool should the control be displayed? */
		protected $blnDisplay = true;
		/** @var QDisplayStyle|string Display style (CSS) for the control */
		protected $strDisplayStyle = QDisplayStyle::NotSet;
		/** @var bool Will the text font for the control be bold */
		protected $blnFontBold = false;
		/** @var bool Will the text font for the control be italisized */
		protected $blnFontItalic = false;
		/** @var string Names of the fonts to be used for the control's text */
		protected $strFontNames = null;
		/** @var bool Line above the text (strTextDecoration will store the value) */
		protected $blnFontOverline = false;
		/** @var string Font-size: Can be specified in numbers (will add 'px' for that) or a number with a unit attached with it */
		protected $strFontSize = null;
		/** @var bool Line over the text, striking it through (strTextDecoration will store the value) */
		protected $blnFontStrikeout = false;
		/** @var bool Line under the text (strTextDecoration will store the value) */
		protected $blnFontUnderline = false;
		/** @var string the 'color' CSS property of the control */
		protected $strForeColor = null;
		/** @var integer Opacity of the control. Range from 0 to 100 (is converted to float automatically) */
		protected $intOpacity = null;

		// BEHAVIOR
		/** @var string The 'accesskey' attribute of the control */
		protected $strAccessKey = null;
		/** @var bool|string|QControl|array How will this control cause validations to trigger  */
		protected $mixCausesValidation = false;
		/** @var string Cursor that should appear when hovering on the control */
		protected $strCursor = QCursor::NotSet;
		/** @var bool Is the control Enabled or Disabled */
		protected $blnEnabled = true;
		/** @var bool Is it mandatory for the control to recive data on a POST back for the control to be called valid? */
		protected $blnRequired = false;
		/** @var int Tab-index */
		protected $intTabIndex = 0;
		/** @var string the 'title' HTML attribute for the control */
		protected $strToolTip = null;
		/** @var string The validation error to be shown */
		protected $strValidationError = null;
		/** @var bool Should the control be visible or not (it normally effects whether Render method will be called or not) */
		protected $blnVisible = true;
		/** @var string Preferred method to be used for rendering e.g. Render, RenderWithName, RenderWithError */
		protected $strPreferedRenderMethod = 'Render';
	
		// LAYOUT
		/** @var string Height of the control. If numeric, 'px' is attached; otherwise used as it is */
		protected $strHeight = null;
		/** @var string Width of the control. If numeric, 'px' is attached; otherwise used as it is */
		protected $strWidth = null;

		/** @var string HTML to rendered before the actual control */
		protected $strHtmlBefore = null;
		/** @var string HTML to rendered after the actual control */
		protected $strHtmlAfter = null;
		/** @var string the Instructions for the control (useful for controls used in data entry) */
		protected $strInstructions = null;
		/** @var string Same as validation error message but is supposed to contain custom messages */
		protected $strWarning = null;

		/** @var QOverflow|string Overflow property for the control */
		protected $strOverflow = QOverflow::NotSet;
		/** @var QPosition|string Position of the control */
		protected $strPosition = QPosition::NotSet;
		/** @var string|null The margin from the top for 'fixed' element. Is used only with the control's wrapper enabled */
		protected $strTop = null;
		/** @var string|null The margin from the left for 'fixed' element. Is used only with the control's wrapper enabled */
		protected $strLeft = null;

		/** @var QDraggable|null When initialized, it implements the jQuery UI Draggable capabilities on to this control.*/
		protected $objDraggable = null;
		/** @var QResizable|null When initialized, it implements the jQuery UI Resizable capabilities on to this control.*/
		protected $objResizable = null;
		/** @var QDroppable|null When initialized, it implements the jQuery UI Droppable capabilities on to this control.*/
		protected $objDroppable = null;

		// MISC
		/**
		 * @var null|string The control ID of this control. Used to represent the control internally
		 *  			and used for the HTML 'id' attribute on the control.
		 */
		protected $strControlId;
		/**
		 *
		 * @var QForm
		 */
		protected $objForm = null;
		/** @var QControl Immediate parent of this control */
		protected $objParentControl = null;
		/** @var QControl[] Controls which have this control as their parent */
		protected $objChildControlArray = array();
		/** @var string|null Name of the control - used as a lable for the control when RenderWithName is used to render */
		protected $strName = null;
		/** @var bool Has the control already been rendered? */
		protected $blnRendered = false;
		/** @var bool Is the control mid-way the process of rendering? */
		protected $blnRendering = false;
		/** @var bool Is the control avaialble on page? Useful when 're-rendering' a control that has children */
		protected $blnOnPage = false;
		/** @var bool Has the control been modified? Used mostly in Ajax or Server callbacks */
		protected $blnModified = false;
		/** @var bool Has the control's wrapper been modified? Used in Ajax or Server callbacks */
		protected $blnWrapperModified = false;
		/** @var string Render method to be used */
		protected $strRenderMethod;
		/** @var string|null Custom HTML attributes for the control  */
		protected $strCustomAttributeArray = null;
		/** @var string|null Custom CSS style attributes for the control */
		protected $strCustomStyleArray = null;
		/** @var array Array containing the list of actions set on the control (for different events) */
		protected $objActionArray = array();
		/** @var string|QJsClosure|null The action parameter (typically small amount of data) for the Ajax or Server Callback  */
		protected $mixActionParameter = null;
		/** @var string|null CSS class for the control's wrapper */
		protected $strWrapperCssClass = null;
		/** @var bool Should the wrapper be used when rendering?  */
		protected $blnUseWrapper = true;
        /** @var string  One time scripts associated with the control. */
        protected $strAttributeScripts = null;

		// SETTINGS
		/** @var string List of JavaScript files to be attached with the control when rendering */
		protected $strJavaScripts = null;
		/** @var string List of CSS files to be attaches with the control when rendering */
		protected $strStyleSheets = null;
		/** @var string Form attributes for the control */
		protected $strFormAttributes = null;
		/**
		 * @var bool Should the default action be stopped from the being triggerred when an even occurrs?
		 *
		 * e.g.:
		 *
		 * 1. When a link is clicked which has an action associated with it - the browser will try to
		 * 	navigate to the link.
		 * 2. When someone presses enter on a textbox - the form will try to submit.
		 *
		 * This variable stops the default behavior (navigation to link / form submission) when set to true.
		 * Modification of this variable is to be done by using 'ActionMustTerminate' property exposed as a property
		 */
		protected $blnActionsMustTerminate = false;
		/** @var bool Is this control a block type element? */
		protected $blnIsBlockElement = false;
		/** @var QWatcher Stores information about watched tables. */
		protected $objWatcher = null;

		//////////
		// Methods
		//////////
		/**
		 * Creates a QControlBase object
		 * This constructor will generally not be used to create a QControlBase object.  Instead it is used by the
		 * classes which extend the class.  Only the parent object parameter is required.  If the option strControlId
		 * parameter is not used, QCubed will generate the id.
		 *
		 * @param QControl|QForm|QControlBase $objParentObject
		 * @param string                      $strControlId
		 *   optional id of this Control. In html, this will be set as the value of the id attribute. The id can only
		 *   contain alphanumeric characters.  If this parameter is not passed, QCubed will generate the id
		 *
		 * @throws Exception|QCallerException
		 */
		public function __construct($objParentObject, $strControlId = null) {
			if ($objParentObject instanceof QForm)
				$this->objForm = $objParentObject;
			else if ($objParentObject instanceof QControl) {
				$this->objParentControl = $objParentObject;
//				$this->objParentControl->blnModified = true;
				$this->objForm = $objParentObject->Form;
			} else
				throw new QCallerException('ParentObject must be either a QForm or QControl object');

			if (strlen($strControlId) == 0)
				$this->strControlId = $this->objForm->GenerateControlId();
			else {
				// Verify ControlId is only AlphaNumeric Characters
				if (ctype_alnum($strControlId))
					$this->strControlId = $strControlId;
				else
					throw new QCallerException('ControlIDs must be only alphanumeric chacters: ' . $strControlId);
			}
			try {
				$this->objForm->AddControl($this);
				if ($this->objParentControl)
					$this->objParentControl->AddChildControl($this);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * This function returns a persistent control which is supposed to be created only once for the user session
		 * The way to call this function is shown in QCubed Examples. For brevity, here is how it is to be used:
		 *
		 * <code>
		 * ProjectPickerListBox::CreatePersistent(
		 * 	'ProjectPickerListBox', // name of the control class
		 * 	$this, // parent - the current QForm
		 * 	'ddnProjects' // id on the form - just your usual ControlID
		 * );
		 * </code>
		 *
		 * The above code will create the control and save it into the form and the session if it does not exist.
		 * If it exists, the control will be retrieved out of the $_SESSION
		 *
		 * @param string $strClassName Name of the class whose instance is to be persisted in the $_SESSION
		 * 			It is not necessary that this name will be the same as the current QControl using
		 * 			which the function is being called.
		 * @param QForm|QFormBase|QControl|QControlBase $objParentObject Parent of this control
		 * @param string $strControlId The ControlID it will aquire when rendered on page
		 *
		 * @return mixed
		 * @throws QCallerException
		 * @throws Exception|QCallerException
		 */
		public static function CreatePersistent($strClassName, $objParentObject, $strControlId) {
			// Test the validity of the Parent object recieved by the function
			if ($objParentObject instanceof QForm) {
				$objForm = $objParentObject;
				$objParentControl = null;
			} else if ($objParentObject instanceof QControl) {
				$objForm = $objParentObject->Form;
				$objParentControl = $objParentObject;
			} else
				throw new QCallerException('Parent Object must be a QForm or QControl');

			// Check if this persistent control already exists in the $_SESSION
			if (array_key_exists($objForm->FormId . '_' . $strControlId, $_SESSION) && $_SESSION[$objForm->FormId . '_' . $strControlId]) {
				// Control exists in the $_SESSION
				// Extract it out of the session as a PHP Object
				$objToReturn = unserialize($_SESSION[$objForm->FormId . '_' . $strControlId]);
				// set its Parents and form
				$objToReturn->objParentControl = $objParentControl;
				$objToReturn->objForm = $objForm;
				try {
					// Add control to the Designated Form
					$objToReturn->objForm->AddControl($objToReturn);
					// If the parent control was a QControl, add the persistent control as a child to that QControl
					if ($objToReturn->objParentControl)
						$objToReturn->objParentControl->AddChildControl($objToReturn);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			} else {
				$objToReturn = new $strClassName($objParentObject, $strControlId);
			}

			// Save the control into the form's persistent controls array
			$objForm->PersistControl($objToReturn);
			return $objToReturn;
		}

		/**
		 * This function is used to prepare the current QControl for persistence.
		 * It acts as a helper function for the 'Persist()' function of this same class (QControlBase)
		 */
		protected function PersistPrepare() {
			$this->objForm = null;
			$this->objParentControl = null;
			$this->objActionArray = array();
			$this->objChildControlArray = array();
			$this->blnRendered = null;
			$this->blnRendering = null;
			$this->blnOnPage = null;
			$this->blnModified = null;
			$this->mixCausesValidation = null;
		}

		/**
		 * Ensure that this QControl is persistent in the User $_SESSION
		 */
		public function Persist() {
			$objControl = clone($this);
			$objControl->PersistPrepare();
			$_SESSION[$this->objForm->FormId . '_' . $this->strControlId] = serialize($objControl);
		}

		/**
		 * Adds a control as a child of this control.
		 *
		 * @param QControl|QControlBase $objControl the control to add
		 */
		public function AddChildControl(QControl $objControl) {
			$this->blnModified = true;
			$this->objChildControlArray[$objControl->ControlId] = $objControl;
			$objControl->objParentControl = $this;
		}

		/**
		 * Returns all child controls as an array
		 *
		 * @param boolean $blnUseNumericIndexes
		 * @return QControl[]
		 */
		public function GetChildControls($blnUseNumericIndexes = true) {
			if ($blnUseNumericIndexes) {
				$objToReturn = array();
				foreach ($this->objChildControlArray as $objChildControl)
					array_push($objToReturn, $objChildControl);
				return $objToReturn;
			} else
				return $this->objChildControlArray;
		}

		/**
		 * Returns the child control with the given id
		 * @param string $strControlId
		 * @return QControl
		 */
		public function GetChildControl($strControlId) {
			if (array_key_exists($strControlId, $this->objChildControlArray))
				return $this->objChildControlArray[$strControlId];
			else
				return null;
		}

		/**
		 * Removes all child controls
		 * @param boolean $blnRemoveFromForm
		 */
		public function RemoveChildControls($blnRemoveFromForm) {
			foreach ($this->objChildControlArray as $objChildControl) {
				$this->RemoveChildControl($objChildControl->ControlId, $blnRemoveFromForm);
			}
		}

		/**
		 * Removes the child control with the given id
		 * @param string $strControlId
		 * @param boolean $blnRemoveFromForm should the control be removed from the form, too?
		 */
		public function RemoveChildControl($strControlId, $blnRemoveFromForm) {
			$this->blnModified = true;
			if (array_key_exists($strControlId, $this->objChildControlArray)) {
				$objChildControl = $this->objChildControlArray[$strControlId];
				$objChildControl->objParentControl = null;
				unset($this->objChildControlArray[$strControlId]);

				if ($blnRemoveFromForm)
					$this->objForm->RemoveControl($objChildControl->ControlId);
			}
		}

		/**
		 * Adds an action to the control
		 *
		 * @param QEvent  $objEvent
		 * @param QAction $objAction
		 *
		 * @throws QCallerException
		 */
		public function AddAction($objEvent, $objAction) {
			if (!($objEvent instanceof QEvent)) {
				throw new QCallerException('First parameter of AddAction is expecting an object of type QEvent');
			}

			if (!($objAction instanceof QAction)) {
				throw new QCallerException('Second parameter of AddAction is expecting an object of type QAction');
			}

			// Modified
			$this->blnModified = true;

			// Store the Event object in the Action object
			if ($objAction->Event) {
				//this Action is in use -> clone it
				$objAction = clone($objAction);
			}
			$objAction->Event = $objEvent;

			// Pull out the Event Name
			$strEventName = $objEvent->EventName;

			if (!array_key_exists($strEventName, $this->objActionArray))
				$this->objActionArray[$strEventName] = array();
			array_push($this->objActionArray[$strEventName], $objAction);
		}

		/**
		 * Adds an array of actions to the control
		 *
		 * @param QEvent $objEvent
		 * @param array  $objActionArray
		 *
		 * @throws QCallerException
		 */
		public function AddActionArray($objEvent, $objActionArray) {
			if (!($objEvent instanceof QEvent)) {
				throw new QCallerException('First parameter of AddAction is expecting on object of type QEvent');
			}

			foreach ($objActionArray as $objAction) {
				$objAction = clone($objAction);
				$this->AddAction($objEvent, $objAction);
			}
		}

		/**
		 * Removes all events for a given event name.
		 *
		 * Be sure and use a QFooEvent::EventName constant here
		 * (QClickEvent::EventName, for example).
		 *
		 * @param string $strEventName
		 */
		public function RemoveAllActions($strEventName) {
			// Modified
			$this->blnModified = true;

			$this->objActionArray[$strEventName] = array();
		}

		/**
		 * Returns all actions that are connected with specific events
		 * @param string $strEventType
		 *  the type of the event. Be sure and use a
		 *  QFooEvent::EventName here. (QClickEvent::EventName, for example)
		 * @param string $strActionType if given only actions of this type will be
		 *  returned
		 * @return array
		 */
		public function GetAllActions($strEventType, $strActionType = null) {
			$objArrayToReturn = array();
			if ($this->objActionArray) foreach ($this->objActionArray as $objActionArray) {
				foreach ($objActionArray as $objAction)
					if (get_class($objAction->Event) == $strEventType) {
						if ((!$strActionType) ||
							($objAction instanceof $strActionType))
							array_push($objArrayToReturn, $objAction);
					}
			}

			return $objArrayToReturn;
		}

		/**
		 * Sets one custom attribute
		 *
		 * Custom Attributes refers to the html name-value pairs that can be rendered within the control that are not
		 * covered by an explicit method. For example, on a textbox, you can render any number of additional name-value
		 * pairs, to assign additional javascript actions, additional formatting, etc.
		 * <code>
		 * <?php
		 * $txtTextbox = new Textbox("txtTextbox");
		 * $txtTextbox->SetCustomAttribute("onfocus", "alert('You are about to edit this field');");
		 * $txtTextbox->SetCustomAttribute("nowrap", "nowrap");
		 * $txtTextbox->SetCustomAttribute("blah", "foo");
		 * ?>
		 * </code>
		 * Will render:
		 * <code>
		 *   <input type="text" ...... onfocus="alert('You are about to edit this field');" nowrap="nowrap" blah="foo" />
		 * </code>
		 *
		 * @param string $strName
		 * @param string $strValue
		 */
		public function SetCustomAttribute($strName, $strValue) {
			$this->blnModified = true;
			if (!is_null($strValue))
				$this->strCustomAttributeArray[$strName] = $strValue;
			else {
				$this->strCustomAttributeArray[$strName] = null;
				unset($this->strCustomAttributeArray[$strName]);
			}
		}

		/**
		 * Returns the value of a custom attribute
		 *
		 * @param string $strName
		 *
		 * @throws QCallerException
		 * @return string
		 */
		public function GetCustomAttribute($strName) {
			if ((is_array($this->strCustomAttributeArray)) && (array_key_exists($strName, $this->strCustomAttributeArray)))
				return $this->strCustomAttributeArray[$strName];
			else
				throw new QCallerException(sprintf("Custom Attribute does not exist in Control '%s': %s", $this->strControlId, $strName));
		}

		/**
		 * Removes the given custom attribute
		 *
		 * @param string $strName
		 *
		 * @throws QCallerException
		 */
		public function RemoveCustomAttribute($strName) {
			$this->blnModified = true;
			if ((is_array($this->strCustomAttributeArray)) && (array_key_exists($strName, $this->strCustomAttributeArray))) {
				$this->strCustomAttributeArray[$strName] = null;
				unset($this->strCustomAttributeArray[$strName]);
			} else
				throw new QCallerException(sprintf("Custom Attribute does not exist in Control '%s': %s", $this->strControlId, $strName));
		}

		/**
		 * Adds a custom style property/value to the html style attribute
		 *
		 * Sets a custom css property. For example:
		 * <code>
		 * <?php
		 * $txtTextbox = new Textbox("txtTextbox");
		 * $txtTextbox->SetCustomStyle("white-space", "nowrap");
		 * $txtTextbox->SetCustomStyle("margin", "10px");
		 * ?>
		 * </code>
		 * Will render:
		 * <code>
		 * 		<input type="text" ...... style="white-space:nowrap;margin:10px" />
		 * </code>
		 *
		 * @param string $strName
		 * @param string $strValue
		 */
		public function SetCustomStyle($strName, $strValue) {
			$this->blnModified = true;
			if (!is_null($strValue))
				$this->strCustomStyleArray[$strName] = $strValue;
			else {
				$this->strCustomStyleArray[$strName] = null;
				unset($this->strCustomStyleArray[$strName]);
			}
		}

		/**
		 * Returns the value of the given custom style
		 *
		 * @param string $strName
		 *
		 * @throws QCallerException
		 * @return string
		 */
		public function GetCustomStyle($strName) {
			if ((is_array($this->strCustomStyleArray)) && (array_key_exists($strName, $this->strCustomStyleArray)))
				return $this->strCustomStyleArray[$strName];
			else
				throw new QCallerException(sprintf("Custom Style does not exist in Control '%s': %s", $this->strControlId, $strName));
		}

		/**
		 * Deletes the given custom style
		 *
		 * @param string $strName
		 *
		 * @throws QCallerException
		 */
		public function RemoveCustomStyle($strName) {
			$this->blnModified = true;
			if ((is_array($this->strCustomStyleArray)) && (array_key_exists($strName, $this->strCustomStyleArray))) {
				$this->strCustomStyleArray[$strName] = null;
				unset($this->strCustomStyleArray[$strName]);
			} else
				throw new QCallerException(sprintf("Custom Style does not exist in Control '%s': %s", $this->strControlId, $strName));
		}

        /**
         * Add javascript file to be included in the form.
         * The  include mechanism will take care of duplicates, and also change the given URL in the following ways:
         * 	- If the file name begins with 'http', it will use it directly as a URL
         *  - If the file name begins with '/', the url will be relative to  __DOCROOT__ . __VIRTUAL_DIRECTORY__
         *  - If the file name begins with anything else, the url will be relative to __JS_ASSETS__
         *
         * @param string $strJsFileName url, path, or file name to include
         */
        public function AddJavascriptFile($strJsFileName) {
            if($this->strJavaScripts) {
                $this->strJavaScripts .= ','.$strJsFileName;
            } else {
                $this->strJavaScripts = $strJsFileName;
            }
        }

        /**
		 * Add javascript file to be included from a plugin. Plugins should use this function instead of AddJavascriptFile.
		 * The  include mechanism will take care of duplicates, and also change the given URL in the following ways:
		 * 	- If the file name begins with 'http', it will use it directly as a URL
		 *  - If the file name begins with '/', the url will be relative to the __DOCROOT__ . __VIRTUAL_DIRECTORY__ directory.
		 *  - If the file name begins with anything else, the url will be relative to __PLUGIN_ASSETS__/pluginName/js/
		 *  
		 * @param string $strPluginName name of plugin
		 * @param string $strJsFileName url, path, or file name to include
		 */
		public function AddPluginJavascriptFile($strPluginName, $strJsFileName) {
			if (strpos($strJsFileName, "http") === 0) {
				$this->AddJavascriptFile($strJsFileName);	// plugin uses js from some other website
			}
			else {
				if (strpos($strJsFileName, "/") === 0) {
					// custom location for plugin javascript, relative to virtual directory
					$this->AddJavascriptFile($strJsFileName);
				} else {
					// Use the default location, relative to plugin's own directory given.
					$this->AddJavascriptFile(__PLUGIN_ASSETS__ . '/' . $strPluginName . "/js/" . $strJsFileName);
				}
			}
		}

		/**
		 * Add style sheet file to be included in the form.
		 * The  include mechanism will take care of duplicates, and also change the given URL in the following ways:
		 * 	- If the file name begins with 'http', it will use it directly as a URL
		 *  - If the file name begins with '/', the url will be relative to the ___DOCROOT__ . __VIRTUAL_DIRECTORY__
		 *  - If the file name begins with anything else, the url will be relative to __CSS_ASSETS__
		 *  
		 * @param string $strCssFileName url, path, or file name to include
		 */
		public function AddCssFile($strCssFileName) {
			if($this->strStyleSheets) {
				$this->strStyleSheets .= ','.$strCssFileName;
			} else {
				$this->strStyleSheets = $strCssFileName;
			}
		}

		/**
		 * Add style sheet file to be included from a plugin. Plugins should use this function instead of AddCssFile.
		 * The  include mechanism will take care of duplicates, and also change the given URL in the following ways:
		 * 	- If the file name begins with 'http', it will use it directly as a URL
		 *  - If the file name begins with '/', the url will be relative to the __PLUGIN_ASSETS__ directory.
		 *  - If the file name begins with anything else, the url will be relative to __PLUGIN_ASSETS__/pluginName/css/
		 *  
		 * @param string $strPluginName name of plugin
		 * @param string $strCssFileName url, path, or file name to include
		 */
		public function AddPluginCssFile($strPluginName, $strCssFileName) {
			if (strpos($strCssFileName, "http") === 0) {
				$this->AddCssFile($strCssFileName);	// plugin uses style sheet from some other website
			}
			else {
				if (strpos($strCssFileName, "/") === 0) {
					// custom location for plugin javascript, relative to virtual dir
					$this->AddCssFile($strCssFileName);
				} else {
					// Use the default location 
					$this->AddCssFile(__PLUGIN_ASSETS__ . '/' . $strPluginName . "/css/" . $strCssFileName);
				}
			}
		}


		/**
		 * This will add a CssClass name to the CssClass property (if it does not yet exist),
		 * updating the CssClass property accordingly.
		 * @param string $strCssClassName
		 */
		public function AddCssClass($strCssClassName) {
			$blnAdded = false;
			$strNewCssClass = '';
			$strCssClassName = trim($strCssClassName);

			foreach (explode(' ', $this->strCssClass) as $strCssClass)
				if ($strCssClass = trim($strCssClass)) {
					if ($strCssClass == $strCssClassName)
						$blnAdded = true;
					$strNewCssClass .= $strCssClass . ' ';
				}
			if (!$blnAdded)
				$this->CssClass = $strNewCssClass . $strCssClassName;
			else
				$this->CssClass = trim($strNewCssClass);
		}

		/**
		 * This will remove a CssClass name from the CssClass property (if it exists),
		 * updating the CssClass property accordingly.
		 * @param string $strCssClassName
		 */
		public function RemoveCssClass($strCssClassName) {
			$strNewCssClass = '';
			$strCssClassName = trim($strCssClassName);
			foreach (explode(' ', $this->strCssClass) as $strCssClass)
				if ($strCssClass = trim($strCssClass)) {
					if ($strCssClass != $strCssClassName)
						$strNewCssClass .= $strCssClass . ' ';
				}
			$this->CssClass = trim($strNewCssClass);
		}

		/**
		 * ParsePostData parses the value of this control from FormState
		 *
		 * This abstract method must be implemented by all controls.
		 *
		 * When utilizing formgen, the programmer should never access form variables directly (e.g.
		 * via the $_FORM array). It can be assumed that at *ANY* given time, a control's
		 * values/properties will be "up to date" with whatever the webuser has entered in.
		 *
		 * When a Form is Created via Form::Create(string), the form will go through to check and
		 * see if it is a first-run of a form, or if it is a post-back.  If it is a postback, it
		 * will go through its own private array of controls and call ParsePostData on EVERY control
		 * it has.  Each control is responsible for "knowing" how to parse the $_POST data to update
		 * its own values/properties based on what was returned to via the postback.
		 */
		abstract public function ParsePostData();


		/**
		 * Returns all attributes in the correct HTML format
		 *
		 * This is utilized by Render methods to display various name-value HTML attributes for the
		 * control.
		 *
		 * ControlBase's implementation contains the very-basic set of HTML attributes... it is expected
		 * that most subclasses will extend this method's functionality to add Control-specific HTML
		 * attributes (e.g. textbox will likely add the maxlength html attribute, etc.)
		 *
		 * @param boolean $blnIncludeCustom Include Custom attributes?
		 * @param boolean $blnIncludeAction Include Action attributes?
		 * @return string
		 */
		public function GetAttributes($blnIncludeCustom = true, $blnIncludeAction = true) {
			$blnIncludeAction = false;
			$strToReturn = "";

			if (!$this->blnEnabled)
				$strToReturn .= 'disabled="disabled" ';
			if ($this->intTabIndex)
				$strToReturn .= sprintf('tabindex="%s" ', $this->intTabIndex);
			if ($this->strToolTip)
				$strToReturn .= sprintf('title="%s" ', QApplication::HtmlEntities($this->strToolTip));
			if ($this->strCssClass)
				$strToReturn .= sprintf('class="%s" ', $this->strCssClass);
			if ($this->strAccessKey)
				$strToReturn .= sprintf('accesskey="%s" ', $this->strAccessKey);

			if ($blnIncludeCustom)
				$strToReturn .= $this->GetCustomAttributes();

			if ($blnIncludeAction)
				$strToReturn .= $this->GetActionAttributes();

			return $strToReturn;
		}

		/**
		 * Returns the custom attributes HTML formatted
		 *
		 * All attributes will be returned as concatened the string of the form
		 * key1="value1" key2="value2"
		 * Note: if the the value is === false, then the key will be randered as is, without any value
		 *
		 * @return string
		 */
		public function GetCustomAttributes() {
			$strToReturn = '';
			if ($this->strCustomAttributeArray)
				foreach ($this->strCustomAttributeArray as $strKey => $strValue) {
					if ($strValue === false) {
						$strToReturn .= $strKey . ' ';
					} else {
						$strToReturn .= sprintf('%s="%s" ', $strKey, $strValue);
					}
				}

			return $strToReturn;
		}

		/**
		 * Returns all action attributes for this QControl
		 *
		 * @return string
		 */
		public function GetActionAttributes() {
			$strToReturn = '';
			foreach ($this->objActionArray as $strEventName => $objActions)
				$strToReturn .= $this->GetJavaScriptForEvent($strEventName);
			return $strToReturn;
		}

		/**
		 * Get the JavaScript for a given Element
		 * @param string $strEventName
		 *
		 * @return null|string
		 */
		public function GetJavaScriptForEvent($strEventName) {
			return QAction::RenderActions($this, $strEventName, $this->objActionArray[$strEventName]);
		}

		/**
		 * Returns all style-attributes
		 *
		 * Similar to GetAttributes, but specifically for CSS name/value pairs that will render
		 * within a control's HTML "style" attribute
		 *
		 * <code>
		 * <?php
		 * $txtTextbox = new Textbox("txtTextbox");
		 * $txtTextbox->SetCustomStyle("white-space", "nowrap");
		 * $txtTextbox->SetCustomStyle("margin", "10px");
		 * $txtTextBox->Height = 20;
		 * $txtTextBox->GetStyleAttributes();
		 * ?>
		 * will return:
		 * white-space:nowrap;margin:10px;height:20px;
		 *
		 * @return string
		 */
		public function GetStyleAttributes() {
			$strToReturn = "";

			if (strlen(trim($this->strWidth)) > 0) {
				$strToReturn .= sprintf('width:%s;', QCss::FormatLength($this->strWidth));
			}
			if (strlen(trim($this->strHeight)) > 0) {
				$strToReturn .= sprintf('height:%s;', QCss::FormatLength($this->strHeight));
			}
			if ($this->blnUseWrapper) {
				if (($this->strDisplayStyle) && ($this->strDisplayStyle != QDisplayStyle::NotSet)) {
					$strToReturn .= sprintf("display:%s;", $this->strDisplayStyle);
				}
			} else {
				if (($this->blnDisplay) &&($this->strDisplayStyle) && ($this->strDisplayStyle != QDisplayStyle::NotSet)) {
					//only apply a display style if it should be displayed and a style is set
					//in case of blnDisplay == false the "display:none;" is set in GetWrapperStyleAttributes
					$strToReturn .= sprintf("display:%s;", $this->strDisplayStyle); 
				}
				$strToReturn .= $this->GetWrapperStyleAttributes();
			}
			if ($this->strForeColor)
				$strToReturn .= sprintf("color:%s;", $this->strForeColor);
			if ($this->strBackColor)
				$strToReturn .= sprintf("background-color:%s;", $this->strBackColor);
			if ($this->strBorderColor)
				$strToReturn .= sprintf("border-color:%s;", $this->strBorderColor);
			if (strlen(trim($this->strBorderWidth)) > 0) {
				$strToReturn .= sprintf('border-width:%s;', QCss::FormatLength($this->strBorderWidth));

				if ((!$this->strBorderStyle) || ($this->strBorderStyle == QBorderStyle::NotSet))
					// For "No Border Style" -- apply a "solid" style because width is set
						$strToReturn .= "border-style:solid;";
			}
			if (($this->strBorderStyle) && ($this->strBorderStyle != QBorderStyle::NotSet))
				$strToReturn .= sprintf("border-style:%s;", $this->strBorderStyle);

			if ($this->strFontNames)
				$strToReturn .= sprintf("font-family:%s;", $this->strFontNames);
			if ($this->strFontSize) {
				if (is_numeric($this->strFontSize))
					$strToReturn .= sprintf("font-size:%spx;", $this->strFontSize);
				else
					$strToReturn .= sprintf("font-size:%s;", $this->strFontSize);
			}
			if ($this->blnFontBold)
				$strToReturn .= "font-weight:bold;";
			if ($this->blnFontItalic)
				$strToReturn .= "font-style:italic;";

			$strTextDecoration = "";
			if ($this->blnFontUnderline)
				$strTextDecoration .= "underline ";
			if ($this->blnFontOverline)
				$strTextDecoration .= "overline ";
			if ($this->blnFontStrikeout)
				$strTextDecoration .= "line-through ";

			if ($strTextDecoration) {
				$strTextDecoration = trim($strTextDecoration);
				$strToReturn .= sprintf("text-decoration:%s;", $strTextDecoration);
			}

			if (($this->strCursor) && ($this->strCursor != QCursor::NotSet))
				$strToReturn .= sprintf("cursor:%s;", $this->strCursor);

			if (($this->strOverflow) && ($this->strOverflow != QOverflow::NotSet))
				$strToReturn .= sprintf("overflow:%s;", $this->strOverflow);

			if (!is_null($this->intOpacity)) {
				if (QApplication::IsBrowser(QBrowserType::InternetExplorer) && QApplication::$BrowserVersion < 9)
					$strToReturn .= sprintf('filter:alpha(opacity=%s);', $this->intOpacity);
				else
					$strToReturn .= sprintf('opacity:%s;', $this->intOpacity / 100.0);
			}
			if ($this->strCustomStyleArray) foreach ($this->strCustomStyleArray as $strKey => $strValue)
				$strToReturn .= sprintf('%s:%s;', $strKey, QCss::FormatLength($strValue));

			return $strToReturn;
		}

		/**
		 * Returns all wrapper-style-attributes
		 * Similar to GetStyleAttributes, but specifically for CSS name/value pairs that will render
		 * within a "wrapper's" HTML "style" attribute
		 *
		 * @param bool $blnIsBlockElement
		 *
		 * @return string
		 */
		protected function GetWrapperStyleAttributes($blnIsBlockElement=false) {
			$strStyle = '';
			if (($this->strPosition) && ($this->strPosition != QPosition::NotSet))
				$strStyle .= sprintf('position:%s;', $this->strPosition);

			if (!$this->blnDisplay)
				$strStyle .= 'display:none;';
			else if ($blnIsBlockElement)
				$strStyle .= 'display:inline;';

			if (strlen(trim($this->strLeft)) > 0) {
				$strStyle .= sprintf('left:%s;', QCss::FormatLength($this->strLeft));
			}

			if (strlen(trim($this->strTop)) > 0) {
				$strStyle .= sprintf('top:%s;', QCss::FormatLength($this->strTop));
			}
			
			return $strStyle;
		}

		/**
		 * RenderHelper should be called from all "Render" functions FIRST in order to check for and
		 * perform attribute overrides (if any).
		 * All render methods should take in an optional first boolean parameter blnDisplayOutput
		 * (default to true), and then any number of attribute overrides.
		 * Any "Render" method (e.g. Render, RenderWithName, RenderWithError) should call the
		 * RenderHelper FIRST in order to:
		 * <ul>
		 * <li>Check for and perform attribute overrides</li>
		 * <li>Check to see if this control is "Visible".  If it is Visible=false, then
		 *        the renderhelper will cause the method to immediately return</li>
		 * </ul>
		 * Proper usage within the first line of any Render() method is:
		 *        <code>$this->RenderHelper(func_get_args(), __FUNCTION__);</code>
		 * See {@link QControl::RenderWithName()} as example.
		 *
		 * @param mixed  $mixParameterArray the parameters given to the render call
		 * @param string $strRenderMethod   the method which has been used to render the
		 *                           control. This is important for ajax rerendering
		 *
		 * @throws QCallerException
		 * @throws Exception|QCallerException
		 * @see QControlBase::RenderOutput()
		 */
		protected function RenderHelper($mixParameterArray, $strRenderMethod) {
			// Make sure the form is already "RenderBegun"
			if ((!$this->objForm) || ($this->objForm->FormStatus != QForm::FormStatusRenderBegun)) {
				if (!$this->objForm)
					$objExc = new QCallerException('Control\'s form does not exist.  It could be that you are attempting to render after RenderEnd() has been called on the form.');
				else if ($this->objForm->FormStatus == QForm::FormStatusRenderEnded)
					$objExc = new QCallerException('Control cannot be rendered after RenderEnd() has been called on the form.');
				else
					$objExc = new QCallerException('Control cannot be rendered until RenderBegin() has been called on the form.');

				// Incremement because we are two-deep below the call stack
				// (e.g. the Render function call, and then this RenderHelper call)
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Make sure this hasn't yet been rendered
			if (($this->blnRendered) || ($this->blnRendering)) {
				$objExc = new QCallerException('This control has already been rendered: ' . $this->strControlId);

				// Incremement because we are two-deep below the call stack
				// (e.g. the Render function call, and then this RenderHelper call)
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Let's remember *which* render method was used to render this control
			$this->strRenderMethod = $strRenderMethod;

			// Apply any overrides (if applicable)
			if (count($mixParameterArray) > 0) {
				if (gettype($mixParameterArray[0]) != QType::String) {
					// Pop the first item off the array
					$mixParameterArray = array_reverse($mixParameterArray);
					array_pop($mixParameterArray);
					$mixParameterArray = array_reverse($mixParameterArray);
				}

				// Override
				try {
					$this->OverrideAttributes($mixParameterArray);
				} catch (QCallerException $objExc) {
					// Incremement Twice because we are two-deep below the call stack
					// (e.g. the Render function call, and then this RenderHelper call)
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}

			// Because we may be re-rendering a parent control, we need to make sure all "children" controls are marked as NOT being on the page.
			foreach ($this->GetChildControls() as $objChildControl)
				$objChildControl->blnOnPage = false;

			// Finally, let's specify that we have begun rendering this control
			$this->blnRendering = true;
		}

		/**
		 * The current use of this function is unknown at the moment. Need to dig deeper.
		 */
		protected function GetNonWrappedHtml() {}

		/**
		 * Sets focus to this control
		 */
		public function Focus() {
			QApplication::ExecuteJavaScript(sprintf('qc.getW("%s").focus();', $this->strControlId));
		}

		/**
		 * Same as "Focus": Sets focus to this control
		 */
		public function SetFocus() {
			QApplication::ExecuteJavaScript(sprintf('qc.getW("%s").focus()', $this->strControlId));
		}

		/**
		 * Let this control blink
		 *
		 * @param string $strFromColor start color
		 * @param string $strToColor blink color
		 */
		public function Blink($strFromColor = '#ffff66', $strToColor = '#ffffff') {
			QApplication::ExecuteJavaScript(sprintf('qc.getW("%s").blink("%s", "%s");', $this->strControlId, $strFromColor, $strToColor));
		}

		/**
		 * Returns all Javscript that needs to be executed after rendering of this control
		 *
		 * For any JavaScript calls that need to be made whenever this control is rendered or
		 * re-rendered return here your custom javascript code.
		 *
		 * Remember to call $strToReturn = parent::GetEndScript if you want to have basic moveable support.
		 *
		 * @return string
		 */
		public function GetEndScript() {

			$strToReturn = $this->GetActionAttributes();

			if ($this->objResizable)
				$strToReturn = sprintf('%s; %s', $this->objResizable->GetControlJavaScript(), $strToReturn);

			if ($this->objDraggable)
				$strToReturn = sprintf('%s; %s', $this->objDraggable->GetControlJavaScript(), $strToReturn);

			if ($this->objDroppable)
				$strToReturn = sprintf('%s; %s', $this->objDroppable->GetControlJavaScript(), $strToReturn);

			// This allows display settings to be applied before other control-specific javascript code is executed
			// It is important if control was hidden and is now appearing to be visible, and
			// the control or any of it's childs has a javascript code that depends on jquery width or outerWidth API.
			// In this case the control should be made visible (display: any-non-hidden-value) first and only then
			// the jquery width or outerWidth API can be used.
			if (($this->blnWrapperModified) && ($this->blnVisible) && ($this->blnUseWrapper)) {
				$strWrapperStyle = $this->GetWrapperStyleAttributes($this->blnIsBlockElement);
				$strJavaScript = sprintf('w = qc.getW("%s"); w.style.cssText = "%stext-decoration:inherit;"; w.className = "%s";', $this->strControlId, $strWrapperStyle, $this->strWrapperCssClass);
				$strToReturn = sprintf('%s; %s', $strJavaScript, $strToReturn);
			}

            $this->strAttributeScripts = null; // erase the attribute scripts, because the entire control is being drawn.

			return $strToReturn;
		}

        /**
         * Return one-time scripts associated with the control.
         *
         * @return null|string
         */
        public function RenderAttributeScripts()
        {
            if ($this->strAttributeScripts) {
                $strToReturn = implode (";\n", $this->strAttributeScripts);
                $this->strAttributeScripts = null;
                return $strToReturn;
            }
            else {
                return '';
            }
        }

        /**
         * Executes a java script associated with the control. These scripts are specifically for the purpose of
         * changing some attribute of the control that would also be taken care of during a refresh of the entire
         * control. The script will only be executed if the entire control is not redrawn.
         *
         * @param string $strScript
         */
        public function AddAttributeScript ($strScript)
        {
            $this->strAttributeScripts[] = $strScript;
        }

		/**
		 * For any HTML code that needs to be rendered at the END of the QForm when this control is
		 * INITIALLY rendered.
		 *
		 * This function is never used throughout the whole framework. So it probably should be
		 * deprecated. Only Call to this function is in QFormBase Line 1171.
		 * @deprecated
		 */
		public function GetEndHtml() {}

		/**
		 * Refreshes the control
		 *
		 * If not yet rendered during this ajax event, will set the Modified variable to true.  This will
		 * have the effect of forcing a refresh of this control when it is supposed to be rendered.
		 * Otherwise, this will do nothing
		 */
		public function Refresh() {
			if ((!$this->blnRendered) && (!$this->blnRendering))
				$this->blnModified = true;
		}

		/**
		 * RenderOutput should be the last call in your custom RenderMethod.
		 * RenderOutput wraps your content with valid divs and control-identifiers, echos your code
		 * to the content buffer or simply returns it. See {@link QControlBase::RenderHelper()}.
		 *
		 * @param string  $strOutput
		 *   Your html-code which should be given out
		 * @param boolean $blnDisplayOutput
		 *   should it be given out, or just be returned?
		 * @param boolean $blnForceAsBlockElement
		 *   should it be given out as a block element, regardless of its configured tag?
		 * @param string  $strWrapperAttributes
		 *
		 * @return string
		 */
		protected function RenderOutput($strOutput, $blnDisplayOutput, $blnForceAsBlockElement = false, $strWrapperAttributes = '') {
			// First, let's mark this control as being rendered and is ON the Page
			$this->blnRendering = false;
			$this->blnRendered = true;
			$this->blnOnPage = true;

			$strWrapperStyle='';
			// Determine whether or not $strOutput is considered a XHTML "Block" Element
			$blnIsBlockElement = $blnForceAsBlockElement || $this->blnIsBlockElement;
			if($this->blnUseWrapper) {
				// Check for Visibility
				if (!$this->blnVisible)
					$strOutput = '';

				$strWrapperStyle = $this->GetWrapperStyleAttributes($blnIsBlockElement);

				if ($this->strWrapperCssClass)
					$strWrapperAttributes .= sprintf(' class="%s"', $this->strWrapperCssClass);
			} else if (!$this->blnVisible) {
				/*no wrapper is used + the control should not be visible
				 *	--> render a span with the control id and display:none
				 *  This allows us to change blnVisible to true in an Ajax call
				 *  as the span will get replaced with the real control 
				 */
				$strOutput = sprintf('<span id="%s" style="display:none;"></span>', $this->strControlId);
			}

			switch ($this->objForm->CallType) {
				case QCallType::Ajax:
					// If we have a ParentControl and the ParentControl has NOT been rendered, then output
					// as standard HTML
					if (($this->objParentControl) && ($this->objParentControl->Rendered || $this->objParentControl->Rendering)) {
						if ($strWrapperStyle)
							$strWrapperStyle = sprintf('style="%s"', $strWrapperStyle);
						if($this->blnUseWrapper) {
							if ($blnIsBlockElement)
								$strOutput = sprintf('<div id="%s_ctl" %s%s>%s</div>%s', $this->strControlId, $strWrapperStyle, $strWrapperAttributes, $strOutput, $this->GetNonWrappedHtml());
							else
								$strOutput = sprintf('<span id="%s_ctl" %s%s>%s</span>%s', $this->strControlId, $strWrapperStyle, $strWrapperAttributes, $strOutput, $this->GetNonWrappedHtml());
						} else {
							$strOutput = $strOutput . $this->GetNonWrappedHtml();
						}
					} else {
						// Otherwise, we are rendering as a top-level AJAX response
						// Surround Output HTML around CDATA tags
						$strOutput = QString::XmlEscape($strOutput);
						// use the wrapper attribute to pass in the special attribute data-hasrel (if no wrappers are used and RenderWithError or similar methods are called)
						$strOutput = sprintf('<control id="%s" %s>%s</control>', $this->strControlId, $strWrapperAttributes, $strOutput);

						// This code was moved to the GetEndScript function.
						// See comment there for an explanation.
//						if (($this->blnWrapperModified) && ($this->blnVisible) && ($this->blnUseWrapper)) {
//							QApplication::ExecuteJavaScript(sprintf('w = qc.getW("%s"); w.style.cssText = "%stext-decoration:inherit;"; w.className = "%s";', $this->strControlId, $strWrapperStyle, $this->strWrapperCssClass), QJsPriority::High);
//						}
					}
					break;

				default:
					if ($strWrapperStyle)
						$strWrapperStyle = sprintf('style="%s"', $strWrapperStyle);

					if ($this->blnUseWrapper) {
						if ($blnIsBlockElement)
							$strOutput = sprintf('<div id="%s_ctl" %s%s>%s</div>%s', $this->strControlId, $strWrapperStyle, $strWrapperAttributes, $strOutput, $this->GetNonWrappedHtml());
						else
							$strOutput = sprintf('<span id="%s_ctl" %s%s>%s</span>%s', $this->strControlId, $strWrapperStyle, $strWrapperAttributes, $strOutput, $this->GetNonWrappedHtml());
					} else {
						$strOutput = $strOutput . $this->GetNonWrappedHtml();
					}
					break;
			}

			// Update watcher
			if ($this->objWatcher) {
				$this->objWatcher->MakeCurrent();
			}

			// Output or Return
			if ($blnDisplayOutput)
				print($strOutput);
			else
				return $strOutput;
		}

		/**
		 * This method will render the control, itself, and will return the rendered HTML as a string
		 *
		 * As an abstract method, any class extending QControlBase must implement it.  This ensures that
		 * each control has its own specific html.
		 * @return string
		 */
		abstract protected function GetControlHtml();

		/**
		 * This render method is the most basic render-method available.
		 * It will perform attribute overiding (if any) and will either display the rendered
		 * HTML (if blnDisplayOutput is true, which it is by default), or it will return the
		 * rendered HTML as a string.
		 *
		 * @param boolean $blnDisplayOutput render the control or return as string
		 *
		 * @throws Exception|QCallerException
		 * @return string
		 */
		public function Render($blnDisplayOutput = true) {
			// Call RenderHelper
			$this->RenderHelper(func_get_args(), __FUNCTION__);

			try {
				$strOutput = sprintf('%s%s%s',
					$this->strHtmlBefore,
					$this->GetControlHtml(),
					$this->strHtmlAfter
				);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Call RenderOutput, Returning its Contents
			return $this->RenderOutput($strOutput, $blnDisplayOutput);
		}

		/**
		 * RenderAjax will be called during an Ajax-Rerendering of the controls due to it being modified
		 * @param boolean $blnDisplayOutput render the control or return as string
		 * @return string
		 */
		public function RenderAjax($blnDisplayOutput = true) {
			// Only render if this control has been modified at all
			if ($this->IsModified()) {

				// Render if (1) object has no parent or (2) parent was not rendered nor currently being rendered
				if ((!$this->objParentControl) || ((!$this->objParentControl->Rendered) && (!$this->objParentControl->Rendering))) {
					$strRenderMethod = $this->strRenderMethod;
					if ($strRenderMethod)
						return $this->$strRenderMethod($blnDisplayOutput);
				}
			}
			// The following line is to suppres the warning in PhpStorm
			return '';
		}

		/**
		 * Returns true if the control should be redrawn.
		 * @return bool
		 */
		protected function IsModified() {
			return ($this->blnModified ||
				($this->objWatcher && !$this->objWatcher->IsCurrent()));
		}

		/**
		 * Renders all Children
		 * @param boolean $blnDisplayOutput display output (echo out) or just return as string
		 * @return string
		 */
		protected function RenderChildren($blnDisplayOutput = true) {
			$strToReturn = "";

			foreach ($this->GetChildControls() as $objControl) {
				if (!$objControl->Rendered) {
					$renderMethod = $objControl->strPreferedRenderMethod;
					$strToReturn .= $objControl->$renderMethod($blnDisplayOutput);
				}
			}

			if ($blnDisplayOutput) {
				print($strToReturn);
				return null;
			} else
				return $strToReturn;
		}

		/**
		 * This render method will render the control with additional output of
		 * any validation errors, that might occur
		 *
		 * @param boolean $blnDisplayOutput display output (echo out) or just return as string
		 *
		 * @throws Exception|QCallerException
		 * @return string
		 */
		public function RenderWithError($blnDisplayOutput = true) {
			// Call RenderHelper
			$this->RenderHelper(func_get_args(), __FUNCTION__);

			/*if we do not use a wrapper we have to ensure that the error element
				gets removed on an ajax update.
				==> we pass a special attribute to the top level ajax response element 
				(called "control" <== created in RenderOutput)
				If this attribute is present, all elements that have an attribute
				data-rel="controlid_of_the_related_control" are removed before updating
			    the control --> no duplication of error/warning controls 
			 */
			$strWrapperAttributes = '';
			$strDataRel = '';
			if (!$this->blnUseWrapper) {
				$strWrapperAttributes = 'data-hasrel="1" ';
				$strDataRel = sprintf('data-rel="#%s" ', $this->strControlId);
			}
			
			try {
				$strOutput = $this->GetControlHtml();

				if ($this->strValidationError)
					$strOutput .= sprintf('<br %s/><span %sclass="warning">%s</span>', $strDataRel, $strDataRel, $this->strValidationError);
				else if ($this->strWarning)
					$strOutput .= sprintf('<br %s/><span %sclass="warning">%s</span>', $strDataRel, $strDataRel, $this->strWarning);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Call RenderOutput, Returning its Contents
			return $this->RenderOutput($strOutput, $blnDisplayOutput, false, $strWrapperAttributes);
		}


		/**
		 * Renders the control with an attached name
		 *
		 * This will call {@link QControlBase::GetControlHtml()} for the bulk of the work, but will add layout html as well.  It will include
		 * the rendering of the Controls' name label, any errors or warnings, instructions, and html before/after (if specified).
		 * As this is the parent class of all controls, this method defines how ALL controls will render when rendered with a name.
		 * If you need certain controls to display differently, override this function in that control's class.
		 *
		 * @param boolean $blnDisplayOutput true to send to display buffer, false to just return then html
		 * @throws QCallerException
		 * @return string HTML of rendered Control
		 */
		public function RenderWithName($blnDisplayOutput = true) {
			////////////////////
			// Call RenderHelper
			$this->RenderHelper(func_get_args(), __FUNCTION__);
			////////////////////

			$strDataRel = '';
			$strWrapperAttributes = '';
			if (!$this->blnUseWrapper) {
				//there is no wrapper --> add the special attribute data-rel to the name control
				$strDataRel = sprintf('data-rel="#%s"',$this->strControlId);
				$strWrapperAttributes = 'data-hasrel="1"';
			}

			// Custom Render Functionality Here

			// Because this example RenderWithName will render a block-based element (e.g. a DIV), let's ensure
			// that IsBlockElement is set to true
			$this->blnIsBlockElement = true;

			// Render the Control's Dressing
			$strToReturn = '<div class="renderWithName" ' . $strDataRel . '>';

			// Render the Left side
			$strLabelClass = "form-name";
			if ($this->blnRequired){
				$strLabelClass .= ' required';
			}
			if (!$this->blnEnabled){
				$strLabelClass .= ' disabled';
			}

			if ($this->strInstructions){
				$strInstructions = '<br/><span class="instructions">' . $this->strInstructions . '</span>';
			}else{
				$strInstructions = '';
			}
			
			$strToReturn .= sprintf('<div class="%s"><label for="%s">%s</label>%s</div>', $strLabelClass, $this->strControlId, $this->strName, $strInstructions);

			// Render the Right side
			$strMessage = '';
			if ($this->strValidationError){
				$strMessage = sprintf('<span class="error">%s</span>', $this->strValidationError);
			}else if ($this->strWarning){
				$strMessage = sprintf('<span class="error">%s</span>', $this->strWarning);
			}
			
			try {
				$strToReturn .= sprintf('<div class="form-field">%s%s%s%s</div>',
					$this->strHtmlBefore,
					$this->GetControlHtml(),
					$this->strHtmlAfter,
					$strMessage);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$strToReturn .= '</div>';

			////////////////////////////////////////////
			// Call RenderOutput, Returning its Contents
			return $this->RenderOutput($strToReturn, $blnDisplayOutput, false, $strWrapperAttributes);
			////////////////////////////////////////////
		}

		/**
		 * Helper method to render the control using some other class/method.
		 *
		 * Useful for plugins that want to override the render behavior for the controls
		 * without modifying the control code.
		 */
		public function RenderExtensionRenderer($classname, $methodname, $args=array()){
			$RenderExtensionInstance = new $classname;
			return $RenderExtensionInstance->{$methodname}($args);
		}

		/**
		 * Checks if this controls contains a valid value.
		 *
		 * This abstract method defines how a control should validate itself based on the value/
		 * properties it has. It should also include the handling of ensuring the "Required"
		 * requirements are obeyed if this control's "Required" flag is set to true.
		 *
		 * For Controls that can't realistically be "validated" (e.g. labels, datagrids, etc.),
		 * those controls should simply have Validate() return true.
		 *
		 * @return boolean
		 */
		abstract public function Validate();

		/**
		 * Validate self + child controls. Controls must mark themselves modified, or somehow redraw themselves
		 * if by failing the validation, they change their visual look in some way (like by adding warning text, turning
		 * red, etc.)
		 *
		 * @return bool
		 */
		public function ValidateControlAndChildren() {
			// Initially Assume Validation is True
			$blnToReturn = true;

			// Check the Control Itself
			if (!$this->Validate()) {
				$blnToReturn = false;
			}

			// Recursive call on Child Controls
			foreach ($this->GetChildControls() as $objChildControl) {
				// Only Enabled and Visible and Rendered controls should be validated
				if (($objChildControl->Visible) && ($objChildControl->Enabled) && ($objChildControl->RenderMethod) && ($objChildControl->OnPage)) {
					if (!$objChildControl->ValidateControlAndChildren()) {
						$blnToReturn = false;
					}
				}
			}

			return $blnToReturn;
		}



		// The following three methods are only intended to be called by code within the Form class.
		// It must be declared as public so that a form object can have access to them, but it really should never be
		// called by user code.
		/**
		 * Reset the control flags to default
		 */
		public function ResetFlags() {
			$this->blnRendered = false;
			$this->blnModified = false;
			$this->blnWrapperModified = false;
		}

		/**
		 * Reset the On-Page status to default (false)
		 */
		public function ResetOnPageStatus() {
			$this->blnOnPage = false;
		}

		/**
		 * Marks this control as modified
		 */
		public function MarkAsModified() {
			$this->blnModified = true;
		}

		/**
		 * Marks the wrapper of this control as modified
		 */
		public function MarkAsWrapperModified() {
			$this->blnWrapperModified = true;
		}

		/**
		 * Marks this control as Rendered
		 */
		public function MarkAsRendered() {
			$this->blnRendered = true;
		}

		/**
		 * Sets the Form of this QControl
		 * @param QForm|QFormBase $objForm
		 */
		public function SetForm($objForm) {
			$this->objForm = $objForm;
		}

		/**
		 * Sets the parent control for this control
		 * @param QControl|QControlBase $objControl The control which has to be set as this control's parent
		 */
		public function SetParentControl($objControl) {
			// Mark this object as modified
			$this->MarkAsModified();

			// Mark the old parent (if applicable) as modified
			if ($this->objParentControl)
				$this->objParentControl->RemoveChildControl($this->ControlId, false);

			// Mark the new parent (if applicable) as modified
			if ($objControl)
				$objControl->AddChildControl($this);
		}

		/**
		 * Resets the validation state to default
		 */
		public function ValidationReset() {
			if (($this->strValidationError) || ($this->strWarning))
				$this->blnModified = true;
			$this->strValidationError = null;
			$this->strWarning = null;
		}

		/**
		 * Runs var_export on this QControl
		 * @param bool $blnReturn Does the result of var_export have to be returned?
		 *
		 * @return mixed
		 */
		public function VarExport($blnReturn = true) {
			if ($this->objForm)
				$this->objForm = $this->objForm->FormId;
			if ($this->objParentControl)
				$this->objParentControl = $this->objParentControl->ControlId;
			if ($blnReturn)
				return var_export($this, true);
		}

		/**
		 * Used by jQuery UI wrapper controls to find the element on which to apply the jQuery function
		 *
		 * NOTE: Some controls that use jQuery will get wrapped with extra divs by the jQuery library.
		 * If such a control then gets replaced by Ajax, the jQuery effects will be deleted. To solve this,
		 * the corresponding QCubed control should set UseWrapper to true, attach the jQuery effect to
		 * the wrapper, and override this function to return the id of the wrapper. See QDialogBase.class.php for
		 * an exaple.
		 *
		 * @return string the DOM element id on which to apply the jQuery UI function
		 */
		public function getJqControlId() {
			return $this->ControlId;
		}

		/**
		 * Watch a particular node in the database. Call this to trigger a redraw of the control
		 * whenever the database table that this node points to is changed.
		 *
		 * @param QQNode $objNode
		 */
		public function Watch (QQNode $objNode)
		{
			if (!$this->objWatcher) {
				$this->objWatcher = new QWatcher(); // only create a watcher object when needed, since it is stored in the form state
			}
			$this->objWatcher->Watch ($objNode);
		}

		/**
		 * Make this control current as of the latest changes so that it will not refresh on the next draw.
		 */
		public function MakeCurrent() {
			if ($this->objWatcher) {
				$this->objWatcher->MakeCurrent();
			}
		}


		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP __get magic method implementation
		 * @param string $strName Property Name
		 *
		 * @return array|bool|int|mixed|null|QControl|QForm|string
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "BackColor": return $this->strBackColor;
				case "BorderColor": return $this->strBorderColor;
				case "BorderStyle": return $this->strBorderStyle;
				case "BorderWidth": return $this->strBorderWidth;
				case "CssClass": return $this->strCssClass;
				case "Display": return $this->blnDisplay;
				case "DisplayStyle": return $this->strDisplayStyle;
				case "FontBold": return $this->blnFontBold;
				case "FontItalic": return $this->blnFontItalic;
				case "FontNames": return $this->strFontNames;
				case "FontOverline": return $this->blnFontOverline;
				case "FontSize": return $this->strFontSize;
				case "FontStrikeout": return $this->blnFontStrikeout;
				case "FontUnderline": return $this->blnFontUnderline;
				case "ForeColor": return $this->strForeColor;
				case "Opacity": return $this->intOpacity;

				// BEHAVIOR
				case "AccessKey": return $this->strAccessKey;
				case "CausesValidation": return $this->mixCausesValidation;
				case "Cursor": return $this->strCursor;
				case "Enabled": return $this->blnEnabled;
				case "Required": return $this->blnRequired;
				case "TabIndex": return $this->intTabIndex;
				case "ToolTip": return $this->strToolTip;
				case "ValidationError": return $this->strValidationError;
				case "Visible": return $this->blnVisible;
				case "PreferedRenderMethod": return $this->strPreferedRenderMethod;

				// LAYOUT
				case "Height": return $this->strHeight;
				case "Width": return $this->strWidth;
				case "HtmlBefore": return $this->strHtmlBefore;
				case "HtmlAfter": return $this->strHtmlAfter;
				case "Instructions": return $this->strInstructions;
				case "Warning": return $this->strWarning;

				case "Overflow": return $this->strOverflow;
				case "Position": return $this->strPosition;
				case "Top": return $this->strTop;
				case "Left": return $this->strLeft;

				case "Moveable": return $this->objDraggable && !$this->objDraggable->Disabled;
				case "Resizable": return $this->objResizable && !$this->objResizable->Disabled;
				case "Droppable": return $this->objDroppable && !$this->objDroppable->Disabled;
				case "DragObj": return $this->objDraggable;
				case "ResizeObj": return $this->objResizable;
				case "DropObj": return $this->objDroppable;

				// MISC
				case "ControlId": return $this->strControlId;
				case "Form": return $this->objForm;
				case "ParentControl": return $this->objParentControl;

				case "Name": return $this->strName;
				case "Rendered": return $this->blnRendered;
				case "Rendering": return $this->blnRendering;
				case "OnPage": return $this->blnOnPage;
				case "RenderMethod": return $this->strRenderMethod;
				case "WrapperModified": return $this->blnWrapperModified;
				case "strActionParameter": //for backward compatibility	
				case "ActionParameter": return $this->mixActionParameter;
				case "ActionsMustTerminate": return $this->blnActionsMustTerminate;
				case "WrapperCssClass": return $this->strWrapperCssClass;
				case "UseWrapper": return $this->blnUseWrapper;

				// SETTINGS
				case "JavaScripts": return $this->strJavaScripts;
				case "StyleSheets": return $this->strStyleSheets;
				case "FormAttributes": return (array) $this->strFormAttributes;

				case "Modified": return $this->IsModified();


				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		/**
		 * PHP __set magic method implementation
		 * @param string $strName Property Name
		 * @param string $mixValue Property Value
		 *
		 * @return mixed|void
		 * @throws QCallerException
		 * @throws Exception|QCallerException
		 * @throws Exception|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				// APPEARANCE
				case "BackColor":
					try {
						$this->strBackColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderColor":
					try {
						$this->strBorderColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderStyle":
					try {
						$this->strBorderStyle = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderWidth":
					try {
						$this->strBorderWidth = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CssClass":
					try {
						$this->strCssClass = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Display":
					try {
						$blnDisplay = QType::Cast($mixValue, QType::Boolean);
						if ($blnDisplay != $this->blnDisplay) {
							$this->blnDisplay = $blnDisplay;
							$this->MarkAsWrapperModified();
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "DisplayStyle":
					try {
						$this->strDisplayStyle = QType::Cast($mixValue, QType::String);
						if (($this->strDisplayStyle == QDisplayStyle::Block) ||
							($this->strDisplayStyle == QDisplayStyle::Inline))
							$this->strDisplayStyle = $this->strDisplayStyle;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontBold":
					try {
						$this->blnFontBold = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontItalic":
					try {
						$this->blnFontItalic = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontNames":
					try {
						$this->strFontNames = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontOverline":
					try {
						$this->blnFontOverline = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontSize":
					try {
						$this->strFontSize = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontStrikeout":
					try {
						$this->blnFontStrikeout = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontUnderline":
					try {
						$this->blnFontUnderline = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ForeColor":
					try {
						$this->strForeColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Opacity":
					try {
						$this->intOpacity = QType::Cast($mixValue, QType::Integer);
						if (($this->intOpacity < 0) || ($this->intOpacity > 100))
							throw new QCallerException('Opacity must be an integer value between 0 and 100');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// BEHAVIOR
				case "AccessKey":
					try {
						$this->strAccessKey = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CausesValidation":
					try {
						$this->mixCausesValidation = $mixValue;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Cursor":
					try {
						$this->strCursor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Enabled":
					try {
						$this->blnEnabled = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Required":
					try {
						$this->blnRequired = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "TabIndex":
					try {
						$this->intTabIndex = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ToolTip":
					try {
						$this->strToolTip = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Visible":
					try {
						$this->blnVisible = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "PreferedRenderMethod":
					try {
						$this->strPreferedRenderMethod = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// LAYOUT
				case "Height":
					try {
						$this->strHeight = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Width":
					try {
						$this->strWidth = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "HtmlBefore":
					try {
						$this->strHtmlBefore = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "HtmlAfter":
					try {
						$this->strHtmlAfter = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Instructions":
					try {
						$this->strInstructions = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Warning":
					try {
						$this->strWarning = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Overflow":
					try {
						$this->strOverflow = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Position":
					try {
						$strPosition = QType::Cast($mixValue, QType::String);
						if ($strPosition != $this->strPosition) {
							$this->strPosition = $strPosition;
							$this->MarkAsWrapperModified();
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Top":
					try {
						$strTop = QType::Cast($mixValue, QType::String);
						if ($strTop != $this->strTop) {
							$this->strTop = $strTop;
							$this->MarkAsWrapperModified();
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Left":
					try {
						$strLeft = QType::Cast($mixValue, QType::String);
						if ($strLeft != $this->strLeft) {
							$this->strLeft = $strLeft;
							$this->MarkAsWrapperModified();
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Moveable":
					try {
						if (QType::Cast($mixValue, QType::Boolean)) {
							if (!$this->objDraggable) {
								$this->objDraggable = new QDraggable($this);
							} else {
								$this->objDraggable->Disabled = false;
							}
						}
						else {
							if ($this->objDraggable) {
								$this->objDraggable->Disabled = true;
							}
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Resizable":
					try {
						if (QType::Cast($mixValue, QType::Boolean)) {
							if (!$this->objResizable) {
								$this->objResizable = new QResizable($this);
							} else {
								$this->objResizable->Disabled = false;
							}
						}
						else {
							if ($this->objResizable) {
								$this->objResizable->Disabled = true;
							}
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Droppable":
					try {
						if (QType::Cast($mixValue, QType::Boolean)) {
							if (!$this->objDroppable) {
								$this->objDroppable = new QDroppable($this);
							} else {
								$this->objDroppable->Disabled = false;
							}
						}
						else {
							if ($this->objDroppable) {
								$this->objDroppable->Disabled = true;
							}
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// MISC
				case "Name":
					try {
						$this->strName = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "strActionParameter": // for backward compatibility
				case "ActionParameter":
					try {
						$this->mixActionParameter = ($mixValue instanceof QJsClosure) ? $mixValue : QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "WrapperCssClass":
					try {
						$strWrapperCssClass = QType::Cast($mixValue, QType::String);
						if ($strWrapperCssClass != $this->strWrapperCssClass) {
							$this->strWrapperCssClass = $strWrapperCssClass;
							$this->MarkAsWrapperModified();
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "UseWrapper":
					try {
						if($this->blnUseWrapper != QType::Cast($mixValue, QType::Boolean)) {
							$this->blnUseWrapper = !$this->blnUseWrapper;
							//need to render the parent again (including its children)
							if ($this->ParentControl) {
								$this->ParentControl->MarkAsModified();
							}
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
?>
