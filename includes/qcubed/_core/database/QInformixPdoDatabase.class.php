<?php
/**
 * EXPERIMENTAL Informix database driver, based on PDO. 
 * 
 * @author BG = Bernhard Gramberg [qcubed@gramberg.de]
 * @package DatabaseAdapters
 *
 * 04/18/2010 BG NonQuery new, because Query-String contans to much  "  
 * 04/18/2010 BG mostly working 
 * 04/16/2010 BG start to change  
 * 
 * Known bugs
 * 04/18/2010 BG numbers / dates not working with german setting (DMY4.), only US/english with (DBDATA / DBMONEY) 
 * 
 * programming completly based on
 * - generic PDO adapter    (Marcos Sánchez)  --> unchanged base 
 * - PostgreSql PDO adapater (Marcos Sánchez)  --> base of this file 
 * - InformixSql adapter (PHP-ifx_xxx   functions ) Josue Balbuena --> copied SQLs and some PHP-Lines 
 * 
 * the old Postgres-Code remains, but commented
 * the copies from the InformixSql is signed 
 */
 
/** BG
 * 
 * Definition of the Informix PDO-Connection 
			define('DB_CONNECTION_1', serialize(array(
				'adapter'   => 'InformixPDO',
				'host'      => 'maxdata',     // IP of the Computer (Informix naming) 
				'server'    => 'maxdata',     // Informix-Server    (Informix-naming)
				'service'   =>  9088,         // same as port       (Informix-naming)
				'protocol'  => 'onsoctcp',    // Informix Special: 
				'database'  => 'festival',
				'username'  => 'prinzBernhard',
				'password'  => 'eisenherz',
				'profiling' => false)));
				
		Additionally, the following two constants MUST be defined 
		to the special needs of the actual server 
				define(__INFORMIX_TRANSLATION__, "/home/informix/lib/esql/igo4a304.so"); 
				define(__INFORMIX_DRIVER__, "/home/informix/lib/cli/libifdmr.so");		
*/

/** BG 
 * 
 * aprox Line 105 Informix-Libs must be adapted to the actual need at the Informix-Server    
 * 
 */ 

/**
 * mainly based on: PDO_PGSQL database driver
 * @author Marcos Sánchez [marcosdsanchez at thinkclear dot com dot ar]
 */
 
/**
 * EXPERIMENTAL Informix database driver bases (via copy) on this work: 
 * 
 * Copyright (C) 2009
 * Josue Balbuena - Ajusco Technology Developers, S.C. <josue.balbuena@gmail.com>
 * This file is an update of the old IfxSqlDatabase.inc found @ qcodo.com
 *
 * Database Adapter for Informix SQL Server
 * Utilizes the Informix extension : ESQL/C is now part of the Informix Client SDK
*/ 
class QInformixPdoDatabase extends QPdoDatabase {
		const Adapter = 'Informix PDO Database Adapter';
		const PDO_INFORMXIX_DSN_IDENTIFIER = 'informix'; // BG needed ?  

		public function Connect() {
				// Lookup Adapter-Specific Connection Properties
				// $strDsn = sprintf("%s:host=%s;dbname=%s;port=%s",QInformixPdoDatabase::PDO_PGSQL_DSN_IDENTIFIER, $this->Server, $this->Database, $this->Port);

				$strDsn = $this->getInformixPdoDsn($this->Database, $this->Host, $this->Server, $this->Service, $this->Protocol); // BG 
 
				// Connect to the Database Server
				try {
						$this->objPdo = new PDO($strDsn, $this->Username, $this->Password);
				} catch (PDOException $expPgSql) {
						throw new QInformixDatabaseException(sprintf("Unable to connect to Database: %s",$expPgSql->getMessage()), -1, null);
				}
				
				// BG Informix specific PDO setting 
				$this->objPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // BG get some warnings as well
				$this->objPdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);         // BG downshift letters in fields: ABC -> abc
 
