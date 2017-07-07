<?php
/**
 *
 * @package Tests
 */

if(!class_exists('TypeTest')){
	require_once __INCLUDES__ .'/model/TypeTest.class.php';
}

class QQFuncTests extends QUnitTestCaseBase {
	protected function setUp()
	{
		TypeTest::DeleteAll();
	}

	public function testFunc() {
		$objTest = new TypeTest();
		$objTest->TestFloat = -1.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = -2.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::GreaterThan(QQ::Func('ABS', QQN::TypeTest()->TestFloat), 1.0));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(-2.0, $objRes->TestFloat);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}
	
	public function testAbs() {
		$objTest = new TypeTest();
		$objTest->TestFloat = -1.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = -2.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::GreaterThan(QQ::Abs(QQN::TypeTest()->TestFloat), 1.0));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(-2.0, $objRes->TestFloat);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testCeil() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 1.1;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 2.1;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::Equal(QQ::Ceil(QQN::TypeTest()->TestFloat), 2.0));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(1.1, $objRes->TestFloat);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testFloor() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 1.1;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 2.1;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::Equal(QQ::Floor(QQN::TypeTest()->TestFloat), 2.0));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(2.1, $objRes->TestFloat);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testMod() {
		$objTest = new TypeTest();
		$objTest->TestInt = 11;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestInt = 12;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::Equal(QQ::Mod(QQN::TypeTest()->TestInt, 10), 2));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(12, $objRes->TestInt);
			}
		}
		
		$objResArray = TypeTest::QueryArray(QQ::Equal(QQ::Mod(QQN::TypeTest()->TestInt, 10), 1));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(11, $objRes->TestInt);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testPower() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 2.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 3.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::Equal(QQ::Power(QQN::TypeTest()->TestFloat, 2), 9.0));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(3.0, $objRes->TestFloat);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testSqrt() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 4.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 9.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::Equal(QQ::Sqrt(QQN::TypeTest()->TestFloat), 3.0));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(9.0, $objRes->TestFloat);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testOrderBy() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 2.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 3.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::GreaterThan(
			QQ::Virtual('power2', QQ::Power(QQN::TypeTest()->TestFloat, 2.0))
			, 1.0
		),
		QQ::Clause(
			QQ::OrderBy(QQ::Virtual('power2'))
			, QQ::Expand(QQ::Virtual('power2'))
		));
		$this->assertEquals(2, count($objResArray));
		if (2 == count($objResArray)) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(2.0, $objRes->TestFloat);
				$this->assertEquals(4.0, $objRes->GetVirtualAttribute('power2'));
			}
			$objRes = $objResArray[1];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(3.0, $objRes->TestFloat);
				$this->assertEquals(9.0, $objRes->GetVirtualAttribute('power2'));
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testOrderByDesc() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 2.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 3.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::GreaterThan(
			QQ::Virtual('power2', QQ::Power(QQN::TypeTest()->TestFloat, 2.0))
			, 1.0
		),
		QQ::Clause(
			QQ::OrderBy(QQ::Virtual('power2'), false)
			, QQ::Expand(QQ::Virtual('power2'))
		));
		$this->assertEquals(2, count($objResArray));
		if (2 == count($objResArray)) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(3.0, $objRes->TestFloat);
				$this->assertEquals(9.0, $objRes->GetVirtualAttribute('power2'));
			}
			$objRes = $objResArray[1];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(2.0, $objRes->TestFloat);
				$this->assertEquals(4.0, $objRes->GetVirtualAttribute('power2'));
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testSelect() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 2.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 3.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::GreaterThan(
			QQ::Virtual('power2', QQ::Power(QQN::TypeTest()->TestFloat, 2.0))
			, 1.0
		),
		QQ::Clause(
			QQ::OrderBy(QQ::Virtual('power2'))
			, QQ::Expand(QQ::Virtual('power2'))
			, QQ::Select(QQ::Virtual('power2'))
		));
		$this->assertEquals(2, count($objResArray));
		if (2 == count($objResArray)) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$blnError = false;
				try {
					$objRes->TestFloat;
				}
				catch (Exception $e) {
					$blnError = true;
				}
				$this->assertTrue($blnError, 'Accessing table column that was not loaded throws exception.');
				$this->assertEquals(4.0, $objRes->GetVirtualAttribute('power2'));
			}
			$objRes = $objResArray[1];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(9.0, $objRes->GetVirtualAttribute('power2'));
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}
}