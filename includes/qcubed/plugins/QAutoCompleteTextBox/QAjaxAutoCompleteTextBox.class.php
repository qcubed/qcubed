<?php
	/** 
 	* Contains the definition of a control: QAjaxAutoCompleteTextBox
 	* 
 	* @package Controls 
 	*/ 
 	
	/**
	 * Server-side autocomplete text box. Whenever the user types stuff
	 * in this text box, an asynchronous Ajax request to the server will
	 * be sent, looking for potential matches.
	 *
	 * @author Original implementation by Zeno Yu <zeno.yu@gmail.com>. Integration into QCubed and refactoring by Alex Weinstein <alex94040@yahoo.com>
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
			$parent = isset($this->objParentControl) ? $this->objParentControl : $this->objForm;
			
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
?>