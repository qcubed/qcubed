<?php
	/**
	 * This file contains the QCalendar class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render a pop-up, modeless calendar control
	 * that can be used to let the user pick a date.
	 *
	 * @package Controls
	 * @property QDateTime    MinDate
	 * @property QDateTime    MaxDate
	 * @property QDateTime    DefaultDate
	 * @property int          FirstDay
	 * @property int|int[]    NumberOfMonths
	 * @property boolean      AutoSize
	 * @property boolean      GotoCurrent
	 * @property boolean      IsRTL
	 * @property string       DateFormat
	 * @property-write string DateTimeFormat
	 * @property string       JqDateFormat
	 * @property boolean      ShowButtonPanel
	 */
	class QCalendar extends QDateTimeTextBox {
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		protected $datMinDate = null;
		protected $datMaxDate = null;
		protected $datDefaultDate = null;
		protected $intFirstDay = null;
		protected $mixNumberOfMonths = null;
		protected $blnAutoSize = false;
		protected $blnGotoCurrent = false;
		protected $blnIsRTL = false;
		protected $blnModified = false;
		protected $strJqDateFormat = 'M d yy';
		protected $blnShowButtonPanel = true;

		// map the JQuery datepicker format specs to QCubed QDateTime format specs.
		//QCubed	JQuery		PHP	Description
		//-------------------------------------------------
		//MMMM	    MM			F	Month as full name (e.g., March)
		//MMM	    M			M	Month as three-letters (e.g., Mar)
		//MM	    mm			m	Month as an integer with leading zero (e.g., 03)
		//M	        m			n	Month as an integer (e.g., 3)
		//DDDD	    DD			l	Day of week as full name (e.g., Wednesday)
		//DDD	    D			D	Day of week as three-letters (e.g., Wed)
		//DD	    dd			d	Day as an integer with leading zero (e.g., 02)
		//D	        d			j	Day as an integer (e.g., 2)
		//YYYY	    yy			Y	Year as a four-digit integer (e.g., 1977)
		//YY	    y			y	Year as a two-digit integer (e.g., 77)
		/** @var array QCubed to JQuery Map of date formates */
		static private $mapQC2JQ = array(
			'MMMM' => 'MM',
			'MMM'  => 'M',
			'MM'   => 'mm',
			'M'    => 'm',
			'DDDD' => 'DD',
			'DDD'  => 'D',
			'DD'   => 'dd',
			'D'    => 'd',
			'YYYY' => 'yy',
			'YY'   => 'y',
		);
		static private $mapJQ2QC = null;

		static public function qcFrmt($jqFrmt) {
			if (!QCalendar::$mapJQ2QC) {
				QCalendar::$mapJQ2QC = array_flip(QCalendar::$mapQC2JQ);
			}

			return strtr($jqFrmt, QCalendar::$mapJQ2QC);
		}

		static public function jqFrmt($qcFrmt) {
			return strtr($qcFrmt, QCalendar::$mapQC2JQ);
		}

		/**
		 * @deprecated Use JavaScriptHelper::toJsObject
		 */
		static public function jsDate(QDateTime $dt) {
			return JavaScriptHelper::toJsObject($dt);
		}

		/**
		 * Validates the control (default: returns true)
		 *
		 * @return bool
		 */
		public function Validate() {
			return true;
		}

		protected function makeJsProperty($strProp, $strKey) {
			$objValue = $this->$strProp;
			if (null === $objValue) {
				return '';
			}

			return $strKey . ': ' . JavaScriptHelper::toJsObject($objValue) . ', ';
		}

		/**
		 * Returns the HTML for the control
		 *
		 * @return string The HTML which can be sent to browser
		 */
		public function GetControlHtml() {
			$strToReturn = parent::GetControlHtml();

			$strJqOptions = '';
			$strJqOptions .= $this->makeJsProperty('ShowButtonPanel', 'showButtonPanel');
			$strJqOptions .= $this->makeJsProperty('JqDateFormat', 'dateFormat');
			$strJqOptions .= $this->makeJsProperty('AutoSize', 'autoSize');
			$strJqOptions .= $this->makeJsProperty('MaxDate', 'maxDate');
			$strJqOptions .= $this->makeJsProperty('MinDate', 'minDate');
			$strJqOptions .= $this->makeJsProperty('DefaultDate', 'defaultDate');
			$strJqOptions .= $this->makeJsProperty('FirstDay', 'firstDay');
			$strJqOptions .= $this->makeJsProperty('GotoCurrent', 'gotoCurrent');
			$strJqOptions .= $this->makeJsProperty('IsRTL', 'isRTL');
			$strJqOptions .= $this->makeJsProperty('NumberOfMonths', 'numberOfMonths');
			if ($strJqOptions) {
				$strJqOptions = substr($strJqOptions, 0, -2);
			}

			QApplication::ExecuteJavaScript(
				sprintf('jQuery("#%s").datepicker({%s})', $this->strControlId, $strJqOptions));

			return $strToReturn;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case "MinDate" :
					return $this->datMinDate;
				case "MaxDate" :
					return $this->datMaxDate;
				case "DefaultDate" :
					return $this->datDefaultDate;
				case "FirstDay" :
					return $this->intFirstDay;
				case "GotoCurrent" :
					return $this->blnGotoCurrent;
				case "IsRTL" :
					return $this->blnIsRTL;
				case "NumberOfMonths" :
					return $this->mixNumberOfMonths;
				case "AutoSize" :
					return $this->blnAutoSize;
				case "DateFormat" :
					return $this->strDateTimeFormat;
				case "JqDateFormat" :
					return $this->strJqDateFormat;
				case "ShowButtonPanel" :
					return $this->blnShowButtonPanel;
				default :
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed
		 * @throws Exception|QCallerException|QInvalidCastException|Exception
		 */
		public function __set($strName, $mixValue) {
			$this->blnModified = true;
			switch ($strName) {
				case "MinDate" :
					try {
						$this->datMinDate = QType::Cast($mixValue, QType::DateTime);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "MaxDate" :
					$blnMaxDate = true;
					try {
						$this->datMaxDate = QType::Cast($mixValue, QType::DateTime);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "DefaultDate" :
					$blnDefaultDate = true;
					try {
						$this->datDefaultDate = QType::Cast($mixValue, QType::DateTime);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FirstDay" :
					$blnFirstDay = true;
					try {
						$this->intFirstDay = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "GotoCurrent" :
					try {
						$this->blnGotoCurrent = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "IsRTL" :
					try {
						$this->blnIsRTL = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "NumberOfMonths" :
					$blnNumberOfMonths = true;
					if (!is_array($mixValue) && !is_numeric($mixValue)) {
						throw new exception('NumberOfMonths must be an integer or an array');
					}
					$this->mixNumberOfMonths = $mixValue;
					break;
				case "AutoSize" :
					try {
						$this->blnAutoSize = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "JqDateFormat":
					try {
						$this->strJqDateFormat = QType::Cast($mixValue, QType::String);
						parent::__set('DateTimeFormat', QCalendar::qcFrmt($this->strJqDateFormat));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "DateTimeFormat":
				case "DateFormat":
					parent::__set('DateTimeFormat', $mixValue);
					$this->strJqDateFormat = QCalendar::jqFrmt($this->strDateTimeFormat);
					break;
				case "ShowButtonPanel" :
					try {
						$this->blnShowButtonPanel = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default :
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}

		/**
		 * Adds an event to the calendar
		 * It overrides the base method to make sure click events are not accepted
		 *
		 * @param QEvent  $objEvent
		 * @param QAction $objAction
		 *
		 * @throws QCallerException
		 */
		public function AddAction($objEvent, $objAction) {
			if ($objEvent instanceof QClickEvent) {
				throw new QCallerException('QCalendar does not support click events');
			}
			parent::AddAction($objEvent, $objAction);
		}
	}