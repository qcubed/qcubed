<?php
require('QString.class.php');

abstract class QInstallationValidator {
	/**
	 * @return array an array of QInstallationValidationResult objects
	 * If no errors were found, the array is empty.
	 */
	public static function Validate() {
		$result = array();
		
		if(ini_get('safe_mode') ){
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Safe Mode is deprecated in PHP 5.3+ and is removed in PHP 6.0+." . 
				"Please disable this setting in php.ini";
			$result[] = $obj;
		}
		
		if (get_magic_quotes_gpc() || get_magic_quotes_runtime()) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "magic_quotes_gpc and magic_quotes_runtime " .
				"need to be disabled\r\n";
			$result[] = $obj;
		}
		
		$docrootOnlyPath = __DOCROOT__;
		$docrootWithSubdirPath = __DOCROOT__ . __DEVTOOLS_ASSETS__ . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], "/"));

		$commonSubsequence = QString::LongestCommonSubsequence($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_FILENAME']);
		$root = substr($_SERVER['SCRIPT_FILENAME'], 0, strlen($_SERVER['SCRIPT_FILENAME']) - strlen($commonSubsequence));
		$part1 = substr($_SERVER['PHP_SELF'], 1, strpos($_SERVER['PHP_SELF'], "/", 1) - 1);
		$part2 = substr($root, strrpos($root, "/") + 1);
		$virtualDir = substr($_SERVER['PHP_SELF'], 0, 0 - strlen($commonSubsequence));


		// Debugging stuff - there until this code stabilizes across multiple platforms.
	/*
		print("DOCROOT = " . __DOCROOT__ . "<br>");
		print("SUBDIR = " . __SUBDIRECTORY__ . "<br>");
		print("DEVTOOLS = " . __DEVTOOLS_ASSETS__ . "<br>");

		print("PHP_SELF = " . $_SERVER['PHP_SELF'] . "<br>");
		print("SCRIPT_FILENAME = " . $_SERVER['SCRIPT_FILENAME'] . "<br>");

		print("commonSubsequence = " . $commonSubsequence . "<br>");
		print("root = " . $root . "<br>");
		print("rootWithSubdirPath = " . $docrootWithSubdirPath . "<br>");
		print("part1 = " . $part1 . "<br>");
		print("part2 = " . $part2 . "<br>");
		print("virtualDir = " . $virtualDir . "<br>");
	//*/

		if (!is_dir($docrootOnlyPath)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = 'Set the __DOCROOT__ constant in ' .
				'/includes/configuration/configuration.inc.php. ' .
				'Most likely value: "' . $root . '"';
			$result[] = $obj;
		} else if (strlen(__VIRTUAL_DIRECTORY__) == 0 &&
				!file_exists(__DOCROOT__ . $_SERVER['PHP_SELF'])) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = 'Set the __DOCROOT__ constant in ' .
				'/includes/configuration/configuration.inc.php. ' .
				'Most likely value: "' . $root . '"';
			$result[] = $obj;
		}

		if (!file_exists($docrootWithSubdirPath)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = 'Set the __SUBDIRECTORY__ constant in ' .
				'/includes/configuration/configuration.inc.php. ' .
				'Most likely value: "/' . $part1 . '"';
			$result[] = $obj;

			// At this point, we cannot proceed with any more checks - basic config
			// is not set up. Just exit.
			return $result;
		}

		if (!file_exists(__INCLUDES__)) {
			// Did the user move the __INCLUDES__ directory out of the docroot?
			$obj = new QInstallationValidationResult();
			$obj->strMessage = 'Set the __INCLUDES__ constant in ' .
				'includes/configuration/configuration.inc.php. ';
			$result[] = $obj;

			// At this point, we cannot proceed with any more checks - basic config
			// is not set up. Just exit.
			return $result;
		}

		// Check for trailing slashes
		self::checkTrailingSlash("__DOCROOT__", $result);
		self::checkTrailingSlash("__SUBDIRECTORY__", $result);
		self::checkTrailingSlash("__VIRTUAL_DIRECTORY__", $result);

		if (strcmp($commonSubsequence, $_SERVER['PHP_SELF']) != 0 && strlen(__VIRTUAL_DIRECTORY__) == 0) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = 'Set the __VIRTUAL_DIRECTORY__ constant in ' .
				'includes/configuration/configuration.inc.php. Most likely value: "' . $virtualDir . '"';
			$result[] = $obj;
		}

		// Now that we know that the basic config is correct, we can actually
		// initialize the full QCubed framework.
		require(__CONFIGURATION__. '/prepend.inc.php');
