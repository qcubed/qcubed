<?php
    /**
	 * @property string $DateFormat
	 * @property string $DateTimeFormat
	 * @property QDateTime $DateTime
	 * @property mixed $Minimum
	 * @property mixed $Maximum
	 *
	 */
	class QDatepickerBox extends QDatepickerBoxBase
	{
		protected $strDateFormat;
		protected $dttDateTime;

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// MISC
				case "Maximum": return $this->MinDate;
				case "Minimum": return $this->MaxDate;
				case 'DateTimeFormat':
				case 'DateFormat': return $this->strDateFormat;
				case 'DateTime': return $this->dttDateTime;

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
				case 'Maximum':
					parent::__set('MaxDate', $mixValue);
					break;

				case 'Minimum':
					parent::__set('MinDate', $mixValue);
					break;

				case 'DateTime':
					try {
						$this->dttDateTime = QType::Cast($mixValue, QType::DateTime);
						if (!$this->dttDateTime || !$this->strDateFormat) {
							parent::__set('Text', '');
						} else {
							parent::__set('Text', $this->dttDateTime->qFormat($this->strDateFormat));
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'JqDateFormat':
					try {
						parent::__set($strName, $mixValue);
						$this->strDateFormat = QCalendar::qcFrmt($this->strDateFormat);
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
						$this->strDateFormat = QType::Cast($mixValue, QType::String);
						parent::__set('JqDateFormat', QCalendar::jqFrmt($this->strDateFormat));
						// trigger an update to reformat the text with the new format
						$this->DateTime = $this->dttDateTime;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Text':
					$this->dttDateTime = new QDateTime($this->strText);
					return parent::__set($strName, $mixValue);

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
