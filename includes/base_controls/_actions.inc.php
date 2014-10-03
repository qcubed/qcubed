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
	 * @property string $Event
	 */
	abstract class QAction extends QBaseClass {
		abstract public function RenderScript(QControl $objControl);

		protected $objEvent;

		/**
		 * @param QControl|QControlBase $objControl   QControl for which the actions have to be rendered
		 * @param string                $strEventName Name of the event for which the actions have to be rendered
		 * @param array                 $objActions   Array of actionss
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

			if ($objActions && count($objActions)) foreach ($objActions as $objAction) {
				if ($objAction->objEvent->EventName != $strEventName)
					throw new Exception('Invalid Action Event in this entry in the ActionArray');

				if ($objAction->objEvent instanceof QJqUiPropertyEvent) {
					$strJqUiProperty = $objAction->objEvent->JqProperty;
				}

				$strScript = $objAction->RenderScript($objControl);
				if (strpos ($strScript, '__PARAM__')) {
					// a script that uses an action parameter.
					$strActionParam = $objAction->getActionParameter($objControl);
					if ($strActionParam === null || $strActionParam === '') {
						$strActionParam = "''"; // empty string to be inserted into javascript
					}
					if ($objAction->objEvent->Delay > 0) {
						// If the action parameter is javascript that returns a value related to 'this', we need to execute that
						// script before the timeout to get the value of the parameter at call time, because after the timeout, 'this' will
						// not be around.
						$strCode = sprintf("  var p = %s; qcubed.setTimeout('%s', '%s'.replace ('__PARAM__', '\\'' + p + '\\''), %s); ",
							$strActionParam,
							$objControl->ControlId,
							addslashes($strScript),
							$objAction->objEvent->Delay);

					} else {
						// Safe to include the action parameter javascript as part of the call.
						$strScript = str_replace ('__PARAM__', $strActionParam, $strScript);
						$strCode = ' ' . $strScript;
					}
				} else { // no action parameter
					if ($objAction->objEvent->Delay > 0) {
							$strCode = sprintf(" qcubed.setTimeout('%s', '%s', %s);",
								$objControl->ControlId,
								addslashes($strScript),
								$objAction->objEvent->Delay);
					} else {
						$strCode = ' ' . $strScript;
					}
				}

				// Add Condition (if applicable)
				if (strlen($objAction->objEvent->Condition))
					$strCode = sprintf(' if (%s) {%s}', $objAction->objEvent->Condition, trim($strCode));

				// Append it to the Return Value
				$strToReturn .= $strCode;
			}

			if (strlen($strToReturn)) {
				if ($strJqUiProperty) {
					return sprintf('$j("#%s").%s("option", {%s: function(event, ui){
								%s
								}});
								', $objControl->getJqControlId(), $objControl->getJqSetupFunction(), $strJqUiProperty,  substr($strToReturn, 1));
				} elseif ($objControl instanceof QControlProxy) {
					if ($objControl->TargetControlId) {
						return sprintf('$j("#%s").on("%s", function(event, ui){
									%s
									});
									', $objControl->TargetControlId, $strEventName,  substr($strToReturn, 1));
					}
				} else {
					return sprintf('$j("#%s").on("%s", function(event, ui){
								%s
								});
								', $objControl->getJqControlId(), $strEventName,  substr($strToReturn, 1));

					//return sprintf('%s="%s" ', $strEventName, substr($strToReturn, 1));
				}
			}
			return null;
		}

		/**
		 * PHP Magic function to set the property values of an object of the class
		 * In this case, we only have 'Event' property to be set
		 *
		 * @param string $strName Name of the property
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
		 * @param string $strName Name of the property
		 *
		 * @return mixed|null|string
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Event': return $this->objEvent;
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
	 * @package Actions
	 */
	class QServerAction extends QAction {
		protected $strMethodName;
		protected $mixCausesValidationOverride;
		protected $strJsReturnParam;

		public function __construct($strMethodName = null, $mixCausesValidationOverride = null, $strJsReturnParam = '') {
			$this->strMethodName = $strMethodName;
			$this->mixCausesValidationOverride = $mixCausesValidationOverride;
			$this->strJsReturnParam = $strJsReturnParam;
		}

		/**
		 * PHP Magic function to get the property values of an object of the class
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

		protected function getActionParameter($objControl) {
			if ($objActionParameter = $this->strJsReturnParam)
				return $objActionParameter;
			if ($objActionParameter = $this->objEvent->JsReturnParam)
				return $objActionParameter;
			$objActionParameter = $objControl->ActionParameter;
			if ($objActionParameter instanceof QJsClosure) {
				return $objActionParameter->toJsObject() . '.call()';
			}
			return "'" . addslashes($objActionParameter) . "'";
		}

		/**
		 * Render the script, returning the javascript to post the ajax command.
		 * __PARAM__ will be substituted with the action parameter when the script is executed.
		 *
		 * @param QControl $objControl
		 * @return string
		 */
		public function RenderScript(QControl $objControl) {
			return sprintf("qc.pB('%s', '%s', '%s', __PARAM__);",
				$objControl->Form->FormId, $objControl->ControlId, get_class($this->objEvent));
		}
	}

	/**
	 * Ajax actions are handled through an asynchronous HTTP request (=AJAX).
	 * No full-page refresh happens when such an action is executing.
	 * @property-read $MethodName Name of the (event-handler) method to be called
	 *              the event handler - function containing the actual code for the Ajax action
	 * @property-read QWaitIcon $WaitIconControl the waiting icon control for this Ajax Action
	 * @property-read mixed $CausesValidationOverride what kind of validation over-ride is to be implemented
	 *              on this action.(See the QCausesValidation class and QFormBase class to understand in greater depth)
	 * @property-read string JsReturnParam The line of javascript which would set the 'strParameter' value on the
	 *              client-side when the action occurs!
	 *              (see /assets/_core/php/examples/other_controls/js_return_param_example.php for example)
	 * @property-read string Id The Ajax Action ID for this action.
	 * @package Actions
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
		 * @param string   $strMethodName Name of the event handler function to be called
		 * @param string|QWaitIcon $objWaitIconControl Wait Icon for the action
		 * @param null|mixed   $mixCausesValidationOverride what kind of validation over-ride is to be implemented
		 * @param string $strJsReturnParam the line of javascript which would set the 'strParameter' value on the
		 *              client-side when the action occurs!
		 */
		public function __construct($strMethodName = null, $objWaitIconControl = 'default', $mixCausesValidationOverride = null,$strJsReturnParam = "") {
			$this->strId = NULL;
			$this->strMethodName = $strMethodName;
			$this->objWaitIconControl = $objWaitIconControl;
			$this->mixCausesValidationOverride = $mixCausesValidationOverride;
			$this->strJsReturnParam = $strJsReturnParam;
		}

		public function __clone() {
			$this->strId = NULL; //we are a fresh clone, lets reset the id and get our own later (in RenderScript)
		}

		/**
		 * PHP Magic function to get the property values of a class object
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
		 * @param QControl $objControl
		 *
		 * @return string
		 */
		protected function getActionParameter($objControl) {
			if ($objActionParameter = $this->strJsReturnParam)
				return $objActionParameter;
			if ($objActionParameter = $this->objEvent->JsReturnParam)
				return $objActionParameter;
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
		 * @param QControl $objControl
		 *
		 * @return string
		 */
		public function RenderScript(QControl $objControl) {
			$strWaitIconControlId = null;
			if ($this->strId == NULL) {
				$this->strId = $objControl->Form->GenerateAjaxActionId();
			}

			if ((gettype($this->objWaitIconControl) == 'string') && ($this->objWaitIconControl == 'default')) {
				if ($objControl->Form->DefaultWaitIcon)
					$strWaitIconControlId = $objControl->Form->DefaultWaitIcon->ControlId;
			} else if ($this->objWaitIconControl) {
				$strWaitIconControlId = $this->objWaitIconControl->ControlId;
			}

			return sprintf("qc.pA('%s', '%s', '%s#%s', __PARAM__, '%s');",
				$objControl->Form->FormId, $objControl->ControlId, get_class($this->objEvent), $this->strId, $strWaitIconControlId);
		}
	}

	/**
	 * Server control action is identical to server action, except
	 * the handler for it is defined NOT on the form host, but on a control.
	 *
	 * @package Actions
	 */
	class QServerControlAction extends QServerAction {
		/**
		 * @param QControl $objControl
		 * @param string     $strMethodName
		 * @param mixed     $mixCausesValidationOverride
		 * @param string   $strJsReturnParam
		 */
		public function __construct(QControl $objControl, $strMethodName, $mixCausesValidationOverride = null, $strJsReturnParam = "") {
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
		 * @param QControl         $objControl
		 * @param QWaitIcon|string $strMethodName
		 * @param string           $objWaitIconControl
		 * @param null             $mixCausesValidationOverride
		 * @param string           $strJsReturnParam
		 */
		public function __construct(QControl $objControl, $strMethodName, $objWaitIconControl = 'default', $mixCausesValidationOverride = null, $strJsReturnParam = "") {
			parent::__construct($objControl->ControlId . ':' . $strMethodName, $objWaitIconControl, $mixCausesValidationOverride, $strJsReturnParam);
		}
	}

	/**
	 * Client-side action - no postbacks of any kind are performed.
	 * All handling activity happens in Javascript.
	 *
	 * @package Actions
	 */
	class QJavaScriptAction extends QAction {
		protected $strJavaScript;

		public function __construct($strJavaScript) {
			$this->strJavaScript = trim($strJavaScript);
			if (QString::LastCharacter($this->strJavaScript) == ';')
				$this->strJavaScript = substr($this->strJavaScript, 0, strlen($this->strJavaScript) - 1);
		}

		/**
		 * PHP Magic function to get the property values of a class object
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

		public function RenderScript(QControl $objControl) {
			return sprintf('%s;', $this->strJavaScript);
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QConfirmAction extends QAction {
		protected $strMessage;

		public function __construct($strMessage) {
			$this->strMessage = $strMessage;
		}

		/**
		 * PHP Magic function to get the property values of an object of the class
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

		public function RenderScript(QControl $objControl) {
			$strMessage = JavaScriptHelper::toJsObject($this->strMessage);
			return sprintf("if (!confirm(%s)) return false;", $strMessage);
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QAlertAction extends QAction {
		protected $strMessage;

		public function __construct($strMessage) {
			$this->strMessage = $strMessage;
		}

		/**
		 * PHP Magic function to get the property values of an object of the class
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

		public function RenderScript(QControl $objControl) {
			$strMessage = JavaScriptHelper::toJsObject($this->strMessage);
			return sprintf("alert(%s);", $strMessage);
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QResetTimerAction extends QAction {
		public function RenderScript(QControl $objControl) {
			return sprintf("qcubed.clearTimeout('%s');", $objControl->ControlId);
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QTerminateAction extends QAction {
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
		public function RenderScript(QControl $objControl) {
			return 'event.stopPropagation();';
		}
	}

	/**
	 * Toggle the Disaply of a control
	 * @package Actions
	 */
	class QToggleDisplayAction extends QAction {
		protected $strControlId = null;
		protected $blnDisplay = null;

		public function __construct($objControl, $blnDisplay = null) {
			if (!($objControl instanceof QControl))
				throw new QCallerException('First parameter of constructor is expecting an object of type QControl');

			$this->strControlId = $objControl->ControlId;

			if (!is_null($blnDisplay))
				$this->blnDisplay = QType::Cast($blnDisplay, QType::Boolean);
		}

		public function RenderScript(QControl $objControl) {
			if ($this->blnDisplay === true)
				$strShowOrHide = 'show';
			else if ($this->blnDisplay === false)
				$strShowOrHide = 'hide';
			else
				$strShowOrHide = '';

			return sprintf("qc.getW('%s').toggleDisplay('%s');",
				$this->strControlId, $strShowOrHide);
		}
	}

	/**
	 * Toggle the 'enabled' status of a control
	 * @package Actions
	 */
	class QToggleEnableAction extends QAction {
		protected $strControlId = null;
		protected $blnEnabled = null;

		public function __construct($objControl, $blnEnabled = null) {
			if (!($objControl instanceof QControl))
				throw new QCallerException('First parameter of constructor is expecting an object of type QControl');

			$this->strControlId = $objControl->ControlId;

			if (!is_null($blnEnabled))
				$this->blnEnabled = QType::Cast($blnEnabled, QType::Boolean);
		}

		public function RenderScript(QControl $objControl) {
			if ($this->blnEnabled === true)
				$strEnableOrDisable = 'enable';
			else if ($this->blnEnabled === false)
				$strEnableOrDisable = 'disable';
			else
				$strEnableOrDisable = '';

			return sprintf("qc.getW('%s').toggleEnabled('%s');", $this->strControlId, $strEnableOrDisable);
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QRegisterClickPositionAction extends QAction {
		protected $strControlId = null;

		public function RenderScript(QControl $objControl) {
			return sprintf("qc.getW('%s').registerClickPosition(event);", $objControl->ControlId);
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QShowDialogBox extends QAction {
		protected $strControlId = null;
		protected $strJavaScript = null;

		public function __construct($objControl) {
			if (!($objControl instanceof QDialogBox))
				throw new QCallerException('First parameter of constructor is expecting an object of type QDialogBox');

			$this->strControlId = $objControl->ControlId;
			$this->strJavaScript = $objControl->GetShowDialogJavaScript();
		}

		public function RenderScript(QControl $objControl) {
			return (sprintf('%s; qcubed.recordControlModification("%s", "Display", "1");', $this->strJavaScript, $this->strControlId));
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QHideDialogBox extends QAction {
		protected $strJavaScript = null;

		public function __construct($objControl) {
			if (!($objControl instanceof QDialogBox))
				throw new QCallerException('First parameter of constructor is expecting an object of type QDialogBox');

			$this->strJavaScript = $objControl->GetHideDialogJavaScript();
		}

		public function RenderScript(QControl $objControl) {
			return $this->strJavaScript;
		}
	}

	/**
	 *
	 * @package Actions
	 *
	 * This is the JQuery UI alternative to show dialog
	 */
	class QShowDialog extends QAction {
		protected $strJavaScript = null;

		public function __construct($objControl) {
			if (!($objControl instanceof QDialog))
				throw new QCallerException('First parameter of constructor is expecting an object of type QDialog');

			$strControlId = $objControl->getJqControlId();
			$this->strJavaScript = sprintf ('jQuery("#%s").dialog("open");', $strControlId);
		}

		public function RenderScript(QControl $objControl) {
			return $this->strJavaScript;
		}
	}

	/**
	 * Hiding a JQuery UI Dialog
	 *
	 * @package Actions
	 */
	class QHideDialog extends QAction {
		protected $strJavaScript = null;

		public function __construct($objControl) {
			if (!($objControl instanceof QDialog))
				throw new QCallerException('First parameter of constructor is expecting an object of type QDialog');

			$strControlId = $objControl->getJqControlId();
			$this->strJavaScript = sprintf ('jQuery("#%s").dialog("close");', $strControlId);
		}

		public function RenderScript(QControl $objControl) {
			return $this->strJavaScript;
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QFocusControlAction extends QAction {
		protected $strControlId = null;

		public function __construct($objControl) {
			if (!($objControl instanceof QControl))
				throw new QCallerException('First parameter of constructor is expecting an object of type QControl');

			$this->strControlId = $objControl->ControlId;
		}

		public function RenderScript(QControl $objControl) {
			// for firefox focus is special when in a blur or in a focusout event
			// http://stackoverflow.com/questions/7046798/jquery-focus-fails-on-firefox/7046837#7046837
			return sprintf("setTimeout(function(){qc.getW('%s').focus();},0);", $this->strControlId);
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QBlurControlAction extends QAction {
		protected $strControlId = null;

		public function __construct($objControl) {
			if (!($objControl instanceof QControl))
				throw new QCallerException('First parameter of constructor is expecting an object of type QControl');

			$this->strControlId = $objControl->ControlId;
		}

		public function RenderScript(QControl $objControl) {
			return sprintf("qc.getW('%s').blur();", $this->strControlId);
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QSelectControlAction extends QAction {
		protected $strControlId = null;

		public function __construct($objControl) {
			if (!($objControl instanceof QTextBox ))
				throw new QCallerException('First parameter of constructor is expecting an object of type QTextBox');

			$this->strControlId = $objControl->ControlId;
		}

		public function RenderScript(QControl $objControl) {
			return sprintf("qc.getW('%s').select();", $this->strControlId);
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QCssClassAction extends QAction {
		protected $strTemporaryCssClass = null;
		protected $blnOverride = false;

		public function __construct($strTemporaryCssClass = null, $blnOverride = false) {
			$this->strTemporaryCssClass = $strTemporaryCssClass;
			$this->blnOverride = $blnOverride;
		}

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
	 *
	 * @package Actions
	 */
	class QCssAction extends QAction {
		protected $strCssProperty = null;
		protected $strCssValue = null;
		protected $strControlId = null;

		public function __construct($strCssProperty, $strCssValue, $objControl = null) {
			$this->strCssProperty = $strCssProperty;
			$this->strCssValue = $strCssValue;
			if ($objControl)
				$this->strControlId = $objControl->ControlId;
		}

		public function RenderScript(QControl $objControl) {
			if ($this->strControlId == null)
				$this->strControlId = $objControl->ControlId;
			// Specified a Temporary Css Class to use?
			return sprintf('$j("#%s").css("%s", "%s"); ', $this->strControlId, $this->strCssProperty, $this->strCssValue);
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QShowCalendarAction extends QAction {
		protected $strControlId = null;

		public function __construct($calControl) {
			if (!($calControl instanceof QCalendar))
				throw new QCallerException('First parameter of constructor is expecting an object of type QCalendar');
			$this->strControlId = $calControl->ControlId;
		}

		public function RenderScript(QControl $objControl) {
			return sprintf("qc.getC('%s').showCalendar();", $this->strControlId);
		}
	}

	/**
	 *
	 * @package Actions
	 */
	class QHideCalendarAction extends QAction {
		protected $strControlId = null;

		public function __construct($calControl) {
			if (!($calControl instanceof QCalendar))
				throw new QCallerException('First parameter of constructor is expecting an object of type QCalendar');
			$this->strControlId = $calControl->ControlId;
		}

		public function RenderScript(QControl $objControl) {
			return sprintf("qc.getC('%s').hideCalendar();", $this->strControlId);
		}
	}
?>
