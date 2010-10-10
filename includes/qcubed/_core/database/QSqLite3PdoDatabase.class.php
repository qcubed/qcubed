<?php
/**
 * PDO_SqLite3 database driver
 * @package DatabaseAdapters
 * @author Christophe Damour [sigeal at sigeal dot com dot fr]
 * Adapted from PDO_PGSQL database driver by Marcos Sánchez [marcosdsanchez at thinkclear dot com dot ar]
 */

class QSqLite3PdoDatabase extends QPdoDatabase {
	const Adapter = 'SqLite3 PDO Database Adapter';
	const PDO_SQLITE3_DSN_IDENTIFIER = 'sqlite';

	protected $strEscapeIdentifierBegin = '';
	protected $strEscapeIdentifierEnd = '';

	protected $strEscapeIdentifierBeginInternal = '\'';
	protected $strEscapeIdentifierEndInternal = '\'';	

		public function Connect() {
				// Lookup Adapter-Specific Connection Properties
				$strDsn = sprintf('%s:%s', QSqLite3PdoDatabase::PDO_SQLITE3_DSN_IDENTIFIER, $this->Database);
				// Connect to the Database Server
				try {
						$this->objPdo = new PDO($strDsn);
				} catch (PDOException $expPgSql) {
						throw new QSqLite3PdoDatabaseException(sprintf('Unable to connect to Database: %s',$expPgSql->getMessage()), -1, null);
				}
				// Update Connected Flag
				$this->blnConnectedFlag = true;
		}

		public function Close() {
			parent::Close();

			// Update Connected Flag
			$this->blnConnectedFlag = false;
		}

		public function SqlVariable($mixData, $blnIncludeEquality = false, $blnReverseEquality = false) {
				// Are we SqlVariabling a BOOLEAN value?
				if (is_bool($mixData)) {
						// Yes
						if ($blnIncludeEquality) {
								// We must include the inequality

								if ($blnReverseEquality) {
										// Do a "Reverse Equality"

										// Check against NULL, True then False
										if (is_null($mixData))
												return 'IS NOT NULL';
										else if ($mixData)
												return "= '0'";
										else
												return "!= '0'";
								} else {
										// Check against NULL, True then False
										if (is_null($mixData))
												return 'IS NULL';
										else if ($mixData)
												return "!= '0'";
										else
												return "= '0'";
								}
						} else {
								// Check against NULL, True then False
								if (is_null($mixData))
										return 'NULL';
								else if ($mixData)
										return "'1'";
								else
										return "'0'";
						}
				}

				// Check for Equality Inclusion
				if ($blnIncludeEquality) {
						if ($blnReverseEquality) {
								if (is_null($mixData))
										$strToReturn = 'IS NOT ';
								else
										$strToReturn = '!= ';
						} else {
								if (is_null($mixData))
										$strToReturn = 'IS ';
								else
										$strToReturn = '= ';
						}
				} else
						$strToReturn = '';

				// Check for NULL Value
				if (is_null($mixData))
						return $strToReturn . 'NULL';

				// Check for NUMERIC Value
				if (is_integer($mixData) || is_float($mixData))
						return $strToReturn . sprintf('%s', $mixData);

				// Check for DATE Value
				if ($mixData instanceof QDateTime) {
						if ($mixData->IsTimeNull())
								return $strToReturn . sprintf("'%s'", $mixData->__toString('YYYY-MM-DD'));
						else
								return $strToReturn . sprintf("'%s'", $mixData->__toString(QDateTime::FormatIso));
				}

				// Assume it's some kind of string value
				return $strToReturn . sprintf("'%s'", addslashes($mixData));
		}

		public function SqlLimitVariablePrefix($strLimitInfo) {
				// SqLite3 uses Limit by Suffixes (via a LIMIT clause)
				// Prefix is not used, therefore, return null
				return null;
		}

		public function SqlLimitVariableSuffix($strLimitInfo) {
				// Setup limit suffix (if applicable) via a LIMIT clause
				if (strlen($strLimitInfo)) {
						if (strpos($strLimitInfo, ';') !== false)
								throw new Exception('Invalid Semicolon in LIMIT Info');
						if (strpos($strLimitInfo, '`') !== false)
								throw new Exception('Invalid Backtick in LIMIT Info');

						// First figure out if we HAVE an offset
						$strArray = explode(',', $strLimitInfo);

						if (count($strArray) == 2) {
								// Yep -- there's an offset
								return sprintf('LIMIT %s OFFSET %s', $strArray[1], $strArray[0]);
						} else if (count($strArray) == 1) {
								return sprintf('LIMIT %s', $strArray[0]);
						} else {
								throw new QSqLite3DatabaseException('Invalid Limit Info: ' . $strLimitInfo, 0, null);
						}
				}
				return null;
		}

