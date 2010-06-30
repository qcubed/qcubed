<?php
	/**
	 * @property boolean $Disabled Disables (true) or enables (false) the draggable. Can be set when
	 * 		initialising (first creating) the draggable.
	 * @property boolean $AddClasses If set to false, will prevent the ui-draggable class from being added. This
	 * 		may be desired as a performance optimization when calling .draggable() init
	 * 		on many hundreds of elements.
	 * @property mixed $AppendTo The element passed to or selected by the appendTo option will be used as
	 * 		the draggable helper's container during dragging. By default, the helper is
	 * 		appended to the same container as the draggable.
	 * @property string $Axis Constrains dragging to either the horizontal (x) or vertical (y) axis.
	 * 		Possible values: 'x', 'y'.
	 * @property QJsClosure $Cancel Prevents dragging from starting on specified elements.
	 * @property QJsClosure $ConnectToSortable Allows the draggable to be dropped onto the specified sortables. If this
	 * 		option is used (helper must be set to 'clone' in order to work flawlessly),
	 * 		a draggable can be dropped onto a sortable list and then becomes part of
	 * 		it.
	 * Note: Specifying this option as an array of selectors has been removed.
	 * @property mixed $Containment Constrains dragging to within the bounds of the specified element or
	 * 		region. Possible string values: 'parent', 'document', 'window', [x1, y1,
	 * 		x2, y2].
	 * @property string $Cursor The css cursor during the drag operation.
	 * @property mixed $CursorAt Moves the dragging helper so the cursor always appears to drag from the
	 * 		same position. Coordinates can be given as a hash using a combination of
	 * 		one or two keys: { top, left, right, bottom }.
	 * @property integer $Delay Time in milliseconds after mousedown until dragging should start. This
	 * 		option can be used to prevent unwanted drags when clicking on an element.
	 * @property integer $Distance Distance in pixels after mousedown the mouse must move before dragging
	 * 		should start. This option can be used to prevent unwanted drags when
	 * 		clicking on an element.
	 * @property array $Grid Snaps the dragging helper to a grid, every x and y pixels. Array values:
	 * 		[x, y]
	 * @property mixed $Handle If specified, restricts drag start click to the specified element(s).
	 * @property mixed $Helper Allows for a helper element to be used for dragging display. Possible
	 * 		values: 'original', 'clone', Function. If a function is specified, it must
	 * 		return a DOMElement.
	 * @property mixed $IframeFix Prevent iframes from capturing the mousemove events during a drag. Useful
	 * 		in combination with cursorAt, or in any case, if the mouse cursor is not
	 * 		over the helper. If set to true, transparent overlays will be placed over
	 * 		all iframes on the page. If a selector is supplied, the matched iframes
	 * 		will have an overlay placed over them.
	 * @property QJsClosure $Opacity Opacity for the helper while being dragged.
	 * @property boolean $RefreshPositions If set to true, all droppable positions are calculated on every mousemove.
	 * 		Caution: This solves issues on highly dynamic pages, but dramatically
	 * 		decreases performance.
	 * @property mixed $Revert If set to true, the element will return to its start position when dragging
	 * 		stops. Possible string values: 'valid', 'invalid'. If set to invalid,
	 * 		revert will only occur if the draggable has not been dropped on a
	 * 		droppable. For valid, it's the other way around.
	 * @property integer $RevertDuration The duration of the revert animation, in milliseconds. Ignored if revert is
	 * 		false.
	 * @property string $Scope Used to group sets of draggable and droppable items, in addition to
	 * 		droppable's accept option. A draggable with the same scope value as a
	 * 		droppable will be accepted by the droppable.
	 * @property boolean $Scroll If set to true, container auto-scrolls while dragging.
	 * @property integer $ScrollSensitivity Distance in pixels from the edge of the viewport after which the viewport
	 * 		should scroll. Distance is relative to pointer, not the draggable.
	 * @property integer $ScrollSpeed The speed at which the window should scroll once the mouse pointer gets
	 * 		within the scrollSensitivity distance.
	 * @property mixed $Snap If set to a selector or to true (equivalent to '.ui-draggable'), the
	 * 		draggable will snap to the edges of the selected elements when near an edge
	 * 		of the element.
	 * @property string $SnapMode Determines which edges of snap elements the draggable will snap to. Ignored
	 * 		if snap is false. Possible values: 'inner', 'outer', 'both'
	 * @property integer $SnapTolerance The distance in pixels from the snap element edges at which snapping should
	 * 		occur. Ignored if snap is false.
	 * @property QJsClosure $Stack Controls the z-Index of the set of elements that match the selector, always
	 * 		brings to front the dragged item. Very useful in things like window
	 * 		managers.
	 * @property integer $ZIndex z-index for the helper while being dragged.
	 * @property QJsClosure $OnStart This event is triggered when dragging starts.
	 * @property QJsClosure $OnDrag This event is triggered when the mouse is moved during the dragging.
	 * @property QJsClosure $OnStop This event is triggered when dragging stops.
	 */

	class QDraggableBase extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var boolean */
		protected $blnAddClasses = null;
		/** @var mixed */
		protected $mixAppendTo = null;
		/** @var string */
		protected $strAxis = null;
		/** @var QJsClosure */
		protected $mixCancel = null;
		/** @var QJsClosure */
		protected $mixConnectToSortable = null;
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
		/** @var array */
		protected $arrGrid = null;
		/** @var mixed */
		protected $mixHandle = null;
		/** @var mixed */
		protected $mixHelper = null;
		/** @var mixed */
		protected $mixIframeFix = null;
		/** @var QJsClosure */
		protected $mixOpacity = null;
		/** @var boolean */
		protected $blnRefreshPositions = null;
		/** @var mixed */
		protected $mixRevert = null;
		/** @var integer */
		protected $intRevertDuration = null;
		/** @var string */
		protected $strScope = null;
		/** @var boolean */
		protected $blnScroll = null;
		/** @var integer */
		protected $intScrollSensitivity = null;
		/** @var integer */
		protected $intScrollSpeed = null;
		/** @var mixed */
		protected $mixSnap = null;
		/** @var string */
		protected $strSnapMode = null;
		/** @var integer */
		protected $intSnapTolerance = null;
		/** @var QJsClosure */
		protected $mixStack = null;
		/** @var integer */
		protected $intZIndex = null;
		/** @var QJsClosure */
		protected $mixOnStart = null;
		/** @var QJsClosure */
		protected $mixOnDrag = null;
		/** @var QJsClosure */
		protected $mixOnStop = null;

		protected function makeJsProperty($strProp, $strKey, $strQuote = "'") {
			$objValue = $this->$strProp;
			if (null === $objValue) {
				return '';
			}

			return $strKey . ': ' . JavaScriptHelper::toJson($objValue, $strQuote) . ', ';
		}

		protected function makeJqOptions() {
			$strJson = '{';
			$strJson .= $this->makeJsProperty('Disabled', 'disabled');
			$strJson .= $this->makeJsProperty('AddClasses', 'addClasses');
			$strJson .= $this->makeJsProperty('AppendTo', 'appendTo');
			$strJson .= $this->makeJsProperty('Axis', 'axis');
			$strJson .= $this->makeJsProperty('Cancel', 'cancel');
			$strJson .= $this->makeJsProperty('ConnectToSortable', 'connectToSortable');
			$strJson .= $this->makeJsProperty('Containment', 'containment');
			$strJson .= $this->makeJsProperty('Cursor', 'cursor');
			$strJson .= $this->makeJsProperty('CursorAt', 'cursorAt');
			$strJson .= $this->makeJsProperty('Delay', 'delay');
			$strJson .= $this->makeJsProperty('Distance', 'distance');
			$strJson .= $this->makeJsProperty('Grid', 'grid');
			$strJson .= $this->makeJsProperty('Handle', 'handle');
			$strJson .= $this->makeJsProperty('Helper', 'helper');
			$strJson .= $this->makeJsProperty('IframeFix', 'iframeFix');
			$strJson .= $this->makeJsProperty('Opacity', 'opacity');
			$strJson .= $this->makeJsProperty('RefreshPositions', 'refreshPositions');
			$strJson .= $this->makeJsProperty('Revert', 'revert');
			$strJson .= $this->makeJsProperty('RevertDuration', 'revertDuration');
			$strJson .= $this->makeJsProperty('Scope', 'scope');
			$strJson .= $this->makeJsProperty('Scroll', 'scroll');
			$strJson .= $this->makeJsProperty('ScrollSensitivity', 'scrollSensitivity');
			$strJson .= $this->makeJsProperty('ScrollSpeed', 'scrollSpeed');
			$strJson .= $this->makeJsProperty('Snap', 'snap');
			$strJson .= $this->makeJsProperty('SnapMode', 'snapMode');
			$strJson .= $this->makeJsProperty('SnapTolerance', 'snapTolerance');
			$strJson .= $this->makeJsProperty('Stack', 'stack');
			$strJson .= $this->makeJsProperty('ZIndex', 'zIndex');
			$strJson .= $this->makeJsProperty('OnStart', 'start');
			$strJson .= $this->makeJsProperty('OnDrag', 'drag');
			$strJson .= $this->makeJsProperty('OnStop', 'stop');
			return $strJson.'}';
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'draggable';
		}

		public function GetControlHtml() {
			$strToReturn = parent::GetControlHtml();

			$strJs = sprintf('jQuery("#%s").%s(%s)', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
			QApplication::ExecuteJavaScript($strJs);
			return $strToReturn;
		}

		/**
		 * Remove the draggable functionality completely. This will return the element
		 * back to its pre-init state.
		 */
		public function Destroy() {
			$args = array();
			$args[] = "destroy";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").draggable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable the draggable.
		 */
		public function Disable() {
			$args = array();
			$args[] = "disable";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").draggable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable the draggable.
		 */
		public function Enable() {
			$args = array();
			$args[] = "enable";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").draggable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Get or set any draggable option. If no value is specified, will act as a
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

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").draggable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set multiple draggable options at once by providing an options object.
		 * @param $options
		 */
		public function Option1($options) {
			$args = array();
			$args[] = "option";
			$args[] = $options;

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").draggable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'Disabled': return $this->blnDisabled;
				case 'AddClasses': return $this->blnAddClasses;
				case 'AppendTo': return $this->mixAppendTo;
				case 'Axis': return $this->strAxis;
				case 'Cancel': return $this->mixCancel;
				case 'ConnectToSortable': return $this->mixConnectToSortable;
				case 'Containment': return $this->mixContainment;
				case 'Cursor': return $this->strCursor;
				case 'CursorAt': return $this->mixCursorAt;
				case 'Delay': return $this->intDelay;
				case 'Distance': return $this->intDistance;
				case 'Grid': return $this->arrGrid;
				case 'Handle': return $this->mixHandle;
				case 'Helper': return $this->mixHelper;
				case 'IframeFix': return $this->mixIframeFix;
				case 'Opacity': return $this->mixOpacity;
				case 'RefreshPositions': return $this->blnRefreshPositions;
				case 'Revert': return $this->mixRevert;
				case 'RevertDuration': return $this->intRevertDuration;
				case 'Scope': return $this->strScope;
				case 'Scroll': return $this->blnScroll;
				case 'ScrollSensitivity': return $this->intScrollSensitivity;
				case 'ScrollSpeed': return $this->intScrollSpeed;
				case 'Snap': return $this->mixSnap;
				case 'SnapMode': return $this->strSnapMode;
				case 'SnapTolerance': return $this->intSnapTolerance;
				case 'Stack': return $this->mixStack;
				case 'ZIndex': return $this->intZIndex;
				case 'OnStart': return $this->mixOnStart;
				case 'OnDrag': return $this->mixOnDrag;
				case 'OnStop': return $this->mixOnStop;
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

				case 'AddClasses':
					try {
						$this->blnAddClasses = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AppendTo':
					$this->mixAppendTo = $mixValue;
					break;

				case 'Axis':
					try {
						$this->strAxis = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Cancel':
					try {
						$this->mixCancel = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ConnectToSortable':
					try {
						$this->mixConnectToSortable = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

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

				case 'IframeFix':
					$this->mixIframeFix = $mixValue;
					break;

				case 'Opacity':
					try {
						$this->mixOpacity = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'RefreshPositions':
					try {
						$this->blnRefreshPositions = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Revert':
					$this->mixRevert = $mixValue;
					break;

				case 'RevertDuration':
					try {
						$this->intRevertDuration = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Scope':
					try {
						$this->strScope = QType::Cast($mixValue, QType::String);
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

				case 'Snap':
					$this->mixSnap = $mixValue;
					break;

				case 'SnapMode':
					try {
						$this->strSnapMode = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SnapTolerance':
					try {
						$this->intSnapTolerance = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Stack':
					try {
						$this->mixStack = QType::Cast($mixValue, 'QJsClosure');
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
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnStart = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnDrag':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnDrag = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnStop':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnStop = QType::Cast($mixValue, 'QJsClosure');
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
