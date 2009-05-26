<?php

class QPluginConfigParser {
	private $mixPluginSet; // a single QPlugin object or an array of QPlugin objects
			
	public static function parseInstalledPlugins() {
		$obj = new QPluginConfigParser(QPluginInstaller::getMasterConfigFilePath());		
		return $obj->parseConfig();
	}
	
	public static function parseNewPlugin($strPluginName) {
		$obj = new QPluginConfigParser(self::getPathForExpandedPlugin($strPluginName));
		$tempArray = $obj->parseConfig();
		return $tempArray[0];
	}
	
	public static function getPathForExpandedPlugin($strPluginName) {
		return __INCLUDES__ . QPluginInstaller::PLUGIN_EXTRACTION_DIR .
				$strPluginName . '/' . QPluginInstaller::PLUGIN_CONFIG_FILE;
	}
	
	private function __construct($strPath) {
		if (!file_exists($strPath)) {
			throw new Exception("Plugin config file does not exist: " . $strPath);
		}
		$this->mixPluginSet = simplexml_load_file($strPath);
	}
	
	private function parseConfig() {
		$arrResult = array();
 		if (!isset($this->mixPluginSet->name)) {
			// If we are parsing a config file with multiple plugin items...
			foreach ($this->mixPluginSet as $xmlItem) {
				$arrResult[] = $this->parsePluginXmlSubsection($xmlItem);
			}
		} else {
			// If we are parsing a config file with just one plugin (no root "plugins" element)
			$arrResult[] = $this->parsePluginXmlSubsection($this->mixPluginSet);
		}
		
		return $arrResult;
	}
	
	private function parsePluginXmlSubsection($xmlPlugin) {
		$objPlugin = new QPlugin();
		$objPlugin->strName 			= (string)$xmlPlugin->name;
		$objPlugin->strDescription 		= (string)$xmlPlugin->description;
		$objPlugin->strVersion 			= (string)$xmlPlugin->version;
		$objPlugin->strPlatformVersion 	= (string)$xmlPlugin->platform_version;
		$objPlugin->strAuthorName 		= (string)$xmlPlugin->author['name'];
		$objPlugin->strAuthorEmail 		= (string)$xmlPlugin->author['email'];
		
		if (strlen($objPlugin->strName) == 0) {
			throw new Exception("Mandatory plugin parameter Name was not defined");
		}
		
		$this->parseFiles($xmlPlugin, $objPlugin);
		$this->parseIncludes($xmlPlugin, $objPlugin);
		$this->parseExamples($xmlPlugin, $objPlugin);
		
		return $objPlugin;
	}
	
	// helper to parse the /plugin/includes section of the config file
	private function parseIncludes(&$xmlPlugin, &$objPlugin) {
		foreach ($xmlPlugin->includes->include_files as $item) {
			$component = new QPluginInclude();
			$component->strFilename 	= (string)$item['filename'];
			$component->strClassname 	= (string)$item['classname'];
			
			$objPlugin->objIncludesArray [] = $component;
		}
	}
	
	// helper to parse the /plugin/examples section of the config file
	private function parseExamples(&$xmlPlugin, &$objPlugin) {
		foreach ($xmlPlugin->examples->example as $item) {
			$component = new QPluginExample();
			$component->strFilename 	= (string)$item['filename'];
			$component->strDescription 	= (string)$item['description'];
			
			$objPlugin->objExamplesArray [] = $component;
		}
	}

	// helper to parse the /plugin/files section of the config file
	private function parseFiles(&$xmlPlugin, &$objPlugin) {
		if (!isset($xmlPlugin->files)) {
			throw new Exception("Plugin that has no registered files: " . $objPlugin->strName);
		}

		foreach ($xmlPlugin->files->file as $item) {
			$component = new QPluginFile();
			$component->strFilename = (string)$item['filename'];
			
			if (!isset($item['type'])) {
				throw new Exception('Mandatory attribute "type" not set on one of the files of plugin ' . $objPlugin->strName);
			}

			switch ($item['type']) {
				case 'control':
					$objPlugin->objControlFilesArray []= $component;
					break;
				case 'misc_include':
					$objPlugin->objMiscIncludeFilesArray []= $component;
					break;
				case 'css':
					$objPlugin->objCssFilesArray []= $component;
					break;
				case 'js':
					$objPlugin->objJavascriptFilesArray []= $component;
					break;
				case 'image':
					$objPlugin->objImageFilesArray []= $component;
					break;
				case 'example':
					$objPlugin->objExampleFilesArray []= $component;
					break;
				default:
					throw new Exception("Invalid plugin component type: " . $item['type']);
			}
			
			$objPlugin->objAllFilesArray [] = $component;
		}
	}
}


class QPlugin {
	public $strName = "";
	public $strDescription = "";
	public $strVersion = "";
	public $strPlatformVersion = "";
	public $strAuthorName = "";
	public $strAuthorEmail = "";
	
	public $objControlFilesArray = array();
	public $objMiscIncludeFilesArray = array();
	public $objImageFilesArray = array();
	public $objCssFilesArray = array();
	public $objJavascriptFilesArray = array();
	public $objExampleFilesArray = array();
	
	public $objAllFilesArray = array(); // array of QPluginFile objects
	
	public $objIncludesArray = array(); // array of QPluginInclude objects
	public $objExamplesArray = array(); // array of QPluginExample objects
}

abstract class QPluginComponent {}

class QPluginFile extends QPluginComponent {
	public $strFilename;
}

class QPluginExample extends QPluginComponent {
	public $strFilename;
	public $strDescription;
}

class QPluginInclude extends QPluginComponent {
	public $strFilename;
	public $strClassname;
}

?>