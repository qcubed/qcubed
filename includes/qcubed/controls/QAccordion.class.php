<?php
	class QAccordion extends QAccordionBase
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
	}
?>