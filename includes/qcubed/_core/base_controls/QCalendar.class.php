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
	protected $strJqDateFormat = 'M d yy';

	// map the JQuery datepicker format specs to QCodo QDateTime format specs.
	//qcodo	jquery			php	Description
	//-------------------------------------------------
	//MMMM	MM			F	Month as full name (e.g., March)
	//MMM	M			M	Month as three-letters (e.g., Mar)
	//MM	mm			m	Month as an integer with leading zero (e.g., 03)
	//M	m			n	Month as an integer (e.g., 3)
	//DDDD	DD			l	Day of week as full name (e.g., Wednesday)
	//DDD	D			D	Day of week as three-letters (e.g., Wed)
	//DD	dd			d	Day as an integer with leading zero (e.g., 02)
	//D	d			j	Day as an integer (e.g., 2)
	//YYYY	yy			Y	Year as a four-digit integer (e.g., 1977)
	//YY	y			y	Year as a two-digit integer (e.g., 77)
	static private $mapQC2JQ = array(
		'MMMM' => 'MM',
		'MMM' => 'M',
		'MM' => 'mm',
		'M' => 'm',
		'DDDD' => 'DD',
		'DDD' => 'D',
		'DD' => 'dd',
		'D' => 'd',
		'YYYY' => 'yy',
		'YY' => 'y',
		);
	static private $mapJQ2QC = null;

	static private function qcFrmt($jqFrmt) {
		if (!QCalendar::$mapJQ2QC) {
			QCalendar::$mapJQ2QC = array_flip(QCalendar::$mapQC2JQ);
		}
		return strtr($jqFrmt, QCalendar::$mapJQ2QC);
	} 
		
	static private function jqFrmt($qcFrmt) {
		return strtr($qcFrmt, QCalendar::$mapQC2JQ);
	} 
		
	static private function jsDate(QDateTime $dt) {
		return 'new Date('.$dt->Year.','.$dt->Month.','.$dt->Day.')';
	} 
		
	public function Validate() {
		return true;
	}

	public function GetControlHtml() {
		$strToReturn = parent::GetControlHtml();
		
		QApplication::ExecuteJavaScript(
			sprintf('jQuery("#%s").datepicker({showButtonPanel: true, dateFormat: "' . $this->strJqDateFormat . '"' .  
					(($this->blnAutoSize) ? ', autoSize: true' : '') . 
					(($this->datMinDate) ? ', minDate: ' . QCalendar::jsDate($this->datMinDate) : '') . 
					(($this->datMaxDate) ? ', maxDate: ' . QCalendar::jsDate($this->datMaxDate) : '') . 
					(($this->datDefaultDate) ? ', defaultDate: ' . QCalendar::jsDate($this->datDefaultDate) : '') . 
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
			case "DateFormat" :
				return $this->strDateTimeFormat;
			case "JqDateFormat" :
				return $this->strJqDateFormat;
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