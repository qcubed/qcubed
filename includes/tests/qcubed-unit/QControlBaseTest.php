<?php

/**
 * 
 * @package Tests
 */
class QControlBaseTests extends QUnitTestCaseBase {
	/**
	 *
	 * @var QControl 
	 */
	protected static $ctlTest;

	/**
	 * @beforeClass
	 */
	public static function setUpClass()
	{
		global $_FORM;
		self::$ctlTest = $_FORM->ctlTest;
	}

	protected function helpTest($objTestDataArray, $objProperiesArray, $strGetStyleMethod = "GetWrapperStyleAttributes") {
		foreach ($objProperiesArray as $strProperty => $strCssProperty) {
			$strValue = $objTestDataArray["Value"];
			if ($strProperty) {
				self::$ctlTest->$strProperty = $strValue;
			} else {
				self::$ctlTest->SetCssStyle ($strCssProperty, $strValue, true);
			}
			
			$strAttrs = self::$ctlTest->$strGetStyleMethod() . ';';
			
			$intResult = strpos($strAttrs, $strCssProperty . ':' . $objTestDataArray["Expected"]);
			$strMessage =
				$objTestDataArray["Msg"] .
				" Expected: '" . $objTestDataArray["Expected"] . "'" .
				" Obtained: '" . $strAttrs . "'";
			$this->assertTrue(false !== $intResult, $strMessage);
		}
	}

	public function testCss() {		
		$objCaseArray = array( 
			array(
				"Value" => "0", "Expected" => "0;", "Msg" => "String zero renders with no 'px'"
			),
			array(
				"Value" => "0.0", "Expected" => "0;", "Msg" => "String '0.0' renders with no 'px'"
			),
			array(
				"Value" => (int)0, "Expected" => "0;", "Msg" => "Integer zero renders with no 'px'"
			),
			array(
				"Value" => (float)0.0, "Expected" => "0;", "Msg" => "Float zero renders with no 'px'"
			),
			array(
				"Value" => (double)0.0, "Expected" => "0;", "Msg" => "Double zero renders with no 'px'"
			),
			array(
				"Value" => "0px", "Expected" => "0px;", "Msg" => "String value renders with no 'px'"
			),
			array(
				"Value" => "0px", "Expected" => "0px;", "Msg" => "String zero with 'px' renders with no additional 'px'"
			),

			array(
				"Value" => "1", "Expected" => "1px;", "Msg" => "String '1' renders with 'px'"
			),
			array(
				"Value" => "1.0", "Expected" => "1px;", "Msg" => "String '1.0' renders with 'px'"
			),
			array(
				"Value" => "1.1", "Expected" => "1.1px;", "Msg" => "String '1.1' renders with 'px'"
			),
			array(
				"Value" => (int)1, "Expected" => "1px;", "Msg" => "Integer 1 renders with 'px'"
			),
			array(
				"Value" => (float)1.0, "Expected" => "1px;", "Msg" => "Float 1 renders with 'px'"
			),
			array(
				"Value" => (float)1.1, "Expected" => "1.1px;", "Msg" => "Float 1.1 renders with 'px'"
			),
			array(
				"Value" => (double)1.0, "Expected" => "1px;", "Msg" => "Double 1 renders with 'px'"
			),
			array(
				"Value" => (double)1.1, "Expected" => "1.1px;", "Msg" => "Double 1.1 renders with 'px'"
			),
			array(
				"Value" => "1px", "Expected" => "1px;", "Msg" => "String value renders with no 'px'"
			),
			array(
				"Value" => "1px", "Expected" => "1px;", "Msg" => "String with 'px' renders with no additional 'px'"
			),

			array(
				"Value" => "0 0 0 0", "Expected" => "0 0 0 0;", "Msg" => "String with many values renders with no 'px'"
			),
			array(
				"Value" => "0px 0px 0px 0px", "Expected" => "0px 0px 0px 0px;", "Msg" => "String with many values renders with no additional 'px'"
			),
			array(
				"Value" => "1 1 1 1", "Expected" => "1 1 1 1;", "Msg" => "String with many values renders with no 'px'"
			),
			array(
				"Value" => "1px 1px 1px 1px", "Expected" => "1px 1px 1px 1px;", "Msg" => "String with many values renders with no additional 'px'"
			),
			array(
				"Value" => "dummy", "Expected" => "dummy;", "Msg" => "String with not a number value renders with no 'px'"
			),
			array(
				"Value" => "the dumbest", "Expected" => "the dumbest;", "Msg" => "String with multiple not a number values renders with no 'px'"
			)
		);

		foreach($objCaseArray as $objCase) {
			$this->helpTest($objCase, array("Left" => "left", "Top" => "top"), "GetWrapperStyleAttributes");
		}

		foreach($objCaseArray as $objCase) {
			$this->helpTest($objCase, array("BorderWidth" => "border-width", "Width" => "width", "Height" => "width", null => "margin"), "GetStyleAttributes");
		}

	}
	
	public function testAjaxChangeFormState() {
		if (!QApplication::$CliMode) {
			$this->assertTrue (self::$ctlTest->savedValue1 == 2, "Actions can change state for later queued actions.");
			$this->assertTrue (self::$ctlTest->savedValue2 == 2, "Actions can change state for later queued actions.");
		}
	}
	
}
?>