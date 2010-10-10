<?php
	/**
	 * Database Adapter for Microsoft SQL Server 2005/2008
	 * Utilizes the native Microsoft SQL Server extension php_sqlsrv.dll version 1.1 (win)
	 * http://www.microsoft.com/sqlserver/2005/en/us/PHP-Driver.aspx
         *
         * Requirements:
         * - Windows XP SP3/2003 SP1/Vista/2008/7 or later
         * - Microsoft SQL Server 2008 Native Client
         * - PHP 5.2.4 or later
         * - SQL Server Driver for PHP 1.1
	 *
	 * @package DatabaseAdapters
	 */

	/**
	 * Database Adapter for Microsoft SQL Server 2005/2008
	 *
	 *
	 * LimitInfo and Query utilizes an interal SQL tag QCODO_OFFSET<#>, where # represents
	 * the number of rows to offset for "Limit"-based queries.  The QCODO_OFFSET is added
	 * internally by SqlLimitVariablePrefix(), and it is handled (and removed from the query)
	 * by Query().  In error messages and DB profiling, the QCODO_OFFSET<#> tag *WILL* appear
	 * (if applicable).  The framework will handle this gracefully, but obviously, if you try
	 * and cut and paste SQL code that contains QCODO_OFFSET<#> into QueryAnalyzer, the query
	 * will fail, so just be aware of that.  If you want to do something like test queries
	 * with QueryAnalyzer, just remember to manually remove any QCODO_OFFSET<#> information.
	 *
	 * ENHANCEMENTS over MSSQL based-driver:
	 * - varchar limit of 254 characters no longer exists, uses full varchar(8000)
         * - supports different date formats
         * - faster
	 *
	 * DATE VALUES:
	 * If the date format of your database is different from the standard english format of
	 * 'YYYY-MM-DD hhhh:mm:ss' you have to declare it by setting the option 'dateformat'
	 * in the database connection array in the configuration.inc.php.
	 * For instance if your database wants german date settings you would set it to
	 * 'DD.MM.YYYY hhhh:mm:ss'.
	 *
	 * LIMITATIONS:
	 * - GROUP BY
	 * Using GROUP BY clause is broken because:
	 * a) Current QQuery implementation is MySQL specific. Standard SQL wants every column
	 * in the SELECT statement, that is not an aggregate statement, in the GROUP BY clause
	 * and this behaviour is currently not supported. This limitation is valid too for the
	 * MSSQL based adapter.
	 *
	 * - Datatypes currently not fully supported
	 * sql_variant, varbinary(max), geometry, geography, hierarchyid, uniqueidentifier
	 *
	 *
	 * @copyright	Copyright (C) 2009,2010 Andreas Krohn
	 * @author	Andreas Krohn <akrohn.pronet@googlemail.com>, based on code by Mike Ho
	 * @license	http://www.opensource.org/licenses/mit-license.php
	 * @package	DatabaseAdapters
	 */
	class QSqlServer2005Database extends QDatabaseBase {
		const Adapter = 'Microsoft SQL Server 2005/2008 Database Adapter';

		protected $objSqlSrvConn;
		protected $objMostRecentStatement; // is always the last executed statement

		protected $strEscapeIdentifierBegin = '[';
		protected $strEscapeIdentifierEnd = ']';

		// Default query options array
		protected $mixedOptionsArray = array(
		);

		/**
		 * Returns extended error information about the last sqlsrv operation performed
		 *
		 * @param string strError Returns Errormessage By Reference
		 * @param string strLastErrorCode Returns last ErrorCode By Reference
		 * @return string Errorinformation
		 */
		private function GetErrorInformation(&$strError, &$strLastErrorCode) {
			$strError = '';
			$strLastErrorCode = '';

			// Determine only the errorinformation
			$objErrors = sqlsrv_errors(SQLSRV_ERR_ERRORS);
			if(!is_null($objErrors)) {
				// Get all errors
				foreach($objErrors as $strErrorArray) {
					$strError .= ' SQLSTATE: '.$strErrorArray['SQLSTATE'].', Code: '.$strErrorArray['code'].', Message: '.$strErrorArray['message'];
					$strLastErrorCode = $strErrorArray['code'];
				}
			}
		}

		/**
		 * Properly escapes $mixData to be used as a SQL query parameter.
		 * If IncludeEquality is set (usually not), then include an equality operator.
		 * So for most data, it would just be "=".  But, for example,
		 * if $mixData is NULL, then most RDBMS's require the use of "IS".
		 *
		 * @param mixed $mixData
		 * @param boolean $blnIncludeEquality whether or not to include an equality operator
		 * @param boolean $blnReverseEquality whether the included equality operator should be a "NOT EQUAL", e.g. "!="
		 * @return string the properly formatted SQL variable
		 */
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
							return '= 0';
						else
							return '!= 0';
					} else {
						// Check against NULL, True then False
						if (is_null($mixData))
							return 'IS NULL';
						else if ($mixData)
							return '!= 0';
						else
							return '= 0';
					}
				} else {
					// Check against NULL, True then False
					if (is_null($mixData))
						return 'NULL';
					else if ($mixData)
						return '1';
					else
						return '0';
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

			// Check for DATE Value and use the desired db-date-format
			if ($mixData instanceof QDateTime) {
				return $strToReturn . sprintf("'%s'", $mixData->qFormat($this->DateFormat));
			}

			// Assume it's some kind of string value
			return $strToReturn . sprintf("'%s'", str_replace("'", "''", $mixData));
		}

		public function SqlLimitVariablePrefix($strLimitInfo) {
			// Setup limit suffix (if applicable) via a TOP clause
			// Add QCODO_OFFSET tag if applicable

			if (strlen($strLimitInfo)) {
				if (strpos($strLimitInfo, ';') !== false)
					throw new Exception('Invalid Semicolon in LIMIT Info');
				if (strpos($strLimitInfo, '`') !== false)
					throw new Exception('Invalid Backtick in LIMIT Info');

				// First figure out if we HAVE an offset
				$strArray = explode(',', $strLimitInfo);

				if (count($strArray) == 2) {
					// Yep -- there's an offset
					return sprintf(
						'TOP %s QCODO_OFFSET<%s>',
						($strArray[0] + $strArray[1]),
						$strArray[0]);
				} else if (count($strArray) == 1) {
					return 'TOP ' . $strArray[0];
				} else {
					throw new QSqlServer2005DatabaseException('Invalid Limit Info: ' . $strLimitInfo, 0, null);
				}
			}

			return null;
		}

		public function SqlLimitVariableSuffix($strLimitInfo) {
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

		public function Connect() {
			// Lookup Adapter-Specific Connection Properties
			$strServer = $this->Server;
			$strName = $this->Database;
			$strUsername = $this->Username;
			$strPassword = $this->Password;
			$strPort = $this->Port;

			if ($strPort) {
				// Windows Servers
				if (array_key_exists('OS', $_SERVER) && stristr($_SERVER['OS'], 'Win') !== false)
					$strServer .= ',' . $strPort;
				// All Other Servers
				else
					$strServer .= ':' . $strPort;
			}

			// define the characterset for the sqlsrv driver
			// special handling for utf-8 data
			$strCharacterset = (QApplication::$EncodingType == 'UTF-8') ? 'UTF-8' : 'SQLSRV_ENC_CHAR';

			// Connect to the Database Server

			// Disable warnings as errors behavior
			sqlsrv_configure("WarningsReturnAsErrors", 0);

			// Set connection parameters
			$strConnectionInfoArray = array(
				'UID'=>$strUsername
				, 'PWD'=>$strPassword
				, 'Database'=>$strName
				, 'CharacterSet'=>$strCharacterset
			);

			// Connect using SQL Server Authentication
			$this->objSqlSrvConn = sqlsrv_connect($strServer, $strConnectionInfoArray);
			if($this->objSqlSrvConn === false) {
			    // Determine the errorinformation
			    $this->GetErrorInformation($strErrorinformation, $strErrorCode);
			    $objException = new QSqlServer2005DatabaseException('Unable to connect: ' . $strErrorinformation, $strErrorCode, null);
			    $objException->IncrementOffset();
			    throw $objException;
			}

			// Update Connected Flag
			$this->blnConnectedFlag = true;
		}

		public function __get($strName) {
			switch ($strName) {
				case 'AffectedRows':
					return sqlsrv_rows_affected($this->objMostRecentStatement);
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
			// First, check for QCODO_OFFSET<#> for LIMIT INFO Offseting
			if ( ($intPosition = strpos($strQuery, 'QCODO_OFFSET<')) !== false) {
				$intEndPosition = strpos($strQuery, '>', $intPosition);
				if ($intEndPosition === false)
					throw new QSqlServer2005DatabaseException('Invalid QCODO_OFFSET', 0, $strQuery);
				$intOffset = QType::Cast(substr($strQuery,
					$intPosition + 13 /* len of QCODO_OFFSET< */,
					$intEndPosition - $intPosition - 13), QType::Integer);
				$strQuery = substr($strQuery, 0, $intPosition) . substr($strQuery, $intEndPosition + 1);
			} else
				$intOffset = 0;

			// Perform the Query
			$objResult = sqlsrv_query($this->objSqlSrvConn, $strQuery, NULL, $this->mixedOptionsArray);
			if ($objResult === false) {
				// Determine the errorinformation
				$this->GetErrorInformation($strErrorinformation, $strErrorCode);
				throw new QSqlServer2005DatabaseException($strErrorinformation, $strErrorCode, $strQuery);
			}

			// Remember most recent statement
			$this->objMostRecentStatement = $objResult;

			// Return the Result
			$objSqlServerDatabaseResult = new QSqlServer2005DatabaseResult($objResult, $this);

			// Perform Offsetting (if applicable)
			for ($intIndex = 0; $intIndex < $intOffset; $intIndex++) {
				$objRow = $objSqlServerDatabaseResult->FetchRow();
				if (!$objRow)
					return $objSqlServerDatabaseResult;
			}

			return $objSqlServerDatabaseResult;
		}

		protected function ExecuteNonQuery($strNonQuery) {
			// Perform the NonQuery
			$objResult = sqlsrv_query($this->objSqlSrvConn, $strNonQuery, NULL, $this->mixedOptionsArray);
			if ($objResult === false) {
				// Determine the errorinformation
				$this->GetErrorInformation($strErrorinformation, $strErrorCode);
				throw new QSqlServer2005DatabaseException($strErrorinformation, $strErrorCode, $strNonQuery);
			}

			// Remember most recent statement
			$this->objMostRecentStatement = $objResult;
		}

		public function GetTables() {
			$objResult = $this->Query("
				SELECT  obj.name
				FROM    sys.objects obj
				WHERE   obj.type = 'U' AND
					obj.name NOT LIKE N'#%' AND
					obj.is_ms_shipped = 0
				ORDER BY obj.name ASC
			");

			$strToReturn = array();
			while ($strRowArray = $objResult->FetchRow()) {
				array_push($strToReturn, $strRowArray[0]);
			}
			return $strToReturn;
		}

		public function GetTableForId($intTableId) {
			$intTableId = $this->SqlVariable($intTableId);
			$strQuery = sprintf('
				SELECT  obj.name
				FROM    sys.objects obj
				WHERE   obj.object_id = %s
			', $intTableId);

			$objResult = $this->Query($strQuery);
			$objRow = $objResult->FetchRow();
			return $objRow[0];
		}

		public function GetFieldsForTable($strTableName) {
			$strTableName = $this->SqlVariable($strTableName);

			$strQuery = sprintf("
				SELECT  col.*
				    , typ.name AS data_type
				    , typ.scale AS scale
				    , obj.name AS table_name
				    , CAST(ex.value AS VARCHAR(8000)) AS comment
				FROM sys.columns col
				JOIN sys.objects obj ON obj.object_id = col.object_id 
				JOIN sys.types typ ON typ.system_type_id = col.system_type_id AND typ.user_type_id = col.user_type_id
				LEFT JOIN sys.extended_properties ex ON ex.major_id = col.object_id AND ex.minor_id = col.column_id AND ex.name = 'MS_Description' AND ex.class = 1 
				WHERE   obj.name = %s
				ORDER BY col.column_id ASC
			", $strTableName);

			$objResult = $this->Query($strQuery);

			$objFields = array();

			while ($objRow = $objResult->GetNextRow()) {
				array_push($objFields, new QSqlServer2005DatabaseField($objRow, $this));
			}

			return $objFields;
		}

		public function InsertId($strTableName = null, $strColumnName = null) {
			$strQuery = 'SELECT SCOPE_IDENTITY();';
			$objResult = $this->Query($strQuery);
			$objRow = $objResult->FetchRow();
			return $objRow[0];
		}

		public function Close() {
			sqlsrv_close($this->objSqlSrvConn);

			// Update Connected Flag
			$this->blnConnectedFlag = false;
		}

		/**
		 * Begin transaction
		 */
		public function TransactionBegin() {
			if (sqlsrv_begin_transaction($this->objSqlSrvConn) === false) {
				// Determine the errorinformation
				$this->GetErrorInformation($strErrorinformation, $strErrorCode);
				throw new QSqlServer2005DatabaseException($strErrorinformation, $strErrorCode, $strNonQuery);
			}
		}

		/**
		 * Commit transaction
		 */
		public function TransactionCommit() {
			if (sqlsrv_commit($this->objSqlSrvConn) === false) {
				// Determine the errorinformation
				$this->GetErrorInformation($strErrorinformation, $strErrorCode);
				throw new QSqlServer2005DatabaseException($strErrorinformation, $strErrorCode, $strNonQuery);
			}
		}

		/**
		 * Rollback transaction
		 */
		public function TransactionRollback() {
			if (sqlsrv_rollback($this->objSqlSrvConn) === false) {
				// Determine the errorinformation
				$this->GetErrorInformation($strErrorinformation, $strErrorCode);
				throw new QSqlServer2005DatabaseException($strErrorinformation, $strErrorCode, $strNonQuery);
			}
		}

		public function GetIndexesForTable($strTableName) {
			$objIndexArray = array();

			// Use sp_helpindex to pull the indexes
			$objResult = $this->Query(sprintf("EXEC sp_helpindex %s", $this->SqlVariable($strTableName)));
			while ($objRow = $objResult->GetNextRow()) {
				$strIndexDescription = $objRow->GetColumn('index_description');
				$strKeyName = $objRow->GetColumn('index_name');
				$blnPrimaryKey = (strpos($strIndexDescription, 'primary key') !== false);
				$blnUnique = (strpos($strIndexDescription, 'unique') !== false);
				$strColumnNameArray = explode(', ', $objRow->GetColumn('index_keys'));

				$objIndex = new QDatabaseIndex($strKeyName, $blnPrimaryKey, $blnUnique, $strColumnNameArray);
				array_push($objIndexArray, $objIndex);
			}

			return $objIndexArray;
		}

		public function GetForeignKeysForTable($strTableName) {
			$objForeignKeyArray = array();

			// Use Query to pull the FKs
			$strQuery = sprintf("
				SELECT  fk_table = FK.TABLE_NAME
				  , fk_column = CU.COLUMN_NAME
				  , pk_table = PK.TABLE_NAME
				  , pk_column = PT.COLUMN_NAME
				  , constraint_name = C.CONSTRAINT_NAME
				FROM    INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS C
				INNER JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS FK ON C.CONSTRAINT_NAME = FK.CONSTRAINT_NAME
				INNER JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS PK ON C.UNIQUE_CONSTRAINT_NAME = PK.CONSTRAINT_NAME
				INNER JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE CU ON C.CONSTRAINT_NAME = CU.CONSTRAINT_NAME
				INNER JOIN (SELECT  i1.TABLE_NAME
					      , i2.COLUMN_NAME
					FROM    INFORMATION_SCHEMA.TABLE_CONSTRAINTS i1
					INNER JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE i2 ON i1.CONSTRAINT_NAME = i2.CONSTRAINT_NAME
					WHERE   i1.CONSTRAINT_TYPE = 'PRIMARY KEY') PT ON PT.TABLE_NAME = PK.TABLE_NAME
				WHERE   FK.TABLE_NAME = %s
				ORDER BY C.constraint_name
			",
			$this->SqlVariable($strTableName));
			$objResult = $this->Query($strQuery);

			$strKeyName = '';
			while ($objRow = $objResult->GetNextRow()) {
				if ($strKeyName != $objRow->GetColumn('constraint_name')) {
					if ($strKeyName) {
						$objForeignKey = new QDatabaseForeignKey(
							$strKeyName,
							$strColumnNameArray,
							$strReferenceTableName,
							$strReferenceColumnNameArray);
						array_push($objForeignKeyArray, $objForeignKey);
					}

					$strKeyName = $objRow->GetColumn('constraint_name');
					$strReferenceTableName = $objRow->GetColumn('pk_table');
					$strColumnNameArray = array();
					$strReferenceColumnNameArray = array();
				}

				if (!array_search($objRow->GetColumn('fk_column'), $strColumnNameArray)) {
					array_push($strColumnNameArray, $objRow->GetColumn('fk_column'));
				}

				if (!array_search($objRow->GetColumn('pk_column'), $strReferenceColumnNameArray)) {
					array_push($strReferenceColumnNameArray, $objRow->GetColumn('pk_column'));
				}
			}

			if ($strKeyName) {
				$objForeignKey = new QDatabaseForeignKey(
					$strKeyName,
					$strColumnNameArray,
					$strReferenceTableName,
					$strReferenceColumnNameArray);
				array_push($objForeignKeyArray, $objForeignKey);
			}

			// Return the Array of Foreign Keys
			return $objForeignKeyArray;
		}
	}

	/**
	 *
	 * @package DatabaseAdapters
	 */
	class QSqlServer2005DatabaseException extends QDatabaseExceptionBase {
		public function __construct($strMessage, $intNumber, $strQuery) {
			parent::__construct(sprintf("SQL Server Error: %s", $strMessage), 2);
			$this->intErrorNumber = $intNumber;
			$this->strQuery = $strQuery;
		}
	}

	/**
	 *
	 * @package DatabaseAdapters
	 */
	class QSqlServer2005DatabaseResult extends QDatabaseResultBase {
		protected $objSqlSrvResult;
		protected $objDb;

		public function __construct($objResult, QSqlServer2005Database $objDb) {
			$this->objSqlSrvResult = $objResult;
			$this->objDb = $objDb;
		}

		public function FetchArray() {
			$strColumnArray = sqlsrv_fetch_array($this->objSqlSrvResult);
			if ($strColumnArray === false) {
				// Determine the errorinformation
				$this->GetErrorInformation($strErrorinformation, $strErrorCode);
				throw new QSqlServer2005DatabaseException($strErrorinformation, $strErrorCode, $strNonQuery);
			}
			return $strColumnArray;
		}

		/**
		 * Function is never used
		 * @todo
		 */
		public function FetchFields() {
			return null;  // Not used
		}

		/**
		 * Function is never used
		 * @todo
		 */
		public function FetchField() {
			return null;  // Not used
		}

		public function FetchRow() {
			return sqlsrv_fetch_array($this->objSqlSrvResult, SQLSRV_FETCH_NUMERIC);
		}

		public function CountRows() {
			return sqlsrv_num_rows($this->objSqlSrvResult);
		}

		public function CountFields() {
			return sqlsrv_num_fields($this->objSqlSrvResult);
		}

		public function Close() {
			sqlsrv_free_stmt($this->objSqlSrvResult);
		}

		public function GetNextRow() {
			$strColumnArray = $this->FetchArray();

			if (!is_null($strColumnArray))
				return new QSqlServer2005DatabaseRow($strColumnArray);
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
	class QSqlServer2005DatabaseRow extends QDatabaseRowBase {
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
						return ($this->strColumnArray[$strColumnName]) ? true : false;

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
	 *
	 * @package DatabaseAdapters
	 */
	class QSqlServer2005DatabaseField extends QDatabaseFieldBase {
		public function __construct($mixFieldData, $objDb = null) {
			$objDatabaseRow = null;
			try {
			    $objDatabaseRow = QType::Cast($mixFieldData, 'QSqlServer2005DatabaseRow');
			} catch (QInvalidCastException $objExc) {
			}

			if ($objDatabaseRow) {
				// Passed in field data is a row from select * from sys.columns for this table
				$intTableId = $objDatabaseRow->GetColumn('object_id');
				$this->strName = $objDatabaseRow->GetColumn('name');
				$this->strOriginalName = $this->strName;
				$this->strTable = $objDatabaseRow->GetColumn('table_name');
				$this->strOriginalTable = $this->strTable;
				$this->strDefault = null; /* Not Supported */
				$this->intMaxLength = $objDatabaseRow->GetColumn('max_length', QDatabaseFieldType::Integer);
				$this->blnNotNull = ($objDatabaseRow->GetColumn('is_nullable')) ? false : true;
				$this->blnIdentity = ($objDatabaseRow->GetColumn('is_identity')) ? true : false;
				$this->strType = $objDatabaseRow->GetColumn('data_type');
				$this->strComment = $objDatabaseRow->GetColumn('comment'); 

				$intScale = $objDatabaseRow->GetColumn('scale', QDatabaseFieldType::Integer);

				// PRIMARY KEY / UNIQUE
				$this->blnPrimaryKey = false;
				$this->blnUnique = false;
				// Determine it with the standardized information_schema views
				$objResult = $objDb->Query(sprintf("
					SELECT   kcu.column_name
					      , tc.constraint_type
					FROM     information_schema.table_constraints tc
					      , information_schema.key_column_usage kcu
					WHERE    tc.table_name = %s
						AND tc.constraint_type IN ('UNIQUE', 'PRIMARY KEY')
						AND kcu.table_name = tc.table_name
						AND kcu.table_schema = tc.table_schema
						AND kcu.constraint_name = tc.constraint_name
				", $objDb->SqlVariable($this->strTable)));
				// Search for current column and look if it's a primary key or unique
				while ($objRow = $objResult->GetNextRow()) {
					if ($objRow->GetColumn('column_name') == $this->strName) {
						// Primary Key?
						if ($objRow->GetColumn('constraint_type') == 'PRIMARY KEY') {
							$this->blnPrimaryKey = true;
							$this->blnUnique = true; // is also UNIQUE
						}
						// Unique?
						if ($objRow->GetColumn('constraint_type') == 'UNIQUE') {
							$this->blnUnique = true;
						}
					}
				}

				switch ($this->strType) {
					case 'numeric':
					case 'decimal':
						if ($intScale == 0)
							$this->strType = QDatabaseFieldType::Integer;
						else
							$this->strType = QDatabaseFieldType::Float;
						break;
					case 'bigint':
					case 'int':
					case 'tinyint':
					case 'smallint':
						$this->strType = QDatabaseFieldType::Integer;
						break;
					case 'money':
					case 'real':
					case 'float':
					case 'smallmoney':
						$this->strType = QDatabaseFieldType::Float;
						break;
					case 'bit':
						$this->strType = QDatabaseFieldType::Bit;
						break;
					case 'char':
					case 'nchar':
						$this->strType = QDatabaseFieldType::Char;
						break;
					case 'varchar':
					case 'nvarchar':
					case 'sysname':
					case 'uniqueidentifier':
						// varchar(max) and nvarchar(max) are recognized by intMaxLength = -1 and will be a blob-type
						if ($this->intMaxLength > -1) {
							$this->strType = QDatabaseFieldType::VarChar;
						}
						else {
							$this->strType = QDatabaseFieldType::Blob;
							$this->intMaxLength = null;
						}
						break;
					case 'text':
					case 'ntext':
					case 'binary':
					case 'image':
					case 'varbinary':
					case 'xml':
					case 'udt':
					case 'geometry':
					case 'geography':
					case 'hierarchyid':
					case 'sql_variant':
						$this->strType = QDatabaseFieldType::Blob;
						$this->intMaxLength = null;
						break;
					case 'datetime':
					case 'datetime2':
					case 'smalldatetime':
						$this->strType = QDatabaseFieldType::DateTime;
						break;
					case 'date':
						$this->strType = QDatabaseFieldType::Date;
						break;
					case 'time':
						$this->strType = QDatabaseFieldType::Time;
						break;
					case 'timestamp':
						// System-generated Timestamp values need to be treated as plain text
						$this->strType = QDatabaseFieldType::VarChar;
						$this->blnTimestamp = true;
						break;
					default:
						throw new QSqlServer2005DatabaseException('Unsupported DataType: ' . $this->strType, 0, null);
				}
			} else {
				// Passed in fielddata is a sqlsrv_fetch_field field result
				$this->strName = $mixFieldData->name;
				$this->strOriginalName = $mixFieldData->name;
				$this->strTable = $mixFieldData->column_source;
				$this->strOriginalTable = $mixFieldData->column_source;
				$this->intMaxLength = $mixFieldData->max_length;
			}
		}
	}
?>
