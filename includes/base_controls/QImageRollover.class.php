<?php
	/**
	 * This file contains the QImageRollover class.
	 *
	 * @package Controls
	 */

	/**
	 * @package Controls
	 *
	 * @property mixed $ImageStandard
	 * @property mixed $ImageHover
	 * @property string $AltText
	 * @property string $LinkUrl
	 */
	class QImageRollover extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// BEHAVIOR
		protected $mixImageStandard;
		protected $mixImageHover;
		protected $strLinkUrl;
		protected $strCustomLinkStyleArray = array();
		/** @var  QTagStyler */
		protected $objLinkStyler;

		//////////
		// Methods
		//////////
		public function ParsePostData() {}

		/**
		 * Returns an object you can use to style the 'href' tag that will surround the picture.
		 * @return mixed|QTagStyler
		 */
		public function GetLinkStyler() {
			if (!$this->objLinkStyler) {
				$this->objLinkStyler = new QTagStyler();
			}
			return $this->objLinkStyler;
		}

		/**
		 * Sets a css style for the link.
		 *
		 * @param $strName
		 * @param $strValue
		 */
		public function SetCustomLinkStyle($strName, $strValue) {
			if ($this->GetLinkStyler()->SetCssStyle($strName, $strValue)) {
				$this->blnModified = true;
			}
		}
		
		public function GetCustomLinkStyle($strName) {
			return $this->GetLinkStyler()->GetCssStyle($strName);
		}

		public function RemoveCustomLinkStyle($strName) {
			if ($this->RemoveCssStyle($strName)) {
				$this->blnModified = true;
			}
		}
		
		protected function GetControlHtml() {
			$attrOverrides['id'] = ($this->strLinkUrl) ? $this->strControlId . '_img' : $this->strControlId; // if a link, the parent tag will be the main tag
			$attrOverrides['src'] = ($this->mixImageStandard instanceof QImageBase) ? $this->mixImageStandard->RenderAsImgSrc(false) : $this->mixImageStandard;
			if (!$this->AltText) {
				$attrOverrides['alt'] = $this->ToolTip;
			}
			$strHtml = $this->RenderTag ('img', $attrOverrides, null, null, true);

			if ($this->strLinkUrl) {
				$linkOverrides['href'] = $this->strLinkUrl;
				$linkOverrides['id'] = $this->strControlId;
				$linkOverrides['name'] = $this->strControlId;
				$this->GetLinkStyler()->ToolTip = $this->ToolTip;	// copy tooltip
				$strLinkAttributes = $this->GetLinkStyler()->RenderHtmlAttributes($linkOverrides);
				$strHtml = QHtml::RenderTag('a', $strLinkAttributes, $strHtml);
			}
			return $strHtml;
		}

		public function GetEndScript() {
			$strToReturn = parent::GetEndScript();
			$strControlId = ($this->strLinkUrl) ? $this->strControlId . '_img' : $this->strControlId;
			if ($this->blnVisible && $this->mixImageHover) {
				$strToReturn .= sprintf('$j("#%s").hover(function(){$j("#%s").attr("src", "%s"); }, function(){$j("#%s").attr("src", "%s"); });', $strControlId, 
					$strControlId,
					($this->mixImageHover instanceof QImageBase) ? $this->mixImageHover->RenderAsImgSrc(false) : $this->mixImageHover,
					$strControlId,					
					($this->mixImageStandard instanceof QImageBase) ? $this->mixImageStandard->RenderAsImgSrc(false) : $this->mixImageStandard
				);
			}
			return $strToReturn;
		}

		public function Validate() {return true;}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "ImageStandard": return $this->mixImageStandard;
				case "ImageHover": return $this->mixImageHover;
				case "LinkUrl": return $this->strLinkUrl;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				case "ImageStandard":
					try {
						if ($mixValue instanceof QImageBase)
							$this->mixImageStandard = $mixValue;
						else
							$this->mixImageStandard = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageHover":
					try {
						if ($mixValue instanceof QImageBase)
							$this->mixImageHover = $mixValue;
						else
							$this->mixImageHover = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "LinkUrl":
					try {
						$this->strLinkUrl = QType::Cast($mixValue, QType::String);
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