<?php
	/**
	 * This file contains the QDataGridColumn and QFilterType class.
	 *
	 * @package Controls
	 */

	abstract class QFilterType {
		const None = '';
		const TextFilter = 'Text';
		const ListFilter = 'List';
	}

	/**
	 * This defines a specific column <td> for a DataGrid
	 * All the appearance properties should be self-explanatory.
	 *
	 * The SortByCommand and ReverseSortByCommand are both optional -- and are explained in more
	 * depth in DataGrid.inc
	 *
	 * @package Controls
	 *
	 * @property string $BackColor
	 * @property string $BorderColor
	 * @property string $BorderStyle
	 * @property string $BorderWidth
	 * @property string $CssClass
	 * @property boolean $FontBold
	 * @property boolean $FontItalic
	 * @property string $FontNames
	 * @property boolean $FontOverline
	 * @property string $FontSize
	 * @property boolean $FontStrikeout
	 * @property boolean $FontUnderline
	 * @property string $ForeColor
	 * @property string $HorizontalAlign
	 * @property string $VerticalAlign
	 * @property string $Width
	 * @property boolean $Wrap
	 * @property mixed $OrderByClause
	 * @property mixed $ReverseOrderByClause
	 * @property mixed $FilterByCommand
	 * @property integer $FilterBoxSize
	 * @property string $FilterType
	 * @property mixed $FilterList
	 * @property integer $FilterColId
	 * @property string $FilterPrefix
	 * @property string $FilterPostfix
	 * @property mixed $FilterConstant
	 * @property mixed $Filter
	 * @property mixed $SortByCommand
	 * @property mixed $ReverseSortByCommand
	 * @property string $Html is the contents of the column itself -- the $this->strHtml contents can contain backticks ` to deliniate commands that are to be PHP evaled (again, see DataGrid.inc for more info)
	 * @property string $Name is the name of the column, as displayed in the DataGrid's header row for that column
	 * @property boolean $HtmlEntities
	 * @property boolean $HasResetButton
	 */
	class QDataGridColumn extends QBaseClass {
		// APPEARANCE
		protected $strBackColor = null;
		protected $strBorderColor = null;
		protected $strBorderStyle = QBorderStyle::NotSet;
		protected $strBorderWidth = null;
		protected $strCssClass = null;
		protected $blnFontBold = false;
		protected $blnFontItalic = false;
		protected $strFontNames = null;
		protected $blnFontOverline = false;
		protected $strFontSize = null;
		protected $blnFontStrikeout = false;
		protected $blnFontUnderline = false;
		protected $strForeColor = null;
		protected $strHorizontalAlign = QHorizontalAlign::NotSet;
		protected $strVerticalAlign = QVerticalAlign::NotSet;
		protected $strWidth = null;
		protected $blnWrap = true;
		protected $blnHasResetButton = false;

		// BEHAVIOR
		protected $objOrderByClause = null;
		protected $objReverseOrderByClause = null;

		protected $FilterBoxSize = '10';
		protected $strFilterType = QFilterType::None;
		protected $intFilterColId = null;
		protected $arrFilterList = array();

		//The filter this column has applied
		protected $objFilter = null;
		//a Filter that gets applied in addition to $Filter when the user filters on this column
		protected $objFilterConstant = null;

		protected $strFilterPrefix = '';
		protected $strFilterPostfix = '';

		//manual filter commands
		protected $arrFilterByCommand = null; 

		// MISC
		protected $strName;
		protected $strHtml;
		protected $blnHtmlEntities = true;

		public function __construct($strName, $strHtml = null, $objOverrideParameters = null) {
			$this->strName = $strName;
			$this->strHtml = $strHtml;

			$objOverrideArray = func_get_args();
			if (count($objOverrideArray) > 2)
				try {
					unset($objOverrideArray[0]);
					unset($objOverrideArray[1]);
					$this->OverrideAttributes($objOverrideArray);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}

		public function GetAttributes($blnIncludeCustom = true, $blnIncludeAction = true) {
			$strToReturn = "";
			$strStyle = "";			

			if (!$this->blnWrap)
				$strToReturn .= 'nowrap="nowrap" ';

			switch ($this->strHorizontalAlign) {
				case QHorizontalAlign::Left:
					$strStyle .= 'text-align:left;';
					break;
				case QHorizontalAlign::Right:
					$strStyle .= 'text-align:right;';
					break;
				case QHorizontalAlign::Center:
					$strStyle .= 'text-align:center;';
					break;
				case QHorizontalAlign::Justify:
					$strStyle .= 'text-align:justify;';
					break;
			}

			switch ($this->strVerticalAlign) {
				case QVerticalAlign::Top:
					$strStyle .= 'vertical-align:top;';
					break;
				case QVerticalAlign::Middle:
					$strStyle .= 'vertical-align:middle;';
					break;
				case QVerticalAlign::Bottom:
					$strStyle .= 'vertical-align:bottom;';
					break;
			}

			if ($this->strCssClass)
				$strToReturn .= sprintf('class="%s" ', $this->strCssClass);

			if ($this->strWidth) {
				if (is_numeric($this->strWidth))
					$strStyle .= sprintf("width:%spx;", $this->strWidth);
				else
					$strStyle .= sprintf("width:%s;", $this->strWidth);
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

		//creates a list for a column's filter
		//2 ways of calling the fuction: specify only one paramter and it should be an advanced list item
		//the other way is to call it using 2 parameters with first one being a name and other a value
		public function FilterAddListItem($arg1=null, $arg2=null) {
			if($this->arrFilterList === null) {
				$this->arrFilterList = array();
			}
			if($arg1 !== null && $arg2 instanceof QQCondition) {
				//they passed in a name, condition pair
				$this->arrFilterList[$arg1] = $arg2;
				$this->strFilterType = QFilterType::ListFilter;
			} elseif ($arg1 !== null) {
				//else we are trying to make a simple list but make sure the name is supplied
				$this->arrFilterList[$arg1] = $arg2;
				$this->strFilterType = QFilterType::ListFilter;
			} else {
				//else fail the function and let the user know about correct use of parameters
				throw new Exception("Please specify a name and QQCondition pair OR a name and value pair as parameters.");
			}
		}

		public function FilterActivate($strIndex = 0) {
			if ($this->strFilterType == QFilterType::TextFilter && count($this->arrFilterList) > 1) {
				throw new Exception('Trying to activate a Filter when multiple filters are stored (potential ListFilter).');
				return;
			}

			//really, this shouldn't happen
			if(null === $strIndex) {
				return $this->ClearFilter();
			}

			$this->objFilter = $this->arrFilterList[$strIndex];
			return true;
		}

		public function FilterSetOperand($mixOperand) {
			try {
				if(null === $this->objFilter) {
					return;
				} elseif($this->objFilter instanceof QQConditionComparison) {
					if ($mixOperand instanceof QQNamedValue) {
						$this->objFilter->mixOperand = $mixOperand;
					} else if ($mixOperand instanceof QQAssociationNode) {
						throw new QInvalidCastException('Comparison operand cannot be an Association-based QQNode', 3);
					} else if ($mixOperand instanceof QQCondition) {
						throw new QInvalidCastException('Comparison operand cannot be a QQCondition', 3);
					} else if ($mixOperand instanceof QQClause) {
						throw new QInvalidCastException('Comparison operand cannot be a QQClause', 3);
					} else if ($mixOperand instanceof QQNode) {
						if (!$mixOperand->_ParentNode)
							throw new QInvalidCastException('Unable to cast "' . $mixOperand->_Name . '" table to Column-based QQNode', 3);
						$this->objFilter->mixOperand = $mixOperand;
					} else {
						//must be a string, apply the pre and postfix (This also handles custom filters)
						$mixOperand = $this->strFilterPrefix . $mixOperand . $this->strFilterPostfix;
						$this->objFilter->mixOperand = $mixOperand;
					}
				} else {
					throw new Exception('Trying to set Operand on a filter that does not take operands');
				}
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function ClearFilter() 
		{
			$this->objFilter = null;
			if($this->arrFilterByCommand !== null)
				$this->arrFilterByCommand['value'] = null;
		}


		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
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
				case "HorizontalAlign": return $this->strHorizontalAlign;
				case "VerticalAlign": return $this->strVerticalAlign;
				case "Width": return $this->strWidth;
				case "Wrap": return $this->blnWrap;

				// BEHAVIOR
				case "OrderByClause": return $this->objOrderByClause;
				case "ReverseOrderByClause": return $this->objReverseOrderByClause;

				case "FilterByCommand": return $this->arrFilterByCommand;
				case "FilterBoxSize": return $this->FilterBoxSize;
				case "FilterType": return $this->strFilterType;
				case "FilterList": return $this->arrFilterList;
				case "FilterColId": return $this->intFilterColId;

				case "FilterPrefix": return $this->strFilterPrefix;
				case "FilterPostfix": return $this->strFilterPostfix;

				case "FilterConstant": return $this->objFilterConstant;
				case "Filter": return $this->objFilter;

				// MANUAL QUERY BEHAVIORS
				case "SortByCommand": return $this->objOrderByClause;
				case "ReverseSortByCommand": return $this->objReverseOrderByClause;

				// MISC
				case "Html": return $this->strHtml;
				case "Name": return $this->strName;
				case "HtmlEntities": return $this->blnHtmlEntities;
				case "HasResetButton": return $this->blnHasResetButton;

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
			switch ($strName) {
				// APPEARANCE
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
				case "Width":
					try {
						$this->strWidth = QType::Cast($mixValue, QType::String);
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

					
				// BEHAVIOR
				case "OrderByClause":
					try {
						$this->objOrderByClause = QType::Cast($mixValue, 'QQOrderBy');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ReverseOrderByClause":
					try {
						$this->objReverseOrderByClause = QType::Cast($mixValue, 'QQOrderBy');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FilterConstant":
					try {
						$this->objFilterConstant = $mixValue;
						break;
					} catch(QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				case "FilterPrefix":
					try {
						$this->strFilterPrefix = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FilterPostfix":
					try {
						$this->strFilterPostfix = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "FilterType":
					try {
						$this->strFilterType= QType::Cast($mixValue, QType::String);
						if($this->strFilterType == QFilterType::None)
						{
							$this->Filter = null;
							$this->FilterByCommand = null;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "FilterColId":
					try {
						$this->intFilterColId = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "FilterByCommand": //for custom filters
					try {
						if(null === $mixValue)
						{
							$this->arrFilterByCommand = null;
							break;
						}

						$arr = QType::Cast($mixValue, QType::ArrayType);
						//ensure pre and postfix exist
						if(!isset($arr['prefix']))
							$arr['prefix'] = '';
						if(!isset($arr['postfix']))
							$arr['postfix'] = '';
						$this->arrFilterByCommand = $arr;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Filter":
					try {
						if(null === $mixValue)
							$this->arrFilterList = null;
						else
							$this->arrFilterList = array($mixValue);
						break;
					} catch(QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}



				case "FilterBoxSize":
					try {
						$this->FilterBoxSize = QType::Cast($mixValue, QType::Integer);
						$this->FilterType = 'Text';
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "FilterList":
					try {
						$this->arrFilterList = QType::Cast($mixValue, QType::ArrayType);
						$this->strFilterType = QFilterType::ListFilter;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// MANUAL QUERY BEHAVIOR
				case "FilterByCommand":
					try {
						if(null === $mixValue) {
							$this->arrFilterByCommand = null;
							break;
						}

						$arr = QType::Cast($mixValue, QType::ArrayType);
						//ensure pre and postfix exist
						if(!isset($arr['prefix']))
							$arr['prefix'] = '';
						if(!isset($arr['postfix']))
							$arr['postfix'] = '';
						$this->arrFilterByCommand = $arr;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "SortByCommand":
					try {
						$this->objOrderByClause = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ReverseSortByCommand":
					try {
						$this->objReverseOrderByClause = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
					
				// MISC
				case "Html":
					try {
						$this->strHtml = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Name":
					try {
						$this->strName = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "HtmlEntities":
					try {
						$this->blnHtmlEntities = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "HasResetButton":
					try {
						$this->blnHasResetButton = QType::Cast($mixValue, QType::Boolean);
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