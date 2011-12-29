<?php

	/**
	 * Represents a column for a SimpleTable. It allows accessing and fetching the data for each cells in a variety of
	 * methods depending on the accessor used
	 *
	 * @see QSimpleTableColumn::__construct
	 *
	 * @property string $Name name of the column
	 * @property string $CssClass css class of the column
	 * @property string $HeaderCssClass css class of the column when it's rendered in a table header
	 * @property boolean $HtmlEntities if true, cell values will be converted using htmlentities()
	 * @property boolean $TranslateName if true, the name of the column will be translated when rendering the header cell.
	 * This is useful when using the name of the column also as data accessor.
	 * @property int|string|callback $Accessor
	 *
	 * @throws QCallerException|QInvalidCastException
	 *
	 */
	class QSimpleTableColumn extends QBaseClass implements Serializable {
		/** @var int|string|callback */
		private $mixAccessor;
		private $strName;
		private $strCssClass = null;
		private $strHeaderCssClass = null;
		private $blnHtmlEntities = true;
		private $blnTranslateName = true;

		/**
		 * @param string $strName Name of the column
		 * @param int|string|callback|null $mixAccessor data accessor
		 * <ul><li>if the accessor is an integer, then each row in the data object should be an array and the accessor
		 * is the index into that array;</li>
		 * <li>if the accessor is a string, and the row of the data object is an array, then accessor is the key into
		 * that array. If the row of the data object is an object, then the accessor is the property of that object;</li>
		 * <li>if the accessor is null , then it's set to $strName the previous rule applies;</li>
		 * <li>if the accessor is a callable object (e.g. a closure), then it should take a single argument, and it
		 * will be called with the row of the data object as that single argument.</li>
		 * </ul>
		 */
		public function __construct($strName, $mixAccessor = null) {
			$this->strName = $strName;
			$this->setAccessor($mixAccessor);
		}

		protected function setAccessor($mixAccessor) {
			if (is_null($mixAccessor)) {
				$this->mixAccessor = $this->strName;
			} else {
				$this->mixAccessor = $mixAccessor;
			}
		}

		public function RenderHeaderCell() {
			$cellValue = $this->strName;
			if ($this->blnTranslateName)
				$cellValue = QApplication::Translate($cellValue);
			if ($this->blnHtmlEntities)
				$cellValue = QApplication::HtmlEntities($cellValue);
			if ($cellValue == '' && QApplication::IsBrowser(QBrowserType::InternetExplorer)) {
				$cellValue = '&nbsp;';
			}

			if ($this->strHeaderCssClass)
				return '<th class="' . $this->strHeaderCssClass . '">' . $cellValue . '<th>';
			return '<th>' . $cellValue . '<th>';
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
					return '<th class="' . $this->strHeaderCssClass . '">' . $cellValue . '<th>';
				return '<th>' . $cellValue . '<th>';
			} else {
				if ($this->strCssClass)
					return '<td class="' . $this->strCssClass . '">' . $cellValue . '<td>';
				return '<td>' . $cellValue . '<td>';
			}
		}

		public function FetchCellValue($item) {
			$mixAccessor = $this->mixAccessor;
			if (is_callable($mixAccessor)) {
				return call_user_func($mixAccessor, $item);
			}
			if (is_array($item)) {
				if (is_int($mixAccessor) || is_string($mixAccessor))
					return $item[$mixAccessor];
				throw new QInvalidCastException("When data is an array, the accessor must be either an int, a string or a callback");
			}
			if (is_object($item)) {
				if (is_string($mixAccessor))
					return $item->$mixAccessor;
				throw new QInvalidCastException("When data is an object, the accessor must be either a string or a callback");
			}
			return $item;
		}

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
				case 'TranslateName':
					return $this->blnTranslateName;
				case 'Accessor':
					return $this->mixAccessor;

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

				case "TranslateName":
					try {
						$this->blnTranslateName = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Accessor":
					$this->setAccessor($mixValue);
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

		/**
		 * (PHP 5 &gt;= 5.1.0)<br/>
		 * String representation of object
		 * @link http://php.net/manual/en/serializable.serialize.php
		 * @return string the string representation of the object or &null;
		 */
		public function serialize() {
			$vars = array($this->strName, $this->strCssClass, $this->strHeaderCssClass, $this->blnHtmlEntities, $this->blnTranslateName);
			// Closure is a feature of PHP 5.3
			// unfortunatly, as of PHP 5.3.6 Closure is not serializable
			// this code can be removed when Closures become serializable in PHP
			if (version_compare(PHP_VERSION, '5.3.0', '<') || (!$this->mixAccessor instanceof Closure)) {
				$vars[] = $this->mixAccessor;
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
			if ($cnt == 5) {
				list($this->strName, $this->strCssClass, $this->strHeaderCssClass, $this->blnHtmlEntities, $this->blnTranslateName) = $vars;
			} else if ($cnt == 6) {
				list($this->strName, $this->strCssClass, $this->strHeaderCssClass, $this->blnHtmlEntities, $this->blnTranslateName, $this->mixAccessor) = $vars;
			} else {
				throw new RuntimeException("wrong number of variables when unserializing QSimpleTableColumn");
			}
		}
	}

?>
