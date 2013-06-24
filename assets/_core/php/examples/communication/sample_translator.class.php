<?php
class QSampleTranslation implements QTranslationBase {
	protected $strTranslationArray = array();
	protected $strCode;
	public static function Initialize() {
		return self::Load(QApplication::$LanguageCode, QApplication::$CountryCode);
	}
	
	public static function Load($strLanguageCode = null, $strCountryCode = null) {
		return new QSampleTranslation($strLanguageCode, $strCountryCode);
	}
	
	public function __construct($strLanguageCode = null, $strCountryCode = null) {
		$strCode = '';
		if ($strLanguageCode) {
			if ($strCountryCode) {
				$strCode = sprintf('%s_%s', $strLanguageCode, $strCountryCode);
			} else {
				$strCode = $strLanguageCode;
			}
		}
		$this->strCode = $strCode;
		// here only relevant translation tokens from database can be loaded
		// instead, we just initialize array
		$this->strTranslationArray = array(
			// array of (token => translation) pairs
			'fr' => array(
				'Required' => 'Requis', 
				'Optional' => 'Facultatif',
				'Hello' => 'Bonjour'
			),
			
			'es' => array(
				'Required' => 'Obligatorio', 
				'Optional' => 'Opcional'
			)
		);
	}
	
	public function TranslateToken($strToken) {
		if (isset($this->strTranslationArray[$this->strCode]) &&
				isset($this->strTranslationArray[$this->strCode][$strToken])) {
			 return $this->strTranslationArray[$this->strCode][$strToken];
		} else {
			// Otherwise, if no translation found, just return the original, untranslated term
			return $strToken;
		}
	}
}


?>
