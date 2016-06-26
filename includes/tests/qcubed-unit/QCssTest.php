<?php

/**
 * 
 * @package Tests
 */
class QCssTests extends QUnitTestCaseBase {
	
	protected function helpTest($objTestDataArray) {
		$strValue = $objTestDataArray["Value"];
		$strAttrs = QHtml::FormatLength($strValue);

		$strMessage =
			$objTestDataArray["Msg"] .
			" Expected: '" . $objTestDataArray["Expected"] . "'" .
			" Obtained: '" . $strAttrs . "'";
		$this->assertEquals($objTestDataArray["Expected"], $strAttrs, $strMessage);
	}

	public function testFormatLength() {
		$objCaseArray = array( 
			array(
				"Value" => "0", "Expected" => "0", "Msg" => "String zero renders with no 'px'"
			),
			array(
				"Value" => "0.0", "Expected" => "0", "Msg" => "String '0.0' renders with no 'px'"
			),
			array(
				"Value" => (int)0, "Expected" => "0", "Msg" => "Integer zero renders with no 'px'"
			),
			array(
				"Value" => (float)0.0, "Expected" => "0", "Msg" => "Float zero renders with no 'px'"
			),
			array(
				"Value" => (double)0.0, "Expected" => "0", "Msg" => "Double zero renders with no 'px'"
			),
			array(
				"Value" => "0px", "Expected" => "0px", "Msg" => "String value renders with no 'px'"
			),
			array(
				"Value" => "0px", "Expected" => "0px", "Msg" => "String zero with 'px' renders with no additional 'px'"
			),

			array(
				"Value" => "1", "Expected" => "1px", "Msg" => "String '1' renders with 'px'"
			),
			array(
				"Value" => "1.0", "Expected" => "1px", "Msg" => "String '1.0' renders with 'px'"
			),
			array(
				"Value" => "1.1", "Expected" => "1.1px", "Msg" => "String '1.1' renders with 'px'"
			),
			array(
				"Value" => (int)1, "Expected" => "1px", "Msg" => "Integer 1 renders with 'px'"
			),
			array(
				"Value" => (float)1.0, "Expected" => "1px", "Msg" => "Float 1 renders with 'px'"
			),
			array(
				"Value" => (float)1.1, "Expected" => "1.1px", "Msg" => "Float 1.1 renders with 'px'"
			),
			array(
				"Value" => (double)1.0, "Expected" => "1px", "Msg" => "Double 1 renders with 'px'"
			),
			array(
				"Value" => (double)1.1, "Expected" => "1.1px", "Msg" => "Double 1.1 renders with 'px'"
			),
			array(
				"Value" => "1px", "Expected" => "1px", "Msg" => "String value renders with no 'px'"
			),
			array(
				"Value" => "1px", "Expected" => "1px", "Msg" => "String with 'px' renders with no additional 'px'"
			),

			array(
				"Value" => "0 0 0 0", "Expected" => "0 0 0 0", "Msg" => "String with many values renders with no 'px'"
			),
			array(
				"Value" => "0px 0px 0px 0px", "Expected" => "0px 0px 0px 0px", "Msg" => "String with many values renders with no additional 'px'"
			),
			array(
				"Value" => "1 1 1 1", "Expected" => "1 1 1 1", "Msg" => "String with many values renders with no 'px'"
			),
			array(
				"Value" => "1px 1px 1px 1px", "Expected" => "1px 1px 1px 1px", "Msg" => "String with many values renders with no additional 'px'"
			),
			array(
				"Value" => "dummy", "Expected" => "dummy", "Msg" => "String with not a number value renders with no 'px'"
			),
			array(
				"Value" => "the dumbest", "Expected" => "the dumbest", "Msg" => "String with multiple not a number values renders with no 'px'"
			)
		);

		foreach($objCaseArray as $objCase) {
			$this->helpTest($objCase);
		}

	}

	protected function helpTest2($objTestDataArray) {
		$strOldValue = $objTestDataArray["OldValue"];
		$newValue = $objTestDataArray["NewValue"];
		$blnChanged = QHtml::SetLength($strOldValue, $newValue);

		$this->assertEquals($objTestDataArray["Changed"], $blnChanged);
		$this->assertEquals($objTestDataArray["Expected"], $strOldValue);
		// problem with sending a percent sign into message, so we just use default message.
	}

