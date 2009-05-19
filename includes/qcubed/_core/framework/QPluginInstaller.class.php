<?php

class QPluginInstaller {
	private static $strLastError = "";
	const PLUGIN_EXTRACTION_DIR = "/tmp/plugin.tmp/";
	
	public static function getLastError() {
		return self::$strLastError;
	}
	
	public static function processUploadedPluginArchive(QFileControl $fileAsset) {
		if (substr($fileAsset->FileName, -3) != "zip") {
			self::$strLastError = "Invalid uploaded plugin file type: " . $fileAsset->Type;
			return null;
		}
		
		$entropy = substr(md5(uniqid()), 0, 6);                        
		$extractionResult = self::extractZip($fileAsset->File, __INCLUDES__ . self::PLUGIN_EXTRACTION_DIR . $entropy . '/');
		if (!$extractionResult) {
			return null;
		}
		
		return $entropy;
	}
	
	public static function installFromExpanded($strExtractedFolderName) {
		QApplication::DisplayAlert("To be implemented");
	}
	
	public static function cancelInstallation($strExtractedFolderName) {
		self::deleteFolderRecursive(__INCLUDES__ . self::PLUGIN_EXTRACTION_DIR . $strExtractedFolderName);
	}
	
	public static function uninstallExisting($strPluginName) {
		QApplication::DisplayAlert("Uninstaller: To be implemented");
	}
	
	/**
	 * Extract a ZIP compressed file to a given path
	 *
	 * @param       string  $archive        Path to ZIP archive to extract
	 * @param       string  $destination    Path to extract archive into
	 * @return      boolean True if successful
	 */
	private static function extractZip($archive, $destination) {
		if ($zip = zip_open($archive)) {
			if ($zip) {
				// Create the destination folder
				if (!mkdir($destination)) {
					self::$strLastError = "Unable to create extraction destination folder";
					return false;
				}

				// Read files in the archive
				$createdFolders = array();
				while ($file = zip_read($zip)) {
					if (zip_entry_open($zip, $file, "r")) {							
						if (substr(zip_entry_name($file), strlen(zip_entry_name($file)) - 1) != "/") {
							
//							echo zip_entry_name($file) . "<br>";
							
							$folderStack = split("/", zip_entry_name($file));
							if (sizeof($folderStack) > 1) {
								for ($i = 0; $i < sizeof($folderStack) - 1; $i++) {
									$item = $folderStack[$i];
									
									if (!in_array($item, $createdFolders)) {
//										echo "- " . $destination . $item . "<br>";
										$createdFolders[] = $item;
										mkdir($destination . $item);
									}
								}
							}
							
							$buffer = zip_entry_read($file, zip_entry_filesize($file));

							if (!$handle = fopen($destination . zip_entry_name($file), 'w')) {
								self::$strLastError = "Cannot open file " . destination . zip_entry_name($file);
								return false;
							}
						   
							// Write $somecontent to our opened file.
							if (fwrite($handle, $buffer) === false) {
								self::$strLastError = "Unable to write extracted file";
								return false;
							}
							
							zip_entry_close($file);
						}
					} else {
						self::$strLastError = "Unable to read zip entry";
						return false;
					}
				}
				zip_close($zip);
			}
		} else {
			self::$strLastError = "Unable to open uploaded archive";
			return false;
		}
		return true;
	}
	
	private static function deleteFolderRecursive($strPath) {
		if (!is_dir($strPath)) {
			unlink($strPath);
			return;
		}

		$d = dir($strPath); 
		while($entry = $d->read()) { 
			if ($entry!= "." && $entry != "..") { 
				if (is_dir($strPath)) {
					self::deleteFolderRecursive($strPath . "/" . $entry);
				} 
			} 
		} 

		$d->close(); 
		rmdir($strPath);
	}
	
}
?>