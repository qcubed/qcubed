<?php

	
	/**
	 * Cache provider based on APC interface. APC or APCu can be used.
	 * APC and APCu are not included in standard PHP, but are easily added with a pecl install.
	 */
	class QCacheProviderAPC extends QAbstractCacheProvider {
		/** @var int The lifetime of cached items. */
		public static $ttl = 86400; // one day between cache drops


		/**
		 * Get the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return object
		 */
		public function Get($strKey) {
			return apc_fetch($strKey);
		}

		/**
		 * Set the object into the cache with the given key
		 * @param string $strKey the key to use for the object
		 * @param object $objValue the object to put in the cache
		 * @return void
		 */
		public function Set($strKey, $objValue) {
			apc_store ($strKey, $objValue, static::$ttl);

		}

		/**
		 * Delete the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return void
		 */
		public function Delete($strKey) {
			apc_delete($strKey);
		}

		/**
		 * Invalidate all the objects in the cache
		 * @return void
		 */
		public function DeleteAll() {
			apc_clear_cache('user');
		}
	}