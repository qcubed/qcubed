<?php
	/** 
 	* Contains the definition of two controls: QAjaxAutoCompleteTextBox and
 	* QJavaScriptAutoCompleteTextBox, as well as their common parent class
 	* QAutoCompleteTextBoxBase.
 	* 
 	* @package Controls 
 	*/ 
	
	/**
	 *	Base class for the Auto Complete text box controls. Abstract.
	 *	
	 *	Uses the JQuery Library and its AutoComplete plugin from:
	 *		http://bassistance.de/jquery-plugins/jquery-plugin-autocomplete/
	 *
	 *	@author Zeno Yu <zeno.yu@gmail.com>. Integration and refactoring by Alex Weinstein <alex94040@yahoo.com>
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
		protected $blnAutoFill		= false;
		protected $blnMustMatch		= false;
		protected $blnMatchContains	= false;
		protected $blnMatchCase		= false;
		protected $intMinChars		= 0;

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
	
	/**
	 * Server-side autocomplete text box. Whenever the user types stuff
	 * in this text box, an asynchronous Ajax request to the server will
	 * be sent, looking for potential matches.
	 *
	 * @package Controls
	 */
	class QAjaxAutoCompleteTextBox extends QAutoCompleteTextBoxBase {
		private $strCallback;

		public function __construct($objParentObject, $strCallback, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->strCallback = $strCallback;
			$this->AddAction(new QAutoCompleteTextBoxEvent(), new QAjaxControlAction($this, 'callbackAjax'));
		}
				
		public function callbackAjax($strFormId, $strControlId, $strParameter) {
			$parent = isset($this->objParentObject) ? $this->objParentObject : $this->objForm;
			
			try {
				$arrToSerialize = $parent->{$this->strCallback}($strParameter);
			} catch (Exception $e) {
				echo "There's been an error processing auto-complete matches: \n";
				throw $e;
			}
			
			if (is_null($arrToSerialize)) {
				return;
			}
			
			if (!is_array($arrToSerialize)) {
				echo 'Error: the callback method for QAutoCompleteTextBox must return an array of strings';
				throw new QCallerException("callback method for QAutoCompleteTextBox must return an array of strings");
			}
			
			foreach ($arrToSerialize as $item) {
				echo $item . "\n";
			}
			
			// Critically important - stop the processing and don't allow any other
			// QCubed events to fire in this case
			exit;
		}
		
		public function GetScript(){
			if(!$this->blnVisible || !$this->blnEnabled) {
				return '';
			}

			return sprintf('$("#%s").autocomplete("%s",
									{extraParams:{Qform__FormId:"%s",Qform__FormControl:"%s"},
									%s%s%s%s%s%s%s})',
							$this->strControlId,
							QApplication::$RequestUri,
							$this->objForm->FormId,
							$this->strControlId,
							"minChars:" . $this->intMinChars,
							(($this->blnAutoFill)		? ",autoFill:true" : ""),
							(($this->blnMatchContains)	? ",matchContains:true" : ""),
							(($this->blnMatchCase) 		? ",matchCase:true" : ""),
							(($this->blnMustMatch)		? ",mustMatch:true" : ""),
							(($this->Width)				? ",width:" . $this->Width : ""),
							(($this->strTextMode == QTextMode::MultiLine) ? ",multiple:true" : "")
			);
		}		
	}
	
	/**
	 * Client-side autocomplete text box. All potential autocomplete options are
	 * sent down to the client in the original page HTML. Whenever the user
	 * types something in the text box, no round-trip is made (unlike with the
	 * QAjaxAutoCompleteTextBox).
	 *
	 * Note that the performance tradeoff here is "pay the performance cost upfront,
	 * at the initial page load time" - instead of paying a small cost every time
	 * the user types something. 
	 *
	 * @package Controls
	 */
	class QJavaScriptAutoCompleteTextBox extends QAutoCompleteTextBoxBase {
		private $strItemsArray = array();

		public function __construct($objParentObject, $strItemsArray, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			
			$this->SetAutoCompleteList($strItemsArray);
		}
		
		public function GetScript(){
			if(!$this->blnVisible || !$this->blnEnabled || count($this->strItemsArray) <= 0) {
				return '';
			}

			// Result should look like ["Aberdeen", "Ada", ..]
			$arrOptions = array();
			$strJavascriptArray = "";
			if (is_array($this->strItemsArray)) {
				foreach ($this->strItemsArray as $strItem) {
					$arrOptions[] = "'" . QApplication::HtmlEntities($strItem) . "'";
				}

				if(count($arrOptions) > 0) {
					$strJavascriptArray = "[" . implode(',', $arrOptions) . "]";
				}
			}

			return sprintf('$("#%s").autocomplete(%s,{%s%s%s%s%s%s%s})',
							$this->strControlId,
							$strJavascriptArray,
							"minChars:" . $this->intMinChars,
							(($this->blnAutoFill) 		? ",autoFill:true" : ""),
							(($this->blnMatchContains) 	? ",matchContains:true" : ""),
							(($this->blnMatchCase) 		? ",matchCase:true" : ""),
							(($this->blnMustMatch)		? ",mustMatch:true" : ""),
							(($this->Width)				? ",width:" . $this->Width :""),
							(($this->strTextMode==QTextMode::MultiLine) ? ",multiple:true" : "")
						);
		}
		
		public function SetAutoCompleteList($strItemsArray) {
			if (is_null($strItemsArray)) {
				$this->strItemsArray = array();
				return;
			}
			
			if (!is_array($strItemsArray)) {
				throw new Exception("arrItems must be an array of strings");
			}
			
			$this->strItemsArray = $strItemsArray;
		}
	}
?>