	public function testSetLength() {
		$objCaseArray = array(
			array(
				"OldValue" => "0", "NewValue" => "0", "Changed"=>false, "Expected" => "0", "Msg" => "Zero set to zero."
			),
			array(
				"OldValue" => "1px", "NewValue" => "2em", "Changed"=>true, "Expected" => "2em", "Msg" => "1px changed to 2em."
			),
			array(
				"OldValue" => "1px", "NewValue" => "1px", "Changed"=>false, "Expected" => "1px", "Msg" => "1px not changed."
			),
			array(
				"OldValue" => "1px", "NewValue" => "+1", "Changed"=>true, "Expected" => "2px", "Msg" => "1 added to 1px."
			),
			array(
				"OldValue" => "1px", "NewValue" => "-1", "Changed"=>true, "Expected" => "0px", "Msg" => "1 subtracted from 1px." //??? should we drop the px?
			),
			array(
				"OldValue" => "1em", "NewValue" => "/2", "Changed"=>true, "Expected" => "0.5em", "Msg" => "1em divided in half."
			),
			array(
				"OldValue" => "1em", "NewValue" => "*2", "Changed"=>true, "Expected" => "2em", "Msg" => "1em multiplied by 2."
			),
			array(
				"OldValue" => "1.1em", "NewValue" => "*2", "Changed"=>true, "Expected" => "2.2em", "Msg" => "1.1em multiplied by 2."
			),
			array(
				"OldValue" => '10%', "NewValue" => "*2", "Changed"=>true, "Expected" => '20%', "Msg" => '10 percent multiplied by 2.'
			)
		);

		foreach($objCaseArray as $objCase) {
			$this->helpTest2($objCase);
		}
	}

	protected function helpTestAddClass($objTestDataArray) {
		$strOldValue = $objTestDataArray["OldValue"];
		$newValue = $objTestDataArray["NewValue"];
		$blnChanged = QHtml::AddClass($strOldValue, $newValue);

		$this->assertEquals($objTestDataArray["Changed"], $blnChanged);
		$this->assertEquals($objTestDataArray["Expected"], $strOldValue);
		// problem with sending a percent sign into message, so we just use default message.
	}

	public function testAddClass() {
		$objCaseArray = array(
			array(
				"OldValue" => "", "NewValue" => "test", "Changed"=>true, "Expected" => "test"
			),
			array(
				"OldValue" => "test", "NewValue" => "test", "Changed"=>false, "Expected" => "test"
			),
			array(
				"OldValue" => "test class2", "NewValue" => "test", "Changed"=>false, "Expected" => "test class2"
			),
			array(
				"OldValue" => "test", "NewValue" => "class2", "Changed"=>true, "Expected" => "test class2"
			),
			array(
				"OldValue" => "test", "NewValue" => "class2 class3", "Changed"=>true, "Expected" => "test class2 class3"
			)
		);

		foreach($objCaseArray as $objCase) {
			$this->helpTestAddClass($objCase);
		}
	}

	protected function helpTestRemoveClass($objTestDataArray) {
		$strOldValue = $objTestDataArray["OldValue"];
		$newValue = $objTestDataArray["NewValue"];
		$blnChanged = QHtml::RemoveClass($strOldValue, $newValue);

		$this->assertEquals($objTestDataArray["Changed"], $blnChanged);
		$this->assertEquals($objTestDataArray["Expected"], $strOldValue);
		// problem with sending a percent sign into message, so we just use default message.
	}

	public function testRemoveClass() {
		$objCaseArray = array(
			array(
				"OldValue" => "", "NewValue" => "test", "Changed"=>false, "Expected" => ""
			),
			array(
				"OldValue" => "test", "NewValue" => "test", "Changed"=>true, "Expected" => ""
			),
			array(
				"OldValue" => "test class2", "NewValue" => "test", "Changed"=>true, "Expected" => "class2"
			),
			array(
				"OldValue" => "test", "NewValue" => "class2", "Changed"=>false, "Expected" => "test"
			),
			array(
				"OldValue" => "test class2 class3", "NewValue" => "test class3", "Changed"=>true, "Expected" => "class2"
			)
		);

		foreach($objCaseArray as $objCase) {
			$this->helpTestRemoveClass($objCase);
		}
	}

	public function testJavascriptDataConversions() {
		$objCaseArray = array(
			array(
				"Value" => "bob", "Expected" => "bob"
			),
			array(
				"Value" => "Bob", "Expected" => "-bob"
			),
			array(
				"Value" => "bobSmith", "Expected" => "bob-smith"
			),
			array(
				"Value" => "bobSmithJones", "Expected" => "bob-smith-jones"
			)
		);

		foreach($objCaseArray as $objCase) {
			$newValue = JavaScriptHelper::dataNameFromCamelCase($objCase["Value"]);
			$this->assertEquals($objCase["Expected"], $newValue);
		}

		$objCaseArray = array(
			array(
				"Value" => "bob", "Expected" => "bob"
			),
			array(
				"Value" => "-bob", "Expected" => "Bob"
			),
			array(
				"Value" => "bob-smith", "Expected" => "bobSmith"
			),
			array(
				"Value" => "bob-smith-jones", "Expected" => "bobSmithJones"
			)
		);

		foreach($objCaseArray as $objCase) {
			$newValue = JavaScriptHelper::dataNameToCamelCase($objCase["Value"]);
			$this->assertEquals($objCase["Expected"], $newValue);
		}

	}


}
?>