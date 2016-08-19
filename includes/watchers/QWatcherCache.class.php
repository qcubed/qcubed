<?php
	/**
	 * QWatcherCache is a watcher based on the CacheProvider class.
	 * By default, it will use the application cache provider. You can do something different by overriding
	 * initCache in project/includes/controls/QWatcher.class.php.
	 * Note that if you want to be able to detect changes by other users, you should use a
	 * shared caching mechanism, like APC or Memcache. QCacheProviderLocalMemory will
	 * not work for this.
	 */

	include ('QWatcherBase.class.php');

	class QWatcherCache extends QWatcherBase {

		/** @var  QAbstractCacheProvider */
		public static $objCache = null;

		/**
		 *
		 */
		public function __construct () {
			if (!static::$objCache) {
				static::initCache();
			}
		}

		/**
		 * Records the current state of the watched tables.
		 */
		public function MakeCurrent() {
			if (!static::$objCache) {
				static::initCache();
			}
			$curTime = microtime();
			foreach ($this->strWatchedKeys as $key=>$val) {
				$time2 = static::$objCache->Get($key);

				if ($time2===false) {
					// if dropped from cache, or not yet cached
					static::$objCache->Set($key, $curTime);
					$time2 = $curTime;
				}
				$this->strWatchedKeys[$key] = $time2;
			}
		}

		/**
		 *
		 * @return bool
		 */
		public function IsCurrent() {
			if (!static::$objCache) {
				static::initCache();
			}
			foreach ($this->strWatchedKeys as $key=>$time) {
				$time2 = static::$objCache->Get($key);
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
		static public function MarkTableModified ($strDbName, $strTableName) {
			parent::MarkTableModified($strDbName, $strTableName);
			if (!static::$objCache) {
				static::initCache();
			}
			$key = static::GetKey ($strDbName, $strTableName);
			$time = microtime();

			self::$objCache->Set($key, $time);
		}

		/**
		 * Initializes the cache. By default, uses the application cache. Configure that in your config
		 * settings.
		 */
		static protected function initCache(){
			static::$objCache = QApplication::$objCacheProvider;
		}

	}