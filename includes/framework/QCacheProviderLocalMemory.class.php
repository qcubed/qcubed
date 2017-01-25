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

		/**
		 * Get the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return object|bool
		 */
		public function Get($strKey) {
			if (array_key_exists($strKey, $this->arrLocalCache)) {
				// Note the clone statement - it is important to return a copy,
				// not a pointer to the stored object
				// to prevent it's modification by user code.
				$objToReturn = $this->arrLocalCache[$strKey];
				if($objToReturn['timeToExpire'] != 0) {
					// Time to expire was set. See if it should be expired
					if($objToReturn['timeToExpire'] < time()) {
						$this->Delete($strKey);
						return false;
					}
				}

				if (isset($objToReturn['value']) && is_object($objToReturn['value'])) {
					$objToReturn['value'] = clone $objToReturn['value'];
				}

				return $objToReturn['value'];
			}
			return false;
		}

		/**
		 * Set the object into the cache with the given key
		 *
		 * @param string $strKey                    the key to use for the object
		 * @param object $objValue                  the object to put in the cache
		 * @param int    $intExpirationAfterSeconds Number of seconds after which the key has to expire
		 *
		 * @return void
		 */
		public function Set($strKey, $objValue, $intExpirationAfterSeconds = null) {
			// Note the clone statement - it is important to store a copy,
			// not a pointer to the user object
			// to prevent it's modification by user code.
			$objToSet = $objValue;
			if ($objToSet && is_object($objToSet)) {
				$objToSet = clone $objToSet;
			}
			$this->arrLocalCache[$strKey] = array (
				'timeToExpire' => $intExpirationAfterSeconds ? (time() + QType::Cast($intExpirationAfterSeconds, QType::Integer)) : 0,
				'value' => $objToSet
			);
		}

		/**
		 * Delete the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return void
		 */
		public function Delete($strKey) {
			if (array_key_exists($strKey, $this->arrLocalCache)) {
				unset($this->arrLocalCache[$strKey]);
			}
		}

		/**
		 * Invalidate all the objects in the cache
		 * @return void
		 */
		public function DeleteAll() {
			$this->arrLocalCache = array();
		}
	}