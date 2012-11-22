<?php
	/* Custom event classes for this control */
	/**
	 * 
	 */
	class QDateRangePicker_CloseEvent extends QEvent {
		const EventName = 'QDateRangePicker_Close';
	}


	/**
	 * @property array $PresetRanges 
	 * @property array $Presets 
	 * @property string $RangeStartTitle 
	 * @property string $RangeEndTitle 
	 * @property string $DoneButtonText 
	 * @property string $PrevLinkText 
	 * @property string $NextLinkText 
	 * @property QDateTime $EarliestDate 
	 * @property QDateTime $LatestDate 
	 * @property boolean $ConstrainDates 
	 * @property string $RangeSplitter 
	 * @property string $JqDateFormat 
	 * @property boolean $CloseOnSelect 
	 * @property boolean $Arrows 
	 * @property QJsClosure $OnClose 
	 */

	class QDateRangePickerBase extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var array */
		protected $arrPresetRanges;
		/** @var array */
		protected $arrPresets;
		/** @var string */
		protected $strRangeStartTitle;
		/** @var string */
		protected $strRangeEndTitle;
		/** @var string */
		protected $strDoneButtonText;
		/** @var string */
		protected $strPrevLinkText;
		/** @var string */
		protected $strNextLinkText;
		/** @var QDateTime */
		protected $dttEarliestDate;
		/** @var QDateTime */
		protected $dttLatestDate;
		/** @var boolean */
		protected $blnConstrainDates = null;
		/** @var string */
		protected $strRangeSplitter;
		/** @var string */
		protected $strJqDateFormat;
		/** @var boolean */
		protected $blnCloseOnSelect = null;
		/** @var boolean */
		protected $blnArrows = null;
		/** @var QJsClosure */
		protected $mixOnClose = null;

		/** @var array $custom_events Event Class Name => Event Property Name */
		protected static $custom_events = array(
			'QDateRangePicker_CloseEvent' => 'OnClose',
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
			$strJqOptions .= $this->makeJsProperty('PresetRanges', 'presetRanges');
			$strJqOptions .= $this->makeJsProperty('Presets', 'presets');
			$strJqOptions .= $this->makeJsProperty('RangeStartTitle', 'rangeStartTitle');
			$strJqOptions .= $this->makeJsProperty('RangeEndTitle', 'rangeEndTitle');
			$strJqOptions .= $this->makeJsProperty('DoneButtonText', 'doneButtonText');
			$strJqOptions .= $this->makeJsProperty('PrevLinkText', 'prevLinkText');
			$strJqOptions .= $this->makeJsProperty('NextLinkText', 'nextLinkText');
			$strJqOptions .= $this->makeJsProperty('EarliestDate', 'earliestDate');
			$strJqOptions .= $this->makeJsProperty('LatestDate', 'latestDate');
			$strJqOptions .= $this->makeJsProperty('ConstrainDates', 'constrainDates');
			$strJqOptions .= $this->makeJsProperty('RangeSplitter', 'rangeSplitter');
			$strJqOptions .= $this->makeJsProperty('JqDateFormat', 'dateFormat');
			$strJqOptions .= $this->makeJsProperty('CloseOnSelect', 'closeOnSelect');
			$strJqOptions .= $this->makeJsProperty('Arrows', 'arrows');
			$strJqOptions .= $this->makeJsProperty('OnClose', 'onClose');
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
		}

		public function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'daterangepicker';
		}

		public function GetControlJavaScript() {
			return sprintf('jQuery("#%s").%s({%s})', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
		}

		public function GetEndScript() {
			return  $this->GetControlJavaScript() . '; ' . parent::GetEndScript();
		}

		/**
		 * returns the property name corresponding to the given custom event
		 * @param QEvent $objEvent the custom event
		 * @return the property name corresponding to $objEvent
		 */
		protected function getCustomEventPropertyName(QEvent $objEvent) {
			$strEventClass = get_class($objEvent);
			if (array_key_exists($strEventClass, QDateRangePicker::$custom_events))
				return QDateRangePicker::$custom_events[$strEventClass];
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
				case 'PresetRanges': return $this->arrPresetRanges;
				case 'Presets': return $this->arrPresets;
				case 'RangeStartTitle': return $this->strRangeStartTitle;
				case 'RangeEndTitle': return $this->strRangeEndTitle;
				case 'DoneButtonText': return $this->strDoneButtonText;
				case 'PrevLinkText': return $this->strPrevLinkText;
				case 'NextLinkText': return $this->strNextLinkText;
				case 'EarliestDate': return $this->dttEarliestDate;
				case 'LatestDate': return $this->dttLatestDate;
				case 'ConstrainDates': return $this->blnConstrainDates;
				case 'RangeSplitter': return $this->strRangeSplitter;
				case 'JqDateFormat': return $this->strJqDateFormat;
				case 'CloseOnSelect': return $this->blnCloseOnSelect;
				case 'Arrows': return $this->blnArrows;
				case 'OnClose': return $this->mixOnClose;
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
				case 'PresetRanges':
					try {
						$this->arrPresetRanges = QType::Cast($mixValue, QType::ArrayType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Presets':
					try {
						$this->arrPresets = QType::Cast($mixValue, QType::ArrayType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'RangeStartTitle':
					try {
						$this->strRangeStartTitle = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'RangeEndTitle':
					try {
						$this->strRangeEndTitle = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DoneButtonText':
					try {
						$this->strDoneButtonText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PrevLinkText':
					try {
						$this->strPrevLinkText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'NextLinkText':
					try {
						$this->strNextLinkText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'EarliestDate':
					try {
						$this->dttEarliestDate = QType::Cast($mixValue, QType::DateTime);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'LatestDate':
					try {
						$this->dttLatestDate = QType::Cast($mixValue, QType::DateTime);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ConstrainDates':
					try {
						$this->blnConstrainDates = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'RangeSplitter':
					try {
						$this->strRangeSplitter = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'JqDateFormat':
					try {
						$this->strJqDateFormat = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CloseOnSelect':
					try {
						$this->blnCloseOnSelect = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Arrows':
					try {
						$this->blnArrows = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnClose':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnClose = QType::Cast($mixValue, 'QJsClosure');
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
