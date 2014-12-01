<?php
	/**
	 * Classes in this file represent various "events" for QCubed.
	 * Programmer can "hook" into these events and write custom handlers.
	 * Event-driven programming is explained in detail here: http://en.wikipedia.org/wiki/Event-driven_programming
	 *
	 * @package Events
	 */

	/**
	 * Base class of QEvents.
	 * Events are used in conjunction with actions to respond to user actions, like clicking, typing, etc.,
	 * or even programmable timer events.
	 * @property-read string $EventName the javascript event name that will be fired
	 * @property-read string $Condition a javascript condition that is tested before the event is sent
	 * @property-read integer $Delay ms delay before action is fired
	 * @property-read string $JsReturnParam the javascript used to create the strParameter that gets sent to the event handler registered with the event.
	 * @property-read string $Selector a jquery selector, causes the event to apply to child items matching the selector, and then get sent up the chain to this object
	 *
	 *
	 */
	abstract class QEvent extends QBaseClass {
		/** @var string|null The JS condition in which an event would fire  */
		protected $strCondition = null;
		/** @var int|mixed The number of second after which the event has to be fired */
		protected $intDelay = 0;
		protected $strSelector = null;

		/**
		 * Create an event.
		 * @param integer $intDelay ms delay to wait before action is fired
		 * @param string $strCondition javascript condition to check before firing the action
		 * @param string $strSelector jquery selector to cause event to be attached to child items instead of this item
		 * @throws Exception|QCallerException
		 */
		public function __construct($intDelay = 0, $strCondition = null, $strSelector = null) {
			try {
				if ($intDelay)
					$this->intDelay = QType::Cast($intDelay, QType::Integer);
				if ($strCondition) {
					if ($this->strCondition)
						$this->strCondition = sprintf('(%s) && (%s)', $this->strCondition, $strCondition);
					else
						$this->strCondition = QType::Cast($strCondition, QType::String);
				}
				if ($strSelector) {
					$this->strSelector = $strSelector;
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * The PHP Magic function for this class
		 * @param string $strName Name of the property to fetch
		 *
		 * @return int|mixed|null|string
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'EventName':
					$strEvent = constant(get_class($this).'::EventName');
					if ($this->strSelector) {
						$strEvent .= '","' . addslashes($this->strSelector);
					}
					return $strEvent;
				case 'Condition':
					return $this->strCondition;
				case 'Delay':
					return $this->intDelay;
				case 'JsReturnParam':
					$strConst = get_class($this).'::JsReturnParam';
					return defined($strConst) ? constant($strConst) : '';
				case 'Selector':
					return $this->strSelector;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	/**
	 * Blur event: keyboard focus moving away from the control.
	 */
	class QBlurEvent extends QEvent {
		/** Event Name */
		const EventName = 'blur';
	}

	/**
	 * Be careful with change events for listboxes -
	 * they don't fire when the user picks a value on many browsers!
	 */
	class QChangeEvent extends QEvent {
		/** Event Name */
		const EventName = 'change';
	}

	/** Click event: when the control recieves a mouse click */
	class QClickEvent extends QEvent {
		/** Event Name */
		const EventName = 'click';
	}

	/** Double-Click event: when the control recieves a double click */
	class QDoubleClickEvent extends QEvent {
		/** Event Name */
		const EventName = 'dblclick';
	}

	/** Drop event: When an element is dropped onto another element */
	class QDragDropEvent extends QEvent {
		/** Event Name */
		const EventName = 'drop';
	}

	/**
	 * Focus event: keyboard focus entering the control.
	 */
	class QFocusEvent extends QEvent {
		/** Event Name */
		const EventName = 'focus';
	}

	/** added for V2 / jQuery support */
	class QFocusInEvent extends QEvent {
		/** Event Name */
		const EventName = 'focusin';
	}

	/** added for V2 / jQuery support */
	class QFocusOutEvent extends QEvent {
		/** Event Name */
		const EventName = 'focusout';
	}

	/** When a keyboard key is pressed down (without having been released) while the control is in focus */
	class QKeyDownEvent extends QEvent {
		/** Event Name */
		const EventName = 'keydown';
	}

	/** When a keyboard key has been pressed (key went down, and went up) */
	class QKeyPressEvent extends QEvent {
		/** Event Name */
		const EventName = 'keypress';
	}

	/** When a pressed key goes up while the focus is on the control */
	class QKeyUpEvent extends QEvent {
		/** Event Name */
		const EventName = 'keyup';
	}

	/** Mouse button was pressed down on the control */
	class QMouseDownEvent extends QEvent {
		/** Event Name */
		const EventName = 'mousedown';
	}

	/** When the mouse cursor enters the control */
	class QMouseEnterEvent extends QEvent {
		/** Event Name */
		const EventName = 'mouseenter';
	}

	/** When the mouse cursor leaves the control */
	class QMouseLeaveEvent extends QEvent {
		/** Event Name */
		const EventName = 'mouseleave';
	}

	/** When the mouse pointer moves within the control on the browser */
	class QMouseMoveEvent extends QEvent {
		/** Event Name */
		const EventName = 'mousemove';
	}

	/** When the mouse cursor leaves the control and any of its children */
	class QMouseOutEvent extends QEvent {
		/** Event Name */
		const EventName = 'mouseout';
	}

	/** When the mouse is over the control or an element inside it */
	class QMouseOverEvent extends QEvent {
		/** Event Name */
		const EventName = 'mouseover';
	}

	/** When the left mouse button is released (after being pressed) from over the control */
	class QMouseUpEvent extends QEvent {
		/** Event Name */
		const EventName = 'mouseup';
	}

	/** When the control/element is selected */
	class QSelectEvent extends QEvent {
		/** Event Name */
		const EventName = 'select';
	}

	/** Override right clicks */
	class QContextMenuEvent extends QEvent {
		/** Event Name */
		const EventName = 'contextmenu';
	}

	/** When enter key is pressed while the control is in focus */
	class QEnterKeyEvent extends QKeyDownEvent {
		/** @var string Condition JS */
		protected $strCondition = 'event.keyCode == 13';
	}

	/** When the escape key is pressed while the control is in focus */
	class QEscapeKeyEvent extends QKeyDownEvent {
		/** @var string Condition JS */
		protected $strCondition = 'event.keyCode == 27';
	}

	/** When the up arrow key is pressed while the element is in focus */
	class QUpArrowKeyEvent extends QKeyDownEvent {
		/** @var string Condition JS */
		protected $strCondition = 'event.keyCode == 38';
	}

	/** When the down arrow key is pressed while the element is in focus */
	class QDownArrowKeyEvent extends QKeyDownEvent {
		/** @var string Condition JS */
		protected $strCondition = 'event.keyCode == 40';
	}

	/** When the Tab key is pressed with element in focus */
	class QTabKeyEvent extends QKeyDownEvent {
		/** @var string Condition JS with keycode for tab key */
		protected $strCondition = 'event.keyCode == 9';
	}

	/** When the Backspace key is pressed with element in focus */
	class QBackspaceKeyEvent extends QKeyDownEvent {
		/** @var string Condition JS with keycode for escape key */
		protected $strCondition = 'event.keyCode == 8';
	}

	/**
	 * Detects changes to textboxes and other input elements. Responds to cut/paste, search cancel, etc.
	 * Ignores arrow keys, etc.
	 * Not in IE8 or below. Buggy in IE9. Full support in IE10 and above.
	 * No support in Safari 5 and below for textarea elements.
	 */
	class QInputEvent extends QEvent {
		/** Event Name */
		const EventName = 'input';
	}

	/**
	 * Class QJqUiEvent: When an event is triggered by jQuery-UI (drag, drop, resize etc.)
	 * @abstract Implementation in children class
	 */
	abstract class QJqUiEvent extends QEvent {
		// be sure to subclass your events from this class if they are JqUiEvents
	}

	/**
	 * Class QJqUiPropertyEvent: When properties of a jQuery-UI widget change
	 * Currently, Date-Time related jQuery-UI controls are derived from this one
	 *
	 * @property-read string $JqProperty The property string
	 */
	abstract class QJqUiPropertyEvent extends QEvent {
		// be sure to subclass your events from this class if they are JqUiEvents
		/** @var string The property JS string */
		protected $strJqProperty = '';

		/**
		 * PHP Magic method to get properties from this class
		 * @param string $strName
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'JqProperty':
					return $this->strJqProperty;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}


	/**
	 *
	 * a custom event with event delegation
	 * With this event you can delegate any jquery event of child controls or any html element
	 * to a parent. By using the selector you can limit the event sources this event
	 * gets triggered from. You can use a css class (or any jquery selector) for
	 * $strSelector. Example ( new QJsDelegateEvent("click",".remove",new QAjaxControlAction( ... )); )
	 *
	 * This event can help you reduce the produced javascript to a minimum.
	 * One positive side effect is that this event will also work for html child elements added
	 * in the future (after the event was created).
	 *
	 * @param string $strEventName the name of the event i.e.: "click"
	 * @param string $strSelector i.e.: "#myselector" ==> results in: $('#myControl').on("myevent","#myselector",function()...
	 *
	 */
	class QOnEvent extends QEvent{
		/** @var string Name of the event */
		protected $strEventName;

		/**
		 * Constructor
		 * @param int  $strEventName
		 * @param string $strSelector
		 * @param string $strCondition
		 * @param int  $intDelay
		 *
		 * @throws Exception|QCallerException
		 */
		public function __construct($strEventName, $strSelector = null, $strCondition = null, $intDelay = 0) {
			$this->strEventName=$strEventName;
			if ($strSelector) {
				$strSelector = addslashes($strSelector);
				$this->strEventName .= '","'.$strSelector;
			}

			try {
				parent::__construct($intDelay,$strCondition, $strSelector);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * PHP Magic function implementation
		 * @param string $strName
		 *
		 * @return int|mixed|null|string
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'EventName':
					return $this->strEventName;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

	}

?>
