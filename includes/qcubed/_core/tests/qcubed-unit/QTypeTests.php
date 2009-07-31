<?php

class QTypeTests extends QUnitTestCaseBase {    
	public function testCasting() {
		$cases = array( 
				array("25.0", "25.0", (float)25.0, 'fail'),
				array("25.1", "25.1", (float)25.1, 'fail'),
				array(25.0, "25.0", (float)25.0, (int)25),
				array(25.1, "25.1", (float)25.1, 'fail'),
				array(true, "true", 'fail','fail'),
				array("true", "true", 'fail','fail'),
				array(false, "false", 'fail','fail'),
				array("false", "false", 'fail','fail'),
				array(1, "1", (float)1, 1),
				array(0, "0", (float)0, 0),
				array("1", "1", (float)1, 1),
				array("0", "0", (float)0, 0),
				array("25", "25", (float)25, 25),
				array(25, "25", (float)25,25),
				array(34.51666666667, "34.51666666667", (float)34.51666666667,'fail'),
				array(2147483648, "2147483648", (float)2147483648,"2147483648"),
				array(-2147483648, "-2147483648", (float)-2147483648,(int)-2147483648),
				array(-2147483649, "-2147483649", (float)-2147483649,"-2147483649"),
				array("34.51666666667", "34.51666666667", (float)34.51666666667,'fail'),
				array("2147483648", "2147483648", (float)2147483648.0,"2147483648"),
				array("-2147483648", "-2147483648", (float)-2147483648.0,(int)-2147483648),
				array("-2147483649", "-2147483649", (float)-2147483649.0,"-2147483649"),
				//this number is never stored at full accuracy, so there's no way to tell if it should be
				// an int (perhaps we should force it if it can be?)
				array(1844674407370955161616,"1844674407370955161616",(double)1844674407370955161616,"fail"), //"1844674407370955100000"
				//this one is
				array("1844674407370955161616","1844674407370955161616","1844674407370955161616","1844674407370955161616")
				);
		
		foreach($cases as $case)
		{
			$value = (string)$case[1].'('.gettype($case[0]).')';
			if($case[2] === 'fail')
			{
				try {  
					QType::Cast($case[0], QType::Float);
					$this->fail("Excepted exception was not thrown casting ".$value." to float");
				} catch(QInvalidCastException $e) {
					$this->pass("Casting ".$value." to Float caused a QInvalidCastException");
					unset($e);  
				}
			}
			else
			{
				try {
					$castValue = QType::Cast($case[0], QType::Float);
					$newValue = $castValue.'('.gettype($castValue).')';
					$this->assertIdentical($castValue, $case[2], "$value cast as a Float is $newValue");
				}
				catch(Exception $e)
				{
					$this->fail("Exception caused when casting $value to Float: {$e->getMessage()}");
				}
			}
			
			if($case[3] === 'fail')
			{
				try {  
					QType::Cast($case[0], QType::Integer);
					$this->fail("Excepted exception was not thrown casting ".$value." to Integer");
				} catch(QInvalidCastException $e) {
					$this->pass("Casting ".$value." to Integer caused a QInvalidCastException");
					unset($e);  
				}
			}
			else
			{
				try {
					$castValue = QType::Cast($case[0], QType::Integer);
					$newValue = $castValue.'('.gettype($castValue).')';
					$this->assertIdentical($castValue, $case[3], "$value cast as a Integer is $newValue");
				}
				catch(Exception $e)
				{
					$this->fail("Exception caused when casting $value to Integer");
				}
			}
		}
	}
}
?>