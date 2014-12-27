<?php
	/**
	 * This file contains the QDataGridRowStyle class.
	 *
	 * @package Controls
	 */

	/**
	 * This defines a the stle for a row <tr> for a DataGrid
	 * All the appearance properties should be self-explanatory.
	 *
	 * @package  Controls
	 * @property string  $BackColor       sets the CSS background-color of the control
	 * @property string  $BorderColor     sets the CSS border-color of the control
	 * @property string  $BorderStyle     is used to set CSS border-style by {@link QBorderStyle}
	 * @property string  $BorderWidth     sets the CSS border-width of the control
	 * @property string  $CssClass        sets or returns the CSS class for this control
	 * @property boolean $FontBold        sets the font bold or normal
	 * @property boolean $FontItalic      sets the Font italic or normal
	 * @property string  $FontNames       sets the name of used fonts
	 * @property boolean $FontOverline    will the text have a line over it?
	 * @property string  $FontSize        sets the font-size of the control
	 * @property boolean $FontStrikeout   will the font in the text be striked at the middle (cut-through)?
	 * @property boolean $FontUnderline   will the font in the test be underlined?
	 * @property string  $ForeColor       sets the forecolor of the control (like fontcolor)
	 * @property string  $Height          Height of the row
	 * @property string  $HorizontalAlign Type of horizintal assignement (constant from QHorizontalAlign)
	 * @property string  $VerticalAlign   Type of vertical assignement (constant from QVerticalAlign)
	 *
	 * @property boolean $Wrap Should the contents inside the cell be wrapped?
	 *                         This property is not supported by HTML5 and should be considered deprecated.
	 */
	class QDataGridRowStyle extends QBaseClass {
		/** @var null|string the Background color of the row */
		protected $strBackColor = null;
		/** @var null|string the border color of the row */
		protected $strBorderColor = null;
		/** @var string style of the border  */
		protected $strBorderStyle = QBorderStyle::NotSet;
		/** @var null|string Width of the border */
		protected $strBorderWidth = null;
		/** @var null|string CSS class for the row */
		protected $strCssClass = null;
		/** @var bool Determines if the font of text will be bold ?*/
		protected $blnFontBold = false;
		/** @var bool Determines if the font of text will be italicized? */
		protected $blnFontItalic = false;
		/** @var null|string CSS font-family property of the text in the row */
		protected $strFontNames = null;
		/** @var bool Determines if the text will have an overline? */
		protected $blnFontOverline = false;
		/**
		 * @var null|string|integer Font size of the text
		 *                          Integer value will mean pixels
		 *                          String value will be sent to browser as is
		 */
		protected $strFontSize = null;
		/** @var bool Determines if the font of text will be striked out */
		protected $blnFontStrikeout = false;
		/** @var bool Determines if the font of text will be underlined */
		protected $blnFontUnderline = false;
		/** @var null|string Color of the text */
		protected $strForeColor = null;
		/**
		 * @var null|string|integer Height of the row
		 *                          Integer value will mean pixels
		 *                          String value will be sent to browser as is
		 */
		protected $strHeight = null;
		/** @var string Horizontal alignment of the text within */
		protected $strHorizontalAlign = QHorizontalAlign::NotSet;
		/** @var string Vertical alignment of the text within */
		protected $strVerticalAlign = QVerticalAlign::NotSet;
		/** @var bool Determines if the contents will be wrapped in the cell or not */
		protected $blnWrap = true;

		/**
		 * Allows the row style to be overriden with an already existing QDataGridRowStyle
		 *
		 * @param QDataGridRowStyle $objOverrideStyle
		 *
		 * @return QDataGridRowStyle
		 */
		public function ApplyOverride(QDataGridRowStyle $objOverrideStyle) {
			$objNewStyle = clone $this;

			if (!$objOverrideStyle->Wrap)
				$objNewStyle->Wrap = false;
			
			if (($objOverrideStyle->HorizontalAlign) && ($objOverrideStyle->HorizontalAlign != QHorizontalAlign::NotSet))
				$objNewStyle->HorizontalAlign = $objOverrideStyle->HorizontalAlign;

			if (($objOverrideStyle->VerticalAlign) && ($objOverrideStyle->VerticalAlign != QVerticalAlign::NotSet))
				$objNewStyle->VerticalAlign = $objOverrideStyle->VerticalAlign;

			if ($objOverrideStyle->Height)
				$objNewStyle->Height = $objOverrideStyle->Height;

			if ($objOverrideStyle->CssClass)
				$objNewStyle->CssClass = $objOverrideStyle->CssClass;

			if ($objOverrideStyle->ForeColor)
				$objNewStyle->ForeColor = $objOverrideStyle->ForeColor;
			if ($objOverrideStyle->BackColor)
				$objNewStyle->BackColor = $objOverrideStyle->BackColor;
			if ($objOverrideStyle->BorderColor)
				$objNewStyle->BorderColor = $objOverrideStyle->BorderColor;
			if ($objOverrideStyle->BorderWidth)
				$objNewStyle->BorderWidth = $objOverrideStyle->BorderWidth;
			if (($objOverrideStyle->BorderStyle) && ($objOverrideStyle->BorderStyle != QBorderStyle::NotSet))
				$objNewStyle->BorderStyle = $objOverrideStyle->BorderStyle;

			if ($objOverrideStyle->FontNames)
				$objNewStyle->FontNames = $objOverrideStyle->FontNames;
			if ($objOverrideStyle->FontSize)
				$objNewStyle->FontSize = $objOverrideStyle->FontSize;

			if ($objOverrideStyle->FontBold)
				$objNewStyle->FontBold = true;
			if ($objOverrideStyle->FontItalic)
				$objNewStyle->FontItalic = true;

			if ($objOverrideStyle->FontUnderline)
				$objNewStyle->FontUnderline = true;
			if ($objOverrideStyle->FontOverline)
				$objNewStyle->FontOverline = true;
			if ($objOverrideStyle->FontStrikeout)
				$objNewStyle->FontStrikeout = true;

			return $objNewStyle;
		}

		/**
		 * Returns HTML attributes for the QDataGrid row
		 *
		 * @return string HTML attributes
		 */
		public function GetAttributes() {
			$strToReturn = "";

			if (!$this->blnWrap)
				$strToReturn .= 'nowrap="nowrap" ';

			switch ($this->strHorizontalAlign) {
				case QHorizontalAlign::Left:
					$strToReturn .= 'align="left" ';
					break;
				case QHorizontalAlign::Right:
					$strToReturn .= 'align="right" ';
					break;
				case QHorizontalAlign::Center:
					$strToReturn .= 'align="center" ';
					break;
				case QHorizontalAlign::Justify:
					$strToReturn .= 'align="justify" ';
					break;
			}

			switch ($this->strVerticalAlign) {
				case QVerticalAlign::Top:
					$strToReturn .= 'valign="top" ';
					break;
				case QVerticalAlign::Middle:
					$strToReturn .= 'valign="middle" ';
					break;
				case QVerticalAlign::Bottom:
					$strToReturn .= 'valign="bottom" ';
					break;
			}

			if ($this->strCssClass)
				$strToReturn .= sprintf('class="%s" ', $this->strCssClass);

			$strStyle = "";			
			
			if ($this->strHeight) {
				if (!is_numeric($this->strHeight))
					$strStyle .= sprintf("height:%s;", $this->strHeight);
				else
					$strStyle .= sprintf("height:%spx;", $this->strHeight);
			}
			if ($this->strForeColor)
				$strStyle .= sprintf("color:%s;", $this->strForeColor);
			if ($this->strBackColor)
				$strStyle .= sprintf("background-color:%s;", $this->strBackColor);
			if ($this->strBorderColor)
				$strStyle .= sprintf("border-color:%s;", $this->strBorderColor);
			if ($this->strBorderWidth) {
				$strStyle .= sprintf("border-width:%s;", $this->strBorderWidth);
				if ((!$this->strBorderStyle) || ($this->strBorderStyle == QBorderStyle::NotSet))
					// For "No Border Style" -- apply a "solid" style because width is set
					$strStyle .= "border-style:solid;";
			}
			if (($this->strBorderStyle) && ($this->strBorderStyle != QBorderStyle::NotSet))
				$strStyle .= sprintf("border-style:%s;", $this->strBorderStyle);
			
			if ($this->strFontNames)
				$strStyle .= sprintf("font-family:%s;", $this->strFontNames);
			if ($this->strFontSize) {
				if (is_numeric($this->strFontSize))
					$strStyle .= sprintf("font-size:%spx;", $this->strFontSize);
				else
					$strStyle .= sprintf("font-size:%s;", $this->strFontSize);
			}
			if ($this->blnFontBold)
				$strStyle .= "font-weight:bold;";
			if ($this->blnFontItalic)
				$strStyle .= "font-style:italic;";
			
			$strTextDecoration = "";
			if ($this->blnFontUnderline)
				$strTextDecoration .= "underline ";
			if ($this->blnFontOverline)
				$strTextDecoration .= "overline ";
			if ($this->blnFontStrikeout)
				$strTextDecoration .= "line-through ";
			
			if ($strTextDecoration) {
				$strTextDecoration = trim($strTextDecoration);
				$strStyle .= sprintf("text-decoration:%s;", $strTextDecoration);
			}
			
			if ($strStyle)
				$strToReturn .= sprintf('style="%s" ', $strStyle);
			
			return $strToReturn;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case "BackColor": return $this->strBackColor;
				case "BorderColor": return $this->strBorderColor;
				case "BorderStyle": return $this->strBorderStyle;
				case "BorderWidth": return $this->strBorderWidth;
				case "CssClass": return $this->strCssClass;
				case "FontBold": return $this->blnFontBold;
				case "FontItalic": return $this->blnFontItalic;
				case "FontNames": return $this->strFontNames;
				case "FontOverline": return $this->blnFontOverline;
				case "FontSize": return $this->strFontSize;
				case "FontStrikeout": return $this->blnFontStrikeout;
				case "FontUnderline": return $this->blnFontUnderline;
				case "ForeColor": return $this->strForeColor;
				case "Height": return $this->strHeight;
				case "HorizontalAlign": return $this->strHorizontalAlign;
				case "VerticalAlign": return $this->strVerticalAlign;
				case "Wrap": return $this->blnWrap;

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
		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "BackColor": 
					try {
						$this->strBackColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderColor":
					try {
						$this->strBorderColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderStyle":
					try {
						$this->strBorderStyle = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderWidth":
					try {
						$this->strBorderWidth = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CssClass":
					try {
						$this->strCssClass = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontBold":
					try {
						$this->blnFontBold = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontItalic":
					try {
						$this->blnFontItalic = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontNames":
					try {
						$this->strFontNames = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontOverline":
					try {
						$this->blnFontOverline = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontSize":
					try {
						$this->strFontSize = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontStrikeout":
					try {
						$this->blnFontStrikeout = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontUnderline":
					try {
						$this->blnFontUnderline = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ForeColor":
					try {
						$this->strForeColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Height":
					try {
						$this->strHeight = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "HorizontalAlign":
					try {
						$this->strHorizontalAlign = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "VerticalAlign":
					try {
						$this->strVerticalAlign = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Wrap":
					try {
						$this->blnWrap = QType::Cast($mixValue, QType::Boolean);
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