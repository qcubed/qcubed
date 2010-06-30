<?php
	/**
	 * @property boolean $Disabled Disables (true) or enables (false) the autocomplete. Can be set when
	 * 		initialising (first creating) the autocomplete.
	 * @property integer $Delay The delay in milliseconds the Autocomplete waits after a keystroke to
	 * 		activate itself. A zero-delay makes sense for local data (more responsive),
	 * 		but can produce a lot of load for remote data, while being less responsive.
	 * @property integer $MinLength The minimum number of characters a user has to type before the Autocomplete
	 * 		activates. Zero is useful for local data with just a few items. Should be
	 * 		increased when there are a lot of items, where a single character would
	 * 		match a few thousand items.
	 * @property mixed $Source Defines the data to use, must be specified. See Overview section for more
	 * 		details, and look at the various demos.
	 * @property QJsClosure $OnSearch Before a request (source-option) is started, after minLength and delay are
	 * 		met. Can be canceled (return false), then no request will be started and no
	 * 		items suggested.
	 * @property QJsClosure $OnOpen Triggered when the suggestion menu is opened.
	 * @property QJsClosure $OnFocus Before focus is moved to an item (not selecting), ui.item refers to the
	 * 		focused item. The default action of focus is to replace the text field's
	 * 		value with the value of the focused item, though only if the focus event
	 * 		was triggered by a keyboard interaction. Canceling this event prevents the
	 * 		value from being updated, but does not prevent the menu item from being
	 * 		focused.
	 * @property QJsClosure $OnSelect Triggered when an item is selected from the menu; ui.item refers to the
	 * 		selected item. The default action of select is to replace the text field's
	 * 		value with the value of the selected item. Canceling this event prevents
	 * 		the value from being updated, but does not prevent the menu from closing.
	 * @property QJsClosure $OnClose When the list is hidden - doesn't have to occur together with a change.
	 * @property QJsClosure $OnChange After an item was selected; ui.item refers to the selected item. Always
	 * 		triggered after the close event.
	 */

	class QAutocompleteBase extends QTextBox	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var integer */
		protected $intDelay = null;
		/** @var integer */
		protected $intMinLength = null;
		/** @var mixed */
		protected $mixSource;
		/** @var QJsClosure */
		protected $mixOnSearch = null;
		/** @var QJsClosure */
		protected $mixOnOpen = null;
		/** @var QJsClosure */
		protected $mixOnFocus = null;
		/** @var QJsClosure */
		protected $mixOnSelect = null;
		/** @var QJsClosure */
		protected $mixOnClose = null;
		/** @var QJsClosure */
		protected $mixOnChange = null;

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
			$strJson .= $this->makeJsProperty('Delay', 'delay');
			$strJson .= $this->makeJsProperty('MinLength', 'minLength');
			$strJson .= $this->makeJsProperty('Source', 'source');
			$strJson .= $this->makeJsProperty('OnSearch', 'search');
			$strJson .= $this->makeJsProperty('OnOpen', 'open');
			$strJson .= $this->makeJsProperty('OnFocus', 'focus');
			$strJson .= $this->makeJsProperty('OnSelect', 'select');
			$strJson .= $this->makeJsProperty('OnClose', 'close');
			$strJson .= $this->makeJsProperty('OnChange', 'change');
			return $strJson.'}';
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'autocomplete';
		}

		public function GetControlHtml() {
			$strToReturn = parent::GetControlHtml();

			$strJs = sprintf('jQuery("#%s").%s(%s)', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
			QApplication::ExecuteJavaScript($strJs);
			return $strToReturn;
		}

		/**
		 * Remove the autocomplete functionality completely. This will return the
		 * element back to its pre-init state.
		 */
		public function Destroy() {
			$args = array();
			$args[] = "destroy";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").autocomplete(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable the autocomplete.
		 */
		public function Disable() {
			$args = array();
			$args[] = "disable";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").autocomplete(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable the autocomplete.
		 */
		public function Enable() {
			$args = array();
			$args[] = "enable";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").autocomplete(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Get or set any autocomplete option. If no value is specified, will act as a
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
			$strJs = sprintf('jQuery("#%s").autocomplete(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set multiple autocomplete options at once by providing an options object.
		 * @param $options
		 */
		public function Option1($options) {
			$args = array();
			$args[] = "option";
			$args[] = $options;

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").autocomplete(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Triggers a search event, which, when data is available, then will display
		 * the suggestions; can be used by a selectbox-like button to open the
		 * suggestions when clicked. If no value argument is specified, the current
		 * input's value is used. Can be called with an empty string and minLength: 0
		 * to display all items.
		 * @param $value
		 */
		public function Search($value = null) {
			$args = array();
			$args[] = "search";
			if ($value !== null) {
				$args[] = $value;
			}

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").autocomplete(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Close the Autocomplete menu. Useful in combination with the search method,
		 * to close the open menu.
		 */
		public function Close() {
			$args = array();
			$args[] = "close";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").autocomplete(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'Disabled': return $this->blnDisabled;
				case 'Delay': return $this->intDelay;
				case 'MinLength': return $this->intMinLength;
				case 'Source': return $this->mixSource;
				case 'OnSearch': return $this->mixOnSearch;
				case 'OnOpen': return $this->mixOnOpen;
				case 'OnFocus': return $this->mixOnFocus;
				case 'OnSelect': return $this->mixOnSelect;
				case 'OnClose': return $this->mixOnClose;
				case 'OnChange': return $this->mixOnChange;
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

				case 'Delay':
					try {
						$this->intDelay = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MinLength':
					try {
						$this->intMinLength = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Source':
					$this->mixSource = $mixValue;
					break;

				case 'OnSearch':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnSearch = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnOpen':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnOpen = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnFocus':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnFocus = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnSelect':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnSelect = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnClose':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnClose = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnChange':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnChange = QType::Cast($mixValue, 'QJsClosure');
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
