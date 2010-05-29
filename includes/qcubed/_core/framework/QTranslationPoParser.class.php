<?php

class QPoParserException extends QCallerException {}

class QTranslationPoParser implements QTranslationBase {
	public static function Initialize() {
		return self::Load(QApplication::$LanguageCode, QApplication::$CountryCode);
	}

	public static function Load($strLanguageCode = null, $strCountryCode = null) {
		$objLanguageObject = null;
		if ($strLanguageCode) {
			if ($strCountryCode) {
				$strCode = sprintf('%s_%s', $strLanguageCode, $strCountryCode);
				$strLanguageFiles = array(
				__QCUBED_CORE__ . '/i18n/' . $strLanguageCode . '.po',
				__QCUBED_CORE__ . '/i18n/' . $strCode . '.po',
				__QI18N_PO_PATH__ . '/' . $strLanguageCode . '.po',
				__QI18N_PO_PATH__ . '/' . $strCode . '.po'
				);
			} else {
				$strCode = $strLanguageCode;
				$strLanguageFiles = array(
				__QCUBED_CORE__ . '/i18n/' . $strLanguageCode . '.po',
				__QI18N_PO_PATH__ . '/' . $strLanguageCode . '.po'
				);
			}

			// Setup the LanguageFileObject cache mechanism
			$objCache = new QCache('i18n.po', $strCode, 'i18n', $strLanguageFiles);

			// If cached data exists and is valid, use it
			$strData = $objCache->GetData();
			if ($strData) {
				$objLanguageObject = unserialize($strData);

				// Otherwise, reload all langauge files and update the cache
			} else {
				$objLanguage = new QTranslationPoParser();
					
				foreach ($strLanguageFiles as $strLanguageFile) {
					if (file_exists($strLanguageFile)) {
						try {
							//print($strLanguageFile.'<BR>');
							$objLanguage->ParsePoData(file_get_contents($strLanguageFile));
						} catch (QPoParserException $objExc) {
							$objExc->setMessage('Invalid Language File: ' . $strLanguageFile . ': ' . $objExc->getMessage());
							$objExc->IncrementOffset();
							throw $objExc;
						}
					}
				}
				$objLanguageObject = $objLanguage;
				$objCache->SaveData(serialize($objLanguage));
			}
		}
		return $objLanguageObject;

	}

	const PoParseStateNone = 0;
	const PoParseStateMessageIdStart = 1;
	const PoParseStateMessageId = 2;
	const PoParseStateMessageStringStart = 3;
	const PoParseStateMessageString = 4;

	protected static function UnescapeContent($strContent) {
		$intLength = strlen($strContent);
		$strToReturn = '';
		$blnEscape = false;

		for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
			if ($blnEscape) {
				switch ($strContent[$intIndex]) {
					case 'n':
						$blnEscape = false;
						$strToReturn .= "\n";
						break;
					case 'r':
						$blnEscape = false;
						$strToReturn .= "\r";
						break;
					case 't':
						$blnEscape = false;
						$strToReturn .= "	";
						break;
					case '\\':
						$blnEscape = false;
						$strToReturn .= '\\';
						break;
					case '"':
						$blnEscape = false;
						$strToReturn .= '"';
						break;
					case "'":
						$blnEscape = false;
						$strToReturn .= "'";
						break;
					default:
						$blnEscape = false;
						$strToReturn .= '\\' . $strContent[$intIndex];
						break;
				}
			} else {
				if ($strContent[$intIndex] == '\\')
				$blnEscape = true;
				else
				$strToReturn .= $strContent[$intIndex];
			}
		}

		if ($blnEscape)
		return false;

