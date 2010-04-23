<?php
	/**
	 * An abstract utility class to handle string manipulation.  All methods
	 * are statically available.
	 */
	abstract class QString {
		/**
		 * This faux constructor method throws a caller exception.
		 * The String object should never be instantiated, and this constructor
		 * override simply guarantees it.
		 *
		 * @return void
		 */
		public final function __construct() {
			throw new QCallerException('String should never be instantiated.  All methods and variables are publically statically accessible.');
		}

		/**
		 * Returns the first character of a given string, or null if the given
		 * string is null.
		 * @param string $strString 
		 * @return string the first character, or null
		 */
		public final static function FirstCharacter($strString) {
			if (strlen($strString) > 0)
				return substr($strString, 0 , 1);
			else
				return null;
		}

		/**
		 * Returns the last character of a given string, or null if the given
		 * string is null.
		 * @param string $strString 
		 * @return string the last character, or null
		 */
		public final static function LastCharacter($strString) {
			$intLength = strlen($strString);
			if ($intLength > 0)
				return substr($strString, $intLength - 1);
			else
				return null;
		}

		/**
		 * Truncates the string to a given length, adding elipses (if needed).
		 * @param string $strString string to truncate
		 * @param integer $intMaxLength the maximum possible length of the string to return (including length of the elipse)
		 * @return string the full string or the truncated string with eplise
		 */
		public final static function Truncate($strText, $intMaxLength) {
			if (strlen($strText) > $intMaxLength)
				return substr($strText, 0, $intMaxLength - 3) . "...";
			else
				return $strText;
		}

		/**
		 * Escapes the string so that it can be safely used in as an Xml Node (basically, adding CDATA if needed)
		 * @param string $strString string to escape
		 * @return string the XML Node-safe String
		 */
		public final static function XmlEscape($strString) {
			if ((strpos($strString, '<') !== false) ||
				(strpos($strString, '&') !== false)) {
				$strString = str_replace(']]>', ']]]]><![CDATA[>', $strString);
				$strString = sprintf('<![CDATA[%s]]>', $strString);
			}

			return $strString;
		}
		
		// Implementation from http://en.wikibooks.org/wiki/Algorithm_Implementation/Strings/Longest_common_substring
		public final static function LongestCommonSubsequence($str1, $str2) {
			$m = strlen($str1);
			$n = strlen($str2);
			$L = array();
			$z = 0;
			$ret = array();
		 
			for($i=0; $i<$m; $i++){
				$L[$i] = array();
				for($j=0; $j<$n; $j++){
					$L[$i][$j] = 0;
				}
			}
		 
			for($i=0; $i<$m; $i++){
				for($j=0; $j<$n; $j++){
					if( $str1[$i] == $str2[$j] ){
						$L[$i][$j] = $L[$i-1][$j-1] + 1;
						if( $L[$i][$j] > $z ){
							$z = $L[$i][$j];
							$ret = array();
						}
						if( $L[$i][$j] == $z )
							$ret[] = substr($str1, $i-$z+1, $z);
					}
				}
			}
			return $ret[0];			
		}
	}
?>