<?php


	/**
	 * No-op cache provider: No caching at all.
	 * Use it to disable caching support.
	 */
	class QCacheProviderNoCache extends QAbstractCacheProvider {
		/**
		 * Get the object that has the given key from the cache
		 *
		 * @param string $strKey the key of the object in the cache
		 *
		 * @return bool Always return false
		 */
		public function Get($strKey) {
			// We are not saving anything. Hence, we return null
			return false;
		}

		/**
		 * Set the object into the cache with the given key
		 *
		 * @param string $strKey                the key to use for the object
		 * @param object $objValue              the object to put in the cache
		 * @param int    $intExpireAfterSeconds Number of seconds after which the object will expire
		 *
		 * @return void
		 */
		public function Set($strKey, $objValue, $intExpireAfterSeconds) {
			// do nothing
		}

		/**
		 * Delete the object that has the given key from the cache
		 *
		 * @param string $strKey the key of the object in the cache
		 *
		 * @return void
		 */
		public function Delete($strKey) {
			// do nothing
		}

		/**
		 * Invalidate all the objects in the cache
		 *
		 * @return void
		 */
		public function DeleteAll() {
			// do nothing
		}
	}