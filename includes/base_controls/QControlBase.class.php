<?php
	/**
	 * QControlBase is the base class of all QControls and shares their common properties.
	 * 
	 * Not every control will utilize every single one of these properties.
	 *
	 * All Controls must implement the following abstract functions:
	 * <ul>
	 * 		<li>{@link QControlBase::GetControlHtml()}</li>
	 * 		<li>{@link QControlBase::ParsePostData()}</li>
	 * 		<li>{@link QControlBase::Validate()}</li>
	 * </ul>
	 *
	 * A QControl conceptually is an object in an html form that manages data or that can be controlled via PHP.
	 * In the early days of the internet, this was simply an html input or select tag that was submitted via a POST.
	 * As the internet has evolved, so has QControl, but its basic idea is the same. Its an object on the screen that
	 * you would like to either control from PHP, or receive information from. The parts of a QControl that are
	 * sent to the browser are:
	 *  - The base tag and its contents, as returned by GetControlHtml(). This would be an Input tag, or a Button, or
	 *    even just a div. Many Javascript widget libraries will take a div and add to it to create a control. The tag
	 *    will include an id in all cases. If you do not assign one, a unique id will be created automatically.
	 *  - An optional Name, often sent to the browser in a Label tag.
	 *  - Optional instructions
	 *  - Optional validation error text
	 *  - Optional Javascript attached to the control as part of its inherint functionality, or to control settable options
	 *    that are handled by a jQuery wrapper function of some kind.
	 *  - Optional Javascript attached to the control through the AddActions mechanism.
	 *
	 * You control how these parts are rendered by implementing Render* methods in your own QControl class. Some basic
	 * ones are included in this class for you to start with.
	 *
	 * Depending on the control, and the implementation, the control might need or want to be rendered with a wrapper tag,
	 * which is controlled by the blnUseWrapper member. For example, if you want to have a form object with a name,
	 * instructions and error text, a wrapper might be needed to make sure all these parts redraw when something changes in
	 * the control. Bootstrap's formObjectGroup is an example of a control that would have all these parts.
	 * Also, if you know that a javascript widget library is going to wrap your html in additional html,
	 * you should include a wrapper here so the additional html is included inside your wrapper, and thus the entire
	 * control will get redrawn on a refresh (jQueryUI's Dialog is an example of this kind of widget.)
	 *
	 * QControls are part of a tree type hierarchy, whose parent can either be a QForm, or another QControl.
	 *
	 * The QControl system is designed to manage the process of redrawing a control automatically when something about
	 * the control changes. You can force a redraw by using the Refresh command from outside of a control, or by setting
	 * the blnModified member variable from a subclass. You can also use the QWatcher mechanism to automatically redraw
	 * when something in the database changes.
	 *
	 * QControls are the base objects for actions to be attached to events. When attaching actions to multiple objects
	 * of the same type, considering attaching the event to a parent object and using event delegation for your action,
	 * as it can be more efficient in certain cases.
	 *
	 * QControls can trigger validation and are part of the validation system. QControls that are not Enabled or not
	 * Visible will not go through the form's Validation routine.
	 *
	 * Controls can be made visible using either the Visible or Display PHP parameters. Both are booleans.
	 * - Setting Visible to false completely removes the control from the DOM, leaving either just its
	 *   wrapper or a an invisible span stub in its place. When the control is made visible again, it is entirely
	 *   redrawn.
	 * - Setting Display to false leaves the control in the DOM, but simply sets its display property to 'none' in CSS.
	 *   Show and hide are much faster.
	 *
	 * @package Controls
	 *
	 * @property-read boolean $ActionsMustTerminate Prevent the default action from happenning upon an event trigger. See documentation for "protected $blnActionsMustTerminate" below.
	 * @property-read boolean $ScriptsOnly Whether the control only generates javascripts and not html.
	 * @property mixed $ActionParameter This property allows you to pass your own parameters to the handlers for actions applied to this control.
	 *			 this can be a string or an object of type QJsClosure. If you pass in a QJsClosure it is possible to return javascript objects/arrays 
	 *			 when using an ajax or server action.
	 * @property mixed $CausesValidation flag says whether or not the form should run through its validation routine if this control has an action defined and is acted upon
	 * @property-read string $ControlId returns the id of this control
	 * @property-read QForm $Form returns the parent form object
	 * @property-read array $FormAttributes
	 * @property string $HtmlAfter HTML that is shown after the control {@link QControl::RenderWithName}
	 * @property string $HtmlBefore HTML that is shown before the control {@link QControl::RenderWithName}
	 * @property string $Instructions instructions that is shown next to the control's name label {@link QControl::RenderWithName}
	 * @property-read string $JavaScripts
	 * @property-read boolean $Modified indicates if the control has been changed. Used to tell Qcubed to rerender the control or not (Ajax calls).
	 * @property boolean $Moveable
	 * @property boolean $Resizable
	 * @property string $Name sets the Name of the Control (see {@link QControl::RenderWithName})
	 * @property-read boolean $OnPage is true if the control is connected to the form
	 * @property-read QForm|QControl $ParentControl returns the parent control
	 * @property-read boolean $Rendered
	 * @property-read boolean $Rendering
	 * @property-read string $RenderMethod carries the name of the function, which were initially used for rendering
	 * @property string $PreferredRenderMethod carries the name of the function, which were initially used for rendering
	 * @property boolean $Required specifies whether or not this is required (will cause a validation error if the form is trying to be validated and this control is left blank)
	 * @property-read string $StyleSheets
	 * @property string $ValidationError is the string that contains the validation error (if applicable) or will be blank if (1) the form did not undergo its validation routine or (2) this control had no error
	 * @property boolean $Visible specifies whether or not the control should be rendered in the page.  This is in contrast to Display, which will just hide the control via CSS styling.
	 * @property string $Warning is warning text that will be shown next to the control's name label {@link QControl::RenderWithName}
	 * @property boolean $UseWrapper defaults to true
	 * @property QQNode $LinkedNode A database node that this control is directly editing
	 * @property-read boolean $WrapperModified
	 * @property string $WrapperCssClass
	 * @property boolean $WrapLabel For checkboxes, radio buttons, and similar controls, whether to wrap the label around
	 * 		the control, or place the label next to the control. Two legal styles of label creation that different css and JS frameworks expect.
	 * @property-write boolean $SaveState set to true to have the control remember its state between visits to the form that the control is on.
	 * @property boolean $Minimize True to force the entire control and child controls to draw minimized. This is helpful when drawing inline-block items to prevent spaces from appearing between them.
	 * @property boolean $AutoRender true to have the control be automatically rendered without an explicit "Render..." call. This is used by QDialogs,
	 * 		and other similar controls that are controlled via javascript, and generally start out hidden on the page. These controls
	 * 		are appended to the form after all other controls.
	 */
	abstract class QControlBase extends QHtmlAttributeManager {

		/*
		 * Constannts
		 */
		const CommentStart = 'Begin';
		const CommentEnd = 'End';

		/*
		 * Static Members
		 */

		protected $objWrapperStyler = null;

		/**
		 * Protected members
		 */

		/** @var mixed Controls how this control will effect the validation system */
		protected $mixCausesValidation = false;
		/** @var bool Is it mandatory for the control to receive data on a POST back for the control to be called valid? */
		protected $blnRequired = false;
		/** @var int Tab-index */
		protected $strValidationError = null;
		/** @var bool Should the control be visible or not (it normally effects whether Render method will be called or not) */
		protected $blnVisible = true;
		/** @var bool should the control be displayed? */
		protected $blnDisplay = true;
		/** @var string Preferred method to be used for rendering e.g. Render, RenderWithName, RenderWithError */
		protected $strPreferredRenderMethod = 'Render';

		/** @var string HTML to rendered before the actual control */
		protected $strHtmlBefore = null;
		/** @var string HTML to rendered after the actual control */
		protected $strHtmlAfter = null;
		/** @var string the Instructions for the control (useful for controls used in data entry) */
		protected $strInstructions = null;
		/** @var string Same as validation error message but is supposed to contain custom messages */
		protected $strWarning = null;

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
		/** @var QForm Redundant copy of the global $_FORM variable. */
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
		/** @var bool Is the control available on page? Useful when 're-rendering' a control that has children */
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
		//protected $strWrapperCssClass = null; -- See objWrapperStyler now
		/** @var bool Should the wrapper be used when rendering?  */
		protected $blnUseWrapper = true;
        /** @var string  One time scripts associated with the control. */
        protected $strAttributeScripts = null;
		/** @var string The INITIAL class for the object. Only subclasses should set this before calling the parent constructor. */
		protected $strCssClass = null;
		/** @var  bool Force this control, and all subcontrols to draw minimized. This is important when using inline-block styles, as not doing so will cause spaces between the objects. */
		protected $blnMinimize = false;

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
		/** @var bool True if this control only generates javascripts and not html. */
		protected $blnScriptsOnly = false;
		/** @var bool Is this control a block type element? This determines whether the control will be wrapped in
		 *  a div or a span if blnUseWrapper is true. For example, if */
		protected $blnIsBlockElement = false;
		/** @var QWatcher Stores information about watched tables. */
		protected $objWatcher = null;
		/** @var QQNode  Used by designer to associate a db node with this control */
		protected $objLinkedNode;
		/**
		 * @var bool | null For controls that also produce built-in labels (QCheckBox, QCheckBoxList, etc.)
		 * True to wrap the checkbox with the label (the Bootstrap way). False to put the label next to the
		 * checkbox (the jQueryUI way).
		 */
		protected $blnWrapLabel = false;

		/** @var bool true to remember the state of this control to restore if the user comes back to it. */
		protected $blnSaveState = false;

		/** @var bool true to have the control be automatically rendered without an explicit "Render..." call. This is used by QDialogs,
		 * and other similar controls that are controlled via javascript, and generally start out hidden on the page. These controls
		 * are appended to the form after all other controls.
		 */
		protected $blnAutoRender = false;

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
		 *   contain alphanumeric characters.  If this parameter is not passed, QCubed will generate the id.
		 *
		 * @throws Exception|QCallerException
		 */
		public function __construct($objParentObject, $strControlId = null) {
			if ($objParentObject instanceof QForm)
				$this->objForm = $objParentObject;
			else if ($objParentObject instanceof QControl) {
				$this->objParentControl = $objParentObject;
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
					throw new QCallerException('ControlIds must be only alphanumeric characters: ' . $strControlId);
			}

			/* If the subclass sets this, we pass it off to the attribute manager. Mostly for backwards compatibility,
			 * but is a conventient way to set the initial class.
			 */
			if ($this->strCssClass) {
				$this->AddCssClass($this->strCssClass);
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
		 * Object persistance support.
		 */

		/**
		 * Save the state of the control to restore it later, so that if the user comes back to this page, the control
		 * will be in the showing the same thing. Subclasses should put minimally important information into the state that
		 * is needed to restore the state later.
		 *
		 * This implementation puts the state into the session. Override to provide a different method if so desired.
		 *
		 * Should normally be called only by FormBase code. See GetState and PutState for the control side implementation.
		 */
		public function _WriteState() {
			global $_FORM;

			assert ($_FORM !== null);
			if (defined ('__SESSION_SAVED_STATE__') && $this->blnSaveState) {
				$formName = get_class($_FORM);	// must use global $_FORM here instead of $this->objForm, since serialization will have nulled the objForm.
				$_SESSION[__SESSION_SAVED_STATE__][$formName][$this->ControlId] = $this->GetState();
			}
		}

		/**
		 * Restore the  state of the control.
		 */
		public function _ReadState() {
			if (defined ('__SESSION_SAVED_STATE__') && $this->blnSaveState) {
				$formName = get_class($this->objForm);
				if (isset ($_SESSION[__SESSION_SAVED_STATE__][$formName][$this->ControlId])) {
					$state = $_SESSION[__SESSION_SAVED_STATE__][$formName][$this->ControlId];
					$this->PutState($state);
				}
			}
		}

		/**
		 * Control subclasses should return their state data that they will use to restore later.
		 * @return mixed
		 */
		protected function GetState() {
			return null;
		}

		/**
		 * Restore the state of the control. The control will have already been
		 * created and initialized. Subclasses should verify that the restored state is still valid for the data
		 * available.
		 * @param mixed $state
		 */
		protected function PutState($state) {}

		/**
		 * Completely forget the saved state for this control.
		 */
		public function ForgetState() {
			if (defined ('__SESSION_SAVED_STATE__')) {
				$formName = get_class($this->objForm);
				unset($_SESSION[__SESSION_SAVED_STATE__][$formName][$this->ControlId]);
			}
		}


		/**
		 * A utility function to convert a template file name into a full path.
		 *
		 * @param $strTemplate
		 */
		public function GetTemplatePath($strTemplate) {
			// If no path is specified, or a relative path, use the path of the child control's file as the starting point.
			if (strpos($strTemplate, DIRECTORY_SEPARATOR) !== 0) {
				$strOriginalPath = $strTemplate;

				// Try the control's subclass location
				$reflector = new ReflectionClass(get_class($this));
				$strDir = dirname($reflector->getFileName());
				$strTemplate = $strDir . DIRECTORY_SEPARATOR . $strTemplate;

				if (!file_exists($strTemplate)) {
					// Try the control's parent
					if ($this->objParentControl) {
						$reflector = new ReflectionClass(get_class($this->objParentControl));
						$strDir = dirname($reflector->getFileName());
						$strTemplate = $strDir . DIRECTORY_SEPARATOR . $strTemplate;
					}
				}

				if (!file_exists($strTemplate)) {
					// Try the form's location
					$reflector = new ReflectionClass(get_class($this->objForm));
					$strDir = dirname($reflector->getFileName());
					$strTemplate = $strDir . DIRECTORY_SEPARATOR . $strTemplate;

					if (!file_exists($strTemplate)) {
						$strTemplate = $strOriginalPath;    // not found, but return original name
					}
				}
			}
			return $strTemplate;
		}


		/**
		 * This function evaluates a template and is used by a variety of controls. It is similar to the function found in the
		 * QForm, but recreated here so that the "$this" in the template will be the control, instead of the form,
		 * and the protected members of the control are available to draw directly.
		 * @param string $strTemplate Path to the HTML template file
		 *
		 * @return string The evaluated HTML string
		 */
		public function EvaluateTemplate($strTemplate) {
			global $_ITEM;		// used by data repeater
			global $_CONTROL;
			global $_FORM;

			if ($strTemplate) {
				QApplication::$ProcessOutput = false;
				// Store the Output Buffer locally
				$strAlreadyRendered = ob_get_contents();
				if ($strAlreadyRendered) {
					ob_clean();
				}

				// Evaluate the new template
				ob_start('__QForm_EvaluateTemplate_ObHandler');

				$strTemplate = $this->GetTemplatePath($strTemplate);
				require($strTemplate);
				$strTemplateEvaluated = ob_get_contents();
				ob_end_clean();

				// Restore the output buffer and return evaluated template
				if ($strAlreadyRendered) {
					print($strAlreadyRendered);
				}
				QApplication::$ProcessOutput = true;

				return $strTemplateEvaluated;
			}

			return null;
		}

		/**
		 * This function passes control of action parameter processing to the control that caused the action, so that
		 * the control can further process the action parameters. It also saves additional information in the returned
		 * parameter array. This is useful for widgets that need to pass more information to the action than just a
		 * simple string, and allows actions to get more information as well. This also allows widgets to modify
		 * the action parameter, while preserving the original action parameter so that the action can see both.
		 *
		 * @param QControl $objSourceControl
		 * @param QAction $objAction
		 * @param $mixParameter
		 * @return mixed
		 */
		public static function _ProcessActionParams(QControl $objSourceControl, QAction $objAction, $mixParameter) {
			$mixParameters['param'] = null;
			$mixParameters = $objSourceControl->ProcessActionParameters($objAction, $mixParameter);
			return $mixParameters;
		}

		/**
		 * Breaks down the action parameter if needed to more useful information. Subclasses should override, call
		 * the parent, and then modify the "param" item in the returned array if needed. This also provides additional
		 * information to the action about the triggering control.
		 *
		 * @param $mixParameter
		 * @return mixed
		 */
		protected function ProcessActionParameters(QAction $objAction, $mixParameter) {
			$params['param'] = $mixParameter;	// this value can be modified by subclass if needed
			$params['originalParam'] = $mixParameter;
			$params['action'] = $objAction;
			$params['controlId'] = $this->strControlId;
			return $params;
		}

		/**
		 * Used by the QForm engine to call the method in the control, allowing the method to be a protected method.
		 *
		 * @param QControl $objDestControl
		 * @param string $strMethodName
		 * @param $strSourceControlId
		 * @param mixed $mixParameter	Parameters coming from javascript
		 */
		public static function _CallActionMethod(QControl $objDestControl, $strMethodName, $strFormId, $params) {
			$objDestControl->$strMethodName($strFormId, $params['controlId'], $params['param'], $params);
		}

		/**
		 * Prepare the control for serialization. All pointers to forms and form objects should be
		 * converted to something that can be restored using Wakeup().
		 *
		 * The main problem we are resolving is that the PHP serialization process will convert an internal reference
		 * to the object being serialized into a copy of the object. After deserialization, you would have the form,
		 * and then somewhere inside the form, a separate copy of the form. This is a long-standing bug in PHP.
		 */
		public function Sleep() {
			$this->objForm = null;
		}

		/**
		 * The object has just been unserialized, so fix up pointers to embedded forms.
		 * @param QForm $objForm
		 */
		public function Wakeup(QForm $objForm) {
			$this->objForm = $objForm;
		}

		/**
		 * A helper function to fix up a 'callable', a formObj, or any other object that we would like to represent
		 * in the serialized stream differently than the default. If a QControl, make sure this isn't the only
		 * instance of the control in the stream, or have some other way to serialize the control.
		 *
		 * @param QForm|QControl|array|callable $obj
		 * @return mixed
		 */
		public static function SleepHelper($obj) {
			if ($obj instanceof QForm) {
				// assume its THE form
				return '**QF;';
			}
			elseif ($obj instanceof QControl) {
				return '**QC;' . $obj->strControlId;
			}
			elseif (is_array ($obj)) {
				$ret = array();
				foreach ($obj as $key=>$val) {
					$ret[$key] = self::SleepHelper($val);
				}
				return $ret;
			}
			return $obj;
		}

		/**
		 * A helper function to restore something possibly serialized with SleepHelper.
		 *
		 * @param QForm|QFormBase $objForm
		 * @param array|string    $obj
		 *
		 * @return mixed
		 */
		public static function WakeupHelper($objForm, $obj) {
			if (is_array ($obj)) {
				$ret = array();
				foreach ($obj as $key=>$val) {
					$ret[$key] = self::WakeupHelper($objForm, $val);
				}
				return $ret;
			} elseif (is_string ($obj)) {
				if (substr($obj, 0, 5) == '**QF;') {
					return $objForm;
				}
				elseif (substr($obj, 0, 5) == '**QC;') {
					return $objForm->GetControl(substr($obj, 5));
				}
			}
			return $obj;
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
			if (isset($this->objChildControlArray[$strControlId])) {
				return $this->objChildControlArray[$strControlId];
			}
			else {
				return null;
			}
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
			if (isset($this->objChildControlArray[$strControlId])) {
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
		public function RemoveAllActions($strEventName = null) {
			// Modified
			$this->blnModified = true;

			if ($strEventName) {
				$this->objActionArray[$strEventName] = array();
			}
			else {
				$this->objActionArray = array();
			}
		}

		/**
		 * Returns all actions that are connected with specific events
		 *
		 * @param string $strEventType the type of the event. Be sure and use a
		 *                              QFooEvent::EventName here. (QClickEvent::EventName, for example)
		 * @param string $strActionType if given only actions of this type will be
		 *                              returned
		 *
		 * @return QAction[]
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
		 * @deprecated Use SetHtmlAttribute instead
		 */
		public function SetCustomAttribute($strName, $strValue) {
			$this->SetHtmlAttribute($strName, $strValue);
		}

		/**
		 * Returns the value of a custom attribute
		 *
		 * @param string $strName
		 *
		 * @throws QCallerException
		 * @return string
		 * @deprected Use GetHtmlAttribute instead
		 */
		public function GetCustomAttribute($strName) {
			return $this->GetHtmlAttribute($strName);
		}

		/**
		 * Removes the given custom attribute
		 *
		 * @param string $strName
		 *
		 * @throws QCallerException
		 * @deprecated Use RemoveHtmlAttribute instead
		 */
		public function RemoveCustomAttribute($strName) {
			$this->RemoveHtmlAttribute($strName);
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
		 * @deprecated Use SetCssStyle instead
		 */
		public function SetCustomStyle($strName, $strValue) {
			$this->SetCssStyle($strName, $strValue);
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
			return $this->GetCssStyle($strName);
		}

		/**
		 * Deletes the given custom style
		 *
		 * @param string $strName
		 *
		 * @throws QCallerException
		 * @deprecated use RemoveCssStyle instead
		 */
		public function RemoveCustomStyle($strName) {
			$this->RemoveCssStyle($strName);
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
		 * Returns all attributes in the correct HTML format
		 *
		 * This is utilized by Render methods to display various name-value HTML attributes for the
		 * control.
		 *
		 * ControlBase's implementation contains the very-basic set of HTML attributes... it is expected
		 * that most subclasses will extend this method's functionality to add Control-specific HTML
		 * attributes (e.g. textbox will likely add the maxlength html attribute, etc.)
		 *
		 * @return string
		 * @deprecated Use renderHtmlAttributes instead
		 */
		public function GetAttributes() {
			return $this->RenderHtmlAttributes() . ' ';
		}

		/**
		 * Returns the custom attributes HTML formatted
		 *
		 * All attributes will be returned as concatened the string of the form
		 * key1="value1" key2="value2"
		 * Note: if the the value is === false, then the key will be randered as is, without any value
		 *
		 * @return string
		 * @deprecated Unused
		 */
		public function GetCustomAttributes() {
			return $this->RenderHtmlAttributes();
		}

		/**
		 * Returns the html for the attributes for the base control of the QControl.
		 * Allows the given arrays to override the attributes and styles before
		 * rendering. This inserts the control id into the rendering of the tag.
		 * @param null|string 	$attributeOverrides
		 * @param null|string 	$styleOverrides
		 * @return string
		 */
		public function RenderHtmlAttributes($attributeOverrides = null, $styleOverrides = null) {
			$attributes['id'] = $this->strControlId;
			if ($attributeOverrides) {
				$attributes = array_merge($attributes, $attributeOverrides);
			}
			return parent::RenderHtmlAttributes($attributes, $styleOverrides);
		}

		/**
		 * Returns all action attributes for this QControl
		 *
		 * @return string
		 */
		public function RenderActionScripts() {
			$strToReturn = '';
			foreach ($this->objActionArray as $strEventName => $objActions) {
				$strToReturn .= $this->GetJavaScriptForEvent($strEventName);
			}
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
		 * @deprected Use
		 */
		public function GetStyleAttributes() {
			return $this->RenderCssStyles();
		}

		/**
		 * Returns the styler for the wrapper tag.
		 * @return null|QTagStyler
		 */
		public function GetWrapperStyler() {
			if (!$this->objWrapperStyler) {
				$this->objWrapperStyler = new QTagStyler();
			}
			return $this->objWrapperStyler;
		}

		/**
		 * Adds the given class to the wrapper tag.
		 * @param $strClass
		 */
		public function AddWrapperCssClass($strClass) {
			if ($this->GetWrapperStyler()->AddCssClass($strClass)) {
				$this->MarkAsWrapperModified();
			}
			/**
			 * TODO: This can likely be done just in javascript without a complete refresh of the control.
			 *
			 * if ($this->blnRendered && $this->blnOnScreen) {
			 *   Change using javascript
			 * }
			 */
		}

		/**
		 * Removes the given class from the wrapper tag.
		 * @param $strClass
		 */
		public function RemoveWrapperCssClass($strClass) {
			if ($this->GetWrapperStyler()->RemoveCssClass($strClass)) {
				$this->MarkAsWrapperModified();
			}

			// TODO: do this in javascript
			// QApplication::ExecuteControlCommand($this->WrapperId, 'removeClass', $this->strValidationState);

		}

		/**
		 * Returns all wrapper-style-attributes
		 * Similar to GetStyleAttributes, but specifically for CSS name/value pairs that will render
		 * within a "wrapper's" HTML "style" attribute
		 *
		 * @param bool $blnIsBlockElement
		 * @deprecated
		 *
		 * @return string
		 */
		protected function GetWrapperStyleAttributes($blnIsBlockElement = false) {
			return $this->GetWrapperStyler()->RenderCssStyles();
		}


		/**
		 * Overrides the default CSS renderer in order to deal with a special situation:
		 * Since there is the possibility of a wrapper, we have to delegate certain CSS properties to the wrapper so
		 * that the whole control gets those properties. Those are mostly positioning properties. In this override,
		 * we detect when we do NOT have a wrapper, and therefore have to copy the positioning properties from the
		 * wrapper styler down to the control itself.
		 *
		 * @param null $styleOverrides
		 * @return string
		 */
		public function RenderCssStyles($styleOverrides = null) {
			$styles = $this->styles;
			if ($styleOverrides) {
				$styles = array_merge($this->styles, $styleOverrides);
			}

			if (!$this->blnUseWrapper) {
				// add wrapper styles if no wrapper. control must stand on its own.
				// This next line sucks just the given attributes out of the wrapper styler
				$aWStyles = array_intersect_key ($this->getWrapperStyler()->styles, ['position'=>1, 'top'=>1, 'left'=>1]);
				$styles = array_merge($styles, $aWStyles);
				if (!$this->blnDisplay) {
					$styles['display'] ='none';
				}
			}
			return QHtml::RenderStyles($styles);
		}

		/**
		 * Returns an array of the wrapper attributes to be used for drawing, including CSS styles. Makes sure the control is hidden if display is off.
		 * @param array $attributeOverrides
		 * @return string
		 */
		protected function GetWrapperAttributes($attributeOverrides = null) {
			$styleOverrides = null;
			if (!$this->blnDisplay) {
				$styleOverrides = ['display'=>'none'];
			}
			$attributes = $this->GetWrapperStyler()->GetHtmlAttributes($attributeOverrides, $styleOverrides);

			return $attributes;
		}

		/**
		 * Renders the given output with the current wrapper.
		 *
		 * @param $strOutput
		 * @param $blnForceAsBlockElement
		 *
		 * @return string
		 */
		protected function RenderWrappedOutput($strOutput, $blnForceAsBlockElement = false) {
			$strTag = ($this->blnIsBlockElement || $blnForceAsBlockElement) ? 'div' : 'span';
			$overrides = ['id'=>$this->strControlId . '_ctl'];
			$attributes = $this->GetWrapperAttributes($overrides);

			return QHtml::RenderTag($strTag, $attributes, $strOutput);
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

			// Remove non-overrides from the parameter array
			while (!empty($mixParameterArray) && gettype(reset($mixParameterArray)) != QType::String && gettype(reset($mixParameterArray)) != QType::ArrayType) {
				array_shift($mixParameterArray);
			}

			// Apply any overrides (if applicable)
			if (!empty($mixParameterArray)) {
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
		 * The current use of this function is unknown at the moment.
		 */
		protected function GetNonWrappedHtml() {}

		/**
		 * Sets focus to this control
		 * TODO: Turn this into a specific command to avoid the javascript eval that happens on the other end.
		 */
		public function Focus() {
			QApplication::ExecuteControlCommand($this->strControlId, 'focus');
		}

		/**
		 * Same as "Focus": Sets focus to this control
		 */
		public function SetFocus() {
			$this->Focus();
		}

		/**
		 * Let this control blink
		 *
		 * @param string $strFromColor start color
		 * @param string $strToColor blink color
		 * TODO: Turn this into a specific command to avoid the javascript eval that happens on the other end.
		 */
		public function Blink($strFromColor = '#ffff66', $strToColor = '#ffffff') {
			QApplication::ExecuteJavaScript(sprintf('qc.getW("%s").blink("%s", "%s");', $this->strControlId, $strFromColor, $strToColor));
		}

		/**
		 * Returns and fires the JavaScript that is associated with this control. The html for the control will have already
		 * been rendered, so refer to the html object with "\$j(#{$this->ControlId})". You should do the following:
		 *  - Return any script that attaches a JavaScript widget to the the html control.
		 *  - Use functions like ExecuteControlCommand to fire commands to execute AFTER all controls have been attached.
		 *
		 * @return string
		 */
		public function GetEndScript() {

			$strToReturn = '';

			if ($this->objResizable)
				$strToReturn .= $this->objResizable->GetEndScript();

			if ($this->objDraggable)
				$strToReturn .= $this->objDraggable->GetEndScript();

			if ($this->objDroppable)
				$strToReturn .= $this->objDroppable->GetEndScript();

			$strToReturn .= $this->RenderActionScripts();

			$this->strAttributeScripts = null; // erase the attribute scripts, because the entire control is being drawn, so we don't need them anymore.

			return $strToReturn;
		}

        /**
         * Return one-time scripts associated with the control. Called by the form during an ajax draw only if the
		 * entire control was not rendered.
		 *
		 * Instead of actually rendering, we add them to the application event queue.
         */
        public function RenderAttributeScripts()
        {
            if ($this->strAttributeScripts) {
				foreach ($this->strAttributeScripts as $scriptArgs) {
					array_unshift($scriptArgs, $this->getJqControlId());
					call_user_func_array('QApplication::ExecuteControlCommand', $scriptArgs);
				}
            }
			$this->strAttributeScripts = null;
        }

        /**
         * Executes a java script associated with the control. These scripts are specifically for the purpose of
         * changing some attribute of the control that would also be taken care of during a refresh of the entire
         * control. The script will only be executed in ajax if the entire control is not redrawn.
		 *
		 * Note that these will execute after most of the other commands execute, so do not count on the order
		 * in which they will execute relative to other commands.
         *
		 * @param string $strMethod	The name of the javascript function to call on this control.
		 * @param string $args One or more arguments to send to the method that will cause the control to change
		 */
		public function AddAttributeScript ($strMethod, $args /*, ... */)
        {
			$args = func_get_args();
            $this->strAttributeScripts[] = $args;
        }

		/**
		 * For any HTML code that needs to be rendered at the END of the QForm when this control is
		 * INITIALLY rendered.
		 *
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
			if ((!$this->blnRendered) &&
					(!$this->blnRendering))
				$this->MarkAsModified();
		}

		/**
		 * RenderOutput should be the last call in your custom RenderMethod. It is responsible for the following:
		 * - Creating the wrapper if you are using a wrapper, or
		 * - Possibly creating a dummy control if not using a wrapper and the control is hidden.
		 * - Generating the control's output in one of 3 ways:
		 * 		- Generate straight html if drawing the control as part of a complete page refresh
		 * 		- Generate straight html if in an ajax call, but a parent is getting redrawn, which requires this
		 *        whole control to get drawn
		 * 		- If in an ajax call and we are the top level control getting drawn, then generate special code that
		 * 		  out javascript will read and put into the control's spot on the page. Requires coordination with
		 * 		  the code in qcubed.js.
		 *
		 * @param string  $strOutput
		 *   Your html-code which should be printed out
		 * @param boolean $blnDisplayOutput
		 *   should it be printed, or just be returned?
		 *
		 * @return string
		 */
		protected function RenderOutput($strOutput, $blnDisplayOutput, $blnForceAsBlockElement = false) {
			if ($blnForceAsBlockElement) {
				$this->blnIsBlockElement = true;	// must be remembered for ajax drawing
			}

			if ($this->blnUseWrapper) {
				if (!$this->blnVisible) $strOutput = '';
			} else if (!$this->blnVisible) {
				/* No wrapper is used and the control is not visible. We must enter a span with the control id and
				 *	display:none in order to be able change blnVisible to true in an Ajax call later and redraw the control.
				 */
				$strOutput = sprintf('<span id="%s" style="display:none;"></span>', $this->strControlId);
			}

			switch ($this->objForm->CallType) {
				case QCallType::Ajax:
					if ($this->objParentControl) {
						if ($this->objParentControl->Rendered || $this->objParentControl->Rendering) {
							// If we have a ParentControl and the ParentControl has NOT been rendered, then output
							// as standard HTML
							if ($this->blnUseWrapper) {
								$strOutput = $this->RenderWrappedOutput($strOutput, $blnForceAsBlockElement) . $this->GetNonWrappedHtml();
							} else {
								$strOutput = $strOutput . $this->GetNonWrappedHtml();
							}
						} else {
							// Do nothing. RenderAjax will handle it.
						}
					} else {
						// if this is an injected top-level control, then we need to render the whole thing
						if (!$this->blnOnPage) {
							if ($this->blnUseWrapper) {
								$strOutput = $this->RenderWrappedOutput($strOutput, $blnForceAsBlockElement) . $this->GetNonWrappedHtml();
							} else {
								$strOutput = $strOutput . $this->GetNonWrappedHtml();
							}
						}
					}
					break;

				default:
					if ($this->blnUseWrapper) {
						$strOutput = $this->RenderWrappedOutput($strOutput) . $this->GetNonWrappedHtml();
					} else {
						$strOutput = $strOutput . $this->GetNonWrappedHtml();
					}

					$strOutput = $this->RenderComment(self::CommentStart) . _indent($strOutput) . $this->RenderComment(self::CommentEnd);
					break;
			}

			// Update watcher
			if ($this->objWatcher) {
				$this->objWatcher->MakeCurrent();
			}

			$this->blnRendering = false;
			$this->blnRendered = true;
			$this->blnOnPage = true;

			// Output or Return
			if ($blnDisplayOutput) {
				print($strOutput);
			} else {
				return $strOutput;
			}
		}

		/**
		 * This method will render the control, itself, and will return the rendered HTML as a string
		 *
		 * As an abstract method, any class extending QControlBase must implement it.  This ensures that
		 * each control has its own specific html.
		 *
		 * When outputting html, you should call GetHtmlAttributes to get the attributes for the main control.
		 *
		 * If you are outputting a complex control, and need to include ids in subcontrols, your ids should be of the form:
		 * 	$parentControl->ControlId . '_' . $strSubcontrolId.
		 * The underscore indicates that actions and post data should be routed first to the parent control, and the parent
		 * control will handle the rest.
		 *
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
		public function Render($blnDisplayOutput = true /* ... */) {
			$blnMinimized = QApplication::$Minimize;
			if ($this->blnMinimize) {
				QApplication::$Minimize = true;
			}
			$this->RenderHelper(func_get_args(), __FUNCTION__);

			try {
				if ($this->blnVisible) {
					$strOutput = sprintf('%s%s%s',
						$this->strHtmlBefore,
						$this->GetControlHtml(),
						$this->strHtmlAfter
					);
				} else {
					// Avoid going through the time to render the control if we are not going to display it.
					$strOutput = "";
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Call RenderOutput, returning its contents
			$strOut = $this->RenderOutput($strOutput, $blnDisplayOutput);

			QApplication::$Minimize = $blnMinimized;

			return $strOut;
		}

		/**
		 * RenderAjax will be called during an Ajax rendering of the controls. Every control gets called. Each control
		 * is responsible for rendering itself. Some objects automatically render their child objects, and some don't,
		 * so we detect whether the parent is being rendered, and assume the parent is taking care of rendering for
		 * us if so.
		 *
		 * Override if you want more control over ajax drawing, like it you detect parts of your control that have changed
		 * and then want to draw only those parts. This will get called on every control on every ajax draw request.
		 * It is up to you to test the blnRendered flag of the control to know whether the control was already rendered
		 * by a parent control before drawing here.
		 *
		 * @return array[] array of control arrays to be interpreted by the response function in qcubed.js
		 */
		public function RenderAjax() {
			// Only render if this control has been modified at all
			$controls = [];
			if ($this->IsModified()) {
				// Render if (1) object has no parent or (2) parent was not rendered nor currently being rendered
				if ((!$this->objParentControl) || ((!$this->objParentControl->Rendered) && (!$this->objParentControl->Rendering))) {
					$strRenderMethod = $this->strRenderMethod;
					if (!$strRenderMethod && $this->AutoRender) {
						// This is an auto-injected control (a dialog for instance) that is not on the page, so go ahead and render it
						$strRenderMethod = $this->strPreferredRenderMethod;
					}
					if ($strRenderMethod) {
						$strOutput = $this->$strRenderMethod(false);
						$controls[] = [QAjaxResponse::Id=>$this->strControlId, QAjaxResponse::Html=>$strOutput];
					}
				}
			}

			if ($this->blnWrapperModified && ($this->blnVisible) && ($this->blnUseWrapper)) {
					// Top level ajax response will usually just draw the innerText of the wrapper
					// If something changed in the wrapper attributes, we need to tell the jQuery response to handle that too.
					// In particular, if the wrapper was hidden, and is now displayed, we need to make sure that the control
					// becomes visible before other scripts execute, or those other scripts will not see the control.
				$wrapperAttributes = $this->GetWrapperAttributes();
				if (!isset($wrapperAttributes['style'])) {
					$wrapperAttributes['style'] = '';	// must specifically turn off styles if none were drawn, in case the previous state had a style and it had changed
				}
				$controls[] = [QAjaxResponse::Id=>$this->strControlId . '_ctl', QAjaxResponse::Attributes=>$wrapperAttributes];
			}
			return $controls;
		}

		/**
		 * Returns true if the control should be redrawn.
		 * @return boolean
		 */
		public function IsModified() {
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
					$renderMethod = $objControl->strPreferredRenderMethod;
					if ($renderMethod) {
						$strToReturn .= $objControl->$renderMethod($blnDisplayOutput);
					}
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

			/**
			 * If we are not using a wrapper, then we are going to tag related elements so that qcubed.js
			 * can remove them when we redraw. Otherwise, they will be repeatedly added instead of replaced.
			 */
			$strDataRel = '';
			if (!$this->blnUseWrapper) {
				$strDataRel = sprintf('data-qrel="#%s" ', $this->strControlId);
			}
			
			try {
				$strOutput = $this->GetControlHtml();

				if ($this->strValidationError)
					$strOutput .= sprintf('<br %s/><span %sclass="error">%s</span>', $strDataRel, $strDataRel, QHtml::RenderString($this->strValidationError));
				else if ($this->strWarning)
					$strOutput .= sprintf('<br %s/><span %sclass="warning">%s</span>', $strDataRel, $strDataRel, QHtml::RenderString($this->strWarning));
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Call RenderOutput, Returning its Contents
			return $this->RenderOutput($strOutput, $blnDisplayOutput, false);
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

			$aWrapperAttributes = array();
			if (!$this->blnUseWrapper) {
				//there is no wrapper --> add the special attribute data-qrel to the name control
				$aWrapperAttributes['data-qrel'] = $this->strControlId;
				if (!$this->blnDisplay) {
					$aWrapperAttributes['style'] = 'display: none';
				}
			}

			// Custom Render Functionality Here

			// Because this example RenderWithName will render a block-based element (e.g. a DIV), let's ensure
			// that IsBlockElement is set to true
			$this->blnIsBlockElement = true;

			// Render the Left side
			$strLabelClass = "form-name";
			if ($this->blnRequired){
				$strLabelClass .= ' required';
			}
			if (!$this->Enabled){
				$strLabelClass .= ' disabled';
			}

			if ($this->strInstructions){
				$strInstructions = '<br/>' .
				QHtml::RenderTag('span', ['class'=>"instructions"], QHtml::RenderString($this->strInstructions));
			}else{
				$strInstructions = '';
			}
			$strLabel = QHtml::RenderTag('label', null, QHtml::RenderString($this->strName));
			$strToReturn = QHtml::RenderTag('div', ['class'=>$strLabelClass], $strLabel . $strInstructions);

			// Render the Right side
			$strMessage = '';
			if ($this->strValidationError){
				$strMessage = sprintf('<span class="error">%s</span>', QHtml::RenderString($this->strValidationError));
			}else if ($this->strWarning){
				$strMessage = sprintf('<span class="warning">%s</span>', QHtml::RenderString($this->strWarning));
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

			// render control dressing, which is essentially a wrapper. Not sure why we are not just rendering a wrapper here!
			$strToReturn = QHtml::RenderTag('div', $aWrapperAttributes, $strToReturn);

			////////////////////////////////////////////
			// Call RenderOutput, Returning its Contents
			return $this->RenderOutput($strToReturn, $blnDisplayOutput, false);
			////////////////////////////////////////////
		}

		/**
		 * Format a comment block if we are not in MINIMIZE mode.
		 *
		 * @param string $strType	Either QControl::CommentStart or QControl::CommentEnd
		 * @return string
		 */
		public function RenderComment($strType) {
			return  QHtml::Comment( $strType . ' ' . get_class($this) . ' ' . $this->strName . ' id:' . $this->strControlId);
		}


		/**
		 * Helper method to render the control using some other class/method.
		 *
		 * Useful for plugins that want to override the render behavior for the controls
		 * without modifying the control code.
		 * @param $classname
		 * @param $methodname
		 * @param array $args
		 * @return mixed
		 */
		public function RenderExtensionRenderer($classname, $methodname, $args=array()){
			$RenderExtensionInstance = new $classname;
			return $RenderExtensionInstance->{$methodname}($args);
		}

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
			/*
			 TODO: Implement and test the code below to reduce the amount of redrawing. In particular, the current
			    implementation will cause invisible and display:none controls to be redrawn whenever something changes,
				even though its not needed.

			if ($this->blnVisible &&
			$this->blnDisplay) {
				$this->blnModified = true;
			} */
		}

		/**
		 * Marks the wrapper of this control as modified
		 */
		public function MarkAsWrapperModified() {
			$this->blnWrapperModified = true;
			$this->blnModified = true;
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
			if ($this->objParentControl) {
				$this->objParentControl->RemoveChildControl($this->ControlId, false);
			}

			// Mark the new parent (if applicable) as modified
			if ($objControl) {
				$objControl->AddChildControl($this);
			}
		}

		/**
		 * Resets the validation state to default
		 */
		public function ValidationReset() {
			if (($this->strValidationError) || ($this->strWarning)) {
				$this->blnModified = true;
			}
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

			// In order to make the control exportable, we can't have circular references or things that are not exportable.
			// We use the sleep helper as an aid to deep exporting the object.

			$vars = get_object_vars($this);
			foreach ($vars as $key=>$val) {
				$this->$key = self::SleepHelper($val);
			}

			if ($blnReturn) {
				return var_export($this, true);
			}
		}

		/**
		 * Used by jQuery UI wrapper controls to find the element on which to apply the jQuery  function
		 *
		 * NOTE: Some controls that use jQuery will get wrapped with extra divs by the jQuery library.
		 * If such a control then gets replaced by Ajax, the jQuery effects will be deleted. To solve this,
		 * the corresponding QCubed control should set UseWrapper to true, attach the jQuery effect to
		 * the wrapper, and override this function to return the id of the wrapper. See QDialogBase.class.php for
		 * an example.
		 *
		 * @return string the DOM element id on which to apply the jQuery UI function
		 */
		public function GetJqControlId() {
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
				if (defined('WATCHER_CLASS')) {
					$class = WATCHER_CLASS;
					$this->objWatcher = new $class(); // only create a watcher object when needed, since it is stored in the form state
				} else {
					$this->objWatcher = new QWatcher(); // only create a watcher object when needed, since it is stored in the form state
				}
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

		/**
		 * Returns true if the given control is anywhere in the parent hierarchy of this control.
		 *
		 * @param $objControl
		 * @return bool
		 */
		public function IsDescendantOf ($objControl) {
			$objParent = $this->objParentControl;
			while ($objParent) {
				if ($objParent == $objControl) {
					return true;
				}
				$objParent = $objParent->objParentControl;
			}
			return false;
		}

		/**
		 * Searches the control and it's hierarchy to see if a method by given name exists.
		 * This method searches only in the current control and its parents and so on.
		 * It will not search for the method in any siblings at any stage in the process.
		 *
		 * @param string $strMethodName            Name of the method
		 * @param bool   $blnIncludeCurrentControl Include this control as well?
		 *
		 * @return null|QControl The control found in the hierarchy to have the method
		 *                       Or null if no control was found in the hierarchy with the given name
		 */
		public function GetControlFromHierarchyByMethodName($strMethodName, $blnIncludeCurrentControl = true) {
			if ($blnIncludeCurrentControl == true) {
				$ctlDelegatorControl = $this;
			} else {
				if (!$this->ParentControl) {
					// ParentControl is null. This means the parent is a QForm.
					$ctlDelegatorControl = $this->Form;
				} else {
					$ctlDelegatorControl = $this->ParentControl;
				}
			}

			do {
				if (method_exists($ctlDelegatorControl, $strMethodName)) {
					return $ctlDelegatorControl;
				} else {
					if (!$ctlDelegatorControl->ParentControl) {
						// ParentControl is null. This means the parent is a QForm.
						$ctlDelegatorControl = $ctlDelegatorControl->Form;
					} else {
						$ctlDelegatorControl = $ctlDelegatorControl->ParentControl;
					}
				}
			} while (!($ctlDelegatorControl instanceof QForm));

			// If we are here, we could not find the method in the hierarchy/lineage of this control.
			return null;
		}

		/**
		 * Returns the form associated with the control. Used by the QDataBinder trait.
		 * @return QForm
		 */
		public function GetForm() {
			return $this->objForm;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP __get magic method implementation
		 * @param string $strName Property Name
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case "Display": return $this->blnDisplay;
				case "CausesValidation": return $this->mixCausesValidation;
				case "Required": return $this->blnRequired;
				case "ValidationError": return $this->strValidationError;
				case "Visible": return $this->blnVisible;
				case "PreferredRenderMethod": return $this->strPreferredRenderMethod;

				// LAYOUT
				case "HtmlBefore": return $this->strHtmlBefore;
				case "HtmlAfter": return $this->strHtmlAfter;
				case "Instructions": return $this->strInstructions;
				case "Warning": return $this->strWarning;
				case "Minimize": return $this->blnMinimize;

				case "Moveable": return $this->objDraggable && !$this->objDraggable->Disabled;
				case "Resizable": return $this->objResizable && !$this->objResizable->Disabled;
				case "Droppable": return $this->objDroppable && !$this->objDroppable->Disabled;
				case "DragObj": return $this->objDraggable;
				case "ResizeObj": return $this->objResizable;
				case "DropObj": return $this->objDroppable;

				// MISC
				case "ControlId": return $this->strControlId;
				case "WrapperId": return $this->strControlId . '_ctl';
				case "Form": return $this->objForm;
				case "ParentControl": return $this->objParentControl;

				case "Name": return $this->strName;
				case "Rendered": return $this->blnRendered;
				case "Rendering": return $this->blnRendering;
				case "OnPage": return $this->blnOnPage;
				case "RenderMethod": return $this->strRenderMethod;
				case "WrapperModified": return $this->blnWrapperModified;
				case "ActionParameter": return $this->mixActionParameter;
				case "ActionsMustTerminate": return $this->blnActionsMustTerminate;
				case "ScriptsOnly": return $this->blnScriptsOnly;
				case "WrapperCssClass": return $this->GetWrapperStyler()->CssClass;
				case "UseWrapper": return $this->blnUseWrapper;

				// SETTINGS
				case "JavaScripts": return $this->strJavaScripts;
				case "StyleSheets": return $this->strStyleSheets;

				case "Modified": return $this->IsModified();
				case "LinkedNode": return $this->objLinkedNode;
				case "WrapperStyles": return $this->getWrapperStyler();
				case "WrapLabel": return $this->blnWrapLabel;
				case "AutoRender": return $this->blnAutoRender;

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
			switch ($strName) {
				// Shunt position settings to the wrapper. Actual drawing will get resolved at draw time.
				case "Position":
				case "Top":
				case "Left":
					try {
						$this->getWrapperStyler()->__set($strName, $mixValue);
						$this->markAsWrapperModified();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Display":	// boolean to determine whether to display or not
					try {
						$mixValue = QType::Cast($mixValue, QType::Boolean);
						$this->markAsWrapperModified();
						$this->blnDisplay = $mixValue;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "CausesValidation":
					try {
						$this->mixCausesValidation = $mixValue;
						// This would not need to cause a redraw
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
				case "Visible":
					try {
						if ($this->blnVisible !== ($mixValue = QType::Cast($mixValue, QType::Boolean))) {
							$this->MarkAsModified();
							$this->blnVisible = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "PreferredRenderMethod":
					try {
						if ($this->strPreferredRenderMethod !== ($mixValue = QType::Cast($mixValue, QType::String))) {
							$this->MarkAsModified();
							$this->strPreferredRenderMethod = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "HtmlBefore":
					try {
						if ($this->strHtmlBefore !== ($mixValue = QType::Cast($mixValue, QType::String))) {
							$this->MarkAsModified();
							$this->strHtmlBefore = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "HtmlAfter":
					try {
						if ($this->strHtmlAfter !== ($mixValue = QType::Cast($mixValue, QType::String))) {
							$this->MarkAsModified();
							$this->strHtmlAfter = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Instructions":
					try {
						if ($this->strInstructions !== ($mixValue = QType::Cast($mixValue, QType::String))) {
							$this->MarkAsModified();
							$this->strInstructions = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Warning":
					try {
						$this->strWarning = QType::Cast($mixValue, QType::String);
						$this->MarkAsModified(); // always modify, since it will get reset on subsequent drawing
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "ValidationError":
					try {
						$this->strValidationError = QType::Cast($mixValue, QType::String);
						$this->MarkAsModified(); // always modify, since it will get reset on subsequent drawing
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Minimize":
					try {
						$this->blnMinimize = QType::Cast($mixValue, QType::Boolean);
						$this->MarkAsModified();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Moveable":
					try {
						$this->MarkAsWrapperModified();
						if (QType::Cast($mixValue, QType::Boolean)) {
							if (!$this->objDraggable) {
								$this->objDraggable = new QDraggable($this, $this->ControlId . 'draggable');
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
						$this->MarkAsWrapperModified();
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
						$this->MarkAsWrapperModified();
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
						if ($this->strName !== ($mixValue = QType::Cast($mixValue, QType::String))) {
							$this->MarkAsModified();
							$this->strName = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ActionParameter":
					try {
						$this->mixActionParameter = ($mixValue instanceof QJsClosure) ? $mixValue : QType::Cast($mixValue, QType::String);
						$this->MarkAsModified();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "WrapperCssClass":
					try {
						$strWrapperCssClass = QType::Cast($mixValue, QType::String);
						if ($this->GetWrapperStyler()->SetCssClass($strWrapperCssClass)) {
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
				case "WrapLabel":
					try {
						if($this->blnWrapLabel != QType::Cast($mixValue, QType::Boolean)) {
							$this->blnWrapLabel = !$this->blnWrapLabel;
							//need to render the parent again (including its children)
							$this->MarkAsModified();
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "SaveState":
					try {
						$this->blnSaveState = QType::Cast($mixValue, QType::Boolean);
						$this->_ReadState(); // during form creation, if we are setting this value, it means we want the state restored at form creation too, so handle both here.
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

				case "AutoRender":
					try {
						$this->blnAutoRender = QType::Cast($mixValue, QType::Boolean);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;


				// CODEGEN
				case "LinkedNode":
					try {
						$this->objLinkedNode = QType::Cast($mixValue, 'QQNode');
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

		/**
		 * Called by the form rendering code to add special attributes to the html form tag. If you need a spcial
		 * attribute in your form (e.g. a multipart attribute), add it to the strFormAttributes array.
		 *
		 * This function is not for general consumption.
		 *
		 * @ignore
		 * @return null|string
		 */
		public function _GetFormAttributes() {
			if (QApplication::$RequestMode == QRequestMode::Ajax) {
				if ($this->IsModified()) {
					return $this->strFormAttributes;
				}
				else {
					return null;
				}
			}
			else {
				return $this->strFormAttributes;
			}
		}


		/**
		 * Returns a description of the options available to modify by the designer for the code generator.
		 *
		 * @return QModelConnectorParam[]
		 */
		public static function GetModelConnectorParams() {
			return array(
				new QModelConnectorParam ('QControl', 'CssClass', 'Css Class assigned to the control', QType::String),
				new QModelConnectorParam ('QControl', 'AccessKey', 'Access Key to focus control', QType::String),
				new QModelConnectorParam ('QControl', 'CausesValidation', 'How and what to validate. Can also be set to a control.', QModelConnectorParam::SelectionList,
					array(
						null=>'None',
						'QCausesValidation::AllControls'=>'All Controls',
						'QCausesValidation::SiblingsAndChildren'=>'Siblings And Children',
						'QCausesValidation::SiblingsOnly'=>'Siblings Only'
					)
				),
				new QModelConnectorParam ('QControl', 'Enabled', 'Will it start as enabled (default true)?', QType::Boolean),
				new QModelConnectorParam ('QControl', 'Required', 'Will it fail validation if nothing is entered (default depends on data definition, if NULL is allowed.)?', QType::Boolean),
				new QModelConnectorParam ('QControl', 'TabIndex', '', QType::Integer),
				new QModelConnectorParam ('QControl', 'ToolTip', '', QType::String),
				new QModelConnectorParam ('QControl', 'Visible', '', QType::Boolean),
				new QModelConnectorParam ('QControl', 'Height', 'Height in pixels. However, you can specify a different unit (e.g. 3.0 em).', QType::String),
				new QModelConnectorParam ('QControl', 'Width', 'Width in pixels. However, you can specify a different unit (e.g. 3.0 em).', QType::String),
				new QModelConnectorParam ('QControl', 'Instructions', 'Additional help for user.', QType::String),
				new QModelConnectorParam ('QControl', 'Moveable', '', QType::Boolean),
				new QModelConnectorParam ('QControl', 'Resizable', '', QType::Boolean),
				new QModelConnectorParam ('QControl', 'Droppable', '', QType::Boolean),
				new QModelConnectorParam ('QControl', 'UseWrapper', 'Control will be forced to be wrapped with a div', QType::Boolean),
				new QModelConnectorParam ('QControl', 'WrapperCssClass', '', QType::String),
				new QModelConnectorParam ('QControl', 'PreferredRenderMethod', '', QType::String)
			);

		}


	}