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
			$row = new QPluginConfigFileItem();
			$row->strName = (string)$plugin->name;
			$row->strDescription = (string)$plugin->description;
			
			if (isset($plugin->controls)) {
				foreach ($plugin->controls->file as $item) {
					$component = new stdclass();
					$component->file = $item['filename'];
					$component->class = $item['class'];
					
					$row->objControlsArray []= $component;
				}
			}

			if (isset($plugin->misc_includes)) {
				foreach ($plugin->misc_includes->file as $item) {
					$component = new stdclass();
					$component->file = $item['filename'];

					$row->objMiscIncludesArray []= $component;
				}
			}
			
			if (isset($plugin->images)) {
				foreach ($plugin->images->file as $pluginControl) {
					$component = new stdclass();
					$component->file = $item['filename'];
										
					$row->objImagesArray []= $component;
				}
			}

			if (isset($plugin->css)) {
				foreach ($plugin->css->file as $pluginControl) {
					$component = new stdclass();
					$component->file = $item['filename'];

					$row->objCssArray []= $component;
				}
			}

			if (isset($plugin->javascript)) {
				foreach ($plugin->javascript->file as $pluginControl) {
					$component = new stdclass();
					$component->file = $item['filename'];

					$row->objJavascriptArray []= $component;
				}
			}

			if (isset($plugin->examples)) {
				foreach ($plugin->examples->file as $item) {
					$component = new stdclass();
					$component->file = $item['filename'];

					$row->objExamplesArray []= $component;
				}
			}
			
			$arrResult[] = $row;
		}
		
		return $arrResult;
	}
}


class QPluginConfigFileItem {
	public $strName = "";
	public $strDescription = "";
	public $objControlsArray = array();
	public $objMiscIncludesArray = array();
	public $objImagesArray = array();
	public $objCssArray = array();
	public $objJavascriptArray = array();
	public $objExamplesArray = array();
}

?>