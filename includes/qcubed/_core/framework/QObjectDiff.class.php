<?php

/**
 * This class can be used to compare two objects and determine what
 * are the fields that are different between the two. It can be used
 * on plain-old PHP objects, as well as QCodo CRUD-generated objects
 * that represent database classes.
 *
 * Why would you want to do this - for example, to have an audit
 * trail that tells you which fields were changed after a user
 * has edited a form.
 *
 * To use, simply call QObjectDiff::Compare($obj1,$obj2). The
 * returned result will be a simple object of type QComparisonResult.
 * Look through the values of the arrDifferentFields field in that object
 * to determine which fields are different between two objects.
 * 
 * Important notes:
 * 1) this class cannot be used to compare the values of private properties.
 * This is because of a limitation in PHP5.
 * 2) this class compares the values of the actual objects only - not child
 * objects. If you want to compare child objects, you'll have to call
 * QObjectDiff::Compare() on them explicitly. 
 *
 * @author Alex Weinstein alex94040@yahoo.com
 */
class QObjectDiff extends QBaseClass {
	public static function Compare($obj1, $obj2) {		
		// initialize to status = identical, until proven otherwise
		$result = new QComparisonResult(QComparisonResult::RESULT_OBJECTS_IDENTICAL);

		$class1 = get_class($obj1);
		$class2 = get_class($obj2);

		if (!($class1 == $class2)) {
			return new QComparisonResult(QComparisonResult::RESULT_OBJECTS_DIFFERENT + QComparisonResult::RESULT_OBJECTS_OF_DIFFERENT_CLASS);
		}

		// Using reflection, we'll get the list of properties for
		// each of the objects, and compare the values.
		// Note that PRIVATE fields cannot be compared -
		// PHP does not allow reflection to look at private fields. 
		$reflection1 = new ReflectionObject($obj1);
		$reflection2 = new ReflectionObject($obj2);

		$staticProperties1 = $reflection1->getProperties();
		$staticProperties2 = $reflection2->getProperties();
		
		foreach ($staticProperties2 as $property2) {
			$staticProperties2Arr[$property2->name] = $property2;
		}
		
		foreach ($staticProperties1 as $property1) {
			if(isset($staticProperties2Arr[$property1->name])) { 
				$found = true;

				if ($obj1 instanceof QBaseClass && substr_count($property1->name, "__") > 0) {
					// skip virtual properties - QCubed specific
					continue;
				}
				
				if ($obj1 instanceof QBaseClass) {
					// QCubed-specific stuff
					
					if (substr($property1->name, 0, 3) == "obj") {
						// skip child objects
						continue;
					}

					if (substr($property1->name, 0, 8) == "blnDirty") {
						// skip indicators of whether the child object is dirty
						continue;
					}
					
					// removing the type prefix that QCubed adds,
					// knowing that the field with a shorter name exists
					// and it's publically accessible through the __get method
					$propertyName = substr($property1->name, 3);					
				} else {
					$propertyName = $property1->name;
				}

				$oldValue = $obj1->$propertyName;
				$newValue = $obj2->$propertyName;

				if ($oldValue != $newValue) {
					if ($result->ComparisonStatus != QComparisonResult::RESULT_OBJECTS_DIFFERENT) {
						$result = new QComparisonResult(QComparisonResult::RESULT_OBJECTS_DIFFERENT);
					}
					$objChangedField = new QChangedField();

					$objChangedField->strFieldName  = $propertyName;
					$objChangedField->mixOldValue   = $oldValue;
					$objChangedField->mixNewValue   = $newValue;

					$result->AddDifferentField($objChangedField);
				}
			}
		}

		return $result;
	}

}

class QComparisonResult extends QBaseClass {
	private $arrDifferentFields = array();    

	private $intComparisonStatus; // a value based on the constants below

	// For example, if objects are different and of different class, it will
	// be equal to RESULT_OBJECTS_DIFFERENT+RESULT_OBJECTS_OF_DIFFERENT_CLASS = 6.    
	const RESULT_OBJECTS_IDENTICAL          = 1;
	const RESULT_OBJECTS_DIFFERENT          = 2;
	const RESULT_OBJECTS_OF_DIFFERENT_CLASS = 4;
	
	public function __construct($intComparisonStatus) {
		$this->intComparisonStatus = $intComparisonStatus;
	}
	
	// Internal use only. Do not call. 
	public function AddDifferentField(QChangedField $objField) {
		$this->arrDifferentFields[] = $objField;
	}
	
	public function __toString() {
		$result = sprintf(QApplication::Translate("Comparison result: objects are %s") . "\r\n",
						  $this->GetFriendlyComparisonStatus());
		if (sizeof($this->arrDifferentFields) > 0) {
			$result .= sprintf(QApplication::Translate("%s field(s) are different:") . "\r\n",
							   sizeof($this->arrDifferentFields));
			$result .= implode("\r\n", $this->arrDifferentFields);
		}
		return $result;
	}
	
	public function __get($strName) {
		switch ($strName) {
			case 'DifferentFields':
				return $this->arrDifferentFields;
			case 'ComparisonStatus':
				return $this->intComparisonStatus;
			case 'FriendlyComparisonStatus':
				return $this->GetFriendlyComparisonStatus();
			default:
				throw new QUndefinedPropertyException('GET', 'QComparisonResult', $strName);
		}
	}
	
	private function GetFriendlyComparisonStatus() {
		switch ($this->intComparisonStatus) {
			case 1:
				return QApplication::Translate("identical");
			case 2:
				return QApplication::Translate("different");
			case 4:
			case 6:
				return QApplication::Translate("of different class");
			default:
				throw new Exception("Unknown object comparison status");
		}
	}
}

class QChangedField extends QBaseClass {
	public $strFieldName;
	public $mixOldValue;
	public $mixNewValue;

	public function __toString() {
		return $this->strFieldName . ": '" .
			$this->mixOldValue . "' => '" . $this->mixNewValue . "'";
	}
}

?>
