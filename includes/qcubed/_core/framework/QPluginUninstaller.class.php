<?php
/**
 * @package PluginManager
 * @author Alex Weinstein <alex94040@yahoo.com>
 */
 
/**
 * This class takes care of uninstalling existing plugins - removal of
 * files, as well as modding any configuration files that were touched
 * by the plugin. Remember - don't ever uninstall the plugin by hand. 
 * Always use this class / UI encapsulation of this class. 
 */
abstract class QPluginUninstaller extends QPluginInstallerBase {
	public static function uninstallExisting($strPluginName) {
		$strLog = "Uninstalling plugin " . $strPluginName . "\r\n\r\n";
		try {
			$blnMasterConfigUpdated 	= self::deleteFromMasterConfig($strPluginName);
			$blnIncludesFileUpdated 	= self::removeMarkedSectionHelper($strPluginName, self::getMasterIncludeFilePath());
			$blnExamplesConfigUpdated 	= self::removeMarkedSectionHelper($strPluginName, self::getMasterExamplesFilePath());
			$strDeleteStatus 			= self::deleteFiles($strPluginName);

			if ($blnMasterConfigUpdated) {
				$strLog .= "Master plugin configuration file updated\r\n";
			}
			if ($blnIncludesFileUpdated) {
				$strLog .= "Class file references updated\r\n";
			}
			if ($blnExamplesConfigUpdated) {
				$strLog .= "Examples file references updated\r\n";
			}

			$strLog .= $strDeleteStatus;
			$strStatus = "Uninstallation completed successfully.";
		} catch (Exception $ex) {
			$strStatus = "Installation failed:\r\n".$ex;
		}
		$strLog .= "\r\n".$strStatus;
		return array($strStatus, $strLog);
	}
	
	private static function deleteFiles($strPluginName) {		
		$strResult = "\r\nDeleting plugin files:\r\n";

		$assetsPath = __DOCROOT__ . __PLUGIN_ASSETS__ . '/' . $strPluginName;
		if (file_exists($assetsPath)) {
			$deletedItems = QFolder::DeleteFolder($assetsPath);
			$strResult .= "- Deleted " . $deletedItems . " files from the plugin assets directory\r\n";
		} else {
			$strResult .= "- Nothing was deleted from the plugin assets directory\r\n";
		}
		
		$includesPath = __PLUGINS__ . '/' . $strPluginName;
		if (file_exists($includesPath)) {
			$deletedItems = QFolder::DeleteFolder($includesPath);
			$strResult .= "- Deleted " . $deletedItems . " files from the plugin includes directory\r\n";
		} else {
			$strResult .= "- Nothing was deleted from the plugin includes directory\r\n";
		}
		
		return $strResult;
	}
	
	private static function deleteFromMasterConfig($strPluginName) {
		$oldContents = QFile::readFile(self::getMasterConfigFilePath());
		
		$doc = new SimpleXMLElement($oldContents);
		$found = false;
		foreach($doc as $plugin) {
			if($plugin->name == $strPluginName) {
				$dom = dom_import_simplexml($plugin);
				$dom->parentNode->removeChild($dom);
				$found = true;
				break;
			}
		}
		$newContents = $doc->asXml();
		
		$newContents = self::stripExtraNewlines($newContents);
		QFile::writeFile(self::getMasterConfigFilePath(), $newContents);
		
		return $found;
	}
	
	private static function removeMarkedSectionHelper($strPluginName, $strFileName) {
		$oldContents = QFile::readFile($strFileName);
		
		$search = str_replace("\r\n", "", self::getBeginMarker($strPluginName) . ".*" . self::getEndMarker($strPluginName));
		
		$intReplacementCount = 0;
		$newContents = preg_replace('|' . $search . '|s', '', $oldContents, -1, $intReplacementCount);
		$newContents = self::stripExtraNewlines($newContents);
		
		QFile::writeFile($strFileName, $newContents);
		
		return $intReplacementCount;
	}
}

?>