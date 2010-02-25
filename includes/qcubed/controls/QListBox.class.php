<?php
	/**
	 * QListBox.class.php contains the QListBox 
	 * @package Controls
	 */
	/**
	 * The QListBox class is based upon QListBoxBase.  
	 * 
	 * The purpose of this class is entirely to provide a place for you to make modifications of the QListBox control.
	 * All updates in QCubed releases will make changes to the QListBoxBase class.  By making your modifications here 
	 * instead of in the base class, you can ensure that your changes are not affected by core improvements.
	 * 
	 * @package Controls
	 */
	class QListBox extends QListBoxBase {
		///////////////////////////
		// ListBox Preferences
		///////////////////////////

		// Feel free to specify global display preferences/defaults for all QListBox controls
		protected $strCssClass = 'listbox';
//		protected $strFontNames = QFontFamily::Verdana;
//		protected $strFontSize = '12px';
//		protected $strWidth = '250px';

		/**
		 * Creates the reset button html for use with multiple select boxes.
		 * 
		 */
		protected function GetResetButtonHtml() {
			$strJavaScriptOnClick = sprintf('$j("#%s").val(null);$j("#%s").trigger("change"); return false;', $this->strControlId,$this->strControlId);
			
			$strToReturn = sprintf(' <a id="reset_ctl_%s" href="#" class="listboxReset">%s</a>',
				$this->strControlId,
				QApplication::Translate('Reset')
			);

			QApplication::ExecuteJavaScript(sprintf('$j("#reset_ctl_%s").bind("%s", function(){ %s });', $this->strControlId, "click",  $strJavaScriptOnClick));
					
			return $strToReturn;
		}
	}
?>