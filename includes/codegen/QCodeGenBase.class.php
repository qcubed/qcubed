<?php

	function QcubedHandleCodeGenParseError($__exc_errno, $__exc_errstr, $__exc_errfile, $__exc_errline) {
		$strErrorString = str_replace("SimpleXMLElement::__construct() [<a href='function.SimpleXMLElement---construct'>function.SimpleXMLElement---construct</a>]: ", '', $__exc_errstr);
		QCodeGen::$RootErrors .= sprintf("%s\r\n", $strErrorString);
	}

	function GO_BACK($intNumChars) {
		$content_so_far = ob_get_contents();
		ob_end_clean();
		$content_so_far = substr($content_so_far, 0, strlen($content_so_far) - $intNumChars);
		ob_start();
		print $content_so_far;
	}

	// returns true if $str begins with $sub
	function beginsWith( $str, $sub ) {
	    return ( substr( $str, 0, strlen( $sub ) ) == $sub );
	}

	// return tru if $str ends with $sub
	function endsWith( $str, $sub ) {
	    return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
	}

	// trims off x chars from the front of a string
	// or the matching string in $off is trimmed off
	function trimOffFront( $off, $str ) {
	    if( is_numeric( $off ) )
	        return substr( $str, $off );
	    else
	        return substr( $str, strlen( $off ) );
	}

	// trims off x chars from the end of a string
	// or the matching string in $off is trimmed off
	function trimOffEnd( $off, $str ) {
	    if( is_numeric( $off ) )
	        return substr( $str, 0, strlen( $str ) - $off );
	    else
	        return substr( $str, 0, strlen( $str ) - strlen( $off ) );
	}

	/**
	 * This is the CodeGen class which performs the code generation
	 * for both the Object-Relational Model (e.g. Data Objects) as well as
	 * the draft Forms, which make up simple HTML/PHP scripts to perform
	 * basic CRUD functionality on each object.
	 * @package Codegen
	 * @property string $Errors List of errors
	 * @property string $Warnings List of warnings
	 */
	abstract class QCodeGenBase extends QBaseClass {
		// Class Name Suffix/Prefix
		/** @var string Class Prefix, as specified in the codegen_settings.xml file */
		protected $strClassPrefix;
		/** @var string Class suffix, as specified in the codegen_settings.xml file */
		protected $strClassSuffix;

		/** @var string Errors and Warnings collected during the process of codegen **/
		protected $strErrors;

		/** @var string Warnings collected during the codegen process. */
		protected $strWarnings;

		/**
		 * PHP Reserved Words.  They make up:
		 * Invalid Type names -- these are reserved words which cannot be Type names in any user type table
		 * Invalid Table names -- these are reserved words which cannot be used as any table name
		 * Please refer to : http://php.net/manual/en/reserved.php
		 */
		const PhpReservedWords = 'new, null, break, return, switch, self, case, const, clone, continue, declare, default, echo, else, elseif, empty, exit, eval, if, try, throw, catch, public, private, protected, function, extends, foreach, for, while, do, var, class, static, abstract, isset, unset, implements, interface, instanceof, include, include_once, require, require_once, abstract, and, or, xor, array, list, false, true, global, parent, print, exception, namespace, goto, final, endif, endswitch, enddeclare, endwhile, use, as, endfor, endforeach, this';

		/**
		 * @var array The list of template base paths to search, in order, when looking for a particular template. Set this
		 * to insert new template paths. If not set, the default will be the project template path, following by the qcubed core path.
		 */
		public static $TemplatePaths;

		/**
		 * DebugMode -- for Template Developers
		 * This will output the current evaluated template/statement to the screen
		 * On "eval" errors, you can click on the "View Rendered Page" to see what currently
		 * is being evaluated, which should hopefully aid in template debugging.
		 */
		const DebugMode = false;

		/**
		 * This static array contains an array of active and executed codegen objects, based
		 * on the XML Configuration passed in to Run()
		 *
		 * @var QCodeGen[] array of active/executed codegen objects
		 */
		public static $CodeGenArray;

		/**
		 * This is the array representation of the parsed SettingsXml
		 * for reportback purposes.
		 *
		 * @var string[] array of config settings
		 */
		protected static $SettingsXmlArray;

		/**
		 * This is the SimpleXML representation of the Settings XML file
		 *
		 * @var SimpleXmlElement the XML representation
		 */
		protected static $SettingsXml;

		public static $SettingsFilePath;

		/**
		 * Application Name (from CodeGen Settings)
		 *
		 * @var string $ApplicationName
		 */
		public static $ApplicationName;

		/**
		 * Preferred Render Method (from CodeGen Settings)
		 *
		 * @var string $PreferredRenderMethod
		 */
		public static $PreferredRenderMethod;

		/**
		 * Create Method (from CodeGen Settings)
		 *
		 * @var string $CreateMethod
		 */
		public static $CreateMethod;

		/**
		 * Default Button Class (from CodeGen Settings)
		 *
		 * @var string $DefaultButtonClass
		 */
		public static $DefaultButtonClass;

		public static $RootErrors = '';

		/**
		 * @var string[] array of directories to be excluded in codegen (lower cased)
		 * @access protected
		 */
		protected static $DirectoriesToExcludeArray = array('.','..','.svn','svn','cvs','.git');

		/**
		 * Gets the settings in codegen_settings.xml file and returns its text without comments
		 * @return string
		 */
		public static function GetSettingsXml() {
			$strCrLf = "\r\n";

			$strToReturn = sprintf('<codegen>%s', $strCrLf);
			$strToReturn .= sprintf('	<name application="%s"/>%s', QCodeGen::$ApplicationName, $strCrLf);
			$strToReturn .= sprintf('	<render preferredRenderMethod="%s"/>%s', QCodeGen::$PreferredRenderMethod, $strCrLf);
			$strToReturn .= sprintf('	<dataSources>%s', $strCrLf);
			foreach (QCodeGen::$CodeGenArray as $objCodeGen)
				$strToReturn .= $strCrLf . $objCodeGen->GetConfigXml();
			$strToReturn .= sprintf('%s	</dataSources>%s', $strCrLf, $strCrLf);
			$strToReturn .= '</codegen>';

			return $strToReturn;
		}

		/**
		 * The function which actually performs the steps for code generation
		 * Code generation begins here.
		 * @param string $strSettingsXmlFilePath Path to the settings file
		 */
		public static function Run($strSettingsXmlFilePath) {
			define ('__CODE_GENERATING__', true);
			QCodeGen::$CodeGenArray = array();
			QCodeGen::$SettingsFilePath = $strSettingsXmlFilePath;

			if (!file_exists($strSettingsXmlFilePath)) {
				QCodeGen::$RootErrors = 'FATAL ERROR: CodeGen Settings XML File (' . $strSettingsXmlFilePath . ') was not found.';
				return;
			}

			if (!is_file($strSettingsXmlFilePath)) {
				QCodeGen::$RootErrors = 'FATAL ERROR: CodeGen Settings XML File (' . $strSettingsXmlFilePath . ') was not found.';
				return;
			}

			// Try Parsing the Xml Settings File
			try {
				QApplication::SetErrorHandler('QcubedHandleCodeGenParseError', E_ALL);
				QCodeGen::$SettingsXml = new SimpleXMLElement(file_get_contents($strSettingsXmlFilePath));
				QApplication::RestoreErrorHandler();
			} catch (Exception $objExc) {
				QCodeGen::$RootErrors .= 'FATAL ERROR: Unable to parse CodeGenSettings XML File: ' . $strSettingsXmlFilePath;
				QCodeGen::$RootErrors .= "\r\n";
				QCodeGen::$RootErrors .= $objExc->getMessage();
				return;
			}

			// Application Name
			QCodeGen::$ApplicationName = QCodeGen::LookupSetting(QCodeGen::$SettingsXml, 'name', 'application');

			// Codegen Defaults
			QCodeGen::$PreferredRenderMethod = QCodeGen::LookupSetting(QCodeGen::$SettingsXml, 'formgen', 'preferredRenderMethod');
			QCodeGen::$CreateMethod = QCodeGen::LookupSetting(QCodeGen::$SettingsXml, 'formgen', 'createMethod');
			QCodeGen::$DefaultButtonClass = QCodeGen::LookupSetting(QCodeGen::$SettingsXml, 'formgen', 'buttonClass');

			if (!QCodeGen::$DefaultButtonClass) {
				QCodeGen::$RootErrors .= "CodeGen Settings XML Fatal Error: buttonClass was not defined\r\n";
				return;
			}

			// Iterate Through DataSources
			if (QCodeGen::$SettingsXml->dataSources->asXML())
				foreach (QCodeGen::$SettingsXml->dataSources->children() as $objChildNode) {
					switch (dom_import_simplexml($objChildNode)->nodeName) {
						case 'database':
							QCodeGen::$CodeGenArray[] = new QDatabaseCodeGen($objChildNode);
							break;
						case 'restService':
							QCodeGen::$CodeGenArray[] = new QRestServiceCodeGen($objChildNode);
							break;
						default:
							QCodeGen::$RootErrors .= sprintf("Invalid Data Source Type in CodeGen Settings XML File (%s): %s\r\n",
								$strSettingsXmlFilePath, dom_import_simplexml($objChildNode)->nodeName);
							break;
					}
				}
		}

		/**
		 * This will lookup either the node value (if no attributename is passed in) or the attribute value
		 * for a given Tag.  Node Searches only apply from the root level of the configuration XML being passed in
		 * (e.g. it will not be able to lookup the tag name of a grandchild of the root node)
		 *
		 * If No Tag Name is passed in, then attribute/value lookup is based on the root node, itself.
		 *
		 * @param SimpleXmlElement $objNode
		 * @param string $strTagName
		 * @param string $strAttributeName
		 * @param string $strType
		 * @return mixed the return type depends on the QType you pass in to $strType
		 */
		static public function LookupSetting($objNode, $strTagName, $strAttributeName = null, $strType = QType::String) {
			if ($strTagName)
				$objNode = $objNode->$strTagName;

			if ($strAttributeName) {
				switch ($strType) {
					case QType::Integer:
						try {
							$intToReturn = QType::Cast($objNode[$strAttributeName], QType::Integer);
							return $intToReturn;
						} catch (Exception $objExc) {
							return null;
						}
					case QType::Boolean:
						try {
							$blnToReturn = QType::Cast($objNode[$strAttributeName], QType::Boolean);
							return $blnToReturn;
						} catch (Exception $objExc) {
							return null;
						}
					default:
						$strToReturn = trim(QType::Cast($objNode[$strAttributeName], QType::String));
						return $strToReturn;
				}
			} else {
				$strToReturn = trim(QType::Cast($objNode, QType::String));
				return $strToReturn;
			}
		}

		/**
		 *
		 * @return array
		 */
		public static function GenerateAggregate() {
			$objDbOrmCodeGen = array();
			$objRestServiceCodeGen = array();

			foreach (QCodeGen::$CodeGenArray as $objCodeGen) {
				if ($objCodeGen instanceof QDatabaseCodeGen)
					array_push($objDbOrmCodeGen, $objCodeGen);
				if ($objCodeGen instanceof QRestServiceCodeGen)
					array_push($objRestServiceCodeGen, $objCodeGen);
			}

			$strToReturn = array();
			array_merge($strToReturn, QDatabaseCodeGen::GenerateAggregateHelper($objDbOrmCodeGen));
//			array_push($strToReturn, QRestServiceCodeGen::GenerateAggregateHelper($objRestServiceCodeGen));

			return $strToReturn;
		}

		/**
		 * Given a template prefix (e.g. db_orm_, db_type_, rest_, soap_, etc.), pull
		 * all the _*.tpl templates from any subfolders of the template prefix
		 * in QCodeGen::TemplatesPath and QCodeGen::TemplatesPathCustom,
		 * and call GenerateFile() on each one.  If there are any template files that reside
		 * in BOTH TemplatesPath AND TemplatesPathCustom, then only use the TemplatesPathCustom one (which
		 * in essence overrides the one in TemplatesPath)
		 *
		 * @param string  $strTemplatePrefix the prefix of the templates you want to generate against
		 * @param mixed[] $mixArgumentArray  array of arguments to send to EvaluateTemplate
		 *
		 * @throws Exception
		 * @throws QCallerException
		 * @return boolean success/failure on whether or not all the files generated successfully
		 */
		public function GenerateFiles($strTemplatePrefix, $mixArgumentArray) {
			// If you are editing core templates, and getting EOF errors only on the travis build, this may be your problem. Scan your files and remove short tags.
			if (QCodeGen::DebugMode && ini_get ('short_open_tag')) _p("Warning: PHP directive short_open_tag is on. Using short tags will cause unexpected EOF on travis build.\n", false);

			// Default the template paths
			if (!static::$TemplatePaths) {
				static::$TemplatePaths = array (
					__QCUBED_CORE__ . '/codegen/templates/',
					__QCUBED__ . '/codegen/templates/'
				);
			}

			// validate the template paths
			foreach (static::$TemplatePaths as $strPath) {
				if (!is_dir($strPath)) {
					throw new Exception(sprintf("Template path: %s does not appear to be a valid directory.", $strPath));
				}
			}

			// Create an array of arrays of standard templates and custom (override) templates to process
			// Index by [module_name][filename] => true/false where
			// module name (e.g. "class_gen", "form_delegates) is name of folder within the prefix (e.g. "db_orm")
			// filename is the template filename itself (in a _*.tpl format)
			// true = override (use custom) and false = do not override (use standard)
			$strTemplateArray = array();

			// Go through standard templates first, then override in order
			foreach (static::$TemplatePaths as $strPath) {
				$this->buildTemplateArray($strPath . $strTemplatePrefix, $strTemplateArray);
			}

			// Finally, iterate through all the TemplateFiles and call GenerateFile to Evaluate/Generate/Save them
			$blnSuccess = true;
			foreach ($strTemplateArray as $strModuleName => $strFileArray) {
				foreach ($strFileArray as $strFilename => $strPath) {
					if (!$this->GenerateFile($strTemplatePrefix . '/' . $strModuleName, $strPath, $mixArgumentArray)) {
						$blnSuccess = false;
					}
				}
			}

			return $blnSuccess;
		}

		protected function buildTemplateArray ($strTemplateFilePath, &$strTemplateArray) {
			if (!$strTemplateFilePath) return;
			if (substr( $strTemplateFilePath, -1 ) != '/') {
				$strTemplateFilePath .= '/';
			}
			if (is_dir($strTemplateFilePath)) {
				$objDirectory = opendir($strTemplateFilePath);
				while ($strModuleName = readdir($objDirectory)) {
					if (!in_array(strtolower($strModuleName), QCodeGen::$DirectoriesToExcludeArray) &&
							is_dir($strTemplateFilePath . $strModuleName)) {
						$objModuleDirectory = opendir($strTemplateFilePath . $strModuleName);
						while ($strFilename = readdir($objModuleDirectory)) {
							if ((QString::FirstCharacter($strFilename) == '_') &&
								(substr($strFilename, strlen($strFilename) - 8) == '.tpl.php')
							) {
								$strTemplateArray[$strModuleName][$strFilename] = $strTemplateFilePath . $strModuleName . '/' . $strFilename;
							}
						}
					}
				}
			}
		}

		/**
		 * Returns the settings of the template file as SimpleXMLElement object
		 *
		 * @param null|string $strTemplateFilePath Path to the file
		 * @param null|string $strTemplate         Text of the template (if $strTemplateFilePath is null, this field must be string)
		 * @deprecated
		 *
		 * @return SimpleXMLElement
		 * @throws Exception
		 */
		protected function getTemplateSettings($strTemplateFilePath, &$strTemplate = null) {
			if ($strTemplate === null)
				$strTemplate = file_get_contents($strTemplateFilePath);
			$strError = 'Template\'s first line must be <template OverwriteFlag="boolean" DocrootFlag="boolean" TargetDirectory="string" DirectorySuffix="string" TargetFileName="string"/>: ' . $strTemplateFilePath;
			// Parse out the first line (which contains path and overwriting information)
			$intPosition = strpos($strTemplate, "\n");
			if ($intPosition === false) {
				throw new Exception($strError);
			}

			$strFirstLine = trim(substr($strTemplate, 0, $intPosition));

			$objTemplateXml = null;
			// Attempt to Parse the First Line as XML
			try {
				@$objTemplateXml = new SimpleXMLElement($strFirstLine);
			} catch (Exception $objExc) {}

			if (is_null($objTemplateXml) || (!($objTemplateXml instanceof SimpleXMLElement)))
				throw new Exception($strError);
			$strTemplate = substr($strTemplate, $intPosition + 1);
			return $objTemplateXml;
		}

		/**
		 * Generates a php code using a template file
		 *
		 * @param string  $strModuleSubPath
		 * @param string  $strTemplateFilePath Path to the template file
		 * @param mixed[] $mixArgumentArray
		 * @param boolean $blnSave             whether or not to actually perform the save
		 *
		 * @throws QCallerException
		 * @throws Exception
		 * @return mixed returns the evaluated template or boolean save success.
		 */
		public function GenerateFile($strModuleSubPath, $strTemplateFilePath, $mixArgumentArray, $blnSave = true) {
			// Setup Debug/Exception Message
			if (QCodeGen::DebugMode) _p("Evaluating $strTemplateFilePath<br/>", false);

			// Check to see if the template file exists, and if it does, Load It
			if (!file_exists($strTemplateFilePath))
				throw new QCallerException('Template File Not Found: ' . $strTemplateFilePath);

			// Evaluate the Template
			// make sure paths are set up to pick up included files from the various directories.
			// Must be the reverse of the buildTemplateArray order
			$a = array();
			foreach (static::$TemplatePaths as $strTemplatePath) {
				array_unshift($a,  $strTemplatePath . $strModuleSubPath);
			}
			$strSearchPath = implode (PATH_SEPARATOR, $a) . PATH_SEPARATOR . get_include_path();
			$strOldIncludePath = set_include_path ($strSearchPath);
			if ($strSearchPath != get_include_path()) {
				throw new QCallerException ('Can\'t override include path. Make sure your apache or server settings allow include paths to be overridden. ' );
			}

			$strTemplate = $this->EvaluatePHP($strTemplateFilePath, $mixArgumentArray, $templateSettings);
			set_include_path($strOldIncludePath);

			$blnOverwriteFlag = QType::Cast($templateSettings['OverwriteFlag'], QType::Boolean);
			$blnDocrootFlag = QType::Cast($templateSettings['DocrootFlag'], QType::Boolean);
			$strTargetDirectory = QType::Cast($templateSettings['TargetDirectory'], QType::String);
			$strDirectorySuffix = QType::Cast($templateSettings['DirectorySuffix'], QType::String);
			$strTargetFileName = QType::Cast($templateSettings['TargetFileName'], QType::String);

			if (is_null($blnOverwriteFlag) || is_null($strTargetFileName) || is_null($strTargetDirectory) || is_null($strDirectorySuffix) || is_null($blnDocrootFlag))  {
				throw new Exception('the template settings cannot be null');
			}

			if ($blnSave && $strTargetDirectory) {
				// Figure out the REAL target directory
				if ($blnDocrootFlag)
					$strTargetDirectory = __DOCROOT__ . $strTargetDirectory . $strDirectorySuffix;
				else
					$strTargetDirectory = $strTargetDirectory . $strDirectorySuffix;

				// Create Directory (if needed)
				if (!is_dir($strTargetDirectory))
					if (!QApplication::MakeDirectory($strTargetDirectory, 0777))
						throw new Exception('Unable to mkdir ' . $strTargetDirectory);

				// Save to Disk
				$strFilePath = sprintf('%s/%s', $strTargetDirectory, $strTargetFileName);
				if ($blnOverwriteFlag || (!file_exists($strFilePath))) {
					$intBytesSaved = file_put_contents($strFilePath, $strTemplate);

					$this->setGeneratedFilePermissions($strFilePath);
					return ($intBytesSaved == strlen($strTemplate));
				} else
					// Because we are not supposed to overwrite, we should return "true" by default
					return true;
			}

			// Why Did We Not Save?
			if ($blnSave) {
				// We WANT to Save, but QCubed Configuration says that this functionality/feature should no longer be generated
				// By definition, we should return "true"
				return true;
			}
			// Running GenerateFile() specifically asking it not to save -- so return the evaluated template instead
			return $strTemplate;
		}

		/**
		 * Sets the file permissions (Linux only) for a file generated by the Code Generator
		 * @param string $strFilePath Path of the generated file
		 *
		 * @throws QCallerException
		 */
		protected function setGeneratedFilePermissions($strFilePath) {
			// CHMOD to full read/write permissions (applicable only to nonwindows)
			// Need to ignore error handling for this call just in case
			if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
				QApplication::SetErrorHandler(null);
				chmod($strFilePath, 0666);
				QApplication::RestoreErrorHandler();
			}
		}

		/**
		 * Returns the evaluated PHP
		 *
		 * @param $strFilename
		 * @param $mixArgumentArray
		 * @param null $templateSettings
		 * @return mixed|string
		 */
		protected function EvaluatePHP($strFilename, $mixArgumentArray, &$templateSettings = null)  {
			// Get all the arguments and set them locally
			if ($mixArgumentArray) foreach ($mixArgumentArray as $strName=>$mixValue) {
				$$strName = $mixValue;
			}
			global $_TEMPLATE_SETTINGS;
			unset($_TEMPLATE_SETTINGS);
			$_TEMPLATE_SETTINGS = null;

			// Of course, we also need to locally allow "objCodeGen"
			$objCodeGen = $this;

			// Get Database Escape Identifiers
			$strEscapeIdentifierBegin = QApplication::$Database[$this->intDatabaseIndex]->EscapeIdentifierBegin;
			$strEscapeIdentifierEnd = QApplication::$Database[$this->intDatabaseIndex]->EscapeIdentifierEnd;

			// Store the Output Buffer locally
			$strAlreadyRendered = ob_get_contents();

			if (ob_get_level()) ob_clean();
			ob_start();
			include($strFilename);
			$strTemplate = ob_get_contents();
			ob_end_clean();

			$templateSettings = $_TEMPLATE_SETTINGS;
			unset($_TEMPLATE_SETTINGS);

			// Restore the output buffer and return evaluated template
			print($strAlreadyRendered);

			// Remove all \r from the template (for Win/*nix compatibility)
			$strTemplate = str_replace("\r", '', $strTemplate);
			return $strTemplate;
		}

		///////////////////////
		// COMMONLY OVERRIDDEN CONVERSION FUNCTIONS
		///////////////////////

		/**
		 * Given a table name, returns the name of the class for the corresponding model object.
		 *
		 * @param string $strTableName
		 * @return string
		 */
		protected function ModelClassName($strTableName) {
			$strTableName = $this->StripPrefixFromTable($strTableName);
			return sprintf('%s%s%s',
				$this->strClassPrefix,
				QConvertNotation::CamelCaseFromUnderscore($strTableName),
				$this->strClassSuffix);
		}

		/**
		 * Given a table name, returns a variable name that will be used to represent the corresponding model object.
		 * @param string $strTableName
		 * @return string
		 */
		public function ModelVariableName($strTableName) {
			$strTableName = $this->StripPrefixFromTable($strTableName);
			return QConvertNotation::PrefixFromType(QType::Object) .
			QConvertNotation::CamelCaseFromUnderscore($strTableName);
		}

		/**
		 * Given a table name, returns the variable name that will be used to refer to the object in a
		 * reverse reference context (many-to-one).
		 * @param string $strTableName
		 * @return string
		 */
		protected function ModelReverseReferenceVariableName($strTableName) {
			$strTableName = $this->StripPrefixFromTable($strTableName);
			return $this->ModelVariableName($strTableName);
		}

		/**
		 * Given a table name, returns the variable type of the object in a
		 * reverse reference context (many-to-one).
		 * @param $strTableName
		 * @return string
		 */
		protected function ModelReverseReferenceVariableType($strTableName) {
			$strTableName = $this->StripPrefixFromTable($strTableName);
			return $this->ModelClassName($strTableName);
		}


		/**
		 * Given a column, returns the name of the variable used to represent the column's value inside
		 * the model object.
		 *
		 * @param QSqlColumn $objColumn
		 * @return string
		 */
		protected function ModelColumnVariableName(QSqlColumn $objColumn) {
			return QConvertNotation::PrefixFromType($objColumn->VariableType) .
				QConvertNotation::CamelCaseFromUnderscore($objColumn->Name);
		}

		/**
		 * Return the name of the property corresponding to the given column name as used in the getter and setter of
		 * the model object.
		 * @param string $strColumnName
		 * @return string
		 */
		protected function ModelColumnPropertyName($strColumnName) {
			return QConvertNotation::CamelCaseFromUnderscore($strColumnName);
		}

		/**
		 * Return the name of the property corresponding to the given column name as used in the getter and setter of
		 * a Type object.
		 * @param string $strColumnName Column name
		 * @return string
		 */
		protected function TypeColumnPropertyName($strColumnName) {
			return QConvertNotation::CamelCaseFromUnderscore($strColumnName);
		}

		/**
		 * Given the name of a column that is a foreign key to another table, returns a kind of
		 * virtual column name that would refer to the object pointed to. This new name is used to refer to the object
		 * version of the column by json and other encodings, and derivatives
		 * of this name are used to represent a variable and property name that refers to this object that will get stored
		 * in the model.
		 *
		 * @param string $strColumnName
		 * @return string
		 */
		protected function ModelReferenceColumnName($strColumnName) {
			$intNameLength = strlen($strColumnName);

			// Does the column name for this reference column end in "_id"?
			if (($intNameLength > 3) && (substr($strColumnName, $intNameLength - 3) == "_id")) {
				// It ends in "_id" but we don't want to include the "Id" suffix
				// in the Variable Name.  So remove it.
				$strColumnName = substr($strColumnName, 0, $intNameLength - 3);
			} else {
				// Otherwise, let's add "_object" so that we don't confuse this variable name
				// from the variable that was mapped from the physical database
				// E.g., if it's a numeric FK, and the column is defined as "person INT",
				// there will end up being two variables, one for the Person id integer, and
				// one for the Person object itself.  We'll add Object to the name of the Person object
				// to make this deliniation.
				$strColumnName = sprintf("%s_object", $strColumnName);
			}

			return $strColumnName;
		}

		/**
		 * Given a column name to a foreign key, returns the name of the variable that will represent the foreign object
		 * stored in the model.
		 *
		 * @param string $strColumnName
		 * @return string
		 */
		protected function ModelReferenceVariableName($strColumnName) {
			$strColumnName = $this->ModelReferenceColumnName($strColumnName);
			return QConvertNotation::PrefixFromType(QType::Object) .
				QConvertNotation::CamelCaseFromUnderscore($strColumnName);
		}

		/**
		 * Given a column name to a foreign key, returns the name of the property that will be used in the getter and setter
		 * to represent the foreign object stored in the model.
		 *
		 * @param string $strColumnName
		 * @return string
		 */
		protected function ModelReferencePropertyName($strColumnName) {
			$strColumnName = $this->ModelReferenceColumnName($strColumnName);
			return QConvertNotation::CamelCaseFromUnderscore($strColumnName);
		}

		protected function ParameterCleanupFromColumn(QSqlColumn $objColumn, $blnIncludeEquality = false) {
			if ($blnIncludeEquality)
				return sprintf('$%s = $objDatabase->SqlVariable($%s, true);',
					$objColumn->VariableName, $objColumn->VariableName);
			else
				return sprintf('$%s = $objDatabase->SqlVariable($%s);',
					$objColumn->VariableName, $objColumn->VariableName);
		}

		// To be used to list the columns as input parameters, or as parameters for sprintf
		protected function ParameterListFromColumnArray($objColumnArray) {
			return $this->ImplodeObjectArray(', ', '$', '', 'VariableName', $objColumnArray);
		}

		protected function ImplodeObjectArray($strGlue, $strPrefix, $strSuffix, $strProperty, $objArrayToImplode) {
			$strArrayToReturn = array();
			if ($objArrayToImplode) foreach ($objArrayToImplode as $objObject) {
				array_push($strArrayToReturn, sprintf('%s%s%s', $strPrefix, $objObject->__get($strProperty), $strSuffix));
			}

			return implode($strGlue, $strArrayToReturn);
		}

		protected function TypeTokenFromTypeName($strName) {
			$strToReturn = '';
			for($intIndex = 0; $intIndex < strlen($strName); $intIndex++)
				if (((ord($strName[$intIndex]) >= ord('a')) &&
					 (ord($strName[$intIndex]) <= ord('z'))) ||
					((ord($strName[$intIndex]) >= ord('A')) &&
					 (ord($strName[$intIndex]) <= ord('Z'))) ||
					((ord($strName[$intIndex]) >= ord('0')) &&
					 (ord($strName[$intIndex]) <= ord('9'))) ||
					($strName[$intIndex] == '_'))
					$strToReturn .= $strName[$intIndex];

			if (is_numeric(QString::FirstCharacter($strToReturn)))
				$strToReturn = '_' . $strToReturn;
			return $strToReturn;
		}

		/**
		 * Returns the control label name as used in the ModelConnector corresponding to this column or table.
		 *
		 * @param QSqlColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @return string
		 */
		public static function ModelConnectorControlName ($objColumn) {
			if (($o = $objColumn->Options) && isset ($o['Name'])) { // Did developer default?
				return $o['Name'];
			}
			return QConvertNotation::WordsFromCamelCase(QCodeGen::ModelConnectorPropertyName($objColumn));
		}

		/**
		 * The property name used in the ModelConnector for the given column, virtual column or table
		 *
		 * @param QSqlColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @return string
		 * @throws Exception
		 */
		public static function ModelConnectorPropertyName ($objColumn) {
			if ($objColumn instanceof QSqlColumn) {
				if ($objColumn->Reference) {
					return $objColumn->Reference->PropertyName;
				} else {
					return $objColumn->PropertyName;
				}
			}
			elseif ($objColumn instanceof QReverseReference) {
				if ($objColumn->Unique) {
					return ($objColumn->ObjectDescription);
				}
				else {
					return ($objColumn->ObjectDescriptionPlural);
				}
			}
			elseif ($objColumn instanceof QManyToManyReference) {
				return $objColumn->ObjectDescriptionPlural;
			}
			else {
				throw new Exception ('Unknown column type.');
			}
		}

		/**
		 * Return a variable name corresponding to the given column, including virtual columns like
		 * QReverseReference and QManyToMany references.
		 * @param QSqlColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		public function ModelConnectorVariableName($objColumn) {
			$strPropName = static::ModelConnectorPropertyName($objColumn);
			$objControlHelper = $this->GetControlCodeGenerator($objColumn);
			return $objControlHelper->VarName ($strPropName);
		}

		/**
		 * Returns a variable name for the "label" version of a control, which would be the read-only version
		 * of viewing the data in the column.
		 * @param QSqlColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		public function ModelConnectorLabelVariableName($objColumn) {
			$strPropName = static::ModelConnectorPropertyName($objColumn);
			return QLabel_CodeGenerator::Instance()->VarName($strPropName);
		}

		/**
		 * Returns the class for the control that will be created to edit the given column,
		 * including the 'virtual' columns of reverse references (many to one) and many-to-many references.
		 *
		 * @param QSqlColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @return string Class name of control which can handle this column's data
		 * @throws Exception
		 */
		protected function ModelConnectorControlClass($objColumn) {

			// Is the class specified by the developer?
			if ($o = $objColumn->Options) {
				if (isset ($o['FormGen']) && $o['FormGen'] == QFormGen::LabelOnly) {
					return 'QLabel';
				}
				if (isset($o['ControlClass'])) {
					return $o['ControlClass'];
				}
			}

			// otherwise, return the default class based on the column
			if ($objColumn instanceof QSqlColumn) {
				if ($objColumn->Identity)
					return 'QLabel';

				if ($objColumn->Timestamp)
					return 'QLabel';

				if ($objColumn->Reference)
					return 'QListBox';

				switch ($objColumn->VariableType) {
					case QType::Boolean:
						return 'QCheckBox';
					case QType::DateTime:
						return 'QDateTimePicker';
					case QType::Integer:
						return 'QIntegerTextBox';
					case QType::Float:
						return 'QFloatTextBox';
					default:
						return 'QTextBox';
				}
			}
			elseif ($objColumn instanceof QReverseReference) {
				if ($objColumn->Unique) {
					return 'QListBox';
				} else {
					return 'QCheckBoxList';	// for multi-selection
				}
			}
			elseif ($objColumn instanceof QManyToManyReference) {
				return 'QCheckBoxList';	// for multi-selection
			}
			throw new Exception('Unknown column type.');
		}


		public function DataListControlClass (QSqlTable $objTable) {
			// Is the class specified by the developer?
			if ($o = $objTable->Options) {
				if (isset($o['ControlClass'])) {
					return $o['ControlClass'];
				}
			}

			// Otherwise, return a default
			return 'QDataGrid';
		}

		/**
		 * Returns the control label name as used in the data list panel corresponding to this column.
		 *
		 * @param QSqlTable $objTable
		 *
		 * @return string
		 */
		public static function DataListControlName (QSqlTable $objTable) {
			if (($o = $objTable->Options) && isset ($o['Name'])) { // Did developer default?
				return $o['Name'];
			}
			return QConvertNotation::WordsFromCamelCase($objTable->ClassNamePlural);
		}

		/**
		 * Returns the name of an item in the data list as will be displayed in the edit panel.
		 *
		 * @param QSqlTable $objTable
		 *
		 * @return string
		 */
		public static function DataListItemName (QSqlTable $objTable) {
			if (($o = $objTable->Options) && isset ($o['ItemName'])) { // Did developer override?
				return $o['ItemName'];
			}
			return QConvertNotation::WordsFromCamelCase($objTable->ClassName);
		}

		public function DataListVarName (QSqlTable $objTable) {
			$strPropName = self::DataListPropertyNamePlural($objTable);
			$objControlHelper = $this->GetDataListCodeGenerator($objTable);
			return $objControlHelper->VarName($strPropName);
		}

		public static function DataListPropertyName (QSqlTable $objTable) {
			return $objTable->ClassName;
		}

		public static function DataListPropertyNamePlural (QSqlTable $objTable) {
			return $objTable->ClassNamePlural;
		}


		/**
		 * Returns the class for the control that will be created to edit the given column,
		 * including the 'virtual' columns of reverse references (many to one) and many-to-many references.
		 *
		 * @param QSqlColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @return AbstractControl_CodeGenerator helper object
		 * @throws Exception
		 */
		public function GetControlCodeGenerator($objColumn) {
			$strControlClass = $this->ModelConnectorControlClass($objColumn);

			if (method_exists($strControlClass, 'GetCodeGenerator')) {
				return call_user_func($strControlClass.'::GetCodeGenerator');
			}

			switch ($strControlClass) {
				case 'QLabel': return QLabel_CodeGenerator::Instance();
				case 'QListBox': return new QListBox_CodeGenerator();
				case 'QCheckBox': return new QCheckBox_CodeGenerator();
				case 'QDateTimePicker': return new QDateTimePicker_CodeGenerator();
				case 'QTextBox': return new QTextBox_CodeGenerator();
				case 'QIntegerTextBox': return new QIntegerTextBox_CodeGenerator();
				case 'QFloatTextBox': return new QFloatTextBox_CodeGenerator();
				case 'QCheckBoxList': return new QCheckBoxList_CodeGenerator();
				default: break;
			}

			$strOrigControlClass = $strControlClass;
			$strControlCodeGeneratorClass = $strControlClass .'_CodeGenerator';
			while (!class_exists($strControlCodeGeneratorClass)) {
				$strControlClass = get_parent_class($strControlClass);
				if ($strControlClass === 'QControl') {
					throw new QCallerException("Cannot find an appropriate subclass of AbstractControl_CodeGenerator for ".$strOrigControlClass);
				}
				$strControlCodeGeneratorClass = $strControlClass .'_CodeGenerator';
			}
			return new $strControlCodeGeneratorClass($strOrigControlClass);
		}

		public function GetDataListCodeGenerator($objTable) {
			$strControlClass = $this->DataListControlClass($objTable);

			if (method_exists($strControlClass, 'GetCodeGenerator')) {
				return call_user_func($strControlClass.'::GetCodeGenerator');
			}

			return new QDataGrid_CodeGenerator();
		}


		protected function CalculateObjectMemberVariable($strTableName, $strColumnName, $strReferencedTableName) {
			return sprintf('%s%s%s%s',
				QConvertNotation::PrefixFromType(QType::Object),
				$this->strAssociatedObjectPrefix,
				$this->CalculateObjectDescription($strTableName, $strColumnName, $strReferencedTableName, false),
				$this->strAssociatedObjectSuffix);
		}

		protected function CalculateObjectPropertyName($strTableName, $strColumnName, $strReferencedTableName) {
			return sprintf('%s%s%s',
				$this->strAssociatedObjectPrefix,
				$this->CalculateObjectDescription($strTableName, $strColumnName, $strReferencedTableName, false),
				$this->strAssociatedObjectSuffix);
		}

		// TODO: These functions need to be documented heavily with information from "lexical analysis on fk names.txt"
		protected function CalculateObjectDescription($strTableName, $strColumnName, $strReferencedTableName, $blnPluralize) {
			// Strip Prefixes (if applicable)
			$strTableName = $this->StripPrefixFromTable($strTableName);
			$strReferencedTableName = $this->StripPrefixFromTable($strReferencedTableName);

			// Starting Point
			$strToReturn = QConvertNotation::CamelCaseFromUnderscore($strTableName);

			if ($blnPluralize)
				$strToReturn = $this->Pluralize($strToReturn);

			if ($strTableName == $strReferencedTableName) {
				// Self-referencing Reference to Describe

				// If Column Name is only the name of the referenced table, or the name of the referenced table with "_id",
				// then the object description is simply based off the table name.
				if (($strColumnName == $strReferencedTableName) ||
					($strColumnName == $strReferencedTableName . '_id'))
					return sprintf('Child%s', $strToReturn);

				// Rip out trailing "_id" if applicable
				$intLength = strlen($strColumnName);
				if (($intLength > 3) && (substr($strColumnName, $intLength - 3) == "_id"))
					$strColumnName = substr($strColumnName, 0, $intLength - 3);

				// Rip out the referenced table name from the column name
				$strColumnName = str_replace($strReferencedTableName, "", $strColumnName);

				// Change any double "_" to single "_"
				$strColumnName = str_replace("__", "_", $strColumnName);
				$strColumnName = str_replace("__", "_", $strColumnName);

				$strColumnName = QConvertNotation::CamelCaseFromUnderscore($strColumnName);

				// Special case for Parent/Child
				if ($strColumnName == 'Parent')
					return sprintf('Child%s', $strToReturn);

				return sprintf("%sAs%s",
					$strToReturn, $strColumnName);

			} else {
				// If Column Name is only the name of the referenced table, or the name of the referenced table with "_id",
				// then the object description is simply based off the table name.
				if (($strColumnName == $strReferencedTableName) ||
					($strColumnName == $strReferencedTableName . '_id'))
					return $strToReturn;

				// Rip out trailing "_id" if applicable
				$intLength = strlen($strColumnName);
				if (($intLength > 3) && (substr($strColumnName, $intLength - 3) == "_id"))
					$strColumnName = substr($strColumnName, 0, $intLength - 3);

				// Rip out the referenced table name from the column name
				$strColumnName = str_replace($strReferencedTableName, "", $strColumnName);

				// Change any double "_" to single "_"
				$strColumnName = str_replace("__", "_", $strColumnName);
				$strColumnName = str_replace("__", "_", $strColumnName);

				return sprintf("%sAs%s",
					$strToReturn,
					QConvertNotation::CamelCaseFromUnderscore($strColumnName));
			}
		}

		// this is called for ReverseReference Object Descriptions for association tables (many-to-many)
		protected function CalculateObjectDescriptionForAssociation($strAssociationTableName, $strTableName, $strReferencedTableName, $blnPluralize) {
			// Strip Prefixes (if applicable)
			$strTableName = $this->StripPrefixFromTable($strTableName);
			$strAssociationTableName = $this->StripPrefixFromTable($strAssociationTableName);
			$strReferencedTableName = $this->StripPrefixFromTable($strReferencedTableName);

			// Starting Point
			$strToReturn = QConvertNotation::CamelCaseFromUnderscore($strReferencedTableName);

			if ($blnPluralize)
				$strToReturn = $this->Pluralize($strToReturn);

			// Let's start with strAssociationTableName

			// Rip out trailing "_assn" if applicable
			$strAssociationTableName = str_replace($this->strAssociationTableSuffix, '', $strAssociationTableName);

			// remove instances of the table names in the association table name
			$strTableName2 = str_replace('_', '', $strTableName); // remove underscores if they are there
			$strReferencedTableName2 = str_replace('_', '', $strReferencedTableName); // remove underscores if they are there

			if (beginsWith ($strAssociationTableName, $strTableName . '_')) {
				$strAssociationTableName = trimOffFront ($strTableName . '_', $strAssociationTableName);
			} elseif (beginsWith ($strAssociationTableName, $strTableName2 . '_')) {
				$strAssociationTableName = trimOffFront ($strTableName2 . '_', $strAssociationTableName);
			} elseif (beginsWith ($strAssociationTableName, $strReferencedTableName . '_')) {
				$strAssociationTableName = trimOffFront ($strReferencedTableName . '_', $strAssociationTableName);
			} elseif (beginsWith ($strAssociationTableName, $strReferencedTableName2 . '_')) {
				$strAssociationTableName = trimOffFront ($strReferencedTableName2 . '_', $strAssociationTableName);
			} elseif ($strAssociationTableName == $strTableName ||
					$strAssociationTableName == $strTableName2 ||
					$strAssociationTableName == $strReferencedTableName ||
					$strAssociationTableName == $strReferencedTableName2) {
				$strAssociationTableName = "";
			}

			if (endsWith ($strAssociationTableName,  '_' . $strTableName)) {
				$strAssociationTableName = trimOffEnd ('_' . $strTableName, $strAssociationTableName);
			} elseif (endsWith ($strAssociationTableName, '_' . $strTableName2)) {
				$strAssociationTableName = trimOffEnd ('_' . $strTableName2, $strAssociationTableName);
			} elseif (endsWith ($strAssociationTableName,  '_' . $strReferencedTableName)) {
				$strAssociationTableName = trimOffEnd ('_' . $strReferencedTableName, $strAssociationTableName);
			} elseif (endsWith ($strAssociationTableName, '_' . $strReferencedTableName2)) {
				$strAssociationTableName = trimOffEnd ('_' . $strReferencedTableName2, $strAssociationTableName);
			} elseif ($strAssociationTableName == $strTableName ||
					$strAssociationTableName == $strTableName2 ||
					$strAssociationTableName == $strReferencedTableName ||
					$strAssociationTableName == $strReferencedTableName2) {
				$strAssociationTableName = "";
			}

			// Change any double "__" to single "_"
			$strAssociationTableName = str_replace("__", "_", $strAssociationTableName);
			$strAssociationTableName = str_replace("__", "_", $strAssociationTableName);
			$strAssociationTableName = str_replace("__", "_", $strAssociationTableName);

			// If we have nothing left or just a single "_" in AssociationTableName, return "Starting Point"
			if (($strAssociationTableName == "_") || ($strAssociationTableName == ""))
				return sprintf("%s%s%s",
					$this->strAssociatedObjectPrefix,
					$strToReturn,
					$this->strAssociatedObjectSuffix);

			// Otherwise, add "As" and the predicate
			return sprintf("%s%sAs%s%s",
				$this->strAssociatedObjectPrefix,
				$strToReturn,
				QConvertNotation::CamelCaseFromUnderscore($strAssociationTableName),
				$this->strAssociatedObjectSuffix);
		}

		// This is called by AnalyzeAssociationTable to calculate the GraphPrefixArray for a self-referencing association table (e.g. directed graph)
		protected function CalculateGraphPrefixArray($objForeignKeyArray) {
			// Analyze Column Names to determine GraphPrefixArray
			if ((strpos(strtolower($objForeignKeyArray[0]->ColumnNameArray[0]), 'parent') !== false) ||
				(strpos(strtolower($objForeignKeyArray[1]->ColumnNameArray[0]), 'child') !== false)) {
				$strGraphPrefixArray[0] = '';
				$strGraphPrefixArray[1] = 'Parent';
			} else if ((strpos(strtolower($objForeignKeyArray[0]->ColumnNameArray[0]), 'child') !== false) ||
						(strpos(strtolower($objForeignKeyArray[1]->ColumnNameArray[0]), 'parent') !== false)) {
				$strGraphPrefixArray[0] = 'Parent';
				$strGraphPrefixArray[1] = '';
			} else {
				// Use Default Prefixing for Graphs
				$strGraphPrefixArray[0] = 'Parent';
				$strGraphPrefixArray[1] = '';
			}

			return $strGraphPrefixArray;
		}

		/**
		 * Returns the variable type corresponding to the database column type
		 * @param string $strDbType
		 * @return string
		 * @throws Exception
		 */
		protected function VariableTypeFromDbType($strDbType) {
			switch ($strDbType) {
				case QDatabaseFieldType::Bit:
					return QType::Boolean;
				case QDatabaseFieldType::Blob:
					return QType::String;
				case QDatabaseFieldType::Char:
					return QType::String;
				case QDatabaseFieldType::Date:
					return QType::DateTime;
				case QDatabaseFieldType::DateTime:
					return QType::DateTime;
				case QDatabaseFieldType::Float:
					return QType::Float;
				case QDatabaseFieldType::Integer:
					return QType::Integer;
				case QDatabaseFieldType::Time:
					return QType::DateTime;
				case QDatabaseFieldType::VarChar:
					return QType::String;
				case QDatabaseFieldType::Json:
					return QType::String;
				default:
					throw new Exception("Invalid Db Type to Convert: $strDbType");
			}
		}

		/**
		 * Return the plural of the given name. Override this and return the plural version of particular names
		 * if this generic version isn't working for you.
		 *
		 * @param string $strName
		 * @return string
		 */
		protected function Pluralize($strName) {
			// Special Rules go Here
			switch (true) {
				case (strtolower($strName) == 'play'):
					return $strName . 's';
			}

			$intLength = strlen($strName);
			if (substr($strName, $intLength - 1) == "y")
				return substr($strName, 0, $intLength - 1) . "ies";
			if (substr($strName, $intLength - 1) == "s")
				return $strName . "es";
			if (substr($strName, $intLength - 1) == "x")
				return $strName . "es";
			if (substr($strName, $intLength - 1) == "z")
				return $strName . "zes";
			if (substr($strName, $intLength - 2) == "sh")
				return $strName . "es";
			if (substr($strName, $intLength - 2) == "ch")
				return $strName . "es";

			return $strName . "s";
		}

		public function ReportError ($strError) {
			$this->strErrors .= $strError . "\r\n";
		}

		////////////////////
		// Public Overriders
		////////////////////

		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName
		 *
		 * @throws Exception|QCallerException
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Errors':
					return $this->strErrors;
				case 'Warnings':
					return $this->strWarnings;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**
		 * PHP magic method to set class properties
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed|void
		 */
		public function __set($strName, $mixValue) {
			try {
				switch($strName) {
					case 'Errors':
						return ($this->strErrors = QType::Cast($mixValue, QType::String));
					case 'Warnings':
						return ($this->strWarnings = QType::Cast($mixValue, QType::String));
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
			}
		}
	}
