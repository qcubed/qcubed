<?php

abstract class QInstallationValidator {
	/**
	 * Returns an array of QInstallationValidationResult objects.
	 *
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
		
		if (ini_get('magic_quotes_gpc') || ini_get('magic_quotes_runtime')) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "magic_quotes_gpc and magic_quotes_runtime " .
				"need to be disabled\r\n";
			$result[] = $obj;
		}
		
		if (!QFolder::isWritable(__INCLUDES__ . QPluginInstaller::PLUGIN_EXTRACTION_DIR)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Plugin temporary extraction directory (" .
				__INCLUDES__ . QPluginInstaller::PLUGIN_EXTRACTION_DIR . ") needs to be writable";
			$obj->strCommandToFix = "chmod 777 " . __INCLUDES__ . QPluginInstaller::PLUGIN_EXTRACTION_DIR;
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

		if (!QFolder::isWritable(__META_CONTROLS__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "MetaControls destination directory (" . __META_CONTROLS__ . ") " .
				"needs to be writable for the code generator to work";
			$obj->strCommandToFix = "chmod 777 " . __META_CONTROLS__;
			$result[] = $obj;
		}
		
		if (!QFolder::isWritable(__META_CONTROLS_GEN__)) {
			$obj = new QInstallationValidationResult();
			$obj->strMessage = "Generated MetaControls directory (" . __META_CONTROLS_GEN__ . ") " .
				"needs to be writable for the code generator to work";
			$obj->strCommandToFix = "chmod 777 " . __META_CONTROLS_GEN__;
			$result[] = $obj;
		}
		
		// Database connection string checks
		for ($i = 1; $i < 1 + sizeof(QApplication::$Database); $i++) {
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