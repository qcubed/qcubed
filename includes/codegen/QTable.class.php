<?php
	/**
	 * Used by the QCubed Code Generator to describe a database Table
	 * @package Codegen
	 *
	 * @property int $OwnerDbIndex
	 * @property string $Name
	 * @property string $ClassNamePlural
	 * @property string $ClassName
	 * @property QColumn[] $ColumnArray
	 * @property QColumn[] $PrimaryKeyColumnArray
	 * @property QReverseReference[] $ReverseReferenceArray
	 * @property QManyToManyReference[] $ManyToManyReferenceArray
	 * @property QIndex[] $IndexArray
	 * @property-read int $ReferenceCount
	 */
	class QTable extends QBaseClass {

		/////////////////////////////
		// Protected Member Variables
		/////////////////////////////

		/**
		 * @var int DB Index to which it belongs in the configuration.inc.php and codegen_settings.xml files.
		 */
		protected $intOwnerDbIndex;

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
		 * Pluralized Name as a collection of objects of this PHP Class
		 * @var string ClassNamePlural;
		 */
		protected $strClassNamePlural;

		/**
		 * Array of Column objects (as indexed by Column name)
		 * @var QColumn[] ColumnArray
		 */
		protected $objColumnArray;

		/**
		 * Array of ReverseReverence objects (indexed numerically)
		 * @var QReverseReference[] ReverseReferenceArray
		 */
		protected $objReverseReferenceArray;

		/**
		 * Array of ManyToManyReference objects (indexed numerically)
		 * @var QManyToManyReference[] ManyToManyReferenceArray
		 */
		protected $objManyToManyReferenceArray;

		/**
		 * Array of Index objects (indexed numerically)
		 * @var QIndex[] IndexArray
		 */
		protected $objIndexArray;

		/**
		 * @var array developer specified options.
		 */
		protected $options;



		/////////////////////
		// Public Constructor
		/////////////////////

		/**
		 * Default Constructor.  Simply sets up the TableName and ensures that ReverseReferenceArray is a blank array.
		 *
		 * @param string $strName Name of the Table
		 */
		public function __construct($strName) {
			$this->strName = $strName;
			$this->objReverseReferenceArray = array();
			$this->objManyToManyReferenceArray = array();
			$this->objColumnArray = array();
			$this->objIndexArray = array();
		}


		/**
		 * return the QColumn object related to that column name
		 * @param string $strColumnName Name of the column
		 * @return QColumn
		 */
		public function GetColumnByName($strColumnName) {
			if ($this->objColumnArray) {
				foreach ($this->objColumnArray as $objColumn){
					if ($objColumn->Name == $strColumnName)
						return $objColumn;
				}
			}
			return null;
		}

		/**
		 * Search within the table's columns for the given column
		 * @param string $strColumnName Name of the column
		 * @return boolean
		 */
		public function HasColumn($strColumnName){
			return ($this->GetColumnByName($strColumnName) !== null);
		}

		/**
		 * Return the property name for a given column name (false if it doesn't exists)
		 * @param string $strColumnName name of the column
		 * @return string
		 */
		public function LookupColumnPropertyName($strColumnName){
			$objColumn = $this->GetColumnByName($strColumnName);
			if ($objColumn)
				return $objColumn->PropertyName;
			else
				return null;
		}
		
		public function HasImmediateArrayExpansions() { 
			$intCount = count($this->objManyToManyReferenceArray);
			foreach ($this->objReverseReferenceArray as $objReverseReference) {
				if (!$objReverseReference->Unique) {
					$intCount++;
				}
			}
			return $intCount > 0;
		}
		
		public function HasExtendedArrayExpansions(QDatabaseCodeGen $objCodeGen, $objCheckedTableArray = array()) {
			$objCheckedTableArray[] = $this;
			foreach ($this->ColumnArray as $objColumn) {
				if (($objReference = $objColumn->Reference) && !$objReference->IsType) {
					if ($objTable2 = $objCodeGen->GetTable($objReference->Table)) {
						if ($objTable2->HasImmediateArrayExpansions()) {
							return true;
						}
						if (!in_array($objTable2, $objCheckedTableArray) &&	// watch out for circular references
								$objTable2->HasExtendedArrayExpansions($objCodeGen, $objCheckedTableArray)) {
							return true;
						}
					}
				}
			}
			return false;
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
				case 'OwnerDbIndex':
					return $this->intOwnerDbIndex;
				case 'Name':
					return $this->strName;
				case 'ClassNamePlural':
					return $this->strClassNamePlural;
				case 'ClassName':
					return $this->strClassName;
				case 'ColumnArray':
					return (array) $this->objColumnArray;
				case 'PrimaryKeyColumnArray':
					if ($this->objColumnArray) {
						$objToReturn = array();
						foreach ($this->objColumnArray as $objColumn)
							if ($objColumn->PrimaryKey)
								array_push($objToReturn, $objColumn);
						return $objToReturn;
					} else
						return null;
				case 'ReverseReferenceArray':
					return (array) $this->objReverseReferenceArray;
				case 'ManyToManyReferenceArray':
					return (array) $this->objManyToManyReferenceArray;
				case 'IndexArray':
					return (array) $this->objIndexArray;
				case 'ReferenceCount':
					$intCount = count($this->objManyToManyReferenceArray);
					foreach ($this->objColumnArray as $objColumn)
						if ($objColumn->Reference)
							$intCount++;
					return $intCount;

				case 'Options':
					return $this->options;

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
					case 'OwnerDbIndex':
						return $this->intOwnerDbIndex = QType::Cast($mixValue, QType::Integer);
					case 'Name':
						return $this->strName = QType::Cast($mixValue, QType::String);
					case 'ClassName':
						return $this->strClassName = QType::Cast($mixValue, QType::String);
					case 'ClassNamePlural':
						return $this->strClassNamePlural = QType::Cast($mixValue, QType::String);
					case 'ColumnArray':
						return $this->objColumnArray = QType::Cast($mixValue, QType::ArrayType);
					case 'ReverseReferenceArray':
						return $this->objReverseReferenceArray = QType::Cast($mixValue, QType::ArrayType);
					case 'ManyToManyReferenceArray':
						return $this->objManyToManyReferenceArray = QType::Cast($mixValue, QType::ArrayType);
					case 'IndexArray':
						return $this->objIndexArray = QType::Cast($mixValue, QType::ArrayType);
					case 'Options':
						return $this->options = QType::Cast($mixValue, QType::ArrayType);
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}
?>