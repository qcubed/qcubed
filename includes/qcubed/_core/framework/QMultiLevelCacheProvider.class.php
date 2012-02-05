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

		public function Get($strKey) {
			foreach ($this->arrCacheProviders as $objCacheProvider) {
				$objValue = $objCacheProvider->Get($strKey);
				if ($objValue !== false) {
					return$objValue;
				}
			}
			return false;
		}

		public function Set($strKey, $objValue) {
			foreach ($this->arrCacheProviders as $objCacheProvider) {
				$objCacheProvider->Set($strKey, $objValue);
			}
		}

		public function Delete($strKey) {
			foreach ($this->arrCacheProviders as $objCacheProvider) {
				$objCacheProvider->Delete($strKey);
			}
		}

		public function DeleteAll() {
			foreach ($this->arrCacheProviders as $objCacheProvider) {
				$objCacheProvider->DeleteAll();
			}
		}
	}

?>