		public function SqlSortByVariable($strSortByInfo) {
				// Setup sorting information (if applicable) via a ORDER BY clause
				if (strlen($strSortByInfo)) {
						if (strpos($strSortByInfo, ';') !== false)
								throw new Exception('Invalid Semicolon in ORDER BY Info');
						if (strpos($strSortByInfo, '`') !== false)
								throw new Exception('Invalid Backtick in ORDER BY Info');

						return 'ORDER BY ' . $strSortByInfo;
				}
				return null;
		}

		public function GetTables() {
				$objResult = $this->Query('SELECT name FROM sqlite_master WHERE type = "table"');
				$strToReturn = array();
				while ($strRowArray = $objResult->FetchRow())
			if (strpos($strRowArray[0], 'sqlite') === false) 
				array_push($strToReturn, $strRowArray[0]);
				return $strToReturn;
		}

		public function GetFieldsForTable($strTableName) {
		$strQuery = sprintf('PRAGMA table_info (%s%s%s)',
			$this->strEscapeIdentifierBeginInternal,
			$strTableName,
			$this->strEscapeIdentifierEndInternal);
		
		$objResult = $this->Query($strQuery);

				$objFields = array();

				while ($objRow = $objResult->GetNextRow()) {
						array_push($objFields, new QSqLite3PdoDatabaseField($objRow, $this));
				}
				return $objFields;
		}

		public function InsertId($strTableName = null, $strColumnName = null) {
		return $this->objPdo->lastInsertId();
		}


		private function ParseColumnNameArrayFromKeyDefinition($strKeyDefinition) {
				$strKeyDefinition = trim($strKeyDefinition);

				// Get rid of the opening "(" and the closing ")"
				$intPosition = strpos($strKeyDefinition, '(');
				if ($intPosition === false)
						throw new Exception('Invalid Key Definition: ' . $strKeyDefinition);
				$strKeyDefinition = trim(substr($strKeyDefinition, $intPosition + 1));

				$intPosition = strpos($strKeyDefinition, ')');
				if ($intPosition === false)
						throw new Exception('Invalid Key Definition: ' . $strKeyDefinition);
				$strKeyDefinition = trim(substr($strKeyDefinition, 0, $intPosition));
				$strKeyDefinition = str_replace(' ', '', $strKeyDefinition);

				// Create the Array
				// TODO: Current method doesn't support key names with commas or parenthesis in them!
				$strToReturn = explode(',', $strKeyDefinition);

				// Take out trailing and leading '"' character in each name (if applicable)
				for ($intIndex = 0; $intIndex < count($strToReturn); $intIndex++) {
						$strColumn = $strToReturn[$intIndex];

						if (substr($strColumn, 0, 1) == '"')
								$strColumn = substr($strColumn, 1, strpos($strColumn, '"', 1) - 1);

						$strToReturn[$intIndex] = $strColumn;
				}
				return $strToReturn;
		}

		public function GetIndexesForTable($strTableName) {
				$objIndexArray = array();

		$objResult = $this->Query(sprintf(
			'PRAGMA index_list (%s%s%s)',
			$this->strEscapeIdentifierBeginInternal, $strTableName,
			$this->strEscapeIdentifierEndInternal));

		while ($objIndexList = $objResult->GetNextRow()) {
			$objResultIndex = $this->Query(sprintf('PRAGMA index_info (%s%s%s)', $this->strEscapeIdentifierBeginInternal, $objIndexList->GetColumn('name'), $this->strEscapeIdentifierEndInternal));
			$blnUnique = ($objIndexList->GetColumn('unique') == 1) ? true : false;
			
			$arrIndex = array();
			while($objIndex= $objResultIndex->GetNextRow()) {
				$arrIndex[] = $objIndex->GetColumn('name');
			}
			
			if(count($arrIndex)>0) {
				$objIndex = new QDatabaseIndex($objIndexList->GetColumn('name'), false, $blnUnique, $arrIndex);	
				array_push($objIndexArray, $objIndex);	
			}			
		}

		//Get the PK-key
		$objPKList = $this->Query(sprintf('PRAGMA table_info (%s%s%s)', $this->strEscapeIdentifierBeginInternal, $strTableName, $this->strEscapeIdentifierEndInternal));
		while ($objPK = $objPKList->GetNextRow()) {
			if ($objPK->GetColumn('pk') == 1) {
				unset($tmp);
				$tmp[] = $objPK->GetColumn('name');
				$objIndex = new QDatabaseIndex('(' . $strTableName . ' autoindex 1)', true, true, $tmp);	
				array_push($objIndexArray, $objIndex);						
			}
		}			
				return $objIndexArray;
		}

