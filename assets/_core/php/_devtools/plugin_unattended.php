<?php

require('../../../../includes/configuration/prepend.inc.php');

QApplication::CheckRemoteAdmin();

echo "<h2>Unattended Plugin Installer</h2>";
echo "<p><i>" . QDateTime::NowToString(QDateTime::FormatDisplayDateTime) . "</i></p>";

$directory = __INCLUDES__ . '/tmp/plugin.install';
$arrFiles = QFolder::listFilesInFolder($directory, false, "/\.zip$/i");

if (sizeof($arrFiles) > 0) {
	foreach ($arrFiles as $strFile) {
		echo "<h2>Installing " . $strFile . "</h2>";
		$fullFilePath = $directory . '/' . $strFile;
		try {
			$pluginFolder = QPluginInstaller::installPluginFromZip($fullFilePath);
			if ($pluginFolder) {
				$strLog = QPluginInstaller::installFromExpanded($pluginFolder);
			
				unlink($fullFilePath);
			}
			echo nl2br($strLog);
		} catch (Exception $e) {
			echo "Error installing the plugin: " . $e->getMessage();
		}
		
		echo "<br>";
	}
} else {
	echo "<p>No plugin zip files found in the unattended install directory: " . $directory . "</p>";
	echo "<p>Download new plugins from the <a target='_blank' href='" . QPluginInstaller::ONLINE_PLUGIN_REPOSITORY . "'>" . 
		"Online repository of QCubed plugins</a></p>"; 
}

?>