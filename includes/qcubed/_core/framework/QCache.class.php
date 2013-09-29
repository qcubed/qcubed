<?php
	class QCache extends QBaseClass {
		public static $CachePath = __CACHE__;

		protected $strNamespace;
		protected $strKey;
		protected $strExtension;
		protected $strCheckFilesArray;

		/**
		 * @param $strNamespace Note that the namespace can be recursive - use forward slashes as separators
		 */
		public function __construct($strNamespace, $strKey, $strExtension = 'txt', $mixCheckFiles = null) {
			$this->strNamespace = trim(strtolower($strNamespace));
			$this->strKey = md5(trim(strtolower($strKey)));
			$this->strExtension = trim(strtolower($strExtension));

			if (is_array($mixCheckFiles))
				$this->strCheckFilesArray = $mixCheckFiles;
			else if ($mixCheckFiles)
				$this->strCheckFilesArray = array($mixCheckFiles);
			else
				$this->strCheckFilesArray = array();
		}

		public function GetData() {
			// First, ensure that the cache file exits
			if (file_exists($this->GetFilePath())) {
				if (count($this->strCheckFilesArray)) {
					// Now, get the current hash of the checkfiles
					$strHash = $this->GetCheckFilesHash();

					// If No CheckFiles, the delete cache file and return false
					if ($strHash === false) {
						unlink($this->GetFilePath());
						return false;
					}

					// If Hash File doesn't exist or if the values don't match, delete and return
					$strHashFile = $this->GetFilePath() . '.hash';
					if (!file_exists($strHashFile) ||
						($strHash != file_get_contents($strHashFile))) {
						unlink($this->GetFilePath());
						return false;
					}
				}

				// If we're here, return the contents of the cache file
				return file_get_contents($this->GetFilePath());
			} else
				return false;
		}

		public function SaveData($strData) {
			if (!is_dir($this->GetCacheDirectory())) {
				mkdir($this->GetCacheDirectory(),0777,true);
			}

			file_put_contents($this->GetFilePath(), $strData);

			if (count($this->strCheckFilesArray)) {
				file_put_contents($this->GetFilePath() . '.hash', $this->GetCheckFilesHash());
			}
		}
		
		public function DeleteData() {
			if (!is_dir($this->GetCacheDirectory())) {
				return;
			}
			
			if (file_exists($this->GetFilePath() . '.hash')) {
				unlink($this->GetFilePath() . '.hash');
			}
		}

		/**
		 * @return boolean that determines whether clearing of the cache was successful
		 */
		public static function ClearNamespace($strNamespace) { 
			$strNamespace = trim(strtolower($strNamespace));
			$strCachePath = strtolower(QCache::$CachePath) . "/";
			
			if (substr_count($strNamespace, $strCachePath) == 0) {
				$strDir = sprintf('%s%s', $strCachePath, $strNamespace);
			} else {
				$strDir = $strNamespace;
			}

			if (!is_dir($strDir)) {
				return false;
			}

			$dir = opendir($strDir);
			if (false === $dir) {
				return false;
			}

			while($filename = readdir($dir))  {
				if ($filename != "." && $filename != "..")  {
					if (!is_dir($strDir."/".$filename)) {
						unlink($strDir."/".$filename);
					} else {
						if(false === self::ClearNamespace($strDir.'/'.$filename)) {
							return false;
						}
						rmdir($strDir.'/'.$filename);
					}
				}
			}
			closedir($dir);
			if ($strDir != $strCachePath) {
				rmdir($strDir);
			}
			return true;
		}
		
		public function GetFilePath() {
			return sprintf('%s/%s/%s.%s', QCache::$CachePath, $this->strNamespace, $this->strKey, $this->strExtension);
		}

		protected function GetCheckFilesHash() {
			$intFoundFileCount = 0;
			$strData = '';
			foreach($this->strCheckFilesArray as $strCheckFile) {
				if (file_exists($strCheckFile)) {
					$intFoundFileCount++;
					$strData .= filemtime($strCheckFile);
				}
			}

			if ($intFoundFileCount == 0)
				return false;
			else
				return $intFoundFileCount . '_' . $strData;
		}

		protected function GetCacheDirectory() {
			return sprintf('%s/%s', QCache::$CachePath, $this->strNamespace);
		}
	}
?>