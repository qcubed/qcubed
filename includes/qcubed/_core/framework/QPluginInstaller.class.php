<?php

abstract class QPluginInstaller extends QPluginInstallerBase {
	protected static $strLastError;
		
	public static function getLastError() {
		return self::$strLastError;
	}
	
	public static function installPluginFromZip($strFileName) {
		$entropy = substr(md5(uniqid()), 0, 6);
		$expandedDir = __INCLUDES__ . self::PLUGIN_EXTRACTION_DIR . $entropy . '/';
		$extractionResult = QArchive::extractZip($strFileName, $expandedDir);
		if (!$extractionResult) {
			self::$strLastError = "Could not extract the plugin zip archive";
			return null;
		}
		
		// Check to see if plugin config is defined as a PHP file,
		// not XML file - and if so, run the PHP file to generate an XML config
		// file. 
		if (file_exists($expandedDir . self::PLUGIN_CONFIG_GENERATION_FILE)) {
			// we'll need this constant to know where to save the XML config file
			global $__PLUGIN_FILES_DIR__;
			$__PLUGIN_FILES_DIR__= $expandedDir;
			// execute the configuration file from the plugin - it will create a plugin
			// config file in the XML format which we will process
			include($expandedDir . self::PLUGIN_CONFIG_GENERATION_FILE);
		}
		
		return $entropy;		
	}
	
	private static function getExpandedPath($strExtractedFolderName) {
		return __INCLUDES__ . self::PLUGIN_EXTRACTION_DIR . $strExtractedFolderName . '/';
	}	

	public static function installFromExpanded($strExtractedFolderName) {
		$expandedDir = self::getExpandedPath($strExtractedFolderName);
		$objPlugin = QPluginConfigParser::parseNewPlugin($expandedDir . self::PLUGIN_CONFIG_FILE);

		$strStatus = "Installing plugin " . $objPlugin->strName . "\r\n\r\n";

		if (self::isPluginInstalled($objPlugin->strName)) {
			$strStatus .= "Plugin with the same name is already installed - aborting";
			return $strStatus;
		}

		$strStatus .= self::appendPluginConfigToMasterConfig($strExtractedFolderName);
		$strStatus .= self::deployFilesForNewPlugin($objPlugin, $strExtractedFolderName);
		$strStatus .= self::appendClassFileReferences($objPlugin, $strExtractedFolderName);
		$strStatus .= self::appendExampleFileReferences($objPlugin, $strExtractedFolderName);
	
		// When installation is done, clean up
		$strStatus .= self::cleanupExtractedFiles($strExtractedFolderName);

		$strStatus .= "\r\nInstallation completed successfully.";

		return $strStatus;
	}
	
	private static function deployFilesForNewPlugin($objPlugin, $strExtractedFolderName) {
		$strStatus = "\r\nDeploying files\r\n";
				
		$sourceRoot = self::getExpandedPath($strExtractedFolderName);
		$nonWebDestinationRoot = __PLUGINS__ . '/' . $objPlugin->strName . "/";
		$webDestinationRoot = __DOCROOT__ . __PLUGIN_ASSETS__ . '/' . $objPlugin->strName . "/";
		
		foreach ($objPlugin->objAllFilesArray as $file) {
			$strStatus .= get_class($file) . " " . $file->strFilename . " deployed\r\n";
			if ($file instanceof QPluginNonWebAccessibleFile) {
				$destinationDir = $nonWebDestinationRoot;
			} elseif ($file instanceof QPluginWebAccessibleFile) {
				$destinationDir = $webDestinationRoot;
			} else {
				$strStatus .= "Invalid component type: " . var_export($file, true);
				continue;
			}
			
			self::writeFileHelper($sourceRoot . $file->strFilename,  $destinationDir . $file->strFilename);
		}
		
		return $strStatus;
	}	

	private static function appendClassFileReferences($objPlugin, $strExtractedFolderName) {
		$strStatus = "\r\nConfiguring class file references\r\n";

		$strSectionToAppend = self::getBeginMarker($objPlugin->strName);
		foreach ($objPlugin->objIncludesArray as $file) {
			$strStatus .= "Included reference to class " . $file->strClassname . " in file " . $file->strFilename . "\r\n";
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
			$strStatus .= "Included reference to example '" . $file->strDescription . "' in file " . $file->strFilename . "\r\n";
			$strSectionToAppend .= "Examples::AddPluginExampleFile('" . $objPlugin->strName . "', '" .
				$file->strFilename . " " . $file->strDescription . "');\r\n";
		}
		$strSectionToAppend .= self::getEndMarker($objPlugin->strName);
		
		$search = "?>";
		$replace = $strSectionToAppend . "\r\n?>";
		self::replaceFileSection(self::getMasterExamplesFilePath(), $search, $replace);

		return $strStatus;
	}
	
	private static function appendPluginConfigToMasterConfig($strExtractedFolderName) {
		$strStatus = "";
		
		$configToAppendPath = self::getExpandedPath($strExtractedFolderName) . self::PLUGIN_CONFIG_FILE;
		// Get the full contents of the configuration file that we need to append
		$configToAppend = QFile::readFile($configToAppendPath);
		
		$strStatus .= "Plugin config read\r\n";				
		
		$search = "</plugins>";
		$replace = "\r\n" . $configToAppend . "\r\n\r\n</plugins>";
		self::replaceFileSection(self::getMasterConfigFilePath(), $search, $replace);
		$strStatus .= "Plugin config appended to master config XML file successfully\r\n";
		
		return $strStatus;
	}
}

?>