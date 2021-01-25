<?php
	/**
	 * JqButton Base File
	 * 
	 * The QJqButtonBase class defined here provides an interface between the generated
	 * QJqButtonGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QJqButton.class.php file instead.
	 *
	 */

	 /**
	  * Implements a JQuery UI Button
	  * 
	  * Create a button exactly as if you were creating a QButton.
	  * 
	  * @property boolean $ShowText Causes text to be shown when icons are also defined.
	  * 
	  * Per our suggestion (yay, they listened), jqui has change the ShowText property to ShowLabel.
      * ShowText remains for backwards compatability for now.
	  *  
	  *  @link http://jqueryui.com/button/
	  *  @package Controls\Base
	  */

	class QJqButtonBase extends QJqButtonGen
	{
		public function __get($strName) {
			switch ($strName) {
				case 'ShowText': return $this->ShowLabel;	// from Gen superclass
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
			switch ($strName) {
				case 'ShowText':	// true if the text should be shown when icons are defined
					$this->ShowLabel = $mixValue;
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