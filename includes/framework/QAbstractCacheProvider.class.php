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
		 *
		 * @param string  $strKey        the key to use for the object
		 * @param object  $objValue      the object to put in the cache
		 * @param integer $intExpiration Number of seconds after which the value has to expire
		 *
		 * @return void
		 */
		abstract public function Set($strKey, $objValue, $intExpiration = null);

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
		public function CreateKey(/* ... */) {
			// @hack for php version < 5.4
			$objArgsArray = array();
			$arg_list = func_get_args();
			$numargs = func_num_args();
			for ($i = 0; $i < $numargs; $i++) {
				$arg = $arg_list[$i];
				if (is_array($arg)) {
					foreach ($arg as $a) {
						$objArgsArray[] = $a;
					}
				} else {
					$objArgsArray[] = $arg;
				}
			}

			return implode(":", $objArgsArray);
			//return implode(":", func_get_args());
		}

		public function CreateKeyArray($a) {
			return implode(":", $a);
		}

	}