<?php

class QPlugin {
	public $strName 			= null;
	public $strDescription 		= null;
	public $strVersion 			= null;
	public $strPlatformVersion 	= null;
	public $strAuthorName 		= null;
	public $strAuthorEmail 		= null;
		
	public $objAllFilesArray = array(); // array of QPluginFile objects
	public $objIncludesArray = array(); // array of QPluginIncludedClass objects
	public $objExamplesArray = array(); // array of QPluginExample objects
	
	private $validationError = null;
	
	public function addComponents($arrComponents) {
		if (is_array($arrComponents) && sizeof($arrComponents) > 0) {
			foreach ($arrComponents as $item) {
				if (!$item instanceof QPluginComponent) {
					throw new Exception("The following item is not a QPluginComponent: " . var_export($item, true));
				}
				
				if (!$item->validate()) {
					throw new Exception("The following plugin component does not validate: " . var_export($item, true));
				}
				
				if ($item instanceof QPluginFile) {
					$this->addFile($item);
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
		$savePath = __TEMP_PLUGIN_EXPANSION_DIR__ . QPluginInstaller::PLUGIN_CONFIG_FILE;
		QPluginInstaller::writeFile($savePath, $pluginConfigXml);
	}
	
	public function toXml() {
		$filesSection 		= "";
		$includesSection 	= "";
		$examplesSection 	= "";
		
		if (sizeof($this->objAllFilesArray) > 0) {
			$filesSection .= "\t<files>\r\n";
			foreach ($this->objAllFilesArray as $item) {
				$filesSection .= "\t\t<file type=\"" . $item->getType() . '" filename="' . $item->strFilename . "\" />\r\n";
			}
			$filesSection .= "\t</files>\r\n";
		}

		if (sizeof($this->objIncludesArray) > 0) {
			$includesSection .= "\t<includes>\r\n";
			foreach ($this->objIncludesArray as $item) {
				$includesSection .= "\t\t<include_files classname=\"" . $item->strClassname . '" filename="' . $item->strFilename . "\" />\r\n";
			}
			$includesSection .= "\t</includes>\r\n";
		}
		
		if (sizeof($this->objExamplesArray) > 0) {
			$examplesSection .= "\t<examples>\r\n";
			foreach ($this->objExamplesArray as $item) {
				$examplesSection .= "\t\t<example filename=\"" . $item->strFilename . '" description="' . $item->strDescription . "\" />\r\n";
			}
			$examplesSection .= "\t</examples>\r\n";
		}
		
		$result = <<<END
<plugin>
	<name>{$this->strName}</name>
	<description>{$this->strDescription}</description>
	<version>{$this->strVersion}</version>
	<platform_version>{$this->strPlatformVersion}</platform_version>
	<author name="{$this->strAuthorName}" email="{$this->strAuthorEmail}"/>
{$filesSection}{$includesSection}{$examplesSection}</plugin>
END;
	return $result;
	}
}

abstract class QPluginComponent {
	/**
	 * Returns true or false depending on if the class information is valid.
	 */
	public abstract function validate();
}

abstract class QPluginFile extends QPluginComponent {
	public $strFilename;
	
	public function __construct($strFilename = null) {
		$this->strFilename = $strFilename;
	}
	
	public function validate() {
		if ($this->strFilename == null) {
			return false;
		}
		
		return true;
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
			return false;
		}
		
		return true;
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
			return false;
		}
		
		return true;
	}
}


?>