<?php

class QPluginInstaller {
	private static $strLastError = "";
	const PLUGIN_EXTRACTION_DIR = "/tmp/plugin.tmp/";
	/**
	 * @var string Name of the file that each plugin should have - that file defines plugin settings.
	 */
	const PLUGIN_CONFIG_FILE = "plugin.xml"; 
	
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
		$objPlugin = QPluginConfigParser::parseNewPlugin($strExtractedFolderName);
		
		$strStatus = "Installing plugin " . $objPlugin->strName . "\r\n\r\n";
		if (self::isPluginInstalled($objPlugin->strName)) {
			self::$strLastError = "Plugin with the same name is already installed - aborting";
			$strStatus .= self::$strLastError;
		} else {		
			$strStatus .= self::appendPluginConfigToMasterConfig($strExtractedFolderName);
			$strStatus .= self::deployFilesForNewPlugin($objPlugin, $strExtractedFolderName);
			$strStatus .= self::appendClassFileReferences($objPlugin, $strExtractedFolderName);
			$strStatus .= self::appendExampleFileReferences($objPlugin, $strExtractedFolderName);
		
			// When installation is done, clean up
			$strStatus .= self::cleanupExtractedFiles($strExtractedFolderName);
			
			$strStatus .= "\r\nInstallation completed successfully.";
		}
						
		echo nl2br($strStatus);
		return $strStatus;
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
	
	private static function appendPluginConfigToMasterConfig($strExtractedFolderName) {
		$strStatus = "";
		
		$configToAppendPath = QPluginConfigParser::getPathForExpandedPlugin($strExtractedFolderName);
		$file = fopen($configToAppendPath, "r");
		if (!$file) {
			self::$strLastError = "Missing plugin config file: " . $configFilePath;
			return $strStatus . self::$strLastError;
		}
		
		// Get the full contents of the configuration file that we need to append
		$configToAppend = "";
		while(!feof($file)) {
			$configToAppend .= fread($file,8000);
		}		
		fclose($file);
		
		$strStatus .= "Plugin config read\r\n";				
		
		$search = "</plugins>";
		$replace = "\r\n" . $configToAppend . "\r\n\r\n</plugins>";
		self::replaceFileSection(self::getMasterConfigFilePath(), $search, $replace);
		$strStatus .= "Plugin config appended to master config XML file successfully\r\n";
		
		return $strStatus;
	}
	
	private static function replaceFileSection($strFilePath, $strSearch, $strReplace) {
		// open the file and read its full contents 
		$fileHandle = fopen($strFilePath, "r");
		$contents = "";
		while(!feof($fileHandle)) {
			$contents .= fread($fileHandle, 8000);
		}		
		fclose($fileHandle);
		
		$contents = str_replace($strSearch, $strReplace, $contents);

		// Write back the file
		$fileHandle = fopen($strFilePath, "w");
		if (fwrite($fileHandle, $contents, strlen($contents)) == false) {
			self::$strLastError = "Unable to write file: " . $strFilePath;
			return $strStatus . self::$strLastError;
		}
		fclose($fileHandle);
	}
	
