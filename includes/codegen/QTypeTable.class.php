<?php
	/**
	 * Used by the QCubed Code Generator to describe a database Type Table
	 * "Type" tables must be defined with at least two columns, the first one being an integer-based primary key,
	 * and the second one being the name of the type.
	 * @package Codegen
	 *
	 * @property string $Name
	 * @property string $ClassName
	 * @property string[] $NameArray
	 * @property string[] $TokenArray
	 * @property array $ExtraPropertyArray
	 * @property string[] $ExtraFieldNamesArray
	 * @property-read QSqlColumn[] $PrimaryKeyColumnArray
	 * @property-write QSqlColumn $KeyColumn
	 * @property QManyToManyReference[] $ManyToManyReferenceArray
	 */
	class QTypeTable extends QBaseClass {

		/////////////////////////////
		// Protected Member Variables
		/////////////////////////////

		/**
		 * Name of the table (as defined in the database)
		 * @var string Name
		 */
		protected $strName;

		/**
		 * Name as a PHP Class
		 * @var string ClassName
		 */
		protected $strClassName;

		/**
		 * Array of Type Names (as entered into the rows of this database table)
		 * This is indexed by integer which represents the ID in the database, starting with 1
		 * @var string[] NameArray
		 */
		protected $strNameArray;

        /**
         * Column names for extra properties (beyond the 2 basic columns), if any.
         */
        protected $strExtraFieldNamesArray;

        /**
         * Array of extra properties. This is a double-array - array of arrays. Example:
         *      1 => ['col1' => 'valueA', 'col2 => 'valueB'],
         *      2 => ['col1' => 'valueC', 'col2 => 'valueD'],
         *      3 => ['col1' => 'valueC', 'col2 => 'valueD']
         */
        protected $arrExtraPropertyArray;

		/**
		 * Array of Type Names converted into Tokens (can be used as PHP Constants)
		 * This is indexed by integer which represents the ID in the database, starting with 1
		 * @var string[] TokenArray
		 */
		protected $strTokenArray;

		protected $objKeyColumn;
		protected $objManyToManyReferenceArray;
		
		/////////////////////
		// Public Constructor
		/////////////////////

		/**
		 * Default Constructor.  Simply sets up the TableName.
		 *
		 * @param string $strName Name of the Table
		 * @return QTypeTable
		 */
		public function __construct($strName) {
			$this->strName = $strName;
		}

		/**
		 * Returns the string that will be used to represent the literal value given when codegenning a type table
		 * @param $mixColValue
		 * @return string
		 */
		public static function Literal($mixColValue) {
			if (is_null($mixColValue)) return 'null';
 			elseif (is_integer($mixColValue)) return $mixColValue;
			elseif (is_bool($mixColValue)) return ($mixColValue ? 'true' : 'false');
			elseif (is_float($mixColValue)) return "(float)$mixColValue";
			elseif (is_object($mixColValue)) return "QApplication::Translate('" . $mixColValue->_toString() . "')";	// whatever is suitable for the constructor of the object
			else return "QApplication::Translate('" . str_replace("'", "\\'", $mixColValue) . "')";
		}

		////////////////////
		// Public Overriders
		////////////////////

		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @throws Exception
		 * @throws QCallerException
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Name':
					return $this->strName;
				case 'ClassName':
					return $this->strClassName;
				case 'NameArray':
					return $this->strNameArray;
				case 'TokenArray':
					return $this->strTokenArray;
				case 'ExtraPropertyArray':
					return $this->arrExtraPropertyArray;
				case 'ExtraFieldNamesArray':
					return $this->strExtraFieldNamesArray;
				case 'PrimaryKeyColumnArray':
					$a[] = $this->objKeyColumn;
					return $a;
				case 'ManyToManyReferenceArray':
					return (array) $this->objManyToManyReferenceArray;
					
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
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @throws Exception
		 * @throws QCallerException
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'Name':
						return $this->strName = QType::Cast($mixValue, QType::String);
					case 'ClassName':
						return $this->strClassName= QType::Cast($mixValue, QType::String);
					case 'NameArray':
						return $this->strNameArray = QType::Cast($mixValue, QType::ArrayType);
					case 'TokenArray':
						return $this->strTokenArray = QType::Cast($mixValue, QType::ArrayType);
					case 'ExtraPropertyArray':
						return $this->arrExtraPropertyArray = QType::Cast($mixValue, QType::ArrayType);
					case 'ExtraFieldNamesArray':
						return $this->strExtraFieldNamesArray = QType::Cast($mixValue, QType::ArrayType);
					case 'KeyColumn':
						return $this->objKeyColumn = $mixValue;
					case 'ManyToManyReferenceArray':
						return $this->objManyToManyReferenceArray = QType::Cast($mixValue, QType::ArrayType);
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}