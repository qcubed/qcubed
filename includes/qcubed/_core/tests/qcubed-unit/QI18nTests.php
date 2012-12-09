<?php

class QI18nTests extends QUnitTestCaseBase {
	public function testTranslationBasic() {
		$this->setCustomTranslatorClass();

		$this->verifyTranslation("Required", QApplication::Translate("Required"), "Requis");
		$this->verifyTranslation("Hello", QApplication::Translate("Hello"), "Bonjour");

		// Verify the case when there's no translation available
		$this->verifyTranslation("Can't translate me!", QApplication::Translate("Can't translate me!"), "Can't translate me!");
	}

	public function testTranslationLoad() {
		$this->setCustomTranslatorClass();
		// Force spanish
		$translator = QI18n::Load('es');

		$this->verifyTranslation("Required", $translator->TranslateToken("Required"), "Obligatorio");		
		$this->verifyTranslation("Optional", $translator->TranslateToken("Optional"), "Opcional");

		// Verify the case when there's no translation available
		$this->verifyTranslation("Can't translate me!", $translator->TranslateToken("Can't translate me!"), "Can't translate me!");
	}
	
	public function testPoParserBasic() {
		QI18n::$DefaultTranslationClass = 'QTranslationPoParser';
		// Force spanish
		$translator = QI18n::Load('es');

		$this->verifyTranslation("List All", $translator->TranslateToken("List All"), "Mostrar Todos");
		$this->verifyTranslation("Create a New", $translator->TranslateToken("Create a New"), "Crear uno Nuevo");
		
		// Verify the case when there's no translation available
		$this->verifyTranslation("Can't translate me!", $translator->TranslateToken("Can't translate me!"), "Can't translate me!");
	}
	
	private function setCustomTranslatorClass() {
		require_once (__DOCROOT__ . __EXAMPLES__ . '/communication/sample_translator.class.php');
		// let's change translation class
		QI18n::$DefaultTranslationClass = 'QSampleTranslation';

		// Set default language to French
		QApplication::$LanguageCode = 'fr';
		QApplication::$CountryCode = null;
		QI18n::Initialize();
	}
	
	private function verifyTranslation($original, $translation, $expectedTranslation) {
		$this->assertEqual($translation, $expectedTranslation, "'" . $original . "' translates to '" . $translation . "'.");
	}
  
  public function tearDown() {
		// let's restore translation class, so that other tests use the default one
		QI18n::$DefaultTranslationClass = 'QTranslationPoParser';
	}		
}
