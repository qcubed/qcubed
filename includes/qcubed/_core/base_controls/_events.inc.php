<?php
/**
 * Classes in this file represent various "events" for QCubed. 
 * Programmer can "hook" into these events and write custom handlers.
 * Event-driven programming is explained in detail here: http://en.wikipedia.org/wiki/Event-driven_programming
 *
 * @package Events
 */

	/**
	 * Base class of all events. It's obviously abstract.
	 *
	 */
	abstract class QEvent extends QBaseClass {
		protected $strJavaScriptEvent;
		protected $strCondition = null;
		protected $intDelay = 0;

		public function __construct($intDelay = 0, $strCondition = null) {
			try {
				if ($intDelay)
					$this->intDelay = QType::Cast($intDelay, QType::Integer);
				if ($strCondition) {
					if ($this->strCondition)
						$this->strCondition = sprintf('(%s) && (%s)', $this->strCondition, $strCondition);
					else
						$this->strCondition = QType::Cast($strCondition, QType::String);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function __get($strName) {
			switch ($strName) {
				case 'JavaScriptEvent':
					return $this->strJavaScriptEvent;
				case 'Condition':
					return $this->strCondition;
				case 'Delay':
					return $this->intDelay;
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
		const EventName = 'blur';
		protected $strJavaScriptEvent = 'blur';
	}

	/**
	 * Be careful with change events for listboxes - 
	 * they don't fire when the user picks a value on many browsers!
	 */
	class QChangeEvent extends QEvent {
		const EventName = 'change';
		protected $strJavaScriptEvent = 'change';
	}

	class QClickEvent extends QEvent {
		const EventName = 'click';
		protected $strJavaScriptEvent = 'click';
	}

	class QDoubleClickEvent extends QEvent {
		const EventName = 'dblclick';
		protected $strJavaScriptEvent = 'dblclick';
	}

	class QDragDropEvent extends QEvent {
		const EventName = 'drop';
		protected $strJavaScriptEvent = 'drop';
	}

	/**
	 * Focus event: keyboard focus entering the control.
	 */
	class QFocusEvent extends QEvent {
		const EventName = 'focus';
		protected $strJavaScriptEvent = 'focus';
	}
	
	/* added for V2 / jQuery support */
	class QFocusInEvent extends QEvent {
		const EventName = 'focusin';
		protected $strJavaScriptEvent = 'focusin';
	}
	
	/* added for V2 / jQuery support */
	class QFocusOutEvent extends QEvent {
		const EventName = 'focusout';
		protected $strJavaScriptEvent = 'focusout';
	}	
	
	class QKeyDownEvent extends QEvent {
		const EventName = 'keydown';
		protected $strJavaScriptEvent = 'keydown';
	}

	class QKeyPressEvent extends QEvent {
		const EventName = 'keypress';
		protected $strJavaScriptEvent = 'keypress';
	}

	class QKeyUpEvent extends QEvent {
		const EventName = 'keyup';
		protected $strJavaScriptEvent = 'keyup';
	}

	class QMouseDownEvent extends QEvent {
		const EventName = 'mousedown';
		protected $strJavaScriptEvent = 'mousedown';
	}
	
	class QMouseEnterEvent extends QEvent {
		const EventName = 'mouseenter';
		protected $strJavaScriptEvent = 'mouseenter';
	}	

	class QMouseLeaveEvent extends QEvent {
		const EventName = 'mouseleave';
		protected $strJavaScriptEvent = 'mouseleave';
	}	

	class QMouseMoveEvent extends QEvent {
		const EventName = 'mousemove';
		protected $strJavaScriptEvent = 'mousemove';
	}

	class QMouseOutEvent extends QEvent {
		const EventName = 'mouseout';
		protected $strJavaScriptEvent = 'mouseout';
	}

	class QMouseOverEvent extends QEvent {
		const EventName = 'mouseover';
		protected $strJavaScriptEvent = 'mouseover';
	}

	class QMouseUpEvent extends QEvent {
		const EventName = 'mouseup';
		protected $strJavaScriptEvent = 'mouseup';
	}

	class QMoveEvent extends QEvent {
		const EventName = 'onqcodomove';
		protected $strJavaScriptEvent = 'onqcodomove';
	}

	class QResizeEvent extends QEvent {
		const EventName = 'onqcodoresize';
		protected $strJavaScriptEvent = 'onqcodoresize';
	}

	class QSelectEvent extends QEvent {
		const EventName = 'select';
		protected $strJavaScriptEvent = 'select';
	}

	class QEnterKeyEvent extends QKeyDownEvent {
		protected $strCondition = 'event.keyCode == 13';
	}
	
	class QEscapeKeyEvent extends QKeyDownEvent {
		protected $strCondition = 'event.keyCode == 27';
	}
	
	class QUpArrowKeyEvent extends QKeyDownEvent {
		protected $strCondition = 'event.keyCode == 38';
	}
	
	class QDownArrowKeyEvent extends QKeyDownEvent {
		protected $strCondition = 'event.keyCode == 40';
	}
?>