		public function GetForeignKeysForTable($strTableName) {
		$objForeignKeyArray = array();
		$objForeignKeyArrayReturn = array();
		
		$strQuery = sprintf('PRAGMA foreign_key_list (%s%s%s)',
			$this->strEscapeIdentifierBeginInternal,
			$strTableName,
			$this->strEscapeIdentifierEndInternal);
		$objForeignKeyList = $this->Query($strQuery);
		//get an list of all foreignkeys
		while($objForeignKeyResult = $objForeignKeyList->GetNextRow()) {
			$objForeignKeyArray[$objForeignKeyResult->GetColumn('seq')][] = array(
				$objForeignKeyResult->GetColumn('from') . '_' .
				$objForeignKeyResult->GetColumn('table') . '_' .
				$objForeignKeyResult->GetColumn('to'),
				$objForeignKeyResult->GetColumn('from'),
				$objForeignKeyResult->GetColumn('table'),
				$objForeignKeyResult->GetColumn('to'));
		}
		
		//put the keys of the same seq together
		foreach($objForeignKeyArray as $objForeignKeySeq) {
			$arrFrom = array();
			$arrTo = array();
			foreach($objForeignKeySeq as $Key) {
				$arrFrom[] = $Key[1];
				$arrTo[] = $Key[3];
			}
			$objForeignKey = new QDatabaseForeignKey($Key[0], $arrFrom, $Key[2], $arrTo);
			array_push($objForeignKeyArrayReturn, $objForeignKey);
		}
				return $objForeignKeyArrayReturn;
		}

		protected function ExecuteQuery($strQuery) {
				// Perform the Query
				$objResult = $this->objPdo->query($strQuery);
				if ($objResult === false)
						throw new QPdoDatabaseException($this->objPdo->errorInfo(), $this->objPdo->errorCode(), $strQuery);

				// Return the Result
				$this->objMostRecentResult = $objResult;
				$objPdoStatementDatabaseResult = new QSqLite3PdoDatabaseResult($objResult, $this);
				return $objPdoStatementDatabaseResult;
		}
}

/**
 * QSqLite3PdoDatabaseResult
 */
class QSqLite3PdoDatabaseResult extends QPdoDatabaseResult {

		public function GetNextRow() {
				$strColumnArray = $this->FetchArray();

				if ($strColumnArray)
						return new QSqLite3PdoDatabaseRow($strColumnArray);
				else
						return null;
		}

		public function FetchFields() {
				$objArrayToReturn = array();
				while ($objField = $this->FetchColumn()) {
						array_push($objArrayToReturn, new QSqLite3PdoDatabaseField($objField, $this->objDb));
				}
				return $objArrayToReturn;
		}

		public function FetchField() {
				if ($objField = $this->FetchColumn())
						return new QSqLite3PdoDatabaseField($objField, $this->objDb);
		}
}

/**
 * QSqLite3PdoDatabaseRow
 */
class QSqLite3PdoDatabaseRow extends QDatabaseRowBase {
		protected $strColumnArray;

		public function __construct($strColumnArray) {
				$this->strColumnArray = $strColumnArray;
		}

		public function GetColumn($strColumnName, $strColumnType = null) {
				if (array_key_exists($strColumnName, $this->strColumnArray)) {
						if (is_null($this->strColumnArray[$strColumnName]))
								return null;

						switch ($strColumnType) {
								case QDatabaseFieldType::Bit:
										if (!$this->strColumnArray[$strColumnName]) {
												return false;
										} else {
												return ($this->strColumnArray[$strColumnName]) ? true : false;
										}

								case QDatabaseFieldType::Blob:
								case QDatabaseFieldType::Char:
								case QDatabaseFieldType::VarChar:
										return QType::Cast($this->strColumnArray[$strColumnName], QType::String);

								case QDatabaseFieldType::Date:
								case QDatabaseFieldType::DateTime:
								case QDatabaseFieldType::Time:
										return new QDateTime($this->strColumnArray[$strColumnName]);

								case QDatabaseFieldType::Float:
										return QType::Cast($this->strColumnArray[$strColumnName], QType::Float);

								case QDatabaseFieldType::Integer:
										return QType::Cast($this->strColumnArray[$strColumnName], QType::Integer);

								default:
										return $this->strColumnArray[$strColumnName];
						}
				} else
						return null;
		}

		public function ColumnExists($strColumnName) {
				return array_key_exists($strColumnName, $this->strColumnArray);
		}

		public function GetColumnNameArray() {
				return $this->strColumnArray;
		}
}

/**
 * QSqLite3PdoDatabaseField
 */
