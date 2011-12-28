<?php
	/**
	 * The  QProgressbarBase class defined here provides an interface between the generated
	 * QProgressbarGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, see the QProgressbar.class.php file in the controls
	 * folder.
	 *
	 */

	class QProgressbarBase extends QProgressbarGen	{
		public function GetControlJavaScript() {
			$strJS = parent::GetControlJavaScript();
			
			// if something else changes the value of the progress bar, make sure we know about it
			$strJS .=<<<FUNC
			.on("progressbarchange", function (event, ui) {
			 			qcubed.recordControlModification("$this->ControlId", "Value", jQuery(this).progressbar ("value"));
					})						
										
FUNC;
			
			return $strJS;
		}

	}

?>
