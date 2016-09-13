<?php
	if(!class_exists('QAbstractCacheProvider')){
		include_once __QCUBED_CORE__ . '/framework/QAbstractCacheProvider.class.php';
	}
	
	/**
	 * The interface for cache actions to be queued and replayed.
	 */
	interface ICacheAction {
		/**
		 * Executes action on the given cache object
		 * @param QAbstractCacheProvider $objCache The cache object to apply this action to.
		 */
		public function Execute(QAbstractCacheProvider $objCache);
	}
	/**
	 * The Set cache action to be queued and replayed.
	 */
	class QCacheSetAction implements ICacheAction {
		/**
		 * @var string the key to use for the object
		 */
		protected $strKey;
		/**
		 * @var object the object to put in the cache
		 */
		protected $objValue;

		/**
		 * Construct the new QCacheSetAction object.
		 *
		 * @param string $strKey   the key to use for the object
		 * @param object $objValue the object to put in the cache
		 */
		public function __construct($strKey, $objValue) {
			$this->strKey = $strKey;
			$this->objValue = $objValue;
		}

		/**
		 * Executes action on the given cache object
		 * @param QAbstractCacheProvider $objCache The cache object to apply this action to.
		 */
		public function Execute(QAbstractCacheProvider $objCache) {
			$objCache->Set($this->strKey, $this->objValue);
		}
	}
	/**
	 * The Delete cache action to be queued and replayed.
	 */
	class QCacheDeleteAction implements ICacheAction {
		/**
		 * @var string the key to use for the object
		 */
		protected $strKey;

		/**
		 * Construct the new QCacheDeleteAction object.
		 * @param string $strKey the key to use for the object
		 */
		public function __construct($strKey) {
			$this->strKey = $strKey;
		}

		/**
		 * Executes action on the given cache object
		 * @param QAbstractCacheProvider $objCache The cache object to apply this action to.
		 */
		public function Execute(QAbstractCacheProvider $objCache) {
			$objCache->Delete($this->strKey);
		}
	}
	/**
	 * The DeleteAll cache action to be queued and replayed.
	 */
	class QCacheDeleteAllAction implements ICacheAction {
		/**
		 * Construct the new QCacheDeleteAction object.
		 */
		public function __construct() {
		}

		/**
		 * Executes action on the given cache object
		 * @param QAbstractCacheProvider $objCache The cache object to apply this action to.
		 */
		public function Execute(QAbstractCacheProvider $objCache) {
			$objCache->DeleteAll();
		}
	}
	/**
	 * Cache provider that records all additions and removals from the cache,
	 * and provides an interface to replay them on another instance of an QAbstractCacheProvider
	 */
	class QCacheProviderProxy extends QAbstractCacheProvider {
		/**
		 * @var array Additions to cache
		 */
		protected $arrLocalCacheAdditions;

		/**
		 * @var array Removals from cache
		 */
		protected $arrLocalCacheRemovals;

		/**
		 * @var QAbstractCacheProvider The super cache to query values from.
		 */
		protected $objSuperCache;

		/**
		 * @var ICacheAction[] The queue of actions performed on this cache. 
		 */
		protected $objCacheActionQueue;

		/**
		 * @param QAbstractCacheProvider $objSuperCache The super cache to query values from.
		 */
		public function __construct($objSuperCache) {
			$this->objSuperCache = $objSuperCache;
			$this->objCacheActionQueue = array();
			$this->arrLocalCacheAdditions = array();
			$this->arrLocalCacheRemovals = array();
		}
		
		/**
		 * Apply changes to the cache object supplyed.
		 *
		 * @param QAbstractCacheProvider $objAbstractCacheProvider The cache object to apply changes.
		 */
		public function Replay($objAbstractCacheProvider) {
			foreach ($this->objCacheActionQueue as $objCacheAction) {
				$objCacheAction->Execute($objAbstractCacheProvider);
			}
			$this->objCacheActionQueue = array();
		}
			
		public function Get($strKey) {
			if (isset($this->arrLocalCacheAdditions[$strKey])) {
				return $this->arrLocalCacheAdditions[$strKey];
			}
			if (!isset($this->arrLocalCacheRemovals[$strKey])) {
				if ($this->objSuperCache) {
					return $this->objSuperCache->Get($strKey);
				}
			}
			return false;
		}

		public function Set($strKey, $objValue) {
			$this->arrLocalCacheAdditions[$strKey] = $objValue;
			if (isset($this->arrLocalCacheRemovals[$strKey])) {
				unset($this->arrLocalCacheRemovals[$strKey]);
			}
			$this->objCacheActionQueue[] = new QCacheSetAction($strKey, $objValue);
		}

		public function Delete($strKey) {
			if (isset($this->arrLocalCacheAdditions[$strKey])) {
				unset($this->arrLocalCacheAdditions[$strKey]);
			}
			$this->arrLocalCacheRemovals[$strKey] = true;
			$this->objCacheActionQueue[] = new QCacheDeleteAction($strKey);
		}

		public function DeleteAll() {
			$this->arrLocalCacheAdditions = array();
			$this->arrLocalCacheRemovals = array();
			$this->objCacheActionQueue[] = new QCacheDeleteAllAction;
		}
	}

	/**
	 * Every database adapter must implement the following 5 classes (all of which are abstract):
	 * * DatabaseBase
	 * * DatabaseFieldBase
	 * * DatabaseResultBase
	 * * DatabaseRowBase
	 * * DatabaseExceptionBase
	 * This Database library also has the following classes already defined, and
	 * Database adapters are assumed to use them internally:
	 * * DatabaseIndex
	 * * DatabaseForeignKey
	 * * DatabaseFieldType (which is an abstract class that solely contains constants)
	 *
	 * @property-read string  $EscapeIdentifierBegin
	 * @property-read string  $EscapeIdentifierEnd
	 * @property-read boolean $EnableProfiling
	 * @property-read int     $AffectedRows
	 * @property-read string  $Profile
	 * @property-read int     $DatabaseIndex
	 * @property-read int     $Adapter
	 * @property-read string  $Server
	 * @property-read string  $Port
	 * @property-read string  $Database
	 * @property-read string  $Service
	 * @property-read string  $Protocol
	 * @property-read string  $Host
	 * @property-read string  $Username
	 * @property-read string  $Password
	 * @property boolean      $Caching         if true objects loaded from this database will be kept in cache (assuming a cache provider is also configured)
	 * @property-read string  $DateFormat
	 * @property-read boolean $OnlyFullGroupBy database adapter sub-classes can override and set this property to true
	 *          to prevent the behavior of automatically adding all the columns to the select clause when the query has
	 *          an aggregation clause.
	 * @package DatabaseAdapters
	 */
	abstract class QDatabaseBase extends QBaseClass {
		// Must be updated for all Adapters
		/** Adapter name */
		const Adapter = 'Generic Database Adapter (Abstract)';

		// Protected Member Variables for ALL Database Adapters
		/** @var int Database Index according to the configuration file */
		protected $intDatabaseIndex;
		/** @var bool Has the profiling been enabled? */
		protected $blnEnableProfiling;
		protected $strProfileArray;

		protected $objConfigArray;
		protected $blnConnectedFlag = false;

		/** @var string The beginning part of characters which can escape identifiers in a SQL query for the database */
		protected $strEscapeIdentifierBegin = '"';
		/** @var string The ending part of characters which can escape identifiers in a SQL query for the database */
		protected $strEscapeIdentifierEnd = '"';
		protected $blnOnlyFullGroupBy = false; // should be set in sub-classes as appropriate
		
		/**
		 * @var int The transaction depth value.
		 * It is incremented on a transaction begin,
		 * decremented on a transaction commit, and reset to zero on a roll back.
		 * It is used to implement the recursive transaction functionality.
		 */
		protected $intTransactionDepth = 0;
		
		/**
		 * @var QStack The stack of cache providers.
		 * It is populated with cache providers from different databases,
		 * if there are transaction of one DB in the middle of the transaction of another DB.
		 */
		protected static $objCacheProviderStack;

		// Abstract Methods that ALL Database Adapters MUST implement
		/**
		 * Connects to the database
		 */
		abstract public function Connect();
		// these are protected - externally, the "Query/NonQuery" wrappers are meant to be called
		/**
		 * Sends a SQL query for execution to the database
		 * In this regard, a query is a 'SELECT' statement
		 *
		 * @param string $strQuery The Query to be executed
		 *
		 * @return mixed Result that the database returns after running the query.
		 */
		abstract protected function ExecuteQuery($strQuery);

		/**
		 * Sends a non-SELECT query (such as INSERT, UPDATE, DELETE, TRUNCATE) to DB server.
		 * In most cases, the results of this function are not used and you should not send
		 * 'SELECT' queries using this method because a result is not guaranteed to be returned
		 *
		 * If there was an error, it would most probably be caught as an exception.
		 *
		 * @param string $strNonQuery The Query to be executed
		 *
		 * @return mixed Result that the database returns after running the query
		 */
		abstract protected function ExecuteNonQuery($strNonQuery);

		/**
		 * Returns the list of tables in the database (as string)
		 *
		 * @return mixed|string[] List of tables
		 */
		abstract public function GetTables();

		/**
		 * Returns the ID to be inserted in a table column (normally it an autoincrement column)
		 *
		 * @param null|string $strTableName  Table name where the ID has to be inserted
		 * @param null|string $strColumnName Column name where the ID has to be inserted
		 *
		 * @return mixed
		 */
		abstract public function InsertId($strTableName = null, $strColumnName = null);

		/**
		 * Get the list of columns/fields for a given table
		 *
		 * @param string $strTableName Name of table whose fields we have to get
		 *
		 * @return mixed
		 */
		abstract public function GetFieldsForTable($strTableName);

		/**
		 * Get list of indexes for a table
		 *
		 * @param string $strTableName Name of table whose column indexes we have to get
		 *
		 * @return mixed
		 */
		abstract public function GetIndexesForTable($strTableName);

		/**
		 * Get list of foreign keys for a table
		 *
		 * @param string $strTableName Name of table whose foreign keys we are trying to get
		 *
		 * @return mixed
		 */
		abstract public function GetForeignKeysForTable($strTableName);

		/**
		 * This function actually begins the database transaction.
		 * Must be implemented in all subclasses.
		 * The "TransactionBegin" wrapper are meant to be called by end-user code
		 *
		 * @return void Nothing
		 */
		abstract protected function ExecuteTransactionBegin();

		/**
		 * This function actually commits the database transaction.
		 * Must be implemented in all subclasses.
		 * The "TransactionCommit" wrapper are meant to be called by end-user code
		 * @return void Nothing
		 */
		abstract protected function ExecuteTransactionCommit();

		/**
		 * This function actually rolls back the database transaction.
		 * Must be implemented in all subclasses.
		 * The "TransactionRollBack" wrapper are meant to be called by end-user code
		 *
		 * @return void Nothing
		 */
		abstract protected function ExecuteTransactionRollBack();

		/**
		 * Template for executing stored procedures. Optional, for those database drivers that support it.
		 * @param $strProcName
		 * @param null $params
		 * @return mixed
		 */
		public function ExecuteProcedure($strProcName, $params = null) {}

		/**
		 * This function begins the database transaction.
		 *
		 * @return void Nothing
		 */
		public final function TransactionBegin() {
			if (0 == $this->intTransactionDepth) {
				$this->ExecuteTransactionBegin();
				$objCacheProvider = QApplication::$objCacheProvider;
				if ($objCacheProvider && $this->Caching && !($objCacheProvider instanceof QCacheProviderNoCache)) {
					if (!self::$objCacheProviderStack) {
						self::$objCacheProviderStack = new QStack;
					}
					self::$objCacheProviderStack->Push($objCacheProvider);
					QApplication::$objCacheProvider = new QCacheProviderProxy($objCacheProvider);
				}
			}
			$this->intTransactionDepth++;
		}

		/**
		 * This function commits the database transaction.
		 *
		 * @throws QCallerException
		 * @return void Nothing
		 */
		public final function TransactionCommit() {
			if (1 == $this->intTransactionDepth) {
				$this->ExecuteTransactionCommit();
				$this->transactionCacheFlush();
				$this->transactionCacheRestore();
			}
			if ($this->intTransactionDepth <= 0) {
				throw new QCallerException("The transaction commit call is called before the transaction begin was called.");
			}
			$this->intTransactionDepth--;
		}

		/**
		 * This function rolls back the database transaction.
		 *
		 * @return void Nothing
		 */
		public final function TransactionRollBack() {
			$this->ExecuteTransactionRollBack();
			$this->intTransactionDepth = 0;
			$this->transactionCacheRestore();
		}
		
		/**
		 * Flushes all objects from the local cache to the actual one.
		 */
		protected final function transactionCacheFlush() {
			if (!self::$objCacheProviderStack || self::$objCacheProviderStack->IsEmpty()) {
				return;
			}
			$objCacheProvider = self::$objCacheProviderStack->PeekLast();
			QApplication::$objCacheProvider->Replay($objCacheProvider);
		}

		/**
		 * Restores the actual cache to the QApplication variable.
		 */
		protected final function transactionCacheRestore() {
			if (!self::$objCacheProviderStack || self::$objCacheProviderStack->IsEmpty()) {
				return;
			}
			$objCacheProvider = self::$objCacheProviderStack->Pop();
			// restore the actual cache to the QApplication variable.
			QApplication::$objCacheProvider = $objCacheProvider;
		}

		abstract public function SqlLimitVariablePrefix($strLimitInfo);
		abstract public function SqlLimitVariableSuffix($strLimitInfo);
		abstract public function SqlSortByVariable($strSortByInfo);

		/**
		 * Closes the database connection
		 *
		 * @return mixed
		 */
		abstract public function Close();

		/**
		 * Given an identifier for a SQL query, this method returns the escaped identifier
		 *
		 * @param string $strIdentifier Identifier to be escaped
		 *
		 * @return string Escaped identifier string
		 */
		public function EscapeIdentifier($strIdentifier) {
			return $this->strEscapeIdentifierBegin . $strIdentifier . $this->strEscapeIdentifierEnd;
		}

		/**
		 * Given an array of identifiers, this method returns array of escaped identifiers
		 * For corner case handling, if a single identifier is supplied, a single escaped identifier is returned
		 *
		 * @param array|string $mixIdentifiers Array of escaped identifiers (array) or one unescaped identifier (string)
		 *
		 * @return array|string Array of escaped identifiers (array) or one escaped identifier (string)
		 */
		public function EscapeIdentifiers($mixIdentifiers) {
			if (is_array($mixIdentifiers)) {
				return array_map(array($this, 'EscapeIdentifier'), $mixIdentifiers);
			} else {
				return $this->EscapeIdentifier($mixIdentifiers);
			}
		}

		/**
		 * Escapes values (or single value) which we can then send to the database
		 *
		 * @param array|mixed $mixValues Array of values (or a single value) to be escaped
		 *
		 * @return array|string Array of (or a single) escaped value(s)
		 */
		public function EscapeValues($mixValues) {
			if (is_array($mixValues)) {
				return array_map(array($this, 'SqlVariable'), $mixValues);
			} else {
				return $this->SqlVariable($mixValues);
			}
		}

		/**
		 * Escapes both column and values when supplied as an array
		 *
		 * @param array $mixColumnsAndValuesArray Array with column=>value format with both (column and value) sides unescaped
		 *
		 * @return array Array with column=>value format data with both column and value escaped
		 */
		public function EscapeIdentifiersAndValues($mixColumnsAndValuesArray) {
			$result = array();
			foreach ($mixColumnsAndValuesArray as $strColumn => $mixValue) {
				$result[$this->EscapeIdentifier($strColumn)] = $this->SqlVariable($mixValue);
			}
			return $result;
		}

		/**
		 * INSERTs or UPDATEs a table
		 *
		 * @param string            $strTable                 Table name
		 * @param array             $mixColumnsAndValuesArray column=>value array
		 *                                                    (they are given to 'EscapeIdentifiersAndValues' method)
		 * @param null|string|array $strPKNames               Name(s) of primary key column(s) (expressed as string or array)
		 */
		public function InsertOrUpdate($strTable, $mixColumnsAndValuesArray, $strPKNames = null) {
			$strEscapedArray = $this->EscapeIdentifiersAndValues($mixColumnsAndValuesArray);
			$strColumns = array_keys($strEscapedArray);
			$strUpdateStatement = '';
			foreach ($strEscapedArray as $strColumn => $strValue) {
				if ($strUpdateStatement) $strUpdateStatement .= ', ';
				$strUpdateStatement .= $strColumn . ' = ' . $strValue;
			}
			if (is_null($strPKNames)) {
				$strMatchCondition = 'target_.'.$strColumns[0].' = source_.'.$strColumns[0];
			} else if (is_array($strPKNames)) {
				$strMatchCondition = '';
				foreach ($strPKNames as $strPKName) {
					if ($strMatchCondition) $strMatchCondition .= ' AND ';
					$strMatchCondition .= 'target_.'.$this->EscapeIdentifier($strPKName).' = source_.'.$this->EscapeIdentifier($strPKName);
				}
			} else {
				$strMatchCondition = 'target_.'.$this->EscapeIdentifier($strPKNames).' = source_.'.$this->EscapeIdentifier($strPKNames);
			}
			$strTable = $this->EscapeIdentifierBegin . $strTable . $this->EscapeIdentifierEnd;
			$strSql = sprintf('MERGE INTO %s AS target_ USING %s AS source_ ON %s WHEN MATCHED THEN UPDATE SET %s WHEN NOT MATCHED THEN INSERT (%s) VALUES (%s)',
				$strTable, $strTable,
				$strMatchCondition, $strUpdateStatement,
				implode(', ', $strColumns),
				implode(', ', array_values($strEscapedArray))
			);
			$this->ExecuteNonQuery($strSql);
		}

		/**
		 * Sends the 'SELECT' query to the database and returns the result
		 *
		 * @param string $strQuery query string
		 *
		 * @return QDatabaseResultBase
		 */
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

		/**
		 * This is basically the same as 'Query' but is used when SQL statements other than 'SELECT'
		 * @param string $strNonQuery The SQL to be sent
		 *
		 * @return mixed
		 * @throws QCallerException
		 */
		public final function NonQuery($strNonQuery) {
			if (!$this->blnConnectedFlag) {
				$this->Connect();
			}
			$timerName = '';
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

		/**
		 * PHP magic method
		 * @param string $strName Property name
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
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
				case 'Caching':
					return $this->objConfigArray[strtolower($strName)];
				case 'DateFormat':
					return (is_null($this->objConfigArray[strtolower($strName)])) ? (QDateTime::FormatIso) : ($this->objConfigArray[strtolower($strName)]);
				case 'OnlyFullGroupBy':
					return (!isset($this->objConfigArray[strtolower($strName)])) ? $this->blnOnlyFullGroupBy : $this->objConfigArray[strtolower($strName)];

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
		 * PHP magic method to set class properties
		 * @param string $strName  Property name
		 * @param string $mixValue Property value
		 *
		 * @return mixed|void
		 * @throws Exception|QCallerException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'Caching':
					$this->objConfigArray[strtolower($strName)] = $mixValue;
					break;

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**
		 * Constructs a Database Adapter based on the database index and the configuration array of properties
		 * for this particular adapter. Sets up the base-level configuration properties for this database,
		 * namely DB Profiling and Database Index
		 *
		 * @param integer  $intDatabaseIndex
		 * @param string[] $objConfigArray configuration array as passed in to the constructor
		 *                                 by QApplicationBase::InitializeDatabaseConnections();
		 *
		 * @throws Exception|QCallerException|QInvalidCastException
		 * @return QDatabaseBase
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
				
				for ($intIndex = 0, $intMax = count($objBacktrace['args']); $intIndex < $intMax; $intIndex++) {
					$obj = $objBacktrace['args'][$intIndex];
					if (($obj instanceof QQClause) || ($obj instanceof QQCondition))
						$obj = sprintf("[%s]", $obj->__toString());
					else if (is_null($obj))
						$obj = 'null';
					else if (gettype($obj) == 'integer') {}
					else if (gettype($obj) == 'object')
						$obj = 'Object';
					else if (is_array($obj))
						$obj = 'Array';
					else
						$obj = sprintf("'%s'", $obj);
					$objBacktrace['args'][$intIndex] = $obj;
				}
				
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
				/** @var QDateTime $mixData */
				if ($mixData->IsTimeNull()) {
					if ($mixData->IsDateNull()) {
						return $strToReturn . 'NULL'; // null date and time is a null value
					}
					return $strToReturn . sprintf("'%s'", $mixData->qFormat('YYYY-MM-DD'));
				}
				elseif ($mixData->IsDateNull()) {
					return  $strToReturn . sprintf("'%s'", $mixData->qFormat('hhhh:mm:ss'));
				}
				return $strToReturn . sprintf("'%s'", $mixData->qFormat(QDateTime::FormatIso));
			}

			// an array. Assume we are using it in an array context, like an IN clause
			if (is_array($mixData)) {
				$items = [];
				foreach ($mixData as $item) {
					$items[] = $this->SqlVariable($item);	// recurse
				}
				return '(' . implode(',', $items) . ')';
			}

			// Assume it's some kind of string value
			return $strToReturn . sprintf("'%s'", addslashes($mixData));
		}

		public function PrepareStatement($strQuery, $mixParameterArray) {
			foreach ($mixParameterArray as $strKey => $mixValue) {
				if (is_array($mixValue)) {
					$strParameters = array();
					foreach ($mixValue as $mixParameter)
						array_push($strParameters, $this->SqlVariable($mixParameter));
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
		 * @param bool $blnPrintOutput
		 * @return null|string
		 */
		public function OutputProfiling($blnPrintOutput = true) {

			$strOut = '<div class="qDbProfile">';
			if ($this->blnEnableProfiling) {
				$strOut .= sprintf('<form method="post" id="frmDbProfile%s" action="%s/profile.php"><div>',
					$this->intDatabaseIndex, __VIRTUAL_DIRECTORY__ . __PHP_ASSETS__);
				$strOut.= sprintf('<input type="hidden" name="strProfileData" value="%s" />',
					base64_encode(serialize($this->strProfileArray)));
				$strOut .= sprintf('<input type="hidden" name="intDatabaseIndex" value="%s" />', $this->intDatabaseIndex);
				$strOut .= sprintf('<input type="hidden" name="strReferrer" value="%s" /></div></form>', QApplication::HtmlEntities(QApplication::$RequestUri));

				$intCount = round(count($this->strProfileArray));
				if ($intCount == 0)
					$strQueryString =  'No queries';
				else if ($intCount == 1)
					$strQueryString =  '1 query';
				else
					$strQueryString =  $intCount . ' queries';

				$strOut .= sprintf('<b>PROFILING INFORMATION FOR DATABASE CONNECTION #%s</b>: %s performed.  Please <a href="#" onclick="var frmDbProfile = document.getElementById(\'frmDbProfile%s\'); frmDbProfile.target = \'_blank\'; frmDbProfile.submit(); return false;">click here to view profiling detail</a><br />',
					$this->intDatabaseIndex, $strQueryString, $this->intDatabaseIndex);
			} else {
				$strOut .= '<form></form><b>Profiling was not enabled for this database connection (#' . $this->intDatabaseIndex . ').</b>  To enable, ensure that ENABLE_PROFILING is set to TRUE.';
			}
			$strOut .= '</div>';

			$strOut .= '<script>$j(function() {$j(".qDbProfile").draggable();});</script>';	// make it draggable so you can move it out of the way if needed.

			if ($blnPrintOutput) {
				print ($strOut);
				return null;
			}
			else {
				return $strOut;
			}
		}

		/**
		 * Executes the explain statement for a given query and returns the output without any transformation.
		 * If the database adapter does not support EXPLAIN statements, returns null.
		 *
		 * @param $strSql
		 *
		 * @return null
		 */
		public function ExplainStatement($strSql) {
			return null;
		}


		/**
		 * Utility function to extract the json embedded options structure from the comments.
		 *
		 * Usage:
		 * <code>
		 * 	list($strComment, $options) = QDatabaseFieldBase::ExtractCommentOptions($strComment);
		 * </code>
		 *
		 * @param string $strComment	The comment to analyze
		 * @return array A two item array, with first item the comment with the options removed, and 2nd item the options array.
		 *
		 */
		public static function ExtractCommentOptions($strComment) {
			$ret[0] = null; // comment string without options
			$ret[1] = null; // the options array
			if (($strComment) &&
				($pos1 = strpos ($strComment, '{')) !== false &&
				($pos2 = strrpos ($strComment, '}', $pos1))) {

				$strJson = substr ($strComment, $pos1, $pos2 - $pos1 + 1);
				$a = json_decode($strJson, true);

				if ($a) {
					$ret[0] = substr ($strComment, 0, $pos1) . substr ($strComment, $pos2 + 1); // return comment without options
					$ret[1] = $a;
				} else {
					$ret[0] = $strComment;
				}
			}

			return $ret;
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

		/**
		 * PHP magic method
		 *
		 * @param string $strName Property name
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
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

	/**
	 * Class to handle results sent by database upon querying
	 * @property QQueryBuilder $QueryBuilder
	 */
	abstract class QDatabaseResultBase extends QBaseClass {
		// Allow to attach a QQueryBuilder object to use the result object as cursor resource for cursor queries.
		/** @var QQueryBuilder Query builder object */
		protected $objQueryBuilder;

		/**
		 * Fetches one row as indexed (column=>value style) array from the result set
		 * @abstract
		 * @return mixed
		 */
		abstract public function FetchArray();

		/**
		 * Fetches one row as enumerated (with numerical indexes) array from the result set
		 * @abstract
		 * @return mixed
		 */
		abstract public function FetchRow();

		abstract public function FetchField();
		abstract public function FetchFields();
		abstract public function CountRows();
		abstract public function CountFields();

		abstract public function GetNextRow();
		abstract public function GetRows();

		abstract public function Close();

		/**
		 * PHP magic method
		 *
		 * @param string $strName Property name
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'QueryBuilder':
					return $this->objQueryBuilder;
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
				case 'QueryBuilder':
					try {
						return ($this->objQueryBuilder = QType::Cast($mixValue, 'QQueryBuilder'));
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
	
	/**
	 * Base class for all Database rows. Implemented by Database adapters
	 * @package DatabaseAdapters
	 */
	abstract class QDatabaseRowBase extends QBaseClass {
		/**
		 * Gets the value of a column from a result row returned by the database
		 *
		 * @param string      $strColumnName Name of the column
		 * @param null|string $strColumnType Data type
		 *
		 * @return mixed
		 */
		abstract public function GetColumn($strColumnName, $strColumnType = null);
		/**
		 * Tells whether a particular column exists in a returned database row
		 *
		 * @param string $strColumnName Name of te column
		 *
		 * @return bool
		 */
		abstract public function ColumnExists($strColumnName);
		abstract public function GetColumnNameArray();

		/**
		 * Returns the boolean value corresponding to whatever a boolean column returns. Some database types
		 * return strings that represent the boolean values. Default is to use a PHP cast.
		 * @param $mixValue Value of the BIT column
		 * @return bool
		 */
		public function ResolveBooleanValue ($mixValue) {
			if ($mixValue === null) {
				return null;
			}
			return ((bool)$mixValue);
		}

	}

	/**
	 * Class to handle exceptions related to database querying
	 * @property-read int $ErrorNumber The number of error provided by the SQL server
	 * @property-read string $Query The query caused the error
	 * @package DatabaseAdapters
	 */
	abstract class QDatabaseExceptionBase extends QCallerException {
		/** @var int Error number */
		protected $intErrorNumber;
		/** @var string Query which produced the error */
		protected $strQuery;

		/**
		 * PHP magic function to get property values
		 * @param string $strName
		 *
		 * @return array|int|mixed
		 */
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

		/**
		 * PHP magic method
		 *
		 * @param string $strName Property name
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
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
	 * To handle index in a table in database
	 * @package DatabaseAdapters
	 */
	class QDatabaseIndex extends QBaseClass {
		/** @var string Name of the index */
		protected $strKeyName;
		/** @var bool Is the Index a primary key index? */
		protected $blnPrimaryKey;
		/** @var bool Is this a Unique index? */
		protected $blnUnique;
		/** @var array Array of column names on which this index is defined */
		protected $strColumnNameArray;

		/**
		 * @param string $strKeyName         Name of the index
		 * @param string $blnPrimaryKey      Is this index a Primary key index?
		 * @param string $blnUnique          Is this index unique?
		 * @param array  $strColumnNameArray Columns on which this index is defined
		 */
		public function __construct($strKeyName, $blnPrimaryKey, $blnUnique, $strColumnNameArray) {
			$this->strKeyName = $strKeyName;
			$this->blnPrimaryKey = $blnPrimaryKey;
			$this->blnUnique = $blnUnique;
			$this->strColumnNameArray = $strColumnNameArray;
		}

		/**
		 * PHP magic function
		 * @param string $strName
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
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
	 * Data types in a database
	 * @package DatabaseAdapters
	 */
	abstract class QDatabaseFieldType {
		/** Binary Data (Binary Large OBjects/BLOBs) */
		const Blob = "Blob";
		/** Character sequence - variable length */
		const VarChar = "VarChar";
		/** Character sequence - fixed length */
		const Char = "Char";
		/** Integers */
		const Integer = "Integer";
		/** Date and Time together */
		const DateTime = "DateTime";
		/** Date only */
		const Date = "Date";
		/** Time only */
		const Time = "Time";
		/** Float, Double and real (postgresql) */
		const Float = "Float";
		/** Boolean */
		const Bit = "Bit";
		/** New JSON type */
		const Json = "Json";
	}