				// Update Connected Flag
				$this->blnConnectedFlag = true;
		}

		public function Close()
		{
			parent::Close();

			// Update Connected Flag
			$this->blnConnectedFlag = false;
		}

		function getInformixPdoDsn($database, $host, $server, $service, $protocol) {
				/** Informix naming convention:
				 * host     = IP-Adresse of the Server, where the Informix-Server is running
				 * server   = Internal Informix-Name of the Informix-Instance 
				 * service  = Port, where Informix is listening 
				 * protocol = like ONSOCTCP, informix specific protocols 
				 */
				
				// build PDO-dsn-String 
				$scroll = "EnableScrollableCursors=1"; // Scroll-Cursor an machen
				$dsn = "informix:host=$host;service=$service;";
				$dsn .= "database=$database;protocol=$protocol;server=$server;$scroll;;";
				$dsn .= "TRANSLATIONDLL=" . __INFORMIX_TRANSLATION__ . ";Driver=" . __INFORMIX_DRIVER__ . ";;";
				return $dsn;
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



		public function SqlLimitVariableSuffix($strLimitInfo) {
				// BG changed, in the ifx-adapter, LIMIt is via Prefix 
				// Informix uses Limit by prefix (via a LIMIT clause)
				return null;
		}

		public function SqlLimitVariablePrefix($strLimitInfo) {
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
								// 04/18/2010  BG   LIMIT OFFSET --> SKIP LIMIT  
								return sprintf('SKIP %s LIMIT %s ', $strArray[1], $strArray[0]);
						} else if (count($strArray) == 1) {
								return sprintf('LIMIT %s', $strArray[0]);
						} else {
								throw new QInformixDatabaseException('Invalid Limit Info: ' . $strLimitInfo, 0, null);
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

						return "ORDER BY $strSortByInfo";
				}

				return null;
		}

		public function GetTables() {
			// Postgres / $objResult = $this->Query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = current_schema() ORDER BY TABLE_NAME ASC");
			
			// BG copy fom  InformixSql  
			$objResult = $this->Query(
				"SELECT tabname FROM systables 
						 WHERE tabname NOT LIKE 'sys%' 
							 AND tabname NOT LIKE ' GL_%' 
							 AND tabname NOT LIKE ' VERSION'
				 ORDER BY tabname ASC"
			);
				
			$strToReturn = array();
			while ($strRowArray = $objResult->FetchRow())
					array_push($strToReturn, $strRowArray[0]);
			return $strToReturn;
		}
		
		// BG New, modified Copy of InformixSql 
		public function GetTableForId($intTableId) {
			$intTableId = $this->SqlVariable($intTableId);
			$strQuery = sprintf('
					SELECT tabname
					FROM systables
					WHERE tabid = %s
				', $intTableId);
			
			$objResult = $this->Query($strQuery);
			// $objRow = $objResult->FetchRow(); /ifx 
			$objRow = $objResult->GetNextRow();  // BG changed 
					// print ("$strQuery <br> ") ;  // BG Testing 
					// print_r( $objRow) ;          // BG Testing 
			return $objRow->GetColumn('tabname');  // BG Changed to PDO access 
		}
	
		public function GetFieldsForTable($strTableName) {
				$strTableName = $this->SqlVariable($strTableName);
				/** Postgres 
				 *         $strQuery = sprintf('
				 * 				SELECT
				 * 					table_name,
				 * 					column_name,
				 * 					ordinal_position,
				 * 					column_default,
				 * 					is_nullable,
				 * 					data_type,
				 * 					character_maximum_length,
				 * 					(pg_get_serial_sequence(table_name,column_name) IS NOT NULL) AS is_serial
				 * 				FROM
				 * 					INFORMATION_SCHEMA.COLUMNS
				 * 				WHERE
				 * 					table_schema = current_schema()
				 * 				AND
				 * 					table_name = %s
				 * 				ORDER BY ordinal_position
				 * 			', $strTableName);
				 */
				
				// BG copy from InformixSql 
				$strQuery = sprintf('
				SELECT syscolumns.*
					FROM syscolumns,	systables
				 WHERE systables.tabname = %s	
									 AND systables.tabid = syscolumns.tabid
				ORDER BY colno ASC ', 
				 $strTableName);
				$objResult = $this->Query($strQuery);

				$objFields = array();

				while ($objRow = $objResult->GetNextRow()) {
						array_push($objFields, new QInformixPdoDatabaseField($objRow, $this));
				}

				return $objFields;
		}

		public function InsertId($strTableName = null, $strColumnName = null) {
				/** Postgres 
				 *         $strQuery = sprintf('
				 * 				SELECT currval(pg_get_serial_sequence(%s, %s))
				 * 			', $this->SqlVariable($strTableName), $this->SqlVariable($strColumnName));
				
				 *         $objResult = $this->Query($strQuery);
				 *         $objRow = $objResult->FetchRow();
				 *         return $objRow[0];
				 */
				 return $this->lastId; // BG ???? Correct ? 
		}


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
				$strKeyDefinition = str_replace(" ","",$strKeyDefinition);

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

				/**
				 *         $objResult = $this->Query(sprintf('
				 * 				SELECT
				 * 					c2.relname AS indname,
				 * 					i.indisprimary,
				 * 					i.indisunique,
				 * 					pg_catalog.pg_get_indexdef(i.indexrelid) AS inddef
				 * 				FROM
				 * 					pg_catalog.pg_class c,
				 * 					pg_catalog.pg_class c2,
				 * 					pg_catalog.pg_index i
				 * 				WHERE
				 * 					c.relname = %s
				 * 				AND
				 * 					pg_catalog.pg_table_is_visible(c.oid)
				 * 				AND
				 * 					c.oid = i.indrelid
				 * 				AND
				 * 					i.indexrelid = c2.oid
				 * 				ORDER BY
				 * 					c2.relname
				 * 			', $this->SqlVariable($strTableName)));
				 */
				 // BG yopy of InformixSql 
				// Query sysindexes & sysconstraints to get a list of indexes by TableId
		$strQuery = sprintf(
									'SELECT a.idxname, a.idxtype, a.tabid, b.constrtype, b.constrid 
										 FROM sysindexes a, sysconstraints b, systables c 
										WHERE a.idxname = b.idxname 
											AND a.tabid = b.tabid 
											AND a.tabid = c.tabid 
											AND c.tabname = %s', $this->SqlVariable($strTableName));
		$objResult = $this->Query($strQuery); 

				while ($objRow = $objResult->GetNextRow()) {    
						/** POstgres 
					 *             $strIndexDefinition = $objRow->GetColumn('inddef');
					 *             $strKeyName = $objRow->GetColumn('indname');
					 *             $blnPrimaryKey = $objRow->GetColumn('indisprimary');
					 *             $blnUnique = $objRow->GetColumn('indisunique');
					 *             $strColumnNameArray = $this->ParseColumnNameArrayFromKeyDefinition($strIndexDefinition);
					 */      
						// BG copy of informixSql 
						$strKeyName = $objRow->GetColumn('idxname');
			$strIndexType = $objRow->GetColumn('idxtype');
			$intTableId = $objRow->GetColumn('tabid');
			$strConstraintType = $objRow->GetColumn('constrtype');
			$intConstraintId = $objRow->GetColumn('constrid');
						$blnPrimaryKey = (strpos($strConstraintType, 'P') !== false);
			$blnUnique = (strpos($strIndexType, 'U') !== false);
			//$strColumnNameArray = explode(', ', $objRow->GetColumn('index_keys'));
			$strColumnNameArray = $this->GetColumnsForConstraint($intConstraintId, $intTableId);
						
						// BG postgres + Informix 
						$objIndex = new QDatabaseIndex($strKeyName, $blnPrimaryKey, $blnUnique, $strColumnNameArray);
						array_push($objIndexArray, $objIndex);
				}

				return $objIndexArray;
		}
		
		// BG New: modified Copy of InformixSql 
		public function GetColumnsForConstraint($intConstraintId, $intTableId){
			$strColumnNameArray = array();
			$strQuery = sprintf("
							SELECT a.constrid, b.* 
								FROM sysconstraints a, sysindexes b 
							 WHERE a.idxname = b.idxname 
								 AND a.tabid = b.tabid 
								 AND a.constrid = %d 
								 AND a.tabid = %d", 
							$intConstraintId, $intTableId);
			$objResult = $this->Query($strQuery);
			$objRow = $objResult->GetNextRow($objResult);  // BG instead of FetchRow 
			//each column in the index is stored in a separate column Part1 thru Part16
			for($i = 1; $i <= 16; $i++){
				$intIndexColumnId = $objRow->GetColumn("part$i");
				if ($intIndexColumnId > 0) {
					$objIndexColumn = $this->Query(sprintf(
													'SELECT colname 
														 FROM syscolumns 
														 WHERE colno = %d 
															 AND tabid = %d', 
													$intIndexColumnId, $intTableId));
					while ($strRowArray = $objIndexColumn->GetNextRow() )   // BG        FetchRow())
						array_push($strColumnNameArray, $strRowArray->GetColumn('colname'));
				}
			}
			return $strColumnNameArray;		
		}
		
		public function GetForeignKeysForTable($strTableName) {
				$objForeignKeyArray = array();

				/** Postgres 
						 *         // Use Query to pull the FKs
						 *         $strQuery = sprintf('
						 * 				SELECT pc.conname,	pg_catalog.pg_get_constraintdef(pc.oid, true) AS consrc
						 * 				FROM	pg_catalog.pg_constraint pc
						 * 				WHERE	pc.conrelid =
						 * 					(SELECT	oid FROM pg_catalog.pg_class
						 * 						WHERE relname=%s
						 * 						AND relnamespace = (
						 * 								SELECT oid FROM	pg_catalog.pg_namespace
						 * 								WHERE nspname=current_schema()
						 * 							)
						 * 					)
						 * 				AND	pc.contype = \'f\'
						 * 			', $this->SqlVariable($strTableName));
						 */            
				// BG copy of InformixSql    
				// Use Query to pull the FKs
				$strQuery = sprintf(
						"SELECT a.constrid, a.constrname, a.tabid, b.primary pconstrid, b.ptabid, pk_tables.tabname pk_table, fk_tables.tabname fk_table
						FROM sysconstraints a, sysreferences b, systables pk_tables, systables fk_tables
						WHERE a.constrid = b.constrid 
								AND b.ptabid = pk_tables.tabid 
								AND a.tabid = fk_tables.tabid 
								AND fk_tables.tabname = %s", 
								$this->SqlVariable($strTableName));

				$objResult = $this->Query($strQuery);


				/**
				 *         while ($objRow = $objResult->GetNextRow()) {
				 *             $strKeyName = $objRow->GetColumn('conname');
				
				 *             // Remove leading and trailing '"' characters (if applicable)
				 *             if (substr($strKeyName, 0, 1) == '"')
				 *                 $strKeyName = substr($strKeyName, 1, strlen($strKeyName) - 2);
				
				 *             // By the end of the following lines, we will end up with a strTokenArray
				 *             // Index 1: the list of columns that are the foreign key
				 *             // Index 2: the table which this FK references
				 *             // Index 3: the list of columns which this FK references
				 *             $strTokenArray = split('FOREIGN KEY ', $objRow->GetColumn('consrc'));
				 *             $strTokenArray[1] = split(' REFERENCES ', $strTokenArray[1]);
				 *             $strTokenArray[2] = $strTokenArray[1][1];
				 *             $strTokenArray[1] = $strTokenArray[1][0];
				 *             $strTokenArray[2] = explode("(", $strTokenArray[2]);
				 *             $strTokenArray[3] = "(".$strTokenArray[2][1];
				 *             $strTokenArray[2] = $strTokenArray[2][0];
				
				 *             // Remove leading and trailing '"' characters (if applicable)
				 *             if (substr($strTokenArray[2], 0, 1) == '"')
				 *                 $strTokenArray[2] = substr($strTokenArray[2], 1, strlen($strTokenArray[2]) - 2);
				
				 *             $strColumnNameArray = $this->ParseColumnNameArrayFromKeyDefinition($strTokenArray[1]);
				 *             $strReferenceTableName = $strTokenArray[2];
				 *             $strReferenceColumnNameArray = $this->ParseColumnNameArrayFromKeyDefinition($strTokenArray[3]);
				
				 *             $objForeignKey = new QDatabaseForeignKey(
				 *                     $strKeyName,
				 *                     $strColumnNameArray,
				 *                     $strReferenceTableName,
				 *                     $strReferenceColumnNameArray);
				 *             array_push($objForeignKeyArray, $objForeignKey);
				 *         }
				 */
				 // Bg copy of InformixSql 
				$strKeyName = '';
				while ($objRow = $objResult->GetNextRow()) {
						if ($strKeyName != $objRow->GetColumn('constrname')) {
								if ($strKeyName) {
									$objForeignKey = new QDatabaseForeignKey(
									$strKeyName,
									$strColumnNameArray,
									$strReferenceTableName,
									$strReferenceColumnNameArray);
									array_push($objForeignKeyArray, $objForeignKey);
								}
				
								$strKeyName = $objRow->GetColumn('constrname');
								$intConstraintId = $objRow->GetColumn('constrid');
								$intTableId = $objRow->GetColumn('tabid');
								$strReferenceTableName = $objRow->GetColumn('pk_table');
								$intReferenceConstraintId = $objRow->GetColumn('pconstrid');
								$intReferenceTableId = $objRow->GetColumn('ptabid');
								$strColumnNameArray = array();
								$strReferenceColumnNameArray = array();
						}
					
					$strColumnNameArray = $this->GetColumnsForConstraint($intConstraintId, $intTableId); 
					$strReferenceColumnNameArray = $this->GetColumnsForConstraint($intReferenceConstraintId, $intReferenceTableId);
				}

				if ($strKeyName) {
					$objForeignKey = new QDatabaseForeignKey(
					$strKeyName,
					$strColumnNameArray,
					$strReferenceTableName,
					$strReferenceColumnNameArray);
					array_push($objForeignKeyArray, $objForeignKey);
				}

				// --- BG End of Copy 
				 
				// Return the Array of Foreign Keys
				return $objForeignKeyArray;
		}



		protected function ExecuteQuery($strQuery) {
				$strQuery = $this->QueryStringToInformixSyntax ($strQuery);  

				// echo "$strQuery <br>" ;
				// $objResult = $this->objPdo->query($strQuery);
				
				try {
						$objResult = $this->objPdo->query($strQuery);
				} catch (PDOException $expPgSql) {
						# throw new QInformixDatabaseException(sprintf("Unable to connect to Database: %s",$expPgSql->getMessage()), -1, null);
							echo "Catched ERROR: $strQuery <br>" ;
							throw new QPdoDatabaseException($this->objPdo->errorInfo(), $this->objPdo->errorCode(), $strQuery);
				}
	
/*
        if ($objResult === false)
        {   
            echo "$strQuery <br>" ;
            throw new QPdoDatabaseException($this->objPdo->errorInfo(), $this->objPdo->errorCode(), $strQuery);
         }

*/
				// Return the Result
				$this->objMostRecentResult = $objResult;
				$objPdoStatementDatabaseResult = new QInformixPdoDatabaseResult($objResult, $this);
				return $objPdoStatementDatabaseResult;
		}


		// BG Copied from generic PDO-adapter, because SQL-String not comaptible with Informix (to much  " )  
		protected function ExecuteNonQuery($strNonQuery) {
				$strNonQuery = $this->QueryStringToInformixSyntax ($strNonQuery);  

				// Perform the Query
				$objResult = $this->objPdo->query($strNonQuery);
				if ($objResult === false)
						throw new QPdoDatabaseException($this->objPdo->errorInfo(), $this->objPdo->errorCode(), $strNonQuery);
				$this->objMostRecentResult = $objResult;
		}
		
		function QueryStringToInformixSyntax ( $strQuery ) {
				$strQuery = str_replace('"','',$strQuery); //
				//remove backslash from escaped characters like \" and \\
				$strQuery = stripslashes($strQuery);
				
				// Informix does not accept the word AS to alias a table
				$strQuery = str_replace(' AS ',' ',$strQuery);

				return $strQuery ;  
		}
		
		
} // end of class 



