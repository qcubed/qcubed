<?php
	/**
	 * The  QSliderBase class defined here provides an interface between the generated
	 * QSliderGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QSlider.class.php file in
	 * the controls folder instead.
	 *
	 */


	class QSliderBase extends QSliderGen	{
		public function GetControlJavaScript() {
			$strJS = parent::GetControlJavaScript();
			
			$strJS .=<<<FUNC
			.on("slidechange", function (event, ui) {
					if (ui.values && ui.values.length) {
			 			qcubed.recordControlModification("$this->ControlId", "_Values", ui.values[0] + ',' +  ui.values[1]);
			 		} else {
			 			qcubed.recordControlModification("$this->ControlId", "_Value", ui.value);
					}
				})						
FUNC;
			
			return $strJS;
		}
		
		public function __set($strName, $mixValue) {

			switch ($strName) {
				case '_Value':	// Internal Only. Used by JS above. Do Not Call.
					try {
						$this->intValue = QType::Cast($mixValue, QType::Integer);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

				case '_Values': // Internal Only. Used by JS above. Do Not Call.
					try {
						$aValues = explode (',', $mixValue);
						$aValues[0] = QType::Cast( $aValues[0], QType::Integer); // important to make sure JS sends values as ints instead of strings
						$aValues[1] = QType::Cast($aValues[1], QType::Integer); // important to make sure JS sends values as ints instead of strings
						$this->arrValues = $aValues;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
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
