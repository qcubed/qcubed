<?php

	/**
	 * Represents a column for a SimpleTable. Different subclasses (see below) allow accessing and fetching the data
	 * for each cells in a variety of ways
	 *
	 * @property string                 $Name           name of the column
	 * @property string                 $CssClass       CSS class of the column
	 * @property string                 $HeaderCssClass CSS class of the column when it's rendered in a table header
	 * @property boolean                $HtmlEntities   if true, cell values will be converted using htmlentities()
	 * @property boolean                $RenderAsHeader if true, all cells in the column will be rendered with a <<th>> tag instead of <<td>>
	 * @property integer                $Id             HTML id attribute to put in the col tag
	 * @property integer                $Span           HTML span attribute to put in the col tag
	 * @property-read QSimpleTableBase  $ParentTable    parent table of the column
	 * @property-write QSimpleTableBase $_ParentTable   Parent table of this column
	 * @property-write callable $CellParamsCallback A callback to set the html parameters of a generated cell
	 * @property boolean                $Visible        Whether the column will be drawn. Defaults to true.
	 * @property-read QTagStyler		$CellStyler		The tag styler for the cells in the column
	 * @property-read QTagStyler		$HeaderCellStyler		The tag styler for the header cells in the column
	 * @property-read QTagStyler		$ColStyler		The tag styler for the col tag in the column
	 */
	abstract class QAbstractSimpleTableColumn extends QBaseClass {
		/** @var string */
		protected $strName;
		/** @var string */
		protected $strCssClass = null;
		/** @var string */
		protected $strHeaderCssClass = null;
		/** @var boolean */
		protected $blnHtmlEntities = true;
		/** @var boolean */
		protected $blnRenderAsHeader = false;
		/** @var QSimpleTableBase */
		protected $objParentTable = null;
		/** @var integer */
		protected $intSpan = 1;
		/** @var string optional id for column tag rendering and datatables */
		protected $strId = null;
		/** @var bool Easy way to hide a column without removing the column. */
		protected $blnVisible = true;
		/** @var Callable Callback to modify the html attributes of the generated cell. */
		protected $cellParamsCallback = null;
		/** @var QTagStyler Styles for each cell. Usually this should be done in css for efficient code generation. */
		protected $objCellStyler;
		/** @var QTagStyler Styles for each header cell. Usually this should be done in css for efficient code generation. */
		protected $objHeaderCellStyler;
		/** @var QTagStyler Styles for each col. Usually this should be done in css for efficient code generation. */
		protected $objColStyler;



		/**
		 * @param string $strName Name of the column
		 */
		public function __construct($strName) {
			$this->strName = $strName;
		}

		/**
		 * 
		 * Render the header cell including opening and closing tags. 
		 * 
		 * This will be called by the data table if ShowHeader is on, and will only
		 * be called for the top line item.
		 * 
		 */
		public function RenderHeaderCell() {
			if (!$this->blnVisible) return '';

			$cellValue = $this->FetchHeaderCellValue();
			if ($this->blnHtmlEntities)
				$cellValue = QApplication::HtmlEntities($cellValue);
			if ($cellValue == '' && QApplication::IsBrowser(QBrowserType::InternetExplorer)) {
				$cellValue = '&nbsp;';
			}
			
			return QHtml::RenderTag('th', $this->GetHeaderCellParams(), $cellValue);
		}
		
		/**
		 * Returns the text to print in the header cell, if one is to be drawn. Override if you want
		 * something other than the default.
		 */
		public function FetchHeaderCellValue() {
			return $this->strName;
		}

		/**
		 * Returns an array of key/value pairs to insert as parameters in the header cell. Override and add
		 * more if you need them.
		 * @return array
		 */
		public function GetHeaderCellParams () {
			$aParams['scope'] = 'col';
			if ($this->strHeaderCssClass) {
				$aParams['class'] = $this->strHeaderCssClass;
			}
			if ($this->objHeaderCellStyler) {
				$aParams = $this->objHeaderCellStyler->GetHtmlAttributes($aParams);
			}
			return $aParams;		
		}
		
		/**
		 * Render a cell.
		 * Called by data table for each cell. Override and call with $blnHeader = true if you want
		 * this individual cell to render with <<th>> tags instead of <<td>>.
		 *
		 * @param mixed   $item
		 * @param boolean $blnAsHeader
		 *
		 * @return string
		 */
		public function RenderCell($item, $blnAsHeader = false) {
			if (!$this->blnVisible) return '';

			$cellValue = $this->FetchCellValue($item);
			if ($this->blnHtmlEntities)
				$cellValue = QApplication::HtmlEntities($cellValue);
			if ($cellValue == '' && QApplication::IsBrowser(QBrowserType::InternetExplorer)) {
				$cellValue = '&nbsp;';
			}
	
			if ($blnAsHeader || $this->blnRenderAsHeader) {
				$strTag = 'th';
			} else {
				$strTag = 'td';
			}
			
			return QHtml::RenderTag($strTag, $this->GetCellParams($item), $cellValue);
		}

		/**
		 * Return a key/val array of items to insert inside the cell tag.
		 * Handles class, style, and id already. Override to add additional items, like an onclick handler.
		 *
		 * @param mixed $item
		 *
		 * @return array
		 */
		protected function GetCellParams ($item) {
			$aParams = array();

			if ($strClass = $this->GetCellClass ($item)) {
				$aParams['class'] = $strClass;
			}
			
			if ($strId = $this->GetCellId ($item)) {
				$aParams['id'] = $strId;
			}
			
			if ($this->blnRenderAsHeader) {
				// assume this means it is a row header
				$aParams['scope'] = 'row';
			}

			if ($this->objCellStyler) {
				$strStyle = $this->GetCellStyle ($item);
				$aParams = $this->objCellStyler->GetHtmlAttributes($aParams, explode(';', $strStyle));
			} else {
				if ($strStyle = $this->GetCellStyle ($item)) {
					$aParams['style'] = $strStyle;
				}
			}

			if ($this->cellParamsCallback) {
				$a = call_user_func($this->cellParamsCallback, $item);
				$aParams = array_merge ($aParams, $a);
			}

			return $aParams;		
		}
		
		/**
		 * Return the class of the cell.
		 *
		 * @param mixed $item
		 *
		 * @return string
		 */
		protected function GetCellClass ($item) {
			if ($this->strCssClass) {
				return $this->strCssClass;
			}
			return '';
		}
		
		/**
		 * Return the id of the cell.
		 *
		 * @param mixed $item
		 *
		 * @return string
		 */
		protected function GetCellId ($item) {
			return '';
		}
		
		/**
		 * Return the style string for the cell.
		 *
		 * @param mixed $item
		 *
		 * @return string
		 */
		protected function GetCellStyle ($item) {
			return '';
		}
		
		/**
		 * Return the raw string that represents the cell value. 
		 * 
		 * @param mixed $item
		 */
		abstract public function FetchCellValue($item);

		/**
		 * Render the column tag.
		 * This special tag can control specific features of columns, but is generally optional on a table.
		 *
		 * @return string
		 */
		public function RenderColTag() {
			return QHtml::RenderTag('col', $this->GetColParams(), null, true);
		}

		/**
		 * Return a key/value array of parameters to put in the col tag.
		 * Override to add parameters.
		 */
		protected function GetColParams () {
			$aParams = array();
			if ($this->intSpan > 1) {
				$aParams['span'] = $this->intSpan;
			}
			if ($this->strId) {
				$aParams['id'] = $this->strId;
			}
			if ($this->strCssClass) {
				$aParams['class'] = $this->strCssClass;
			}

			if ($this->objColStyler) {
				$aParams = $this->objColStyler->GetHtmlAttributes($aParams);
			}

			return $aParams;		
		}

		/**
		 * Prepare to serialize references to the form.
		 */
		public function Sleep() {
			$this->cellParamsCallback = QControl::SleepHelper($this->cellParamsCallback);
		}

		/**
		 * The object has been unserialized, so fix up pointers to embedded objects.
		 * @param QForm $objForm
		 */
		public function Wakeup(QForm $objForm) {
			$this->cellParamsCallback = QControl::WakeupHelper($objForm, $this->cellParamsCallback);
		}

		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 *
		 * @return bool|int|mixed|QSimpleTableBase|string
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Name':
					return $this->strName;
				case 'CssClass':
					return $this->strCssClass;
				case 'HeaderCssClass':
					return $this->strHeaderCssClass;
				case 'HtmlEntities':
					return $this->blnHtmlEntities;
				case 'RenderAsHeader':
					return $this->blnRenderAsHeader;
				case 'ParentTable':
					return $this->objParentTable;
				case 'Span':
					return $this->intSpan;
				case 'Id':
					return $this->strId;
				case 'Visible':
					return $this->blnVisible;
				case 'CellStyler':
					if (!$this->objCellStyler) {
						$this->objCellStyler = new QTagStyler();
					}
					return $this->objCellStyler;
				case 'HeaderCellStyler':
					if (!$this->objHeaderCellStyler) {
						$this->objHeaderCellStyler = new QTagStyler();
					}
					return $this->objHeaderCellStyler;
				case 'ColStyler':
					if (!$this->objColStyler) {
						$this->objColStyler = new QTagStyler();
					}
					return $this->objColStyler;


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
		 * PHP Magic method
		 *
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed|void
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "Name":
					try {
						$this->strName = QType::Cast($mixValue, QType::String);
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

				case "HeaderCssClass":
					try {
						$this->strHeaderCssClass = QType::Cast($mixValue, QType::String);
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
					
				case "RenderAsHeader":
					try {
						$this->blnRenderAsHeader = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
				case "Span":
					try {
						$this->intSpan = QType::Cast($mixValue, QType::Integer);
						if ($this->intSpan < 1) {
							throw new Exception("Span must be 1 or greater.");
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Id":
					try {
						$this->strId = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Visible":
					try {
						$this->blnVisible = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "CellParamsCallback":
					$this->cellParamsCallback = $mixValue;
					break;

				case "_ParentTable":
					try {
						$this->objParentTable = QType::Cast($mixValue, 'QSimpleTableBase');
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
	
	/**
	 * An abstract column designed to work with QDataTables and other tables that require more than basic columns.
	 * Supports post processing of cell contents for further formatting, and orderby clauses.
	 *
	 * @property mixed          $OrderByClause        order by info for sorting the column in ascending order. Used by subclasses.
	 *    Most often this is a QQ::Clause, but can be any data needed.
	 * @property mixed          $ReverseOrderByClause order by info for sorting the column in descending order.
	 * @property string         $Format               the default format to use for FetchCellValueFormatted(). Used by QDataTables plugin.
	 *    For date columns it should be a format accepted by QDateTime::qFormat()
	 * @property-write string   $PostMethod           after the cell object is retrieved, call this method on the obtained object
	 * @property-write callback $PostCallback         after the cell object is retrieved, call this callback on the obtained object.
	 *    If $PostMethod is also set, this will be called after that method call.
	 */
	abstract class QAbstractSimpleTableDataColumn extends QAbstractSimpleTableColumn {
		/** @var mixed Order By information. Can be a QQ::Clause, or any kind of object depending on your need */
		protected $objOrderByClause = null;
		/** @var mixed */
		protected $objReverseOrderByClause = null;
		/** @var string */
		protected $strFormat = null;
		/** @var string */
		protected $strPostMethod = null;
		/** @var callback */
		protected $objPostCallback = null;

		/**
		 * Return the raw string that represents the cell value.
		 * This version uses a combination of post processing strategies so that you can set
		 * column options to format the raw data. If no
		 * options are set, then $item will just pass through, or __toString() will be called
		 * if its an object. If none of these work for you, just override FetchCellObject and
		 * return your formatted string from there.
		 *
		 * @param mixed $item
		 *
		 * @return mixed|string
		 */
		public function FetchCellValue($item) {
			$cellValue = $this->FetchCellObject($item);
						
			if ($cellValue !== null && $this->strPostMethod) {
				$strPostMethod = $this->strPostMethod;
				assert ('is_callable([$cellValue, $strPostMethod])');	// Malformed post method, or the item is not an object
				$cellValue = $cellValue->$strPostMethod();
			}
			if ($this->objPostCallback) {
				$cellValue = call_user_func($this->objPostCallback, $cellValue);
			}
			if (!$cellValue)
				return '';

			if ($cellValue instanceof QDateTime) {
				return $cellValue->qFormat($this->strFormat);
			}
			if (is_object($cellValue)) {
				$cellValue = $cellValue->__toString();
			}
			if ($this->strFormat)
				return sprintf($this->strFormat, $cellValue);

			return $cellValue;
		}

		/**
		 * Return the value of the cell. FetchCellValue will process this more if needed.
		 * Default returns an entire data row and relies on FetchCellValue to extract the needed data.
		 * 
		 * @param mixed $item
		 */
		abstract public function FetchCellObject($item);

		/**
		 * Fix up possible embedded reference to the form.
		 */
		public function Sleep() {
			$this->objPostCallback = QControl::SleepHelper($this->objPostCallback);
			parent::Sleep();
		}

		/**
		 * The object has been unserialized, so fix up pointers to embedded objects.
		 * @param QForm $objForm
		 */
		public function Wakeup(QForm $objForm) {
			parent::Wakeup($objForm);
			$this->objPostCallback = QControl::WakeupHelper($objForm, $this->objPostCallback);
		}

		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 *
		 * @return bool|int|mixed|QSimpleTableBase|string
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case "OrderByClause":
					return $this->objOrderByClause;
				case "ReverseOrderByClause":
					return $this->objReverseOrderByClause;
				case "Format":
					return $this->strFormat;

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
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed|void
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "OrderByClause":
					try {
						$this->objOrderByClause = $mixValue;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "ReverseOrderByClause":
					try {
						$this->objReverseOrderByClause = $mixValue;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Format":
					try {
						$this->strFormat = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "PostMethod":
					try {
						$this->strPostMethod = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "PostCallback":
					$this->objPostCallback = $mixValue;
					break;

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

	/**
	 * Displays a  property of an object, as in $object->Property
	 * If your DataSource is an array of objects, use this column to display a particular property of each object.
	 * Can search with depth to, as in $obj->Prop1->Prop2.
	 *
	 * @property string  $Property the property to use when accessing the objects in the DataSource array. Can be a s
	 *  series of properties separated with '->', i.e. 'Prop1->Prop2->Prop3' will find the Prop3 item inside the Prop2 object,
	 *  inside the Prop1 object, inside the current object.
	 * @property boolean $NullSafe if true the value fetcher will check for nulls before accessing the properties
	 */
	class QSimpleTablePropertyColumn extends QAbstractSimpleTableDataColumn {
		protected $strProperty;
		protected $strPropertiesArray;
		protected $blnNullSafe = true;

		/**
		 * @param string      $strName     name of the column
		 * @param string|null $strProperty the property name to use when accessing the DataSource row object.
		 *                                 Can be null, in which case object will have the ->__toString() function called on it.
		 * @param QQNode      $objBaseNode if not null, the OrderBy and ReverseOrderBy clauses will be created using the property path and the given database node
		 */
		public function __construct($strName, $strProperty, $objBaseNode = null) {
			parent::__construct($strName);
			$this->Property = $strProperty;

			if ($objBaseNode != null) {
				foreach ($this->strPropertiesArray as $strProperty) {
					$objBaseNode = $objBaseNode->$strProperty;
				}

				$this->OrderByClause = QQ::OrderBy($objBaseNode);
				$this->ReverseOrderByClause = QQ::OrderBy($objBaseNode, 'desc');
			}
		}

		public function FetchCellObject($item) {
			if ($this->blnNullSafe && $item == null)
				return null;
			foreach ($this->strPropertiesArray as $strProperty) {
				$item = $item->$strProperty;
				if ($this->blnNullSafe && $item == null)
					break;
			}
			return $item;
		}

		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 *
		 * @return bool|int|mixed|QSimpleTableBase|string
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Property':
					return $this->strProperty;
				case 'NullSafe':
					return $this->blnNullSafe;
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
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed|void
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "Property":
					try {
						$this->strProperty = QType::Cast($mixValue, QType::String);
						$this->strPropertiesArray = $this->strProperty ? explode('->', $this->strProperty) : array();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "NullSafe":
					try {
						$this->blnNullSafe = QType::Cast($mixValue, QType::Boolean);
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

	/**
	 * Class QSimpleTableNodeColumn
	 *
	 * A table column that displays the content of a database column represented by a QQNode object.
	 * The $objNodes can be a single node, or an array of nodes. If an array of nodes, the first
	 * node will be the display node, and the rest of the nodes will be used for sorting.
	 */
	class QSimpleTableNodeColumn extends QSimpleTablePropertyColumn {
		public function __construct($strName, $objNodes) {
			if ($objNodes instanceof QQNode) {
				$objNodes = [$objNodes];
			}
			elseif (empty($objNodes) || !is_array($objNodes) || !$objNodes[0] instanceof QQNode) {
				throw new QCallerException('Pass either a QQNode or an array of QQNodes only');
			}

			$objNode = $objNodes[0]; // First node is the data node, the rest are for sorting.

			if (!$objNode->_ParentNode) {
				throw new QCallerException('First QQNode cannot be a Top Level Node');
			}
			if (($objNode instanceof QQReverseReferenceNode) && !$objNode->IsUnique()) {
				throw new QCallerException('Content QQNode cannot go through any "To Many" association nodes.');
			}

			$properties = array($objNode->_PropertyName);
			while ($objNode = $objNode->_ParentNode) {
				if (!($objNode instanceof QQNode))
					throw new QCallerException('QQNode cannot go through any "To Many" association nodes.');
				if (($objNode instanceof QQReverseReferenceNode) && !$objNode->IsUnique())
					throw new QCallerException('QQNode cannot go through any "To Many" association nodes.');
				if ($strPropName = $objNode->_PropertyName) {
					$properties[] = $strPropName;
				}
			}
			$properties = array_reverse($properties);
			$strProp = implode ('->', $properties);
			parent::__construct($strName, $strProp, null);

			// build sort nodes
			foreach ($objNodes as $objNode) {
				if ($objNode instanceof QQReverseReferenceNode) {
					$objNode = $objNode->_PrimaryKeyNode;
				}
				$objSortNodes[] = $objNode;
				$objReverseNodes[] = $objNode;
				$objReverseNodes[] = false;
			}

			$this->OrderByClause = QQ::OrderBy($objSortNodes);
			$this->ReverseOrderByClause = QQ::OrderBy($objReverseNodes);
		}
	}


	/**
	 * A type of column that should be used when the DataSource items are arrays
	 *
	 * @property int|string $Index the index or key to use when accessing the arrays in the DataSource array
	 *
	 */
	class QSimpleTableIndexedColumn extends QAbstractSimpleTableDataColumn {
		protected $mixIndex;

		/**
		 * @param string $strName name of the column
		 * @param int|string $mixIndex the index or key to use when accessing the DataSource row array
		 */
		public function __construct($strName, $mixIndex) {
			parent::__construct($strName);
			$this->mixIndex = $mixIndex;
		}

		public function FetchCellObject($item) {
			if (isset ($item[$this->mixIndex])) {
				return $item[$this->mixIndex];
			} else {
				return '';
			}
		}

		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 *
		 * @return bool|int|mixed|QSimpleTableBase|string
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Index':
					return $this->mixIndex;
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
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed|void
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "Index":
					$this->mixIndex = $mixValue;
					break;

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

	/**
	 * A type of column that lets you use a PHP 'callable'. However, you CANNOT send a PHP closure to this,
	 * since closures are not serializable. You CAN do things like array($this, 'method'), or 'Class::StaticMethod'.
	 *
	 * @property int|string $Index the index or key to use when accessing the arrays in the DataSource array
	 *
	 */
	class QSimpleTableCallableColumn extends QAbstractSimpleTableDataColumn {
		/** @var callback */
		protected $objCallable;
		/** @var array extra parameters passed to closure */
		protected $mixParams;

		/**
		 * @param string $strName name of the column
		 * @param callback $objCallable a callable object. It should take a single argument, the item
		 *   of the array. Do NOT pass an actual Closure object, as they are not serializable. However,
		 *   you can pass a callable, like array($this, 'method'), or an object that has the __invoke method defined,
		 *   as long as its serializable. You can also pass static methods as a string, as in "Class::method"
		 * @param mixed $mixParams extra parameters to pass to the closure callback.
		 * will be called with the row of the DataSource as that single argument.
		 *
		 * @throws InvalidArgumentException
		 */
		public function __construct($strName, callable $objCallable, $mixParams = null) {
			parent::__construct($strName);
			if ($objCallable instanceof Closure) {
				throw new InvalidArgumentException('Cannot be a Closure.');
			}
			$this->objCallable = $objCallable;
			$this->mixParams = $mixParams;
		}

		public function FetchCellObject($item) {
			if ($this->mixParams) {
				return call_user_func($this->objCallable, $item, $this->mixParams);
			} else {
				return call_user_func($this->objCallable, $item);
			}
		}

		/**
		 * Fix up possible embedded reference to the form.
		 */
		public function Sleep() {
			$this->objCallable = QControl::SleepHelper($this->objCallable);
			parent::Sleep();
		}

		/**
		 * Restore serialized references.
		 * @param QForm $objForm
		 */
		public function Wakeup(QForm $objForm) {
			parent::Wakeup($objForm);
			$this->objCallable = QControl::WakeupHelper($objForm, $this->objCallable);
		}

		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 *
		 * @return bool|callable|int|mixed|QSimpleTableBase|string
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Callable':
					return $this->objCallable;
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
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed|void
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "Callable":
					if (!is_callable($mixValue)) {
						throw new QInvalidCastException("Callable must be a callable object");
					}
					$this->objCallable = $mixValue;
					break;

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
	
	/**
	 * 
	 * A column to display a virtual attribute from a database record.
	 *
	 * @property string $Attribute
	 */
	class QVirtualAttributeColumn extends QAbstractSimpleTableDataColumn {
		protected $strAttribute;
		
		public function __construct($strName, $strAttribute = null) {
			parent::__construct($strName);
			if ($strAttribute) {
				$this->strAttribute = $strAttribute;
			}
		}
		
		public function FetchCellObject($item) {
			return $item->GetVirtualAttribute ($this->strAttribute);
		}

		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 *
		 * @return bool|int|mixed|null|QSimpleTableBase|string
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Attribute':
					return $this->strAttribute;
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
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed|void
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "Attribute":
					$this->strAttribute = QType::Cast ($mixValue, QType::String);
					break;

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
	
	/**
	 * 
	 * A column of checkboxes. 
	 * 
	 * Prints checkboxes in a column, including the header. Override this class and implement whatever hooks you need. In
	 * particular implement the CheckId hooks, and IsChecked hooks.
	 *
	 * This class does not detect and record changes in the checkbox list.
	 * Use the QSimpleTableCheckBoxColumn_ClickEvent to detect a change to a checkbox. You will need to detect whether
	 * the header check all box was clicked, or a regular box was clicked and respond accordingly. In response to a
	 * click, you could store the array of ids of the checkboxes clicked in a session variable, the database, or
	 * a cache variable. You would just give an id to each checkbox. This would cause internet traffic every time
	 * a box is clicked.
	 *
	 * @property bool $ShowCheckAll
	 *
	 */
	class QSimpleTableCheckBoxColumn extends QAbstractSimpleTableDataColumn {
		protected $blnHtmlEntities = false;	// turn off html entities
		protected $checkParamCallback = null;
		protected $blnShowCheckAll = false;

		/**
		 * Returns a header cell with a checkbox. This could be used as a check all box. Override this and return
		 * an empty string to turn it off.
		 *
		 * @return string
		 */
		public function FetchHeaderCellValue() {
			if ($this->blnShowCheckAll) {
				$aParams = $this->GetCheckboxParams(null);
				$aParams['type'] = 'checkbox';
				return QHtml::RenderTag('input', $aParams, null, true);
			} else {
				return $this->Name;
			}
		}

		public function FetchCellObject($item) {
			$aParams = $this->GetCheckboxParams($item);
			$aParams['type'] = 'checkbox';
			return QHtml::RenderTag('input', $aParams, null, true);
		}

		/**
		 * Returns an array of parameters to attach to the checkbox tag. Includes whether the
		 * checkbox should appear as checked. Will try the callback first, and if not present,
		 * will try overridden functions.
		 * @param mixed|null $item	Null to indicate that we want the params for the header cell.
		 * @return array
		 */
		public function GetCheckboxParams ($item) {
			$aParams = array();
			
			if ($strId = $this->GetCheckboxId ($item)) {
				$aParams['id'] = $strId;
			}
			
			if ($this->IsChecked ($item)) {
				$aParams['checked'] = 'checked';
			}

			if ($strName = $this->GetCheckboxName ($item)) {
				$aParams['name'] = $strName; // if no name is indicated, then the data for the checkboxes will not be submitted.
			}

			$aParams['value'] = $this->GetCheckboxValue ($item); // note that value is required for html checkboxes

			if ($this->checkParamCallback) {
				$a = call_user_func($this->checkParamCallback, $item);
				$aParams = array_merge ($aParams, $a);
			}


			return $aParams;		
		}

		/**
		 * Optional callback to control the appearance of the checkboxes. You can use a callback, or subclass to do this.
		 * If a callback, it should be of the form:
		 * 	func($item)
		 *
		 * 	$item is either the line item, or null to indicate the header
		 *
		 * This should return the following values in an array to indicate what should be put as attributes for the checkbox tag:
		 * 	id
		 *  name
		 *  value
		 *  checked (only return a value here if you want it checked. Otherwise, do not include in the array)
		 *
		 *  See below for a description of what should be returned for each item.
		 *
		 * @param $callable
		 */
		public function SetCheckParamCallback ($callable) {
			$this->checkParamCallback = $callable;
		}
		
		/**
		 * Returns the id for the checkbox itself. This is used together with the check action to send the item
		 * id to the action.
		 * @param mixed|null $item	Null to get the id for the header checkbox
		 */
		protected function GetCheckboxId ($item) {
			return null;
		}

		/**
		 * Return true if the checkbox should be drawn checked. Override this to provide the correct value.
		 * @param mixed|null $item	Null to get the id for the header checkbox
		 * @return bool
		 */
		protected function IsChecked ($item) {
			return false;
		}

		/**
		 * Return the name attribute for the checkbox. If you return null, the checkbox will not get submitted to the form.
		 * If you return a name, then that will be the key for the value submitted by the form. If you return a name
		 * ending with brackets [], then this checkbox will be part of an array of values posted to that name.
		 *
		 * @param mixed|null $item	Null to get the id for the header checkbox
		 * @return null|string
		 */
		protected function GetCheckboxName ($item) {
			return null;
		}

		/**
		 * Return the value attribute of the checkbox tag. Checkboxes are required to have a value in html.
		 * This value will be what is posted by form post.
		 *
		 * @param mixed|null $item	Null to get the id for the header checkbox
		 * @return string
		 */
		protected function GetCheckboxValue ($item) {
			return "1"; // Means that if the checkbox is checked, the POST value corresponding to the name of the checkbox will be 1.
		}

		/**
		 * Fix up possible embedded reference to the form.
		 */
		public function Sleep() {
			$this->checkParamCallback = QControl::SleepHelper($this->checkParamCallback);
			parent::Sleep();
		}

		/**
		 * Restore embedded objects.
		 * 
		 * @param QForm $objForm
		 */
		public function Wakeup(QForm $objForm) {
			parent::Wakeup($objForm);
			$this->checkParamCallback = QControl::WakeupHelper($objForm, $this->checkParamCallback);
		}

		/**
		 * PHP magic method
		 *
		 * @param string $strName
		 *
		 * @return bool|int|mixed|QSimpleTableBase|string
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'ShowCheckAll':
					return $this->blnShowCheckAll;
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
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed|void
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "ShowCheckAll":
					try {
						$this->blnShowCheckAll = QType::Cast($mixValue, QType::Boolean);
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


	class QSimpleTableCheckBoxColumn_ClickEvent extends QClickEvent {
		const JsReturnParam = '{"row": $j(this).closest("tr")[0].rowIndex, "col": $j(this).parent()[0].cellIndex, "checked":this.checked, "id":this.id}'; // returns the array of cell info, and the new state of the checkbox

		public function __construct($intDelay = 0, $strCondition = null) {
			parent::__construct($intDelay, $strCondition, 'input[type="checkbox"]');
		}
	}
