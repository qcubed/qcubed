<?php	
	/**
	 * Triggered after a tab has been activated (after animation completes).
	 * If the tabs were previously collapsed, ui.oldTab and ui.oldPanel will
	 * be empty jQuery objects. If the tabs are collapsing, ui.newTab and
	 * ui.newPanel will be empty jQuery objects. Note: Since the activate
	 * event is only fired on tab activation, it is not fired for the initial
	 * tab when the tabs widget is created. If you need a hook for widget
	 * creation use the create event.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* newTab Type: jQuery The tab that was just activated.
	 * 	* oldTab Type: jQuery The tab that was just deactivated.
	 * 	* newPanel Type: jQuery The panel that was just activated.
	 * 	* oldPanel Type: jQuery The panel that was just deactivated.
	 * 
	 */
	class QTabs_ActivateEvent extends QJqUiEvent {
		const EventName = 'tabsactivate';
	}
	/**
	 * Triggered immediately before a tab is activated. Can be canceled to
	 * prevent the tab from activating. If the tabs are currently collapsed,
	 * ui.oldTab and ui.oldPanel will be empty jQuery objects. If the tabs
	 * are collapsing, ui.newTab and ui.newPanel will be empty jQuery
	 * objects.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* newTab Type: jQuery The tab that is about to be activated.
	 * 	* oldTab Type: jQuery The tab that is about to be deactivated.
	 * 	* newPanel Type: jQuery The panel that is about to be activated.
	 * 	* oldPanel Type: jQuery The panel that is about to be deactivated.
	 * 
	 */
	class QTabs_BeforeActivateEvent extends QJqUiEvent {
		const EventName = 'tabsbeforeactivate';
	}
	/**
	 * Triggered when a remote tab is about to be loaded, after the
	 * beforeActivate event. Can be canceled to prevent the tab panel from
	 * loading content; though the panel will still be activated. This event
	 * is triggered just before the Ajax request is made, so modifications
	 * can be made to ui.jqXHR and ui.ajaxSettings. 
	 * 
	 * _Note: Although ui.ajaxSettings is provided and can be modified, some
	 * of these properties have already been processed by jQuery. For
	 * example, prefilters have been applied, data has been processed, and
	 * type has been determined. The beforeLoad event occurs at the same
	 * time, and therefore has the same restrictions, as the beforeSend
	 * callback from jQuery.ajax()._
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* tab Type: jQuery The tab that is being loaded.
	 * 	* panel Type: jQuery The panel which will be populated by the Ajax
	 * response.
	 * 	* jqXHR Type: jqXHR The jqXHR object that is requesting the content.
	 * 	* ajaxSettings Type: Object The properties that will be used by
	 * jQuery.ajax to request the content.
	 * 
	 */
	class QTabs_BeforeLoadEvent extends QJqUiEvent {
		const EventName = 'tabsbeforeload';
	}
	/**
	 * Triggered when the tabs are created. If the tabs are collapsed, ui.tab
	 * and ui.panel will be empty jQuery objects.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* tab Type: jQuery The active tab.
	 * 	* panel Type: jQuery The active panel.
	 * 
	 */
	class QTabs_CreateEvent extends QJqUiEvent {
		const EventName = 'tabscreate';
	}
	/**
	 * Triggered after a remote tab has been loaded.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* tab Type: jQuery The tab that was just loaded.
	 * 	* panel Type: jQuery The panel which was just populated by the Ajax
	 * response.
	 * 
	 */
	class QTabs_LoadEvent extends QJqUiEvent {
		const EventName = 'tabsload';
	}

	/* Custom "property" event classes for this control */

	/**
	 * Generated QTabsGen class.
	 * 
	 * This is the QTabsGen class which is automatically generated
	 * by scraping the JQuery UI documentation website. As such, it includes all the options
	 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
	 * the QTabsBase class for any glue code to make this class more
	 * usable in QCubed.
	 * 
	 * @see QTabsBase
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
	 * @property boolean $Collapsible
	 * When set to true, the active panel can be closed.
	 *
	 * @property mixed $Disabled
	 * Which tabs are disabled.Multiple types supported:
	 * 
	 * 	* Boolean: Enable or disable all tabs.
	 * 	* Array: An array containing the zero-based indexes of the tabs that
	 * should be disabled, e.g., [ 0, 2 ] would disable the first and third
	 * tab.
	 * 

	 *
	 * @property string $Event
	 * The type of event that the tabs should react to in order to activate
	 * the tab. To activate on hover, use "mouseover".
	 *
	 * @property string $HeightStyle
	 * Controls the height of the tabs widget and each panel. Possible
	 * values: 
	 * 
	 * 	* "auto": All panels will be set to the height of the tallest panel.
	 * 	* "fill": Expand to the available height based on the tabs parent
	 * height.
	 * 	* "content": Each panel will be only as tall as its content.
	 * 

	 *
	 * @property mixed $Hide
	 * If and how to animate the hiding of the panel.Multiple types
	 * supported:
	 * 
	 * 	* Boolean: When set to false, no animation will be used and the panel
	 * will be hidden immediately. When set to true, the panel will fade out
	 * with the default duration and the default easing.
	 * 	* Number: The panel will fade out with the specified duration and
	 * the default easing.
	 * 	* String: The panel will be hidden using the specified effect. The
	 * value can either be the name of a built-in jQuery animation method,
	 * such as "slideUp", or the name of a jQuery UI effect, such as "fold".
	 * In either case the effect will be used with the default duration and
	 * the default easing.
	 * 	* Object: If the value is an object, then effect, delay, duration,
	 * and easing properties may be provided. If the effect property contains
	 * the name of a jQuery method, then that method will be used; otherwise
	 * it is assumed to be the name of a jQuery UI effect. When using a
	 * jQuery UI effect that supports additional settings, you may include
	 * those settings in the object and they will be passed to the effect. If
	 * duration or easing is omitted, then the default values will be used.
	 * If effect is omitted, then "fadeOut" will be used. If delay is
	 * omitted, then no delay is used.
	 * 

	 *
	 * @property mixed $Show
	 * If and how to animate the showing of the panel.Multiple types
	 * supported:
	 * 
	 * 	* Boolean: When set to false, no animation will be used and the panel
	 * will be shown immediately. When set to true, the panel will fade in
	 * with the default duration and the default easing.
	 * 	* Number: The panel will fade in with the specified duration and the
	 * default easing.
	 * 	* String: The panel will be shown using the specified effect. The
	 * value can either be the name of a built-in jQuery animation method,
	 * such as "slideDown", or the name of a jQuery UI effect, such as
	 * "fold". In either case the effect will be used with the default
	 * duration and the default easing.
	 * 	* Object: If the value is an object, then effect, delay, duration,
	 * and easing properties may be provided. If the effect property contains
	 * the name of a jQuery method, then that method will be used; otherwise
	 * it is assumed to be the name of a jQuery UI effect. When using a
	 * jQuery UI effect that supports additional settings, you may include
	 * those settings in the object and they will be passed to the effect. If
	 * duration or easing is omitted, then the default values will be used.
	 * If effect is omitted, then "fadeIn" will be used. If delay is omitted,
	 * then no delay is used.
	 * 

	 *
	 */

	class QTabsGen extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var mixed */
		protected $mixActive;
		/** @var boolean */
		protected $blnCollapsible = null;
		/** @var mixed */
		protected $mixDisabled = null;
		/** @var string */
		protected $strEvent = null;
		/** @var string */
		protected $strHeightStyle = null;
		/** @var mixed */
		protected $mixHide = null;
		/** @var mixed */
		protected $mixShow = null;

		/**
		 * Builds the option array to be sent to the widget constructor.
		 *
		 * @return array key=>value array of options
		 */
		protected function MakeJqOptions() {
			$jqOptions = null;
			if (!is_null($val = $this->Active)) {$jqOptions['active'] = $val;}
			if (!is_null($val = $this->Collapsible)) {$jqOptions['collapsible'] = $val;}
			if (!is_null($val = $this->Disabled)) {$jqOptions['disabled'] = $val;}
			if (!is_null($val = $this->Event)) {$jqOptions['event'] = $val;}
			if (!is_null($val = $this->HeightStyle)) {$jqOptions['heightStyle'] = $val;}
			if (!is_null($val = $this->Hide)) {$jqOptions['hide'] = $val;}
			if (!is_null($val = $this->Show)) {$jqOptions['show'] = $val;}
			return $jqOptions;
		}

		/**
		 * Return the JavaScript function to call to associate the widget with the control.
		 *
		 * @return string
		 */
		public function GetJqSetupFunction() {
			return 'tabs';
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
		 * Removes the tabs functionality completely. This will return the
		 * element back to its pre-init state.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Destroy() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", QJsPriority::Low);
		}
		/**
		 * Disables all tabs.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Disable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", QJsPriority::Low);
		}
		/**
		 * Disables a tab. The selected tab cannot be disabled. To disable more
		 * than one tab at once, set the disabled option: $( "#tabs" ).tabs(
		 * "option", "disabled", [ 1, 2, 3 ] ).
		 * 
		 * 	* index Type: Number The zero-based index of the tab to disable.
		 * @param $index
		 */
		public function Disable1($index) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", $index, QJsPriority::Low);
		}
		/**
		 * Disables a tab. The selected tab cannot be disabled.
		 * 
		 * 	* href Type: String The href of the tab to disable.
		 * @param $href
		 */
		public function Disable2($href) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", $href, QJsPriority::Low);
		}
		/**
		 * Enables all tabs.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Enable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", QJsPriority::Low);
		}
		/**
		 * Enables a tab. To enable more than one tab at once reset the disabled
		 * property like: $( "#example" ).tabs( "option", "disabled", [] );.
		 * 
		 * 	* index Type: Number The zero-based index of the tab to enable.
		 * @param $index
		 */
		public function Enable1($index) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", $index, QJsPriority::Low);
		}
		/**
		 * Enables a tab.
		 * 
		 * 	* href Type: String The href of the tab to enable.
		 * @param $href
		 */
		public function Enable2($href) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", $href, QJsPriority::Low);
		}
		/**
		 * Retrieves the tabss instance object. If the element does not have an
		 * associated instance, undefined is returned. 
		 * 
		 * Unlike other widget methods, instance() is safe to call on any element
		 * after the tabs plugin has loaded.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Instance() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "instance", QJsPriority::Low);
		}
		/**
		 * Loads the panel content of a remote tab.
		 * 
		 * 	* index Type: Number The zero-based index of the tab to load.
		 * @param $index
		 */
		public function Load($index) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "load", $index, QJsPriority::Low);
		}
		/**
		 * Loads the panel content of a remote tab.
		 * 
		 * 	* href Type: String The href of the tab to load.
		 * @param $href
		 */
		public function Load1($href) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "load", $href, QJsPriority::Low);
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
		 * tabs options hash.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Option1() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", QJsPriority::Low);
		}
		/**
		 * Sets the value of the tabs option associated with the specified
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
		 * Sets one or more options for the tabs.
		 * 
		 * 	* options Type: Object A map of option-value pairs to set.
		 * @param $options
		 */
		public function Option3($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, QJsPriority::Low);
		}
		/**
		 * Process any tabs that were added or removed directly in the DOM and
		 * recompute the height of the tab panels. Results depend on the content
		 * and the heightStyle option.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Refresh() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "refresh", QJsPriority::Low);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'Active': return $this->mixActive;
				case 'Collapsible': return $this->blnCollapsible;
				case 'Disabled': return $this->mixDisabled;
				case 'Event': return $this->strEvent;
				case 'HeightStyle': return $this->strHeightStyle;
				case 'Hide': return $this->mixHide;
				case 'Show': return $this->mixShow;
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
					$this->mixDisabled = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'disabled', $mixValue);
					break;

				case 'Event':
					try {
						$this->strEvent = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'event', $this->strEvent);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'HeightStyle':
					try {
						$this->strHeightStyle = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'heightStyle', $this->strHeightStyle);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Hide':
					$this->mixHide = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'hide', $mixValue);
					break;

				case 'Show':
					$this->mixShow = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'show', $mixValue);
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
				new QModelConnectorParam (get_called_class(), 'Collapsible', 'When set to true, the active panel can be closed.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Event', 'The type of event that the tabs should react to in order to activatethe tab. To activate on hover, use \"mouseover\".', QType::String),
				new QModelConnectorParam (get_called_class(), 'HeightStyle', 'Controls the height of the tabs widget and each panel. Possiblevalues: 	* \"auto\": All panels will be set to the height of the tallest panel.	* \"fill\": Expand to the available height based on the tabs parentheight.	* \"content\": Each panel will be only as tall as its content.', QType::String),
			));
		}
	}