<?php
	/**
	 * @property boolean $Disabled Disables (true) or enables (false) the selectable. Can be set when
	 * 		initialising (first creating) the selectable.
	 * @property boolean $AutoRefresh This determines whether to refresh (recalculate) the position and size of
	 * 		each selectee at the beginning of each select operation. If you have many
	 * 		many items, you may want to set this to false and call the refresh method
	 * 		manually.
	 * @property QJsClosure $Cancel Prevents selecting if you start on elements matching the selector.
	 * @property integer $Delay Time in milliseconds to define when the selecting should start. It helps
	 * 		preventing unwanted selections when clicking on an element.
	 * @property integer $Distance Tolerance, in pixels, for when selecting should start. If specified,
	 * 		selecting will not start until after mouse is dragged beyond distance.
	 * @property QJsClosure $Filter The matching child elements will be made selectees (able to be selected).
	 * @property string $Tolerance Possible values: 'touch', 'fit'.
	 * 
	 * 
	 * fit: draggable overlaps the droppable
	 * 		entirely
	 * touch: draggable overlaps the droppable any amount
	 * @property QJsClosure $OnSelected This event is triggered at the end of the select operation, on each element
	 * 		added to the selection.
	 * @property QJsClosure $OnSelecting This event is triggered during the select operation, on each element added
	 * 		to the selection.
	 * @property QJsClosure $OnStart This event is triggered at the beginning of the select operation.
	 * @property QJsClosure $OnStop This event is triggered at the end of the select operation.
	 * @property QJsClosure $OnUnselected This event is triggered at the end of the select operation, on each element
	 * 		removed from the selection.
	 * @property QJsClosure $OnUnselecting This event is triggered during the select operation, on each element
	 * 		removed from the selection.
	 */

	class QSelectableBase extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var boolean */
		protected $blnAutoRefresh = null;
		/** @var QJsClosure */
		protected $mixCancel = null;
		/** @var integer */
		protected $intDelay;
		/** @var integer */
		protected $intDistance;
		/** @var QJsClosure */
		protected $mixFilter = null;
		/** @var string */
		protected $strTolerance = null;
		/** @var QJsClosure */
		protected $mixOnSelected = null;
		/** @var QJsClosure */
		protected $mixOnSelecting = null;
		/** @var QJsClosure */
		protected $mixOnStart = null;
		/** @var QJsClosure */
		protected $mixOnStop = null;
		/** @var QJsClosure */
		protected $mixOnUnselected = null;
		/** @var QJsClosure */
		protected $mixOnUnselecting = null;

		protected function makeJsProperty($strProp, $strKey, $strQuote = "'") {
			$objValue = $this->$strProp;
			if (null === $objValue) {
				return '';
			}

			return $strKey . ': ' . JavaScriptHelper::toJson($objValue, $strQuote) . ', ';
		}

		protected function makeJqOptions() {
			$strJson = '{';
			$strJson .= $this->makeJsProperty('Disabled', 'disabled');
			$strJson .= $this->makeJsProperty('AutoRefresh', 'autoRefresh');
			$strJson .= $this->makeJsProperty('Cancel', 'cancel');
			$strJson .= $this->makeJsProperty('Delay', 'delay');
			$strJson .= $this->makeJsProperty('Distance', 'distance');
			$strJson .= $this->makeJsProperty('Filter', 'filter');
			$strJson .= $this->makeJsProperty('Tolerance', 'tolerance');
			$strJson .= $this->makeJsProperty('OnSelected', 'selected');
			$strJson .= $this->makeJsProperty('OnSelecting', 'selecting');
			$strJson .= $this->makeJsProperty('OnStart', 'start');
			$strJson .= $this->makeJsProperty('OnStop', 'stop');
			$strJson .= $this->makeJsProperty('OnUnselected', 'unselected');
			$strJson .= $this->makeJsProperty('OnUnselecting', 'unselecting');
			return $strJson.'}';
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'selectable';
		}

		public function GetControlHtml() {
			$strToReturn = parent::GetControlHtml();

			$strJs = sprintf('jQuery("#%s").%s(%s)', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
			QApplication::ExecuteJavaScript($strJs);
			return $strToReturn;
		}

		/**
		 * Remove the selectable functionality completely. This will return the
		 * element back to its pre-init state.
		 */
		public function Destroy() {
			$args = array();
			$args[] = "destroy";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable the selectable.
		 */
		public function Disable() {
			$args = array();
			$args[] = "disable";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable the selectable.
		 */
		public function Enable() {
			$args = array();
			$args[] = "enable";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Get or set any selectable option. If no value is specified, will act as a
		 * getter.
		 * @param $optionName
		 * @param $value
		 */
		public function Option($optionName, $value = null) {
			$args = array();
			$args[] = "option";
			$args[] = $optionName;
			if ($value !== null) {
				$args[] = $value;
			}

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set multiple selectable options at once by providing an options object.
		 * @param $options
		 */
		public function Option1($options) {
			$args = array();
			$args[] = "option";
			$args[] = $options;

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Refresh the position and size of each selectee element. This method can be
		 * used to manually recalculate the position and size of each selectee
		 * element. Very useful if autoRefresh is set to false.
		 */
		public function Refresh() {
			$args = array();
			$args[] = "refresh";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'Disabled': return $this->blnDisabled;
				case 'AutoRefresh': return $this->blnAutoRefresh;
				case 'Cancel': return $this->mixCancel;
				case 'Delay': return $this->intDelay;
				case 'Distance': return $this->intDistance;
				case 'Filter': return $this->mixFilter;
				case 'Tolerance': return $this->strTolerance;
				case 'OnSelected': return $this->mixOnSelected;
				case 'OnSelecting': return $this->mixOnSelecting;
				case 'OnStart': return $this->mixOnStart;
				case 'OnStop': return $this->mixOnStop;
				case 'OnUnselected': return $this->mixOnUnselected;
				case 'OnUnselecting': return $this->mixOnUnselecting;
				default: 
					try { 
						return parent::__get($strName); 
					} catch (QCallerException $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}

		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				case 'Disabled':
					try {
						$this->blnDisabled = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AutoRefresh':
					try {
						$this->blnAutoRefresh = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Cancel':
					try {
						$this->mixCancel = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Delay':
					try {
						$this->intDelay = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Distance':
					try {
						$this->intDistance = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Filter':
					try {
						$this->mixFilter = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tolerance':
					try {
						$this->strTolerance = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnSelected':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnSelected = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnSelecting':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnSelecting = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnStart':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnStart = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnStop':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnStop = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnUnselected':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnUnselected = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnUnselecting':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnUnselecting = QType::Cast($mixValue, 'QJsClosure');
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
