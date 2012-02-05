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
				$port = array_key_exists("port", $objServerOptions) ? $objServerOptions["port"] : null;
				$persistent = array_key_exists("persistent", $objServerOptions) ? $objServerOptions["persistent"] : null;
				$weight = array_key_exists("weight", $objServerOptions) ? $objServerOptions["weight"] : null;
				$timeout = array_key_exists("timeout", $objServerOptions) ? $objServerOptions["timeout"] : null;
				$retry_interval = array_key_exists("retry_interval", $objServerOptions) ? $objServerOptions["retry_interval"] : null;
				$status = array_key_exists("status", $objServerOptions) ? $objServerOptions["status"] : null;
				$failure_callback = array_key_exists("failure_callback", $objServerOptions) ? $objServerOptions["failure_callback"] : null;
				$timeoutms = array_key_exists("timeoutms", $objServerOptions) ? $objServerOptions["timeoutms"] : null;
				$this->objMemcache->addserver($host, $port, $persistent, $weight, $timeout, $retry_interval, $status, $failure_callback, $timeoutms);
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