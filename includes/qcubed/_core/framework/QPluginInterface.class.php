<?php
/**
 * @package PluginManager
 * @author Alex Weinstein <alex94040@yahoo.com>
 */

/**
 * This class should be used by plugin writers to create their own
 * plugin descriptors in the install.php scripts of every plugin. 
 * Details on creating an install script are here: http://examples.qcu.be/assets/_core/php/examples/plugins/components.php
 *
 * Whenever you use the install.php script, the plugin manager will run the script, 
 * and then internally generate an XML descriptor of the plugin before actually
 * installing it. Note that this XML descriptor can be used directly, in place of
 * the install.php configuration script, in order to install the plugin. 
 */
class QPlugin {
	/**
	 * Properties of the plugin that can and should be set
	 * when instantiating a new plugin.
	 */
	public $strName = null;
	public $strDescription = null;
	public $strVersion = null;
	public $strPlatformVersion = null;
	public $strAuthorName = null;
	public $strAuthorEmail = null;
		
	// private state - do not modify
	public $objAllFilesArray = array(); // array of QPluginFile objects
	public $objIncludesArray = array(); // array of QPluginIncludedClass objects
	public $objExamplesArray = array(); // array of QPluginExample objects

	private $strValidationError = null;	
	public $strTemporaryExpandedPath = null;
	
	public function __construct($blnInstalling = true) {
		if ($blnInstalling) {
			global $__PLUGIN_FILES_DIR__;
			$this->strTemporaryExpandedPath = $__PLUGIN_FILES_DIR__;
		}
	}
	
	public function addComponents($arrComponents) {
		if (is_array($arrComponents) && sizeof($arrComponents) > 0) {
			foreach ($arrComponents as $item) {
				if (!$item instanceof QPluginComponent) {
					throw new Exception("The following item is not a QPluginComponent: " . var_export($item, true));
				}
				
				// Make the component aware of its hosting QPlugin
				$item->registerPlugin($this);
				
				if (!$item->validate()) {
					throw new Exception("The following plugin component does not validate: " . $item->__toString() . ". Error: " . $item->strValidationError);
				}
				
				if ($item instanceof QPluginFile) {
					$fullPath = $this->strTemporaryExpandedPath . $item->strFilename;
					if (is_dir($fullPath)) {
						$folderContents = QFolder::listFilesInFolder($fullPath);
						foreach ($folderContents as $file) {
							$itemClass = get_class($item);
							$newComponent = new $itemClass($item->strFilename . "/" . $file);
							$this->addFile($newComponent);
						}
					} else {
						$this->addFile($item);
					}
				} else if ($item instanceof QPluginIncludedClass) {
					$this->addIncludedClass($item);
				} else if ($item instanceof QPluginExample) {
					$this->addExample($item);
				} else {
					throw new Exception("Unknown item type: " . var_export($item, true));
				}
			}
		} else {
			throw new Exception("addFiles only accepts an array of QPluginFile objects");
		}
	}
	
	public function addFile(QPluginFile $objComponent) {
		$this->objAllFilesArray[] = $objComponent;
	}
	
	public function addIncludedClass(QPluginIncludedClass $objComponent) {
		$this->objIncludesArray[] = $objComponent;
	}
	
	public function addExample(QPluginExample $objComponent) {
		$this->objExamplesArray[] = $objComponent;
	}
	
	public function validate() {
		$result = true;
		if ($this->strName == null) {
			$result = false;
			$this->validationError .= "strName parameter must be set\r\n";
		}
		
		if (substr_count($this->strName, " ") > 0) {
			$result = false;
			$this->validationError .= "strName parameter must not contain spaces\r\n";
		}
		
		if ($this->strDescription == null) {
			$result = false;
			$this->validationError .= "strDescription parameter must be set\r\n";
		}
		if ($this->strVersion == null) {
			$result = false;
			$this->validationError .= "strVersion parameter (version of the plugin) must be set\r\n";
		}
		if ($this->strPlatformVersion == null) {
			$result = false;
			$this->validationError .= "strPlatformVersion parameter must be set\r\n";
		}

		return $result;
	}
	
	public function install() {
		if (!$this->validate()) {
			throw new QCallerException("The plugin doesn't validate: \r\n" . $this->validationError);
		}
		
		$pluginConfigXml = $this->toXml();		
		$savePath = $this->strTemporaryExpandedPath . QPluginInstaller::PLUGIN_CONFIG_FILE;
		QFile::writeFile($savePath, $pluginConfigXml);
	}
	
