<?php
    /**
	 * @property string $DateFormat
	 * @property string $DateTimeFormat
	 * @property QDateTime $DateTime
	 * @property mixed $Minimum
	 * @property mixed $Maximum
	 * @property string $Text
	 *
	 */
	class QDatepickerBase extends QDatepickerGen
	{
		protected $strDateTimeFormat = "MM/DD/YY";	// same as default for JQuery UI control
		protected $dttDateTime;	// default to no selection
		
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct ($objParentObject, $strControlId);
			
			parent::__set ('OnSelect', $this->OnSelectJs());	// setup a way to detect a selection
		}
		


		// The datebpicker will not send its results to us by default
		protected function OnSelectJs () {
			$strJS = 'qcubed.recordControlModification("' . $this->getJqControlId() . '", "Text", dateText)';
			return $strJS;
		}
		

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// MISC
				case "Maximum": return $this->MaxDate;
				case "Minimum": return $this->MinDate;
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
						/*
						if (!$this->dttDateTime || !$this->strDateTimeFormat) {
							parent::__set('Text', '');
						} else {
							parent::__set('Text', $this->dttDateTime->qFormat($this->strDateTimeFormat));
						}*/
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
					//parent::__set($strName, $mixValue);
					$this->dttDateTime = new QDateTime($mixValue);
					break;
					
				case 'OnSelect':
					// Since we are using the OnSelect event alreay, and Datepicker doesn't allow binding, so there can be
					// only one event, we will make sure our js is part of any new OnSelect js.
					$mixValue = $this->OnSelectJs() . ';' . $mixValue;
					parent::__set('OnSelect', $mixValue);
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