		$strToReturn = str_replace("\r", '', $strToReturn);
		return $strToReturn;
	}

	protected function ParsePoData($strPoData) {
		$strPoData = str_replace("\r", '', trim($strPoData));
		$strPoLines = explode("\n", $strPoData);

		$strMatches = array();

		$intState = QTranslationPoParser::PoParseStateNone;
		$intLineCount = count($strPoLines);

		if (strlen($strPoLines[0]) == 0)
		return;

		for ($intLineNumber = 0; $intLineNumber < $intLineCount; $intLineNumber++) {
			$strPoLine = $strPoLines[$intLineNumber] = trim($strPoLines[$intLineNumber]);

			if (strlen($strPoLine) && (QString::FirstCharacter($strPoLine) != '#')) {
				switch ($intState) {
					case QTranslationPoParser::PoParseStateNone:
						$intCount = preg_match_all('/msgid(_[a-z0-9]+)?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
						if ($intCount && ($strMatches[0][0] == $strPoLine)) {
							$intLineNumber--;
							$intState = QTranslationPoParser::PoParseStateMessageIdStart;
						} else
						throw new QPoParserException('Invalid content for PoParseStateNone on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);
						break;

					case QTranslationPoParser::PoParseStateMessageIdStart:
						$intCount = preg_match_all('/msgid(_[a-z0-9]+)?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
						if ($intCount && ($strMatches[0][0] == $strPoLine)) {
							$strMessageId = array('', '', '', '', '', '', '');
							$strMessageString = array('', '', '', '', '', '', '');
							$intArrayIndex = 0;

							$strContent = QTranslationPoParser::UnescapeContent($strMatches[2][0]);
							if ($strContent === false)
							throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
							$strMessageId[$intArrayIndex] = $strContent;
							$intState = QTranslationPoParser::PoParseStateMessageId;
						} else
						throw new QPoParserException('Invalid content for PoParseStateMessageIdStart on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);
						break;

					case QTranslationPoParser::PoParseStateMessageId:
						$intCount = preg_match_all('/msgid(_[a-z0-9]+)[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
						if ($intCount && ($strMatches[0][0] == $strPoLine)) {
							if (strlen(trim($strMessageId[$intArrayIndex])) == 0)
							throw new QPoParserException('No MsgId content for current MsgId on Line ' . ($intLineNumber) . ': ' . $strPoLine);
							$intArrayIndex++;
							$strContent = QTranslationPoParser::UnescapeContent($strMatches[2][0]);
							if ($strContent === false)
							throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
							$strMessageId[$intArrayIndex] = $strContent;
							break;
						}

						$intCount = preg_match_all('/"([\S 	]*)"/', $strPoLine, $strMatches);
						if ($intCount && ($strMatches[0][0] == $strPoLine)) {
							$strContent = QTranslationPoParser::UnescapeContent($strMatches[1][0]);
							if ($strContent === false)
							throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
							$strMessageId[$intArrayIndex] .= $strContent;
							break;
						}

						$intCount = preg_match_all('/msgstr(\[[0-9]+\])?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
						if ($intCount && ($strMatches[0][0] == $strPoLine)) {
							if (strlen(trim($strMessageId[$intArrayIndex])) == 0)
							throw new QPoParserException('No MsgId content for current MsgId on Line ' . ($intLineNumber) . ': ' . $strPoLine);
							$intLineNumber--;
							$intState = QTranslationPoParser::PoParseStateMessageStringStart;
							break;
						}

						throw new QPoParserException('Invalid content for PoParseStateMessageId on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);

					case QTranslationPoParser::PoParseStateMessageStringStart:
						$intCount = preg_match_all('/msgstr(\[[0-9]+\])?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
						if ($intCount && ($strMatches[0][0] == $strPoLine)) {
							$intArrayIndex = 0;

							if (strlen($strMatches[1][0]))
							$intArrayIndex = intval(substr($strMatches[1][0], 1, strlen($strMatches[1][0]) - 2));

							$strContent = QTranslationPoParser::UnescapeContent($strMatches[2][0]);
							if ($strContent === false)
							throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
							$strMessageString[$intArrayIndex] = $strContent;
							$intState = QTranslationPoParser::PoParseStateMessageString;
						} else
						throw new QPoParserException('Invalid content for PoParseStateMessageStringStart on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);
						break;


					case QTranslationPoParser::PoParseStateMessageString:
						$intCount = preg_match_all('/msgid(_[a-z0-9]+)?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
						if ($intCount && ($strMatches[0][0] == $strPoLine)) {
							for ($intIndex = 0; $intIndex < count($strMessageId); $intIndex++)
							if (strlen(trim($strMessageId[$intIndex]))) {
								if (!strlen(trim($strMessageString[$intIndex]))) {
									$this->SetTranslation($strMessageId[$intIndex], "");
								}
								$this->SetTranslation($strMessageId[$intIndex], $strMessageString[$intIndex]);
							}

							$intLineNumber--;
							$intState = QTranslationPoParser::PoParseStateMessageIdStart;
							break;
						}

						$intCount = preg_match_all('/"([\S 	]*)"/', $strPoLine, $strMatches);
						if ($intCount && ($strMatches[0][0] == $strPoLine)) {
							$strContent = QTranslationPoParser::UnescapeContent($strMatches[1][0]);
							if ($strContent === false)
							throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
							$strMessageString[$intArrayIndex] .= $strContent;
							break;
						}

						$intCount = preg_match_all('/msgstr(\[[0-9]+\])?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
						if ($intCount && ($strMatches[0][0] == $strPoLine)) {

							if (strlen($strMatches[1][0]))
							$intArrayIndex = intval(substr($strMatches[1][0], 1, strlen($strMatches[1][0]) - 2));
							else
							throw new QPoParserException('No index specified for alternate MsgStr for PoParseStateMessageString on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);

							if (strlen(trim($strMessageId[$intArrayIndex])) == 0)
							throw new QPoParserException('No MsgId for MsgStr' . $strMatches[1][0] . ' for PoParseStateMessageString on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);

							$strContent = QTranslationPoParser::UnescapeContent($strMatches[2][0]);
							if ($strContent === false)
							throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
							$strMessageString[$intArrayIndex] = $strContent;
							break;
						}

						throw new QPoParserException('Invalid content for PoParseStateMessageString on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);

					default:
						throw new QPoParserException('Invalid PoParseState on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);
				}
			}
		}

		for ($intIndex = 0; $intIndex < count($strMessageId); $intIndex++) {
			if (strlen(trim($strMessageId[$intIndex]))) {
				if (!strlen(trim($strMessageString[$intIndex]))) {
					$this->SetTranslation($strMessageId[$intIndex], "");
				}
				$this->SetTranslation($strMessageId[$intIndex], $strMessageString[$intIndex]);
			}
		}
	}

	protected $strTranslationArray = array();

	protected function SetTranslation($strToken, $strTranslatedText) {
		$this->strTranslationArray[$strToken] = $strTranslatedText;
	}

	public function TranslateToken($strToken) {
		$strCleanToken = str_replace("\r", '', $strToken);
		if (array_key_exists($strCleanToken, $this->strTranslationArray)) {
			return $this->strTranslationArray[$strCleanToken];
		} else {
			return $strToken;
		}
	}

	public function VarDump() {
		$strToReturn = '';
		foreach ($this->strTranslationArray as $strKey=>$strValue) {
			$strKey = str_replace("\n", '\\n', addslashes(QApplication::HtmlEntities($strKey)));
			$strValue = str_replace("\n", '\\n', addslashes(QApplication::HtmlEntities($strValue)));
			$strToReturn .= sprintf("\"%s\"\n\"%s\"\n\n", $strKey, $strValue);
		}
		return $strToReturn;
	}
}
