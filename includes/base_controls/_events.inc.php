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
	 * @property-read boolean $Block indicates that other events after this event will be thrown away until the browser receives a response from this event.
	 */
	abstract class QEvent extends QBaseClass {
		/** @var string|null The JS condition in which an event would fire  */
		protected $strCondition = null;
		/** @var int|mixed The number of second after which the event has to be fired */
		protected $intDelay = 0;
		protected $strSelector = null;
		/** @var  boolean True to block all other events until a response is received. */
		protected $blnBlock;

		/**
		 * Create an event.
		 * @param integer $intDelay ms delay to wait before action is fired
		 * @param string $strCondition javascript condition to check before firing the action
		 * @param string $strSelector jquery selector to cause event to be attached to child items instead of this item
		 * @param boolean $blnBlockOtherEvents True to "debounce" the event by throwing away all other events until the browser receives a response from this event.
		 * 							Only use this on Server and Ajax events. Do not use on Javascript events, or the browser will stop responding to Ajax and Server events.
		 * @throws Exception|QCallerException
		 */
		public function __construct($intDelay = 0, $strCondition = null, $strSelector = null, $blnBlockOtherEvents = false) {
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
				$this->blnBlock = $blnBlockOtherEvents;
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
				case 'Block':
					return $this->blnBlock;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
		
		/**
		 * Use an option array to create a new event e.g.:
		 *  
		 * QKeyUpEvent(['Delay'=>100,'Condition'=>'event.keyCode == 13']);
		 * is equivalent to
		 * new QKeyUpEvent(100,'event.keyCode == 13')  
		 *  
		 * @param array $objOptions
		 * @return QEvent
		 */
		public static function CreateWithOptions($objOptions=null) {
			$objEvent = new static();
			foreach($objOptions as $option=>$value) {
				$objEvent->$option = $value;
			}
			return $objEvent;			
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
	 * Respond to any custom javascript event.
	 *
	 * Note, at one time, this event was required to react to bubbled events, but now every event
	 * has a $strSelector to trigger on bubbled events.
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
		 * @param int  $intDelay
		 * @param string $strCondition
		 * @param string $strSelector
		 * @throws Exception|QCallerException
		 */
		public function __construct($strEventName, $intDelay = 0, $strCondition = null, $strSelector = null) {
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

	/**
	 * Class QCellClickEvent
	 * An event to detect clicking on a table cell.
	 * Lots of things can be determined using this event by changing the JsReturnParam values. When this event fires,
	 * the javascript environment will have the following local variables defined:
	 * - this: The html object for the cell clicked.
	 * - event: The event object for the click.
	 *
	 * Here are some examples of return params you can specify to return data to your action handler:
	 * 	this.id - the cell id
	 *  this.tagName - the tag for the cell (either th or td)
	 *  this.cellIndex - the column index that was clicked on, starting on the left with column zero
	 *  $j(this).data('value') - the "data-value" attribute of the cell (if you specify one). Use this formula for any kind of "data-" attribute.
	 *  $j(this).parent() - the jQuery row object
	 *  $j(this).parent()[0] - the html row object
	 *  $j(this).parent()[0].rowIndex - the index of the row clicked, starting with zero at the top (including any header rows).
	 *  $j(this).parent().attr('id') or $j(this).parent()[0].id - the id of the row clicked on
	 *  $j(this).parent().data("value") - the "data-value" attribute of the row. Use this formula for any kind of "data-" attribute.
	 *  $j(this).parent().closest('table').find('thead').find('th')[this.cellIndex].id - the id of the column clicked in
	 *  event.target - the html object clicked in. If your table cell had other objects in it, this will return the
	 *    object clicked inside the cell. This could be important, for example, if you had form objects inside the cell,
	 *    and you wanted to behave differently if a form object was clicked on, verses clicking outside the form object.
	 *
	 * You can put your items in a javascript array, and an array will be returned as the strParameter in the action.
	 * Or you can put it in a javascript object, and a named array(hash) will be returned.
	 *
	 * The default returns the array(row=>rowIndex, col=>colIndex), but you can override this with your action. For
	 * example:
	 *
	 * new QAjaxAction ('yourFunction', null, 'this.cellIndex')
	 *
	 * will return the column index into the strParameter, instead of the default.
	 */
	class QCellClickEvent extends QClickEvent {
		// Shortcuts to specify common return parameters
		const RowIndex = '$j(this).parent()[0].rowIndex';
		const ColumnIndex = 'this.cellIndex';
		const CellId = 'this.id';
		const RowId = '$j(this).parent().attr("id")';
		const RowValue = '$j(this).parent().data("value")';
		const ColId = '$j(this).parent().closest("table").find("thead").find("th")[this.cellIndex].id';

		protected $strReturnParam;

		public function __construct($intDelay = 0, $strCondition = null, $mixReturnParams = null, $blnBlockOtherEvents = false) {
			parent::__construct($intDelay, $strCondition, 'th,td', $blnBlockOtherEvents);

			if (!$mixReturnParams) {
				$this->strReturnParam = '{"row": $j(this).parent()[0].rowIndex, "col": this.cellIndex}'; // default returns the row and colum indexes of the cell clicked
			}
			else if (is_array($mixReturnParams)) {
				$combined = array_map(function($key, $val) {
					return '"' . $key . '":' . $val;
				}, array_keys($mixReturnParams), array_values($mixReturnParams));

				$this->strReturnParam = '{' . implode(',', $combined) . '}';
			}
			elseif (is_string($mixReturnParams)) {
				$this->strReturnParam = $mixReturnParams;
			}
		}

		/**
		 * Returns the javascript that returns the row data value into a param
		 * @param $strKey
		 * @return string
		 */
		public static function RowDataValue($strKey) {
			return 	'$j(this).parent().data("' . $strKey . '")';
		}

		/**
		 * Same for the cell.
		 *
		 * @param $strKey
		 * @return string
		 */
		public static function CellDataValue($strKey) {
			return 	'$j(this).data("' . $strKey . '")';
		}


		public function __get($strName) {
			switch($strName) {
				case 'JsReturnParam':
					return $this->strReturnParam;

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