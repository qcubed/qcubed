<?php
	/**
	 * QWatcher is a helper class that allows controls to watch a database table
	 * and automatically update when changes are detected. It works together with the codegened
	 * model classes, the controls, and the QForm class to draw when needed.
	 * 
	 * It relies on the presence of a SQL database table in the system. Define the following
	 * in your config file to tell it which tables to use:
	 * 		__WATCHER_DB_INDEX__ - The database index to look for the table
	 * 		__WATCHER_TABLE_NAME__ - The name of the table.
	 * 
	 * Static functions handle the database updating, while member variables store the current state
	 * of a control's watched tables.
	 */

	if (!defined('__WATCHER_DB_INDEX__')) define ('__WATCHER_DB_INDEX__', 1);
	if (!defined('__WATCHER_TABLE_NAME__')) define ('__WATCHER_TABLE_NAME__', '_qc_watchers');

	include ('QWatcherBase.class.php');

	class QWatcherDB extends QWatcherBase {

		/**
		 * The table name which will keep info about changed tables. It must have the following columns:
		 * 1. table_key: varchar(largest key size)
		 * 2. time: varchar(30)
		 *
		 */
		public static $intDbIndex = __WATCHER_DB_INDEX__;

		public static $strTableName = __WATCHER_TABLE_NAME__;

		protected static $strKeyCaches = null;

		/**
		 * Override
		 */
		public function MakeCurrent() {
			$objDatabase = QApplication::$Database[__WATCHER_DB_INDEX__];
			$strIn = implode (',', $objDatabase->EscapeValues(array_keys($this->strWatchedKeys)));
			$strSQL = sprintf ("SELECT * FROM %s WHERE %s in (%s)",
				$objDatabase->EscapeIdentifier(__WATCHER_TABLE_NAME__),
				$objDatabase->EscapeIdentifier("table_key"),
				$strIn);

			$objDbResult = $objDatabase->Query($strSQL);

			while ($strRow = $objDbResult->FetchRow()) {
				$this->strWatchedKeys[$strRow[0]] = $strRow[1];
			}
		}

		/**
		 * Override
		 * @return bool
		 */
		public function IsCurrent() {
			$objDatabase = QApplication::$Database[__WATCHER_DB_INDEX__];
			$strIn = implode (',', $objDatabase->EscapeValues(array_keys($this->strWatchedKeys)));
			$strSQL = sprintf ("SELECT * FROM %s WHERE %s in (%s)",
				$objDatabase->EscapeIdentifier(__WATCHER_TABLE_NAME__),
				$objDatabase->EscapeIdentifier("table_key"),
				$strIn);

			$objDbResult = $objDatabase->Query($strSQL);
			
			while ($strRow = $objDbResult->FetchRow()) {
				if ($this->strWatchedKeys[$strRow[0]] !== $strRow[1]) {
					return false;
				}
			}
			
			return true;
		}
		
		/**
		 * Override
		 *
		 * @param string $strTableName
		 * @throws QCallerException
		 */
		static public function MarkTableModified ($strDbName, $strTableName) {
			$key = static::GetKey ($strDbName, $strTableName);
			$objDatabase = QApplication::$Database[__WATCHER_DB_INDEX__];
			$time = microtime();

			$objDatabase->InsertOrUpdate(__WATCHER_TABLE_NAME__,
				array ('table_key'=>$key,
						'time'=>$time));
			$objDatabase->InsertOrUpdate(__WATCHER_TABLE_NAME__,
				array ('table_key'=>static::GetKey ('', static::$strAppKey),
					'time'=>$time));

		}

		/**
		 * Override
		 * @param $strFormWatcherTime
		 * @return bool
		 */
		static public function FormWatcherChanged (&$strFormWatcherTime) {
			$objDatabase = QApplication::$Database[__WATCHER_DB_INDEX__];
			$strSQL = sprintf ("SELECT * FROM %s WHERE %s = %s",
				$objDatabase->EscapeIdentifier(__WATCHER_TABLE_NAME__),
				$objDatabase->EscapeIdentifier("table_key"),
				$objDatabase->EscapeValues(static::GetKey('', static::$strAppKey)));

			$objDbResult = $objDatabase->Query($strSQL);

			if ($strRow = $objDbResult->FetchRow()) {
				if ($strFormWatcherTime !== $strRow[1]) {
					$strFormWatcherTime = $strRow[1];
					return true;
				}
			}
			return false;
		}
	}
	
?>
	