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
	 * @deprecated Use QDialog instead
	 */
	class QDialogBox extends QPanel {
		protected $strTitle = "";		
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;

		// APPEARANCE
		protected $strMatteColor = '#000000';
		protected $intMatteOpacity = 50;
		protected $strDialogWidth = '350';
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
			if (null === $this->strDialogWidth)
				$strOptions .= ", width: 'auto'";
			else if ($this->strDialogWidth)
				$strOptions .= ', width: '. $this->strDialogWidth;
			if (null === $this->strHeight)
				$strOptions .= ", height: 'auto'";
			else if ($this->strHeight)
				$strOptions .= ', height: '. $this->strHeight;
			//move both the dialog and the matte back into the form, to ensure they continue to function
			$strOptions .= ', open: function() { $j(this).parent().appendTo($j("form:first")); }';

			return sprintf('$j(qc.getW("%s")).dialog({%s}).dialog("open");', $this->strControlId, $strOptions, $this->strControlId);
		}

		public function GetHideDialogJavaScript() {
			return sprintf('var $dlg = $j(qc.getW("%s")); if($dlg.is(":ui-dialog")) { $dlg.dialog("close"); }', $this->strControlId);
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

		public function GetEndScript() {
			$strToReturn = parent::GetEndScript();
			if ($this->Visible && $this->Display) {
				$strToReturn .= "; ". $this->GetShowDialogJavaScript();
			}
			return $strToReturn;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP magic method
		 *
		 * @param string $strName Property name
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Width": return $this->strDialogWidth;
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

		/**
		 * PHP magic method
		 *
		 * @param string $strName  Property name
		 * @param string $mixValue Property value
		 *
		 * @return mixed
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
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
							$this->strDialogWidth = null;
						} else {
							$mixValue = str_replace("px", "", strtolower($mixValue)); // Replace the text "px" (pixels) with empty string if it's there
							
							// for now, jQuery dialog only accepts integers as width
							$this->strDialogWidth = QType::Cast($mixValue, QType::Integer);
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