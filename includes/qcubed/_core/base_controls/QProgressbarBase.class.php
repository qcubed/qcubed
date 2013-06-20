<?php
	/**
	 * QProgressbar Base File
	 * 
	 * The  QProgressbarBase class defined here provides an interface between the generated
	 * QProgressbarGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, see the QProgressbar.class.php file in the controls
	 * folder.
	 *
	 */

	/**
	 * Implements a JQuery UI Progress Bar
	 * 
	 * Use the inherited interface to control the progress bar.
	 * 
	 * @link http://jqueryui.com/progressbar/
	 * @package Controls\Base
	 *
	 */
	class QProgressbarBase extends QProgressbarGen	{
		public function GetControlJavaScript() {
			$strJS = parent::GetControlJavaScript();
			
			// if something else changes the value of the progress bar, make sure we know about it
			$strJS .=<<<FUNC
			.on("progressbarchange", function (event, ui) {
			 			qcubed.recordControlModification("$this->ControlId", "_Value", jQuery(this).progressbar ("value"));
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
