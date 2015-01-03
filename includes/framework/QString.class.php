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
		 * @throws QCallerException
		 */
		public final function __construct() {
			throw new QCallerException('String should never be instantiated.  All methods and variables are publically statically accessible.');
		}

		/**
		 * Returns the first character of a given string, or null if the given
		 * string is null.
		 *
		 * @param string $strString
		 *
		 * @return string the first character, or null
		 */
		public final static function FirstCharacter($strString) {
			if (mb_strlen($strString, __QAPPLICATION_ENCODING_TYPE__) > 0)
				return mb_substr($strString, 0 , 1, __QAPPLICATION_ENCODING_TYPE__);
			else
				return null;
		}

		/**
		 * Returns the last character of a given string, or null if the given
		 * string is null.
		 *
		 * @param string $strString
		 *
		 * @return string the last character, or null
		 */
		public final static function LastCharacter($strString) {
			$intLength = mb_strlen($strString, __QAPPLICATION_ENCODING_TYPE__);
			if ($intLength > 0)
				return mb_substr($strString, $intLength - 1, 1, __QAPPLICATION_ENCODING_TYPE__);
			else
				return null;
		}

		/**
		 * Checks whether a given string starts with another (sub)string
		 *
		 * @param string $strHaystack
		 * @param string $strNeedle
		 *
		 * @return bool
		 */
		public final static function StartsWith($strHaystack, $strNeedle) {
			// If the length of needle is greater than the length of haystack, then return false
			if(strlen($strNeedle) > strlen($strHaystack)){
				// To supress the error in strpos function below
				return false;
			}

			// search backwards starting from haystack length characters from the end
			return $strNeedle === "" || strrpos($strHaystack, $strNeedle, -strlen($strHaystack)) !== false;
		}

		/**
		 * Checks whether a given string ends with another (sub)string
		 *
		 * @param string $strHaystack
		 * @param string $strNeedle
		 *
		 * @return bool
		 */
		public final static function EndsWith($strHaystack, $strNeedle) {
			// If the length of needle is greater than the length of haystack, then return false
			if(strlen($strNeedle) > strlen($strHaystack)){
				// To supress the error in strpos function below
				return false;
			}

			// search forward starting from end minus needle length characters
			return $strNeedle === "" || strpos($strHaystack, $strNeedle, strlen($strHaystack) - strlen($strNeedle)) !== false;
		}

		/**
		 * Truncates the string to a given length, adding elipses (if needed).
		 *
		 * @param string  $strText      string to truncate
		 * @param integer $intMaxLength the maximum possible length of the string to return (including length of the elipse)
		 *
		 * @return string the full string or the truncated string with eplise
		 */
		public final static function Truncate($strText, $intMaxLength) {
			if (mb_strlen($strText, __QAPPLICATION_ENCODING_TYPE__) > $intMaxLength)
				return mb_substr($strText, 0, $intMaxLength - 3, __QAPPLICATION_ENCODING_TYPE__) . "...";
			else
				return $strText;
		}

		/**
		 * Escapes the string so that it can be safely used in as an Xml Node (basically, adding CDATA if needed)
		 *
		 * @param string $strString string to escape
		 *
		 * @return string the XML Node-safe String
		 */
		public final static function XmlEscape($strString) {
			if ((mb_strpos($strString, '<', 0, __QAPPLICATION_ENCODING_TYPE__) !== false) ||
				(mb_strpos($strString, '&', 0, __QAPPLICATION_ENCODING_TYPE__) !== false)) {
				$strString = str_replace(']]>', ']]]]><![CDATA[>', $strString);
				$strString = sprintf('<![CDATA[%s]]>', $strString);
			}

			return $strString;
		}

		/**
		 * Finds longest substring which is common among two strings
		 *
		 * @param string $str1
		 * @param string $str2
		 *
		 * @return string
		 */
		// Implementation from http://en.wikibooks.org/wiki/Algorithm_Implementation/Strings/Longest_common_substring
		public final static function LongestCommonSubsequence($str1, $str2) {
			$str1Len = mb_strlen($str1, __QAPPLICATION_ENCODING_TYPE__);
			$str2Len = mb_strlen($str2, __QAPPLICATION_ENCODING_TYPE__);
			
			if($str1Len == 0 || $str2Len == 0)
				return '';
			
			$CSL = array(); //Common Sequence Length array
			$intLargestSize = 0;
			$ret = array();
			
			//initialize the CSL array to assume there are no similarities
			for($i=0; $i<$str1Len; $i++){
				$CSL[$i] = array();
				for($j=0; $j<$str2Len; $j++){
					$CSL[$i][$j] = 0;
				}
			}
			
			for($i=0; $i<$str1Len; $i++){
				for($j=0; $j<$str2Len; $j++){
					//check every combination of characters
					if( $str1[$i] == $str2[$j] ){
						//these are the same in both strings
						if($i == 0 || $j == 0)
							//it's the first character, so it's clearly only 1 character long
							$CSL[$i][$j] = 1; 
						else
							//it's one character longer than the string from the previous character
							$CSL[$i][$j] = $CSL[$i-1][$j-1] + 1; 

						if( $CSL[$i][$j] > $intLargestSize ){
							//remember this as the largest
							$intLargestSize = $CSL[$i][$j]; 
							//wipe any previous results
							$ret = array();
							//and then fall through to remember this new value
						}
						if( $CSL[$i][$j] == $intLargestSize )
							//remember the largest string(s)
							$ret[] = substr($str1, $i-$intLargestSize+1, $intLargestSize);
					}
					//else, $CSL should be set to 0, which it was already initialized to
				}
			}
			//return the first match
			if(count($ret) > 0)
				return $ret[0];
			else
				return ''; //no matches
		}
	}
?>