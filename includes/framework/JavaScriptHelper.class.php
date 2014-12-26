<?php

	/**
	 * Class QJsClosure
	 *
	 * An object which represents a javascript closure (annonymous function). Use this to embed a
	 * function into a PHP array or object that eventually will get turned into javascript.
	 *
	 * @property-read boolean $RawArrayKey If this closure is part of an array, this will determine whether
	 * 	the key for the array gets processed using toJsObject() (which for a string, will put quotes around it),
	 *  or the key will get output a raw text. As of JQuery 1.8, there are situations where quoted keys
	 *  are treated as properties, and non-quoted keys are treated as function names.
	 */
	class QJsClosure {
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

		public function toJsObject() {
			$strParams = $this->strParamsArray ? implode(', ', $this->strParamsArray) : '';
			return 'function('.$strParams.') {'.$this->strBody.'}';
		}
	}

	/**
	 * Wrapper class for arrays to control whether the key in the array is quoted.
	 * In some situations, a quoted key has a different meaning from a non-quoted key.
	 * For example, when making a list of parameters to pass when calling the jQuery $() command,
	 * (i.e. $j(selector, params)), quoted words are turned into parameters, and nonquoted words
	 * are turned into functions. For example, "size" will set the size attribute of the object, and
	 * sixe (no quotes), will call the size() function on the object.
	 *
	 * To use it, simply wrap the value part of the array with this class.
	 * @usage: $a = array ("click", new QJsNoQuoteKey (new QJsClosure('alert ("I was clicked")')));
	 */
	class QJsNoQuoteKey {
		protected $mixContent;

		public function __construct ($mixContent) {
			$this->mixContent = $mixContent;
		}

		public function toJsObject() {
			return JavaScriptHelper::toJsObject($this->mixContent);
		}
	}

	class QNoScriptAjaxAction extends QAjaxAction {
		private $objTargetAction;

		function __construct(QAction $objTargetAction) {
			$this->objTargetAction = $objTargetAction;
		}

		public function __get($strName) {
			if ($strName == 'Event')
				return parent::__get($strName);
			return $this->objTargetAction->__get($strName);
		}

		public function RenderScript(QControl $objControl) {
			return '';
		}
	}

	/**
	 * Class JavaScriptHelper: used to help around with some JS code creation when rendering controls
	 */
	abstract class JavaScriptHelper {
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
		 * the $objControl using the key $strKey and assign it to the variable $strValue.
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
		 * Recursively convert a php object to a javascript object.
		 * If the $objValue is an object other than Date and has a toJsObject() method, the method will be called
		 * to perform the conversion.
		 * Array values are recursively converted as well.
		 * @static
		 * @param mixed $objValue the php object to convert
		 * @return string javascript representation of the php object
		 */
		public static function toJsObject($objValue) {
			switch (gettype($objValue)) {
				case 'double':
				case 'integer':
					return $objValue;
				case 'boolean':
					return $objValue? 'true' : 'false';
				case 'string':
					// see below
					break;
				case 'NULL':
					return 'null';
				case 'object':
					if ($objValue instanceof QDateTime) {
						return 'new Date('.$objValue->Year.','.($objValue->Month-1).','.$objValue->Day.')';
					} else if ($objValue instanceof DateTime) {
						return self::toJsObject(new QDateTime($objValue));
					}
					if (method_exists($objValue, 'toJsObject')) {
						return $objValue->toJsObject();
					}
					break;
				case 'array':
					$array = (array)$objValue;
					if (count($array) == 0) {
						return '[]';
					}
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
						return '{'.$strHash.'}';
					}
					// simple array - create a list
					$strList = '';
					foreach ($array as $objItem) {
						if (strlen($strList) > 0) $strList .= ',';
						$strList .= self::toJsObject($objItem);
					}
					return '['.$strList.']';

			}

			// default to string
			static $search = array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"');
			static $replace = array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"');
			return '"' . str_replace($search, $replace, $objValue) . '"';
		}
	}
?>
