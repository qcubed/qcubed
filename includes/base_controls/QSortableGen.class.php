<?php	
	/**
	 * This event is triggered when using connected lists, every connected
	 * list on drag start receives it.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* sender Type: jQuery The sortable that the item comes from if
	 * moving from one sortable to another.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_ActivateEvent extends QJqUiEvent {
		const EventName = 'sortactivate';
	}
	/**
	 * This event is triggered when sorting stops, but when the
	 * placeholder/helper is still available.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* sender Type: jQuery The sortable that the item comes from if
	 * moving from one sortable to another.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_BeforeStopEvent extends QJqUiEvent {
		const EventName = 'sortbeforestop';
	}
	/**
	 * This event is triggered during sorting, but only when the DOM position
	 * has changed.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* sender Type: jQuery The sortable that the item comes from if
	 * moving from one sortable to another.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_ChangeEvent extends QJqUiEvent {
		const EventName = 'sortchange';
	}
	/**
	 * Triggered when the sortable is created.
	 * 
	 * 	* event Type: Event 
	 * 	* ui Type: Object 
	 * 
	 * _Note: The ui object is empty but included for consistency with other
	 * events._	 */
	class QSortable_CreateEvent extends QJqUiEvent {
		const EventName = 'sortcreate';
	}
	/**
	 * This event is triggered when sorting was stopped, is propagated to all
	 * possible connected lists.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* sender Type: jQuery The sortable that the item comes from if
	 * moving from one sortable to another.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_DeactivateEvent extends QJqUiEvent {
		const EventName = 'sortdeactivate';
	}
	/**
	 * This event is triggered when a sortable item is moved away from a
	 * sortable list. 
	 * 
	 * _Note: This event is also triggered when a sortable item is dropped._
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* sender Type: jQuery The sortable that the item comes from if
	 * moving from one sortable to another.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_OutEvent extends QJqUiEvent {
		const EventName = 'sortout';
	}
	/**
	 * This event is triggered when a sortable item is moved into a sortable
	 * list.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* sender Type: jQuery The sortable that the item comes from if
	 * moving from one sortable to another.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_OverEvent extends QJqUiEvent {
		const EventName = 'sortover';
	}
	/**
	 * This event is triggered when an item from a connected sortable list
	 * has been dropped into another list. The latter is the event target.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* sender Type: jQuery The sortable that the item comes from if
	 * moving from one sortable to another.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_ReceiveEvent extends QJqUiEvent {
		const EventName = 'sortreceive';
	}
	/**
	 * This event is triggered when a sortable item from the list has been
	 * dropped into another. The former is the event target.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_RemoveEvent extends QJqUiEvent {
		const EventName = 'sortremove';
	}
	/**
	 * This event is triggered during sorting.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* sender Type: jQuery The sortable that the item comes from if
	 * moving from one sortable to another.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_SortEvent extends QJqUiEvent {
		const EventName = 'sort';
	}
	/**
	 * This event is triggered when sorting starts.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* sender Type: jQuery The sortable that the item comes from if
	 * moving from one sortable to another.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_StartEvent extends QJqUiEvent {
		const EventName = 'sortstart';
	}
	/**
	 * This event is triggered when sorting has stopped.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* sender Type: jQuery The sortable that the item comes from if
	 * moving from one sortable to another.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_StopEvent extends QJqUiEvent {
		const EventName = 'sortstop';
	}
	/**
	 * This event is triggered when the user stopped sorting and the DOM
	 * position has changed.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* helper Type: jQuery The jQuery object representing the helper being
	 * sorted.
	 * 	* item Type: jQuery The jQuery object representing the current
	 * dragged element.
	 * 	* offset Type: Object The current absolute position of the helper
	 * represented as { top, left }.
	 * 	* position Type: Object The current position of the helper
	 * represented as { top, left }.
	 * 	* originalPosition Type: Object The original position of the element
	 * represented as { top, left }.
	 * 	* sender Type: jQuery The sortable that the item comes from if
	 * moving from one sortable to another.
	 * 	* placeholder Type: jQuery The jQuery object representing the
	 * element being used as a placeholder.
	 * 
	 */
	class QSortable_UpdateEvent extends QJqUiEvent {
		const EventName = 'sortupdate';
	}

	/* Custom "property" event classes for this control */

	/**
	 * Generated QSortableGen class.
	 * 
	 * This is the QSortableGen class which is automatically generated
	 * by scraping the JQuery UI documentation website. As such, it includes all the options
	 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
	 * the QSortableBase class for any glue code to make this class more
	 * usable in QCubed.
	 * 
	 * @see QSortableBase
	 * @package Controls\Base
	 * @property mixed $AppendTo
	 * Defines where the helper that moves with the mouse is being appended
	 * to during the drag (for example, to resolve overlap/zIndex
	 * issues).Multiple types supported:
	 * 
	 * 	* jQuery: A jQuery object containing the element to append the helper
	 * to.
	 * 	* Element: The element to append the helper to.
	 * 	* Selector: A selector specifying which element to append the helper
	 * to.
	 * 	* String: The string "parent" will cause the helper to be a sibling
	 * of the sortable item.
	 * 

	 *
	 * @property string $Axis
	 * If defined, the items can be dragged only horizontally or vertically.
	 * Possible values: "x", "y".
	 *
	 * @property mixed $Cancel
	 * Prevents sorting if you start on elements matching the selector.
	 *
	 * @property mixed $ConnectWith
	 * A selector of other sortable elements that the items from this list
	 * should be connected to. This is a one-way relationship, if you want
	 * the items to be connected in both directions, the connectWith option
	 * must be set on both sortable elements.
	 *
	 * @property mixed $Containment
	 * Defines a bounding box that the sortable items are constrained to
	 * while dragging. 
	 * 
	 * Note: The element specified for containment must have a calculated
	 * width and height (though it need not be explicit). For example, if you
	 * have float: left sortable children and specify containment: "parent"
	 * be sure to have float: left on the sortable/parent container as well
	 * or it will have height: 0, causing undefined behavior.Multiple types
	 * supported:
	 * 
	 * 	* Element: An element to use as the container.
	 * 	* Selector: A selector specifying an element to use as the
	 * container.
	 * 	* String: A string identifying an element to use as the container.
	 * Possible values: "parent", "document", "window".
	 * 

	 *
	 * @property string $Cursor
	 * Defines the cursor that is being shown while sorting.
	 *
	 * @property mixed $CursorAt
	 * Moves the sorting element or helper so the cursor always appears to
	 * drag from the same position. Coordinates can be given as a hash using
	 * a combination of one or two keys: { top, left, right, bottom }.
	 *
	 * @property integer $Delay
	 * Time in milliseconds to define when the sorting should start. Adding a
	 * delay helps preventing unwanted drags when clicking on an element.
	 *
	 * @property boolean $Disabled
	 * Disables the sortable if set to true.
	 *
	 * @property integer $Distance
	 * Tolerance, in pixels, for when sorting should start. If specified,
	 * sorting will not start until after mouse is dragged beyond distance.
	 * Can be used to allow for clicks on elements within a handle.
	 *
	 * @property boolean $DropOnEmpty
	 * If false, items from this sortable cant be dropped on an empty connect
	 * sortable (see the connectWith option.
	 *
	 * @property boolean $ForceHelperSize
	 * If true, forces the helper to have a size.
	 *
	 * @property boolean $ForcePlaceholderSize
	 * If true, forces the placeholder to have a size.
	 *
	 * @property array $Grid
	 * Snaps the sorting element or helper to a grid, every x and y pixels.
	 * Array values: [ x, y ].
	 *
	 * @property mixed $Handle
	 * Restricts sort start click to the specified element.
	 *
	 * @property mixed $Helper
	 * Allows for a helper element to be used for dragging display.Multiple
	 * types supported:
	 * 
	 * 	* String: If set to "clone", then the element will be cloned and the
	 * clone will be dragged.
	 * 	* Function: A function that will return a DOMElement to use while
	 * dragging. The function receives the event and the element being
	 * sorted.
	 * 

	 *
	 * @property mixed $Items
	 * Specifies which items inside the element should be sortable.
	 *
	 * @property integer $Opacity
	 * Defines the opacity of the helper while sorting. From 0.01 to 1.
	 *
	 * @property string $Placeholder
	 * A class name that gets applied to the otherwise white space.
	 *
	 * @property mixed $Revert
	 * Whether the sortable items should revert to their new positions using
	 * a smooth animation.Multiple types supported:
	 * 
	 * 	* Boolean: When set to true, the items will animate with the default
	 * duration.
	 * 	* Number: The duration for the animation, in milliseconds.
	 * 

	 *
	 * @property boolean $Scroll
	 * If set to true, the page scrolls when coming to an edge.
	 *
	 * @property integer $ScrollSensitivity
	 * Defines how near the mouse must be to an edge to start scrolling.
	 *
	 * @property integer $ScrollSpeed
	 * The speed at which the window should scroll once the mouse pointer
	 * gets within the scrollSensitivity distance.
	 *
	 * @property string $Tolerance
	 * Specifies which mode to use for testing whether the item being moved
	 * is hovering over another item. Possible values: 
	 * 
	 * 	* "intersect": The item overlaps the other item by at least 50%.
	 * 	* "pointer": The mouse pointer overlaps the other item.
	 * 

	 *
	 * @property integer $ZIndex
	 * Z-index for element/helper while being sorted.
	 *
	 */

	class QSortableGen extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var mixed */
		protected $mixAppendTo = null;
		/** @var string */
		protected $strAxis = null;
		/** @var mixed */
		protected $mixCancel = null;
		/** @var mixed */
		protected $mixConnectWith = null;
		/** @var mixed */
		protected $mixContainment = null;
		/** @var string */
		protected $strCursor = null;
		/** @var mixed */
		protected $mixCursorAt = null;
		/** @var integer */
		protected $intDelay;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var integer */
		protected $intDistance = null;
		/** @var boolean */
		protected $blnDropOnEmpty = null;
		/** @var boolean */
		protected $blnForceHelperSize = null;
		/** @var boolean */
		protected $blnForcePlaceholderSize = null;
		/** @var array */
		protected $arrGrid = null;
		/** @var mixed */
		protected $mixHandle = null;
		/** @var mixed */
		protected $mixHelper = null;
		/** @var mixed */
		protected $mixItems = null;
		/** @var integer */
		protected $intOpacity = null;
		/** @var string */
		protected $strPlaceholder = null;
		/** @var mixed */
		protected $mixRevert = null;
		/** @var boolean */
		protected $blnScroll = null;
		/** @var integer */
		protected $intScrollSensitivity = null;
		/** @var integer */
		protected $intScrollSpeed = null;
		/** @var string */
		protected $strTolerance = null;
		/** @var integer */
		protected $intZIndex = null;

		/**
		 * Builds the option array to be sent to the widget constructor.
		 *
		 * @return array key=>value array of options
		 */
		protected function MakeJqOptions() {
			$jqOptions = null;
			if (!is_null($val = $this->AppendTo)) {$jqOptions['appendTo'] = $val;}
			if (!is_null($val = $this->Axis)) {$jqOptions['axis'] = $val;}
			if (!is_null($val = $this->Cancel)) {$jqOptions['cancel'] = $val;}
			if (!is_null($val = $this->ConnectWith)) {$jqOptions['connectWith'] = $val;}
			if (!is_null($val = $this->Containment)) {$jqOptions['containment'] = $val;}
			if (!is_null($val = $this->Cursor)) {$jqOptions['cursor'] = $val;}
			if (!is_null($val = $this->CursorAt)) {$jqOptions['cursorAt'] = $val;}
			if (!is_null($val = $this->Delay)) {$jqOptions['delay'] = $val;}
			if (!is_null($val = $this->Disabled)) {$jqOptions['disabled'] = $val;}
			if (!is_null($val = $this->Distance)) {$jqOptions['distance'] = $val;}
			if (!is_null($val = $this->DropOnEmpty)) {$jqOptions['dropOnEmpty'] = $val;}
			if (!is_null($val = $this->ForceHelperSize)) {$jqOptions['forceHelperSize'] = $val;}
			if (!is_null($val = $this->ForcePlaceholderSize)) {$jqOptions['forcePlaceholderSize'] = $val;}
			if (!is_null($val = $this->Grid)) {$jqOptions['grid'] = $val;}
			if (!is_null($val = $this->Handle)) {$jqOptions['handle'] = $val;}
			if (!is_null($val = $this->Helper)) {$jqOptions['helper'] = $val;}
			if (!is_null($val = $this->Items)) {$jqOptions['items'] = $val;}
			if (!is_null($val = $this->Opacity)) {$jqOptions['opacity'] = $val;}
			if (!is_null($val = $this->Placeholder)) {$jqOptions['placeholder'] = $val;}
			if (!is_null($val = $this->Revert)) {$jqOptions['revert'] = $val;}
			if (!is_null($val = $this->Scroll)) {$jqOptions['scroll'] = $val;}
			if (!is_null($val = $this->ScrollSensitivity)) {$jqOptions['scrollSensitivity'] = $val;}
			if (!is_null($val = $this->ScrollSpeed)) {$jqOptions['scrollSpeed'] = $val;}
			if (!is_null($val = $this->Tolerance)) {$jqOptions['tolerance'] = $val;}
			if (!is_null($val = $this->ZIndex)) {$jqOptions['zIndex'] = $val;}
			return $jqOptions;
		}

		/**
		 * Return the JavaScript function to call to associate the widget with the control.
		 *
		 * @return string
		 */
		public function GetJqSetupFunction() {
			return 'sortable';
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
		 * Cancels a change in the current sortable and reverts it to the state
		 * prior to when the current sort was started. Useful in the stop and
		 * receive callback functions.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Cancel() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "cancel", QJsPriority::Low);
		}
		/**
		 * Removes the sortable functionality completely. This will return the
		 * element back to its pre-init state.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Destroy() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", QJsPriority::Low);
		}
		/**
		 * Disables the sortable.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Disable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", QJsPriority::Low);
		}
		/**
		 * Enables the sortable.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Enable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", QJsPriority::Low);
		}
		/**
		 * Retrieves the sortables instance object. If the element does not have
		 * an associated instance, undefined is returned. 
		 * 
		 * Unlike other widget methods, instance() is safe to call on any element
		 * after the sortable plugin has loaded.
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
		 * sortable options hash.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Option1() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", QJsPriority::Low);
		}
		/**
		 * Sets the value of the sortable option associated with the specified
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
		 * Sets one or more options for the sortable.
		 * 
		 * 	* options Type: Object A map of option-value pairs to set.
		 * @param $options
		 */
		public function Option3($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, QJsPriority::Low);
		}
		/**
		 * Refresh the sortable items. Triggers the reloading of all sortable
		 * items, causing new items to be recognized.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Refresh() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "refresh", QJsPriority::Low);
		}
		/**
		 * Refresh the cached positions of the sortable items. Calling this
		 * method refreshes the cached item positions of all sortables.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function RefreshPositions() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "refreshPositions", QJsPriority::Low);
		}
		/**
		 * Serializes the sortables item ids into a form/ajax submittable string.
		 * Calling this method produces a hash that can be appended to any url to
		 * easily submit a new item order back to the server. 
		 * 
		 * It works by default by looking at the id of each item in the format
		 * "setname_number", and it spits out a hash like
		 * "setname[]=number&setname[]=number". 
		 * 
		 * _Note: If serialize returns an empty string, make sure the id
		 * attributes include an underscore. They must be in the form:
		 * "set_number" For example, a 3 element list with id attributes "foo_1",
		 * "foo_5", "foo_2" will serialize to "foo[]=1&foo[]=5&foo[]=2". You can
		 * use an underscore, equal sign or hyphen to separate the set and
		 * number. For example "foo=1", "foo-1", and "foo_1" all serialize to
		 * "foo[]=1"._
		 * 
		 * 	* options Type: Object Options to customize the serialization. 
		 * 
		 * 	* key (default: the part of the attribute in front of the separator)
		 * Type: String Replaces part1[] with the specified value.
		 * 	* attribute (default: "id") Type: String The name of the attribute
		 * to use for the values.
		 * 	* expression (default: /(.+)[-=_](.+)/) Type: RegExp A regular
		 * expression used to split the attribute value into key and value parts.
		 * @param $options
		 */
		public function Serialize($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "serialize", $options, QJsPriority::Low);
		}
		/**
		 * Serializes the sortables item ids into an array of string.
		 * 
		 * 	* options Type: Object Options to customize the serialization. 
		 * 
		 * 	* attribute (default: "id") Type: String The name of the attribute to
		 * use for the values.
		 * @param $options
		 */
		public function ToArray($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "toArray", $options, QJsPriority::Low);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'AppendTo': return $this->mixAppendTo;
				case 'Axis': return $this->strAxis;
				case 'Cancel': return $this->mixCancel;
				case 'ConnectWith': return $this->mixConnectWith;
				case 'Containment': return $this->mixContainment;
				case 'Cursor': return $this->strCursor;
				case 'CursorAt': return $this->mixCursorAt;
				case 'Delay': return $this->intDelay;
				case 'Disabled': return $this->blnDisabled;
				case 'Distance': return $this->intDistance;
				case 'DropOnEmpty': return $this->blnDropOnEmpty;
				case 'ForceHelperSize': return $this->blnForceHelperSize;
				case 'ForcePlaceholderSize': return $this->blnForcePlaceholderSize;
				case 'Grid': return $this->arrGrid;
				case 'Handle': return $this->mixHandle;
				case 'Helper': return $this->mixHelper;
				case 'Items': return $this->mixItems;
				case 'Opacity': return $this->intOpacity;
				case 'Placeholder': return $this->strPlaceholder;
				case 'Revert': return $this->mixRevert;
				case 'Scroll': return $this->blnScroll;
				case 'ScrollSensitivity': return $this->intScrollSensitivity;
				case 'ScrollSpeed': return $this->intScrollSpeed;
				case 'Tolerance': return $this->strTolerance;
				case 'ZIndex': return $this->intZIndex;
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

				case 'Axis':
					try {
						$this->strAxis = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'axis', $this->strAxis);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Cancel':
					$this->mixCancel = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'cancel', $mixValue);
					break;

				case 'ConnectWith':
					$this->mixConnectWith = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'connectWith', $mixValue);
					break;

				case 'Containment':
					$this->mixContainment = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'containment', $mixValue);
					break;

				case 'Cursor':
					try {
						$this->strCursor = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'cursor', $this->strCursor);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CursorAt':
					$this->mixCursorAt = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'cursorAt', $mixValue);
					break;

				case 'Delay':
					try {
						$this->intDelay = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'delay', $this->intDelay);
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

				case 'Distance':
					try {
						$this->intDistance = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'distance', $this->intDistance);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DropOnEmpty':
					try {
						$this->blnDropOnEmpty = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'dropOnEmpty', $this->blnDropOnEmpty);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ForceHelperSize':
					try {
						$this->blnForceHelperSize = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'forceHelperSize', $this->blnForceHelperSize);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ForcePlaceholderSize':
					try {
						$this->blnForcePlaceholderSize = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'forcePlaceholderSize', $this->blnForcePlaceholderSize);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Grid':
					try {
						$this->arrGrid = QType::Cast($mixValue, QType::ArrayType);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'grid', $this->arrGrid);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Handle':
					$this->mixHandle = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'handle', $mixValue);
					break;

				case 'Helper':
					$this->mixHelper = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'helper', $mixValue);
					break;

				case 'Items':
					$this->mixItems = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'items', $mixValue);
					break;

				case 'Opacity':
					try {
						$this->intOpacity = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'opacity', $this->intOpacity);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Placeholder':
					try {
						$this->strPlaceholder = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'placeholder', $this->strPlaceholder);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Revert':
					$this->mixRevert = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'revert', $mixValue);
					break;

				case 'Scroll':
					try {
						$this->blnScroll = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'scroll', $this->blnScroll);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ScrollSensitivity':
					try {
						$this->intScrollSensitivity = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'scrollSensitivity', $this->intScrollSensitivity);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ScrollSpeed':
					try {
						$this->intScrollSpeed = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'scrollSpeed', $this->intScrollSpeed);
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

				case 'ZIndex':
					try {
						$this->intZIndex = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'zIndex', $this->intZIndex);
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
				new QModelConnectorParam (get_called_class(), 'Axis', 'If defined, the items can be dragged only horizontally or vertically.Possible values: \"x\", \"y\".', QType::String),
				new QModelConnectorParam (get_called_class(), 'Cursor', 'Defines the cursor that is being shown while sorting.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Delay', 'Time in milliseconds to define when the sorting should start. Adding adelay helps preventing unwanted drags when clicking on an element.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the sortable if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Distance', 'Tolerance, in pixels, for when sorting should start. If specified,sorting will not start until after mouse is dragged beyond distance.Can be used to allow for clicks on elements within a handle.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'DropOnEmpty', 'If false, items from this sortable cant be dropped on an empty connectsortable (see the connectWith option.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ForceHelperSize', 'If true, forces the helper to have a size.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ForcePlaceholderSize', 'If true, forces the placeholder to have a size.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Grid', 'Snaps the sorting element or helper to a grid, every x and y pixels.Array values: [ x, y ].', QType::ArrayType),
				new QModelConnectorParam (get_called_class(), 'Opacity', 'Defines the opacity of the helper while sorting. From 0.01 to 1.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Placeholder', 'A class name that gets applied to the otherwise white space.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Scroll', 'If set to true, the page scrolls when coming to an edge.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ScrollSensitivity', 'Defines how near the mouse must be to an edge to start scrolling.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'ScrollSpeed', 'The speed at which the window should scroll once the mouse pointergets within the scrollSensitivity distance.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Tolerance', 'Specifies which mode to use for testing whether the item being movedis hovering over another item. Possible values: 	* \"intersect\": The item overlaps the other item by at least 50%.	* \"pointer\": The mouse pointer overlaps the other item.', QType::String),
				new QModelConnectorParam (get_called_class(), 'ZIndex', 'Z-index for element/helper while being sorted.', QType::Integer),
			));
		}
	}