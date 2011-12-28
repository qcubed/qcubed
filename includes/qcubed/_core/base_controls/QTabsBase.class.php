<?php
	/**
	 * The QTabsBase class defined here provides an interface between the generated
	 * QTabsGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QTabs.class.php file instead.
	 *
	 */


    /**
	 * @property-write array $Headers
	 *
	 */
	class QTabsBase extends QTabsGen
	{
		protected $objTabHeadersArray = array();
		protected $blnAutoRenderChildren = true;
		protected $intSelected = 0;

		public function GetControlJavaScript() {
			$strJS = parent::GetControlJavaScript();
			$strJS .= sprintf('; $j("#%s").on("tabsselect", function(event, ui) {$j("#%s").val(ui.index);})',
					$this->ControlId,
					$this->getSelectedInputId());
			return $strJS;
		}

		protected function getSelectedInputId() {
			return $this->ControlId.'_selected';
		}

		public function ParsePostData() {
			$strSelectedInputId = $this->getSelectedInputId();
			if (array_key_exists($strSelectedInputId, $_POST)) {
				$this->intSelected = $_POST[$strSelectedInputId];
			}
		}

		protected function RenderChildren($blnDisplayOutput = true) {
			$strToReturn = $this->GetTabHeaderHtml();

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

		protected function GetTabHeaderHtml() {
			$strResult = sprintf('<input id="%s" type="hidden" value="%d"/>', $this->getSelectedInputId(), $this->intSelected);
			$strResult .= '<ul>';
			$childControls = $this->GetChildControls();
			for ($i = 0, $cnt = count($childControls); $i < $cnt; ++$i) {
				$strControlId = $childControls[$i]->ControlId;
				$strResult .= '<li><a href="#'.$strControlId.'">';
				if (array_key_exists($key = $strControlId, $this->objTabHeadersArray) ||
						array_key_exists($key = $i, $this->objTabHeadersArray)) {
					$objHeader = $this->objTabHeadersArray[$key];
					if ($objHeader instanceof QControl) {
						$strResult .= $objHeader->GetControlHtml();
					} else {
						$strResult .= (string)$objHeader;
					}
				} else {
					$strResult .= 'Tab '. ($i+1);
				}
				$strResult .= '</a></li>';
			}

			$strResult .= '</ul>';
			return $strResult;
		}

		/**
		 * Set the tab header for a tab
		 * @param integer|QControl|string $mixHeaderIndicator either the 0-based index of the header, or the section control or that control's id
		 * @param string|QControl $mixHeader string or control to render as the tab header
		 * @return void
		 */
		public function SetHeader($mixHeaderIndicator, $mixHeader) {
			$key = ($mixHeaderIndicator instanceof QControl) ? $mixHeaderIndicator->ControlId : $mixHeaderIndicator;
			$this->objTabHeadersArray[$key] = $mixHeader;
		}

		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				case 'Headers':
					try {
						$this->objTabHeadersArray = QType::Cast($mixValue, QType::ArrayType);
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