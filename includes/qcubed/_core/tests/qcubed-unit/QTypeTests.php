<?php
/**
 * 
 * @package Tests
 */
class QTypeTests extends QUnitTestCaseBase {    
	public function testCasting() {
		define('_FAIL_', 'fail');
		$cases = array( 
			//array(value, display, expected result, cast to type),
				array("25.0", "25.0", (float)25.0, QType::Float),
				array("25.1", "25.1", (float)25.1, QType::Float),
				array(25.0, "25.0", (float)25.0, QType::Float),
				array(25.1, "25.1", (float)25.1, QType::Float),
				array(true, "true", _FAIL_,QType::Float),
				array("true", "true", _FAIL_,QType::Float),
				array(false, "false", _FAIL_,QType::Float),
				array("false", "false", _FAIL_,QType::Float),
				array(1, "1", (float)1, QType::Float),
				array(0, "0", (float)0, QType::Float),
				array("1", "1", (float)1, QType::Float),
				array("0", "0", (float)0, QType::Float),
				array("25", "25", (float)25, QType::Float),
				array(25, "25", (float)25,QType::Float),
				array(34.51666666667, "34.51666666667", (float)34.51666666667,QType::Float),
				array(2147483648, "2147483648", (float)2147483648,QType::Float),
				array(-2147483648, "-2147483648", (float)-2147483648,QType::Float),
				array(-2147483649, "-2147483649", (float)-2147483649,QType::Float),
				array("34.51666666667", "34.51666666667", (float)34.51666666667,QType::Float),
				array("2147483648", "2147483648", (float)2147483648.0,QType::Float),
				array("-2147483648", "-2147483648", (float)-2147483648.0,QType::Float),
				array("-2147483649", "-2147483649", (float)-2147483649.0,QType::Float),

				array("25.0", "25.0", _FAIL_, QType::Integer),
				array("25.1", "25.1", _FAIL_, QType::Integer),
				array(25.0, "25.0", (int)25, QType::Integer),
				array(25.1, "25.1", _FAIL_, QType::Integer),
				array(true, "true", _FAIL_, QType::Integer),
				array("true", "true", _FAIL_, QType::Integer),
				array(false, "false", _FAIL_, QType::Integer),
				array("false", "false", _FAIL_, QType::Integer),
				array(1, "1", 1, QType::Integer),
				array(0, "0", 0, QType::Integer),
				array("1", "1", 1, QType::Integer),
				array("0", "0", 0, QType::Integer),
				array("25", "25", 25, QType::Integer),
				array(25, "25", 25, QType::Integer),
				array(34.51666666667, "34.51666666667", _FAIL_, QType::Integer),
				array(2147483648, "2147483648", "2147483648", QType::Integer),
				array(-2147483648, "-2147483648", (int)-2147483648, QType::Integer),
				array(-2147483649, "-2147483649", "-2147483649", QType::Integer),
				array("34.51666666667", "34.51666666667", _FAIL_, QType::Integer),
				array("2147483648", "2147483648", "2147483648", QType::Integer),
				array("-2147483648", "-2147483648", (int)-2147483648, QType::Integer),
				array("-2147483649", "-2147483649", "-2147483649", QType::Integer),

				//this number is never stored at full accuracy, so there's no way to tell if it should be
				// an int (perhaps we should force it if it can be?)
				array(1844674407370955161616,"1844674407370955161616",(double)1844674407370955161616, QType::Float), //"1844674407370955100000"
				array(1844674407370955161616,"1844674407370955161616","fail", QType::Integer), //"1844674407370955100000"

				//this one is
				array("1844674407370955161616","1844674407370955161616","1844674407370955161616", QType::Float),
				array("1844674407370955161616","1844674407370955161616","1844674407370955161616", QType::Integer),

				array(6, '6', '6', QType::String),
				array(6.94, '6.94', '6.94', QType::String),
				array(0.694*10, '6.94', '6.94', QType::String),
				);
		
		foreach($cases as $case)
		{
			$value = (string)$case[1].'('.gettype($case[0]).')';
			if($case[2] === _FAIL_)
			{
				try {  
					QType::Cast($case[0], $case[3]);
					$this->fail("Excepted exception was not thrown casting ".$value." to ".$case[3]);
				} catch(QInvalidCastException $e) {
					$this->pass("Casting ".$value." to ".$case[3]." caused a QInvalidCastException");
					unset($e);  
				}
			}
			else
			{
				try {
					$castValue = QType::Cast($case[0], $case[3]);
					$newValue = $castValue.'('.gettype($castValue).')';
					$this->assertIdentical($castValue, $case[2], "$value cast as a ".$case[3]." is $newValue");
				}
				catch(Exception $e)
				{
					$this->fail("Exception caused when casting $value to ".$case[3].": {$e->getMessage()}");
				}
			}
		}
	}
}
?>