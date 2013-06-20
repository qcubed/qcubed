<?php


	/**
	 * No-op cache provider: No caching at all.
	 * Use it to disable caching support.
	 */
	class QCacheProviderNoCache extends QAbstractCacheProvider {
		/**
		 * Get the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return object
		 */
		public function Get($strKey) {
			return false;
		}

		/**
		 * Set the object into the cache with the given key
		 * @param string $strKey the key to use for the object
		 * @param object $objValue the object to put in the cache
		 * @return void
		 */
		public function Set($strKey, $objValue) {
			// do nothing
		}

		/**
		 * Delete the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return void
		 */
		public function Delete($strKey) {
			// do nothing
		}

		/**
		 * Invalidate all the objects in the cache
		 * @return void
		 */
		public function DeleteAll() {
			// do nothing
		}
	}

?>