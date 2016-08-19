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
	  * One of the JqButtonGen properties use the same names as standard QCubed properties.
	  * The Text property is a boolean in the JqUi object that specifies whether
	  * to show text or just icons (provided icons are defined), and the Label property overrides
	  * the standard HTML of the button. Because of the name conflict, the JQ UI property is called
	  * ->JqText. You can also use ShowText as an alias to this as well so that your code is more readable.
	  *  Text = standard html text of button
	  *  Label = override of standard HTML text, if you want a button to say something different when JS is on or off
	  *  ShowText = whether or not to hide the text of the button when icons are set
	  *  
	  *  @link http://jqueryui.com/button/
	  *  @package Controls\Base
	  */

	class QJqButtonBase extends QJqButtonGen
	{
		public function __get($strName) {
			switch ($strName) {
				case 'ShowText': return $this->JqText;	// from Gen superclass
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
					$this->JqText = $mixValue;
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