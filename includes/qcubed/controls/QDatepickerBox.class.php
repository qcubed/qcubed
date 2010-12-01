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
		protected $strDateTimeFormat = "MMM D, YYYY";
		protected $dttDateTime;

        public function ParsePostData() {
            // Check to see if this Control's Value was passed in via the POST data
            if (array_key_exists($this->strControlId, $_POST)) {
                parent::ParsePostData();
                $this->dttDateTime = new QDateTime($this->strText);
            }
        }

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// MISC
				case "Maximum": return $this->MinDate;
				case "Minimum": return $this->MaxDate;
				case 'DateTimeFormat':
				case 'DateFormat': return $this->strDateTimeFormat;
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
