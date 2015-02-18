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
		/**
		 * The javascript for the control to be sent to the client.
		 * @return string The control's JS
		 */
		public function GetEndScript() {
			$strJS = parent::GetEndScript();
			QApplication::ExecuteJsFunction('qcubed.progressbar', $this->ControlId);
			return $strJS;
		}

		/**
		 * PHP __set magic method
		 * @param string $strName Name of the property
		 * @param string $mixValue Value of the property
		 *
		 * @throws Exception|QCallerException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case '_Value':	// Internal Only. Used by JS above. Do Not Call.
					try {
						$this->Value = QType::Cast($mixValue, QType::Integer);
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
