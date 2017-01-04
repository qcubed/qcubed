<?php

	
	/**
	 * Cache provider based on Memcache
	 */
	class QCacheProviderMemcache extends QAbstractCacheProvider {
		/** @var Memcache */
		protected $objMemcache;

		/**
		 * Construct the Memcache based cache provider
		 * @param array $objOptionsArray array of server options. Each item in the array contains an associative
		 * arrays with options for the server to add to memcache
		 */
		public function __construct($objOptionsArray) {
			$this->objMemcache = new Memcache();
			foreach ($objOptionsArray as $objServerOptions) {
				$host = $objServerOptions["host"];
				$port = array_key_exists("port", $objServerOptions) ? $objServerOptions["port"] : 11211;
				$persistent = array_key_exists("persistent", $objServerOptions) ? $objServerOptions["persistent"] : true;
				$weight = array_key_exists("weight", $objServerOptions) ? $objServerOptions["weight"] : 10;
				$timeout = array_key_exists("timeout", $objServerOptions) ? $objServerOptions["timeout"] : 1;
				$retry_interval = array_key_exists("retry_interval", $objServerOptions) ? $objServerOptions["retry_interval"] : 15;
				$status = array_key_exists("status", $objServerOptions) ? $objServerOptions["status"] : true;
				$failure_callback = array_key_exists("failure_callback", $objServerOptions) ? $objServerOptions["failure_callback"] : null;
				$this->objMemcache->addserver($host, $port, $persistent, $weight, $timeout, $retry_interval, $status, $failure_callback);
			}
		}

		/**
		 * Get the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return object
		 */
		public function Get($strKey) {
			return $this->objMemcache->get($strKey);
		}

		/**
		 * Set the object into the cache with the given key
		 *
		 * @param string $strKey                the key to use for the object
		 * @param object $objValue              the object to put in the cache
		 * @param null   $intExpireAfterSeconds Number of seconds after which the key will be expired
		 *
		 * @return void
		 */
		public function Set($strKey, $objValue, $intExpireAfterSeconds = null) {
			$this->objMemcache->set($strKey, $objValue, (int)$intExpireAfterSeconds);
		}

		/**
		 * Delete the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return void
		 */
		public function Delete($strKey) {
			$this->objMemcache->delete($strKey);
		}

		/**
		 * Invalidate all the objects in the cache
		 * @return void
		 */
		public function DeleteAll() {
			$this->objMemcache->flush();
			// needs to wait one second after flush.
			//  See comment on http://www.php.net/manual/ru/memcache.flush.php#81420
			sleep(1);
		}
	}