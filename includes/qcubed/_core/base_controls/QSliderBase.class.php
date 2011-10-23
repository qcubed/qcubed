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
			.bind("slidechange", function (event, ui) {
					if (ui.values && ui.values.length) {
			 			qcubed.recordControlModification("$this->ControlId", "Values", ui.values[0] + ',' +  ui.values[1]);
			 		} else {
			 			qcubed.recordControlModification("$this->ControlId", "Value", ui.value);
					}
				})						
FUNC;
			
			return $strJS;
		}
		
		public function __set($strName, $mixValue) {
			//$this->blnModified = true;

			switch ($strName) {
				case 'Values':
					if (is_string($mixValue)) {
						$mixValue = explode (',', $mixValue);
					}
					parent::__set($strName, $mixValue);
					break;
														
				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				
			}
		}


	}

?>
