<?php

	/**
	 * abstract cache provider
	 */
	abstract class QAbstractCacheProvider {
		/**
		 * Get the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return object
		 */
		abstract public function Get($strKey);

		/**
		 * Set the object into the cache with the given key
		 * @param string $strKey the key to use for the object
		 * @param object $objValue the object to put in the cache
		 * @return void
		 */
		abstract public function Set($strKey, $objValue);

		/**
		 * Delete the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return void
		 */
		abstract public function Delete($strKey);

		/**
		 * Invalidate all the objects in the cache
		 * @return void
		 */
		abstract public function DeleteAll();

		/**
		 * Create a key appropriate for this cache provider
		 * @return string the key
		 */
		public function CreateKey(/* ...*/) {
			return implode(":", func_get_args());
		}
	}

?>