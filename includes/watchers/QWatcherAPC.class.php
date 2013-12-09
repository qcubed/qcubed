<?php
	/**
	 * QWatcherAPC is a watcher based on the APC cache php extension. You can use either APC, or APCu.
	 * APC and APCu are not included in standard PHP, but are easily added with a pecl install.
	 * APCus is probably the fastest pure memory cache available for PHP.
	 * This class could easily be modified to use memcached.
	 */

	include ('QWatcherBase.class.php');

	class QWatcherAPC extends QWatcherBase {

		public static $ttl = 86400; // one day between cache drops

		/**
		 * Records the current state of the watched tables.
		 */
		public function MakeCurrent() {
			$curTime = microtime();
			foreach ($this->strWatchedKeys as $key=>$val) {
				$time2 = apc_fetch ($key);

				if ($time2===false) {
					// if dropped from cache, or not yet cached
					apc_store ($key, $curTime, static::$ttl);
					$time2 = $curTime;
					apc_store (static::GetKey (static::ALL_WATCHERS), $curTime, static::$ttl);
				}
				$this->strWatchedKeys[$key] = $time2;
			}
		}

		/**
		 *
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

			apc_store ($key, $time, static::$ttl);
			apc_store (static::GetKey (static::ALL_WATCHERS), $time, static::$ttl);
		}

		/**
		 * Support function for the Form to determine if any of the watchers have changed.
		 *
		 * @param $strFormWatcherTime
		 * @return bool
		 */
		static public function FormWatcherChanged (&$strFormWatcherTime) {
			$time = apc_fetch(static::GetKey (static::ALL_WATCHERS));

			if ($strFormWatcherTime !== $time) {
				$strFormWatcherTime = $time;
				return true;
			}
			return false;
		}

	}
	
?>
	