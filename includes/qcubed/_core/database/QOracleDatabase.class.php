<?php
/**
 * EXPERIMENTAL Oracle database adapter.
 *
 * Tested on: Qcodo Development Framework 0.3.32 (Qcodo Beta 3)
 *
 * @package DatabaseAdapters
 * @author Riccardo Tacconi, Ago Luberg, enzo - Eduardo Garcia
 
*/

class QOracleDatabase extends QDatabaseBase {
	const Adapter = 'Oracle Database Adapter';
	protected $objOracle;

	protected $EscapeIdentifierBegin = '';
	protected $EscapeIdentifierEnd = '';
	protected $debug;
	protected $commitMode;

	public function SqlLimitVariablePrefix($strLimitInfo) {
		// MySQL uses Limit by Suffixes (via a LIMIT clause)
		// Prefix is not used, therefore, return null
		return null;
	}

	public function SqlLimitVariableSuffix($strLimitInfo) {
		// Setup limit suffix (if applicable) via a LIMIT clause
		// the parameter receives 10 or 60,10 from instance
		// then from Mysql style must be converted to the Oracle way
		if (strlen($strLimitInfo)) {
			if (strpos($strLimitInfo, ';') !== false)
			throw new Exception('Invalid Semicolon in LIMIT Info');
			if (strpos($strLimitInfo, '`') !== false)
			throw new Exception('Invalid Backtick in LIMIT Info');

			//if there is a ',' we change it to ORACLE ROWNUM

			if(strpos($strLimitInfo,',') !== false){
				// we have to add a lot of code to make a sql statement with
				//pagination FROM x to y
				// Oracle doesnt support LIMT, or OFFSET or TOP

				$array_limit = explode(',',$strLimitInfo);
				//array_limit[0] is the MIN row to fetch
				//max_row is MAX row to fetch

				$max_row = $array_limit[0] + $array_limit[1];

				return "_LIMIT2_) a where ROWNUM <= $max_row ) where rnum  > $array_limit[0]";
			}
			else{
				//The string LIMIT will be parsed in Query Method
				return "_LIMIT1_) where rownum <= $strLimitInfo";
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

	public function Connect() {
		// Lookup Adapter-Specific Connection Properties
		$strServer = $this->Server;
		$strName = $this->Database;
		$strUsername = $this->Username;
		$strPassword = $this->Password;
		$strPort = $this->Port;
		//echo "DB vars: ".$this->__get('Username');

		// Connect to the Database Server
		$this->objOracle = ociplogon($strUsername, $strPassword, "$strServer/$strName",'UTF8');

		if (!$this->objOracle)
			throw new QOracleDatabaseException("Unable to connect to Database", -1, null);

		if ($objOracle_error=oci_error())
			throw new QOracleDatabaseException($objOracle_error['message'],$objOracle_error['code'] , null);

		// Set to AutoCommit
		//$this->NonQuery('SET AUTOCOMMIT=1;');
		//Set SORT with special characteres
		//$this->NonQuery("ALTER SESSION SET NLS_SORT=SPANISH");
		// Update "Connected" Flag
		$this->blnConnectedFlag = true;
		
		$this->NonQuery("ALTER SESSION SET NLS_DATE_FORMAT='YYYY-MM-DD hh24:mi:ss'");
	    //$this->NonQuery("ALTER SESSION SET NLS_LANGUAGE='AMERICAN'");
	    // to use . instead of , for floating point
	    //$this->NonQuery("ALTER SESSION SET NLS_NUMERIC_CHARACTERS='.,'");
	}

	public function __get($strName) {
		switch ($strName) {
			case 'AffectedRows':
				return $this->objOracle->affected_rows;
			case 'EscapeIdentifierBegin':
				return $this->EscapeIdentifierBegin;
			case 'EscapeIdentifierEnd':
				return $this->EscapeIdentifierEnd;
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
   * Prepares Oracle statement for execution
   *
   * @param string $strQuery
   * @return resource
   */
  public function Parse ($strQuery) {
    // Connect if Applicable
    if (!$this->blnConnectedFlag) $this->Connect();
    return oci_parse($this->objOracle,$strQuery);
  }


  /**
   * Executes prepared statement and returns usable object
   *
   * @param resource $objResult
   * @return QOracleDatabaseResult
   */
  protected function Execute ($objResult) {
    $blnReturn = false;
    if($objResult)
    {
      if($this->commitMode) {
        $blnReturn = oci_execute($objResult,OCI_DEFAULT);
      }
      else {
          $blnReturn = @oci_execute($objResult);
          if (!$blnReturn) {
            $objOracle_error=oci_error($objResult);
          }
      }
    }

    if ($objOracle_error=oci_error())
    {
      throw new QOracleDatabaseException($objOracle_error['message'],$objOracle_error['code'] , null);
    }

    return $blnReturn;
  }


	protected function ExecuteQuery($strQuery) {
		
		// Connect if Applicable
		if (!$this->blnConnectedFlag) $this->Connect();

		//looks for escaped characters like \' and replace them for two single
		//quotes. this is needed because, Oracle, diferently from other SGDB
		// do not understand \ do parse quotes and double quotes

		$strQuery = str_replace("\'","''",$strQuery);

		//remove backslash from escaped characters like \" and \\
		//Oracle has a problem only with single quotes escaped
		$strQuery = stripslashes($strQuery);

		//oracle does not accept the word AS to alias a table
		$strQuery = str_replace(' AS ',' ',$strQuery);

		//oracle does not accept formated SQL statement,
		// so strip off /n/t/r
		$strQuery=trim($strQuery);

		// called for displaying the pages except first page... see the else clause
		if(!strpos($strQuery,'_LIMIT2_')===false){
			//check if SqlLimitVariableSuffix was called
			// and if it as a "composed limit" ( from x to y)
			//if so, add the rest of the query, and it will look like this
			// http://www.oracle.com/technology/oramag/oracle/06-sep/o56asktom.html

			$strQuery ="SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, ROWNUM 
			 				rnum from (". str_replace('_LIMIT2_','',$strQuery);

		}
		// called for displaying the first page
		else if(!strpos($strQuery,'_LIMIT1_')===false){
			//check if SqlLimitVariableSuffix was called
			// and if it as a "simple limit" (TOP N)
			//if so, add the rest of the query, and it will look like this
			// http://www.oracle.com/technology/oramag/oracle/06-sep/o56asktom.html

			$strQuery ="SELECT * FROM(". str_replace('_LIMIT1_','',$strQuery);
		}
			
		// Perform the Query
		//print $strQuery; die();
		$objResult = oci_parse($this->objOracle,$strQuery);

		if($objResult)
		{
			if($this->commitMode) {
				oci_execute($objResult,OCI_DEFAULT);
			}
			else {
				//die($strQuery);
				oci_execute($objResult);
			}
		}

		if ($objOracle_error=oci_error())
		{
			throw new QOracleDatabaseException($objOracle_error['message'],$objOracle_error['code'] , null);
		}

		// Return the Result

		$objOracleDatabaseResult = new QOracleDatabaseResult($objResult, $this);
		return $objOracleDatabaseResult;
	}



	protected function ExecuteNonQuery($strNonQuery) {

		// Connect if Applicable
		if (!$this->blnConnectedFlag) $this->Connect();
		
		//looks for escaped characters like \' and replace them for two single
		//quotes. this is needed because, Oracle, diferently from other SGDB
		// do not understand \ do parse quotes and double quotes

		$strNonQuery = str_replace("\'","''",$strNonQuery);

		//remove backslash from escaped characters like \" and \\
		//Oracle has a problem only with single quotes escaped
		$strNonQuery = stripslashes($strNonQuery);

		// Perform the Query
		$objResult = oci_parse($this->objOracle,$strNonQuery);

		if($objResult)
		{
			if($this->commitMode) {
				oci_execute($objResult,OCI_DEFAULT);
			}
			else {
				oci_execute($objResult);
			}
		}

		if ($objOracle_error=oci_error())
		{
			throw new QOracleDatabaseException($objOracle_error['message'],$objOracle_error['code'] , null);
		}
	}

	public function GetTables() {
		$objResult = $this->Query("select table_name from tabs order by table_name");

		$strToReturn = array();
		while ($strRowArray = $objResult->FetchRow())
		{
			array_push($strToReturn, $strRowArray[0]);
		}
		return $strToReturn;
	}

	public function GetFieldsForTable($strTableName) {
		$objResult = $this->Query(sprintf("select * from user_tab_columns where table_name = '%s'",strtoupper($strTableName)));

		$objFields = array();
		while ($objRow = $objResult->FetchRow())
		{
			array_push($objFields, new QOracleDatabaseField($objRow, $this));
		}
		return $objFields;
	}

	//it returns the last inserted row for the current session
	public function InsertId($strTableName = null, $strColumnName = null) {
		$seqName = substr($strTableName,0, 25) . "_SEQ";
		$objResult = $this->Query("select "."$seqName".".currval from dual");
		$strDbRow = $objResult->FetchRow();
		return QType::Cast($strDbRow[0], QType::Integer);		
	}

	public function Close() {
		oci_close($this->objOracle);

		// Update Connected Flag
		$this->blnConnectedFlag = false;
	}

	public function TransactionBegin() {
		// Set to AutoCommit
		//$this->NonQuery('SET AUTOCOMMIT OFF;');
		$this->commitMode = true;
	}

	public function TransactionCommit() {
		/*$this->NonQuery('COMMIT;');
		// Set to AutoCommit
		$this->NonQuery('SET AUTOCOMMIT OFF;');*/
		oci_commit($this->objOracle);
	}

	public function TransactionRollback() {
		/*$this->NonQuery('ROLLBACK;');
		// Set to AutoCommit
		$this->NonQuery('SET AUTOCOMMIT OFF;');*/
		oci_rollback($this->objOracle);
	}

	public function GetIndexesForTable($strTableName) {
		$objIndexArray = array();
		$objResult = $this->Query(sprintf("select ui.index_name,ui.index_type,ui.uniqueness,uc.constraint_type FROM user_indexes ui left join user_constraints uc on (ui.index_name=uc.index_name) WHERE ui.table_name= '%s'",strtoupper($strTableName)));
		while ($objRow = $objResult->FetchRow())
		{
			$ColumnNameArray = array();
			$objResult2 = $this->Query(sprintf("select * from user_ind_columns where index_name='%s' order by  column_position",$objRow[0]));
			while ($objRow2 = $objResult2->FetchRow())
			{
				array_push($ColumnNameArray, $objRow2[2]);
			}
			$blnUnique = strcmp($objRow[2],"UNIQUE")?false:true;
			$blnPrimaryKey = strcmp($objRow[3],"P")?false:true;
			$objIndex = new QDatabaseIndex($objRow[0], $blnPrimaryKey, $blnUnique, $ColumnNameArray);
			array_push($objIndexArray, $objIndex);
		}
		//SHOW The Obj Index
		// echo "<pre>";
		// print_r($objIndexArray);
		// echo "</pre>";
		//*/
			
		return $objIndexArray;
	}

	public function GetForeignKeysForTable($strTableName) {

		$objForeignKeysArray = array();
		$objResult = $this->Query(sprintf("select uc1.constraint_name,uc2.table_name,uc2.constraint_name from user_constraints uc1,user_constraints uc2 where uc1.r_constraint_name=uc2.constraint_name and uc1.constraint_type='R' and uc1.table_name='%s'",strtoupper($strTableName)));
		while ($objRow = $objResult->FetchRow())
		{
			$ColumnNameArray = array();
			$objResult2 = $this->Query(sprintf("select * from user_cons_columns where constraint_name = '%s' order by  position",$objRow[0]));
			while ($objRow2 = $objResult2->FetchRow())
			{
				array_push($ColumnNameArray, $objRow2[3]);
			}

			$ColumnNameArray2 = array();
			$objResult3 = $this->Query(sprintf("select * from user_cons_columns where constraint_name = '%s' order by  position",strtoupper($objRow[2])));
			while ($objRow3 = $objResult3->FetchRow())
			{
				array_push($ColumnNameArray2, $objRow3[3]);
			}

			$objIndex = new QDatabaseForeignKey($objRow[0], $ColumnNameArray,$objRow[1],$ColumnNameArray2);
			array_push($objForeignKeysArray, $objIndex);
		}
			
  /*    SHOW The Obj ForeingKey
   echo "<pre>";
   print_r($objForeignKeysArray);
   echo "</pre>";
   */
			
			

  return $objForeignKeysArray;
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
				$strTokenArray = split(' FOREIGN KEY ', $strLine);
				$strTokenArray[1] = split(' REFERENCES ', $strTokenArray[1]);
				$strTokenArray[2] = $strTokenArray[1][1];
				$strTokenArray[1] = $strTokenArray[1][0];
				$strTokenArray[2] = split(' ', $strTokenArray[2]);
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
}

class QOracleDatabaseException extends QDatabaseExceptionBase {
	public function __construct($strMessage, $intNumber, $strQuery) {
		parent::__construct(sprintf("Oracle Error: %s", $strMessage), 2);
		$this->intErrorNumber = $intNumber;
		$this->strQuery = $strQuery;
	}
}

class QOracleDatabaseResult extends QDatabaseResultBase {
	protected $objOracleResult;
	protected $objDb;

	public function __construct($objResult, QOracleDatabase $objDb) {
		$this->objOracleResult = $objResult;
		$this->objDb = $objDb;
	}

	public function FetchArray() {
		return oci_fetch_array($this->objOracleResult);
	}

	public function FetchObject() {
		return oci_fetch_object($this->objOracleResult);
	}

	public function FetchFields() {
		return null; // not implemented
	}

	public function FetchField() {
		return null; // not implemented
	}

	public function FetchRow() {
		return oci_fetch_row($this->objOracleResult);
	}

	public function CountRows() {
		$nr_rows =sizeof(oci_fetch_array($this->objOracleResult,OCI_NUM));
		if($nr_rows >1)
		return ($nr_rows/2);//divide by 2 because it makes OCI_BOTH by default, so ir repeats the results twice
		else
		return 0;
	}

	public function CountFields() {
		return oci_num_fields($this->objOracleResult);
	}

	public function Close() {
		$this->objOracleResult->free();
	}

	public function GetNextRow() {
		$strColumnArray = $this->FetchArray();

		if ($strColumnArray)
		return new QOracleDatabaseRow($strColumnArray);
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

class QOracleDatabaseRow extends QDatabaseRowBase {
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
					// Account for single bit value
					$chrBit = $this->strColumnArray[$strColumnName];
					if ((strlen($chrBit) == 1) && (ord($chrBit) == 0))
					return false;

					// Otherwise, use PHP conditional to determine true or false
					return ($this->strColumnArray[$strColumnName]) ? true : false;

				case QDatabaseFieldType::Blob:
					return QType::Cast($this->strColumnArray[$strColumnName]->load(), QType::String);
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

class QOracleDatabaseField extends QDatabaseFieldBase {
	public function __construct($mixFieldData, $objDb = null) {
			
			
		$this->strTable =  $mixFieldData[0];
		$this->strName = $mixFieldData[1];
		$this->strType =$mixFieldData[2];
		$this->intMaxLength = $mixFieldData[5];
		$this->blnNotNull = ($mixFieldData[8]=="N")?true:false;
		$this->strDefault = $mixFieldData[11];
		$this->strOriginalTable = $this->strTable;
		$this->strOriginalName = $this->strName;
		$this->SetFieldType($mixFieldData[2]);


		/**
		 *
			Implementation just like PostGreSQL Adapter
		 */

  		$objResult=$objDb->Query(sprintf("select position, constraint_type as type from
			user_cons_columns ucc,user_constraints uc where ucc.column_name='%s' 
			AND ucc.TABLE_NAME='%s' and ucc.TABLE_NAME=uc.TABLE_NAME and 
			uc.CONSTRAINT_NAME=ucc.CONSTRAINT_NAME and constraint_type IN ('P', 'U') order by position",$this->strOriginalName,$this->strTable));
	    // TODO: unique and primary?
	    // TODO: auto number (sequence)
	    // TODO: not null?
	    while ($mixRow = $objResult->FetchArray()) {
	      if (isset($mixRow['TYPE'])) {
	        if ($mixRow['TYPE'] == 'P') {
	          $this->blnPrimaryKey = true;
	          // auto-number
	          $this->blnIdentity = true;
	          break;
	        }
	        if ($mixRow['TYPE'] == 'U') {
	          $this->blnUnique = true;
	          $this->blnIdentity = false;
	          $this->blnPrimaryKey = false;
	        }
	      }     
	    }
	}

	protected function SetFieldType($OracleFieldType) {

		//Note: Info from http://download-east.oracle.com/docs/html/B10255_01/ch3.htm#1026123
		switch ($OracleFieldType) {
			case "FLOAT":
				$this->strType = QDatabaseFieldType::Float;
				break;				
			case "NUMBER":
				$this->strType = QDatabaseFieldType::Float;
				break;
            case "CHAR":
                if ($this->intMaxLength == 1)
                    $this->strType = QDatabaseFieldType::Bit;
                else
                    $this->strType = QDatabaseFieldType::Char;
                break;
			case "VARCHAR":
			case "VARCHAR2":
			case "NVARCHAR2":
			case "NCHAR":
				$this->strType = QDatabaseFieldType::VarChar;
				break;
			case "DATE":
				$this->strType = QDatabaseFieldType::DateTime;
				break;
			case "BLOB":
			case "CLOB":
			case "LONG":
			case "NCLOB":
			case "RAW":
			case "LONG RAW":
				$this->strType = QDatabaseFieldType::Blob;
				break;
			default:
				throw new Exception("Unable to determine Oracle Database Field Type: $OracleFieldType");
				break;
		}
	}
}
?>
