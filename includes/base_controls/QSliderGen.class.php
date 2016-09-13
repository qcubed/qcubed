<?php	
	/**
	 * Triggered after the user slides a handle, if the value has changed; or
	 * if the value is changed programmatically via the value method.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* handle Type: jQuery The jQuery object representing the handle that
	 * was changed.
	 * 	* value Type: Number The current value of the slider.
	 * 
	 */
	class QSlider_ChangeEvent extends QJqUiEvent {
		const EventName = 'slidechange';
	}
	/**
	 * Triggered when the slider is created.
	 * 
	 * 	* event Type: Event 
	 * 	* ui Type: Object 
	 * 
	 * _Note: The ui object is empty but included for consistency with other
	 * events._	 */
	class QSlider_CreateEvent extends QJqUiEvent {
		const EventName = 'slidecreate';
	}
	/**
	 * Triggered on every mouse move during slide. The value provided in the
	 * event as ui.value represents the value that the handle will have as a
	 * result of the current movement. Canceling the event will prevent the
	 * handle from moving and the handle will continue to have its previous
	 * value.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* handle Type: jQuery The jQuery object representing the handle being
	 * moved.
	 * 	* value Type: Number The value that the handle will move to if the
	 * event is not canceled.
	 * 	* values Type: Array An array of the current values of a
	 * multi-handled slider.
	 * 
	 */
	class QSlider_SlideEvent extends QJqUiEvent {
		const EventName = 'slide';
	}
	/**
	 * Triggered when the user starts sliding.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* handle Type: jQuery The jQuery object representing the handle being
	 * moved.
	 * 	* value Type: Number The current value of the slider.
	 * 
	 */
	class QSlider_StartEvent extends QJqUiEvent {
		const EventName = 'slidestart';
	}
	/**
	 * Triggered after the user slides a handle.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* handle Type: jQuery The jQuery object representing the handle that
	 * was moved.
	 * 	* value Type: Number The current value of the slider.
	 * 
	 */
	class QSlider_StopEvent extends QJqUiEvent {
		const EventName = 'slidestop';
	}

	/* Custom "property" event classes for this control */

	/**
	 * Generated QSliderGen class.
	 * 
	 * This is the QSliderGen class which is automatically generated
	 * by scraping the JQuery UI documentation website. As such, it includes all the options
	 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
	 * the QSliderBase class for any glue code to make this class more
	 * usable in QCubed.
	 * 
	 * @see QSliderBase
	 * @package Controls\Base
	 * @property mixed $Animate
	 * Whether to slide the handle smoothly when the user clicks on the
	 * slider track. Also accepts any valid animation duration.Multiple types
	 * supported:
	 * 
	 * 	* Boolean: When set to true, the handle will animate with the default
	 * duration.
	 * 	* String: The name of a speed, such as "fast" or "slow".
	 * 	* Number: The duration of the animation, in milliseconds.
	 * 

	 *
	 * @property boolean $Disabled
	 * Disables the slider if set to true.
	 *
	 * @property integer $Max
	 * The maximum value of the slider.
	 *
	 * @property integer $Min
	 * The minimum value of the slider.
	 *
	 * @property string $Orientation
	 * Determines whether the slider handles move horizontally (min on left,
	 * max on right) or vertically (min on bottom, max on top). Possible
	 * values: "horizontal", "vertical".
	 *
	 * @property mixed $Range
	 * Whether the slider represents a range.Multiple types supported:
	 * 
	 * 	* Boolean: If set to true, the slider will detect if you have two
	 * handles and create a styleable range element between these two.
	 * 	* String: Either "min" or "max". A min range goes from the slider
	 * min to one handle. A max range goes from one handle to the slider max.
	 * 

	 *
	 * @property integer $Step
	 * Determines the size or amount of each interval or step the slider
	 * takes between the min and max. The full specified value range of the
	 * slider (max - min) should be evenly divisible by the step.
	 *
	 * @property integer $Value
	 * Determines the value of the slider, if theres only one handle. If
	 * there is more than one handle, determines the value of the first
	 * handle.
	 *
	 * @property array $Values
	 * This option can be used to specify multiple handles. If the range
	 * option is set to true, the length of values should be 2.
	 *
	 */

	class QSliderGen extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var mixed */
		protected $mixAnimate = null;
		/** @var boolean */
		protected $blnDisabled = null;
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

		/**
		 * Builds the option array to be sent to the widget constructor.
		 *
		 * @return array key=>value array of options
		 */
		protected function MakeJqOptions() {
			$jqOptions = null;
			if (!is_null($val = $this->Animate)) {$jqOptions['animate'] = $val;}
			if (!is_null($val = $this->Disabled)) {$jqOptions['disabled'] = $val;}
			if (!is_null($val = $this->Max)) {$jqOptions['max'] = $val;}
			if (!is_null($val = $this->Min)) {$jqOptions['min'] = $val;}
			if (!is_null($val = $this->Orientation)) {$jqOptions['orientation'] = $val;}
			if (!is_null($val = $this->Range)) {$jqOptions['range'] = $val;}
			if (!is_null($val = $this->Step)) {$jqOptions['step'] = $val;}
			if (!is_null($val = $this->Value)) {$jqOptions['value'] = $val;}
			if (!is_null($val = $this->Values)) {$jqOptions['values'] = $val;}
			return $jqOptions;
		}

		/**
		 * Return the JavaScript function to call to associate the widget with the control.
		 *
		 * @return string
		 */
		public function GetJqSetupFunction() {
			return 'slider';
		}

		/**
		 * Returns the script that attaches the JQueryUI widget to the html object.
		 *
		 * @return string
		 */
		public function GetEndScript() {
			$strId = $this->GetJqControlId();
			$jqOptions = $this->makeJqOptions();
			$strFunc = $this->getJqSetupFunction();

			if ($strId !== $this->ControlId && QApplication::$RequestMode == QRequestMode::Ajax) {
				// If events are not attached to the actual object being drawn, then the old events will not get
				// deleted during redraw. We delete the old events here. This must happen before any other event processing code.
				QApplication::ExecuteControlCommand($strId, 'off', QJsPriority::High);
			}

			// Attach the javascript widget to the html object
			if (empty($jqOptions)) {
				QApplication::ExecuteControlCommand($strId, $strFunc, QJsPriority::High);
			} else {
				QApplication::ExecuteControlCommand($strId, $strFunc, $jqOptions, QJsPriority::High);
			}

			return parent::GetEndScript();
		}

		/**
		 * Removes the slider functionality completely. This will return the
		 * element back to its pre-init state.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Destroy() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", QJsPriority::Low);
		}
		/**
		 * Disables the slider.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Disable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", QJsPriority::Low);
		}
		/**
		 * Enables the slider.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Enable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", QJsPriority::Low);
		}
		/**
		 * Retrieves the sliders instance object. If the element does not have an
		 * associated instance, undefined is returned. 
		 * 
		 * Unlike other widget methods, instance() is safe to call on any element
		 * after the slider plugin has loaded.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Instance() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "instance", QJsPriority::Low);
		}
		/**
		 * Gets the value currently associated with the specified optionName. 
		 * 
		 * Note: For options that have objects as their value, you can get the
		 * value of a specific key by using dot notation. For example, "foo.bar"
		 * would get the value of the bar property on the foo option.
		 * 
		 * 	* optionName Type: String The name of the option to get.
		 * @param $optionName
		 */
		public function Option($optionName) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $optionName, QJsPriority::Low);
		}
		/**
		 * Gets an object containing key/value pairs representing the current
		 * slider options hash.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Option1() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", QJsPriority::Low);
		}
		/**
		 * Sets the value of the slider option associated with the specified
		 * optionName. 
		 * 
		 * Note: For options that have objects as their value, you can set the
		 * value of just one property by using dot notation for optionName. For
		 * example, "foo.bar" would update only the bar property of the foo
		 * option.
		 * 
		 * 	* optionName Type: String The name of the option to set.
		 * 	* value Type: Object A value to set for the option.
		 * @param $optionName
		 * @param $value
		 */
		public function Option2($optionName, $value) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $optionName, $value, QJsPriority::Low);
		}
		/**
		 * Sets one or more options for the slider.
		 * 
		 * 	* options Type: Object A map of option-value pairs to set.
		 * @param $options
		 */
		public function Option3($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, QJsPriority::Low);
		}
		/**
		 * Get the value of the slider.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Value() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "value", QJsPriority::Low);
		}
		/**
		 * Set the value of the slider.
		 * 
		 * 	* value Type: Number The value to set.
		 * @param $value
		 */
		public function Value1($value) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "value", $value, QJsPriority::Low);
		}
		/**
		 * Get the value for all handles.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Values() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "values", QJsPriority::Low);
		}
		/**
		 * Get the value for the specified handle.
		 * 
		 * 	* index Type: Integer The zero-based index of the handle.
		 * @param $index
		 */
		public function Values1($index) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "values", $index, QJsPriority::Low);
		}
		/**
		 * Set the value for the specified handle.
		 * 
		 * 	* index Type: Integer The zero-based index of the handle.
		 * 	* value Type: Number The value to set.
		 * @param $index
		 * @param $value
		 */
		public function Values2($index, $value) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "values", $index, $value, QJsPriority::Low);
		}
		/**
		 * Set the value for all handles.
		 * 
		 * 	* values Type: Array The values to set.
		 * @param $values
		 */
		public function Values3($values) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "values", $values, QJsPriority::Low);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'Animate': return $this->mixAnimate;
				case 'Disabled': return $this->blnDisabled;
				case 'Max': return $this->intMax;
				case 'Min': return $this->intMin;
				case 'Orientation': return $this->strOrientation;
				case 'Range': return $this->mixRange;
				case 'Step': return $this->intStep;
				case 'Value': return $this->intValue;
				case 'Values': return $this->arrValues;
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
			switch ($strName) {
				case 'Animate':
					$this->mixAnimate = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'animate', $mixValue);
					break;

				case 'Disabled':
					try {
						$this->blnDisabled = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'disabled', $this->blnDisabled);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Max':
					try {
						$this->intMax = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'max', $this->intMax);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Min':
					try {
						$this->intMin = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'min', $this->intMin);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Orientation':
					try {
						$this->strOrientation = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'orientation', $this->strOrientation);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Range':
					$this->mixRange = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'range', $mixValue);
					break;

				case 'Step':
					try {
						$this->intStep = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'step', $this->intStep);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Value':
					try {
						$this->intValue = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'value', $this->intValue);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Values':
					try {
						$this->arrValues = QType::Cast($mixValue, QType::ArrayType);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'values', $this->arrValues);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				case 'Enabled':
					$this->Disabled = !$mixValue;	// Tie in standard QCubed functionality
					parent::__set($strName, $mixValue);
					break;
					
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
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public static function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the slider if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Max', 'The maximum value of the slider.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Min', 'The minimum value of the slider.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Orientation', 'Determines whether the slider handles move horizontally (min on left,max on right) or vertically (min on bottom, max on top). Possiblevalues: \"horizontal\", \"vertical\".', QType::String),
				new QModelConnectorParam (get_called_class(), 'Step', 'Determines the size or amount of each interval or step the slidertakes between the min and max. The full specified value range of theslider (max - min) should be evenly divisible by the step.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Value', 'Determines the value of the slider, if theres only one handle. Ifthere is more than one handle, determines the value of the firsthandle.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Values', 'This option can be used to specify multiple handles. If the rangeoption is set to true, the length of values should be 2.', QType::ArrayType),
			));
		}
	}