	public function toXml() {
		$filesSection 		= "";
		$includesSection 	= "";
		$examplesSection 	= "";
		
		if (sizeof($this->objAllFilesArray) > 0) {
			$filesSection .= "\t<files>\r\n";
			foreach ($this->objAllFilesArray as $item) {
				$filesSection .= "\t\t<file type=\"" . QPlugin::escapeAttribute($item->getType()) . '" filename="' . QPlugin::escapeAttribute($item->strFilename) . "\" />\r\n";
			}
			$filesSection .= "\t</files>\r\n";
		}

		if (sizeof($this->objIncludesArray) > 0) {
			$includesSection .= "\t<includes>\r\n";
			foreach ($this->objIncludesArray as $item) {
				$includesSection .= "\t\t<include_files classname=\"" . QPlugin::escapeAttribute($item->strClassname) . '" filename="' . QPlugin::escapeAttribute($item->strFilename) . "\" />\r\n";
			}
			$includesSection .= "\t</includes>\r\n";
		}
		
		if (sizeof($this->objExamplesArray) > 0) {
			$examplesSection .= "\t<examples>\r\n";
			foreach ($this->objExamplesArray as $item) {
				$examplesSection .= "\t\t<example filename=\"" . QPlugin::escapeAttribute($item->strFilename) . '" description="' . QPlugin::escapeAttribute($item->strDescription) . "\" />\r\n";
			}
			$examplesSection .= "\t</examples>\r\n";
		}
		
		$strName = QPlugin::escapeCData($this->strName);
		$strDescription = QPlugin::escapeCData($this->strDescription);
		$strVersion = $this->strVersion;
		$strPlatformVersion = $this->strPlatformVersion;
		$strAuthorName = QPlugin::escapeAttribute($this->strAuthorName);
		$strAuthorEmail = QPlugin::escapeAttribute($this->strAuthorEmail);

		$result = <<<END
<plugin>
	<name>{$strName}</name>
	<description>{$strDescription}</description>
	<version>{$strVersion}</version>
	<platform_version>{$strPlatformVersion}</platform_version>
	<author name="{$strAuthorName}" email="{$strAuthorEmail}"/>
{$filesSection}{$includesSection}{$examplesSection}</plugin>
END;
	return $result;
	}
	
	private static function escapeAttribute($strAttributeValue)	{
		$strAttributeValue = str_replace("&", "&amp;", $strAttributeValue); 
		$strAttributeValue = str_replace("<", "&lt;", $strAttributeValue); 
		$strAttributeValue = str_replace(">", "&gt;", $strAttributeValue); 
		$strAttributeValue = str_replace("\"", "&quot;", $strAttributeValue);
		$strAttributeValue = str_replace("'", "&apos;", $strAttributeValue);
		return $strAttributeValue;
	}
	
	private static function escapeCData($value)	{
		//CData can't have ]]> in it
		$value = str_replace("]]>", "]]&gt;", $value); 
		return '<![CDATA['.$value.']]>';
	}
}

abstract class QPluginComponent {
	/**
	 * Returns true or false depending on if the class information is valid.
	 */
	public abstract function validate();
	
	public $strValidationError;
	
	protected $objPlugin;
	
	public function registerPlugin(QPlugin $objPlugin) {
		$this->objPlugin = $objPlugin;
	}
}

abstract class QPluginFile extends QPluginComponent {
	public $strFilename;
	
	public function __construct($strFilename = null) {
		$this->strFilename = $strFilename;
	}
	
	public function validate() {
		if ($this->strFilename == null) {
			$this->strValidationError = "Filename is not set";
			return false;
		}
		
		if (!file_exists($this->objPlugin->strTemporaryExpandedPath . $this->strFilename)) {
			$this->strValidationError = "File does not exist";
			return false;				
		}
		return true;
	}
	
	public function __toString() {
		return "QPluginFile " . $this->strFilename;
	}
	
	/**
	 * When serializing this component into XML, what is the value of
	 * the "type" attribute? For example:
	 *
	 * <file type=">>>TYPE_VALUE<<<" filename="includes/a.php" />
	 *
	 */
	public abstract function getType();
}

abstract class QPluginWebAccessibleFile extends QPluginFile {}
abstract class QPluginNonWebAccessibleFile extends QPluginFile {}

class QPluginControlFile extends QPluginNonWebAccessibleFile {
	public function getType() { return "control"; } 
}
class QPluginMiscIncludedFile extends QPluginNonWebAccessibleFile {
	public function getType() { return "misc_include"; } 	
}

class QPluginCssFile extends QPluginWebAccessibleFile {
	public function getType() { return "css"; } 	
}
class QPluginJsFile extends QPluginWebAccessibleFile {
	public function getType() { return "js"; } 
}
class QPluginImageFile extends QPluginWebAccessibleFile {
	public function getType() { return "image"; } 
}
class QPluginExampleFile extends QPluginWebAccessibleFile {
	public function getType() { return "example"; } 
}


class QPluginExample extends QPluginComponent {
	public $strFilename;
	public $strDescription;
	
	public function __construct($strFilename = null, $strDescription = null) {
		$this->strFilename = $strFilename;
		$this->strDescription = $strDescription;
	}

	public function validate() {
		if ($this->strFilename == null || $this->strDescription == null) {
			$this->strValidationError = "Filename or Description are not set";
			return false;
		}
		
		return true;
	}
	
	public function __toString() {
		return "QPluginExample " . $this->strFilename;
	}
}

class QPluginIncludedClass extends QPluginComponent {
	public $strFilename;
	public $strClassname;
	
	public function __construct($strClassname = null, $strFilename = null) {
		$this->strClassname = $strClassname;
		$this->strFilename = $strFilename;
	}

	public function validate() {
		if ($this->strFilename == null || $this->strClassname == null) {
			$this->strValidationError = "Filename or Classname are not set";
			return false;
		}
		
		return true;
	}
	
	public function __toString() {
		return "QPluginIncludedClass " . $this->strClassname;
	}
}


?>