	private static function deployFilesForNewPlugin($objPlugin, $strExtractedFolderName) {
		$strStatus = "\r\nDeploying files\r\n";
				
		$createdFolders = array();
		$sourceRoot = __INCLUDES__ . QPluginInstaller::PLUGIN_EXTRACTION_DIR . $strExtractedFolderName . "/"; 
		$nonWebDestinationRoot = __PLUGINS__ . '/' . $objPlugin->strName . "/";
		$webDestinationRoot = __DOCROOT__ . __PLUGIN_ASSETS__ . '/' . $objPlugin->strName . "/";
		
		foreach ($objPlugin->objControlFilesArray as $file) {
			$strStatus .= "Control file " . $file->strFilename . "\r\n";
			$strStatus .= self::writeFileHelper($sourceRoot . $file->strFilename, $nonWebDestinationRoot . $file->strFilename);
		}
		foreach ($objPlugin->objMiscIncludeFilesArray as $file) {
			$strStatus .= "Misc include file " . $file->strFilename . "\r\n";
			$strStatus .= self::writeFileHelper($sourceRoot . $file->strFilename, $nonWebDestinationRoot . $file->strFilename);
		}
		foreach ($objPlugin->objImageFilesArray as $file) {
			$strStatus .= "Image file " . $file->strFilename . "\r\n";
			$strStatus .= self::writeFileHelper($sourceRoot . $file->strFilename, $webDestinationRoot . $file->strFilename);
		}		
		foreach ($objPlugin->objCssFilesArray as $file) {
			$strStatus .= "CSS file " . $file->strFilename . "\r\n";
			$strStatus .= self::writeFileHelper($sourceRoot . $file->strFilename, $webDestinationRoot . $file->strFilename);
		}		
		foreach ($objPlugin->objJavascriptFilesArray as $file) {
			$strStatus .= "JS file " . $file->strFilename . "\r\n";
			$strStatus .= self::writeFileHelper($sourceRoot . $file->strFilename, $webDestinationRoot . $file->strFilename);			
		}		
		foreach ($objPlugin->objExampleFilesArray as $file) {
			$strStatus .= "Example file " . $file->strFilename . "\r\n";
			$strStatus .= self::writeFileHelper($sourceRoot . $file->strFilename, $webDestinationRoot . $file->strFilename);
		}				
		
		return $strStatus;
	}
	
	/**
	 * Copies the file to the destination folder for the plugin,
	 * creating all relevant directories in the process, if necessary.
	 */
	private static function writeFileHelper($strSourcePath, $strDestinationPath) {
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
	
	private static function appendClassFileReferences($objPlugin, $strExtractedFolderName) {
		$strStatus = "\r\nConfiguring class file references\r\n";

		$strSectionToAppend = self::getBeginMarker($objPlugin->strName);
		foreach ($objPlugin->objIncludesArray as $file) {
			$strStatus .= "Include reference to class " . $file->strClassname . " in file " . $file->strFilename . "\r\n";
			$strSectionToAppend .= "QApplicationBase::\$ClassFile['" . strtolower($file->strClassname) .
					   "'] = __PLUGINS__ . '/" . $objPlugin->strName . "/" . $file->strFilename . "';\r\n";
		}
		$strSectionToAppend .= self::getEndMarker($objPlugin->strName);
		
		$search = "?>";
		$replace = $strSectionToAppend . "\r\n?>";
		self::replaceFileSection(self::getMasterIncludeFilePath(), $search, $replace);

		return $strStatus;
	}
	
	private static function appendExampleFileReferences($objPlugin, $strExtractedFolderName) {
		$strStatus = "\r\nConfiguring example file references\r\n";

		$strSectionToAppend = self::getBeginMarker($objPlugin->strName);
		foreach ($objPlugin->objExamplesArray as $file) {
			$strStatus .= "Include reference to example '" . $file->strDescription . "' in file " . $file->strFilename . "\r\n";
			$strSectionToAppend .= "Examples::AddPluginExampleFile('" . $objPlugin->strName . "', '" .
				$file->strFilename . " " . $file->strDescription . "');\r\n";
		}
		$strSectionToAppend .= self::getEndMarker($objPlugin->strName);
		
		$search = "?>";
		$replace = $strSectionToAppend . "\r\n?>";
		self::replaceFileSection(self::getMasterExamplesFilePath(), $search, $replace);

		return $strStatus;
	}


	private static function getBeginMarker($strId) {
		return "\r\n//// BEGIN " . $strId . "\r\n";
	}
	
	private static function getEndMarker ($strId) {
		return "//// END " . $strId . "\r\n";
	}
	
	public static function cleanupExtractedFiles($strExtractedFolderName) {
		self::deleteFolderRecursive(__INCLUDES__ . self::PLUGIN_EXTRACTION_DIR . $strExtractedFolderName);
		return "\r\nCleaned up installation files.\r\n";
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