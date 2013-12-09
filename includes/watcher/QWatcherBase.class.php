<?php
	/**
	 * QWatcher is a controller for allowing controls to watch a database table to detect changes
	 * and automatically update when changes are detected.
	 * 
	 * It relies on the presence of a helper database in the system. 
	 * 
	 * Static functions handle the database updating, while member variables store the current state
	 * of a control's watched tables.
	 */
	
	class QWatcherBase extends QBaseClass {
		const ALL_WATCHERS = 'QWATCH_ALL';

		//public static $intDbIndex = __WATCHER_DB_INDEX__;
		
		/**
		 * The table name which will keep info about changed tables. It must have the following columns:
		 * 1. name: varchar(100)
		 * 2. ts: timestamp
		 *
		 * Alternatively, it can also have:
		 * 3. id: varchar or int
		 */
		//public static $strTableName = __WATCHER_TABLE_NAME__;
		
		protected $strWatchedKeys = array();

		/**
		 * Override this function to return a key value that will define a subset of the table to
		 * watch. For example, if you have User Ids, combine the user id with the table name.
		 *
		 * @return string
		 */
		protected static function GetKey($strTableName) {
			return $strTableName;
		}
		
		/**
		 * Call from control to watch a node. Current implementation watches all tables associated with the node.
		 * 
		 * @param QQNode $objNode
		 */
		public function Watch(QQNode $objNode) {
			$this->RegisterTable($objNode->_TableName);
			$objParentNode = $objNode->_ParentNode;
			if ($objParentNode) {
				$this->Watch ($objParentNode);
			}
		}
		
		/**
		 * 
		 * Internal function to watch a single table.
		 * 
		 * @param string $strTableName
		 */
		protected function RegisterTable ($strTableName) {
			$key = static::GetKey($strTableName);
			if (empty($this->strWatchedKeys[$key])) {
				$this->strWatchedKeys[$key] =  true;
			}
		}
		
		/**
		 * Controls should call this function just after rendering. Updates strWatchedTables
		 * to the current state of the database.
		 * 
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
		 * Controls should call this from IsModified to detect if they should redraw.
		 * Returns false if the database has been changed since the last draw.
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
		 * Model Save() method should call this to indicate that a table has changed.
		 * 
		 * @param string $strTableName
		 * @throws QCallerException
		 */
		static public function MarkTableModified ($strTableName) {
			$key = static::GetKey ($strTableName);
			$objDatabase = QApplication::$Database[__WATCHER_DB_INDEX__];
			$time = microtime();

			$objDatabase->InsertOrUpdate(__WATCHER_TABLE_NAME__,
				array ('table_key'=>$key,
						'time'=>$time));
			$objDatabase->InsertOrUpdate(__WATCHER_TABLE_NAME__,
				array ('table_key'=>static::GetKey (self::ALL_WATCHERS),
					'time'=>$time));

		}


		static public function FormWatcherChanged (&$strFormWatcherTime) {
			$objDatabase = QApplication::$Database[__WATCHER_DB_INDEX__];
			$strSQL = sprintf ("SELECT * FROM %s WHERE %s = %s",
				$objDatabase->EscapeIdentifier(__WATCHER_TABLE_NAME__),
				$objDatabase->EscapeIdentifier("table_key"),
				$objDatabase->EscapeValues(static::GetKey(self::ALL_WATCHERS)));

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
	