<?php


	class QCacheProviderNoCache extends QAbstractCacheProvider {
		public function Get($strKey) {
			return false;
		}

		public function Set($strKey, $objValue) {
			// do nothing
		}

		public function Delete($strKey) {
			// do nothing
		}

		public function DeleteAll() {
			// do nothing
		}
	}

?>