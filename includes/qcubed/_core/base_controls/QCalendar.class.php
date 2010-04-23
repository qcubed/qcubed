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
	protected $strMinDate = null;
	protected $strMaxDate = null;
	protected $strDefDate = null;
	protected $strFirstDay = null;
	protected $strNumberOfMonths = null;
	protected $blnAutoSize = false;
	protected $blnGotoCurrent = false;
	protected $blnIsRtl = false;
	protected $blnModified = false;

	public function Validate() {
		return true;
	}

	public function GetControlHtml() {
		$strToReturn = parent :: GetControlHtml();
		
		QApplication :: ExecuteJavaScript(
			sprintf('jQuery("#%s").datepicker({showButtonPanel: true,  dateFormat: "M d yy"' . 
				(($this->blnAutoSize) ? ', autoSize: true' : '') . 
				(($this->strMinDate) ? ', minDate: ' . $this->strMinDate : '') . 
				(($this->strMaxDate) ? ', maxDate: "' . $this->strMaxDate . '"' : '') . 
				(($this->strDefDate) ? $this->strDefDate : '') . 
				(($this->strFirstDay) ? $this->strFirstDay : '') . 
				(($this->blnGotoCurrent) ? ', gotoCurrent: true' : '') . 
				(($this->blnIsRtl) ? ', isRTL: true' : '') . 
				(($this->strNumberOfMonths) ? $this->strNumberOfMonths : '') . 
			'})', $this->strControlId));

		return $strToReturn;
	}

	/////////////////////////
	// Public Properties: GET
	/////////////////////////
	public function __get($strName) {
		switch ($strName) {
			case "MinDate" :
				return $this->strMinDate;
			case "MaxDate" :
				return $this->strMaxDate;
			case "DefDate" :
				return $this->strDefDate;
			case "FirstDay" :
				return $this->strFirstDay;
			case "blnGotoCurrent" :
				return $this->blnGotoCurrent;
			case "blnIsRtl" :
				return $this->blnIsRtl;
			case "NumberOfMonths" :
				return $this->strNumberOfMonths;
			case "blnAutoSize" :
				return $this->blnAutoSize;
			default :
				try {
					return parent :: __get($strName);
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
				$blnMinDate = true;
				if ($mixValue == "Today")
					$this->strMinDate = "new Date()";
				else
					$this->strMinDate = "new Date(" . $mixValue . ")";
				break;
			case "MaxDate" :
				$blnMaxDate = true;
				$this->strMaxDate = "new MaxDate('" . $mixValue . "')";
				break;
			case "defaultDate" :
				$blnDefDate = true;
				$this->strDefDate = ", defaultDate: +" . $mixValue . " ";
				break;
			case "FirstDay" :
				$blnFirstDay = true;
				$this->strFirstDay = ", FirstDay: " . $mixValue;
				break;
			case "gotoCurrent" :
				try {
					$this->blnGotoCurrent = QType :: Cast($mixValue, QType :: Boolean);
					break;
				}
				catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "isRTL" :
				try {
					$this->blnIsRtl = QType :: Cast($mixValue, QType :: Boolean);
					break;
				}
				catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "NumberOfMonths" :
				$blnNumberOfMonths = true;
				$mixRowsCols = explode(',', $mixValue);
				$this->strNumberOfMonths = ", NumberOfMonths: [" . $mixRowsCols[1] . ", " . $mixRowsCols[1] . "]";
				break;
			case "AutoSize" :
				try {
					$this->blnAutoSize = QType :: Cast($mixValue, QType :: Boolean);
					break;
				}
				catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			default :
				try {
					parent :: __set($strName, $mixValue);
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