/*
		if (!QFolder::isWritable(QPluginInstaller::PLUGIN_EXTRACTION_DIR)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Plugin temporary extraction directory (" .
				QPluginInstaller::PLUGIN_EXTRACTION_DIR . ") needs to be writable";
			$obj->strCommandToFix = "chmod 777 " . QPluginInstaller::PLUGIN_EXTRACTION_DIR;
			$result[] = $obj;
		}
		
		// Checks to make sure that everything about plugins is allright
		if (!QFile::isWritable(QPluginInstaller::getMasterConfigFilePath())) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Plugin master configuration file (" .
				QPluginInstaller::getMasterConfigFilePath() . ") needs to be writable";
			$obj->strCommandToFix = "chmod 777 " . QPluginInstaller::getMasterConfigFilePath();
			$result[] = $obj;
		}

		if (!QFile::isWritable(QPluginInstaller::getMasterExamplesFilePath())) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Plugin example configuration file (" .
				QPluginInstaller::getMasterExamplesFilePath() . ") needs to be writable";
			$obj->strCommandToFix = "chmod 777 " . QPluginInstaller::getMasterExamplesFilePath();
			$result[] = $obj;
		}

		if (!QFile::isWritable(QPluginInstaller::getMasterIncludeFilePath())) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Plugin includes configuration file (" .
				QPluginInstaller::getMasterIncludeFilePath() . ") needs to be writable";
			$obj->strCommandToFix = "chmod 777 " . QPluginInstaller::getMasterIncludeFilePath();
			$result[] = $obj;
		}
					
		if (!QFolder::isWritable(__PLUGINS__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Plugin includes installation directory (" .
				__PLUGINS__ . ") needs to be writable";
			$obj->strCommandToFix = "chmod 777 " . __PLUGINS__;
			$result[] = $obj;
		}

		if (!QFolder::isWritable(__DOCROOT__ . __PLUGIN_ASSETS__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Plugin assets installation directory (" .
				__DOCROOT__ . __PLUGIN_ASSETS__ . ") needs to be writable";
			$obj->strCommandToFix = "chmod 777 " . __DOCROOT__ . __PLUGIN_ASSETS__;
			$result[] = $obj;
		}
		*/
		if (!QFolder::isWritable(__CACHE__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Cache directory (" . __CACHE__ . ") needs to be writable";
			$obj->strCommandToFix = "chmod 777 " . __CACHE__;
			$result[] = $obj;
		}
		
		if (!function_exists('zip_open')) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "ZIP extension is not enabled on this installation of PHP. " .
				"This extension is required to be able to install plugins. " .
				"To make it work on Linux/MacOS, recompile your installation of PHP with --enable-zip parameter. " .
				"On Windows, enable extension=php_zip.dll in php.ini.";
			$result[] = $obj;
		}
		
		// Checks to make sure that all codegen-related folders are good to go
		if (!QFolder::isWritable(__DOCROOT__ . __FORM_DRAFTS__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Form drafts directory (" . __DOCROOT__ . __FORM_DRAFTS__ . ") " .
				"needs to be writable for the code generator to work";
			$obj->strCommandToFix = "chmod 777 " . __DOCROOT__ . __FORM_DRAFTS__;
			$result[] = $obj;
		}

		if (!QFolder::isWritable(__DOCROOT__ . __PANEL_DRAFTS__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Panel drafts directory (" . __DOCROOT__ . __PANEL_DRAFTS__ . ") " .
				"needs to be writable for the code generator to work";
			$obj->strCommandToFix = "chmod 777 " . __DOCROOT__ . __PANEL_DRAFTS__;
			$result[] = $obj;
		}
		
		if (!QFolder::isWritable(__FORMBASE_CLASSES__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Generated form base classes directory (" . __FORMBASE_CLASSES__ . ") " .
				"needs to be writable for the code generator to work";
			$obj->strCommandToFix = "chmod 777 " . __FORMBASE_CLASSES__;
			$result[] = $obj;
		}
		
		if (!QFolder::isWritable(__MODEL__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Model destination directory (" . __MODEL__ . ") " .
				"needs to be writable for the code generator to work";
			$obj->strCommandToFix = "chmod 777 " . __MODEL__;
			$result[] = $obj;
		}

		if (!QFolder::isWritable(__MODEL_GEN__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Generated model destination directory (" . __MODEL_GEN__ . ") " .
				"needs to be writable for the code generator to work";
			$obj->strCommandToFix = "chmod 777 " . __MODEL_GEN__;
			$result[] = $obj;
		}

		if (!QFolder::isWritable(__MODEL_CONNECTOR__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "ModelConnector destination directory (" . __MODEL_CONNECTOR__ . ") " .
				"needs to be writable for the code generator to work";
			$obj->strCommandToFix = "chmod 777 " . __MODEL_CONNECTOR__;
			$result[] = $obj;
		}
		
		if (!QFolder::isWritable(__MODEL_CONNECTOR_GEN__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Generated ModelConnector directory (" . __MODEL_CONNECTOR_GEN__ . ") " .
				"needs to be writable for the code generator to work";
			$obj->strCommandToFix = "chmod 777 " . __MODEL_CONNECTOR_GEN__;
			$result[] = $obj;
		}
		
		// Database connection string checks
		for ($i = 1; $i < 1 + sizeof(QApplication::$Database); $i++) {
			if (!isset(QApplication::$Database[$i]))
				continue;
			$db = QApplication::$Database[$i];
			// database connection problems are PHP Errors, not exceptions
			// using an intermediate error handler to make them into
			// exceptions so that we can catch them locally
			set_error_handler("__database_check_error");
			try {
				$db->Connect();
			} catch (Exception $e) {
				$obj = new QInstallationValidationResult();
				$obj->strMessage = "Fix database configuration settings in " .
					__CONFIGURATION__ . "/configuration.inc.php for adapter #"
					. $i . ": " . $e->getMessage();
				$result[] = $obj;
			}
			restore_error_handler();
		}
		
		return $result;
	}

	public static function checkTrailingSlash($strConstantName, & $result) {
		if (QString::LastCharacter(constant($strConstantName)) == '/') {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = 'Remove the trailing slash from the ' . $strConstantName . ' constant in ' .
				'/includes/configuration/configuration.inc.php. ';
			$result[] = $obj;
		}
	}

}

function __database_check_error($errno, $errstr, $errfile, $errline, $errcontext) {
	throw new Exception(strip_tags($errstr));
}

class QInstallationValidationResult {
	/**
	 * A string that represents an error that has been
	 * found for this installation. If no errors were found, the array
	 * is empty.
	 */
	public $strMessage = "";

	/**
	 * A hint of the bash script that can be used to fix the issues.
	 * May or may not be set, depending on whether there's an
	 * automated way to fix these.
	 */
	public $strCommandToFix = "";
}


?>