/**
 * QInformixPdoDatabaseResult
 */
class QInformixPdoDatabaseResult extends QPdoDatabaseResult {

		public function GetNextRow() {
				$strColumnArray = $this->FetchArray();

				if ($strColumnArray)
						return new QInformixPdoDatabaseRow($strColumnArray);
				else
						return null;
		}

		public function FetchFields() {
				$objArrayToReturn = array();
				while ($objField = $this->FetchColumn()) {
						array_push($objArrayToReturn, new QInformixPdoDatabaseField($objField, $this->objDb));
				}
				return $objArrayToReturn;
		}

		public function FetchField() {
				if ($objField = $this->FetchColumn())
						return new QInformixPdoDatabaseField($objField, $this->objDb);
		}

}
/**
 * QInformixPdoDatabaseRow
 */
class QInformixPdoDatabaseRow extends QDatabaseRowBase {
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
 * QInformixPdoDatabaseField
 *
 *
 * BG complete copy from InformixSql 
 */

class QInformixPdoDatabaseField extends QDatabaseFieldBase {
	public function __construct($mixFieldData, $objDb = null) {
		$objDatabaseRow = null;
				
				$objDatabaseRow = $mixFieldData ; // BG New 
				
/**
 * 		try {
 * 			$objDatabaseRow = QType::Cast($mixFieldData, 'QInformixSqlDatabaseRow');
 * 		} catch (InvalidCastException $objExc) {
 * 		}
 */

		if ($objDatabaseRow) {
			// Passed in field data is a row from select * from syscolumns for this table
			$intTableId = $objDatabaseRow->GetColumn('tabid');
			$this->strName = $objDatabaseRow->GetColumn('colname');
			$this->strOriginalName = $this->strName;
			$this->strTable = $objDb->GetTableForId($intTableId);
			$this->strOriginalTable = $this->strTable;
			$this->strDefault = null; /* Not Supported */
			// If the coltype contains a value greater than 256, it does not allow null values.
			$this->blnNotNull = ($objDatabaseRow->GetColumn('coltype')>=256) ? true : false;

			// Determine Primary Key
			$objResult = $objDb->Query(sprintf("
				SELECT a.constrid, a.constrname,  c.colname
				FROM sysconstraints a, sysindexes b, syscolumns c
				WHERE (a.idxname = b.idxname and b.part1 = c.colno and a.tabid = b.tabid and b.tabid = c.tabid)
				AND a.tabid = %d and a.constrtype = 'P'", $intTableId));
			while ($objRow = $objResult->GetNextRow()) {
				if ($objRow->GetColumn('colname') == $this->strName)
				$this->blnPrimaryKey = true;
			}
			if (!$this->blnPrimaryKey)
			$this->blnPrimaryKey = false;

			// UNIQUE
			$objResult = $objDb->Query(sprintf("
				SELECT	a.idxname, a.idxtype, b.colname
				FROM sysindexes a, syscolumns b
				WHERE a.tabid = b.tabid and a.part1 = b.colno and a.part2 = 0 and a.tabid = %d", $intTableId));
			while ($objRow = $objResult->GetNextRow()) {
				if ($objRow->GetColumn('colname') == $this->strName && $objRow->GetColumn('idxtype') == 'U')
				$this->blnUnique = true;
			}
			if (!$this->blnUnique)
			$this->blnUnique = false;


			/* Figure out Type, Maxlength and Identity by using syscolumns */

			$this->blnIdentity = false; // A serial is an identity column

			/* For each column in the tablea corresponding 'coltype' value will be given that is a numeric
			representation of the column type.

			0 = CHAR                8 = MONEY
			1 = SMALLINT           10 = DATETIME
			2 = INTEGER            11 = BYTE
			3 = FLOAT              12 = TEXT
			4 = SMALLFLOAT         13 = VARCHAR
			5 = DECIMAL            14 = INTERVAL
			6 = SERIAL             15 = NCHAR
			7 = DATE               16 = NVARCHAR

			*/

			$intColType =$objDatabaseRow->GetColumn('coltype', QDatabaseFieldType::Integer );
			$this->intMaxLength = $objDatabaseRow->GetColumn('collength', QDatabaseFieldType::Integer);
			switch (($intColType < 256) ? $intColType : $intColType - 256  ) {
				case 0: //char
					$this->strType = QDatabaseFieldType::Char;
					break;
				case 1: //smallint
					$this->strType = QDatabaseFieldType::Integer;
					break;
				case 2: //integer
					$this->strType = QDatabaseFieldType::Integer;
					break;
				case 3: //float
					$this->strType = QDatabaseFieldType::Float;
					break;
				case 4: //smallfloat
					$this->strType = QDatabaseFieldType::Float;
					break;
				case 5: //decimal
					$this->intMaxLength = ((($objDatabaseRow->GetColumn('collength', QDatabaseFieldType::Integer))>>8) & 0xff);
				case 6: //serial
					$this->strType = QDatabaseFieldType::Integer;
					$this->blnIdentity = true; //if a serial is found: this is the identity column
					break;
				case 7: //date
					$this->strType = QDatabaseFieldType::Date;
					break;
				case 8: //money
					$this->strType = QDatabaseFieldType::Float;
					$this->intMaxLength = ((($objDatabaseRow->GetColumn('collength', QDatabaseFieldType::Integer))>>8) & 0xff);
					break;
				case 'bit':
					$this->strType = QDatabaseFieldType::Bit;
					break;
				case 10: //datetime
					$this->strType = QDatabaseFieldType::DateTime;
					break;
				case 11: //byte
					$this->strType = QDatabaseFieldType::Integer;
					break;
				case 12: //text
					$this->strType = QDatabaseFieldType::Blob;
					break;
				case 13: //varchar
					$this->strType = QDatabaseFieldType::VarChar;
					$this->intMaxLength = (($objDatabaseRow->GetColumn('collength', QDatabaseFieldType::Integer)) & 0x00ff);
										// BG added Q  -> QDatabsseFieldType 
					break;
				case 14: //interval
					$this->strType = QDatabaseFieldType::Integer;
					break;
				case 15: //nchar
					$this->strType = QDatabaseFieldType::VarChar;
					break;
				case 16: //nvarchar
					$this->intMaxLength = (($objDatabaseRow->GetColumn('collength', QDatabaseFieldType::Integer)) & 0x00ff);
					$this->strType = QDatabaseFieldType::VarChar;
					break;
				default:
					throw new QInformixSqlDatabaseException('Unsupported Field Type: ' . $intColType, 0, null);
			}
		} else {
			// Passed in fielddata is a ifx_fetch_field field result
			$this->strName = $mixFieldData->name;
			$this->strOriginalName = $mixFieldData->name;
			$this->strTable = $mixFieldData->column_source;
			$this->strOriginalTable = $mixFieldData->column_source;
			$this->intMaxLength = $mixFieldData->max_length;
		}
	}
}


/** Postgres 
 * class QInformixPdoDatabaseField extends QDatabaseFieldBase {
 *     public function __construct($mixFieldData, $objDb = null) {
 *         $this->strName = $mixFieldData->GetColumn('column_name');
 *         $this->strOriginalName = $this->strName;
 *         $this->strTable = $mixFieldData->GetColumn('table_name');
 *         $this->strOriginalTable = $this->strTable;
 *         $this->strDefault = $mixFieldData->GetColumn('column_default');
 *         $this->intMaxLength = $mixFieldData->GetColumn('character_maximum_length', QDatabaseFieldType::Integer);
 *         $this->blnNotNull = ($mixFieldData->GetColumn('is_nullable') == "NO") ? true : false;

 *         // If the first column of the table was created as SERIAL, we assume it's the identity field.
 *         // Otherwise, no identity field will be set for this table.
 *         $ordinalPos = $mixFieldData->GetColumn('ordinal_position', QDatabaseFieldType::Integer);
 *         $isSerial = $mixFieldData->GetColumn('is_serial');
 *         $this->blnIdentity = ($ordinalPos == 1 && $isSerial) ? true : false;

 *         // Determine Primary Key
 *         $objResult = $objDb->Query(sprintf('
 * 				SELECT
 * 					kcu.column_name
 * 				FROM
 * 					information_schema.table_constraints tc,
 * 					information_schema.key_column_usage kcu
 * 				WHERE
 * 					tc.table_name = %s
 * 				AND
 * 					tc.table_schema = current_schema()
 * 				AND
 * 					tc.constraint_type = \'PRIMARY KEY\'
 * 				AND
 * 					kcu.table_name = tc.table_name
 * 				AND
 * 					kcu.table_schema = tc.table_schema
 * 				AND
 * 					kcu.constraint_name = tc.constraint_name
 * 			', $objDb->SqlVariable($this->strTable)));

 *         while ($objRow = $objResult->GetNextRow()) {
 *             if ($objRow->GetColumn('column_name') == $this->strName)
 *                 $this->blnPrimaryKey = true;
 *         }

 *         if (!$this->blnPrimaryKey)
 *             $this->blnPrimaryKey = false;

 *         // UNIQUE
 *         $objResult = $objDb->Query(sprintf('
 * 				SELECT
 * 					kcu.column_name, (SELECT COUNT(*) FROM information_schema.key_column_usage kcu2 WHERE kcu2.constraint_name=kcu.constraint_name ) as unique_fields
 * 				FROM
 * 					information_schema.table_constraints tc,
 * 					information_schema.key_column_usage kcu
 * 				WHERE
 * 					tc.table_name = %s
 * 				AND
 * 					tc.table_schema = current_schema()
 * 				AND
 * 					tc.constraint_type = \'UNIQUE\'
 * 				AND
 * 					kcu.table_name = tc.table_name
 * 				AND
 * 					kcu.table_schema = tc.table_schema
 * 				AND
 * 					kcu.constraint_name = tc.constraint_name
 * 				GROUP BY
 * 					kcu.constraint_name, kcu.column_name
 * 			', $objDb->SqlVariable($this->strTable)));
 *         while ($objRow = $objResult->GetNextRow()) {
 *             if ($objRow->GetColumn('column_name') == $this->strName && $objRow->GetColumn('unique_fields') == 1)
 *                 $this->blnUnique = true;
 *         }
 *         if (!$this->blnUnique)
 *             $this->blnUnique = false;

 *         // Determine Type
 *         $this->strType = $mixFieldData->GetColumn('data_type');

 *         switch ($this->strType) {
 *             case 'integer':
 *             case 'smallint':
 *                 $this->strType = QDatabaseFieldType::Integer;
 *                 break;
 *             case 'money':
 *             // NOTE: The money type is deprecated in Informix.
 *                 throw new QInformixDatabaseException('Unsupported Field Type: money.  Use numeric or decimal instead.', 0,null);
 *                 break;
 *             case 'bigint':
 *             case 'decimal':
 *             case 'numeric':
 *             case 'real':
 *             // "BIGINT" must be specified here as a float so that PHP can support it's size
 *             // http://www.Informix.org/docs/8.2/static/datatype-numeric.html
 *                 $this->strType = QDatabaseFieldType::Float;
 *                 break;
 *             case 'bit':
 *                 if ($this->intMaxLength == 1)
 *                     $this->strType = QDatabaseFieldType::Bit;
 *                 else
 *                     throw new QInformixDatabaseException('Unsupported Field Type: bit with MaxLength > 1', 0, null);
 *                 break;
 *             case 'boolean':
 *                 $this->strType = QDatabaseFieldType::Bit;
 *                 break;
 *             case 'character':
 *                 $this->strType = QDatabaseFieldType::Char;
 *                 break;
 *             case 'character varying':
 *             case 'double precision':
 *             // NOTE: PHP does not offer full support of double-precision floats.
 *             // Value will be set as a VarChar which will guarantee that the precision will be maintained.
 *             //    However, you will not be able to support full typing control (e.g. you would
 *             //    not be able to use a QFloatTextBox -- only a regular QTextBox)
 *                 $this->strType = QDatabaseFieldType::VarChar;
 *                 break;
 *             case 'text':
 *                 $this->strType = QDatabaseFieldType::Blob;
 *                 break;
 *             case 'timestamp':
 *             case 'timestamp without time zone':
 *             // System-generated Timestamp values need to be treated as plain text
 *                 $this->strType = QDatabaseFieldType::VarChar;
 *                 $this->blnTimestamp = true;
 *                 break;
 *             case 'date':
 *                 $this->strType = QDatabaseFieldType::Date;
 *                 break;
 *             case 'time':
 *             case 'time without time zone':
 *                 $this->strType = QDatabaseFieldType::Time;
 *                 break;
 *             default:
 *                 throw new QInformixDatabaseException('Unsupported Field Type: ' . $this->strType, 0, null);
 *         }
 *     }
 * }
 */
/**
 * QInformixPdoDatabaseException
 */
class QInformixPdoDatabaseException extends QPdoDatabaseException {

}


?>
