<?php
	/** 
 	* Contains the definition of a control: QJavaScriptAutoCompleteTextBox
 	* 
 	* @package Controls 
 	*/ 
 	
 	
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
	 * @author Original implementation by Zeno Yu <zeno.yu@gmail.com>. Integration into QCubed and refactoring by Alex Weinstein <alex94040@yahoo.com>
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
							(($this->blnAutoFill) 			? ",autoFill:true" : ""),
							(($this->blnMatchContains) 	? ",matchContains:true" : ""),
							(($this->blnMatchCase) 			? ",matchCase:true" : ""),
							(($this->blnMustMatch)			? ",mustMatch:true" : ""),
							(($this->Width)							? ",width:" . $this->Width :""),
							(($this->strTextMode == QTextMode::MultiLine) ? ",multiple:true" : "")
						);
		}
		
		public function SetAutoCompleteList($strItemsArray) {
			if (is_null($strItemsArray)) {
				$this->strItemsArray = array();
				return;
			}
			
			if (!is_array($strItemsArray)) {
				throw new QCallerException("strItemsArray parameter must be an array of strings");
			}
			
			$this->strItemsArray = $strItemsArray;
		}
	}

?>