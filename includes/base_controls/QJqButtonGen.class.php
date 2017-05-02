<?php	
	/**
	 * Triggered when the button is created.
	 * 
	 * 	* event Type: Event 
	 * 	* ui Type: Object 
	 * 
	 * _Note: The ui object is empty but included for consistency with other
	 * events._
	 */
	class QJqButton_CreateEvent extends QJqUiEvent {
		const EventName = 'buttoncreate';
	}

	/* Custom "property" event classes for this control */

	/**
	 * Generated QJqButtonGen class.
	 * 
	 * This is the QJqButtonGen class which is automatically generated
	 * by scraping the JQuery UI documentation website. As such, it includes all the options
	 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
	 * the QJqButtonBase class for any glue code to make this class more
	 * usable in QCubed.
	 * 
	 * @see QJqButtonBase
	 * @package Controls\Base
	 * @property mixed $Classes
	 * Specify additional classes to add to the widgets elements. Any of
	 * classes specified in the Theming section can be used as keys to
	 * override their value. To learn more about this option, check out the
	 * learn article about the classes option.

	 *
	 * @property boolean $Disabled
	 * Disables the button if set to true.
	 *
	 * @property string $Icon
	 * Icon to display, with or without text (see showLabel option). By
	 * default, the icon is displayed on the left of the label text. The
	 * positioning can be controlled using the iconPosition option.
	 * 
	 * The value for this option must match an icon class name, e.g.,
	 * "ui-icon-gear".
	 * 
	 * When using an input of type button, submit or reset, icons are not
	 * supported.

	 *
	 * @property string $IconPosition
	 * Where to display the icon: Valid values are "beginning", "end", "top"
	 * and "bottom". In a left-to-right (LTR) display, "beginning" refers to
	 * the left, in a right-to-left (RTL, e.g. in Hebrew or Arabic), it
	 * refers to the right.

	 *
	 * @property string $Label
	 * Text to show in the button. When not specified (null), the elements
	 * HTML content is used, or its value attribute if the element is an
	 * input element of type submit or reset, or the HTML content of the
	 * associated label element if the element is an input of type radio or
	 * checkbox.
	 * 
	 * When using an input of type button, submit or reset, support is
	 * limited to plain text labels.

	 *
	 * @property boolean $ShowLabel
	 * Whether to show the label. When set to false no text will be
	 * displayed, but the icon option must be used, otherwise the showLabel
	 * option will be ignored.
	 *
	 */

	class QJqButtonGen extends QButton	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var mixed */
		protected $mixClasses = null;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var string */
		protected $strIcon = null;
		/** @var string */
		protected $strIconPosition = null;
		/** @var string */
		protected $strLabel = null;
		/** @var boolean */
		protected $blnShowLabel = null;

		/**
		 * Builds the option array to be sent to the widget constructor.
		 *
		 * @return array key=>value array of options
		 */
		protected function MakeJqOptions() {
			$jqOptions = null;
			if (!is_null($val = $this->Classes)) {$jqOptions['classes'] = $val;}
			if (!is_null($val = $this->Disabled)) {$jqOptions['disabled'] = $val;}
			if (!is_null($val = $this->Icon)) {$jqOptions['icon'] = $val;}
			if (!is_null($val = $this->IconPosition)) {$jqOptions['iconPosition'] = $val;}
			if (!is_null($val = $this->Label)) {$jqOptions['label'] = $val;}
			if (!is_null($val = $this->ShowLabel)) {$jqOptions['showLabel'] = $val;}
			return $jqOptions;
		}

		/**
		 * Return the JavaScript function to call to associate the widget with the control.
		 *
		 * @return string
		 */
		public function GetJqSetupFunction() {
			return 'button';
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
		 * Removes the button functionality completely. This will return the
		 * element back to its pre-init state.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Destroy() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", QJsPriority::Low);
		}
		/**
		 * Disables the button.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Disable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", QJsPriority::Low);
		}
		/**
		 * Enables the button.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Enable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", QJsPriority::Low);
		}
		/**
		 * Retrieves the buttons instance object. If the element does not have an
		 * associated instance, undefined is returned.
		 * 
		 * Unlike other widget methods, instance() is safe to call on any element
		 * after the button plugin has loaded.
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
		 * button options hash.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Option1() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", QJsPriority::Low);
		}
		/**
		 * Sets the value of the button option associated with the specified
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
		 * Sets one or more options for the button.
		 * 
		 * 	* options Type: Object A map of option-value pairs to set.
		 * @param $options
		 */
		public function Option3($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, QJsPriority::Low);
		}
		/**
		 * Refreshes the visual state of the button. Useful for updating button
		 * state after the native elements disabled state is changed
		 * programmatically.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Refresh() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "refresh", QJsPriority::Low);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'Classes': return $this->mixClasses;
				case 'Disabled': return $this->blnDisabled;
				case 'Icon': return $this->strIcon;
				case 'IconPosition': return $this->strIconPosition;
				case 'Label': return $this->strLabel;
				case 'ShowLabel': return $this->blnShowLabel;
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
				case 'Classes':
					$this->mixClasses = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'classes', $mixValue);
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

				case 'Icon':
					try {
						$this->strIcon = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'icon', $this->strIcon);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'IconPosition':
					try {
						$this->strIconPosition = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'iconPosition', $this->strIconPosition);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Label':
					try {
						$this->strLabel = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'label', $this->strLabel);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowLabel':
					try {
						$this->blnShowLabel = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'showLabel', $this->blnShowLabel);
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
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the button if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Icon', 'Icon to display, with or without text (see showLabel option). Bydefault, the icon is displayed on the left of the label text. Thepositioning can be controlled using the iconPosition option.The value for this option must match an icon class name, e.g.,\"ui-icon-gear\".When using an input of type button, submit or reset, icons are notsupported.', QType::String),
				new QModelConnectorParam (get_called_class(), 'IconPosition', 'Where to display the icon: Valid values are \"beginning\", \"end\", \"top\"and \"bottom\". In a left-to-right (LTR) display, \"beginning\" refers tothe left, in a right-to-left (RTL, e.g. in Hebrew or Arabic), itrefers to the right.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Label', 'Text to show in the button. When not specified (null), the elementsHTML content is used, or its value attribute if the element is aninput element of type submit or reset, or the HTML content of theassociated label element if the element is an input of type radio orcheckbox.When using an input of type button, submit or reset, support islimited to plain text labels.', QType::String),
				new QModelConnectorParam (get_called_class(), 'ShowLabel', 'Whether to show the label. When set to false no text will bedisplayed, but the icon option must be used, otherwise the showLabeloption will be ignored.', QType::Boolean),
			));
		}
	}