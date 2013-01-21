<?php
	class QJqDateTimePickerBase extends QJqDateTimePickerGen
	{
		protected $strDateFormat = 'MMM D, YYYY';
		protected $strTimeFormat;

		public function  __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->AddPluginJavascriptFile("QJqDateTimePicker", "jquery-ui-timepicker-addon-0.9.9.min.js");
			$this->AddPluginCssFile("QJqDateTimePicker", "jquery-ui-timepicker-addon-0.9.9.css");
		}

		static private $mapQC2JQ = array(
			'hhhh' => 'hh',
			'hh' => 'h',
			'zz' => 'TT',
			'z' => 'tt',
			);
		static private $mapJQ2QC = null;

		static public function qcFrmt($jqFrmt) {
			if (!self::$mapJQ2QC) {
				self::$mapJQ2QC = array_flip(self::$mapQC2JQ);
			}
			return strtr($jqFrmt, self::$mapJQ2QC);
		}

		static public function jqFrmt($qcFrmt) {
			return strtr($qcFrmt, self::$mapQC2JQ);
		}

		protected function updateDateTimeFormat() {
			$this->strDateTimeFormat = '';
			if ($this->strDateFormat) {
				$this->strDateTimeFormat = $this->strDateFormat;
			}
			if ($this->strTimeFormat) {
				if ($this->strDateTimeFormat)
					$this->strDateTimeFormat .= ' ';
				$this->strDateTimeFormat .= $this->strTimeFormat;
			}
			// trigger an update to reformat the text with the new format
			$this->DateTime = $this->dttDateTime;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// MISC
				case 'DateFormat': return $this->strDateFormat;
				case 'TimeFormat': return $this->strTimeFormat;

				default:
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
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				case 'DateTimeFormat':
					throw new QCallerException("Please use DateFormat and TimeFormat properties instead of DateTimeFormat");

				case 'JqDateFormat':
					try {
						$this->strJqDateFormat = QType::Cast($mixValue, QType::String);
						$this->strDateFormat = QCalendar::qcFrmt($this->strJqDateFormat);
						$this->updateDateTimeFormat();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DateFormat':
					try {
						$this->strDateFormat = QType::Cast($mixValue, QType::String);
						$this->strJqDateFormat = QCalendar::jqFrmt($this->strDateFormat);
						$this->updateDateTimeFormat();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'JqTimeFormat':
					try {
						$this->strJqTimeFormat = QType::Cast($mixValue, QType::String);
						$this->strTimeFormat = self::qcFrmt($this->strJqTimeFormat);
						$this->updateDateTimeFormat();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'TimeFormat':
					try {
						$this->strTimeFormat = QType::Cast($mixValue, QType::String);
						$this->strJqTimeFormat = self::jqFrmt($this->strTimeFormat);
						$this->updateDateTimeFormat();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}
?>
