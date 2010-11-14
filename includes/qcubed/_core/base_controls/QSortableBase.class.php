<?php
	/* Custom event classes for this control */
	/**
	 * This event is triggered when sorting starts.
	 */
	class QSortable_StartEvent extends QEvent {
		const EventName = 'QSortable_Start';
	}

	/**
	 * This event is triggered during sorting.
	 */
	class QSortable_SortEvent extends QEvent {
		const EventName = 'QSortable_Sort';
	}

	/**
	 * This event is triggered during sorting, but only when the DOM position has
	 * 		changed.
	 */
	class QSortable_ChangeEvent extends QEvent {
		const EventName = 'QSortable_Change';
	}

	/**
	 * This event is triggered when sorting stops, but when the placeholder/helper
	 * 		is still available.
	 */
	class QSortable_BeforeStopEvent extends QEvent {
		const EventName = 'QSortable_BeforeStop';
	}

	/**
	 * This event is triggered when sorting has stopped.
	 */
	class QSortable_StopEvent extends QEvent {
		const EventName = 'QSortable_Stop';
	}

	/**
	 * This event is triggered when the user stopped sorting and the DOM position
	 * 		has changed.
	 */
	class QSortable_UpdateEvent extends QEvent {
		const EventName = 'QSortable_Update';
	}

	/**
	 * This event is triggered when a connected sortable list has received an item
	 * 		from another list.
	 */
	class QSortable_ReceiveEvent extends QEvent {
		const EventName = 'QSortable_Receive';
	}

	/**
	 * This event is triggered when a sortable item has been dragged out from the
	 * 		list and into another.
	 */
	class QSortable_RemoveEvent extends QEvent {
		const EventName = 'QSortable_Remove';
	}

	/**
	 * This event is triggered when a sortable item is moved into a connected
	 * 		list.
	 */
	class QSortable_OverEvent extends QEvent {
		const EventName = 'QSortable_Over';
	}

	/**
	 * This event is triggered when a sortable item is moved away from a connected
	 * 		list.
	 */
	class QSortable_OutEvent extends QEvent {
		const EventName = 'QSortable_Out';
	}

	/**
	 * This event is triggered when using connected lists, every connected list on
	 * 		drag start receives it.
	 */
	class QSortable_ActivateEvent extends QEvent {
		const EventName = 'QSortable_Activate';
	}

	/**
	 * This event is triggered when sorting was stopped, is propagated to all
	 * 		possible connected lists.
	 */
	class QSortable_DeactivateEvent extends QEvent {
		const EventName = 'QSortable_Deactivate';
	}


	/**
	 * @property boolean $Disabled Disables (true) or enables (false) the sortable. Can be set when
	 * 		initialising (first creating) the sortable.
	 * @property string $AppendTo Defines where the helper that moves with the mouse is being appended to
	 * 		during the drag (for example, to resolve overlap/zIndex issues).
	 * @property string $Axis If defined, the items can be dragged only horizontally or vertically.
	 * 		Possible values:'x', 'y'.
	 * @property mixed $Cancel Prevents sorting if you start on elements matching the selector.
	 * @property mixed $ConnectWith Takes a jQuery selector with items that also have sortables applied. If
	 * 		used, the sortable is now connected to the other one-way, so you can drag
	 * 		from this sortable to the other.
	 * @property mixed $Containment Constrains dragging to within the bounds of the specified element - can be
	 * 		a DOM element, 'parent', 'document', 'window', or a jQuery selector.
	 * @property string $Cursor Defines the cursor that is being shown while sorting.
	 * @property mixed $CursorAt Moves the sorting element or helper so the cursor always appears to drag
	 * 		from the same position. Coordinates can be given as a hash using a
	 * 		combination of one or two keys: { top, left, right, bottom }.
	 * @property integer $Delay Time in milliseconds to define when the sorting should start. It helps
	 * 		preventing unwanted drags when clicking on an element.
	 * @property integer $Distance Tolerance, in pixels, for when sorting should start. If specified, sorting
	 * 		will not start until after mouse is dragged beyond distance. Can be used to
	 * 		allow for clicks on elements within a handle.
	 * @property boolean $DropOnEmpty If false items from this sortable can't be dropped to an empty linked
	 * 		sortable.
	 * @property boolean $ForceHelperSize If true, forces the helper to have a size.
	 * @property boolean $ForcePlaceholderSize If true, forces the placeholder to have a size.
	 * @property array $Grid Snaps the sorting element or helper to a grid, every x and y pixels. Array
	 * 		values: [x, y]
	 * @property mixed $Handle Restricts sort start click to the specified element.
	 * @property mixed $Helper Allows for a helper element to be used for dragging display. The supplied
	 * 		function receives the event and the element being sorted, and should return
	 * 		a DOMElement to be used as a custom proxy helper. Possible values:
	 * 		'original', 'clone'
	 * @property mixed $Items Specifies which items inside the element should be sortable.
	 * @property QJsClosure $Opacity Defines the opacity of the helper while sorting. From 0.01 to 1
	 * @property string $Placeholder Class that gets applied to the otherwise white space.
	 * @property QJsClosure $Revert If set to true, the item will be reverted to its new DOM position with a
	 * 		smooth animation. Optionally, it can also be set to a number that controls
	 * 		the duration of the animation in ms.
	 * @property boolean $Scroll If set to true, the page scrolls when coming to an edge.
	 * @property integer $ScrollSensitivity Defines how near the mouse must be to an edge to start scrolling.
	 * @property integer $ScrollSpeed The speed at which the window should scroll once the mouse pointer gets
	 * 		within the scrollSensitivity distance.
	 * @property string $Tolerance This is the way the reordering behaves during drag. Possible values:
	 * 		'intersect', 'pointer'. In some setups, 'pointer' is more
	 * 		natural.
	 * 
	 * 
	 * intersect: draggable overlaps the droppable at least
	 * 		50%
	 * pointer: mouse pointer overlaps the droppable
	 * @property integer $ZIndex Z-index for element/helper while being sorted.
	 * @property QJsClosure $OnStart This event is triggered when sorting starts.
	 * @property QJsClosure $OnSort This event is triggered during sorting.
	 * @property QJsClosure $OnChange This event is triggered during sorting, but only when the DOM position has
	 * 		changed.
	 * @property QJsClosure $OnBeforeStop This event is triggered when sorting stops, but when the placeholder/helper
	 * 		is still available.
	 * @property QJsClosure $OnStop This event is triggered when sorting has stopped.
	 * @property QJsClosure $OnUpdate This event is triggered when the user stopped sorting and the DOM position
	 * 		has changed.
	 * @property QJsClosure $OnReceive This event is triggered when a connected sortable list has received an item
	 * 		from another list.
	 * @property QJsClosure $OnRemove This event is triggered when a sortable item has been dragged out from the
	 * 		list and into another.
	 * @property QJsClosure $OnOver This event is triggered when a sortable item is moved into a connected
	 * 		list.
	 * @property QJsClosure $OnOut This event is triggered when a sortable item is moved away from a connected
	 * 		list.
	 * @property QJsClosure $OnActivate This event is triggered when using connected lists, every connected list on
	 * 		drag start receives it.
	 * @property QJsClosure $OnDeactivate This event is triggered when sorting was stopped, is propagated to all
	 * 		possible connected lists.
	 */

	class QSortableBase extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var string */
		protected $strAppendTo = null;
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
		/** @var QJsClosure */
		protected $mixOpacity = null;
		/** @var string */
		protected $strPlaceholder = null;
		/** @var QJsClosure */
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
		/** @var QJsClosure */
		protected $mixOnStart = null;
		/** @var QJsClosure */
		protected $mixOnSort = null;
		/** @var QJsClosure */
		protected $mixOnChange = null;
		/** @var QJsClosure */
		protected $mixOnBeforeStop = null;
		/** @var QJsClosure */
		protected $mixOnStop = null;
		/** @var QJsClosure */
		protected $mixOnUpdate = null;
		/** @var QJsClosure */
		protected $mixOnReceive = null;
		/** @var QJsClosure */
		protected $mixOnRemove = null;
		/** @var QJsClosure */
		protected $mixOnOver = null;
		/** @var QJsClosure */
		protected $mixOnOut = null;
		/** @var QJsClosure */
		protected $mixOnActivate = null;
		/** @var QJsClosure */
		protected $mixOnDeactivate = null;

		/** @var array $custom_events Event Class Name => Event Property Name */
		protected static $custom_events = array(
			'QSortable_StartEvent' => 'OnStart',
			'QSortable_SortEvent' => 'OnSort',
			'QSortable_ChangeEvent' => 'OnChange',
			'QSortable_BeforeStopEvent' => 'OnBeforeStop',
			'QSortable_StopEvent' => 'OnStop',
			'QSortable_UpdateEvent' => 'OnUpdate',
			'QSortable_ReceiveEvent' => 'OnReceive',
			'QSortable_RemoveEvent' => 'OnRemove',
			'QSortable_OverEvent' => 'OnOver',
			'QSortable_OutEvent' => 'OnOut',
			'QSortable_ActivateEvent' => 'OnActivate',
			'QSortable_DeactivateEvent' => 'OnDeactivate',
		);
		
		protected function makeJsProperty($strProp, $strKey) {
			$objValue = $this->$strProp;
			if (null === $objValue) {
				return '';
			}

			return $strKey . ': ' . JavaScriptHelper::toJsObject($objValue) . ', ';
		}

		protected function makeJqOptions() {
			$strJqOptions = '';
			$strJqOptions .= $this->makeJsProperty('Disabled', 'disabled');
			$strJqOptions .= $this->makeJsProperty('AppendTo', 'appendTo');
			$strJqOptions .= $this->makeJsProperty('Axis', 'axis');
			$strJqOptions .= $this->makeJsProperty('Cancel', 'cancel');
			$strJqOptions .= $this->makeJsProperty('ConnectWith', 'connectWith');
			$strJqOptions .= $this->makeJsProperty('Containment', 'containment');
			$strJqOptions .= $this->makeJsProperty('Cursor', 'cursor');
			$strJqOptions .= $this->makeJsProperty('CursorAt', 'cursorAt');
			$strJqOptions .= $this->makeJsProperty('Delay', 'delay');
			$strJqOptions .= $this->makeJsProperty('Distance', 'distance');
			$strJqOptions .= $this->makeJsProperty('DropOnEmpty', 'dropOnEmpty');
			$strJqOptions .= $this->makeJsProperty('ForceHelperSize', 'forceHelperSize');
			$strJqOptions .= $this->makeJsProperty('ForcePlaceholderSize', 'forcePlaceholderSize');
			$strJqOptions .= $this->makeJsProperty('Grid', 'grid');
			$strJqOptions .= $this->makeJsProperty('Handle', 'handle');
			$strJqOptions .= $this->makeJsProperty('Helper', 'helper');
			$strJqOptions .= $this->makeJsProperty('Items', 'items');
			$strJqOptions .= $this->makeJsProperty('Opacity', 'opacity');
			$strJqOptions .= $this->makeJsProperty('Placeholder', 'placeholder');
			$strJqOptions .= $this->makeJsProperty('Revert', 'revert');
			$strJqOptions .= $this->makeJsProperty('Scroll', 'scroll');
			$strJqOptions .= $this->makeJsProperty('ScrollSensitivity', 'scrollSensitivity');
			$strJqOptions .= $this->makeJsProperty('ScrollSpeed', 'scrollSpeed');
			$strJqOptions .= $this->makeJsProperty('Tolerance', 'tolerance');
			$strJqOptions .= $this->makeJsProperty('ZIndex', 'zIndex');
			$strJqOptions .= $this->makeJsProperty('OnStart', 'start');
			$strJqOptions .= $this->makeJsProperty('OnSort', 'sort');
			$strJqOptions .= $this->makeJsProperty('OnChange', 'change');
			$strJqOptions .= $this->makeJsProperty('OnBeforeStop', 'beforeStop');
			$strJqOptions .= $this->makeJsProperty('OnStop', 'stop');
			$strJqOptions .= $this->makeJsProperty('OnUpdate', 'update');
			$strJqOptions .= $this->makeJsProperty('OnReceive', 'receive');
			$strJqOptions .= $this->makeJsProperty('OnRemove', 'remove');
			$strJqOptions .= $this->makeJsProperty('OnOver', 'over');
			$strJqOptions .= $this->makeJsProperty('OnOut', 'out');
			$strJqOptions .= $this->makeJsProperty('OnActivate', 'activate');
			$strJqOptions .= $this->makeJsProperty('OnDeactivate', 'deactivate');
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'sortable';
		}

		public function GetControlJavaScript() {
			return sprintf('jQuery("#%s").%s({%s})', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
		}

		public function GetEndScript() {
			return  $this->GetControlJavaScript() . '; ' . parent::GetEndScript();
		}

		/**
		 * Remove the sortable functionality completely. This will return the element
		 * back to its pre-init state.
		 */
		public function Destroy() {
			$args = array();
			$args[] = "destroy";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").sortable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable the sortable.
		 */
		public function Disable() {
			$args = array();
			$args[] = "disable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").sortable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable the sortable.
		 */
		public function Enable() {
			$args = array();
			$args[] = "enable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").sortable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Get or set any sortable option. If no value is specified, will act as a
		 * getter.
		 * @param $optionName
		 * @param $value
		 */
		public function Option($optionName, $value = null) {
			$args = array();
			$args[] = "option";
			$args[] = $optionName;
			if ($value !== null) {
				$args[] = $value;
			}

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").sortable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set multiple sortable options at once by providing an options object.
		 * @param $options
		 */
		public function Option1($options) {
			$args = array();
			$args[] = "option";
			$args[] = $options;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").sortable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Serializes the sortable's item id's into a form/ajax submittable string.
		 * Calling this method produces a hash that can be appended to any url to
		 * easily submit a new item order back to the server.
		 * It works by default by looking at the id of each item in the format
		 * 'setname_number', and it spits out a hash like
		 * "setname[]=number&amp;setname[]=number".
		 * You can also give in a option hash as second argument to custom define how
		 * the function works. The possible options are: 'key' (replaces part1[] with
		 * whatever you want), 'attribute' (test another attribute than 'id') and
		 * 'expression' (use your own regexp).
		 * If serialize returns an empty string, make sure the id attributes include
		 * an underscore.  They must be in the form: "set_number" For example, a 3
		 * element list with id attributes foo_1, foo_5, foo_2 will serialize to
		 * foo[]=1&amp;foo[]=5&amp;foo[]=2. You can use an underscore, equal sign or
		 * hyphen to separate the set and number.  For example foo=1 or foo-1 or foo_1
		 * all serialize to foo[]=1.
		 * @param $options
		 */
		public function Serialize($options = null) {
			$args = array();
			$args[] = "serialize";
			if ($options !== null) {
				$args[] = $options;
			}

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").sortable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Serializes the sortable's item id's into an array of string. If you have
		 * 
		 * 
		 * &lt;ul id=&quot;a_sortable&quot;&gt;&lt;br&gt;
		 * &lt;li id=&quot;hello&quot;&gt;Hello&lt;/li&gt;&lt;br&gt;
		 * &lt;li id=&quot;goodbye&quot;&gt;Good bye&lt;/li&gt;&lt;br&gt;
		 * &lt;/ul&gt;
		 * 
		 * and do
		 * 
		 * var result = $('#a_sortable').sortable('toArray');
		 * then
		 * 
		 * result[0] will contain &quot;hello&quot; and result[1] will contain
		 * &quot;goodbye&quot;.</p>
		 */
		public function ToArray() {
			$args = array();
			$args[] = "toArray";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").sortable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Refresh the sortable items. Custom trigger the reloading of all sortable
		 * items, causing new items to be recognized.
		 */
		public function Refresh() {
			$args = array();
			$args[] = "refresh";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").sortable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Refresh the cached positions of the sortables' items. Calling this method
		 * refreshes the cached item positions of all sortables. This is usually done
		 * automatically by the script and slows down performance. Use wisely.
		 */
		public function RefreshPositions() {
			$args = array();
			$args[] = "refreshPositions";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").sortable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Cancels a change in the current sortable and reverts it back to how it was
		 * before the current sort started. Useful in the stop and receive callback
		 * functions.
		 * If the sortable item is not being moved from one connected sortable to
		 * another:
		 * 
		 * $(this).sortable('cancel');
		 * will cancel the change.
		 * If the sortable item is being moved from one connected sortable to
		 * another:
		 * 
		 * $(ui.sender).sortable('cancel');
		 * will cancel the change. Useful in the 'receive' callback.
		 */
		public function Cancel() {
			$args = array();
			$args[] = "cancel";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").sortable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * returns the property name corresponding to the given custom event
		 * @param QEvent $objEvent the custom event
		 * @return the property name corresponding to $objEvent
		 */
		protected function getCustomEventPropertyName(QEvent $objEvent) {
			$strEventClass = get_class($objEvent);
			if (array_key_exists($strEventClass, QSortable::$custom_events))
				return QSortable::$custom_events[$strEventClass];
			return null;
		}

		/**
		 * Wraps $objAction into an object (typically a QJsClosure) that can be assigned to the corresponding Event
		 * property (e.g. OnFocus)
		 * @param QEvent $objEvent
		 * @param QAction $objAction
		 * @return mixed the wrapped object
		 */
		protected function createEventWrapper(QEvent $objEvent, QAction $objAction) {
			$objAction->Event = $objEvent;
			return new QJsClosure($objAction->RenderScript($this));
		}

		/**
		 * If $objEvent is one of the custom events (as determined by getCustomEventPropertyName() method)
		 * the corresponding JQuery event is used and if needed a no-script action is added. Otherwise the normal
		 * QCubed AddAction is performed.
		 * @param QEvent  $objEvent
		 * @param QAction $objAction
		 */
		public function AddAction($objEvent, $objAction) {
			$strEventName = $this->getCustomEventPropertyName($objEvent);
			if ($strEventName) {
				$this->$strEventName = $this->createEventWrapper($objEvent, $objAction);
				if ($objAction instanceof QAjaxAction) {
					$objAction = new QNoScriptAjaxAction($objAction);
					parent::AddAction($objEvent, $objAction);
				} else if (!($objAction instanceof QJavaScriptAction)) {
					throw new Exception('handling of "' . get_class($objAction) . '" actions with "' . get_class($objEvent) . '" events not yet implemented');
				}
			} else {
				parent::AddAction($objEvent, $objAction);
			}
		}

		public function __get($strName) {
			switch ($strName) {
				case 'Disabled': return $this->blnDisabled;
				case 'AppendTo': return $this->strAppendTo;
				case 'Axis': return $this->strAxis;
				case 'Cancel': return $this->mixCancel;
				case 'ConnectWith': return $this->mixConnectWith;
				case 'Containment': return $this->mixContainment;
				case 'Cursor': return $this->strCursor;
				case 'CursorAt': return $this->mixCursorAt;
				case 'Delay': return $this->intDelay;
				case 'Distance': return $this->intDistance;
				case 'DropOnEmpty': return $this->blnDropOnEmpty;
				case 'ForceHelperSize': return $this->blnForceHelperSize;
				case 'ForcePlaceholderSize': return $this->blnForcePlaceholderSize;
				case 'Grid': return $this->arrGrid;
				case 'Handle': return $this->mixHandle;
				case 'Helper': return $this->mixHelper;
				case 'Items': return $this->mixItems;
				case 'Opacity': return $this->mixOpacity;
				case 'Placeholder': return $this->strPlaceholder;
				case 'Revert': return $this->mixRevert;
				case 'Scroll': return $this->blnScroll;
				case 'ScrollSensitivity': return $this->intScrollSensitivity;
				case 'ScrollSpeed': return $this->intScrollSpeed;
				case 'Tolerance': return $this->strTolerance;
				case 'ZIndex': return $this->intZIndex;
				case 'OnStart': return $this->mixOnStart;
				case 'OnSort': return $this->mixOnSort;
				case 'OnChange': return $this->mixOnChange;
				case 'OnBeforeStop': return $this->mixOnBeforeStop;
				case 'OnStop': return $this->mixOnStop;
				case 'OnUpdate': return $this->mixOnUpdate;
				case 'OnReceive': return $this->mixOnReceive;
				case 'OnRemove': return $this->mixOnRemove;
				case 'OnOver': return $this->mixOnOver;
				case 'OnOut': return $this->mixOnOut;
				case 'OnActivate': return $this->mixOnActivate;
				case 'OnDeactivate': return $this->mixOnDeactivate;
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
			$this->blnModified = true;

			switch ($strName) {
				case 'Disabled':
					try {
						$this->blnDisabled = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AppendTo':
					try {
						$this->strAppendTo = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Axis':
					try {
						$this->strAxis = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Cancel':
					$this->mixCancel = $mixValue;
					break;

				case 'ConnectWith':
					$this->mixConnectWith = $mixValue;
					break;

				case 'Containment':
					$this->mixContainment = $mixValue;
					break;

				case 'Cursor':
					try {
						$this->strCursor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CursorAt':
					$this->mixCursorAt = $mixValue;
					break;

				case 'Delay':
					try {
						$this->intDelay = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Distance':
					try {
						$this->intDistance = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DropOnEmpty':
					try {
						$this->blnDropOnEmpty = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ForceHelperSize':
					try {
						$this->blnForceHelperSize = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ForcePlaceholderSize':
					try {
						$this->blnForcePlaceholderSize = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Grid':
					try {
						$this->arrGrid = QType::Cast($mixValue, QType::ArrayType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Handle':
					$this->mixHandle = $mixValue;
					break;

				case 'Helper':
					$this->mixHelper = $mixValue;
					break;

				case 'Items':
					$this->mixItems = $mixValue;
					break;

				case 'Opacity':
					try {
						$this->mixOpacity = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Placeholder':
					try {
						$this->strPlaceholder = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Revert':
					try {
						$this->mixRevert = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Scroll':
					try {
						$this->blnScroll = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ScrollSensitivity':
					try {
						$this->intScrollSensitivity = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ScrollSpeed':
					try {
						$this->intScrollSpeed = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tolerance':
					try {
						$this->strTolerance = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ZIndex':
					try {
						$this->intZIndex = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnStart':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnStart = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnSort':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnSort = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnChange':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnChange = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnBeforeStop':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnBeforeStop = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnStop':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnStop = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnUpdate':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnUpdate = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnReceive':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnReceive = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnRemove':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnRemove = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnOver':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnOver = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnOut':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnOut = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnActivate':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnActivate = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnDeactivate':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnDeactivate = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

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
	}

?>
