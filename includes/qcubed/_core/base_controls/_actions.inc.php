<?php
/**
 * This file contains all basic action classes: QAction, QServerAction, QAjaxAction, etc. 
 *
 * @package Actions
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

		public static function RenderActions(QControl $objControl, $strEventName, $objActions) {
			$strToReturn = '';

			if ($objActions && count($objActions)) foreach ($objActions as $objAction) {
				if ($objAction->objEvent->EventName != $strEventName)
					throw new Exception('Invalid Action Event in this entry in the ActionArray');

				if ($objAction->objEvent->Delay > 0) {
					$strCode = sprintf(" qcubed.setTimeout('%s', '%s', %s);",
						$objControl->ControlId,
						addslashes($objAction->RenderScript($objControl)),
						$objAction->objEvent->Delay);
				} else {
					$strCode = ' ' . $objAction->RenderScript($objControl);
				}

				// Add Condition (if applicable)
				if (strlen($objAction->objEvent->Condition))
					$strCode = sprintf(' if (%s) {%s}', $objAction->objEvent->Condition, trim($strCode));

				// Append it to the Return Value
				$strToReturn .= $strCode;
			}

			if ($objControl->ActionsMustTerminate) {
				if (QApplication::IsBrowser(QBrowserType::InternetExplorer_6_0))
					$strToReturn .= ' qc.terminateEvent(event);';
				else
					$strToReturn .= ' return false;';
			}

			if (strlen($strToReturn)) {
				if ($objControl instanceof QControlProxy) {
					if ($objControl->TargetControlId) {
						return sprintf('$j("#%s").bind("%s", function(){
									%s
									});
									', $objControl->TargetControlId, $strEventName,  substr($strToReturn, 1));
					}
				} else {
					return sprintf('$j("#%s").bind("%s", function(event){
								%s
								});
								', $objControl->ControlId, $strEventName,  substr($strToReturn, 1));
				
				//return sprintf('%s="%s" ', $strEventName, substr($strToReturn, 1));
				}
			}
			return null;
		}

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

		public function __construct($strMethodName = null, $mixCausesValidationOverride = null) {
			$this->strMethodName = $strMethodName;
			$this->mixCausesValidationOverride = $mixCausesValidationOverride;
		}

		public function __get($strName) {
			switch ($strName) {
				case 'MethodName':
					return $this->strMethodName;
				case 'CausesValidationOverride':
					return $this->mixCausesValidationOverride;
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
			return sprintf("qc.pB('%s', '%s', '%s', '%s');",
				$objControl->Form->FormId, $objControl->ControlId, get_class($this->objEvent), addslashes($objControl->ActionParameter));
		}
	}

	/**
	 * Ajax actions are handled through an asynchronous HTTP request (=AJAX). 
	 * No full-page refresh happens when such an action is executing. 
	 * 
	 * @package Actions
	 */
	class QAjaxAction extends QAction {
		protected $strMethodName;
		protected $objWaitIconControl;
		protected $mixCausesValidationOverride;

		public function __construct($strMethodName = null, $objWaitIconControl = 'default', $mixCausesValidationOverride = null) {
			$this->strMethodName = $strMethodName;
			$this->objWaitIconControl = $objWaitIconControl;
			$this->mixCausesValidationOverride = $mixCausesValidationOverride;
		}

		public function __get($strName) {
			switch ($strName) {
				case 'MethodName':
					return $this->strMethodName;
				case 'WaitIconControl':
					return $this->objWaitIconControl;
				case 'CausesValidationOverride':
					return $this->mixCausesValidationOverride;
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
			$strWaitIconControlId = null;
			if ((gettype($this->objWaitIconControl) == 'string') && ($this->objWaitIconControl == 'default')) {
				if ($objControl->Form->DefaultWaitIcon)
					$strWaitIconControlId = $objControl->Form->DefaultWaitIcon->ControlId;
			} else if ($this->objWaitIconControl) {
				$strWaitIconControlId = $this->objWaitIconControl->ControlId;
			}

			return sprintf("qc.pA('%s', '%s', '%s', '%s', '%s');",
				$objControl->Form->FormId, $objControl->ControlId, get_class($this->objEvent), addslashes($objControl->ActionParameter), $strWaitIconControlId);
		}
	}

	/**
	 * Server control action is identical to server action, except
	 * the handler for it is defined NOT on the form host, but on a control. 
	 * 
	 * @package Actions
	 */
	class QServerControlAction extends QServerAction {
		public function __construct(QControl $objControl, $strMethodName, $mixCausesValidationOverride = null) {
			parent::__construct($objControl->ControlId . ':' . $strMethodName, $mixCausesValidationOverride);
		}
	}

	/**
	 * Ajax control action is identical to Ajax action, except
	 * the handler for it is defined NOT on the form host, but on a control. 
	 * 
	 * @package Actions
	 */
	class QAjaxControlAction extends QAjaxAction {
		public function __construct(QControl $objControl, $strMethodName, $objWaitIconControl = 'default', $mixCausesValidationOverride = null) {
			parent::__construct($objControl->ControlId . ':' . $strMethodName, $objWaitIconControl, $mixCausesValidationOverride);
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
			$strMessage = QApplication::HtmlEntities($this->strMessage);
			$strMessage = str_replace("'", "\\'", $strMessage);
			return sprintf("if (!confirm('%s')) return false;", $strMessage);
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
			if (QApplication::IsBrowser(QBrowserType::InternetExplorer_6_0))
				return sprintf('qcubed.terminateEvent(event);', $objControl->ControlId);
			else
				return sprintf('return false;', $objControl->ControlId);
//			return 'return qc.terminatesEvent(event);';
		}
	}

	/**
	 * 
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
	 * 
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
	 */
	class QFocusControlAction extends QAction {
		protected $strControlId = null;

		public function __construct($objControl) {
			if (!($objControl instanceof QControl))
    			throw new QCallerException('First parameter of constructor is expecting an object of type QControl');

			$this->strControlId = $objControl->ControlId;
		}

		public function RenderScript(QControl $objControl) {
			return sprintf("qc.getW('%s').focus();", $this->strControlId);
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