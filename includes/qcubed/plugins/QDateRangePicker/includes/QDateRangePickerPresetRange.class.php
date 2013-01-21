<?php
	/*
	 * @property string $MenuItemText
	 * @property mixed $DateStart
	 * @property mixed $DateEnd
	 */
	class QDateRangePickerPresetRange extends QBaseClass {
		protected $strMenuItemText;
		protected $mixDateStart;
		protected $mixDateEnd;

		public static function Today() {
			static $range = null;
			if (null === $range)
				$range = new QDateRangePickerPresetRange(
					QApplication::Translate('Today'),
					'today',
					'today');
			return $range;
		}

		public static function Last7Days() {
			static $range = null;
			if (null === $range)
				$range = new QDateRangePickerPresetRange(
					QApplication::Translate('Last 7 Days'),
					'today-7days',
					'today');
			return $range;
		}

		public static function Last30Days() {
			static $range = null;
			if (null === $range)
				$range = new QDateRangePickerPresetRange(
					QApplication::Translate('Last 30 Days'),
					'today-30days',
					'today');
			return $range;
		}

		public static function Next30Days() {
			static $range = null;
			if (null === $range)
				$range = new QDateRangePickerPresetRange(
					QApplication::Translate('Next 30 Days'),
					'today',
					'today+30days');
			return $range;
		}

		public static function MonthToDate() {
			static $range = null;
			if (null === $range)
				$range = new QDateRangePickerPresetRange(
					QApplication::Translate('Month To Date'),
					new QJsClosure('return Date.parse(\'today\').moveToFirstDayOfMonth();'),
					'today');
			return $range;
		}

		public static function YearToDate() {
			static $range = null;
			if (null === $range)
				$range = new QDateRangePickerPresetRange(
					QApplication::Translate('Year To Date'),
					new QJsClosure('var x = Date.parse(\'today\'); x.setMonth(0); x.setDate(1); return x;'),
					'today');
			return $range;
		}

		public static function PreviousMonth() {
			static $range = null;
			if (null === $range)
				$range = new QDateRangePickerPresetRange(
					QApplication::Translate('The previous Month'),
					new QJsClosure('return Date.parse(\'1 month ago\').moveToFirstDayOfMonth();'),
					new QJsClosure('return Date.parse(\'1 month ago\').moveToLastDayOfMonth();'));
			return $range;
		}

		public static function Tomorrow() {
			static $range = null;
			if (null === $range)
				$range = new QDateRangePickerPresetRange(
					QApplication::Translate('Tomorrow'),
					'tomorrow',
					'tomorrow');
			return $range;
		}

		public function __construct($strMenuItemText, $mixDateStart, $mixDateEnd) {
			$this->strMenuItemText = $strMenuItemText;
			$this->mixDateStart = $mixDateStart;
			$this->mixDateEnd = $mixDateEnd;
		}


		public function __get($strName) {
			switch ($strName) {
				case "MenuItemText": return $this->strMenuItemText;
				case "DateStart": return $this->mixDateStart;
				case "DateEnd": return $this->mixDateEnd;
				default :
					try {
						return parent::__get($strName);
					}
					catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "MenuItemText":
					try {
						$this->strMenuItemText = QType::Cast($mixValue, QType::String);
						break;
					}
					catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "DateStart":
					$this->mixDateStart = $mixValue;
					break;

				case "DateEnd":
					$this->mixDateEnd = $mixValue;
					break;

				default :
					try {
						parent::__set($strName, $mixValue);
					}
					catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

			}
		}

		public function toJsObject() {
			$strReturn = '{';
			$strReturn .= 'text : '.JavaScriptHelper::toJsObject($this->MenuItemText).', ';
			$strReturn .= 'dateStart : '.JavaScriptHelper::toJsObject($this->DateStart).', ';
			$strReturn .= 'dateEnd : '.JavaScriptHelper::toJsObject($this->DateEnd);
			$strReturn .= '}';
			return $strReturn;
		}
	}

?>
