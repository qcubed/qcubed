<?php
	/**
	 * @property boolean $Disabled Disables (true) or enables (false) the slider. Can be set when initialising
	 * 		(first creating) the slider.
	 * @property mixed $Animate Whether to slide handle smoothly when user click outside handle on the bar.
	 * 		Will also accept a string representing one of the three predefined speeds
	 * 		("slow", "normal", or "fast") or the number of milliseconds to run the
	 * 		animation (e.g. 1000).
	 * @property integer $Max The maximum value of the slider.
	 * @property integer $Min The minimum value of the slider.
	 * @property string $Orientation This option determines whether the slider has the min at the left, the max
	 * 		at the right or the min at the bottom, the max at the top. Possible values:
	 * 		'horizontal', 'vertical'.
	 * @property mixed $Range If set to true, the slider will detect if you have two handles and create a
	 * 		stylable range element between these two. Two other possible values are
	 * 		'min' and 'max'. A min range goes from the slider min to one handle. A max
	 * 		range goes from one handle to the slider max.
	 * @property integer $Step Determines the size or amount of each interval or step the slider takes
	 * 		between min and max. The full specified value range of the slider (max -
	 * 		min) needs to be evenly divisible by the step.
	 * @property integer $Value Determines the value of the slider, if there's only one handle. If there is
	 * 		more than one handle, determines the value of the first handle.
	 * @property array $Values This option can be used to specify multiple handles. If range is set to
	 * 		true, the length of 'values' should be 2.
	 * @property QJsClosure $OnStart This event is triggered when the user starts sliding.
	 * @property QJsClosure $OnSlide This event is triggered on every mouse move during slide. Use ui.value
	 * 		(single-handled sliders) to obtain the value of the current handle,
	 * 		$(..).slider('value', index) to get another handles' value.
	 * Return false in
	 * 		order to prevent a slide, based on ui.value.
	 * @property QJsClosure $OnChange This event is triggered on slide stop, or if the value is changed
	 * 		programmatically (by the value method).  Takes arguments event and ui.  Use
	 * 		event.orginalEvent to detect whether the value changed by mouse, keyboard,
	 * 		or programmatically. Use ui.value (single-handled sliders) to obtain the
	 * 		value of the current handle, $(this).slider('values', index) to get another
	 * 		handle's value.
	 * @property QJsClosure $OnStop This event is triggered when the user stops sliding.
	 */

	class QSliderBase extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var mixed */
		protected $mixAnimate = null;
		/** @var integer */
		protected $intMax = null;
		/** @var integer */
		protected $intMin;
		/** @var string */
		protected $strOrientation = null;
		/** @var mixed */
		protected $mixRange = null;
		/** @var integer */
		protected $intStep = null;
		/** @var integer */
		protected $intValue;
		/** @var array */
		protected $arrValues = null;
		/** @var QJsClosure */
		protected $mixOnStart = null;
		/** @var QJsClosure */
		protected $mixOnSlide = null;
		/** @var QJsClosure */
		protected $mixOnChange = null;
		/** @var QJsClosure */
		protected $mixOnStop = null;

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
			$strJson .= $this->makeJsProperty('Animate', 'animate');
			$strJson .= $this->makeJsProperty('Max', 'max');
			$strJson .= $this->makeJsProperty('Min', 'min');
			$strJson .= $this->makeJsProperty('Orientation', 'orientation');
			$strJson .= $this->makeJsProperty('Range', 'range');
			$strJson .= $this->makeJsProperty('Step', 'step');
			$strJson .= $this->makeJsProperty('Value', 'value');
			$strJson .= $this->makeJsProperty('Values', 'values');
			$strJson .= $this->makeJsProperty('OnStart', 'start');
			$strJson .= $this->makeJsProperty('OnSlide', 'slide');
			$strJson .= $this->makeJsProperty('OnChange', 'change');
			$strJson .= $this->makeJsProperty('OnStop', 'stop');
			return $strJson.'}';
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'slider';
		}

		public function GetControlHtml() {
			$strToReturn = parent::GetControlHtml();

			$strJs = sprintf('jQuery("#%s").%s(%s)', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
			QApplication::ExecuteJavaScript($strJs);
			return $strToReturn;
		}

		/**
		 * Remove the slider functionality completely. This will return the element
		 * back to its pre-init state.
		 */
		public function Destroy() {
			$args = array();
			$args[] = "destroy";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").slider(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable the slider.
		 */
		public function Disable() {
			$args = array();
			$args[] = "disable";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").slider(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable the slider.
		 */
		public function Enable() {
			$args = array();
			$args[] = "enable";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").slider(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Get or set any slider option. If no value is specified, will act as a
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
			$strJs = sprintf('jQuery("#%s").slider(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set multiple slider options at once by providing an options object.
		 * @param $options
		 */
		public function Option1($options) {
			$args = array();
			$args[] = "option";
			$args[] = $options;

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").slider(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Gets or sets the value of the slider. For single handle sliders.
		 * @param $value
		 */
		public function Value($value = null) {
			$args = array();
			$args[] = "value";
			if ($value !== null) {
				$args[] = $value;
			}

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").slider(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Gets or sets the values of the slider. For multiple handle or range
		 * sliders.
		 * @param $index
		 * @param $value
		 */
		public function Values($index, $value = null) {
			$args = array();
			$args[] = "values";
			$args[] = $index;
			if ($value !== null) {
				$args[] = $value;
			}

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").slider(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'Disabled': return $this->blnDisabled;
				case 'Animate': return $this->mixAnimate;
				case 'Max': return $this->intMax;
				case 'Min': return $this->intMin;
				case 'Orientation': return $this->strOrientation;
				case 'Range': return $this->mixRange;
				case 'Step': return $this->intStep;
				case 'Value': return $this->intValue;
				case 'Values': return $this->arrValues;
				case 'OnStart': return $this->mixOnStart;
				case 'OnSlide': return $this->mixOnSlide;
				case 'OnChange': return $this->mixOnChange;
				case 'OnStop': return $this->mixOnStop;
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

				case 'Animate':
					$this->mixAnimate = $mixValue;
					break;

				case 'Max':
					try {
						$this->intMax = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Min':
					try {
						$this->intMin = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Orientation':
					try {
						$this->strOrientation = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Range':
					$this->mixRange = $mixValue;
					break;

				case 'Step':
					try {
						$this->intStep = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Value':
					try {
						$this->intValue = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Values':
					try {
						$this->arrValues = QType::Cast($mixValue, QType::ArrayType);
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

				case 'OnSlide':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnSlide = QType::Cast($mixValue, 'QJsClosure');
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
