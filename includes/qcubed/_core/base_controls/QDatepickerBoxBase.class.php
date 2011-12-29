<?php

	/**
	 * @property string $DateFormat
	 * @property string $DateTimeFormat
	 * @property QDateTime $DateTime
	 * @property mixed $Minimum
	 * @property mixed $Maximum
	 *
	 */
	class QDatepickerBoxBase extends QDatepickerBoxGen {
		protected $strDateTimeFormat = "MM/DD/YY"; // matches default of JQuery UI control
		/** @var QDateTime */
		protected $dttDateTime;

		public function ParsePostData() {
			// Check to see if this Control's Value was passed in via the POST data
			if (array_key_exists($this->strControlId, $_POST)) {
				parent::ParsePostData();
				$this->dttDateTime = new QDateTime($this->strText);
				if ($this->dttDateTime->IsNull()) {
					$this->dttDateTime = null;
				}
			}
		}

		public function Validate() {
			if (!parent::Validate()) {
				return false;
			}

			if ($this->strText != '') {
				$dttDateTime = new QDateTime($this->strText);
				if ($dttDateTime->IsDateNull()) {
					$this->strValidationError = QApplication::Translate("invalid date");
					return false;
				}
				if (!is_null($this->Minimum)) {
					if ($dttDateTime->IsEarlierThan($this->Minimum)) {
						$this->strValidationError = QApplication::Translate("date is earlier than minimum allowed");
						return false;
					}
				}

				if (!is_null($this->Maximum)) {
					if ($dttDateTime->IsLaterThan($this->Maximum)) {
						$this->strValidationError = QApplication::Translate("date is later than maximum allowed");
						return false;
					}
				}
			}

			$this->strValidationError = '';
			return true;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// MISC
				case "Maximum":
					return $this->MaxDate;
				case "Minimum":
					return $this->MinDate;
				case 'DateTimeFormat':
				case 'DateFormat':
					return $this->strDateTimeFormat;
				case 'DateTime':
					return $this->dttDateTime;

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
				case 'MaxDate':
				case 'Maximum':
					if (is_string($mixValue)) {
						$mixValue = new QDateTime($mixValue);
					}
					parent::__set('MaxDate', QType::Cast($mixValue, QType::DateTime));
					break;

				case 'MinDate':
				case 'Minimum':
					if (is_string($mixValue)) {
						$mixValue = new QDateTime($mixValue);
					}
					parent::__set('MinDate', QType::Cast($mixValue, QType::DateTime));
					break;

				case 'DateTime':
					try {
						$this->dttDateTime = QType::Cast($mixValue, QType::DateTime);
						if ($this->dttDateTime->IsNull()) {
							$this->dttDateTime = null;
						}
						if (!$this->dttDateTime || !$this->strDateTimeFormat) {
							parent::__set('Text', '');
						} else {
							parent::__set('Text', $this->dttDateTime->qFormat($this->strDateTimeFormat));
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'JqDateFormat':
					try {
						parent::__set($strName, $mixValue);
						$this->strDateTimeFormat = QCalendar::qcFrmt($this->JqDateFormat);
						// trigger an update to reformat the text with the new format
						$this->DateTime = $this->dttDateTime;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DateTimeFormat':
				case 'DateFormat':
					try {
						$this->strDateTimeFormat = QType::Cast($mixValue, QType::String);
						parent::__set('JqDateFormat', QCalendar::jqFrmt($this->strDateTimeFormat));
						// trigger an update to reformat the text with the new format
						$this->DateTime = $this->dttDateTime;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Text':
					parent::__set($strName, $mixValue);
					$this->dttDateTime = new QDateTime($this->strText);
					break;

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
