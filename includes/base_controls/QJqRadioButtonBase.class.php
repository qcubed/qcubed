<?php
	/**
	 * QRadioButton Base File
	 * 
	 * The QJqRadioButtonBase class defined here provides an interface between the generated
	 * QJqRadioButtonGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QJqRadioButton.class.php file instead.
	 *
	 */

	 /**
	  * Implements a JQuery UI Radio Button
	  * 
	  * Use in the same way you would use a standard radio button
	  * 
	  * One of the QJqRadioButtonGen properties use the same names as standard QCubed properties.
	  * The text property is a boolean in the JqUi object that specifies whether
	  * to show text or just icons (provided icons are defined), and the Label property overrides
	  * the standard HTML of the button. Because of the name conflict, the JQ UI property is called
	  * ->JqText. You can also use ShowText as an alias to this as well so that your code is more readable.
	  * 	Text = standard html text of button
	  *  Label = override of standard HTML text, if you want a button to say something different when JS is on or off
      *  ShowLabel = Removed from jqui 1.12 for checkboxes and radios
	  *
	  *  @link http://jqueryui.com/button/#radio
	  *  @package Controls\Base
	  * 
	  */
	 
	class QJqRadioButtonBase extends QJqRadioButtonGen
	{
	}