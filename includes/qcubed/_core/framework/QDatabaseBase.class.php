<?php
	/**
	 * Every database adapter must implement the following 5 classes (all which are abstract):
	 * * DatabaseBase
	 * * DatabaseFieldBase
	 * * DatabaseResultBase
	 * * DatabaseRowBase
	 * * DatabaseExceptionBase
	 *
	 * This Database library also has the following classes already defined, and 
	 * Database adapters are assumed to use them internally:
	 * * DatabaseIndex
	 * * DatabaseForeignKey
	 * * DatabaseFieldType (which is an abstract class that solely contains constants)
	 *
	 * @package DatabaseAdapters
	 */
	abstract class QDatabaseBase extends QBaseClass {
		// Must be updated for all Adapters
		const Adapter = 'Generic Database Adapter (Abstract)';

		// Protected Member Variables for ALL Database Adapters
		protected $intDatabaseIndex;
		protected $blnEnableProfiling;
		protected $strProfileArray;

		protected $objConfigArray;
		protected $blnConnectedFlag = false;

		protected $strEscapeIdentifierBegin = '"';
		protected $strEscapeIdentifierEnd = '"';

		// Abstract Methods that ALL Database Adapters MUST implement
		abstract public function Connect();
		// these are protected - externally, the "Query/NonQuery" wrappers are meant to be called
		abstract protected function ExecuteQuery($strQuery);
		abstract protected function ExecuteNonQuery($strNonQuery);

		abstract public function GetTables();
		abstract public function InsertId($strTableName = null, $strColumnName = null);

		abstract public function GetFieldsForTable($strTableName);
		abstract public function GetIndexesForTable($strTableName);
		abstract public function GetForeignKeysForTable($strTableName);

		abstract public function TransactionBegin();
		abstract public function TransactionCommit();
		abstract public function TransactionRollBack();

		abstract public function SqlLimitVariablePrefix($strLimitInfo);
		abstract public function SqlLimitVariableSuffix($strLimitInfo);
		abstract public function SqlSortByVariable($strSortByInfo);

		abstract public function Close();
		
		public final function Query($strQuery) {
			$timerName = null;
			if (!$this->blnConnectedFlag) {
				$this->Connect();
			}
			
			
			if ($this->blnEnableProfiling) {
				$timerName = 'queryExec' . mt_rand() ;
				QTimer::Start($timerName);
			}
			
			$result = $this->ExecuteQuery($strQuery);
			
			if ($this->blnEnableProfiling) {
				$dblQueryTime = QTimer::Stop($timerName);
				QTimer::Reset($timerName);
				
				// Log Query (for Profiling, if applicable)
				$this->LogQuery($strQuery, $dblQueryTime);
			}

			return $result;
		}
		
		public final function NonQuery($strNonQuery) {
			if (!$this->blnConnectedFlag) {
				$this->Connect();
			}

			if ($this->blnEnableProfiling) {
				$timerName = 'queryExec' . mt_rand() ;
				QTimer::Start($timerName);
			}
			
			$result = $this->ExecuteNonQuery($strNonQuery);

			if ($this->blnEnableProfiling) {
				$dblQueryTime = QTimer::Stop($timerName);
				QTimer::Reset($timerName);
	
				// Log Query (for Profiling, if applicable)
				$this->LogQuery($strNonQuery, $dblQueryTime);
			}
			
			return $result;
		}

		public function __get($strName) {
			switch ($strName) {
				case 'EscapeIdentifierBegin':
					return $this->strEscapeIdentifierBegin;
				case 'EscapeIdentifierEnd':
					return $this->strEscapeIdentifierEnd;
				case 'EnableProfiling':
					return $this->blnEnableProfiling;
				case 'AffectedRows':
					return -1;
				case 'Profile':
					return $this->strProfileArray;
				case 'DatabaseIndex':
					return $this->intDatabaseIndex;					
				case 'Adapter':
					$strConstantName = get_class($this) . '::Adapter';
					return constant($strConstantName) . ' (' . $this->objConfigArray['adapter'] . ')';
				case 'Server':
				case 'Port':
				case 'Database':
				// Informix naming
				case 'Service':
				case 'Protocol':
				case 'Host':
				
				case 'Username':
				case 'Password':
					return $this->objConfigArray[strtolower($strName)];
				case 'DateFormat':
					return (is_null($this->objConfigArray[strtolower($strName)])) ? (QDateTime::FormatIso) : ($this->objConfigArray[strtolower($strName)]);

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
		 * Constructs a Database Adapter based on the database index and the configuration array of properties for this particular adapter
		 * Sets up the base-level configuration properties for this database,
		 * namely DB Profiling and Database Index
		 *
		 * @param integer $intDatabaseIndex
		 * @param string[] $objConfigArray configuration array as passed in to the constructor by QApplicationBase::InitializeDatabaseConnections();
		 * @return void
		 */
		public function __construct($intDatabaseIndex, $objConfigArray) {
			// Setup DatabaseIndex
			$this->intDatabaseIndex = $intDatabaseIndex;

			// Save the ConfigArray
			$this->objConfigArray = $objConfigArray;

			// Setup Profiling Array (if applicable)
			$this->blnEnableProfiling = QType::Cast($objConfigArray['profiling'], QType::Boolean);
			if ($this->blnEnableProfiling)
				$this->strProfileArray = array();
		}


		/**
		 * Allows for the enabling of DB profiling while in middle of the script
		 *
		 * @return void
		 */
		public function EnableProfiling() {
			// Only perform profiling initialization if profiling is not yet enabled
			if (!$this->blnEnableProfiling) {
				$this->blnEnableProfiling = true;
				$this->strProfileArray = array();
			}
		}

		/**
		 * If EnableProfiling is on, then log the query to the profile array
		 *
		 * @param string $strQuery
		 * @param double $dblQueryTime query execution time in milliseconds
		 * @return void
		 */
		private function LogQuery($strQuery, $dblQueryTime) {
			if ($this->blnEnableProfiling) {
				// Dereference-ize Backtrace Information
				$objDebugBacktrace = debug_backtrace();
				
				// get rid of unnecessary backtrace info in case of:
				// query
				if ((count($objDebugBacktrace) > 3) &&
					(array_key_exists('function', $objDebugBacktrace[2])) &&
					(($objDebugBacktrace[2]['function'] == 'QueryArray') ||
					 ($objDebugBacktrace[2]['function'] == 'QuerySingle') ||
					 ($objDebugBacktrace[2]['function'] == 'QueryCount')))
					$objBacktrace = $objDebugBacktrace[3];
				else
					if (isset($objDebugBacktrace[2]))
						// non query
						$objBacktrace = $objDebugBacktrace[2];
					else
						// ad hoc query
						$objBacktrace = $objDebugBacktrace[1];
				
				// get rid of reference to current object in backtrace array
				if( isset($objBacktrace['object']))
					$objBacktrace['object'] = null;
				
				for ($intIndex = 0; $intIndex < count($objBacktrace['args']); $intIndex++)
					if (($objBacktrace['args'][$intIndex] instanceof QQClause) || ($objBacktrace['args'][$intIndex] instanceof QQCondition))
						$objBacktrace['args'][$intIndex] = sprintf("[%s]", $objBacktrace['args'][$intIndex]->__toString());
					else if (is_null($objBacktrace['args'][$intIndex]))
						$objBacktrace['args'][$intIndex] = 'null';
					else if (gettype($objBacktrace['args'][$intIndex]) == 'integer') {}
					else if (gettype($intIndex['args'][$intIndex]) == 'object')
						$objBacktrace['args'][$intIndex] = 'Object';
					else
						$objBacktrace['args'][$intIndex] = sprintf("'%s'", $objBacktrace['args'][$intIndex]);
				
				// Push it onto the profiling information array
				$arrProfile = array(
					'objBacktrace' 	=> $objBacktrace,
					'strQuery'			=> $strQuery,
					'dblTimeInfo'		=> $dblQueryTime);
				
				array_push( $this->strProfileArray, $arrProfile);
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

			// Check for DATE Value
			if ($mixData instanceof QDateTime) {
				if ($mixData->IsTimeNull())
					return $strToReturn . sprintf("'%s'", $mixData->qFormat('YYYY-MM-DD'));
				else
					return $strToReturn . sprintf("'%s'", $mixData->qFormat(QDateTime::FormatIso));
			}

			// Assume it's some kind of string value
			return $strToReturn . sprintf("'%s'", addslashes($mixData));
		}

		public function PrepareStatement($strQuery, $mixParameterArray) {
			foreach ($mixParameterArray as $strKey => $mixValue) {
				if (is_array($mixValue)) {
					$strParameters = array();
					foreach ($mixValue as $mixParameter)
						array_push($strParameters, $this->Database->SqlVariable($mixParameter));
					$strQuery = str_replace(chr(QQNamedValue::DelimiterCode) . '{' . $strKey . '}', implode(',', $strParameters) . ')', $strQuery);
				} else {
					$strQuery = str_replace(chr(QQNamedValue::DelimiterCode) . '{=' . $strKey . '=}', $this->SqlVariable($mixValue, true, false), $strQuery);
					$strQuery = str_replace(chr(QQNamedValue::DelimiterCode) . '{!' . $strKey . '!}', $this->SqlVariable($mixValue, true, true), $strQuery);
					$strQuery = str_replace(chr(QQNamedValue::DelimiterCode) . '{' . $strKey . '}', $this->SqlVariable($mixValue), $strQuery);
				}
			}

			return $strQuery;
		}

		/**
		 * Displays the OutputProfiling results, plus a link which will popup the details of the profiling.
		 *
		 * @return void
		 */
		public function OutputProfiling() {
			if ($this->blnEnableProfiling) {
				printf('<form method="post" id="frmDbProfile%s" action="%s/profile.php"><div>',
					$this->intDatabaseIndex, __VIRTUAL_DIRECTORY__ . __PHP_ASSETS__);
				printf('<input type="hidden" name="strProfileData" value="%s" />',
					base64_encode(serialize($this->strProfileArray)));
				printf('<input type="hidden" name="intDatabaseIndex" value="%s" />', $this->intDatabaseIndex);
				printf('<input type="hidden" name="strReferrer" value="%s" /></div></form>', QApplication::HtmlEntities(QApplication::$RequestUri));

				$intCount = round(count($this->strProfileArray));
				if ($intCount == 0)
					printf('<b>PROFILING INFORMATION FOR DATABASE CONNECTION #%s</b>: No queries performed.  Please <a href="#" onclick="var frmDbProfile = document.getElementById(\'frmDbProfile%s\'); frmDbProfile.target = \'_blank\'; frmDbProfile.submit(); return false;">click here to view profiling detail</a><br />',
						$this->intDatabaseIndex, $this->intDatabaseIndex);
				else if ($intCount == 1)
					printf('<b>PROFILING INFORMATION FOR DATABASE CONNECTION #%s</b>: 1 query performed.  Please <a href="#" onclick="var frmDbProfile = document.getElementById(\'frmDbProfile%s\'); frmDbProfile.target = \'_blank\'; frmDbProfile.submit(); return false;">click here to view profiling detail</a><br />',
						$this->intDatabaseIndex, $this->intDatabaseIndex);
				else
					printf('<b>PROFILING INFORMATION FOR DATABASE CONNECTION #%s</b>: %s queries performed.  Please <a href="#" onclick="var frmDbProfile = document.getElementById(\'frmDbProfile%s\'); frmDbProfile.target = \'_blank\'; frmDbProfile.submit(); return false;">click here to view profiling detail</a><br />',
						$this->intDatabaseIndex, $intCount, $this->intDatabaseIndex);
			} else {
				_p('<form></form><b>Profiling was not enabled for this database connection (#' . $this->intDatabaseIndex . ').</b>  To enable, ensure that ENABLE_PROFILING is set to TRUE.', false);
			}
		}
	}

	abstract class QDatabaseFieldBase extends QBaseClass {
		protected $strName;
		protected $strOriginalName;
		protected $strTable;
		protected $strOriginalTable;
		protected $strDefault;
		protected $intMaxLength;
		protected $strComment;

		// Bool
		protected $blnIdentity;
		protected $blnNotNull;
		protected $blnPrimaryKey;
		protected $blnUnique;
		protected $blnTimestamp;

		protected $strType;

		public function __get($strName) {
			switch ($strName) {
				case "Name":
					return $this->strName;
				case "OriginalName":
					return $this->strOriginalName;
				case "Table":
					return $this->strTable;
				case "OriginalTable":
					return $this->strOriginalTable;
				case "Default":
					return $this->strDefault;
				case "MaxLength":
					return $this->intMaxLength;
				case "Identity":
					return $this->blnIdentity;
				case "NotNull":
					return $this->blnNotNull;
				case "PrimaryKey":
					return $this->blnPrimaryKey;
				case "Unique":
					return $this->blnUnique;
				case "Timestamp":
					return $this->blnTimestamp;
				case "Type":
					return $this->strType;
				case "Comment":
					return $this->strComment;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	abstract class QDatabaseResultBase extends QBaseClass {
		abstract public function FetchArray();
		abstract public function FetchRow();
		abstract public function FetchField();
		abstract public function FetchFields();
		abstract public function CountRows();
		abstract public function CountFields();

		abstract public function GetNextRow();
		abstract public function GetRows();

		abstract public function Close();
	}
	
	/**
	 *
	 * @package DatabaseAdapters
	 */
	abstract class QDatabaseRowBase extends QBaseClass {
		abstract public function GetColumn($strColumnName, $strColumnType = null);
		abstract public function ColumnExists($strColumnName);
		abstract public function GetColumnNameArray();
	}

	/**
	 *
	 * @package DatabaseAdapters
	 */
	abstract class QDatabaseExceptionBase extends QCallerException {
		protected $intErrorNumber;
		protected $strQuery;

		public function __get($strName) {
			switch ($strName) {
				case "ErrorNumber":
					return $this->intErrorNumber;
				case "Query";
					return $this->strQuery;
				default:
					return parent::__get($strName);
			}
		}
	}

	/**
	 *
	 * @package DatabaseAdapters
	 */
	class QDatabaseForeignKey extends QBaseClass {
		protected $strKeyName;
		protected $strColumnNameArray;
		protected $strReferenceTableName;
		protected $strReferenceColumnNameArray;

		public function __construct($strKeyName, $strColumnNameArray, $strReferenceTableName, $strReferenceColumnNameArray) {
			$this->strKeyName = $strKeyName;
			$this->strColumnNameArray = $strColumnNameArray;
			$this->strReferenceTableName = $strReferenceTableName;
			$this->strReferenceColumnNameArray = $strReferenceColumnNameArray;
		}

		public function __get($strName) {
			switch ($strName) {
				case "KeyName":
					return $this->strKeyName;
				case "ColumnNameArray":
					return $this->strColumnNameArray;
				case "ReferenceTableName":
					return $this->strReferenceTableName;
				case "ReferenceColumnNameArray":
					return $this->strReferenceColumnNameArray;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	/**
	 *
	 * @package DatabaseAdapters
	 */
	class QDatabaseIndex extends QBaseClass {
		protected $strKeyName;
		protected $blnPrimaryKey;
		protected $blnUnique;
		protected $strColumnNameArray;
		
		public function __construct($strKeyName, $blnPrimaryKey, $blnUnique, $strColumnNameArray) {
			$this->strKeyName = $strKeyName;
			$this->blnPrimaryKey = $blnPrimaryKey;
			$this->blnUnique = $blnUnique;
			$this->strColumnNameArray = $strColumnNameArray;
		}
		
		public function __get($strName) {
			switch ($strName) {
				case "KeyName":
					return $this->strKeyName;
				case "PrimaryKey":
					return $this->blnPrimaryKey;
				case "Unique":
					return $this->blnUnique;
				case "ColumnNameArray":
					return $this->strColumnNameArray;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	/**
	 *
	 * @package DatabaseAdapters
	 */
	abstract class QDatabaseFieldType {
		const Blob = "Blob";
		const VarChar = "VarChar";
		const Char = "Char";
		const Integer = "Integer";
		const DateTime = "DateTime";
		const Date = "Date";
		const Time = "Time";
		const Float = "Float";
		const Bit = "Bit";
	}
?>