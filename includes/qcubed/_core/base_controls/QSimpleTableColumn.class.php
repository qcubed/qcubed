<?php

	/**
	 * Represents a column for a SimpleTable. Different subclasses (see below) allow accessing and fetching the data
	 * for each cells in a variety of ways
	 *
	 * @property string $Name name of the column
	 * @property string $CssClass css class of the column
	 * @property string $HeaderCssClass css class of the column when it's rendered in a table header
	 * @property boolean $HtmlEntities if true, cell values will be converted using htmlentities()
	 * @property QQOrderBy $OrderByClause order by clause for sorting the column in ascending order
	 * @property QQOrderBy $ReverseOrderByClause order by clause for sorting the column in descending order
	 *
	 */
	abstract class QAbstractSimpleTableColumn extends QBaseClass {
		protected $strName;
		protected $strCssClass = null;
		protected $strHeaderCssClass = null;
		protected $blnHtmlEntities = true;
		protected $objOrderByClause = null;
		protected $objReverseOrderByClause = null;

		/**
		 * @param string $strName Name of the column
		 */
		public function __construct($strName) {
			$this->strName = $strName;
		}

		public function RenderHeaderCell() {
			$cellValue = $this->strName;
			if ($this->blnHtmlEntities)
				$cellValue = QApplication::HtmlEntities($cellValue);
			if ($cellValue == '' && QApplication::IsBrowser(QBrowserType::InternetExplorer)) {
				$cellValue = '&nbsp;';
			}

			if ($this->strHeaderCssClass)
				return '<th class="' . $this->strHeaderCssClass . '">' . $cellValue . '</th>';
			return '<th>' . $cellValue . '</th>';
		}

		public function RenderCell($item, $blnAsHeader = false) {
			$cellValue = $this->FetchCellValue($item);
			if ($this->blnHtmlEntities)
				$cellValue = QApplication::HtmlEntities($cellValue);
			if ($cellValue == '' && QApplication::IsBrowser(QBrowserType::InternetExplorer)) {
				$cellValue = '&nbsp;';
			}

			if ($blnAsHeader) {
				if ($this->strHeaderCssClass)
					return '<th class="' . $this->strHeaderCssClass . '">' . $cellValue . '</th>';
				return '<th>' . $cellValue . '</th>';
			} else {
				if ($this->strCssClass)
					return '<td class="' . $this->strCssClass . '">' . $cellValue . '</td>';
				return '<td>' . $cellValue . '</td>';
			}
		}

		abstract public function FetchCellValue($item);

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
				case "OrderByClause":
					return $this->objOrderByClause;
				case "ReverseOrderByClause":
					return $this->objReverseOrderByClause;

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
	 * A type of column that should be used when the cell data is given by a simple property of the objects inside the
	 * DataSource array
	 *
	 * @property string $Property the property to use when accessing the objects in the DataSource array
	 *
	 */
	class QSimpleTablePropertyColumn extends QAbstractSimpleTableColumn {
		protected $strProperty;

		/**
		 * @param string $strName name of the column
		 * @param string $strProperty the property name to use when accessing the DataSource row object
		 */
		public function __construct($strName, $strProperty) {
			parent::__construct($strName);
			$this->strProperty = $strProperty;
		}

		public function FetchCellValue($item) {
			$strProperty = $this->strProperty;
			return $item->$strProperty;
		}

		public function __get($strName) {
			switch ($strName) {
				case 'Property':
					return $this->strProperty;
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
			switch ($strName) {
				case "Property":
					try {
						$this->strProperty = QType::Cast($mixValue, QType::String);
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
	 * A type of column that should be used when the DataSource items are arrays
	 *
	 * @property int|string $Index the index or key to use when accessing the arrays in the DataSource array
	 *
	 */
	class QSimpleTableIndexedColumn extends QAbstractSimpleTableColumn {
		protected $mixIndex;

		/**
		 * @param string $strName name of the column
		 * @param int|string $mixIndex the index or key to use when accessing the DataSource row array
		 */
		public function __construct($strName, $mixIndex) {
			parent::__construct($strName);
			$this->mixIndex = $mixIndex;
		}

		public function FetchCellValue($item) {
			return $item[$this->mixIndex];
		}

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
	 * A type of column based on a user specified function (Closure) that can be used when a complex logic is required
	 * to fetch the cell data from the DataSource items
	 *
	 * @property int|string $Index the index or key to use when accessing the arrays in the DataSource array
	 *
	 */
	class QSimpleTableClosureColumn extends QAbstractSimpleTableColumn implements Serializable {
		protected $objClosure;

		/**
		 * @param string $strName name of the column
		 * @param object $objClosure a callable object (e.g. Closure). It should take a single argument, and it
		 * will be called with the row of the DataSource as that single argument.
		 *
		 * @throws InvalidArgumentException
		 */
		public function __construct($strName, $objClosure) {
			parent::__construct($strName);
			if (!is_callable($objClosure)) {
				throw new InvalidArgumentException();
			}
			$this->objClosure = $objClosure;
		}

		public function FetchCellValue($item) {
			return call_user_func($this->objClosure, $item);
		}

		/**
		 * (PHP 5 &gt;= 5.1.0)<br/>
		 * String representation of object
		 * @link http://php.net/manual/en/serializable.serialize.php
		 * @return string the string representation of the object or &null;
		 */
		public function serialize() {
			$vars = array(
				$this->strName,
				$this->strCssClass,
				$this->strHeaderCssClass,
				$this->blnHtmlEntities,
				$this->objOrderByClause,
				$this->objReverseOrderByClause);
			// Closure is a feature of PHP 5.3
			// unfortunatly, as of PHP 5.3.6 Closure is not serializable
			// this code can be removed when Closures become serializable in PHP
			if (version_compare(PHP_VERSION, '5.3.0', '<') || (!$this->objClosure instanceof Closure)) {
				$vars[] = $this->objClosure;
			}
			return serialize($vars);
		}

		/**
		 * (PHP 5 &gt;= 5.1.0)<br/>
		 * Constructs the object
		 * @link http://php.net/manual/en/serializable.unserialize.php
		 * @param string $serialized <p>
		 * The string representation of the object.
		 * </p>
		 * @return mixed the original value unserialized.
		 */
		public function unserialize($serialized) {
			$vars = unserialize($serialized);
			$cnt = count($vars);
			if ($cnt == 6) {
				list($this->strName,
						$this->strCssClass,
						$this->strHeaderCssClass,
						$this->blnHtmlEntities,
						$this->objOrderByClause,
						$this->objReverseOrderByClause
						) = $vars;
			} else if ($cnt == 7) {
				list($this->strName,
						$this->strCssClass,
						$this->strHeaderCssClass,
						$this->blnHtmlEntities,
						$this->objOrderByClause,
						$this->objReverseOrderByClause,
						$this->objClosure
						) = $vars;
			} else {
				throw new RuntimeException("wrong number of variables when unserializing QSimpleTableClosureColumn");
			}
		}

		public function __get($strName) {
			switch ($strName) {
				case 'Closure':
					return $this->objClosure;
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
			switch ($strName) {
				case "Closure":
					if (!is_callable($mixValue)) {
						throw new QInvalidCastException("Closure must be a callable object");
					}
					$this->objClosure = $mixValue;
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

?>