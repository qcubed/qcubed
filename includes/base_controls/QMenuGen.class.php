<?php	
	/**
	 * Triggered when the menu loses focus.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* item Type: jQuery The currently active menu item.
	 * 
	 */
	class QMenu_BlurEvent extends QJqUiEvent {
		const EventName = 'menublur';
	}
	/**
	 * Triggered when the menu is created.
	 * 
	 * 	* event Type: Event 
	 * 	* ui Type: Object 
	 * 
	 * _Note: The ui object is empty but included for consistency with other
	 * events._	 */
	class QMenu_CreateEvent extends QJqUiEvent {
		const EventName = 'menucreate';
	}
	/**
	 * Triggered when a menu gains focus or when any menu item is activated.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* item Type: jQuery The currently active menu item.
	 * 
	 */
	class QMenu_FocusEvent extends QJqUiEvent {
		const EventName = 'menufocus';
	}
	/**
	 * Triggered when a menu item is selected.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* item Type: jQuery The currently active menu item.
	 * 
	 */
	class QMenu_SelectEvent extends QJqUiEvent {
		const EventName = 'menuselect';
	}

	/* Custom "property" event classes for this control */

	/**
	 * Generated QMenuGen class.
	 * 
	 * This is the QMenuGen class which is automatically generated
	 * by scraping the JQuery UI documentation website. As such, it includes all the options
	 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
	 * the QMenuBase class for any glue code to make this class more
	 * usable in QCubed.
	 * 
	 * @see QMenuBase
	 * @package Controls\Base
	 * @property boolean $Disabled
	 * Disables the menu if set to true.
	 *
	 * @property mixed $Icons
	 * Icons to use for submenus, matching an icon provided by the jQuery UI
	 * CSS Framework. 
	 * 
	 * 	* submenu (string, default: "ui-icon-carat-1-e")
	 * 

	 *
	 * @property string $Items
	 * Selector for the elements that serve as the menu items. Note: The
	 * items option should not be changed after initialization. (version
	 * added: 1.11.0)
	 *
	 * @property string $Menus
	 * Selector for the elements that serve as the menu container, including
	 * sub-menus. Note: The menus option should not be changed after
	 * initialization. Existing submenus will not be updated.
	 *
	 * @property mixed $Position
	 * Identifies the position of submenus in relation to the associated
	 * parent menu item. The of option defaults to the parent menu item, but
	 * you can specify another element to position against. You can refer to
	 * the jQuery UI Position utility for more details about the various
	 * options.
	 *
	 * @property string $Role
	 * Customize the ARIA roles used for the menu and menu items. The default
	 * uses "menuitem" for items. Setting the role option to "listbox" will
	 * use "option" for items. If set to null, no roles will be set, which is
	 * useful if the menu is being controlled by another element that is
	 * maintaining focus. Note: The role option should not be changed after
	 * initialization. Existing (sub)menus and menu items will not be
	 * updated.
	 *
	 */

	class QMenuGen extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var mixed */
		protected $mixIcons = null;
		/** @var string */
		protected $strItems = null;
		/** @var string */
		protected $strMenus = null;
		/** @var mixed */
		protected $mixPosition = null;
		/** @var string */
		protected $strRole = null;

		/**
		 * Builds the option array to be sent to the widget constructor.
		 *
		 * @return array key=>value array of options
		 */
		protected function MakeJqOptions() {
			$jqOptions = null;
			if (!is_null($val = $this->Disabled)) {$jqOptions['disabled'] = $val;}
			if (!is_null($val = $this->Icons)) {$jqOptions['icons'] = $val;}
			if (!is_null($val = $this->Items)) {$jqOptions['items'] = $val;}
			if (!is_null($val = $this->Menus)) {$jqOptions['menus'] = $val;}
			if (!is_null($val = $this->Position)) {$jqOptions['position'] = $val;}
			if (!is_null($val = $this->Role)) {$jqOptions['role'] = $val;}
			return $jqOptions;
		}

		/**
		 * Return the JavaScript function to call to associate the widget with the control.
		 *
		 * @return string
		 */
		public function GetJqSetupFunction() {
			return 'menu';
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
		 * Removes focus from a menu, resets any active element styles and
		 * triggers the menus blur event.
		 * 
		 * 	* event Type: Event What triggered the menu to blur.
		 * @param $event
		 */
		public function Blur($event = null) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "blur", $event, QJsPriority::Low);
		}
		/**
		 * Closes the currently active sub-menu.
		 * 
		 * 	* event Type: Event What triggered the menu to collapse.
		 * @param $event
		 */
		public function Collapse($event = null) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "collapse", $event, QJsPriority::Low);
		}
		/**
		 * Closes all open sub-menus.
		 * 
		 * 	* event Type: Event What triggered the menu to collapse.
		 * 	* all Type: Boolean Indicates whether all sub-menus should be closed
		 * or only sub-menus below and including the menu that is or contains the
		 * target of the triggering event.
		 * @param $event
		 * @param $all
		 */
		public function CollapseAll($event = null, $all = null) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "collapseAll", $event, $all, QJsPriority::Low);
		}
		/**
		 * Removes the menu functionality completely. This will return the
		 * element back to its pre-init state.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Destroy() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", QJsPriority::Low);
		}
		/**
		 * Disables the menu.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Disable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", QJsPriority::Low);
		}
		/**
		 * Enables the menu.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Enable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", QJsPriority::Low);
		}
		/**
		 * Opens the sub-menu below the currently active item, if one exists.
		 * 
		 * 	* event Type: Event What triggered the menu to expand.
		 * @param $event
		 */
		public function Expand($event = null) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "expand", $event, QJsPriority::Low);
		}
		/**
		 * Activates a particular menu item, begins opening any sub-menu if
		 * present and triggers the menus focus event.
		 * 
		 * 	* event Type: Event What triggered the menu item to gain focus.
		 * 	* item Type: jQuery The menu item to focus/activate.
		 * @param $item
		 * @param $event
		 */
		public function Focus($event = null, $item) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "focus", $item, $event, QJsPriority::Low);
		}
		/**
		 * Retrieves the menus instance object. If the element does not have an
		 * associated instance, undefined is returned. 
		 * 
		 * Unlike other widget methods, instance() is safe to call on any element
		 * after the menu plugin has loaded.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Instance() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "instance", QJsPriority::Low);
		}
		/**
		 * Returns a boolean value stating whether or not the currently active
		 * item is the first item in the menu.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function IsFirstItem() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "isFirstItem", QJsPriority::Low);
		}
		/**
		 * Returns a boolean value stating whether or not the currently active
		 * item is the last item in the menu.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function IsLastItem() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "isLastItem", QJsPriority::Low);
		}
		/**
		 * Moves active state to next menu item.
		 * 
		 * 	* event Type: Event What triggered the focus to move.
		 * @param $event
		 */
		public function Next($event = null) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "next", $event, QJsPriority::Low);
		}
		/**
		 * Moves active state to first menu item below the bottom of a scrollable
		 * menu or the last item if not scrollable.
		 * 
		 * 	* event Type: Event What triggered the focus to move.
		 * @param $event
		 */
		public function NextPage($event = null) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "nextPage", $event, QJsPriority::Low);
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
		 * menu options hash.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Option1() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", QJsPriority::Low);
		}
		/**
		 * Sets the value of the menu option associated with the specified
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
		 * Sets one or more options for the menu.
		 * 
		 * 	* options Type: Object A map of option-value pairs to set.
		 * @param $options
		 */
		public function Option3($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, QJsPriority::Low);
		}
		/**
		 * Moves active state to previous menu item.
		 * 
		 * 	* event Type: Event What triggered the focus to move.
		 * @param $event
		 */
		public function Previous($event = null) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "previous", $event, QJsPriority::Low);
		}
		/**
		 * Moves active state to first menu item above the top of a scrollable
		 * menu or the first item if not scrollable.
		 * 
		 * 	* event Type: Event What triggered the focus to move.
		 * @param $event
		 */
		public function PreviousPage($event = null) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "previousPage", $event, QJsPriority::Low);
		}
		/**
		 * Initializes sub-menus and menu items that have not already been
		 * initialized. New menu items, including sub-menus can be added to the
		 * menu or all of the contents of the menu can be replaced and then
		 * initialized with the refresh() method.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Refresh() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "refresh", QJsPriority::Low);
		}
		/**
		 * Selects the currently active menu item, collapses all sub-menus and
		 * triggers the menus select event.
		 * 
		 * 	* event Type: Event What triggered the selection.
		 * @param $event
		 */
		public function Select($event = null) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "select", $event, QJsPriority::Low);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'Disabled': return $this->blnDisabled;
				case 'Icons': return $this->mixIcons;
				case 'Items': return $this->strItems;
				case 'Menus': return $this->strMenus;
				case 'Position': return $this->mixPosition;
				case 'Role': return $this->strRole;
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

				case 'Items':
					try {
						$this->strItems = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'items', $this->strItems);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Menus':
					try {
						$this->strMenus = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'menus', $this->strMenus);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Position':
					$this->mixPosition = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'position', $mixValue);
					break;

				case 'Role':
					try {
						$this->strRole = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'role', $this->strRole);
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
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the menu if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Items', 'Selector for the elements that serve as the menu items. Note: Theitems option should not be changed after initialization. (versionadded: 1.11.0)', QType::String),
				new QModelConnectorParam (get_called_class(), 'Menus', 'Selector for the elements that serve as the menu container, includingsub-menus. Note: The menus option should not be changed afterinitialization. Existing submenus will not be updated.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Role', 'Customize the ARIA roles used for the menu and menu items. The defaultuses \"menuitem\" for items. Setting the role option to \"listbox\" willuse \"option\" for items. If set to null, no roles will be set, which isuseful if the menu is being controlled by another element that ismaintaining focus. Note: The role option should not be changed afterinitialization. Existing (sub)menus and menu items will not beupdated.', QType::String),
			));
		}
	}