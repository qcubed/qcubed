<?php
	/**
	 * The  QJqCheckBoxBase class defined here provides an interface between the generated
	 * QJqCheckBoxGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QJqCheckBox.class.php file instead.
	 *
	 */

	/* Notes:
	 * 
	 * Some of the JqButtonGen properties use the same names as standard QCubed properties.
	 * In particular, the Text property is a boolean in the JqUi object that specifies whether
	 * to show text or just icons (provided icons are defined), and the Label property overrides
	 * the standard HTML of the button. This class will sort some of that out.
	 * 
	 */

	 /* @property boolean $ShowText Causes text to be shown when icons are also defined.
	  * 
	  */
	 
	class QJqCheckBoxBase extends QJqCheckBoxGen
	{
		public function __get($strName) {
			switch ($strName) {
				case 'ShowText': return $this->blnText;
				case 'Text': return $this->strText; // overwrite auto-generated implementation in parent
				default: 
					try { 
						return parent::__get($strName); 
					} catch (QCallerException $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}
		
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				case 'ShowText':	// true if the text should be shown when icons are defined
					try {
						$this->blnText = QType::Cast($mixValue, QType::Boolean);
						if ($this->Rendered) {
							$this->CallJqUiMethod ("option", $strName, $mixValue);
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
				case 'Text': // overwrite auto-generated implementation in parent
					try {
						$this->strText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
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