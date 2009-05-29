<?php

abstract class QPluginInstallerBase {
	private static $strLastError = "";
	
	const PLUGIN_EXTRACTION_DIR = "/tmp/plugin.tmp/";
	/**
	 * @var string Name of the the file defines plugin settings in XML format.
	 */
	const PLUGIN_CONFIG_FILE = "plugin.xml";

	/**
	 * @var string Name of the the file defines plugin settings in PHP format.
	 */	
	const PLUGIN_CONFIG_GENERATION_FILE = "install.php";
	
	// these three have to be functions - PHP doesn't allow for static vars with concatenation :(
	public static function getMasterConfigFilePath() { 
		return __PLUGINS__ . '/plugin_config.xml';
	}
	
	public static function getMasterIncludeFilePath() {
		return __PLUGINS__ . '/plugin_includes.php';
	}
	
	public static function getMasterExamplesFilePath() {
		return __PLUGINS__ . '/plugin_examples.php';
	}
	
	public static function getLastError() {
		return self::$strLastError;
	}
			
	public static function isPluginInstalled($strPluginName) {
		$installedPlugins = QPluginConfigParser::parseInstalledPlugins();
		$found = false;
		foreach ($installedPlugins as $plugin) {
			if (strcmp($plugin->strName, $strPluginName) == 0) {
				$found = true;
				break;
			}
		}
		
		return $found;
	}
		
	protected static function replaceFileSection($strFilePath, $strSearch, $strReplace) {
		$contents = self::readFile($strFilePath);
		
		$contents = str_replace($strSearch, $strReplace, $contents);
		
		self::writeFile($strFilePath, self::stripExtraNewlines($contents));
	}
		
	/**
	 * Copies the file to the destination folder for the plugin,
	 * creating all relevant directories in the process, if necessary.
	 */
	protected static function writeFileHelper($strSourcePath, $strDestinationPath) {
		$result = "";
		
		// Creating folder hierarchy if necessary
		$arrFolderSteps = split("/", $strDestinationPath);
		$cumulativePath = "";
		for ($i = 0; $i < sizeof($arrFolderSteps) - 1; $i++) {
			$step = $arrFolderSteps[$i];
			$cumulativePath .= $step . "/";
			if (!is_dir($cumulativePath)) {
				mkdir ($cumulativePath);
				$result .= "Created deployment destination directory " . $cumulativePath . "\r\n";
			}
		}
		
		copy($strSourcePath, $strDestinationPath);
		$result .= "Deployed file to " . $strDestinationPath . "\r\n";
		
		return $result;
	}
	
	protected static function getBeginMarker($strId) {
		return "\r\n//// BEGIN " . $strId . "\r\n";
	}
	
	protected static function getEndMarker ($strId) {
		return "//// END " . $strId . "\r\n";
	}
	
	public static function cleanupExtractedFiles($strExtractedFolderName) {
		self::deleteFolderRecursive(__INCLUDES__ . self::PLUGIN_EXTRACTION_DIR . $strExtractedFolderName);
		return "\r\nCleaned up installation files.\r\n";
	}
		
	/**
	 * Extract a ZIP compressed file to a given path
	 *
	 * @param       string  $archive        Path to ZIP archive to extract
	 * @param       string  $destination    Path to extract archive into
	 * @return      boolean True if successful
	 */
	protected static function extractZip($archive, $destination) {
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
							
							$strSectionToAppend = zip_entry_read($file, zip_entry_filesize($file));

							if (!$handle = fopen($destination . zip_entry_name($file), 'w')) {
								self::$strLastError = "Cannot open file " . destination . zip_entry_name($file);
								return false;
							}
						   
							// Write $somecontent to our opened file.
							if (fwrite($handle, $strSectionToAppend) === false) {
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
	
	protected static function deleteFolderRecursive($strPath) {
		if (!is_dir($strPath)) {
			unlink($strPath);
			return 1;
		}

		$d = dir($strPath);
		$count = 0;
		while($entry = $d->read()) { 
			if ($entry!= "." && $entry != "..") { 
				if (is_dir($strPath)) {
					$count += self::deleteFolderRecursive($strPath . "/" . $entry);
				} 
			} 
		} 

		$d->close(); 
		rmdir($strPath);
		
		return $count;
	}
	
	protected static function readFile($strFilePath) {
		$result = "";
		$handle = fopen($strFilePath, "r");
		while(!feof($handle)) {
			$result .= fread($handle, 8000);
		}		
		fclose($handle);
		
		return $result;
	}
	
	public static function writeFile($strFilePath, $strContents) {
		// Write back the file
		$fileHandle = fopen($strFilePath, "w");
		if (fwrite($fileHandle, $strContents, strlen($strContents)) == false) {
			self::$strLastError = "Unable to write file: " . $strFilePath;
			return self::$strLastError;
		}
		fclose($fileHandle);
	}
	
	protected static function stripExtraNewlines($strInput) {
		return ereg_replace("([\r\n]|\r\n){3,}", "\r\n\r\n", $strInput);
	}
}


?>