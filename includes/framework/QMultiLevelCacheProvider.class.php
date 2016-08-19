<?php


	/**
	 * A multi-level Cache provider that's made of multiple cache providers.
	 * Cache hit is checked in the order the cache providers are set
	 * Setting or deleting into the cache sets or deletes it from all the providers
	 */
	class QMultiLevelCacheProvider extends QAbstractCacheProvider {
		/** @var QAbstractCacheProvider[] */
		protected $arrCacheProviders = array();

		/**
		 * Constructs a Multi-level Cache provider based on the configuration used for each provider
		 * @param array $objOptionsArray an associative array where each item is an array with two elements specifying
		 * an inner cache provider. The first element of that array is the class name of the cache provider, and the second
		 * one is the options for constructing the provider
		 */
		public function __construct($objOptionsArray) {
			foreach ($objOptionsArray as $arrProviderNameAndOptions) {
				$strCacheProviderClassname = $arrProviderNameAndOptions[0];
				$this->arrCacheProviders[] = new $strCacheProviderClassname($arrProviderNameAndOptions[1]);
			}
		}

		/**
		 * Get the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return object
		 */
		public function Get($strKey) {
			$objValue = false;
			/** @var QAbstractCacheProvider[] */
			$arrCacheProviders = array();
			foreach ($this->arrCacheProviders as $objCacheProvider) {
				$objValue = $objCacheProvider->Get($strKey);
				if (false !== $objValue) {
					break;
				}
				$arrCacheProviders[] = $objCacheProvider;
			}
			// Set or clear value in all lower caches
			if (false !== $objValue) {
				$arrCacheProviders = array_reverse($arrCacheProviders);
				foreach ($arrCacheProviders as /** @var QAbstractCacheProvider */ $objCacheProvider) {
					$objCacheProvider->Set($strKey, $objValue);
				}
			}
			return $objValue;
		}

		/**
		 * Set the object into the cache with the given key
		 * @param string $strKey the key to use for the object
		 * @param object $objValue the object to put in the cache
		 * @return void
		 */
		public function Set($strKey, $objValue) {
			foreach ($this->arrCacheProviders as $objCacheProvider) {
				$objCacheProvider->Set($strKey, $objValue);
			}
		}

		/**
		 * Delete the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return void
		 */
		public function Delete($strKey) {
			foreach ($this->arrCacheProviders as $objCacheProvider) {
				$objCacheProvider->Delete($strKey);
			}
		}

		/**
		 * Invalidate all the objects in the cache
		 * @return void
		 */
		public function DeleteAll() {
			foreach ($this->arrCacheProviders as $objCacheProvider) {
				$objCacheProvider->DeleteAll();
			}
		}
	}