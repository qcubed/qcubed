<?php
	/**
	 * This file contains all basic action classes: QAction, QServerAction, QAjaxAction, etc.
	 *
	 * @package Actions
	 * @filesource
	 */

	/**
	 * Base class for all other Actions.
	 *
	 * @package Actions
	 * @property QEvent $Event Any QEvent derivated class instance
	 */
	abstract class QAction extends QBaseClass {
		/**
		 * Abstract method, implemented in derived classes. Returns the JS needed for the action to work
		 *
		 * @param QControl $objControl
		 *
		 * @return mixed
		 */
		abstract public function RenderScript(QControl $objControl);

		/** @var QEvent Event object which will fire this action */
		protected $objEvent;

		/**
		 * @param QControl|QControlBase $objControl   QControl for which the actions have to be rendered
		 * @param string                $strEventName Name of the event for which the actions have to be rendered
		 * @param QAction[]             $objActions   Array of actionss
		 *
		 * @return null|string
		 * @throws Exception
		 */
		public static function RenderActions(QControl $objControl, $strEventName, $objActions) {
			$strToReturn = '';
			$strJqUiProperty = null;

			if ($objControl->ActionsMustTerminate) {
				$strToReturn .= ' event.preventDefault();';
			}

			if ($objActions && count($objActions)) {
				foreach ($objActions as $objAction) {
					if ($objAction->objEvent->EventName != $strEventName) {
						throw new Exception('Invalid Action Event in this entry in the ActionArray');
					}

					if ($objAction->objEvent instanceof QJqUiPropertyEvent) {
						$strJqUiProperty = $objAction->objEvent->JqProperty;
					}

					if ($objAction->objEvent->Delay > 0) {
						$strCode = sprintf(" qcubed.setTimeout('%s', \$j.proxy(function(){%s},this), %s);",
							$objControl->ControlId,
							$objAction->RenderScript($objControl),
							$objAction->objEvent->Delay);
					} else {
						$strCode = ' ' . $objAction->RenderScript($objControl);
					}

					// Add Condition (if applicable)
					if (strlen($objAction->objEvent->Condition)) {
						$strCode = sprintf(' if (%s) {%s}', $objAction->objEvent->Condition, trim($strCode));
					}

					// Append it to the Return Value
					$strToReturn .= $strCode;
				}
			}

			if (strlen($strToReturn)) {
				if ($strJqUiProperty) {
					return sprintf('$j("#%s").%s("option", {%s: function(event, ui){
								%s
								}});
								', $objControl->getJqControlId(), $objControl->getJqSetupFunction(), $strJqUiProperty, substr($strToReturn, 1));
				} elseif ($objControl instanceof QControlProxy) {
					if ($objControl->TargetControlId) {
						return sprintf('$j("#%s").on("%s", function(event, ui){
									%s
									});
									', $objControl->TargetControlId, $strEventName, substr($strToReturn, 1));
					}
				} else {
					return sprintf('$j("#%s").on("%s", function(event, ui){
								%s
								});
								', $objControl->getJqControlId(), $strEventName, substr($strToReturn, 1));

					//return sprintf('%s="%s" ', $strEventName, substr($strToReturn, 1));
				}
			}

			return null;
		}

		/**
		 * PHP Magic function to set the property values of an object of the class
		 * In this case, we only have 'Event' property to be set
		 *
		 * @param string $strName  Name of the property
		 * @param string $mixValue Value of the property
		 *
		 * @throws QCallerException
		 * @return mixed|null|string
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'Event':
					return ($this->objEvent = QType::Cast($mixValue, 'QEvent'));

				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**
		 * PHP Magic function to get the property values of an object of the class
		 * In this case, we only have 'Event' property to be set
		 *
		 * @param string $strName Name of the property
		 *
		 * @return mixed|null|string
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Event':
					return $this->objEvent;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	/**
	 * Server actions are handled through a full-page postback.
	 *
	 * @package                                        Actions
	 * @property-read string $MethodName               Name of the associated action handling method
	 * @property-read mixed  $CausesValidationOverride An override for CausesValidation property (if supplied)
	 * @property-read string $JsReturnParam            The parameter to be returned
	 *                                                 (overrides the Control's ActionParameter)
	 */
	class QServerAction extends QAction {
		/** @var string Name of the method in the form to be called */
		protected $strMethodName;
		/**
		 * @var mixed A constant from QCausesValidation enumeration class
		 *            It is set in the constructor via the corresponding argument
		 */
		protected $mixCausesValidationOverride;
		/** @var string An over-ride for the Control's ActionParameter */
		protected $strJsReturnParam;

		/**
		 * @param string $strMethodName                The method name which is to be assigned as the event handler
		 *                                             (for the event being created)
		 * @param string $mixCausesValidationOverride  A constant from QCausesValidation
		 *                                             (or $this or an array of QControls)
		 * @param string $strJsReturnParam             The parameter to be returned when this event occurs
		 *                                             (this is an override for the control's ActionParameter)
		 */
		public function __construct($strMethodName = null, $mixCausesValidationOverride = null,
		                            $strJsReturnParam = '') {
			$this->strMethodName = $strMethodName;
			$this->mixCausesValidationOverride = $mixCausesValidationOverride;
			$this->strJsReturnParam = $strJsReturnParam;
		}

		/**
		 * PHP Magic function to get the property values of an object of the class
		 *
		 * @param string $strName Name of the property
		 *
		 * @return mixed|null|string
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'MethodName':
					return $this->strMethodName;
				case 'CausesValidationOverride':
					return $this->mixCausesValidationOverride;
				case 'JsReturnParam':
					return $this->strJsReturnParam;
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
		 * Determines the ActionParameter associated with the action and returns it
		 *
		 * @param QControlBase $objControl
		 *
		 * @return string The action parameter
		 */
		protected function getActionParameter($objControl) {
			if ($objActionParameter = $this->strJsReturnParam) {
				return $objActionParameter;
			}
			if ($objActionParameter = $this->objEvent->JsReturnParam) {
				return $objActionParameter;
			}
			$objActionParameter = $objControl->ActionParameter;
			if ($objActionParameter instanceof QJsClosure) {
				return $objActionParameter->toJsObject() . '.call()';
			}

			return "'" . addslashes($objActionParameter) . "'";
		}

		/**
		 * Returns the JS which will be called on the client side
		 * which will result in the event handler being called
		 *
		 * @param QControl $objControl
		 *
		 * @return string
		 */
		public function RenderScript(QControl $objControl) {
			return sprintf("qc.pB('%s', '%s', '%s', %s);",
				$objControl->Form->FormId, $objControl->ControlId, get_class($this->objEvent), $this->getActionParameter($objControl));
		}
	}

	/**
	 * Ajax actions are handled through an asynchronous HTTP request (=AJAX).
	 * No full-page refresh happens when such an action is executing.
	 *
	 * @property-read           $MethodName               Name of the (event-handler) method to be called
	 *              the event handler - function containing the actual code for the Ajax action
	 * @property-read QWaitIcon $WaitIconControl          the waiting icon control for this Ajax Action
	 * @property-read mixed     $CausesValidationOverride what kind of validation over-ride is to be implemented
	 *              on this action.(See the QCausesValidation class and QFormBase class to understand in greater depth)
	 * @property-read string    JsReturnParam             The line of javascript which would set the 'strParameter' value on the
	 *              client-side when the action occurs!
	 *              (see /assets/_core/php/examples/other_controls/js_return_param_example.php for example)
	 * @property-read string    Id                        The Ajax Action ID for this action.
	 * @package     Actions
	 */
	class QAjaxAction extends QAction {
		/** @var string Ajax Action ID */
		protected $strId;
		/** @var string The event handler function name */
		protected $strMethodName;
		/** @var QWaitIcon Wait Icon to be used for this particular action */
		protected $objWaitIconControl;
		/**
		 * @var mixed what kind of validation over-ride is to be implemented
		 *              (See the QCausesValidation class and QFormBase class to understand in greater depth)
		 */
		protected $mixCausesValidationOverride;
		/**
		 * @var string the line of javascript which would set the 'strParameter' value on the
		 *              client-side when the action occurs!
		 */
		protected $strJsReturnParam;

		/**
		 * @param string           $strMethodName               Name of the event handler function to be called
		 * @param string|QWaitIcon $objWaitIconControl          Wait Icon for the action
		 * @param null|mixed       $mixCausesValidationOverride what kind of validation over-ride is to be implemented
		 * @param string           $strJsReturnParam            the line of javascript which would set the 'strParameter' value on the
		 *                                                      client-side when the action occurs!
		 */
		public function __construct($strMethodName = null, $objWaitIconControl = 'default',
		                            $mixCausesValidationOverride = null, $strJsReturnParam = "") {
			$this->strId = null;
			$this->strMethodName = $strMethodName;
			$this->objWaitIconControl = $objWaitIconControl;
			$this->mixCausesValidationOverride = $mixCausesValidationOverride;
			$this->strJsReturnParam = $strJsReturnParam;
		}

		public function __clone() {
			$this->strId = null; //we are a fresh clone, lets reset the id and get our own later (in RenderScript)
		}

		/**
		 * PHP Magic function to get the property values of a class object
		 *
		 * @param string $strName Name of the property
		 *
		 * @return mixed|null|string
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'MethodName':
					return $this->strMethodName;
				case 'WaitIconControl':
					return $this->objWaitIconControl;
				case 'CausesValidationOverride':
					return $this->mixCausesValidationOverride;
				case 'JsReturnParam':
					return $this->strJsReturnParam;
				case 'Id':
					return $this->strId;
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
		 * Returns the control's ActionParameter in string format
		 *
		 * @param QControl $objControl
		 *
		 * @return string
		 */
		protected function getActionParameter($objControl) {
			if ($objActionParameter = $this->strJsReturnParam) {
				return $objActionParameter;
			}
			if ($objActionParameter = $this->objEvent->JsReturnParam) {
				return $objActionParameter;
			}
			$objActionParameter = $objControl->ActionParameter;
			if ($objActionParameter instanceof QJsClosure) {
				return $objActionParameter->toJsObject() . '.call()';
			}

			return "'" . addslashes($objActionParameter) . "'";
		}

		/**
		 * Returns the RenderScript script for the action.
		 * The returned script is to be executed on the client side when the action is executed
		 * (in this case qc.pA function is executed)
		 *
		 * @param QControl $objControl
		 *
		 * @return string
		 */
		public function RenderScript(QControl $objControl) {
			$strWaitIconControlId = null;
			if ($this->strId == null) {
				$this->strId = $objControl->Form->GenerateAjaxActionId();
			}

			if ((gettype($this->objWaitIconControl) == 'string') && ($this->objWaitIconControl == 'default')) {
				if ($objControl->Form->DefaultWaitIcon) {
					$strWaitIconControlId = $objControl->Form->DefaultWaitIcon->ControlId;
				}
			} else {
				if ($this->objWaitIconControl) {
					$strWaitIconControlId = $this->objWaitIconControl->ControlId;
				}
			}

			return sprintf("qc.pA('%s', '%s', '%s#%s', %s, '%s');",
				$objControl->Form->FormId, $objControl->ControlId, get_class($this->objEvent), $this->strId, $this->getActionParameter($objControl), $strWaitIconControlId);
		}
	}

	/**
	 * Server control action is identical to server action, except
	 * the handler for it is defined NOT in the form, but in a control.
	 *
	 * @package Actions
	 */
	class QServerControlAction extends QServerAction {
		/**
		 * @param QControl $objControl                  Control where the action handler is defined
		 * @param string   $strMethodName               Name of the method which acts as the action handler
		 * @param mixed    $mixCausesValidationOverride Override for CausesValidation (if needed)
		 * @param string   $strJsReturnParam            Override for ActionParameter
		 */
		public function __construct(QControl $objControl, $strMethodName, $mixCausesValidationOverride = null,
		                            $strJsReturnParam = "") {
			parent::__construct($objControl->ControlId . ':' . $strMethodName, $mixCausesValidationOverride, $strJsReturnParam);
		}
	}

	/**
	 * Ajax control action is identical to Ajax action, except
	 * the handler for it is defined NOT on the form host, but on a control.
	 *
	 * @package Actions
	 */
	class QAjaxControlAction extends QAjaxAction {
		/**
		 * @param QControl $objControl                  Control where the action handler is defined
		 * @param string   $strMethodName               Name of the action handler method
		 * @param string   $objWaitIconControl          The wait icon to be implemented
		 * @param null     $mixCausesValidationOverride Override for CausesValidation (if needed)
		 * @param string   $strJsReturnParam            Override for ActionParameter
		 */
		public function __construct(QControl $objControl, $strMethodName, $objWaitIconControl = 'default',
		                            $mixCausesValidationOverride = null, $strJsReturnParam = "") {
			parent::__construct($objControl->ControlId . ':' . $strMethodName, $objWaitIconControl, $mixCausesValidationOverride, $strJsReturnParam);
		}
	}

	/**
	 * Client-side action - no postbacks of any kind are performed.
	 * All handling activity happens in Javascript.
	 *
	 * @package Actions
	 */
	class QRedirectAction extends QAction {
		/** @var string JS to be run on the client side */
		protected $strJavaScript;

		/**
		 * possible values:
		 * http://google.com
		 * index.php?page=view
		 * /foo/bar/woot.html
		 *
		 * @param string $strUrl
		 */
		public function __construct($strUrl) {
			$this->strJavaScript = sprintf("document.location.href ='%s'", trim($strUrl));
		}

		/**
		 * PHP Magic function to get the property values of a class object
		 *
		 * @param string $strName Name of the property
		 *
		 * @return mixed|null|string
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'JavaScript':
					return $this->strJavaScript;
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
		 * Returns the JS which runs on the client side
		 * @param QControl $objControl
		 *
		 * @return string
		 */
		public function RenderScript(QControl $objControl) {
			return sprintf('%s;', $this->strJavaScript);
		}
	}

	/**
	 * Client-side action - no postbacks of any kind are performed.
	 * All handling activity happens in Javascript.
	 *
	 * @package Actions
	 */
	class QJavaScriptAction extends QAction {
		/** @var string JS to be run on the client side */
		protected $strJavaScript;

		/**
		 * The constructor
		 * @param string $strJavaScript JS which is to be executed on the client side
		 */
		public function __construct($strJavaScript) {
			$this->strJavaScript = trim($strJavaScript);
			if (QString::LastCharacter($this->strJavaScript) == ';') {
				$this->strJavaScript = substr($this->strJavaScript, 0, strlen($this->strJavaScript) - 1);
			}
		}

		/**
		 * PHP Magic function to get the property values of a class object
		 *
		 * @param string $strName Name of the property
		 *
		 * @return mixed|null|string
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'JavaScript':
					return $this->strJavaScript;
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
		 * Returns the JS whcih will be executed on the client side
		 * @param QControl $objControl
		 *
		 * @return string
		 */
		public function RenderScript(QControl $objControl) {
			return sprintf('%s;', $this->strJavaScript);
		}
	}

	/**
	 * This action works as a if-else stopper for another action.
	 * This action should be added to a control with the same event type before another action of that event type
	 * Doing so brings up a JavaScript Confirmation box in front of the user.
	 * If the user clicks on 'OK', then the next next action is executed (and any actions after that as well)
	 * If the user clicks on 'Cancel', then next/rest of the action(s) are not executed
	 * @package Actions
	 */
	class QConfirmAction extends QAction {
		/** @var string Message to be shown to the user on the confirmation prompt */
		protected $strMessage;

		/**
		 * Constructor of the function
		 * @param string $strMessage Message which is to be set as the confirmation prompt message
		 */
		public function __construct($strMessage) {
			$this->strMessage = $strMessage;
		}

		/**
		 * PHP Magic function to get the property values of an object of the class
		 *
		 * @param string $strName Name of the property
		 *
		 * @return mixed|null|string
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Message':
					return $this->strMessage;
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
		 * Returns the JS to be executed on the client side
		 * @param QControl $objControl
		 *
		 * @return string The JS to be executed
		 */
		public function RenderScript(QControl $objControl) {
			$strMessage = JavaScriptHelper::toJsObject($this->strMessage);

			return sprintf("if (!confirm(%s)) return false;", $strMessage);
		}
	}

	/**
	 * Displays an alert to the user
	 *
	 * @package Actions
	 */
	class QAlertAction extends QAction {
		/** @var string Message to be shown as the alert */
		protected $strMessage;

		/**
		 * Constructor
		 *
		 * @param string $strMessage Message to be shown as the alert
		 */
		public function __construct($strMessage) {
			$this->strMessage = $strMessage;
		}

		/**
		 * PHP Magic function to get the property values of an object of the class
		 *
		 * @param string $strName Name of the property
		 *
		 * @return mixed|null|string
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Message':
					return $this->strMessage;
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
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return string
		 */
		public function RenderScript(QControl $objControl) {
			$strMessage = JavaScriptHelper::toJsObject($this->strMessage);

			return sprintf("alert(%s);", $strMessage);
		}
	}

	/**
	 * @package Actions
	 */
	class QResetTimerAction extends QAction {
		/**
		 * Returns the JavaScript to be executed on the client side (to clear the timeout on the control)
		 *
		 * @param QControl $objControl Control on which the timeout has to be cleared
		 *
		 * @return string JavaScript to be executed on the client side
		 */
		public function RenderScript(QControl $objControl) {
			return sprintf("qcubed.clearTimeout('%s');", $objControl->ControlId);
		}
	}

	/**
	 * Prevents the default action on an event.
	 *
	 * E.g. If you have a click action added to a label whose text is a link, clicking it will take the action
	 * but also take you to the link pointed by the label. You can add a QTerminateAction after all QClickEvent
	 * handlers and that will make sure that action handlers are triggered but the browser does not navigate
	 * the user to the link pointed by the label
	 *
	 * @package Actions
	 */
	class QTerminateAction extends QAction {
		/**
		 * Returns the JS for the browser
		 *
		 * @param QControl $objControl
		 *
		 * @return string JS to prevent the default action
		 */
		public function RenderScript(QControl $objControl) {
			return 'event.preventDefault();';
		}
	}

	/**
	 * Prevents the event from bubbling up the DOM tree, preventing any parent
	 * handlers from being notified of the event.
	 *
	 * @package Actions
	 */
	class QStopPropagationAction extends QAction {
		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return string Client side JS
		 */
		public function RenderScript(QControl $objControl) {
			return 'event.stopPropagation();';
		}
	}

	/**
	 * Toggle the Disaply of a control
	 *
	 * @package Actions
	 */
	class QToggleDisplayAction extends QAction {
		/** @var string Control ID of the control */
		protected $strControlId = null;
		/** @var boolean|null Enforce 'show' or 'hide' action */
		protected $blnDisplay = null;

		/**
		 * @param QControl|QControlBase $objControl
		 * @param bool                  $blnDisplay
		 *
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
		public function __construct($objControl, $blnDisplay = null) {
			if (!($objControl instanceof QControl)) {
				throw new QCallerException('First parameter of constructor is expecting an object of type QControl');
			}

			$this->strControlId = $objControl->ControlId;

			if (!is_null($blnDisplay)) {
				$this->blnDisplay = QType::Cast($blnDisplay, QType::Boolean);
			}
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return string Returns the JavaScript to be executed on the client side
		 */
		public function RenderScript(QControl $objControl) {
			if ($this->blnDisplay === true) {
				$strShowOrHide = 'show';
			} else {
				if ($this->blnDisplay === false) {
					$strShowOrHide = 'hide';
				} else {
					$strShowOrHide = '';
				}
			}

			return sprintf("qc.getW('%s').toggleDisplay('%s');",
				$this->strControlId, $strShowOrHide);
		}
	}

	/**
	 * Toggle the 'enabled' status of a control
	 * NOTE: It does not change the Enabled property on the server side
	 *
	 * @package Actions
	 */
	class QToggleEnableAction extends QAction {
		/** @var null|string Control ID of the control to be Enabled/Disabled */
		protected $strControlId = null;
		/** @var boolean|null Enforce the Enabling or Disabling action */
		protected $blnEnabled = null;

		/**
		 * @param QControl|QControlBase $objControl
		 * @param boolean               $blnEnabled
		 *
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
		public function __construct($objControl, $blnEnabled = null) {
			if (!($objControl instanceof QControl)) {
				throw new QCallerException('First parameter of constructor is expecting an object of type QControl');
			}

			$this->strControlId = $objControl->ControlId;

			if (!is_null($blnEnabled)) {
				$this->blnEnabled = QType::Cast($blnEnabled, QType::Boolean);
			}
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return string Client side JS
		 */
		public function RenderScript(QControl $objControl) {
			if ($this->blnEnabled === true) {
				$strEnableOrDisable = 'enable';
			} else {
				if ($this->blnEnabled === false) {
					$strEnableOrDisable = 'disable';
				} else {
					$strEnableOrDisable = '';
				}
			}

			return sprintf("qc.getW('%s').toggleEnabled('%s');", $this->strControlId, $strEnableOrDisable);
		}
	}

	/**
	 * Registers the click position on a control
	 *
	 * @package Actions
	 */
	class QRegisterClickPositionAction extends QAction {
		/** @var null|string Control ID of the control on which the click position has to be registered */
		protected $strControlId = null;

		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return string
		 */
		public function RenderScript(QControl $objControl) {
			return sprintf("qc.getW('%s').registerClickPosition(event);", $objControl->ControlId);
		}
	}

	/**
	 * Shows a dialog box (QDialogBox)
	 *
	 * @package Actions
	 */
	class QShowDialogBox extends QAction {
		/** @var null|string Control ID of the dialog box (QDialogBox) */
		protected $strControlId = null;
		/**
		 * @var null|string The Javascript that executes on the client side
		 *                  For this action, this string contains the JS to show the dialog box
		 */
		protected $strJavaScript = null;

		/**
		 * Constructor method
		 *
		 * @param QDialogBox $objControl
		 *
		 * @throws QCallerException
		 */
		public function __construct($objControl) {
			if (!($objControl instanceof QDialogBox)) {
				throw new QCallerException('First parameter of constructor is expecting an object of type QDialogBox');
			}

			$this->strControlId = $objControl->ControlId;
			$this->strJavaScript = $objControl->GetShowDialogJavaScript();
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return string JS to be executed on the client side
		 */
		public function RenderScript(QControl $objControl) {
			return (sprintf('%s; qcubed.recordControlModification("%s", "Display", "1");', $this->strJavaScript, $this->strControlId));
		}
	}

	/**
	 * Hides a dialog box (QDialogBox)
	 *
	 * @package Actions
	 */
	class QHideDialogBox extends QAction {
		/** @var null|string The JS for hiding the dialog box */
		protected $strJavaScript = null;

		/**
		 * Constructor
		 *
		 * @param QDialogBox $objControl
		 *
		 * @throws QCallerException
		 */
		public function __construct($objControl) {
			if (!($objControl instanceof QDialogBox)) {
				throw new QCallerException('First parameter of constructor is expecting an object of type QDialogBox');
			}

			$this->strJavaScript = $objControl->GetHideDialogJavaScript();
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return null|string JS to be executed on the client side
		 */
		public function RenderScript(QControl $objControl) {
			return $this->strJavaScript;
		}
	}

	/**
	 * Shows a QDialog
	 * This is the JQuery UI alternative to show dialog
	 *
	 * @package Actions
	 */
	class QShowDialog extends QAction {
		/** @var null|string The JS to show the dialog */
		protected $strJavaScript = null;

		/**
		 * Constructor
		 *
		 * @param QDialog $objControl
		 *
		 * @throws QCallerException
		 */
		public function __construct($objControl) {
			if (!($objControl instanceof QDialog)) {
				throw new QCallerException('First parameter of constructor is expecting an object of type QDialog');
			}

			$strControlId = $objControl->getJqControlId();
			$this->strJavaScript = sprintf('jQuery("#%s").dialog("open");', $strControlId);
		}

		/**
		 * Returns the JavaScript to be executed on the client side for opening/showing the dialog
		 *
		 * @param QControl $objControl
		 *
		 * @return null|string JS that will be run on the client side
		 */
		public function RenderScript(QControl $objControl) {
			return $this->strJavaScript;
		}
	}

	/**
	 * Hiding a JQuery UI Dialog (QDialog)
	 *
	 * @package Actions
	 */
	class QHideDialog extends QAction {
		/** @var null|string JS to be executed on the client side for closing the dialog */
		protected $strJavaScript = null;

		/**
		 * Constructor
		 *
		 * @param QDialog $objControl
		 *
		 * @throws QCallerException
		 */
		public function __construct($objControl) {
			if (!($objControl instanceof QDialog)) {
				throw new QCallerException('First parameter of constructor is expecting an object of type QDialog');
			}

			$strControlId = $objControl->getJqControlId();
			$this->strJavaScript = sprintf('jQuery("#%s").dialog("close");', $strControlId);
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return null|string JavaScript to be executed on the client side
		 */
		public function RenderScript(QControl $objControl) {
			return $this->strJavaScript;
		}
	}

	/**
	 * Puts focus on a Control (on the client side/browser)
	 * @package Actions
	 */
	class QFocusControlAction extends QAction {
		/** @var null|string Control ID of the control on which focus is to be put */
		protected $strControlId = null;

		/**
		 * Constructor
		 *
		 * @param QControl $objControl
		 *
		 * @throws QCallerException
		 */
		public function __construct($objControl) {
			if (!($objControl instanceof QControl)) {
				throw new QCallerException('First parameter of constructor is expecting an object of type QControl');
			}

			$this->strControlId = $objControl->ControlId;
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return string JavaScript to be executed on the client side
		 */
		public function RenderScript(QControl $objControl) {
			// for firefox focus is special when in a blur or in a focusout event
			// http://stackoverflow.com/questions/7046798/jquery-focus-fails-on-firefox/7046837#7046837
			return sprintf("setTimeout(function(){qc.getW('%s').focus();},0);", $this->strControlId);
		}
	}

	/**
	 * Blurs (JS blur, not visual blur) a control on server side (i.e. removes focus from that control)
	 *
	 * @package Actions
	 */
	class QBlurControlAction extends QAction {
		/** @var null|string Control ID of the control from which focus has to be removed */
		protected $strControlId = null;

		/**
		 * Constructor
		 *
		 * @param QControl $objControl
		 *
		 * @throws QCallerException
		 */
		public function __construct($objControl) {
			if (!($objControl instanceof QControl)) {
				throw new QCallerException('First parameter of constructor is expecting an object of type QControl');
			}

			$this->strControlId = $objControl->ControlId;
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return string JavaScript to be executed on the client side
		 */
		public function RenderScript(QControl $objControl) {
			return sprintf("qc.getW('%s').blur();", $this->strControlId);
		}
	}

	/**
	 * Selects contents inside a QTextBox on the client-side/browser
	 * @package Actions
	 */
	class QSelectControlAction extends QAction {
		/** @var null|string Control ID of the QTextBox which is to be selected */
		protected $strControlId = null;

		/**
		 * Constructor
		 *
		 * @param QTextBox $objControl
		 *
		 * @throws QCallerException
		 */
		public function __construct($objControl) {
			if (!($objControl instanceof QTextBox)) {
				throw new QCallerException('First parameter of constructor is expecting an object of type QTextBox');
			}

			$this->strControlId = $objControl->ControlId;
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return string JavaScript to be executed on the client side
		 */
		public function RenderScript(QControl $objControl) {
			return sprintf("qc.getW('%s').select();", $this->strControlId);
		}
	}

	/**
	 * Can add or remove an extra CSS class from a control.
	 * Should be used mostly for temporary purposes such as 'hovering' over a control
	 *
	 * @package Actions
	 */
	class QCssClassAction extends QAction {
		/** @var null|string The CSS class to be added to the control */
		protected $strTemporaryCssClass = null;
		/** @var bool Should the CSS class be applied by removing the previous one? */
		protected $blnOverride = false;

		/**
		 * Constructor
		 *
		 * @param null|string $strTemporaryCssClass The temporary class to be added to the control
		 *                                          If null, it will reset the CSS classes to the previous set
		 * @param bool        $blnOverride          Should the previously set classes be removed (true) or not (false)
		 *                                          This will not reset the CSS class on the server side
		 */
		public function __construct($strTemporaryCssClass = null, $blnOverride = false) {
			$this->strTemporaryCssClass = $strTemporaryCssClass;
			$this->blnOverride = $blnOverride;
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return string The JavaScript to be executed on the client side
		 */
		public function RenderScript(QControl $objControl) {
			// Specified a Temporary Css Class to use?
			if (is_null($this->strTemporaryCssClass)) {
				// No Temporary CSS Class -- use the Control's already-defined one
				return sprintf("qc.getC('%s').className = '%s';", $objControl->ControlId, $objControl->CssClass);
			} else {
				// Are we overriding or are we displaying this temporary css class outright?
				if ($this->blnOverride) {
					// Overriding
					return sprintf("qc.getC('%s').className = '%s %s';", $objControl->ControlId, $objControl->CssClass, $this->strTemporaryCssClass);
				} else {
					// Use Temp Css Class Outright
					return sprintf("qc.getC('%s').className = '%s';", $objControl->ControlId, $this->strTemporaryCssClass);
				}
			}
		}
	}

	/**
	 * Sets the CSS class of a control on the client side (does not update the server side)
	 *
	 * @package Actions
	 */
	class QCssAction extends QAction {
		/** @var string CSS property to be set */
		protected $strCssProperty = null;
		/** @var string Value to which the CSS property should be set */
		protected $strCssValue = null;
		/**
		 * @var null|string The control ID for which the action should be done.
		 *                  By default, it is applied to the QControl to which the action is added.
		 */
		protected $strControlId = null;

		/**
		 * Constructor
		 *
		 * @param string        $strCssProperty
		 * @param string        $strCssValue
		 * @param null|QControl $objControl
		 */
		public function __construct($strCssProperty, $strCssValue, $objControl = null) {
			$this->strCssProperty = $strCssProperty;
			$this->strCssValue = $strCssValue;
			if ($objControl) {
				$this->strControlId = $objControl->ControlId;
			}
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 *
		 * @param QControl $objControl
		 *
		 * @return string JavaScript to be executed on the client side for setting the CSS
		 */
		public function RenderScript(QControl $objControl) {
			if ($this->strControlId == null) {
				$this->strControlId = $objControl->ControlId;
			}

			// Specified a Temporary Css Class to use?
			return sprintf('$j("#%s").css("%s", "%s"); ', $this->strControlId, $this->strCssProperty, $this->strCssValue);
		}
	}

	/**
	 * Shows a Calendar Control
	 *
	 * @package Actions
	 */
	class QShowCalendarAction extends QAction {
		/** @var null|string Control ID of the calendar */
		protected $strControlId = null;

		/**
		 * @param QCalendar $calControl
		 *
		 * @throws QCallerException
		 */
		public function __construct($calControl) {
			if (!($calControl instanceof QCalendar)) {
				throw new QCallerException('First parameter of constructor is expecting an object of type QCalendar');
			}
			$this->strControlId = $calControl->ControlId;
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 * @param QControl $objControl
		 *
		 * @return string JavaScript to be executed on the client side
		 */
		public function RenderScript(QControl $objControl) {
			return sprintf("qc.getC('%s').showCalendar();", $this->strControlId);
		}
	}

	/**
	 * Hides a calendar control
	 *
	 * @package Actions
	 */
	class QHideCalendarAction extends QAction {
		/** @var null|string Control ID of the calendar control */
		protected $strControlId = null;

		/**
		 * Constructor
		 * @param QCalendar $calControl
		 *
		 * @throws QCallerException
		 */
		public function __construct($calControl) {
			if (!($calControl instanceof QCalendar)) {
				throw new QCallerException('First parameter of constructor is expecting an object of type QCalendar');
			}
			$this->strControlId = $calControl->ControlId;
		}

		/**
		 * Returns the JavaScript to be executed on the client side
		 * @param QControl $objControl
		 *
		 * @return string JavaScript to be executed on the client side
		 */
		public function RenderScript(QControl $objControl) {
			return sprintf("qc.getC('%s').hideCalendar();", $this->strControlId);
		}
	}
?>