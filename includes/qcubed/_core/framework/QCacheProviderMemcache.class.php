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

		public function Get($strKey) {
			return $this->objMemcache->get($strKey);
		}

		public function Set($strKey, $objValue) {
			$this->objMemcache->set($strKey, $objValue);
		}

		public function Delete($strKey) {
			$this->objMemcache->delete($strKey);
		}

		public function DeleteAll() {
			$this->objMemcache->flush();
		}
	}

?>