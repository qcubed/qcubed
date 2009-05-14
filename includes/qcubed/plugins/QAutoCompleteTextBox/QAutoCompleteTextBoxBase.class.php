<?php
	/** 
 	* Contains the definition of a base control: QAutoCompleteTextBoxBase
 	* 
 	* @package Controls 
 	*/ 
	
	/**
	 *	Base class for the Auto Complete text box controls. Abstract.
	 *	
	 *	Uses the JQuery Library and its AutoComplete plugin from:
	 *		http://bassistance.de/jquery-plugins/jquery-plugin-autocomplete/
	 *
	 *	@author Original implementation by Zeno Yu <zeno.yu@gmail.com>. Integration into QCubed and refactoring by Alex Weinstein <alex94040@yahoo.com>
	 *	@package Controls
	 *
	 *	@property boolean $AutoFill Fill the textinput while still selecting a value, replacing the value if more is typed or something else is selected. Default: false
	 *	@property boolean $MustMatch If set to true, the autocompleter will only allow results that are presented by the backend. Note that illegal values result in an empty input box. Default: false
	 *	@property integer $MinChars The minimum number of characters a user has to type before the autocompleter activates. Default: 0
	 *	@property boolean $MatchContains Whether or not the comparison looks inside (i.e. does "ba" match "foo bar") the search results. Only important if you use caching. Don�t mix with autofill. Default: false
	 *	@property boolean $MatchCase Whether or not the comparison is case sensitive. Only important only if you use caching. Default: false
	 *	 
	 */
	abstract class QAutoCompleteTextBoxBase extends QTextBoxBase{
		protected $blnAutoFill			= false;
		protected $blnMustMatch			= false;
		protected $blnMatchContains	= false;
		protected $blnMatchCase			= false;
		protected $intMinChars			= 0;

		// TextBox CSS Class
		protected $strCssClass = 'textbox';

		//////////
		// Methods
		//////////
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			
			$this->AddJavascriptFile("jquery.ui-1.5.3/jquery-1.2.6.js");

			$this->AddPluginJavascriptFile("QAutoCompleteTextBox", "jquery.autocomplete.js");
			$this->AddPluginJavascriptFile("QAutoCompleteTextBox", "jquery.bgiframe.js");
			
			$this->AddPluginCssFile("QAutoCompleteTextBox", "jquery.autocomplete.css");
						
			$this->strLabelForRequired = QApplication::Translate('%s is required');
			$this->strLabelForRequiredUnnamed = QApplication::Translate('Required');
		}
		
		public abstract function GetScript();

		public function GetEndScript() {
			if(!$this->blnVisible || !$this->blnEnabled ) {
				return '';
			}
			$strJavaScript = $this->GetScript();
			return "$().ready(function() {" . $strJavaScript . ";});";
		}

		public function __get($strName) {
			switch ($strName) {
				case "AutoFill":		return $this->blnAutoFill;
				case "MatchContains":	return $this->blnMatchContains;
				case "MustMatch":		return $this->blnMustMatch;
				case "MatchCase":		return $this->blnMatchCase;
				case "MinChars":		return $this->intMinChars;
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
				case "CssClass":
					try {
						$this->strCssClass = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "AutoFill":
					try {
						$this->blnAutoFill = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "MustMatch":
					try {
						$this->blnMustMatch = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "MatchCase":
					try {
						$this->blnMatchCase = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "MatchContains":
					try {
						$this->blnMatchContains = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "MinChars":
					try {
						$this->intMinChars = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
			
			$this->blnModified = true;
		}
	}
	
	/**
	 * Empty event, used internally by QAutoCompleteTextBoxBase.
	 *
	 * @package Events
	 */
	class QAutoCompleteTextBoxEvent extends QEvent {
		protected $strJavaScriptEvent = '';
	}
?>