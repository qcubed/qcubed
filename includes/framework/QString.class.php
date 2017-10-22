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
			if (defined('__QAPPLICATION_ENCODING_TYPE__')) {
				if (mb_strlen($strString, __QAPPLICATION_ENCODING_TYPE__) > 0)
					return mb_substr($strString, 0 , 1, __QAPPLICATION_ENCODING_TYPE__);
				else
					return null;
			} else {
				if (strlen($strString)) {
					return $strString[0];
				} else {
					return null;
				}
			}
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
			if (defined('__QAPPLICATION_ENCODING_TYPE__')) {
				$intLength = mb_strlen($strString, __QAPPLICATION_ENCODING_TYPE__);
				if ($intLength > 0)
					return mb_substr($strString, $intLength - 1, 1, __QAPPLICATION_ENCODING_TYPE__);
				else
					return null;
			} else {
				$intLength = strlen($strString);
				if ($intLength > 0)
					return $strString[$intLength -1];
				else
					return null;
			}
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
			if (defined('__QAPPLICATION_ENCODING_TYPE__')) {
				$str1Len = mb_strlen($str1, __QAPPLICATION_ENCODING_TYPE__);
				$str2Len = mb_strlen($str2, __QAPPLICATION_ENCODING_TYPE__);
			} else {
				$str1Len = strlen($str1);
				$str2Len = strlen($str2);
			}

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

		/**
		 * Base64 encode in a way that the result can be passed through HTML forms and URLs.
		 * @param $s
		 * @return mixed
		 */
		public static function Base64UrlSafeEncode($s) {
			$s = base64_encode($s);
			$s = str_replace('+', '-', $s);
			$s = str_replace('/', '_', $s);
			$s = str_replace('=', '', $s);
			return ($s);
		}

		/**
		 * Base64 Decode in a way that the result can be passed through HTML forms and URLs.
		 *
		 * @param $s
		 * @return mixed
		 */
		public static function Base64UrlSafeDecode($s) {
			$s = str_replace('_', '/', $s);
			$s = str_replace('-', '+', $s);
			$s = base64_decode($s);
			return ($s);
		}

		/**
		 * Generates a string usable in a URL from any Utf8 encoded string, commonly called a slug.
		 *
		 * @param string $strString Input string in utf8
		 * @param integer $intMaxLength the maximum possible length of the string to return. The result will be truncated to this lenghth.
		 *
		 * @return string the slug-generated string
		 */
		public static function SanitizeForUrl($strString = '', $intMaxLength = null) {
			if (mb_strlen($strString, __QAPPLICATION_ENCODING_TYPE__) > $intMaxLength ||
				(mb_strlen($strString, __QAPPLICATION_ENCODING_TYPE__) < $intMaxLength))  {

				$strString = strip_tags($strString);
				$strString = preg_replace('/%([a-fA-F0-9][a-fA-F0-9])/', '--$1--', $strString); // Preserve escaped octets
				$strString = str_replace('%', '', $strString); // Remove percent signs that are not part of an octet
				$strString = preg_replace('/--([a-fA-F0-9][a-fA-F0-9])--/', '%$1', $strString);  // Restore octets

				$strString = QString::RemoveAccents($strString);

				$strString = mb_convert_case($strString, MB_CASE_LOWER, "UTF-8");
				$strString = preg_replace('/&.+?;/', '', $strString); // Kill entities
				$strString = str_replace('.', '-', $strString);
				$strString = str_replace('::', '-', $strString);
				$strString = preg_replace('/\s+/', '-', $strString); // Remove excess spaces in a string
				$strString = preg_replace('|[\p{Ps}\p{Pe}\p{Pi}\p{Pf}\p{Po}\p{S}\p{Z}\p{C}\p{No}]+|u', '', $strString); // Remove separator, control, formatting, surrogate, open/close quotes

				$strString = preg_replace('/-+/', '-', $strString); // Remove excess hyphens
				$strString = trim($strString, '-');

				$strString = mb_substr($strString, 0, $intMaxLength, __QAPPLICATION_ENCODING_TYPE__);
				return rtrim($strString, '-'); // When the end of the text remains the hyphen, it is removed.
				//This rtrim() starts implementing at the $intMaxLength.
			} else  {
				return $strString;
			}
		}

		/**
		 * Remove accents from a string
		 *
		 * @param string $strString string
		 * @return string
		 */
		public static function RemoveAccents($strString) {
			if (!preg_match('/[\x80-\xff]/', $strString))
				return $strString;

			if (self::IsUtf8($strString)) {
				$chars = array(
					// Decompositions for Latin-1 Supplement
					chr(195) . chr(128) => 'A', chr(195) . chr(129) => 'A',
					chr(195) . chr(130) => 'A', chr(195) . chr(131) => 'A',
					chr(195) . chr(132) => 'A', chr(195) . chr(133) => 'A',
					chr(195) . chr(135) => 'C', chr(195) . chr(136) => 'E',
					chr(195) . chr(137) => 'E', chr(195) . chr(138) => 'E',
					chr(195) . chr(139) => 'E', chr(195) . chr(140) => 'I',
					chr(195) . chr(141) => 'I', chr(195) . chr(142) => 'I',
					chr(195) . chr(143) => 'I', chr(195) . chr(145) => 'N',
					chr(195) . chr(146) => 'O', chr(195) . chr(147) => 'O',
					chr(195) . chr(148) => 'O', chr(195) . chr(149) => 'O',
					chr(195) . chr(150) => 'O', chr(195) . chr(153) => 'U',
					chr(195) . chr(154) => 'U', chr(195) . chr(155) => 'U',
					chr(195) . chr(156) => 'U', chr(195) . chr(157) => 'Y',
					chr(195) . chr(159) => 's', chr(195) . chr(160) => 'a',
					chr(195) . chr(161) => 'a', chr(195) . chr(162) => 'a',
					chr(195) . chr(163) => 'a', chr(195) . chr(164) => 'a',
					chr(195) . chr(165) => 'a', chr(195) . chr(167) => 'c',
					chr(195) . chr(168) => 'e', chr(195) . chr(169) => 'e',
					chr(195) . chr(170) => 'e', chr(195) . chr(171) => 'e',
					chr(195) . chr(172) => 'i', chr(195) . chr(173) => 'i',
					chr(195) . chr(174) => 'i', chr(195) . chr(175) => 'i',
					chr(195) . chr(177) => 'n', chr(195) . chr(178) => 'o',
					chr(195) . chr(179) => 'o', chr(195) . chr(180) => 'o',
					chr(195) . chr(181) => 'o', chr(195) . chr(182) => 'o',
					chr(195) . chr(182) => 'o', chr(195) . chr(185) => 'u',
					chr(195) . chr(186) => 'u', chr(195) . chr(187) => 'u',
					chr(195) . chr(188) => 'u', chr(195) . chr(189) => 'y',
					chr(195) . chr(191) => 'y',
					// Decompositions for Latin Extended-A
					chr(196) . chr(128) => 'A', chr(196) . chr(129) => 'a',
					chr(196) . chr(130) => 'A', chr(196) . chr(131) => 'a',
					chr(196) . chr(132) => 'A', chr(196) . chr(133) => 'a',
					chr(196) . chr(134) => 'C', chr(196) . chr(135) => 'c',
					chr(196) . chr(136) => 'C', chr(196) . chr(137) => 'c',
					chr(196) . chr(138) => 'C', chr(196) . chr(139) => 'c',
					chr(196) . chr(140) => 'C', chr(196) . chr(141) => 'c',
					chr(196) . chr(142) => 'D', chr(196) . chr(143) => 'd',
					chr(196) . chr(144) => 'D', chr(196) . chr(145) => 'd',
					chr(196) . chr(146) => 'E', chr(196) . chr(147) => 'e',
					chr(196) . chr(148) => 'E', chr(196) . chr(149) => 'e',
					chr(196) . chr(150) => 'E', chr(196) . chr(151) => 'e',
					chr(196) . chr(152) => 'E', chr(196) . chr(153) => 'e',
					chr(196) . chr(154) => 'E', chr(196) . chr(155) => 'e',
					chr(196) . chr(156) => 'G', chr(196) . chr(157) => 'g',
					chr(196) . chr(158) => 'G', chr(196) . chr(159) => 'g',
					chr(196) . chr(160) => 'G', chr(196) . chr(161) => 'g',
					chr(196) . chr(162) => 'G', chr(196) . chr(163) => 'g',
					chr(196) . chr(164) => 'H', chr(196) . chr(165) => 'h',
					chr(196) . chr(166) => 'H', chr(196) . chr(167) => 'h',
					chr(196) . chr(168) => 'I', chr(196) . chr(169) => 'i',
					chr(196) . chr(170) => 'I', chr(196) . chr(171) => 'i',
					chr(196) . chr(172) => 'I', chr(196) . chr(173) => 'i',
					chr(196) . chr(174) => 'I', chr(196) . chr(175) => 'i',
					chr(196) . chr(176) => 'I', chr(196) . chr(177) => 'i',
					chr(196) . chr(178) => 'IJ', chr(196) . chr(179) => 'ij',
					chr(196) . chr(180) => 'J', chr(196) . chr(181) => 'j',
					chr(196) . chr(182) => 'K', chr(196) . chr(183) => 'k',
					chr(196) . chr(184) => 'k', chr(196) . chr(185) => 'L',
					chr(196) . chr(186) => 'l', chr(196) . chr(187) => 'L',
					chr(196) . chr(188) => 'l', chr(196) . chr(189) => 'L',
					chr(196) . chr(190) => 'l', chr(196) . chr(191) => 'L',
					chr(197) . chr(128) => 'l', chr(197) . chr(129) => 'L',
					chr(197) . chr(130) => 'l', chr(197) . chr(131) => 'N',
					chr(197) . chr(132) => 'n', chr(197) . chr(133) => 'N',
					chr(197) . chr(134) => 'n', chr(197) . chr(135) => 'N',
					chr(197) . chr(136) => 'n', chr(197) . chr(137) => 'N',
					chr(197) . chr(138) => 'n', chr(197) . chr(139) => 'N',
					chr(197) . chr(140) => 'O', chr(197) . chr(141) => 'o',
					chr(197) . chr(142) => 'O', chr(197) . chr(143) => 'o',
					chr(197) . chr(144) => 'O', chr(197) . chr(145) => 'o',
					chr(197) . chr(146) => 'OE', chr(197) . chr(147) => 'oe',
					chr(197) . chr(148) => 'R', chr(197) . chr(149) => 'r',
					chr(197) . chr(150) => 'R', chr(197) . chr(151) => 'r',
					chr(197) . chr(152) => 'R', chr(197) . chr(153) => 'r',
					chr(197) . chr(154) => 'S', chr(197) . chr(155) => 's',
					chr(197) . chr(156) => 'S', chr(197) . chr(157) => 's',
					chr(197) . chr(158) => 'S', chr(197) . chr(159) => 's',
					chr(197) . chr(160) => 'S', chr(197) . chr(161) => 's',
					chr(197) . chr(162) => 'T', chr(197) . chr(163) => 't',
					chr(197) . chr(164) => 'T', chr(197) . chr(165) => 't',
					chr(197) . chr(166) => 'T', chr(197) . chr(167) => 't',
					chr(197) . chr(168) => 'U', chr(197) . chr(169) => 'u',
					chr(197) . chr(170) => 'U', chr(197) . chr(171) => 'u',
					chr(197) . chr(172) => 'U', chr(197) . chr(173) => 'u',
					chr(197) . chr(174) => 'U', chr(197) . chr(175) => 'u',
					chr(197) . chr(176) => 'U', chr(197) . chr(177) => 'u',
					chr(197) . chr(178) => 'U', chr(197) . chr(179) => 'u',
					chr(197) . chr(180) => 'W', chr(197) . chr(181) => 'w',
					chr(197) . chr(182) => 'Y', chr(197) . chr(183) => 'y',
					chr(197) . chr(184) => 'Y', chr(197) . chr(185) => 'Z',
					chr(197) . chr(186) => 'z', chr(197) . chr(187) => 'Z',
					chr(197) . chr(188) => 'z', chr(197) . chr(189) => 'Z',
					chr(197) . chr(190) => 'z', chr(197) . chr(191) => 's',
					// Euro Sign
					chr(226) . chr(130) . chr(172) => 'E',
					// GBP (Pound) Sign
					chr(194) . chr(163) => '');
				$strString = strtr($strString, $chars);
			} else {
				// Assume ISO-8859-1 if not UTF-8
				$chars['in'] = chr(128) . chr(131) . chr(138) . chr(142) . chr(154) . chr(158)
					. chr(159) . chr(162) . chr(165) . chr(181) . chr(192) . chr(193) . chr(194)
					. chr(195) . chr(196) . chr(197) . chr(199) . chr(200) . chr(201) . chr(202)
					. chr(203) . chr(204) . chr(205) . chr(206) . chr(207) . chr(209) . chr(210)
					. chr(211) . chr(212) . chr(213) . chr(214) . chr(216) . chr(217) . chr(218)
					. chr(219) . chr(220) . chr(221) . chr(224) . chr(225) . chr(226) . chr(227)
					. chr(228) . chr(229) . chr(231) . chr(232) . chr(233) . chr(234) . chr(235)
					. chr(236) . chr(237) . chr(238) . chr(239) . chr(241) . chr(242) . chr(243)
					. chr(244) . chr(245) . chr(246) . chr(248) . chr(249) . chr(250) . chr(251)
					. chr(252) . chr(253) . chr(255);

				$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

				$strString = strtr($strString, $chars['in'], $chars['out']);
				$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
				$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
				$strString = str_replace($double_chars['in'], $double_chars['out'], $strString);
			}
			return $strString;
		}

		/**
		 * Check whether a string is utf 8 or not
		 *
		 * @param string $strString string
		 * @return string
		 */
		public static function IsUtf8($strString) {
			return preg_match('%^(?:

					[\x09\x0A\x0D\x20-\x7E]             # ASCII
					| [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
					|  \xE0[\xA0-\xBF][\x80-\xBF]       # excluding overlongs
					| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
					|  \xED[\x80-\x9F][\x80-\xBF]       # excluding surrogates
					|  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
					| [\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
					|  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
					)*$%xs', $strString);
		}

		/**
		 * Get a random string of a given length
		 *
		 * @param int        $intLength       Length of the string which is to be produced
		 * @param int|string $strCharacterSet Character Set to be used
		 *
		 * @return string The generated Random string
		 * @throws QCallerException
		 */
		public static function GetRandomString($intLength, $strCharacterSet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
			// Cast in case there were something else
			$intLength = QType::Cast($intLength, QType::Integer);
			$strCharacterSet = QType::Cast($strCharacterSet, QType::String);

			if($intLength < 1) {
				throw new QCallerException('Cannot generate a random string of zero length');
			}

			if(strlen(trim($strCharacterSet)) == 0) {
				throw new QCallerException('Character set must contain at least 1 printable character.');
			}

			return substr(
				str_shuffle(
					str_repeat($strCharacterSet, ceil($intLength / strlen($strCharacterSet)))
				)
				, 0, $intLength);
		}

		/**
		 * Convert file size in bytes to human readable
		 *
		 * @param  integer  $intSize
		 * @param  integer $intPrecision
		 * @return  integer
		 */
		public static function DisplayByteSize($intSize, $intPrecision = null) {
			if (is_null($intSize)) return 'n/a';
			if ($intSize == 0) return '0 KB';
			if ($intSize < 0) return '-';

			if ($intSize > 0) {
				$intSize = (int) $intSize;
				$base = log($intSize) / log(1024);
				$suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
				return round(pow(1024, $base - floor($base)), $intPrecision) . $suffixes[floor($base)];
			} else {
				return $intSize;
			}
		}
  }
