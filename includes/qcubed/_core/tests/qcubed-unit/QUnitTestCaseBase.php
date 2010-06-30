<?php

/**
 * Base class for all QCubed unit tests. Contains shared functionality.
 * @package Tests
 */
class QUnitTestCaseBase extends UnitTestCase {

	/**
	 * Given an array of objects $arrObj, verifies that
	 * there is an object inside with a property $strPropertyName
	 * that equals $mixExpectedValue.
	 * 
	 * @param $arrObj Array of objects to look through
	 * @param $strPropertyName Name of the property to validate each item on
	 * @param $mixExpectedValue Value of the property to look for
	 *
	 * @return $mixObject The object in the array that had the property value we were looking for
	 */ 
	protected function verifyObjectPropertyHelper($arrObj, $strPropertyName, $mixExpectedValue) {
		$objResult = null;
		$className = "object";
		if (sizeof($arrObj) > 0) {
			foreach ($arrObj as $objItem) {
				if ($objItem->$strPropertyName == $mixExpectedValue) {
					$objResult = $objItem;
					break;
				}
			}
			$className = get_class($arrObj[0]);
		}
		
		$this->assertNotNull($objResult, "Found a " . $className . " with " . $strPropertyName . " = " . $mixExpectedValue);
		return $objResult;
	}
}
?>