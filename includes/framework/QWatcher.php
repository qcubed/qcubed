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
	
	class QWatcher extends QBaseClass {
		
		//public static $intDbIndex = __WATCHER_DB_INDEX__;
		
		/**
		 * The table name which will keep info about changed tables. It must have the following columns:
		 * 1. name: varchar(100)
		 * 2. timestamp: timestamp
		 */
		//public static $strTableName = __WATCHER_TABLE_NAME__;
		
		protected $strWatchedTables = array();
		
		/**
		 * Call from control to watch a node. Current implementation watches all tables associated with the node.
		 * 
		 * @param QQNode $objNode
		 */
		public function Watch(QQNode $objNode) {
			$this->RegisterTable($objNode->_TableName);
			$objChildren = $objNode->ChildNodeArray;
			if ($objChildren) foreach ($objChildren as $objChild) {
				$this->Watch ($objChild);
			}
		}
		
		/**
		 * 
		 * Internal function to watch a single table.
		 * 
		 * @param string $strTableName
		 */
		protected function RegisterTable ($strTableName) {
			if (empty($this->strWatchedTables[$strTableName])) {
				$this->strWatchedTables[$strTableName] =  true;
			}
		}
		
		/**
		 * Controls should call this function just after rendering.
		 * 
		 */
		protected function MakeCurrent() {
			$objDatabase = QApplication::$Database[__WATCHER_DB_INDEX__];
			$strSQL = "SELECT * FROM "  . $objDatabase->EscapeIdentifier(__WATCHER_TABLE_NAME__);
			$objDatabase->Query($strSQL);
			
			while ($strRow = $objDbResult->FetchRow()) {
				if (isset ($this->strWatchedTables[$strRow[0]])) {
					$this->strWatchedTables[$strRow[0]] = $strRow[1];
				}
			}

			// we will wait until next MakeCurrent to put new timestamp into item
		}
		
		/**
		 * Controls should call this from IsModified to detect if they should redraw.
		 */
		protected function IsCurrent() {
			$objDatabase = QApplication::$Database[__WATCHER_DB_INDEX__];
			$strSQL = "SELECT * FROM "  . $objDatabase->EscapeIdentifier(__WATCHER_TABLE_NAME__);
			$objDatabase->Query($strSQL);
			
			while ($strRow = $objDbResult->FetchRow()) {
				if (isset ($this->strWatchedTables[$strRow[0]])) {
					if ($this->strWatchedTables[$strRow[0]] != $strRow[1]) {
						return false;
					}
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
			$objDatabase = QApplication::$Database[__WATCHER_DB_INDEX__];
			try {
				$objDatabase->TransactionBegin();
				
				$strSql = "DELETE FROM " . __WATCHER_TABLE_NAME__ . 'WHERE name="' . $strTableName . '"';
				$result = $objDatabase->NonQuery($strSql);
				$strSql = '
                                INSERT INTO
                                        ' . $objDatabase->EscapeIdentifier(__WATCHER_TABLE_NAME__) . '
                                (
                                        ' . $objDatabase->EscapeIdentifier('name') . ',
                                        ' . $objDatabase->EscapeIdentifier('timestamp') . '
                                )
                                VALUES
                                (
                                        ' . $objDatabase->SqlVariable($strTableName) . ',
                                        CURRENT_TIMESTAMP
                                )';
				
				$result = $objDatabase->NonQuery($strSql);
				$objDatabase->TransactionCommit();
			}
			catch (QCallerException $objException) {
				$objDatabase->TransactionRollback();
				$objException->IncrementOffset();
				throw $objException;
			}
		}
	}
	
?>
	