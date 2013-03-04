<?php
/**
 * @package PluginManager
 * @author Alex Weinstein <alex94040@yahoo.com>
 */

/**
 * This class takes care of parsing XML configuration files of the plugins. 
 * Note that even if a plugin doesn't explicitly have an XML config file, it will 
 * get generated by the install.php script of the plugin. 
 */
class QPluginConfigParser {
	private $mixPluginSet; // a single QPlugin object or an array of QPlugin objects
			
	public static function parseInstalledPlugins() {
		$obj = new QPluginConfigParser(QPluginInstaller::getMasterConfigFilePath());		
		return $obj->parseConfig();
	}
	
	public static function parseNewPlugin($strExpandedPath) {
		$obj = new QPluginConfigParser($strExpandedPath);
		$tempArray = $obj->parseConfig();
		return $tempArray[0];
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
		$objPlugin = new QPlugin(false);
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
		if (!is_null($xmlPlugin->includes->include_files)) {
			foreach ($xmlPlugin->includes->include_files as $item) {
				$component = new QPluginIncludedClass();
				$component->strFilename 	= (string)$item['filename'];
				$component->strClassname 	= (string)$item['classname'];

				$objPlugin->objIncludesArray [] = $component;
			}
		}
	}
	
	// helper to parse the /plugin/examples section of the config file
	private function parseExamples(&$xmlPlugin, &$objPlugin) {
		if (!is_null($xmlPlugin->examples->example)) {
			foreach ($xmlPlugin->examples->example as $item) {
				$component = new QPluginExample();
				$component->strFilename 	= (string)$item['filename'];
				$component->strDescription 	= (string)$item['description'];

				$objPlugin->objExamplesArray [] = $component;
			}
		}
	}

	// helper to parse the /plugin/files section of the config file
	private function parseFiles(&$xmlPlugin, &$objPlugin) {
		if (!isset($xmlPlugin->files)) {
			throw new Exception("Plugin that has no registered files: " . $objPlugin->strName);
		}

		foreach ($xmlPlugin->files->file as $item) {
			if (!isset($item['type'])) {
				throw new Exception('Mandatory attribute "type" not set on one of the files of plugin ' . $objPlugin->strName);
			}

			switch ($item['type']) {
				case 'control':
					$component = new QPluginControlFile();
					break;
				case 'misc_include':
					$component = new QPluginMiscIncludedFile();
					break;
				case 'css':
					$component = new QPluginCssFile();
					break;
				case 'js':
					$component = new QPluginJsFile();
					$objPlugin->objJavascriptFilesArray []= $component;
					break;
				case 'image':
					$component = new QPluginImageFile();
					break;
				case 'example':
					$component = new QPluginExampleFile();
					break;
				default:
					throw new Exception("Invalid plugin component type: " . $item['type']);
			}
			
			$component->strFilename = (string)$item['filename'];
			$objPlugin->objAllFilesArray [] = $component;
		}
	}
}

?>