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
 *
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
	protected $blnIsRtl = false;
	protected $blnModified = false;

	public function Validate() {
		return true;
	}

	public function GetControlHtml() {
		$strToReturn = parent::GetControlHtml();
		
		QApplication::ExecuteJavaScript(
			sprintf('jQuery("#%s").datepicker({showButtonPanel: true,  dateFormat: "M d yy"' . 
					(($this->blnAutoSize) ? ', autoSize: true' : '') . 
					(($this->datMinDate) ? ', minDate: "' . $this->datMinDate->__toString() .'"': '') . 
					(($this->datMaxDate) ? ', maxDate: "' . $this->datMaxDate->__toString() . '"' : '') . 
					(($this->datDefaultDate) ? ', defaultDate: "'.$this->datDefaultDate->__toString().'"' : '') . 
					(($this->intFirstDay) ? ', firstDay: '.$this->intFirstDay : '') . 
					(($this->blnGotoCurrent) ? ', gotoCurrent: true' : '') . 
					(($this->blnIsRtl) ? ', isRTL: true' : '') . 
					(($this->mixNumberOfMonths) ? (
							is_array($this->mixNumberOfMonths)? 
							'['.implode(', ',$this->mixNumberOfMonths).']' : $this->mixNumberOfMonths
							) :
						'') . 
					'})', $this->strControlId));

		return $strToReturn;
	}

	/////////////////////////
	// Public Properties: GET
	/////////////////////////
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
			case "IsRtl" :
				return $this->blnIsRtl;
			case "NumberOfMonths" :
				return $this->mixNumberOfMonths;
			case "AutoSize" :
				return $this->blnAutoSize;
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

	/////////////////////////
	// Public Properties: SET
	/////////////////////////
	public function __set($strName, $mixValue) {
		$this->blnModified = true;
		switch ($strName) {
			case "MinDate" :
				try {
					$this->datMinDate = QType::Cast($mixValue, QType::DateTime);
					break;
				}
				catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "MaxDate" :
				$blnMaxDate = true;
				try {
					$this->datMaxDate = QType::Cast($mixValue, QType::DateTime);
					break;
				}
				catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "DefaultDate" :
				$blnDefaultDate = true;
				try {
					$this->datDefaultDate = QType::Cast($mixValue, QType::DateTime);
					break;
				}
				catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "FirstDay" :
				$blnFirstDay = true;
				try {
					$this->intFirstDay = QType::Cast($mixValue, QType::Integer);
					break;
				}
				catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "GotoCurrent" :
				try {
					$this->blnGotoCurrent = QType::Cast($mixValue, QType::Boolean);
					break;
				}
				catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "IsRTL" :
				try {
					$this->blnIsRtl = QType::Cast($mixValue, QType::Boolean);
					break;
				}
				catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "NumberOfMonths" :
				$blnNumberOfMonths = true;
				if(!is_array($mixValue) && !is_numeric($mixValue))
					throw new exception('NumberOfMonths must be an integer or an array');
				$this->mixNumberOfMonths = $mixValue;
				break;
			case "AutoSize" :
				try {
					$this->blnAutoSize = QType::Cast($mixValue, QType::Boolean);
					break;
				}
				catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
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

	public function AddAction($objEvent, $objAction) {
		throw new QCallerException('QCalendar does not support custom events');
	}
}
?>