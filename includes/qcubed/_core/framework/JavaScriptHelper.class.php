<?php
	class QJsClosure {
		protected $strBody;
		
		public function __construct($strBody) {
			$this->strBody = $strBody;
		}

		public function toJson() {
			return 'function() {'.$this->strBody.'}';
		}
	}

	abstract class JavaScriptHelper {
		public static function toJson($objValue, $strQuote = "'") {
			if (is_null($objValue)) {
				return 'null';
			}
			if (is_bool($objValue)) {
				return $objValue ? 'true' : 'false';
			}
			if (is_numeric($objValue)) {
				return  (string)$objValue;
			}
			if (is_array($objValue)) {
				$array = (array)$objValue;
				if (count($array) == 0) {
					return '[]';
				}
				if (0 !== count(array_diff_key($array, array_keys(array_keys($array))))) {
					// associative array - create a hash
					$strHash = '{';
					foreach (((array)$objValue) as $objKey => $objItem) {
						$strHash .= $objKey . ': ' . self::toJson($objItem, $strQuote);
						$strHash .= ', ';
					}
					return substr($strHash, 0, -2).'}';
				}
				// simple array - create a list
				$strList = '[';
				foreach ($array as $objKey => $objItem) {
					$strList .= self::toJson($objItem, $strQuote);
					$strList .= ', ';
				}
				return substr($strList, 0, -2).']';
			}
			if ($objValue instanceof QDateTime) {
				return 'new Date('.$objValue->Year.','.$objValue->Month.','.$objValue->Day.')';
			}
			if (is_object($objValue) && method_exists($objValue, 'toJson')) {
				return $objValue->toJson();
			}

			// default to string
			return $strQuote.$objValue.$strQuote;
		}
	}
?>
