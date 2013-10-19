<?php
	/**
	 * Datepicker Base File
	 * 
	 * The QDatepickerBase class defined here provides an interface between the generated
	 * QDatepickerGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QDatepicker.class.php file instead.
	 *
	 */

    /**
     * Impelements a JQuery UI Datepicker
     * 
     * A Datepicker is a field that is designed to just allow dates, and to popup a calendar for picking dates.
     * 
	 * @property string $DateFormat			The format to use for displaying the date in the field
	 * @property string $DateTimeFormat		Alias for DateFormat
	 * @property QDateTime $DateTime		The date to set the field to
	 * @property mixed $Minimum				Alias for MinDate
	 * @property mixed $Maximum				Alias for MaxDate
	 * @property string $Text				Textual date to set it to
	 *
	 * @link http://jqueryui.com/datepicker/
	 * @package Controls\Base
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
			$strJS = 'qcubed.recordControlModification("' . $this->getJqControlId() . '", "_Text", dateText)';
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
			switch ($strName) {
				case 'MaxDate':
				case 'Maximum':
					if (is_string($mixValue)) {
						if (preg_match('/[+-][0-9]+[dDwWmMyY]/', $mixValue)) {
							parent::__set($strName, $mixValue);
							break;
						}
						$mixValue = new QDateTime($mixValue);
					}
					parent::__set('MaxDate', QType::Cast($mixValue, QType::DateTime));
					break;

				case 'MinDate':
				case 'Minimum':
					if (is_string($mixValue)) {
						if (preg_match('/[+-][0-9]+[dDwWmMyY]/', $mixValue)) {
							parent::__set($strName, $mixValue);
							break;
						}
						$mixValue = new QDateTime($mixValue);
					}
					parent::__set('MinDate', QType::Cast($mixValue, QType::DateTime));
					break;

				case 'DateTime':
					try {
						$this->dttDateTime = QType::Cast($mixValue, QType::DateTime);
						$this->blnModified = true;
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

				case 'Text':	// Set the selected date with a text value
					$this->dttDateTime = new QDateTime($mixValue);
					$this->blnModified = true;
					break;

				case '_Text':	// Internal only. Do not use. Called by JS above to keep track of user selection.
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
