<?php

	/**
	 * Class QJsClosure
	 *
	 * An object which represents a javascript closure (annonymous function). Use this to embed a
	 * function into a PHP array or object that eventually will get turned into javascript.
	 */
	class QJsClosure implements JsonSerializable {
		/** @var  string The js code for the function. */
		protected $strBody;
		/** @var array parameter names for the function call that get passed into the function. */
		protected $strParamsArray;

		/**
		 * @param string     $strBody        The function body
		 * @param array|null $strParamsArray The names of the parameters passed in the function call
		 */
		public function __construct($strBody, $strParamsArray = null) {
			$this->strBody = $strBody;
			$this->strParamsArray = $strParamsArray;
		}

		/**
		 * Return a javascript enclosure. Enclosures cannot be included in JSON, so we need to create a custom
		 * encoding to include in the json that will get decoded at the other side.
		 *
		 * @return string
		 */
		public function toJsObject() {
			$strParams = $this->strParamsArray ? implode(', ', $this->strParamsArray) : '';
			return  'function('.$strParams.') {' . $this->strBody . '}';
		}

		/**
		 * Converts the object into something serializable by json_encode. Will get decoded in qcubed.unpackObj
		 * @return mixed
		 */
		public function jsonSerialize() {
			// Encode in a way to decode in qcubed.js
			$a[JavaScriptHelper::ObjectType] = 'qClosure';
			$a['func'] = $this->strBody;
			$a['params'] = $this->strParamsArray;
			return JavaScriptHelper::MakeJsonEncodable($a);
		}
	}

	/**
	 * Wrapper class for arrays to control whether the key in the array is quoted.
	 * In some situations, a quoted key has a different meaning from a non-quoted key.
	 * For example, when making a list of parameters to pass when calling the jQuery $() command,
	 * (i.e. $j(selector, params)), quoted words are turned into parameters, and non-quoted words
	 * are turned into functions. For example, "size" will set the size attribute of the object, and
	 * size (no quotes), will call the size() function on the object.
	 *
	 * To use it, simply wrap the value part of the array with this class.
	 * @usage: $a = array ("click", new QJsNoQuoteKey (new QJsClosure('alert ("I was clicked")')));
	 */
	class QJsNoQuoteKey implements JsonSerializable {
		protected $mixContent;

		public function __construct ($mixContent) {
			$this->mixContent = $mixContent;
		}

		public function toJsObject() {
			return JavaScriptHelper::toJsObject($this->mixContent);
		}

		public function jsonSerialize() {
			return $this->mixContent;
		}

	}

	/**
	 * Class QJsVarName
	 * Outputs a string without quotes to specify a global variable name. Strings are normally quoted. Dot notation
	 * can be used to specify items within globals.
	 */
	class QJsVarName implements JsonSerializable {
		protected $strContent;

		public function __construct($strContent) {
			$this->strContent = $strContent;
		}

		public function toJsObject() {
			return $this->strContent;
		}

		public function jsonSerialize() {
			$a[JavaScriptHelper::ObjectType] = 'qVarName';
			$a['varName'] = $this->strContent;
			return JavaScriptHelper::MakeJsonEncodable($a);
		}
	}

	/**
	 * Class QJsFunction
	 * Outputs a function call to a global function or function in an object referenced from global space. The purpose
	 * of this is to immediately use the results of the function call, as opposed to a closure, which stores a pointer
	 * to a function that is used later.
	 */
	class QJsFunction implements JsonSerializable {
		/** @var  string|null */
		protected $strContext;
		/** @var  string */
		protected $strFunctionName;
		/** @var  array|null */
		protected $params;

		/**
		 * QJsFunction constructor.
		 * @param string $strFunctionName The name of the function call.
		 * @param null|array $strParamsArray If given, the parameters to send to the function call
		 * @param null $strContext If given, the object in the window object which contains the function and is the context for the function.
		 *   Use dot '.' notation to traverse the object tree. i.e. "obj1.obj2" refers to window.obj1.obj2 in javascript.
		 */
		public function __construct($strFunctionName, $params = null, $strContext = null) {
			$this->strFunctionName = $strFunctionName;
			$this->params = $params;
			$this->strContext = $strContext;
		}

		/**
		 * Returns this as a javascript string to be included in the end script of the page.
		 * @return string
		 */
		public function toJsObject() {
			if ($this->params) {
				foreach ($this->params as $param) {
					$strParams[] = JavaScriptHelper::toJsObject($param);
				}
				$strParams = implode (",", $strParams);
			}
			else {
				$strParams = '';
			}
			$strFuncName = $this->strFunctionName;
			if ($this->strContext) {
				$strFuncName = $this->strContext . '.' . $strFuncName;
			}
 			return  $strFuncName . '('.$strParams.')';
		}

		/**
		 * Returns this as a json object to be sent to qcubed.js during ajax drawing.
		 * @return mixed
		 */
		public function jsonSerialize() {
			$a[JavaScriptHelper::ObjectType] = 'qFunc';
			$a['func'] = $this->strFunctionName;
			if ($this->strContext) {
				$a['context'] = $this->strContext;
			}
			if ($this->params) {
				$a['params'] = $this->params;
			}

			return JavaScriptHelper::MakeJsonEncodable($a);
		}
	}



	/**
	 * Class QJsonParameterList
	 * A Wrapper class that will render an array without the brackets, so that it becomes a variable length parameter list.
	 */
	class QJsParameterList {
		protected $arrContent;

		public function __construct ($arrContent) {
			$this->arrContent = $arrContent;
		}

		public function toJsObject() {
			$strList = '';
			foreach ($this->arrContent as $objItem) {
				if (strlen($strList) > 0) $strList .= ',';
				$strList .= JavaScriptHelper::toJsObject($objItem);
			}
			return $strList;
		}
	}

	/**
	 * Class JavaScriptHelper: used to help with generating javascript code
	 */
	abstract class JavaScriptHelper {

		const ObjectType = 'qObjType';	// Identifies a JSON object as an object we want handle specially in qcubed.js
		/**
		 * Returns javascript that on execution will insert the value $strValue into the DOM element corresponding to
		 * the $objControl using the key $strKey
		 *
		 * @static
		 * @param QControl $objControl
		 * @param string $strKey
		 * @param string $strValue any javascript variable or object
		 * @return string data insertion javascript
		 */
		public static function customDataInsertion(QControl $objControl, $strKey, $strValue) {
			return 'jQuery("#'.$objControl->ControlId.'").data("'.$strKey.'", '.$strValue.');';
		}

		/**
		 * Returns javascript that on execution will retrieve the value from the DOM element corresponding to
		 * the $objControl using the key $strKey and assign it to the javascript variable $strValue.
		 *
		 * @static
		 * @param QControl $objControl
		 * @param string $strKey
		 * @param string $strValue
		 * @return string data retrieval javascript
		 */
		public static function customDataRetrieval(QControl $objControl, $strKey, $strValue) {
			return 'var '.$strValue.' = jQuery("#'.$objControl->ControlId.'").data("'.$strKey.'");';
		}

		/**
		 * Helper class to convert a name from camel case to using dashes to separated words.
		 * data-* html attributes have special conversion rules. Key names should always be lower case. Dashes in the
		 * name get converted to camel case javascript variable names by jQuery.
		 * For example, if you want to pass the value with key name "testVar" from PHP to javascript by printing it in
		 * the html, you would use this function to help convert it to "data-test-var", after which you can retrieve
		 * in in javascript by calling ".data('testVar')". on the object.
		 * @param $strName
		 * @return string
		 * @throws QCallerException
		 */
		public static function dataNameFromCamelCase($strName) {
			if (preg_match('/[A-Z][A-Z]/', $strName)) {
				throw new QCallerException ('Not a camel case string');
			}
			return preg_replace_callback('/([A-Z])/',
				function ($matches) {
					return '-' . strtolower($matches[1]);
				},
				$strName
			);

		}

		/**
		 * Converts an html data attribute name to camelCase.
		 *
		 * @param $strName
		 * @return string
		 */
		public static function dataNameToCamelCase($strName) {
			return preg_replace_callback('/-([a-z])/',
				function ($matches) {
					return ucfirst($matches[1]);
				},
				$strName
			);
		}

		public static function jsEncodeString($objValue) {
			// default to string if not specified
			static $search = array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"');
			static $replace = array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"');
			return '"' . str_replace($search, $replace, $objValue) . '"';
		}

		/**
		 * Recursively convert a php object to a javascript object.
		 * If the $objValue is an object other than Date and has a toJsObject() method, the method will be called
		 * to perform the conversion. Array values are recursively converted as well.
		 *
		 * This string is designed to create the object if it was directly output to the browser. See toJSON below
		 * for an equivalent version that is passable through a json interface.
		 *
		 * @static
		 * @param mixed $objValue the php object to convert
		 * @return string javascript representation of the php object
		 */
		public static function toJsObject($objValue) {
			$strRet = '';

			switch (gettype($objValue)) {
				case 'double':
				case 'integer':
					$strRet = (string)$objValue;
					break;

				case 'boolean':
					$strRet = $objValue? 'true' : 'false';
					break;

				case 'string':
					$strRet = self::jsEncodeString($objValue);
					break;

				case 'NULL':
					$strRet = 'null';
					break;

				case 'object':
					if (method_exists($objValue, 'toJsObject')) {
						$strRet = $objValue->toJsObject();
					}
					break;

				case 'array':
					$array = (array)$objValue;
					if (0 !== count(array_diff_key($array, array_keys(array_keys($array))))) {
						// associative array - create a hash
						$strHash = '';
						foreach ($array as $objKey => $objItem) {
							if ($strHash) $strHash .= ',';
							if ($objItem instanceof QJsNoQuoteKey) {
								$strHash .= $objKey.': '.self::toJsObject($objItem);
							} else {
								$strHash .= self::toJsObject($objKey).': '.self::toJsObject($objItem);
							}
						}
						$strRet = '{'.$strHash.'}';
					}
					else {
						// simple array - create a list
						$strList = '';
						foreach ($array as $objItem) {
							if (strlen($strList) > 0) $strList .= ',';
							$strList .= self::toJsObject($objItem);
						}
						$strRet = '['.$strList.']';
					}

					break;

				default:
					$strRet = self::jsEncodeString((string)$objValue);
					break;

			}
			return $strRet;
		}

		/**
		 * Our specialized json encoder. Strings will be converted to UTF-8. Arrays will be recursively searched and
		 * both keys and values made UTF-8. Objects will be converted with json_encode, and so objects that need a special
		 * encoding should implement the jsonSerializable interface. See below
		 * @param mixed $objValue
		 * @return string
		 */
		public static function toJSON($objValue) {
			assert ('is_array($objValue) || is_object($objValue)');	// json spec says only arrays or objects can be encoded
			$objValue = JavaScriptHelper::MakeJsonEncodable($objValue);
			$strRet = json_encode($objValue);
			if ($strRet === false) {
				throw new QCallerException ('Json Encoding Error: ' . json_last_error_msg());
			}
			return $strRet;
		}

		/**
		 * Convert an object to a structure that we can call json_encode on. This is particularly meant for the purpose of
		 * sending json data to qcubed.js through ajax, but can be used for other things as well.
		 *
		 * PHP 5.4 has a new jsonSerializable interface that objects should use to modify their encoding if needed. Otherwise,
		 * public member variables will be encoded. The goal of object serialization should be to be able to send it
		 * to qcubed.unpackParams in qcubed.js to create the javascript form of the object. This decoder will look for objects
		 * that have the 'qObjType' key set and send the object to the special unpacker.
		 *
		 * DateTime handling is absent below. DateTime objects will get converted, but not in a very useful way. If you
		 * are using strict DateTime objects (not likely since the framework normally uses QDateTime for all date objects),
		 * you should convert them to QDateTime objects before sending them here.
		 *
		 * @param mixed $objValue
		 * @return mixed
		 */
		public static function MakeJsonEncodable($objValue) {
			if (QApplication::$EncodingType && QApplication::$EncodingType == 'UTF-8') {
				return $objValue; // Nothing to do, since all strings are already UTF-8 and objects can take care of themselves.
			}

			switch (gettype($objValue)) {
				case 'string':
					$objValue = mb_convert_encoding($objValue, 'UTF-8', QApplication::$EncodingType);
					return $objValue;

				case 'array':
					$newArray = array();
					foreach ($objValue as $key=>$val) {
						$key = self::makeJsonEncodable($key);
						$val = self::makeJsonEncodable($val);
						$newArray[$key] = $val;
					}
					return $newArray;

				default:
					return $objValue;

			}
		}

		/**
		 * Utility function to make sure a script is terminated with a semicolon.
		 *
		 * @param $strScript
		 * @return string
		 */
		public static function TerminateScript($strScript) {
			if (!$strScript) return '';
			if (!($strScript = trim ($strScript))) return '';
			if (substr($strScript, -1) != ';') {
				$strScript .= ';';
			}
			return $strScript . _nl();
		}

	}
