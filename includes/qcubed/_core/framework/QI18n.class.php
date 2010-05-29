<?php

/**
 * Factory class for translations
 * @author Ago Luberg
 *
 */
class QI18n extends QBaseClass {
	/**
	 * Default translation class. If this is specified, both Initialize and Load
	 * methods will create an instance of given translation (if not different class
	 * is not specified).
	 * @var string
	 */
	public static $DefaultTranslationClass = 'QTranslationPoParser';

	/**
	 * Initializes application translation. Creates an instance of translation and sets it
	 * to QApplication::$LanguageObject
	 * @param string[optional] $strTranslationClass Translation class name
	 * @return QTranslationBase
	 */
	public static function Initialize($strTranslationClass = null) {
		if (!$strTranslationClass) $strTranslationClass = self::$DefaultTranslationClass;
		$mixInitializeMethod = array($strTranslationClass, 'Initialize');
		$objQI18n = call_user_func_array($mixInitializeMethod, array());
		if (!($objQI18n instanceof QTranslationBase)) {
			throw new QCallerException(sprintf("Translation class '%s' should extend QTranslationBase", $strTranslationClass));
		}
		QApplication::$LanguageObject = $objQI18n;
	}
	
	/**
	 * Loads translation with given language and country code. 
	 * @param string[optional] $strLanguageCode Language code
	 * @param string[optional] $strCountryCode Country code
	 * @param string[optional] $strTranslationClass Translation class name
	 * @return QTranslationBase
	 */
	public static function Load($strLanguageCode = null, $strCountryCode = null, $strTranslationClass = null) {
		if (!$strTranslationClass) $strTranslationClass = self::$DefaultTranslationClass;
		$mixLoadMethod = array($strTranslationClass, 'Load');
		$objQI18n = call_user_func_array($mixLoadMethod, array($strLanguageCode, $strCountryCode));
		if (!($objQI18n instanceof QTranslationBase)) {
			throw new QCallerException(sprintf("Translation class '%s' should extend QTranslationBase", $strTranslationClass));
		}
		return $objQI18n;
	}
}
