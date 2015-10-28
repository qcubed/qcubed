<?php
	/**
	 * An abstract utility class to handle Html tag rendering, as well as utilities to render
	 * pieces of HTML and CSS code.  All methods are static.
	 */
	abstract class QHtml {

		const IsVoid = true;

		// Common URL Protocols
		/** HTTP Protocol */
		const HTTP = 'http://';
		/** HTTPS Protocol */
		const HTTPS = 'https://';
		/** FTP Protocol */
		const FTP = 'ftp://';
		/** SFTP Protocol */
		const SFTP = 'sftp://';
		/** SMB Protocol */
		const SMB = 'smb://';


		/**
		 * This faux constructor method throws a caller exception.
		 * The Css object should never be instantiated, and this constructor
		 * override simply guarantees it.
		 *
		 * @throws QCallerException
		 * @return QHtml
		 */
		public final function __construct() {
			throw new QCallerException('QHtml should never be instantiated.  All methods and variables are publicly statically accessible.');
		}

		/**
		 * Renders an html tag with the given attributes and inner html.
		 *
		 * If the innerHtml is detected as being wrapped in an html tag of some sort, it will attempt to format the code so that
		 * it has a structured view in a browser, with the inner html indented and on a new line in between the tags. You
		 * can turn this off by setting __MINIMIZE__, or by passing in true to $blnNoSpace.
		 *
		 * There area a few special cases to consider:
		 * - Void elements will not be formatted to avoid adding unnecessary white space since these are generally
		 *   inline elements
		 * - Non-void elements always use internal newlines, even in __MINIMIZE__ mode. This is to prevent different behavior
		 *   from appearing in __MINIMIZE__ mode on inline elements, because inline elements with internal space will render with space to separate
		 *   from surrounding elements. Usually, this is not an issue, but in the special situations where you really need inline
		 *   elements to be right up against its siblings, set $blnNoSpace to true.
		 *
		 *
		 * @param string 		$strTag				The tag name
		 * @param null|mixed 	$mixAttributes 		String of attribute values or array of attribute values.
		 * @param null|string 	$strInnerHtml 		The html to print between the opening and closing tags. This will NOT be escaped.
		 * @param boolean		$blnIsVoidElement 	True to print as a tag with no closing tag.
		 * @param boolean		$blnNoSpace		 	Renders with no white-space. Useful in special inline situations.
		 * @return string						The rendered html tag
		 */
		public static function RenderTag($strTag, $mixAttributes, $strInnerHtml = null, $blnIsVoidElement = false, $blnNoSpace = false) {
			assert ('!empty($strTag)');
			$strToReturn = '<' . $strTag;
			if ($mixAttributes) {
				if (is_string($mixAttributes)) {
					$strToReturn .=  ' ' . trim($mixAttributes);
				} else {
					// assume array
					$strToReturn .=  QHtml::RenderHtmlAttributes($mixAttributes);
				}
			};
			if ($blnIsVoidElement) {
				$strToReturn .= ' />'; // conforms to both XHTML and HTML5 for both normal and foreign elements
			}
			elseif ($blnNoSpace || substr (trim($strInnerHtml), 0, 1) !== '<') {
				$strToReturn .= '>' . $strInnerHtml . '</' . $strTag . '>';
			}
			else {
				// the hardcoded newlines below are important to prevent different drawing behavior in MINIMIZE mode
				$strToReturn .= '>' . "\n" . _indent(trim($strInnerHtml)) .  "\n" . '</' . $strTag . '>' . _nl();
			}
			return $strToReturn;
		}

		/**
		 * Renders an input element with a label tag. Uses separate styling for the label and the input object.
		 * In particular, this gives you the option of wrapping the input with a label (which is what Bootstrap
		 * expects on checkboxes) or putting the label next to the object (which is what jQueryUI expects).
		 *
		 * Note that if you are not setting $blnWrapped, it is up to you to insert the "for" attribute into
		 * the label attributes.
		 *
		 * @param $strLabel
		 * @param $blnTextLeft
		 * @param $strAttributes
		 * @param $strLabelAttributes
		 * @param $blnWrapped
		 * @return string
		 */
		public static function RenderLabeledInput($strLabel, $blnTextLeft, $strAttributes, $strLabelAttributes, $blnWrapped) {
			$strHtml = trim(self::RenderTag('input', $strAttributes, null, true));

			if ($blnWrapped) {
				if ($blnTextLeft) {
					$strCombined = $strLabel .  $strHtml;
				} else {
					$strCombined = $strHtml . $strLabel;
				}

				$strHtml = self::RenderTag('label', $strLabelAttributes, $strCombined);
			}
			else {
				$strLabel = trim(self::RenderTag('label', $strLabelAttributes, $strLabel));
				if ($blnTextLeft) {
					$strHtml = $strLabel .  $strHtml;
				} else {
					$strHtml = $strHtml . $strLabel;
				}
			}
			return $strHtml;
		}

		/**
		 * Returns the formatted value of type <length>.
		 * See http://www.w3.org/TR/CSS1/#units for more info.
		 * @param 	string 	$strValue 	The number or string to be formatted to the <length> compatible value.
		 * @return 	string 	the formatted value of type <length>.
		 */
		public final static function FormatLength($strValue) {
			if (is_numeric($strValue)) {
				if (0 == $strValue) {
					if (!is_int($strValue)) {
						$fltValue = floatval($strValue);
						return sprintf('%s', $fltValue);
					} else {
						return sprintf('%s', $strValue);
					}
				} else {
					if (!is_int($strValue)) {
						$fltValue = floatval($strValue);
						return sprintf('%spx', $fltValue);
					} else {
						return sprintf('%spx', $strValue);
					}
				}
			} else {
				return sprintf('%s', $strValue);
			}
		}

		/**
		 * Sets the given length string to the new length value.
		 * If the new length is preceded by a math operator (+-/*), then arithmetic is performed on the previous
		 * value. Returns true if the length changed.
		 * @param 	string 	$strOldLength
		 * @param 	string 	$newLength
		 * @return 	bool	true if the length was changed
		 */
		public static function SetLength(&$strOldLength, $newLength) {
			if ($newLength && preg_match('#^(\+|\-|/|\*)(.+)$#',$newLength, $matches)) { // do math operation
				$strOperator = $matches[1];
				$newValue = $matches[2];
				assert (is_numeric($newValue));
				if (!$strOldLength) {
					$oldValue  = 0;
					$oldUnits = 'px';
				} else {
					$oldValue = filter_var ($strOldLength, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
					if (preg_match('/([A-Z]+|[a-z]+|%)$/', $strOldLength, $matches)) {
						$oldUnits = $matches[1];
					} else {
						$oldUnits = 'px';
					}
				}

				switch ($strOperator) {
					case '+':
						$newValue = $oldValue + $newValue;
						break;

					case '-':
						$newValue = $oldValue - $newValue;
						break;

					case '/':
						$newValue = $oldValue / $newValue;
						break;

					case '*':
						$newValue = $oldValue * $newValue;
						break;
				}
				if ($newValue != $oldValue) {
					$strOldLength = $newValue . $oldUnits; // update returned value
					return true;
				} else {
					return false; // nothing changed
				}
			} else { // no math operation
				$newLength = self::FormatLength($newLength);

				if ($strOldLength !== $newLength) {
					$strOldLength = $newLength;
					return true;
				} else {
					return false;
				}
			}
		}


		/**
		 * Helper to add a class or classes to a pre-existing space-separated list of classes. Checks to make sure the
		 * class isn't already in the list. Returns true to indicate a change in the list.
		 *
		 * @param string 	$strClassList	Current list of classes separated by a space
		 * @param string 	$strNewClasses 	New class to add. Could be a list separated by spaces.
		 * @return bool 	true if the class list was changed.
		 */
		public static function AddClass(&$strClassList, $strNewClasses) {
			$strNewClasses = trim($strNewClasses);
			if (empty($strNewClasses)) return false;

			if (empty ($strClassList)) {
				$strCurrentClasses = array();
			}
			else {
				$strCurrentClasses = explode(' ', $strClassList);
			}

			$blnChanged = false;
			foreach (explode (' ', $strNewClasses) as $strClass) {
				if ($strClass && !in_array ($strClass, $strCurrentClasses)) {
					$blnChanged = true;
					if (!empty ($strClassList)) {
						$strClassList .= ' ';
					}
					$strClassList .= $strClass;
				}
			}

			return $blnChanged;
		}

		/**
		 * Helper to remove a class or classes from a list of space-separated classes.
		 *
		 * @param string $strClassList        class list string to search
		 * @param string $strCssNamesToRemove space separated list of names to remove
		 *
		 * @return bool    true if the class list was changed
		 */
		public static function RemoveClass(&$strClassList, $strCssNamesToRemove) {
			$strNewCssClass = '';
			$blnRemoved = false;
			$strCssNamesToRemove = trim($strCssNamesToRemove);
			if (empty($strCssNamesToRemove)) return false;

			if (empty ($strClassList)) {
				$strCurrentClasses = array();
			}
			else {
				$strCurrentClasses = explode(' ', $strClassList);
			}
			$strRemoveArray = explode (' ', $strCssNamesToRemove);

			foreach ($strCurrentClasses as $strCssClass) {
				if ($strCssClass = trim($strCssClass)) {
					if (in_array($strCssClass, $strRemoveArray)) {
						$blnRemoved = true;
					}
					else {
						$strNewCssClass .= $strCssClass . ' ';
					}
				}
			}
			if ($blnRemoved) {
				$strClassList = trim($strNewCssClass);
			}
			return $blnRemoved;
		}

		/**
		 * Many CSS frameworks use families of classes, which are built up from a base family name. For example,
		 * Bootstrap uses 'col-lg-6' to represent a column that is 6 units wide on large screens and Foundation
		 * uses 'large-6' to do the same thing. This utility removes classes that start with a particular prefix
		 * to remove whatever sizing class was specified.
		 *
		 * @param  $strClassList
		 * @param  $strPrefix
		 * @return bool true if the class list changed
		 */
		public static function RemoveClassesByPrefix (&$strClassList, $strPrefix) {
			$aRet = array();
			$blnChanged = false;
			if ($strClassList) foreach (explode (' ', $strClassList) as $strClass) {
				if (strpos($strClass, $strPrefix) !== 0) {
					$aRet[] = $strClass;
				}
				else {
					$blnChanged = true;
				}
			}
			$strClassList = implode (' ', $aRet);
			return $blnChanged;
		}

		/**
		 * Render the given attribute array for html output. Escapes html entities enclosed in values. Uses
		 * double-quotes to surround the value. Precedes the resulting text with a space character.
		 *
		 * @param array $attributes
		 * @return string
		 */
		public static function RenderHtmlAttributes ($attributes) {
			$strToReturn = '';
			if ($attributes) {
				foreach ($attributes as $strName=>$strValue) {
					if ($strValue === false) {
						$strToReturn .= (' ' . $strName);
					} elseif (!is_null($strValue)) {
						$strToReturn .= (' ' . $strName . '="' . htmlspecialchars($strValue, ENT_COMPAT | ENT_HTML5, QApplication::$EncodingType) . '"');
					}
				}
			}
			return $strToReturn;
		}


		/**
		 * Render the given array as a css style string. It will NOT be escaped.
		 *
		 * @param array 	$styles		key/value array representing the styles.
		 * @return string	a string suitable for including in a css 'style' property
		 */
		public static function RenderStyles($styles) {
			if (!$styles) return '';
			return implode('; ', array_map(
				function ($v, $k) { return $k . ':' . $v; },
				$styles,
				array_keys($styles))
			);
		}

		/**
		 * Returns the given string formatted as an html comment that will go on its own line.
		 * @param string 	$strText
		 * @param bool 		$blnRemoveOnMinimize
		 * @return string
		 */
		public static function Comment($strText, $blnRemoveOnMinimize = true) {
			if ($blnRemoveOnMinimize && QApplication::$Minimize) {
				return '';
			}
			return  _nl() . '<!-- ' . $strText . ' -->' . _nl();

		}

		/**
		 * Generate a URL from components. This URL can be used in the QApplication::Redirect function, or applied to
		 * an anchor tag by setting the href attribute.
		 *
		 * @param string $strLocation			absolute or relative path to resource, depending on your protocol. If not needed, enter an empty string.
		 * @param array|null $queryParams		key->value array of query parameters to add to the location.
		 * @param string|null $strAnchor		anchor to add to the url
		 * @param string|null $strProtocol		protocol if specifying a resource outside of the current server
		 * @param string|null $strServer				server that the resource is on. Required if specifying a protocol.
		 * @param string|null $strUser					user name if needed. Some protocols like mailto and ftp need this
		 * @param string|null $strPassword				password if needed. Note that password is sent in the clear.
		 * @param string|null $intPort					port if different from default
		 * @return string
		 */
		public static function MakeUrl ($strLocation, $queryParams = null, $strAnchor = null, $strProtocol = null, $strServer = null, $strUser = null, $strPassword = null, $intPort = null) {
			// Basic URLs that are pointing to our own server
			$strUrl = $strLocation; // can be relative or absolute
			if ($queryParams)  {
				$strUrl .= '?' . http_build_query($queryParams);
			}
			if ($strAnchor) {
				$strUrl .= '#' . urlencode($strAnchor);
			}

			// More complex URLs. Once you specify protocol, you will need to specify the server too.
			if ($strProtocol) {
				assert('!empty($strServer)');

				// We do not do any checking at this point since URLs can be complex. It is up to you to build a correct URL.
				// If you use a protocol that expects an absolute path, you must start with a slash (http), or a relative path (mailto), leave the slash off.

				// Build server portion.
				if ($intPort) {
					$strServer .= ':' . $intPort;
				}
				if ($strUser) {
					$strUser = rawurlencode($strUser);
					if ($strPassword) {
						$strUser = $strUser . ':' . rawurlencode($strPassword);
					}
					$strServer = $strUser . '@' . $strServer;
				}
				$strUrl = $strProtocol . $strServer . $strUrl;
			}
			return $strUrl;
		}

		/**
		 * Returns a MailTo url.
		 *
		 * @param $strUser
		 * @param $strServer
		 * @param null $queryParams
		 * @return string
		 */
		public static function MailToUrl ($strUser, $strServer, $queryParams = null) {
			$strUrl = 'mailto:' . rawurlencode($strUser) . '@' . rawurlencode($strServer);
			if ($queryParams) {
				$strUrl .= '?' . http_build_query($queryParams, null, null, PHP_QUERY_RFC3986);
			}
			return $strUrl;
		}

		/**
		 * Utility function to create a link, i.e. an "a" tag.
		 *
		 *
		 *
		 * @param string $strUrl URL to link to. Use MakeUrl or MailToUrl to create the URL.
		 * @param string $strText The inner text. This WILL be escaped.
		 * @param array $attributes Other html attributes to include in the tag
		 * @param boolean $blnHtmlEntities False to prevent encoding
		 */
		public static function RenderLink ($strUrl, $strText, $attributes = null, $blnHtmlEntities = true) {
			$attributes["href"] = $strUrl;
			if ($blnHtmlEntities) {
				$strText = QApplication::HtmlEntities($strText);
			}
			return self::RenderTag("a", $attributes, $strText);
		}

	}
