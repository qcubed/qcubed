<?php
	/**
	 * This file contains the QDataGridEgacyColumn and QFilterType class.
	 *
	 * @package Controls
	 */

	/**
	 * Class QFilterType: Type of filter which can be implemented on a QDataGridLegacy's Column
	 *
	 * A constant from this class determines the type of filter which is to be applied to the UI
	 * in a QDataGridLegacy and hence determines the type of query to be sent to the database for filtering
	 * results based on input
	 */
	abstract class QFilterType {
		/** No filter */
		const None = '';
		/** Text search filter */
		const TextFilter = 'Text';
		/** List type filter (mostly used for Boolean fields) */
		const ListFilter = 'List';
	}

	/**
	 * This defines a specific column <td> for a DataGrid
	 * All the appearance properties should be self-explanatory.
	 * The SortByCommand and ReverseSortByCommand are both optional -- and are explained in more
	 * depth in DataGrid.inc

	 *
*@package Controls
	 * @property string               $BackColor            Background colour of an element of this column
	 * @property string               $BorderColor          Border colour of an element of this column
	 * @property string               $BorderStyle          Border style of an element of this column (constant from QBorderStyle class)
	 * @property string               $BorderWidth          Border width of an element of this column
	 * @property string               $CssClass             CSS class of an element of this column
	 * @property boolean              $FontBold             Determines if the font will be bold
	 * @property boolean              $FontItalic           Determines if the font will be italicized
	 * @property string               $FontNames            Font family to be used (can use a value from QFontFamily class)
	 * @property boolean              $FontOverline         Determines if the font will have an overline
	 * @property string|integer       $FontSize             Font size of the element in this column
	 * @property boolean              $FontStrikeout        Determines if the font will be striked out
	 * @property boolean              $FontUnderline        Determines if the font will be underlined
	 * @property null|string          $ForeColor            Text Color of the element in this column
	 * @property string               $HorizontalAlign      The horizontal text alignment attribute for the element in this column
	 * @property string               $VerticalAlign        The vertical alignment attribute for the element in this column
	 * @property string|integer       $Width                Column width
	 * @property boolean              $Wrap                 Determines if the column will have nowrap html attribute set on it or not
	 * @property mixed                $OrderByClause        The ordering clause associated with this column
	 * @property null|QQOrderBy       $ReverseOrderByClause The REVERSED ordering clause associated with this column
	 * @property mixed                $FilterByCommand
	 * @property-read array           $FilterInfo
	 * @property integer              $FilterBoxSize        Determines the width ("size" attribute) of the input control of this column on the filter row
	 * @property string               $FilterType           Type of filter to be used for this column (text/list)
	 * @property mixed                $FilterList
	 * @property integer              $FilterColId          The filter column id to be used for the column
	 * @property string               $FilterPrefix
	 * @property string               $FilterPostfix
	 * @property mixed                $FilterConstant
	 * @property-read mixed           $ActiveFilter
	 * @property-write mixed          $Filter
	 * @property mixed                $SortByCommand
	 * @property mixed                $ReverseSortByCommand
	 * @property string               $Html                 is the contents of the column itself -- the $this->strHtml contents can contain backticks ` to deliniate commands that are to be PHP evaled (again, see DataGrid.inc for more info)
	 * @property string               $Name                 is the name of the column, as displayed in the DataGrid's header row for that column
	 * @property boolean              $HtmlEntities         Determines if the contents of this column have to be processed through HtmlEntities
	 * @property boolean              $HasResetButton       If the concerned row is a filter row then this variable determines if it has a Reset Button on it
	 */
	class QDataGridLegacyColumn extends QBaseClass {
		// APPEARANCE
		/**
		 * @var null|string Background colour of an element of this column
		 *                  null = not specified in rendered HTML  (browser or another CSS rule can determine value)
		 *                  string = applied as-is
		 */
		protected $strBackColor = null;
		/**
		 * @var null|string Border colour of an element of this column
		 *                  null = not specified in rendered HTML (browser or another CSS rule can determine value)
		 *                  string = applied as-is
		 */
		protected $strBorderColor = null;
		/** @var string Border style of an element of this column (constant from QBorderStyle class) */
		protected $strBorderStyle = QBorderStyle::NotSet;
		/**
		 * @var null|string Border width of an element of this column
		 *                  null = not specified in rendered HTML (browser or another CSS rule can determine value)
		 *                  string = applied as-is
		 */
		protected $strBorderWidth = null;
		/**
		 * @var null|string CSS class of an element of this column
		 *                  null = not specified in rendered HTML
		 *                  string = applied as-is
		 */
		protected $strCssClass = null;
		/** @var bool Determines if the font will be bold */
		protected $blnFontBold = false;
		/** @var bool Determines if the font will be italicized */
		protected $blnFontItalic = false;
		/** @var null|string Font family to be used (can use a value from QFontFamily class) */
		protected $strFontNames = null;
		/** @var bool Determines if the font will have an overline */
		protected $blnFontOverline = false;
		/**
		 * @var null|string|integer Font size of the element in this column
		 *                          null    = not specified in rendered HTML (browser or another CSS rule can determine value)
		 *                          string  = applies as-is
		 *                          integer = interpreted as value in pixels
		 */
		protected $strFontSize = null;
		/** @var bool Determines if the font will be striked out */
		protected $blnFontStrikeout = false;
		/** @var bool Determines if the font will be underlined */
		protected $blnFontUnderline = false;
		/**
		 * @var null|string Text Color of the element in this column
		 *                  null = not specified in rendered HTML (browser or another CSS rule can determine value)
		 *                  string = applied as-is
		 */
		protected $strForeColor = null;
		/** @var string The horizontal text alignment attribute for the element in this column */
		protected $strHorizontalAlign = QHorizontalAlign::NotSet;
		/** @var string The vertical alignment attribute for the element in this column */
		protected $strVerticalAlign = QVerticalAlign::NotSet;
		/**
		 * @var null|string|integer Column width
		 *                          null    = not specified in rendered HTML (browser or another CSS rule can determine value)
		 *                          string  = applies as-is
		 *                          integer = interpreted as value in pixels
		 */
		protected $strWidth = null;
		/** @var bool Determines if the column will have nowrap html attribute set on it or not */
		protected $blnWrap = true;

		/** @var bool If the concerned row is a filter row then this variable determines if it has a Reset Button on it */
		protected $blnHasResetButton = false;

		// BEHAVIOR
		/**
		 * @var null|QQOrderBy The ordering clause associated with this column
		 *                     This clause is utilized when user clicks on the top row which can be used to order results
		 */
		protected $objOrderByClause = null;
		/**
		 * @var null|QQOrderBy The REVERSED ordering clause associated with this column
		 */
		protected $objReverseOrderByClause = null;

		/** @var int Determines the width ("size" attribute) of the input control of this column on the filter row */
		protected $intFilterBoxSize = 10;
		/** @var string Type of filter to be used for this column (text/list) */
		protected $strFilterType = QFilterType::None;
		/**
		 * @var null|integer The filter column id to be used for the column
		 *                   It is derived from the index of the column in the datagrid
		 */
		protected $intFilterColId = null;
		protected $arrFilterList = array();

		//The filter this column has applied
		protected $objActiveFilter = null;
		//a Filter that gets applied in addition to $Filter when the user filters on this column
		protected $objFilterConstant = null;

		protected $strFilterPrefix = '';
		protected $strFilterPostfix = '';

		//manual filter commands
		protected $arrFilterByCommand = null; 

		// MISC
		/** @var string Name of the column to be shown on the top row */
		protected $strName;
		/** @var null|string Contents of the column */
		protected $strHtml;
		/** @var bool Determines if the contents of this column have to be processed through HtmlEntities */
		protected $blnHtmlEntities = true;

		/**
		 * Constructor
		 *
		 * @param string      $strName               Name of the column
		 * @param null|string $strHtml               Text for the column (Can be processed through HtmlEntities)
		 * @param null|mixed  $objOverrideParameters Parameters to be overriden (for func_get_args())
		 *
		 * @throws Exception
		 * @throws QCallerException
		 */
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

		/**
		 * Returns the HTML attributes for the column
		 *
		 * @param bool $blnIncludeCustom [For future use only]
		 * @param bool $blnIncludeAction [For future use only]
		 *
		 * @return string
		 */
		public function GetAttributes() {
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

		/**
		 * Creates a list for a column's filter
		 * Two ways of calling the fuction:
		 *      1. specify only one paramter and it should be an advanced list item
		 *      2. the other way is to call it using 2 parameters with first one being a name and other a value
		 *
		 * @param null|string      $arg1
		 * @param null|QQCondition $arg2
		 *
		 * @throws Exception
		 */
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

		/**
		 * Tells whether or not the column has a filter
		 * @return bool Does the column have a filter?
		 */
		public function HasFilter() {
			return $this->ActiveFilter !== null || $this->FilterByCommand !== null || $this->FilterType != QFilterType::None;
		}

		/**
		 * @param mixed $mixFilterValue for the custom filters, $mixFilterValue will be set as FilterByCommand['value'].
		 *                              Otherwise, if $mixFilterValue is a QQ condition, it's set as the active filter.
		 *                              If $mixFilterValue is not a QQ condition, then it's either the value of the
		 *                              filter for text box filters, or it's the index into the filter list for the list
		 *                              box filters.
		 *
		 * @return void
		 */
		public function SetActiveFilterState($mixFilterValue) {
			//deal with any manual filters
			if ($this->FilterByCommand !== null) {
				//update the column's filterByCommand with the user-entered value
				$filter = $this->FilterByCommand;

				if($mixFilterValue !== null)
					$filter['value'] = $mixFilterValue;
				else if(isset($filter['value']))
					unset($filter['value']);

				$this->FilterByCommand = $filter;
			} elseif ($mixFilterValue !== null) { //Handle the other methods differently
				switch ($this->FilterType) {
					case QFilterType::ListFilter:
						$this->objActiveFilter = $this->arrFilterList[$mixFilterValue];
						break;
					default:
					case QFilterType::TextFilter;
						$this->objActiveFilter = $this->arrFilterList[0];
						$this->FilterSetOperand($mixFilterValue);
				}
			} else {
				$this->ClearFilter();
			}
		}
		
		public function GetActiveFilterState() {
			return $this->GetActiveFilterValue();
		}
		
		public function GetActiveFilterValue() {
			$value = null;
			//for manual queries
			if (isset($this->FilterByCommand['value']))
				$value = $this->FilterByCommand['value'];
			//for lists
			elseif (null !== $this->ActiveFilter && $this->FilterType == QFilterType::ListFilter)
				$value = array_search($this->ActiveFilter, $this->FilterList);
			//or for text
			elseif (null !== $this->FilterList && count($this->FilterList) > 0 && $this->FilterType == QFilterType::TextFilter) {
				$value = $this->FilterList[0]->mixOperand;
				if (null !== $value) {
					//Strip prefix and postfix
					if (null !== $this->FilterPrefix) {
						$prefixLength = strlen($this->FilterPrefix);
						if(substr($value, 0, $prefixLength) == $this->FilterPrefix)
							$value = substr($value, $prefixLength);
					}
					if (null !== $this->FilterPostfix) {
						$postfixLength = strlen($this->FilterPostfix);
						if(substr($value, strlen($value) - $postfixLength) == $this->FilterPostfix)
							$value = substr($value, 0, strlen($value) - $postfixLength);
					}
				}
			}
			return $value;
		}
		
		private function FilterSetOperand($mixOperand) {
			try {
				if(null === $this->objActiveFilter) {
					return;
				} elseif($this->objActiveFilter instanceof QQConditionComparison) {
					if ($mixOperand instanceof QQNamedValue) {
						$this->objActiveFilter->mixOperand = $mixOperand;
					} else if ($mixOperand instanceof QQAssociationNode) {
						throw new QInvalidCastException('Comparison operand cannot be an Association-based QQNode', 3);
					} else if ($mixOperand instanceof QQCondition) {
						throw new QInvalidCastException('Comparison operand cannot be a QQCondition', 3);
					} else if ($mixOperand instanceof QQClause) {
						throw new QInvalidCastException('Comparison operand cannot be a QQClause', 3);
					} else if ($mixOperand instanceof QQNode) {
						if (!$mixOperand->_ParentNode)
							throw new QInvalidCastException('Unable to cast "' . $mixOperand->_Name . '" table to Column-based QQNode', 3);
						$this->objActiveFilter->mixOperand = $mixOperand;
					} else {
						//must be a string, apply the pre and postfix (This also handles custom filters)
						$mixOperand = $this->strFilterPrefix . $mixOperand . $this->strFilterPostfix;
						$this->objActiveFilter->mixOperand = $mixOperand;
					}
				} else {
					throw new Exception('Trying to set Operand on a filter that does not take operands');
				}
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function ClearFilter() {
			$this->objActiveFilter = null;
			if($this->arrFilterByCommand !== null)
				$this->arrFilterByCommand['value'] = null;
		}


		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP magic method
		 * @param string $strName
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
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
				case "FilterBoxSize": return $this->intFilterBoxSize;
				case "FilterInfo": {
					if (!isset($this->FilterByCommand['value'])) {
						return null;
					}
					$filterCommand = $this->FilterByCommand;
					$filterCommand['clause_operator'] = 'AND';
					//apply the pre and postfix
					$filterCommand['value'] = $filterCommand['prefix'] . $filterCommand['value'] . $filterCommand['postfix'];
					return $filterCommand;
				}
				case "FilterType": return $this->strFilterType;
				case "FilterList": return $this->arrFilterList;
				case "FilterColId": return $this->intFilterColId;

				case "FilterPrefix": return $this->strFilterPrefix;
				case "FilterPostfix": return $this->strFilterPostfix;

				case "FilterConstant": return $this->objFilterConstant;
				case "ActiveFilter": return $this->objActiveFilter;

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
		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed
		 *
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
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
							$this->objActiveFilter = null;
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
						$this->intFilterBoxSize = QType::Cast($mixValue, QType::Integer);
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
