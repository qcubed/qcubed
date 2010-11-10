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
	 * @property boolean $Modal
	 * @property string $AnyKeyCloses
	 */
	class QDialogBox extends QPanel {
		protected $strTitle = "";		
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;

		// APPEARANCE
		protected $strMatteColor = '#000000';
		protected $intMatteOpacity = 50;
		protected $strWidth = '350';
		/* protected $strCssClass = 'dialogbox';  this is now handled through jQuery UI */

		// BEHAVIOR
		protected $blnMatteClickable = true;
		protected $blnAnyKeyCloses = false;
		
		protected $blnModal = true;

		public function  __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->blnDisplay = false;
		}

		public function GetShowDialogJavaScript() {
			$strOptions = 'autoOpen: false';
			$strOptions .= ', modal: '.($this->blnModal ? 'true' : 'false');
			if ($this->strTitle)
				$strOptions .= ', title: "'.$this->strTitle.'"';
			if ($this->strCssClass)
				$strOptions .= ', dialogClass: "'.$this->strCssClass.'"';
			if (null === $this->strWidth)
				$strOptions .= ", width: 'auto'";
			else if ($this->strWidth)
				$strOptions .= ', width: '. $this->strWidth;
			if (null === $this->strHeight)
				$strOptions .= ", height: 'auto'";
			else if ($this->strHeight)
				$strOptions .= ', height: '. $this->strHeight;
			$strParentId = $this->ParentControl ? $this->ParentControl->ControlId : $this->Form->FormId;
			//move both the dialog and the matte back into the form, to ensure they continue to function
			$strOptions .= sprintf(', open: function() { $j(this).parent().appendTo("#%s"); $j(".ui-widget-overlay").appendTo("#%s"); }', $strParentId, $strParentId);

			return sprintf('$j(qc.getW("%s")).dialog({%s}); $j(qc.getW("%s")).dialog("open");', $this->strControlId, $strOptions, $this->strControlId);
		}

		public function GetHideDialogJavaScript() {
			return sprintf('$j(qc.getW("%s")).dialog("close");', $this->strControlId);
		}

		public function ShowDialogBox() {
			if (!$this->blnVisible)
				$this->Visible = true;
			if (!$this->blnDisplay)
				$this->Display = true;
			QApplication::ExecuteJavaScript($this->GetShowDialogJavaScript());
			$this->blnWrapperModified = false;
		}

		public function HideDialogBox() {
			$this->blnDisplay = false;
			QApplication::ExecuteJavaScript($this->GetHideDialogJavaScript());
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
				case "Modal": return $this->blnModal;

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

				case "Modal":
					try { 
						$this->blnModal = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Width":
					try {
						if (null === $mixValue || 'auto' === $mixValue) {
							$this->strWidth = null;
						} else {
							$mixValue = str_replace("px", "", strtolower($mixValue)); // Replace the text "px" (pixels) with empty string if it's there
							
							// for now, jQuery dialog only accepts integers as width
							$this->strWidth = QType::Cast($mixValue, QType::Integer);
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Height":
					try {
						if (null === $mixValue || 'auto' === $mixValue) {
							$this->strHeight = null;
						} else {
							$mixValue = str_replace("px", "", strtolower($mixValue)); // Replace the text "px" (pixels) with empty string if it's there

							// for now, jQuery dialog only accepts integers as height
							$this->strHeight = QType::Cast($mixValue, QType::Integer);
						}
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