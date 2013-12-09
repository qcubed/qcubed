<?php
	/**
	 * QWatcherAPC is a watcher based on the APC cache php extension. You can use either APC, or APCu.
	 * Could easily be modified to use memcached.
	 */
	
	class QWatcherAPC extends QWatcherBase {

		public static $ttl = 86400; // one day between cache drops

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
			$curTime = microtime();
			foreach ($this->strWatchedKeys as $key=>$val) {
				$time2 = apc_fetch ($key);

				if ($time2===false) {
					// if dropped from cache, or not yet cached
					apc_store ($key, $curTime, static::$ttl);
					$time2 = $curTime;
					apc_store (self::ALL_WATCHERS, $curTime, static::$ttl);
				}
				$this->strWatchedKeys[$key] = $time2;
			}
		}

		/**
		 * Controls should call this from IsModified to detect if they should redraw.
		 * Returns false if the database has been changed since the last draw.
		 * @return bool
		 */
		public function IsCurrent() {
			foreach ($this->strWatchedKeys as $key=>$time) {
				$time2 = apc_fetch($key);
				if (false===$time2 || $time2 != $time) {
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
			$time = microtime();

			apc_store ($key, $time, static::$ttl); // refresh once a day
			apc_store (self::ALL_WATCHERS, $time, static::$ttl);
		}

		/**
		 * Support function for the Form to determine if any of the watchers have changed.
		 *
		 * @param $strFormWatcherTime
		 * @return bool
		 */
		static public function FormWatcherChanged (&$strFormWatcherTime) {
			$time = apc_fetch(self::ALL_WATCHERS);

			if ($strFormWatcherTime !== $time) {
				$strFormWatcherTime = $time;
				return true;
			}
			return false;
		}

	}
	
?>
	