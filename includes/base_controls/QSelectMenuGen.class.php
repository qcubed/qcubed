<?php	
	/**
	 * Triggered when the selected item has changed. Not every select event
	 * will fire a change event.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* item Type: jQuery The active item.
	 * 
	 */
	class QSelectMenu_ChangeEvent extends QJqUiEvent {
		const EventName = 'selectmenuchange';
	}
	/**
	 * Triggered when the menu is hidden.
	 * 
	 * 	* event Type: Event 
	 * 
	 * _Note: The ui object is empty but included for consistency with other
	 * events._	 */
	class QSelectMenu_CloseEvent extends QJqUiEvent {
		const EventName = 'selectmenuclose';
	}
	/**
	 * Triggered when the selectmenu is created.
	 * 
	 * 	* event Type: Event 
	 * 	* ui Type: Object 
	 * 
	 * _Note: The ui object is empty but included for consistency with other
	 * events._	 */
	class QSelectMenu_CreateEvent extends QJqUiEvent {
		const EventName = 'selectmenucreate';
	}
	/**
	 * Triggered when an items gains focus.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* item Type: jQuery The focused item.
	 * 
	 */
	class QSelectMenu_FocusEvent extends QJqUiEvent {
		const EventName = 'selectmenufocus';
	}
	/**
	 * Triggered when the menu is opened.
	 * 
	 * 	* event Type: Event 
	 * 
	 * _Note: The ui object is empty but included for consistency with other
	 * events._	 */
	class QSelectMenu_OpenEvent extends QJqUiEvent {
		const EventName = 'selectmenuopen';
	}
	/**
	 * Triggered when a menu item is selected.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* item Type: jQuery The selected item.
	 * 
	 */
	class QSelectMenu_SelectEvent extends QJqUiEvent {
		const EventName = 'selectmenuselect';
	}

	/* Custom "property" event classes for this control */

	/**
	 * Generated QSelectMenuGen class.
	 * 
	 * This is the QSelectMenuGen class which is automatically generated
	 * by scraping the JQuery UI documentation website. As such, it includes all the options
	 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
	 * the QSelectMenuBase class for any glue code to make this class more
	 * usable in QCubed.
	 * 
	 * @see QSelectMenuBase
	 * @package Controls\Base
	 * @property mixed $AppendTo
	 * Which element to append the menu to. When the value is null, the
	 * parents of the <select> are checked for a class name of ui-front. If
	 * an element with the ui-front class name is found, the menu is appended
	 * to that element. Regardless of the value, if no element is found, the
	 * menu is appended to the body.
	 *
	 * @property boolean $Disabled
	 * Disables the selectmenu if set to true.
	 *
	 * @property mixed $Icons
	 * Icons to use for the button, matching an icon defined by the jQuery UI
	 * CSS Framework. 
	 * 
	 * 	* button (string, default: "ui-icon-triangle-1-s")
	 * 

	 *
	 * @property mixed $Position
	 * Identifies the position of the menu in relation to the associated
	 * button element. You can refer to the jQuery UI Position utility for
	 * more details about the various options.
	 *
	 * @property integer $Width
	 * The width of the menu, in pixels. When the value is null, the width of
	 * the native select is used.
	 *
	 */

	class QSelectMenuGen extends QListBox	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var mixed */
		protected $mixAppendTo = null;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var mixed */
		protected $mixIcons = null;
		/** @var mixed */
		protected $mixPosition = null;
		/** @var integer */
		protected $intWidth = null;

		/**
		 * Builds the option array to be sent to the widget constructor.
		 *
		 * @return array key=>value array of options
		 */
		protected function MakeJqOptions() {
			$jqOptions = null;
			if (!is_null($val = $this->AppendTo)) {$jqOptions['appendTo'] = $val;}
			if (!is_null($val = $this->Disabled)) {$jqOptions['disabled'] = $val;}
			if (!is_null($val = $this->Icons)) {$jqOptions['icons'] = $val;}
			if (!is_null($val = $this->Position)) {$jqOptions['position'] = $val;}
			if (!is_null($val = $this->Width)) {$jqOptions['width'] = $val;}
			return $jqOptions;
		}

		/**
		 * Return the JavaScript function to call to associate the widget with the control.
		 *
		 * @return string
		 */
		public function GetJqSetupFunction() {
			return 'selectmenu';
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
		 * Closes the menu.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Close() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "close", QJsPriority::Low);
		}
		/**
		 * Removes the selectmenu functionality completely. This will return the
		 * element back to its pre-init state.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Destroy() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", QJsPriority::Low);
		}
		/**
		 * Disables the selectmenu.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Disable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", QJsPriority::Low);
		}
		/**
		 * Enables the selectmenu.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Enable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", QJsPriority::Low);
		}
		/**
		 * Retrieves the selectmenus instance object. If the element does not
		 * have an associated instance, undefined is returned. 
		 * 
		 * Unlike other widget methods, instance() is safe to call on any element
		 * after the selectmenu plugin has loaded.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Instance() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "instance", QJsPriority::Low);
		}
		/**
		 * Returns a jQuery object containing the menu element.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function MenuWidget() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "menuWidget", QJsPriority::Low);
		}
		/**
		 * Opens the menu.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Open() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "open", QJsPriority::Low);
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
		 * selectmenu options hash.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Option1() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", QJsPriority::Low);
		}
		/**
		 * Sets the value of the selectmenu option associated with the specified
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
		 * Sets one or more options for the selectmenu.
		 * 
		 * 	* options Type: Object A map of option-value pairs to set.
		 * @param $options
		 */
		public function Option3($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, QJsPriority::Low);
		}
		/**
		 * Parses the original element and re-renders the menu. Processes any
		 * <option> or <optgroup> elements that were added, removed or disabled.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Refresh() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "refresh", QJsPriority::Low);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'AppendTo': return $this->mixAppendTo;
				case 'Disabled': return $this->blnDisabled;
				case 'Icons': return $this->mixIcons;
				case 'Position': return $this->mixPosition;
				case 'Width': return $this->intWidth;
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
				case 'AppendTo':
					$this->mixAppendTo = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'appendTo', $mixValue);
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

				case 'Icons':
					$this->mixIcons = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'icons', $mixValue);
					break;

				case 'Position':
					$this->mixPosition = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'position', $mixValue);
					break;

				case 'Width':
					try {
						$this->intWidth = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'width', $this->intWidth);
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
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the selectmenu if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Width', 'The width of the menu, in pixels. When the value is null, the width ofthe native select is used.', QType::Integer),
			));
		}
	}