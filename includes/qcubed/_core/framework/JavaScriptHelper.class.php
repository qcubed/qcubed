<?php
	class QJsClosure {
		protected $strBody;
		
		public function __construct($strBody) {
			$this->strBody = $strBody;
		}

		public function toJsObject() {
			return 'function() {'.$this->strBody.'}';
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

	abstract class JavaScriptHelper {
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
						return 'new Date('.$objValue->Year.','.$objValue->Month.','.$objValue->Day.')';
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
							$strHash .= self::toJsObject($objKey).': '.self::toJsObject($objItem);
						}
						return '{'.$strHash.'}';
					}
					// simple array - create a list
					$strList = '';
					foreach ($array as $objItem) {
						if ($strList) $strList .= ',';
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
