<?php
	/**
	 * This file contains the QDialogBox class.
	 *
	 * @package Controls
	 */

	/**
	 * @package Controls
	 *
	 * @property string $MatteColor
	 * @property string $MatteOpacity
	 * @property string $MatteClickable
	 * @property string $AnyKeyCloses
	 */
	class QDialogBox extends QPanel {
		protected $strTitle = "";		
		protected $strPosition = QPosition::Absolute;
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;

		// APPEARANCE
		protected $strMatteColor = '#000000';
		protected $intMatteOpacity = 50;
		/* protected $strCssClass = 'dialogbox';  this is now handled through jQuery UI */

		// BEHAVIOR
		protected $blnMatteClickable = true;
		protected $blnAnyKeyCloses = false;

		protected function GetControlHtml() {
			$strToReturn = parent::GetControlHtml();
			return $strToReturn;
		}

		public function GetEndScript() {
			$strToReturn = parent::GetEndScript();
			return $strToReturn;
		}

		public function ShowDialogBox() {
			$this->SetCustomAttribute("title", $this->strTitle);		// not sure if this is the right place to do it, we could probably do it in the set()	
			if (!$this->blnVisible)
				$this->Visible = true;
			if (!$this->blnDisplay)
				$this->Display = true;

			$strOptions = "";
			if ($this->strCssClass != "")
				$strOptions .= sprintf(', dialogClass: "%s"', $this->strCssClass);
				
			$strOptions = sprintf(', modal: true', $this->strCssClass);
				
			QApplication::ExecuteJavaScript(sprintf('$j("#%s").dialog({autoOpen: false %s })', $this->strControlId, $strOptions));
			QApplication::ExecuteJavaScript(sprintf('$j("#%s").dialog("open")', $this->strControlId));
			
			$this->blnWrapperModified = false;
		}

		public function HideDialogBox() {
			QApplication::ExecuteJavaScript(sprintf('$j("#%s").dialog("close")', $this->strControlId));
			$this->blnWrapperModified = false;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Title": return $this->strTitle;
				case "MatteColor": return $this->strMatteColor;
				case "MatteOpacity": return $this->intMatteOpacity;

				// BEHAVIOR
				case "MatteClickable": return $this->blnMatteClickable;
				case "AnyKeyCloses": return $this->blnAnyKeyCloses;

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
			$this->blnModified = true;

			switch ($strName) {
				case "Title":
					try {
						$this->strTitle = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
				case "MatteColor":
					try {
						$this->strMatteColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "MatteOpacity":
					try {
						$this->intMatteOpacity = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "MatteClickable":
					try {
						$this->blnMatteClickable = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "AnyKeyCloses":
					try {
						$this->blnAnyKeyCloses = QType::Cast($mixValue, QType::Boolean);
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
		}
	}
?>