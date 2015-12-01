<?php
/**
 * Utility code to make common control management and rendering tasks easier to type.
 *
 */

// Special Print Functions / Shortcuts
// NOTE: These are simply meant to be shortcuts to actual QCubed functional
// calls to make your templates a little easier to read.  By no means do you have to
// use them.  Your templates can just as easily make the fully-named method/function calls.

/**
 * Standard Print function.  To aid with possible cross-scripting vulnerabilities,
 * this will automatically perform QApplication::HtmlEntities() unless otherwise specified.
 *
 * @param string $strString string value to print
 * @param boolean $blnHtmlEntities perform HTML escaping on the string first
 */

function _p($strString, $blnHtmlEntities = true) {
	// Standard Print
	if ($blnHtmlEntities && (gettype($strString) != 'object'))
		print(QApplication::HtmlEntities($strString));
	else
		print($strString);
}

/**
 * Standard Print as Block function.  To aid with possible cross-scripting vulnerabilities,
 * this will automatically perform htmlspecialchars unless otherwise specified.
 *
 * Difference between _b() and _p() is that _b() will convert any linebreaks to <br/> tags.
 * This allows _b() to print any "block" of text that will have linebreaks in standard HTML.
 *
 * @param string $strString
 * @param boolean $blnHtmlEntities
 */
function _b($strString, $blnHtmlEntities = true) {
	// Text Block Print
	if ($blnHtmlEntities && (gettype($strString) != 'object'))
		print(QHtml::RenderString($strString));
	else
		print(nl2br($strString));
}

/**
 * Standard Print-Translated functions.
 *
 * Uses QApplication::Translate() to perform the translation (if applicable)
 *
 * @param string $strString string value to print via translation
 */
function _t($strString) {
	// Print, via Translation (if applicable)
	print(QApplication::Translate($strString));
}

/**
 * Translate
 * @param $strString
 * @return string
 */
function _tr($strString) {
	return QApplication::Translate($strString);
}

/**
 * Translate and print encoded for html.
 *
 * @param $strString
 * @param bool $blnHtmlEntities
 */
function _tp($strString, $blnHtmlEntities = true) {
	_p(_tr($strString), $blnHtmlEntities);
}

/**
 * Returns a newline for generating html, only if we are not in MINIMIZE mode. Do NOT use for generating
 * newlines inside of strings. The primary purpose of this function is to make HTML and other code that will appear
 * in a browser more readable and easier to debug in development mode.
 *
 * If a string is given, it will make sure the string ends with a newline, and return the string with a newline
 * attached to the end. If a newline is already there, the string will be returned as is. All this provided we
 * are not in MINIMIZE mode. Otherwise we return the string unchanged.
 *
 * @param string|null $strText
 * @return string
 */
function _nl($strText = null) {
	if (QApplication::$Minimize) {
		return $strText;
	} else {
		if ($strText === null) return "\n";
		if (!$strText) return '';	// don't add a shadow newline
		if (substr($strText, -1) == "\n") {
			return $strText; // text already ends with a newline
		} else {
			return $strText . "\n";
		}
	}
}

/**
 * Adds an indent to the beginning of every non-empty line in the string, for
 * the purpose of indenting the text once to the right so it is easier to read when viewing source in a browser and
 * debugging javascript.
 *
 * After some deliberation, since the purpose is mainly for browser source viewing and debugging, the current implementation
 * uses two spaces as an indent.
 *
 * @param string $strText
 * @param integer $intCount	The number of indents to add
 * @return string
 */
function _indent($strText, $intCount = 1) {
	if (!defined('__CODE_GENERATING__') && QApplication::$Minimize) {
		return $strText;
	} else {
		if (defined ('__CODE_GENERATING__')) {
			$strRepeat = '    ';
		} else {
			$strRepeat = '  ';
		}
		$strTabs = str_repeat($strRepeat, $intCount);
		$strRet = preg_replace ( '/^/m', $strTabs , $strText);
		return $strRet;
	}
}

/**
 * Render the control, checking for existence.
 *
 * We always return a value, rather than print, because this is designed to primarily be used in a PHP template,
 * and php now has a shorthand way of printing items by using the opening tag <?= .
 * 
 * @usage <?= _r($obj, {renderFunc}, ...); ?>
 * @param QControl $obj
 * @param null|string $strRenderFunc
 * @return null|string
 */
function _r($obj, $strRenderFunc = null /*, $overrides */) {
	$aParams = func_get_args();
	array_shift($aParams);
	array_shift($aParams);
	if ($obj) {
		if (!$strRenderFunc) {
			if ($obj->PreferredRenderMethod) {
				$strRenderFunc = $obj->PreferredRenderMethod;
			}
			else {
				$strRenderFunc = 'Render';
			}
		}
		if (count($aParams) == 1 && is_array($aParams[0])) {
			return $obj->$strRenderFunc(false, $aParams[0]); // single array of params which is a key->value array
		}
		elseif ($aParams) {
			return call_user_func_array([$obj, $strRenderFunc], $aParams);
		} else {
			return $obj->$strRenderFunc(false);
		}
	}
	return null;
}

/** TODO: Implement the following. */
/*
function _i($intNumber) {
	// Not Yet Implemented
	// Print Integer with Localized Formatting
}

function _f($intNumber) {
	// Not Yet Implemented
	// Print Float with Localized Formatting
}

function _c($strString) {
	// Not Yet Implemented
	// Print Currency with Localized Formatting
}*/

//////////////////////////////////////
