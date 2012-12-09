<?php

	/**
	 * QAccordionBase
	 * 
	 * The QAccordionBase class defined here provides an interface between the generated
	 * QAccordianGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QAccordion.class.php file instead.
	 *
	 * The Accordion decends from QPanel. There are a number of ways to create an Accordion, 
	 * but the basics are that you put a series of block level items inside the Accordion (like divs, or h1, QPanels, etc.)
	 * and it will automatically pick the first item as the header and the second item as the content that will be collapsed
	 * or expanded, and will repeat that until the end of the Accordion block. 
	 * 
	 * If you want more control, you can assign a jQuery selector to the Header item and that selector 
	 * will be used to find the headers within the Accordion. In this case, the next block level sibling to
	 * the header will be used as the content for that header. For example, to use all the items with class ItemHeader
	 * inside the Accordion panel as the headers for the accordion, do this:
	 * 
	 * $accordion->Header = '.ItemHeader';
	 * 
	 * To get or set the index of the item that is currently open, use the inherited ->Active value. 
	 * 
	 * The Accordion will generate a QChangeEvent when a new header is selected.
	 * 
	 * See the jQuery UI documentation for additional events, methods and options that may be useful.
	 * 
	 */

	class QAccordionBase extends QAccordionGen
	{
		protected $blnAutoRenderChildren = true;

		protected function RenderChildren($blnDisplayOutput = true) {
			$strToReturn = "";

			foreach ($this->GetChildControls() as $objControl) {
				if (!$objControl->Rendered) {
					$renderMethod = $objControl->strPreferedRenderMethod;
					$strToReturn .= '<div>';
					$strToReturn .= $objControl->$renderMethod($blnDisplayOutput);
					$strToReturn .= '</div>';
				}
			}

			if ($blnDisplayOutput) {
				print($strToReturn);
				return null;
			} else
				return $strToReturn;
		}
		
		// These functions are used to keep track of the selected index.
		// To query or set the selected index, use ->Active
		public function GetControlJavaScript() {
			
			$formId = $this->Form->FormId;
			$strJS = parent::GetControlJavaScript();
			
			$strJS .=<<<FUNC
			.on("accordionchange", function(event, ui) {
			 			qcubed.recordControlModification("$this->ControlId", "_SelectedIndex", ui.options.active);
						qc.pA("$formId", "$this->ControlId", "QChangeEvent", "", "");
			})						
FUNC;
			
			return $strJS;
		}
				
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case '_SelectedIndex': // Internal Only. Used by JS above. Do Not Call.
					try {
						$this->mixActive = QType::Cast($mixValue, QType::Integer);	// will cause ->Active getter to always return index of content item that is currently active
					} catch (QInvalidCastException $objExc) {
						try {
							$this->mixActive = QType::Cast($mixValue, QType::Boolean);
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}
					}
					break;
					
				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
			
		}
	}
?>