class QSqLite3PdoDatabaseField extends QDatabaseFieldBase {
	//TODO: Tables and Unique
	public function __construct($mixFieldData, $objDb = null, $strTableName=null) {
		$this->strName = $mixFieldData->GetColumn('name');
		// Set strOriginalName to Name if it isn't set
		if (!$this->strOriginalName)
			$this->strOriginalName = $this->strName;
		else
			$this->strOriginalName = $mixFieldData->GetColumn('orgname');
		//TODO:table
		$this->strTable = $strTableName;
		$this->strOriginalTable = $strTableName;
		$this->strDefault = $mixFieldData->GetColumn('dflt_value');
		$this->intMaxLength = null;

		
		$strLengthArray = explode('(', $mixFieldData->GetColumn('type'));
		if (count($strLengthArray) > 1) {
			//$mixFieldData->type = $strLengthArray[0];
			$strLengthArray = explode(')', $strLengthArray[1]);
			$this->intMaxLength = $strLengthArray[0];

			// If the length is something like (7,2), then let's pull out just the "7"
			$intCommaPosition = strpos($this->intMaxLength, ',');
			if ($intCommaPosition !== false)
				$this->intMaxLength = substr($this->intMaxLength, 0, $intCommaPosition);

			if (!is_numeric($this->intMaxLength))
				throw new Exception('Not a valid Column Length: ' . $mixFieldData->GetColumn('type'));
		}

		$this->blnNotNull = $mixFieldData->GetColumn('notnull');
		$this->blnPrimaryKey = $mixFieldData->GetColumn('pk');
		
		//this is the way you define a auto_increment in Sqlite3
		if (($this->blnPrimaryKey == true) && ($mixFieldData->GetColumn('type') == 'INTEGER'))
			$this->blnIdentity = true;
		else
			$this->blnIdentity = false;
			
		//check if is unique
		if ($strTableName && $objDb) {
			$objResultList = $objDb->Query(sprintf('PRAGMA index_list (%s%s%s)', "'", $strTableName, "'"));
			$this->blnUnique = false;
			while ($objIndexList = $objResultList->FetchObject()) {
				if ($objIndexList->unique == 1) {
					$objResult = $objDb->Query(sprintf('PRAGMA index_info (%s%s%s)', "'", $objIndexList->name, "'"));
					while ($objIndex = $objResult->FetchObject()) {
						if ($objIndex->name == $this->strName)
							$this->blnUnique = true;
					}
				}
			}
		}
		$strSqlite3FieldType = $mixFieldData->GetColumn('type');
		if (($intPos = strpos($strSqlite3FieldType, '(')) > 0)
			$strSqlite3FieldType = substr($strSqlite3FieldType, 0, $intPos);
		$strSqlite3FieldType = strtoupper($strSqlite3FieldType);
		$this->SetFieldType($strSqlite3FieldType);
	}

	protected function SetFieldType($strSqlite3FieldType) {
		switch ($strSqlite3FieldType) {
			case 'TINYINT':
			case 'BOOLEAN':
				if ($this->intMaxLength == 1)
					$this->strType = QDatabaseFieldType::Bit;
				else
					$this->strType = QDatabaseFieldType::Integer;
				break;
			case 'INTEGER':
			case 'INT':
			case 'BIGINT':
			case 'SMALLINT':
			case 'MEDIUMINT':
				$this->strType = QDatabaseFieldType::Integer;
				break;
			case 'FLOAT':
			case 'DECIMAL':
				$this->strType = QDatabaseFieldType::Float;
				break;
			case 'DOUBLE':
				// NOTE: PHP does not offer full support of double-precision floats.
				// Value will be set as a VarChar which will guarantee that the precision will be maintained.
				//    However, you will not be able to support full typing control (e.g. you would
				//    not be able to use a QFloatTextBox -- only a regular QTextBox)
				$this->strType = QDatabaseFieldType::VarChar;
				break;
			case 'TIMESTAMP':
				// System-generated Timestamp values need to be treated as plain text
				$this->strType = QDatabaseFieldType::VarChar;
				$this->blnTimestamp = true;
				break;
			case 'DATE':
				$this->strType = QDatabaseFieldType::Date;
				break;
			case 'TIME':
				$this->strType = QDatabaseFieldType::Time;
				break;
			case 'DATETIME':
				$this->strType = QDatabaseFieldType::DateTime;
				break;
			case 'TINYBLOB':
			case 'MEDIUMBLOB':
			case 'LONGBLOB':
			case 'BLOB':
				$this->strType = QDatabaseFieldType::Blob;
				break;
			case 'VARCHAR':
			case 'TEXT':
			case 'MEMO':
			case 'LONGTEXT':
			case 'MEDIUMTEXT':
				$this->strType = QDatabaseFieldType::VarChar;
				break;
			case 'CHAR':
				$this->strType = QDatabaseFieldType::Char;
				break;
			case 'YEAR':
				$this->strType = QDatabaseFieldType::Integer;
				break;
			default:
				throw new Exception('Unable to determine Sqlite3 Database Field Type: ' . $strSqlite3FieldType);
				break;
		}
	}
}

/**
 * QSqLite3PdoDatabaseException
 */
class QSqLite3PdoDatabaseException extends QPdoDatabaseException {

}
?>