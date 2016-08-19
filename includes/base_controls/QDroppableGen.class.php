<?php	
	/**
	 * Triggered when an accepted draggable starts dragging. This can be
	 * useful if you want to make the droppable "light up" when it can be
	 * dropped on.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* draggable Type: jQuery A jQuery object representing the draggable
	 * element.
	 * 	* helper Type: jQuery A jQuery object representing the helper that
	 * is being dragged.
	 * 	* position Type: Object Current CSS position of the draggable helper
	 * as { top, left } object.
	 * 	* offset Type: Object Current offset position of the draggable
	 * helper as { top, left } object.
	 * 
	 */
	class QDroppable_ActivateEvent extends QJqUiEvent {
		const EventName = 'dropactivate';
	}
	/**
	 * Triggered when the droppable is created.
	 * 
	 * 	* event Type: Event 
	 * 	* ui Type: Object 
	 * 
	 * _Note: The ui object is empty but included for consistency with other
	 * events._	 */
	class QDroppable_CreateEvent extends QJqUiEvent {
		const EventName = 'dropcreate';
	}
	/**
	 * Triggered when an accepted draggable stops dragging.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* draggable Type: jQuery A jQuery object representing the draggable
	 * element.
	 * 	* helper Type: jQuery A jQuery object representing the helper that
	 * is being dragged.
	 * 	* position Type: Object Current CSS position of the draggable helper
	 * as { top, left } object.
	 * 	* offset Type: Object Current offset position of the draggable
	 * helper as { top, left } object.
	 * 
	 */
	class QDroppable_DeactivateEvent extends QJqUiEvent {
		const EventName = 'dropdeactivate';
	}
	/**
	 * Triggered when an accepted draggable is dropped on the droppable
	 * (based on thetolerance option).
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* draggable Type: jQuery A jQuery object representing the draggable
	 * element.
	 * 	* helper Type: jQuery A jQuery object representing the helper that
	 * is being dragged.
	 * 	* position Type: Object Current CSS position of the draggable helper
	 * as { top, left } object.
	 * 	* offset Type: Object Current offset position of the draggable
	 * helper as { top, left } object.
	 * 
	 */
	class QDroppable_DropEvent extends QJqUiEvent {
		const EventName = 'drop';
	}
	/**
	 * Triggered when an accepted draggable is dragged out of the droppable
	 * (based on thetolerance option).
	 * 
	 * 	* event Type: Event 
	 * 	* ui Type: Object 
	 * 
	 * _Note: The ui object is empty but included for consistency with other
	 * events._	 */
	class QDroppable_OutEvent extends QJqUiEvent {
		const EventName = 'dropout';
	}
	/**
	 * Triggered when an accepted draggable is dragged over the droppable
	 * (based on thetolerance option).
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* draggable Type: jQuery A jQuery object representing the draggable
	 * element.
	 * 	* helper Type: jQuery A jQuery object representing the helper that
	 * is being dragged.
	 * 	* position Type: Object Current CSS position of the draggable helper
	 * as { top, left } object.
	 * 	* offset Type: Object Current offset position of the draggable
	 * helper as { top, left } object.
	 * 
	 */
	class QDroppable_OverEvent extends QJqUiEvent {
		const EventName = 'dropover';
	}

	/* Custom "property" event classes for this control */

	/**
	 * Generated QDroppableGen class.
	 * 
	 * This is the QDroppableGen class which is automatically generated
	 * by scraping the JQuery UI documentation website. As such, it includes all the options
	 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
	 * the QDroppableBase class for any glue code to make this class more
	 * usable in QCubed.
	 * 
	 * @see QDroppableBase
	 * @package Controls\Base
	 * @property mixed $Accept
	 * Controls which draggable elements are accepted by the
	 * droppable.Multiple types supported:
	 * 
	 * 	* Selector: A selector indicating which draggable elements are
	 * accepted.
	 * 	* Function: A function that will be called for each draggable on the
	 * page (passed as the first argument to the function). The function must
	 * return true if the draggable should be accepted.
	 * 

	 *
	 * @property string $ActiveClass
	 * If specified, the class will be added to the droppable while an
	 * acceptable draggable is being dragged.
	 *
	 * @property boolean $AddClasses
	 * If set to false, will prevent the ui-droppable class from being added.
	 * This may be desired as a performance optimization when calling
	 * .droppable() init on hundreds of elements.
	 *
	 * @property boolean $Disabled
	 * Disables the droppable if set to true.
	 *
	 * @property boolean $Greedy
	 * By default, when an element is dropped on nested droppables, each
	 * droppable will receive the element. However, by setting this option to
	 * true, any parent droppables will not receive the element. The drop
	 * event will still bubble normally, but the event.target can be checked
	 * to see which droppable received the draggable element.
	 *
	 * @property string $HoverClass
	 * If specified, the class will be added to the droppable while an
	 * acceptable draggable is being hovered over the droppable.
	 *
	 * @property string $Scope
	 * Used to group sets of draggable and droppable items, in addition to
	 * the accept option. A draggable with the same scope value as a
	 * droppable will be accepted.
	 *
	 * @property string $Tolerance
	 * Specifies which mode to use for testing whether a draggable is
	 * hovering over a droppable. Possible values: 
	 * 
	 * 	* "fit": Draggable overlaps the droppable entirely.
	 * 	* "intersect": Draggable overlaps the droppable at least 50% in both
	 * directions.
	 * 	* "pointer": Mouse pointer overlaps the droppable.
	 * 	* "touch": Draggable overlaps the droppable any amount.
	 * 

	 *
	 */

	abstract class QDroppableGen extends QControl	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var mixed */
		protected $mixAccept = null;
		/** @var string */
		protected $strActiveClass = null;
		/** @var boolean */
		protected $blnAddClasses = null;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var boolean */
		protected $blnGreedy = null;
		/** @var string */
		protected $strHoverClass = null;
		/** @var string */
		protected $strScope = null;
		/** @var string */
		protected $strTolerance = null;

		/**
		 * Builds the option array to be sent to the widget constructor.
		 *
		 * @return array key=>value array of options
		 */
		protected function MakeJqOptions() {
			$jqOptions = null;
			if (!is_null($val = $this->Accept)) {$jqOptions['accept'] = $val;}
			if (!is_null($val = $this->ActiveClass)) {$jqOptions['activeClass'] = $val;}
			if (!is_null($val = $this->AddClasses)) {$jqOptions['addClasses'] = $val;}
			if (!is_null($val = $this->Disabled)) {$jqOptions['disabled'] = $val;}
			if (!is_null($val = $this->Greedy)) {$jqOptions['greedy'] = $val;}
			if (!is_null($val = $this->HoverClass)) {$jqOptions['hoverClass'] = $val;}
			if (!is_null($val = $this->Scope)) {$jqOptions['scope'] = $val;}
			if (!is_null($val = $this->Tolerance)) {$jqOptions['tolerance'] = $val;}
			return $jqOptions;
		}

		/**
		 * Return the JavaScript function to call to associate the widget with the control.
		 *
		 * @return string
		 */
		public function GetJqSetupFunction() {
			return 'droppable';
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
		 * Removes the droppable functionality completely. This will return the
		 * element back to its pre-init state.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Destroy() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", QJsPriority::Low);
		}
		/**
		 * Disables the droppable.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Disable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", QJsPriority::Low);
		}
		/**
		 * Enables the droppable.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Enable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", QJsPriority::Low);
		}
		/**
		 * Retrieves the droppables instance object. If the element does not have
		 * an associated instance, undefined is returned. 
		 * 
		 * Unlike other widget methods, instance() is safe to call on any element
		 * after the droppable plugin has loaded.
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
		 * droppable options hash.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Option1() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", QJsPriority::Low);
		}
		/**
		 * Sets the value of the droppable option associated with the specified
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
		 * Sets one or more options for the droppable.
		 * 
		 * 	* options Type: Object A map of option-value pairs to set.
		 * @param $options
		 */
		public function Option3($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, QJsPriority::Low);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'Accept': return $this->mixAccept;
				case 'ActiveClass': return $this->strActiveClass;
				case 'AddClasses': return $this->blnAddClasses;
				case 'Disabled': return $this->blnDisabled;
				case 'Greedy': return $this->blnGreedy;
				case 'HoverClass': return $this->strHoverClass;
				case 'Scope': return $this->strScope;
				case 'Tolerance': return $this->strTolerance;
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
				case 'Accept':
					$this->mixAccept = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'accept', $mixValue);
					break;

				case 'ActiveClass':
					try {
						$this->strActiveClass = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'activeClass', $this->strActiveClass);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AddClasses':
					try {
						$this->blnAddClasses = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'addClasses', $this->blnAddClasses);
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

				case 'Greedy':
					try {
						$this->blnGreedy = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'greedy', $this->blnGreedy);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'HoverClass':
					try {
						$this->strHoverClass = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'hoverClass', $this->strHoverClass);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Scope':
					try {
						$this->strScope = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'scope', $this->strScope);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tolerance':
					try {
						$this->strTolerance = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'tolerance', $this->strTolerance);
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
				new QModelConnectorParam (get_called_class(), 'ActiveClass', 'If specified, the class will be added to the droppable while anacceptable draggable is being dragged.', QType::String),
				new QModelConnectorParam (get_called_class(), 'AddClasses', 'If set to false, will prevent the ui-droppable class from being added.This may be desired as a performance optimization when calling.droppable() init on hundreds of elements.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the droppable if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Greedy', 'By default, when an element is dropped on nested droppables, eachdroppable will receive the element. However, by setting this option totrue, any parent droppables will not receive the element. The dropevent will still bubble normally, but the event.target can be checkedto see which droppable received the draggable element.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'HoverClass', 'If specified, the class will be added to the droppable while anacceptable draggable is being hovered over the droppable.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Scope', 'Used to group sets of draggable and droppable items, in addition tothe accept option. A draggable with the same scope value as adroppable will be accepted.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Tolerance', 'Specifies which mode to use for testing whether a draggable ishovering over a droppable. Possible values: 	* \"fit\": Draggable overlaps the droppable entirely.	* \"intersect\": Draggable overlaps the droppable at least 50% in bothdirections.	* \"pointer\": Mouse pointer overlaps the droppable.	* \"touch\": Draggable overlaps the droppable any amount.', QType::String),
			));
		}
	}