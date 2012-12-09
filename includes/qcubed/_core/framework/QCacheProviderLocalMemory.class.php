<?php


	/**
	 * Cache provider that uses a local in memory array.
	 * The lifespan of this cache is the request, unless 'KeepInSession' option is used, in which case the lifespan
	 * is the session.
	 */
	class QCacheProviderLocalMemory extends QAbstractCacheProvider {
		/** @var array */
		protected $arrLocalCache;

		/**
		 * @param array $objOptionsArray configuration options for this cache provider. Currently supported options are
		 *   'KeepInSession': if set to true the cache will be kept in session
		 */
		public function __construct($objOptionsArray) {
			if (array_key_exists('KeepInSession', $objOptionsArray) && $objOptionsArray['KeepInSession'] === true) {
				if (!isset($_SESSION['__LOCAL_MEMORY_CACHE__'])) {
					$_SESSION['__LOCAL_MEMORY_CACHE__'] = array();
				}
				$this->arrLocalCache = &$_SESSION['__LOCAL_MEMORY_CACHE__'];
			} else {
				$this->arrLocalCache = array();
			}
		}

		public function Get($strKey) {
			if (array_key_exists($strKey, $this->arrLocalCache)) {
				return $this->arrLocalCache[$strKey];
			}
			return false;
		}

		public function Set($strKey, $objValue) {
			$this->arrLocalCache[$strKey] = $objValue;
		}

		public function Delete($strKey) {
			unset($this->arrLocalCache[$strKey]);
		}

		public function DeleteAll() {
			$this->arrLocalCache = array();
		}
	}

?>