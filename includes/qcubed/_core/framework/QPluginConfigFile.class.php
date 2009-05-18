<?php

class QPluginConfigFile { // Singleton
	private $objPluginArray;
	private static $objInstance = null; 
	
	public static function parse() {
		self::$objInstance = new QPluginConfigFile();
		
		return self::$objInstance->parseConfig();
	}
	
	private function __construct() {
		$this->objPluginArray = simplexml_load_file(__PLUGINS__ . '/plugin_config.xml');
	}
	
	private function parseConfig() {
		$arrResult = array();
		foreach ($this->objPluginArray as $plugin) {
			$row = new QPlugin();
			$row->strName = (string)$plugin->name;
			$row->strDescription = (string)$plugin->description;
			$row->strVersion = (string)$plugin->version;
			$row->strPlatformVersion = (string)$plugin->platform_version;
			$row->strAuthorName = (string)$plugin->author['name'];
			$row->strAuthorEmail = (string)$plugin->author['email'];
			
			$this->parseFiles($plugin, $row);
			$this->parseIncludes($plugin, $row);
			$this->parseExamples($plugin, $row);
			
			$arrResult[] = $row;
		}
		
		return $arrResult;
	}
	
	// helper to parse the /plugin/includes section of the config file
	private function parseIncludes(&$plugin, &$row) {
		foreach ($plugin->includes->include_files as $item) {
			$component = new QPluginInclude();
			$component->strFilename = $item['filename'];
			$component->strClassname = $item['classname'];
			
			$row->objIncludesArray [] = $component;
		}
	}
	
	// helper to parse the /plugin/examples section of the config file
	private function parseExamples(&$plugin, &$row) {
		foreach ($plugin->examples->example as $item) {
			$component = new QPluginExample();
			$component->strFilename = $item['filename'];
			$component->strDescription = $item['description'];
			
			$row->objExamplesArray [] = $component;
		}
	}

	// helper to parse the /plugin/files section of the config file
	private function parseFiles(&$plugin, &$row) {
		if (!isset($plugin->files)) {
			throw new Exception("Plugin that has no registered files: " . $row->strName);
		}

		foreach ($plugin->files->file as $item) {
			$component = new QPluginFile();
			$component->strFilename = $item['filename'];
			
			if (!isset($item['type'])) {
				throw new Exception('Mandatory attribute "type" not set on one of the files of plugin ' . $row->strName);
			}

			switch ($item['type']) {
				case 'control':
					$row->objControlFilesArray []= $component;
					break;
				case 'misc_include':
					$row->objMiscIncludeFilesArray []= $component;
					break;
				case 'css':
					$row->objCssFilesArray []= $component;
					break;
				case 'js':
					$row->objJavascriptFilesArray []= $component;
					break;
				case 'image':
					$row->objImageFilesArray []= $component;
					break;
				case 'example':
					$row->objExampleFilesArray []= $component;
					break;
				default:
					throw new Exception("Invalid plugin component type: " . $item['type']);
			}
			
			$row->objAllFilesArray [] = $component;
		}
	}
}


class QPlugin{
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