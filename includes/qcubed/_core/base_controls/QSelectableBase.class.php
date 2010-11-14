<?php
	/* Custom event classes for this control */
	/**
	 * This event is triggered at the end of the select operation, on each element
	 * 		added to the selection.
	 */
	class QSelectable_SelectedEvent extends QEvent {
		const EventName = 'QSelectable_Selected';
	}

	/**
	 * This event is triggered during the select operation, on each element added
	 * 		to the selection.
	 */
	class QSelectable_SelectingEvent extends QEvent {
		const EventName = 'QSelectable_Selecting';
	}

	/**
	 * This event is triggered at the beginning of the select operation.
	 */
	class QSelectable_StartEvent extends QEvent {
		const EventName = 'QSelectable_Start';
	}

	/**
	 * This event is triggered at the end of the select operation.
	 */
	class QSelectable_StopEvent extends QEvent {
		const EventName = 'QSelectable_Stop';
	}

	/**
	 * This event is triggered at the end of the select operation, on each element
	 * 		removed from the selection.
	 */
	class QSelectable_UnselectedEvent extends QEvent {
		const EventName = 'QSelectable_Unselected';
	}

	/**
	 * This event is triggered during the select operation, on each element
	 * 		removed from the selection.
	 */
	class QSelectable_UnselectingEvent extends QEvent {
		const EventName = 'QSelectable_Unselecting';
	}


	/**
	 * @property boolean $Disabled Disables (true) or enables (false) the selectable. Can be set when
	 * 		initialising (first creating) the selectable.
	 * @property boolean $AutoRefresh This determines whether to refresh (recalculate) the position and size of
	 * 		each selectee at the beginning of each select operation. If you have many
	 * 		many items, you may want to set this to false and call the refresh method
	 * 		manually.
	 * @property mixed $Cancel Prevents selecting if you start on elements matching the selector.
	 * @property integer $Delay Time in milliseconds to define when the selecting should start. It helps
	 * 		preventing unwanted selections when clicking on an element.
	 * @property integer $Distance Tolerance, in pixels, for when selecting should start. If specified,
	 * 		selecting will not start until after mouse is dragged beyond distance.
	 * @property mixed $Filter The matching child elements will be made selectees (able to be selected).
	 * @property string $Tolerance Possible values: 'touch', 'fit'.
	 * 
	 * 
	 * fit: draggable overlaps the droppable
	 * 		entirely
	 * touch: draggable overlaps the droppable any amount
	 * @property QJsClosure $OnSelected This event is triggered at the end of the select operation, on each element
	 * 		added to the selection.
	 * @property QJsClosure $OnSelecting This event is triggered during the select operation, on each element added
	 * 		to the selection.
	 * @property QJsClosure $OnStart This event is triggered at the beginning of the select operation.
	 * @property QJsClosure $OnStop This event is triggered at the end of the select operation.
	 * @property QJsClosure $OnUnselected This event is triggered at the end of the select operation, on each element
	 * 		removed from the selection.
	 * @property QJsClosure $OnUnselecting This event is triggered during the select operation, on each element
	 * 		removed from the selection.
	 */

	class QSelectableBase extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var boolean */
		protected $blnAutoRefresh = null;
		/** @var mixed */
		protected $mixCancel = null;
		/** @var integer */
		protected $intDelay;
		/** @var integer */
		protected $intDistance;
		/** @var mixed */
		protected $mixFilter = null;
		/** @var string */
		protected $strTolerance = null;
		/** @var QJsClosure */
		protected $mixOnSelected = null;
		/** @var QJsClosure */
		protected $mixOnSelecting = null;
		/** @var QJsClosure */
		protected $mixOnStart = null;
		/** @var QJsClosure */
		protected $mixOnStop = null;
		/** @var QJsClosure */
		protected $mixOnUnselected = null;
		/** @var QJsClosure */
		protected $mixOnUnselecting = null;

		/** @var array $custom_events Event Class Name => Event Property Name */
		protected static $custom_events = array(
			'QSelectable_SelectedEvent' => 'OnSelected',
			'QSelectable_SelectingEvent' => 'OnSelecting',
			'QSelectable_StartEvent' => 'OnStart',
			'QSelectable_StopEvent' => 'OnStop',
			'QSelectable_UnselectedEvent' => 'OnUnselected',
			'QSelectable_UnselectingEvent' => 'OnUnselecting',
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
			$strJqOptions .= $this->makeJsProperty('AutoRefresh', 'autoRefresh');
			$strJqOptions .= $this->makeJsProperty('Cancel', 'cancel');
			$strJqOptions .= $this->makeJsProperty('Delay', 'delay');
			$strJqOptions .= $this->makeJsProperty('Distance', 'distance');
			$strJqOptions .= $this->makeJsProperty('Filter', 'filter');
			$strJqOptions .= $this->makeJsProperty('Tolerance', 'tolerance');
			$strJqOptions .= $this->makeJsProperty('OnSelected', 'selected');
			$strJqOptions .= $this->makeJsProperty('OnSelecting', 'selecting');
			$strJqOptions .= $this->makeJsProperty('OnStart', 'start');
			$strJqOptions .= $this->makeJsProperty('OnStop', 'stop');
			$strJqOptions .= $this->makeJsProperty('OnUnselected', 'unselected');
			$strJqOptions .= $this->makeJsProperty('OnUnselecting', 'unselecting');
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'selectable';
		}

		public function GetControlJavaScript() {
			return sprintf('jQuery("#%s").%s({%s})', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
		}

		public function GetEndScript() {
			return  $this->GetControlJavaScript() . '; ' . parent::GetEndScript();
		}

		/**
		 * Remove the selectable functionality completely. This will return the
		 * element back to its pre-init state.
		 */
		public function Destroy() {
			$args = array();
			$args[] = "destroy";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable the selectable.
		 */
		public function Disable() {
			$args = array();
			$args[] = "disable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable the selectable.
		 */
		public function Enable() {
			$args = array();
			$args[] = "enable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Get or set any selectable option. If no value is specified, will act as a
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
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set multiple selectable options at once by providing an options object.
		 * @param $options
		 */
		public function Option1($options) {
			$args = array();
			$args[] = "option";
			$args[] = $options;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Refresh the position and size of each selectee element. This method can be
		 * used to manually recalculate the position and size of each selectee
		 * element. Very useful if autoRefresh is set to false.
		 */
		public function Refresh() {
			$args = array();
			$args[] = "refresh";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").selectable(%s)', 
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
			if (array_key_exists($strEventClass, QSelectable::$custom_events))
				return QSelectable::$custom_events[$strEventClass];
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
				case 'AutoRefresh': return $this->blnAutoRefresh;
				case 'Cancel': return $this->mixCancel;
				case 'Delay': return $this->intDelay;
				case 'Distance': return $this->intDistance;
				case 'Filter': return $this->mixFilter;
				case 'Tolerance': return $this->strTolerance;
				case 'OnSelected': return $this->mixOnSelected;
				case 'OnSelecting': return $this->mixOnSelecting;
				case 'OnStart': return $this->mixOnStart;
				case 'OnStop': return $this->mixOnStop;
				case 'OnUnselected': return $this->mixOnUnselected;
				case 'OnUnselecting': return $this->mixOnUnselecting;
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

				case 'AutoRefresh':
					try {
						$this->blnAutoRefresh = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Cancel':
					$this->mixCancel = $mixValue;
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

				case 'Filter':
					$this->mixFilter = $mixValue;
					break;

				case 'Tolerance':
					try {
						$this->strTolerance = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnSelected':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnSelected = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnSelecting':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnSelecting = QType::Cast($mixValue, 'QJsClosure');
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

				case 'OnUnselected':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnUnselected = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnUnselecting':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnUnselecting = QType::Cast($mixValue, 'QJsClosure');
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
