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
	 * To create the database, use the following SQL:
	 * CREATE TABLE IF NOT EXISTS qc_watchers (
	 * table_key varchar(200) NOT NULL,
	 * ts varchar(40) NOT NULL,
	 * PRIMARY KEY (table_key)
	 * );

	 * 
	 * Static functions handle the database updating, while member variables store the current state
	 * of a control's watched tables.
	 */

	if (!defined('__WATCHER_DB_INDEX__')) define ('__WATCHER_DB_INDEX__', 1);
	if (!defined('__WATCHER_TABLE_NAME__')) define ('__WATCHER_TABLE_NAME__', 'qc_watchers');

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

		/**
		 * @var string[] Caches results of database lookups. Will not be saved with the formstate.
		 */
		private static $strKeyCaches = null;

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
		 * Returns true if the watcher is up to date, and false if something has
		 * changed. Caches the results so it only hits the database minimally for each
		 * read.
		 *
		 * @return bool
		 */
		public function IsCurrent() {
			// check cache
			$ret = true;

			foreach ($this->strWatchedKeys as $key=>$ts) {
				if (!isset (self::$strKeyCaches[$key])) {
					$ret = false;
					break;
				}
				if (self::$strKeyCaches[$key] !== $ts) {
					return false;
				}
			}
			if ($ret) return true; // cache had everything we were looking for

			// cache did not have what we were looking for, so check database
			$objDatabase = QApplication::$Database[__WATCHER_DB_INDEX__];
			$strIn = implode (',', $objDatabase->EscapeValues(array_keys($this->strWatchedKeys)));
			$strSQL = sprintf ("SELECT * FROM %s WHERE %s in (%s)",
				$objDatabase->EscapeIdentifier(__WATCHER_TABLE_NAME__),
				$objDatabase->EscapeIdentifier("table_key"),
				$strIn);

			$objDbResult = $objDatabase->Query($strSQL);

			// fill cache and check result
			while ($strRow = $objDbResult->FetchRow()) {
				self::$strKeyCaches[$strRow[0]] = $strRow[1];
				if ($ret && $this->strWatchedKeys[$strRow[0]] !== $strRow[1]) {
					$ret = false;
				}
			}
			
			return $ret;
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
						'ts'=>$time));
			$objDatabase->InsertOrUpdate(__WATCHER_TABLE_NAME__,
				array ('table_key'=>static::GetKey ('', static::$strAppKey),
					'ts'=>$time));

		}
	}
