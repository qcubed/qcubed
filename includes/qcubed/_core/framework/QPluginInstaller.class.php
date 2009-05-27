<?php

abstract class QPluginInstaller extends QPluginInstallerBase {
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
	
	private static function appendPluginConfigToMasterConfig($strExtractedFolderName) {
		$strStatus = "";
		
		$configToAppendPath = QPluginConfigParser::getPathForExpandedPlugin($strExtractedFolderName);
		// Get the full contents of the configuration file that we need to append
		$configToAppend = self::readFile($configToAppendPath);
		
		$strStatus .= "Plugin config read\r\n";				
		
		$search = "</plugins>";
		$replace = "\r\n" . $configToAppend . "\r\n\r\n</plugins>";
		self::replaceFileSection(self::getMasterConfigFilePath(), $search, $replace);
		$strStatus .= "Plugin config appended to master config XML file successfully\r\n";
		
		return $strStatus;
	}
}

?>