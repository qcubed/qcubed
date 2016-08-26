<?php	
	/**
	 * Triggered after a panel has been activated (after animation
	 * completes). If the accordion was previously collapsed, ui.oldHeader
	 * and ui.oldPanel will be empty jQuery objects. If the accordion is
	 * collapsing, ui.newHeader and ui.newPanel will be empty jQuery objects.
	 * Note: Since the activate event is only fired on panel activation, it
	 * is not fired for the initial panel when the accordion widget is
	 * created. If you need a hook for widget creation use the create event.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* newHeader Type: jQuery The header that was just activated.
	 * 	* oldHeader Type: jQuery The header that was just deactivated.
	 * 	* newPanel Type: jQuery The panel that was just activated.
	 * 	* oldPanel Type: jQuery The panel that was just deactivated.
	 * 
	 */
	class QAccordion_ActivateEvent extends QJqUiEvent {
		const EventName = 'accordionactivate';
	}
	/**
	 * Triggered directly before a panel is activated. Can be canceled to
	 * prevent the panel from activating. If the accordion is currently
	 * collapsed, ui.oldHeader and ui.oldPanel will be empty jQuery objects.
	 * If the accordion is collapsing, ui.newHeader and ui.newPanel will be
	 * empty jQuery objects.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* newHeader Type: jQuery The header that is about to be activated.
	 * 	* oldHeader Type: jQuery The header that is about to be deactivated.
	 * 	* newPanel Type: jQuery The panel that is about to be activated.
	 * 	* oldPanel Type: jQuery The panel that is about to be deactivated.
	 * 
	 */
	class QAccordion_BeforeActivateEvent extends QJqUiEvent {
		const EventName = 'accordionbeforeactivate';
	}
	/**
	 * Triggered when the accordion is created. If the accordion is
	 * collapsed, ui.header and ui.panel will be empty jQuery objects.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* header Type: jQuery The active header.
	 * 	* panel Type: jQuery The active panel.
	 * 
	 */
	class QAccordion_CreateEvent extends QJqUiEvent {
		const EventName = 'accordioncreate';
	}

	/* Custom "property" event classes for this control */

	/**
	 * Generated QAccordionGen class.
	 * 
	 * This is the QAccordionGen class which is automatically generated
	 * by scraping the JQuery UI documentation website. As such, it includes all the options
	 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
	 * the QAccordionBase class for any glue code to make this class more
	 * usable in QCubed.
	 * 
	 * @see QAccordionBase
	 * @package Controls\Base
	 * @property mixed $Active
	 * Which panel is currently open.Multiple types supported:
	 * 
	 * 	* Boolean: Setting active to false will collapse all panels. This
	 * requires the collapsible option to be true.
	 * 	* Integer: The zero-based index of the panel that is active (open).
	 * A negative value selects panels going backward from the last panel.
	 * 

	 *
	 * @property mixed $Animate
	 * If and how to animate changing panels.Multiple types supported:
	 * 
	 * 	* Boolean: A value of false will disable animations.
	 * 	* Number: Duration in milliseconds with default easing.
	 * 	* String: Name of easing to use with default duration.
	 * 
	 * 	* Object: An object containing easing and duration properties to
	 * configure animations. 
	 * 
	 * 	* Can also contain a down property with any of the above options.
	 * 	* "Down" animations occur when the panel being activated has a lower
	 * index than the currently active panel.
	 * 

	 *
	 * @property boolean $Collapsible
	 * Whether all the sections can be closed at once. Allows collapsing the
	 * active section.
	 *
	 * @property boolean $Disabled
	 * Disables the accordion if set to true.
	 *
	 * @property string $Event
	 * The event that accordion headers will react to in order to activate
	 * the associated panel. Multiple events can be specified, separated by a
	 * space.
	 *
	 * @property mixed $Header
	 * Selector for the header element, applied via .find() on the main
	 * accordion element. Content panels must be the sibling immediately
	 * after their associated headers.
	 *
	 * @property string $HeightStyle
	 * Controls the height of the accordion and each panel. Possible values: 
	 * 
	 * 	* "auto": All panels will be set to the height of the tallest panel.
	 * 	* "fill": Expand to the available height based on the accordions
	 * parent height.
	 * 	* "content": Each panel will be only as tall as its content.
	 * 

	 *
	 * @property mixed $Icons
	 * Icons to use for headers, matching an icon provided by the jQuery UI
	 * CSS Framework. Set to false to have no icons displayed. 
	 * 
	 * 	* header (string, default: "ui-icon-triangle-1-e")
	 * 	* activeHeader (string, default: "ui-icon-triangle-1-s")
	 * 

	 *
	 */

	class QAccordionGen extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var mixed */
		protected $mixActive;
		/** @var mixed */
		protected $mixAnimate = null;
		/** @var boolean */
		protected $blnCollapsible = null;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var string */
		protected $strEvent = null;
		/** @var mixed */
		protected $mixHeader = null;
		/** @var string */
		protected $strHeightStyle = null;
		/** @var mixed */
		protected $mixIcons = null;

		/**
		 * Builds the option array to be sent to the widget constructor.
		 *
		 * @return array key=>value array of options
		 */
		protected function MakeJqOptions() {
			$jqOptions = null;
			if (!is_null($val = $this->Active)) {$jqOptions['active'] = $val;}
			if (!is_null($val = $this->Animate)) {$jqOptions['animate'] = $val;}
			if (!is_null($val = $this->Collapsible)) {$jqOptions['collapsible'] = $val;}
			if (!is_null($val = $this->Disabled)) {$jqOptions['disabled'] = $val;}
			if (!is_null($val = $this->Event)) {$jqOptions['event'] = $val;}
			if (!is_null($val = $this->Header)) {$jqOptions['header'] = $val;}
			if (!is_null($val = $this->HeightStyle)) {$jqOptions['heightStyle'] = $val;}
			if (!is_null($val = $this->Icons)) {$jqOptions['icons'] = $val;}
			return $jqOptions;
		}

		/**
		 * Return the JavaScript function to call to associate the widget with the control.
		 *
		 * @return string
		 */
		public function GetJqSetupFunction() {
			return 'accordion';
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
		 * Removes the accordion functionality completely. This will return the
		 * element back to its pre-init state.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Destroy() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", QJsPriority::Low);
		}
		/**
		 * Disables the accordion.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Disable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", QJsPriority::Low);
		}
		/**
		 * Enables the accordion.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Enable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", QJsPriority::Low);
		}
		/**
		 * Retrieves the accordions instance object. If the element does not have
		 * an associated instance, undefined is returned. 
		 * 
		 * Unlike other widget methods, instance() is safe to call on any element
		 * after the accordion plugin has loaded.
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
		 * accordion options hash.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Option1() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", QJsPriority::Low);
		}
		/**
		 * Sets the value of the accordion option associated with the specified
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
		 * Sets one or more options for the accordion.
		 * 
		 * 	* options Type: Object A map of option-value pairs to set.
		 * @param $options
		 */
		public function Option3($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, QJsPriority::Low);
		}
		/**
		 * Process any headers and panels that were added or removed directly in
		 * the DOM and recompute the height of the accordion panels. Results
		 * depend on the content and the heightStyle option.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Refresh() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "refresh", QJsPriority::Low);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'Active': return $this->mixActive;
				case 'Animate': return $this->mixAnimate;
				case 'Collapsible': return $this->blnCollapsible;
				case 'Disabled': return $this->blnDisabled;
				case 'Event': return $this->strEvent;
				case 'Header': return $this->mixHeader;
				case 'HeightStyle': return $this->strHeightStyle;
				case 'Icons': return $this->mixIcons;
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
				case 'Active':
					$this->mixActive = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'active', $mixValue);
					break;

				case 'Animate':
					$this->mixAnimate = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'animate', $mixValue);
					break;

				case 'Collapsible':
					try {
						$this->blnCollapsible = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'collapsible', $this->blnCollapsible);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Disabled':
					try {
						$this->blnDisabled = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'disabled', $this->blnDisabled);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Event':
					try {
						$this->strEvent = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'event', $this->strEvent);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Header':
					$this->mixHeader = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'header', $mixValue);
					break;

				case 'HeightStyle':
					try {
						$this->strHeightStyle = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'heightStyle', $this->strHeightStyle);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Icons':
					$this->mixIcons = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'icons', $mixValue);
					break;


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
				new QModelConnectorParam (get_called_class(), 'Collapsible', 'Whether all the sections can be closed at once. Allows collapsing theactive section.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the accordion if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Event', 'The event that accordion headers will react to in order to activatethe associated panel. Multiple events can be specified, separated by aspace.', QType::String),
				new QModelConnectorParam (get_called_class(), 'HeightStyle', 'Controls the height of the accordion and each panel. Possible values: 	* \"auto\": All panels will be set to the height of the tallest panel.	* \"fill\": Expand to the available height based on the accordionsparent height.	* \"content\": Each panel will be only as tall as its content.', QType::String),
			));
		}
	}