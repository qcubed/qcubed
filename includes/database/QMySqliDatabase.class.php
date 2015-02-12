<?php
	/**
	 *
	 * @package DatabaseAdapters
	 */
	class QMySqliDatabase extends QDatabaseBase {
		const Adapter = 'MySql Improved Database Adapter for MySQL 4';

		protected $objMySqli;

		protected $strEscapeIdentifierBegin = '`';
		protected $strEscapeIdentifierEnd = '`';

		public function SqlLimitVariablePrefix($strLimitInfo) {
			// MySQL uses Limit by Suffixes (via a LIMIT clause)

			// If requested, use SQL_CALC_FOUND_ROWS directive to utilize GetFoundRows() method
			if (array_key_exists('usefoundrows', $this->objConfigArray) && $this->objConfigArray['usefoundrows'])
				return 'SQL_CALC_FOUND_ROWS';

			return null;
		}

		public function SqlLimitVariableSuffix($strLimitInfo) {
			// Setup limit suffix (if applicable) via a LIMIT clause 
			if (strlen($strLimitInfo)) {
				if (strpos($strLimitInfo, ';') !== false)
					throw new Exception('Invalid Semicolon in LIMIT Info');
				if (strpos($strLimitInfo, '`') !== false)
					throw new Exception('Invalid Backtick in LIMIT Info');
				return "LIMIT $strLimitInfo";
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

				return "ORDER BY $strSortByInfo";
			}
			
			return null;
		}

		public function InsertOrUpdate($strTable, $mixColumnsAndValuesArray, $strPKNames = null) {
			$strEscapedArray = $this->EscapeIdentifiersAndValues($mixColumnsAndValuesArray);
			$strUpdateStatement = '';
			foreach ($strEscapedArray as $strColumn => $strValue) {
				if ($strUpdateStatement) $strUpdateStatement .= ', ';
				$strUpdateStatement .= $strColumn . ' = ' . $strValue;
			}
			$strSql = sprintf('INSERT INTO %s%s%s (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s',
				$this->EscapeIdentifierBegin, $strTable, $this->EscapeIdentifierEnd,
				implode(', ', array_keys($strEscapedArray)),
				implode(', ', array_values($strEscapedArray)),
				$strUpdateStatement
			);
			$this->ExecuteNonQuery($strSql);
		}

		public function Connect() {
			// Connect to the Database Server
			$this->objMySqli = new MySqli($this->Server, $this->Username, $this->Password, $this->Database, $this->Port);

			if (!$this->objMySqli)
				throw new QMySqliDatabaseException("Unable to connect to Database", -1, null);
			
			if ($this->objMySqli->error)
				throw new QMySqliDatabaseException($this->objMySqli->error, $this->objMySqli->errno, null);

			// Update "Connected" Flag
			$this->blnConnectedFlag = true;

			// Set to AutoCommit
			$this->NonQuery('SET AUTOCOMMIT=1;');

			// Set NAMES (if applicable)
			if (array_key_exists('encoding', $this->objConfigArray))
				$this->NonQuery('SET NAMES ' . $this->objConfigArray['encoding'] . ';');
		}

		public function __get($strName) {
			switch ($strName) {
				case 'AffectedRows':
					return $this->objMySqli->affected_rows;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		protected function ExecuteQuery($strQuery) {
			// Perform the Query
			$objResult = $this->objMySqli->query($strQuery);
			if ($this->objMySqli->error)
				throw new QMySqliDatabaseException($this->objMySqli->error, $this->objMySqli->errno, $strQuery);

			// Return the Result
			$objMySqliDatabaseResult = new QMySqliDatabaseResult($objResult, $this);
			return $objMySqliDatabaseResult;
		}

		protected function ExecuteNonQuery($strNonQuery) {
			// Perform the Query
			$this->objMySqli->query($strNonQuery);
			if ($this->objMySqli->error)
				throw new QMySqliDatabaseException($this->objMySqli->error, $this->objMySqli->errno, $strNonQuery);
		}
		
		public function GetTables() {
			// Use the MySQL "SHOW TABLES" functionality to get a list of all the tables in this database
			$objResult = $this->Query("SHOW TABLES");
			$strToReturn = array();
			while ($strRowArray = $objResult->FetchRow())
				array_push($strToReturn, $strRowArray[0]);
			return $strToReturn;
		}
		
		public function GetFieldsForTable($strTableName) {
			$objResult = $this->Query(sprintf('SELECT * FROM %s%s%s LIMIT 1', $this->strEscapeIdentifierBegin, $strTableName, $this->strEscapeIdentifierEnd));
			return $objResult->FetchFields();
		}

		public function InsertId($strTableName = null, $strColumnName = null) {
			return $this->objMySqli->insert_id;
		}

		public function Close() {
			$this->objMySqli->close();

			// Update Connected Flag
			$this->blnConnectedFlag = false;
		}

		protected function ExecuteTransactionBegin() {
			// Set to AutoCommit
			$this->NonQuery('SET AUTOCOMMIT=0;');
		}

		protected function ExecuteTransactionCommit() {
			$this->NonQuery('COMMIT;');
			// Set to AutoCommit
			$this->NonQuery('SET AUTOCOMMIT=1;');
		}

		protected function ExecuteTransactionRollBack() {
			$this->NonQuery('ROLLBACK;');
			// Set to AutoCommit
			$this->NonQuery('SET AUTOCOMMIT=1;');
		}

		public function GetFoundRows() {
			if (array_key_exists('usefoundrows', $this->objConfigArray) && $this->objConfigArray['usefoundrows']) {
				$objResult = $this->Query('SELECT FOUND_ROWS();');
				$strRow = $objResult->FetchArray();
				return $strRow[0];
			} else
				throw new QCallerException('Cannot call GetFoundRows() on the database when "usefoundrows" configuration was not set to true.');
		}

		public function GetIndexesForTable($strTableName) {
			// Figure out the Table Type (InnoDB, MyISAM, etc.) by parsing the Create Table description
			$strCreateStatement = $this->GetCreateStatementForTable($strTableName);
			$strTableType = $this->GetTableTypeForCreateStatement($strCreateStatement);

			switch (true) {
				case substr($strTableType, 0, 6) == 'MYISAM':
					return $this->ParseForIndexes($strCreateStatement);

				case substr($strTableType, 0, 6) == 'INNODB':
					return $this->ParseForIndexes($strCreateStatement);

				case substr($strTableType, 0, 6) == 'MEMORY':
				case substr($strTableType, 0, 4) == 'HEAP':
					return $this->ParseForIndexes($strCreateStatement);

				default:
					throw new Exception("Table Type is not supported: $strTableType");
			}
		}

		public function GetForeignKeysForTable($strTableName) {
			// Figure out the Table Type (InnoDB, MyISAM, etc.) by parsing the Create Table description
			$strCreateStatement = $this->GetCreateStatementForTable($strTableName);
			$strTableType = $this->GetTableTypeForCreateStatement($strCreateStatement);

			switch (true) {
				case substr($strTableType, 0, 6) == 'MYISAM':
					$objForeignKeyArray = array();
					break;

				case substr($strTableType, 0, 6) == 'MEMORY':
				case substr($strTableType, 0, 4) == 'HEAP':
					$objForeignKeyArray = array();
					break;

				case substr($strTableType, 0, 6) == 'INNODB':
					$objForeignKeyArray = $this->ParseForInnoDbForeignKeys($strCreateStatement);
					break;

				default:
					throw new Exception("Table Type is not supported: $strTableType");
			}

			return $objForeignKeyArray;
		}

		// MySql defines KeyDefinition to be [OPTIONAL_NAME] ([COL], ...)
		// If the key name exists, this will parse it out and return it
		private function ParseNameFromKeyDefinition($strKeyDefinition) {
			$strKeyDefinition = trim($strKeyDefinition);

			$intPosition = strpos($strKeyDefinition, '(');

			if ($intPosition === false)
				throw new Exception("Invalid Key Definition: $strKeyDefinition");
			else if ($intPosition == 0)
				// No Key Name Defined
				return null;
			
			// If we're here, then we have a key name defined
			$strName = trim(substr($strKeyDefinition, 0, $intPosition));
			
			// Rip Out leading and trailing "`" character (if applicable)
			if (substr($strName, 0, 1) == '`')
				return substr($strName, 1, strlen($strName) - 2);
			else
				return $strName;
		}

		// MySql defines KeyDefinition to be [OPTIONAL_NAME] ([COL], ...)
		// This will return an array of strings that are the names [COL], etc.
		private function ParseColumnNameArrayFromKeyDefinition($strKeyDefinition) {
			$strKeyDefinition = trim($strKeyDefinition);
			
			// Get rid of the opening "(" and the closing ")"
			$intPosition = strpos($strKeyDefinition, '(');
			if ($intPosition === false)
				throw new Exception("Invalid Key Definition: $strKeyDefinition");
			$strKeyDefinition = trim(substr($strKeyDefinition, $intPosition + 1));

			$intPosition = strpos($strKeyDefinition, ')');
			if ($intPosition === false)
				throw new Exception("Invalid Key Definition: $strKeyDefinition");
			$strKeyDefinition = trim(substr($strKeyDefinition, 0, $intPosition));

			// Create the Array
			// TODO: Current method doesn't support key names with commas or parenthesis in them!
			$strToReturn = explode(',', $strKeyDefinition);

			// Take out trailing and leading "`" character in each name (if applicable)
			for ($intIndex = 0; $intIndex < count($strToReturn); $intIndex++) {
				$strColumn = $strToReturn[$intIndex];

				if (substr($strColumn, 0, 1) == '`')
					$strColumn = substr($strColumn, 1, strpos($strColumn, '`', 1) - 1);

				$strToReturn[$intIndex] = $strColumn;
			}

			return $strToReturn;
		}

		private function ParseForIndexes($strCreateStatement) {
			// MySql nicely splits each object in a table into it's own line
			// Split the create statement into lines, and then pull out anything
			// that says "PRIMARY KEY", "UNIQUE KEY", or just plain ol' "KEY"
			$strLineArray = explode("\n", $strCreateStatement);

			$objIndexArray = array();
			
			// We don't care about the first line or the last line
			for ($intIndex = 1; $intIndex < (count($strLineArray) - 1); $intIndex++) {
				$strLine = $strLineArray[$intIndex];

				// Each object has a two-space indent
				// So this is a key object if any of those key-related words exist at position 2
				switch (2) {
					case (strpos($strLine, 'PRIMARY KEY')):
						$strKeyDefinition = substr($strLine, strlen('  PRIMARY KEY '));

						$strKeyName = $this->ParseNameFromKeyDefinition($strKeyDefinition);
						$strColumnNameArray = $this->ParseColumnNameArrayFromKeyDefinition($strKeyDefinition);

						$objIndex = new QDatabaseIndex($strKeyName, $blnPrimaryKey = true, $blnUnique = true, $strColumnNameArray);
						array_push($objIndexArray, $objIndex);
						break;

					case (strpos($strLine, 'UNIQUE KEY')):
						$strKeyDefinition = substr($strLine, strlen('  UNIQUE KEY '));

						$strKeyName = $this->ParseNameFromKeyDefinition($strKeyDefinition);
						$strColumnNameArray = $this->ParseColumnNameArrayFromKeyDefinition($strKeyDefinition);

						$objIndex = new QDatabaseIndex($strKeyName, $blnPrimaryKey = false, $blnUnique = true, $strColumnNameArray);
						array_push($objIndexArray, $objIndex);
						break;

					case (strpos($strLine, 'KEY')):
						$strKeyDefinition = substr($strLine, strlen('  KEY '));

						$strKeyName = $this->ParseNameFromKeyDefinition($strKeyDefinition);
						$strColumnNameArray = $this->ParseColumnNameArrayFromKeyDefinition($strKeyDefinition);

						$objIndex = new QDatabaseIndex($strKeyName, $blnPrimaryKey = false, $blnUnique = false, $strColumnNameArray);
						array_push($objIndexArray, $objIndex);
						break;
				}
			}

			return $objIndexArray;
		}

		private function ParseForInnoDbForeignKeys($strCreateStatement) {
			// MySql nicely splits each object in a table into it's own line
			// Split the create statement into lines, and then pull out anything
			// that starts with "CONSTRAINT" and contains "FOREIGN KEY"
			$strLineArray = explode("\n", $strCreateStatement);

			$objForeignKeyArray = array();

			// We don't care about the first line or the last line
			for ($intIndex = 1; $intIndex < (count($strLineArray) - 1); $intIndex++) {
				$strLine = $strLineArray[$intIndex];

				// Check to see if the line:
				// * Starts with "CONSTRAINT" at position 2 AND
				// * contains "FOREIGN KEY"
				if ((strpos($strLine, "CONSTRAINT") == 2) &&
					(strpos($strLine, "FOREIGN KEY") !== false)) {
					$strLine = substr($strLine, strlen('  CONSTRAINT '));
					
					// By the end of the following lines, we will end up with a strTokenArray
					// Index 0: the FK name
					// Index 1: the list of columns that are the foreign key
					// Index 2: the table which this FK references
					// Index 3: the list of columns which this FK references
					$strTokenArray = explode(' FOREIGN KEY ', $strLine);
					$strTokenArray[1] = explode(' REFERENCES ', $strTokenArray[1]);
					$strTokenArray[2] = $strTokenArray[1][1];
					$strTokenArray[1] = $strTokenArray[1][0];
					$strTokenArray[2] = explode(' ', $strTokenArray[2]);
					$strTokenArray[3] = $strTokenArray[2][1];
					$strTokenArray[2] = $strTokenArray[2][0];
					
					// Cleanup, and change Index 1 and Index 3 to be an array based on the
					// parsed column name list
					if (substr($strTokenArray[0], 0, 1) == '`')
						$strTokenArray[0] = substr($strTokenArray[0], 1, strlen($strTokenArray[0]) - 2);
					$strTokenArray[1] = $this->ParseColumnNameArrayFromKeyDefinition($strTokenArray[1]);
					if (substr($strTokenArray[2], 0, 1) == '`')
						$strTokenArray[2] = substr($strTokenArray[2], 1, strlen($strTokenArray[2]) - 2);
					$strTokenArray[3] = $this->ParseColumnNameArrayFromKeyDefinition($strTokenArray[3]);
					
					// Create the FK object and add it to the return array
					$objForeignKey = new QDatabaseForeignKey($strTokenArray[0], $strTokenArray[1], $strTokenArray[2], $strTokenArray[3]);
					array_push($objForeignKeyArray, $objForeignKey);
					
					// Ensure the FK object has matching column numbers (or else, throw)
					if ((count($objForeignKey->ColumnNameArray) == 0) ||
						(count($objForeignKey->ColumnNameArray) != count($objForeignKey->ReferenceColumnNameArray)))
						throw new Exception("Invalid Foreign Key definition: $strLine");
				}
			}
			return $objForeignKeyArray;
		}

		private function GetCreateStatementForTable($strTableName) {
			// Use the MySQL "SHOW CREATE TABLE" functionality to get the table's Create statement
			$objResult = $this->Query(sprintf('SHOW CREATE TABLE `%s`', $strTableName));
			$objRow = $objResult->FetchRow();
			$strCreateTable = $objRow[1];
			$strCreateTable = str_replace("\r", "", $strCreateTable);
			return $strCreateTable;
		}

		private function GetTableTypeForCreateStatement($strCreateStatement) {
			// Table Type is in the last line of the Create Statement, "TYPE=DbTableType"
			$strLineArray = explode("\n", $strCreateStatement);
			$strFinalLine = strtoupper($strLineArray[count($strLineArray) - 1]);

			if (substr($strFinalLine, 0, 7) == ') TYPE=') {
				return trim(substr($strFinalLine, 7));
			} else if (substr($strFinalLine, 0, 9) == ') ENGINE=') {
				return trim(substr($strFinalLine, 9));
			} else
				throw new Exception("Invalid Table Description");
		}

        /**
         * 
         * @param string $sql
         * @return QMySqliDatabaseResult
         */
		public function ExplainStatement($sql) {
            // As of MySQL 5.6.3, EXPLAIN provides information about
            // SELECT, DELETE, INSERT, REPLACE, and UPDATE statements.
            // Before MySQL 5.6.3, EXPLAIN provides information only about SELECT statements.
            
            $objDbResult = $this->Query("select version()");
            $strDbRow = $objDbResult->FetchRow();
            $strVersion = QType::Cast($strDbRow[0], QType::String);
            $strVersionArray = explode('.', $strVersion);
            $strMajorVersion = null;
            if (count($strVersionArray) > 0) {
                $strMajorVersion = $strVersionArray[0];
            }
            if (null === $strMajorVersion) {
                return null;
            }
            if (intval($strMajorVersion) > 5) {
                return $this->Query("EXPLAIN " . $sql);
            } else if (5 == intval($strMajorVersion)) {
                $strMinorVersion = null;
                if (count($strVersionArray) > 1) {
                    $strMinorVersion = $strVersionArray[1];
                }
                if (null === $strMinorVersion) {
                    return null;
                }
                if (intval($strMinorVersion) > 6) {
                    return $this->Query("EXPLAIN " . $sql);
                } else if (6 == intval($strMinorVersion)) {
                    $strSubMinorVersion = null;
                    if (count($strVersionArray) > 2) {
                        $strSubMinorVersion = $strVersionArray[2];
                    }
                    if (null === $strSubMinorVersion) {
                        return null;
                    }
                    if (!is_integer($strSubMinorVersion)) {
                        $strSubMinorVersionArray = explode("-", $strSubMinorVersion);
                        if (count($strSubMinorVersionArray) > 1) {
                           $strSubMinorVersion = $strSubMinorVersionArray[0];
                           if (!is_integer($strSubMinorVersion)) {
                               // Failed to determine the sub-minor version.
                               return null;
                           }
                        } else {
                            // Failed to determine the sub-minor version.
                            return null;
                        }
                    }
                    if (intval($strSubMinorVersion) > 2) {
                        return $this->Query("EXPLAIN " . $sql);
                    } else {
                        // We have the version before 5.6.3
                        // let's check if it is SELECT-only request
                        if (0 == substr_count($sql, "DELETE") &&
                            0 == substr_count($sql, "INSERT") &&
                            0 == substr_count($sql, "REPLACE") &&
                            0 == substr_count($sql, "UPDATE")
                        ) {
                            return $this->Query("EXPLAIN " . $sql);
                        }
                    }
                }
            }
            // Return null by default
			return null;
		}
	}

	/**
	 *
	 * @package DatabaseAdapters
	 */
	class QMySqliDatabaseException extends QDatabaseExceptionBase {
		public function __construct($strMessage, $intNumber, $strQuery) {
			parent::__construct(sprintf("MySqli Error: %s", $strMessage), 2);
			$this->intErrorNumber = $intNumber;
			$this->strQuery = $strQuery;
		}
	}

	/**
	 * Class to handle results sent by database upon querying
	 * @package DatabaseAdapters
	 */
	class QMySqliDatabaseResult extends QDatabaseResultBase {
		protected $objMySqliResult;
		protected $objDb;

		public function __construct(mysqli_result $objResult, QMySqliDatabase $objDb) {
			$this->objMySqliResult = $objResult;
			$this->objDb = $objDb;
		}

		public function FetchArray() {
			return $this->objMySqliResult->fetch_array();
		}

		public function FetchFields() {
			$objArrayToReturn = array();
			while ($objField = $this->objMySqliResult->fetch_field())
				array_push($objArrayToReturn, new QMySqliDatabaseField($objField, $this->objDb));
			return $objArrayToReturn;
		}

		public function FetchField() {
			if ($objField = $this->objMySqliResult->fetch_field())
				return new QMySqliDatabaseField($objField, $this->objDb);
		}

		public function FetchRow() {
			return $this->objMySqliResult->fetch_row();
		}

		public function MySqlFetchField() {
			return $this->objMySqliResult->fetch_field();
		}

		public function CountRows() {
			return $this->objMySqliResult->num_rows;
		}

		public function CountFields() {
			return $this->objMySqliResult->num_fields();
		}

		public function Close() {
			$this->objMySqliResult->free();
		}
		
		public function GetNextRow() {
			$strColumnArray = $this->FetchArray();
			
			if ($strColumnArray)
				return new QMySqliDatabaseRow($strColumnArray);
			else
				return null;
		}

		public function GetRows() {
			$objDbRowArray = array();
			while ($objDbRow = $this->GetNextRow())
				array_push($objDbRowArray, $objDbRow);
			return $objDbRowArray;
		}
	}

	/**
	 *
	 * @package DatabaseAdapters
	 */
	class QMySqliDatabaseRow extends QDatabaseRowBase {
		protected $strColumnArray;

		public function __construct($strColumnArray) {
			$this->strColumnArray = $strColumnArray;
		}

		/**
		 * Gets the value of a column from a result row returned by the database
		 *
		 * @param string                  $strColumnName Name of te column
		 * @param null|QDatabaseFieldType $strColumnType Data type
		 *
		 * @return mixed
		 */
		public function GetColumn($strColumnName, $strColumnType = null) {
			if (!isset($this->strColumnArray[$strColumnName])) {
				return null;
			}
			$strColumnValue = $this->strColumnArray[$strColumnName];

			switch ($strColumnType) {
				case QDatabaseFieldType::Bit:
					// Account for single bit value
					$chrBit = $strColumnValue;
					if ((strlen($chrBit) == 1) && (ord($chrBit) == 0))
						return false;

					// Otherwise, use PHP conditional to determine true or false
					return ($strColumnValue) ? true : false;

				case QDatabaseFieldType::Blob:
				case QDatabaseFieldType::Char:
				case QDatabaseFieldType::VarChar:
					return QType::Cast($strColumnValue, QType::String);

				case QDatabaseFieldType::Date:
					return new QDateTime($strColumnValue, null, QDateTime::DateOnlyType);
				case QDatabaseFieldType::DateTime:
					return new QDateTime($strColumnValue, null, QDateTime::DateAndTimeType);
				case QDatabaseFieldType::Time:
					return new QDateTime($strColumnValue, null, QDateTime::TimeOnlyType);

				case QDatabaseFieldType::Float:
					return QType::Cast($strColumnValue, QType::Float);

				case QDatabaseFieldType::Integer:
					return QType::Cast($strColumnValue, QType::Integer);

				default:
					return $strColumnValue;
			}
		}

		/**
		 * Tells whether a particular column exists in a returned database row
		 *
		 * @param string $strColumnName Name of te column
		 *
		 * @return bool
		 */
		public function ColumnExists($strColumnName) {
			return array_key_exists($strColumnName, $this->strColumnArray);
		}

		public function GetColumnNameArray() {
			return $this->strColumnArray;
		}
	}

	/**
	 *
	 * @package DatabaseAdapters
	 */
	class QMySqliDatabaseField extends QDatabaseFieldBase {
		public function __construct($mixFieldData, $objDb = null) {
			$this->strName = $mixFieldData->name;
			$this->strOriginalName = $mixFieldData->orgname;
			$this->strTable = $mixFieldData->table;
			$this->strOriginalTable = $mixFieldData->orgtable;
			$this->strDefault = $mixFieldData->def;
			$this->intMaxLength = null;
			$this->strComment = null;

			// Set strOriginalName to Name if it isn't set
			if (!$this->strOriginalName)
				$this->strOriginalName = $this->strName;
			
			if($this->strOriginalTable)
			{
				$objDescriptionResult = $objDb->Query(sprintf("SHOW FULL FIELDS FROM `%s`", $this->strOriginalTable));
				while (($objRow = $objDescriptionResult->FetchArray())) {
					if ($objRow["Field"] == $this->strOriginalName) {
 						
 						$this->strDefault = $objRow["Default"]; 
						// Calculate MaxLength of this column (e.g. if it's a varchar, calculate length of varchar
						// NOTE: $mixFieldData->max_length in the MySQL spec is **DIFFERENT**
						$strLengthArray = explode("(", $objRow["Type"]);
						if ((count($strLengthArray) > 1) &&
							(strtolower($strLengthArray[0]) != 'enum') &&
							(strtolower($strLengthArray[0]) != 'set')) {
							$strLengthArray = explode(")", $strLengthArray[1]);
							$this->intMaxLength = $strLengthArray[0];

							// If the length is something like (7,2), then let's pull out just the "7"
							$intCommaPosition = strpos($this->intMaxLength, ',');
							if ($intCommaPosition !== false) {
								$this->intMaxLength = substr($this->intMaxLength, 0, $intCommaPosition);
							}

							if (!is_numeric($this->intMaxLength)) {
								throw new Exception("Not a valid Column Length: " . $objRow["Type"]);
							}
						}
						
						// Get the field comment
						$this->strComment = $objRow["Comment"];
					}
				}
			}

			$this->blnIdentity = ($mixFieldData->flags & MYSQLI_AUTO_INCREMENT_FLAG) ? true: false;
			$this->blnNotNull = ($mixFieldData->flags & MYSQLI_NOT_NULL_FLAG) ? true : false;
			$this->blnPrimaryKey = ($mixFieldData->flags & MYSQLI_PRI_KEY_FLAG) ? true : false;
			$this->blnUnique = ($mixFieldData->flags & MYSQLI_UNIQUE_KEY_FLAG) ? true : false;
			
			$this->SetFieldType($mixFieldData->type);

			// Special situation that we take advantage of to automatically implement optimistic locking
			if ($mixFieldData->type == MYSQLI_TYPE_TIMESTAMP &&
					$mixFieldData->flags & MYSQLI_ON_UPDATE_NOW_FLAG) {
				$this->strType = QDatabaseFieldType::VarChar;
				$this->blnTimestamp = true;
			}
		}

		protected function SetFieldType($intMySqlFieldType) {
			switch ($intMySqlFieldType) {
				case MYSQLI_TYPE_TINY:
					if ($this->intMaxLength == 1)
						$this->strType = QDatabaseFieldType::Bit;
					else
						$this->strType = QDatabaseFieldType::Integer;
					break;
				case MYSQLI_TYPE_SHORT:
				case MYSQLI_TYPE_LONG:
				case MYSQLI_TYPE_LONGLONG:
				case MYSQLI_TYPE_INT24:
					$this->strType = QDatabaseFieldType::Integer;
					break;
				case MYSQLI_TYPE_NEWDECIMAL:
				case MYSQLI_TYPE_DECIMAL:
				case MYSQLI_TYPE_FLOAT:
					$this->strType = QDatabaseFieldType::Float;
					break;
				case MYSQLI_TYPE_DOUBLE:
					// NOTE: PHP does not offer full support of double-precision floats.
					// Value will be set as a VarChar which will guarantee that the precision will be maintained.
					//    However, you will not be able to support full typing control (e.g. you would
					//    not be able to use a QFloatTextBox -- only a regular QTextBox)
					$this->strType = QDatabaseFieldType::VarChar;
					break;
				case MYSQLI_TYPE_DATE:
					$this->strType = QDatabaseFieldType::Date;
					break;
				case MYSQLI_TYPE_TIME:
					$this->strType = QDatabaseFieldType::Time;
					break;
				case MYSQLI_TYPE_TIMESTAMP:
				case MYSQLI_TYPE_DATETIME:
					$this->strType = QDatabaseFieldType::DateTime;
					break;
				case MYSQLI_TYPE_TINY_BLOB:
				case MYSQLI_TYPE_MEDIUM_BLOB:
				case MYSQLI_TYPE_LONG_BLOB:
				case MYSQLI_TYPE_BLOB:
					$this->strType = QDatabaseFieldType::Blob;
					break;
				case MYSQLI_TYPE_STRING:
				case MYSQLI_TYPE_VAR_STRING:
					$this->strType = QDatabaseFieldType::VarChar;
					break;
				case MYSQLI_TYPE_CHAR:
					$this->strType = QDatabaseFieldType::Char;
					break;
				case MYSQLI_TYPE_INTERVAL:
					throw new Exception("QCubed MySqliDatabase library: MYSQLI_TYPE_INTERVAL is not supported");
					break;
				case MYSQLI_TYPE_NULL:
					throw new Exception("QCubed MySqliDatabase library: MYSQLI_TYPE_NULL is not supported");
					break;
				case MYSQLI_TYPE_YEAR:
					$this->strType = QDatabaseFieldType::Integer;
					break;
				case MYSQLI_TYPE_NEWDATE:
					throw new Exception("QCubed MySqliDatabase library: MYSQLI_TYPE_NEWDATE is not supported");
					break;
				case MYSQLI_TYPE_ENUM:
					throw new Exception("QCubed MySqliDatabase library: MYSQLI_TYPE_ENUM is not supported.  Use TypeTables instead.");
					break;
				case MYSQLI_TYPE_SET:
					throw new Exception("QCubed MySqliDatabase library: MYSQLI_TYPE_SET is not supported.  Use TypeTables instead.");
					break;
				case MYSQLI_TYPE_GEOMETRY:
					throw new Exception("QCubed MySqliDatabase library: MYSQLI_TYPE_GEOMETRY is not supported");
					break;
				default:
					throw new Exception("Unable to determine MySqli Database Field Type: " . $intMySqlFieldType);
					break;
			}
		}
	}
?>
