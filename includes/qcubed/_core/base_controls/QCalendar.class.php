<?php
	/**
	 * This file contains the QCalendar class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render an HTML Button.
	 *
	 * @package Controls
	 *
	 * @property string $CalendarImageSource
	 */
	class QCalendar extends QDateTimeTextBox {
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		
		public function Validate() {return true;}
		public function GetControlHtml() {
			$strToReturn = parent::GetControlHtml();
			QApplication::ExecuteJavaScript(sprintf('$j("#%s").datepicker({showButtonPanel: true, dateFormat: "M d yy" })', $this->strControlId));
			
			return $strToReturn;
		}
		public function AddAction($objEvent, $objAction) {
			throw new QCallerException('QJQCalendar does not support custom events');
		}
	}
?>