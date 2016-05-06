<?php
/**
 *
 * @package Tests
 */

if(!class_exists('TypeTest')){
	require_once __INCLUDES__ .'/model/TypeTest.class.php';
}

class QQMathOpTests extends QUnitTestCaseBase {

	public function testMathOp() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 1.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 2.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::GreaterThan(QQ::MathOp('*', QQN::TypeTest()->TestFloat, 2.0), 3.0));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(2.0, $objRes->TestFloat);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testMul() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 1.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 2.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::GreaterThan(QQ::Mul(QQN::TypeTest()->TestFloat, 2.0), 3.0));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(2.0, $objRes->TestFloat);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testDiv() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 4.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 8.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::GreaterThan(QQ::Div(QQN::TypeTest()->TestFloat, 2.0), 3.0));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(8.0, $objRes->TestFloat);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testSub() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 2.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 4.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::GreaterOrEqual(QQ::Sub(QQN::TypeTest()->TestFloat, 1.0), 3.0));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(4.0, $objRes->TestFloat);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testAdd() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 1.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 2.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::GreaterThan(QQ::Add(QQN::TypeTest()->TestFloat, 1.5), 3.0));
		$this->assertEquals(1, count($objResArray));
		if (count($objResArray) > 0) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(2.0, $objRes->TestFloat);
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testOrderBy() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 1.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 2.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::LessThan(
			QQ::Virtual('mul1', QQ::Mul(QQN::TypeTest()->TestFloat, -2.0))
			, -1.0
		),
		QQ::Clause(
			QQ::OrderBy(QQ::Virtual('mul1'))
			, QQ::Expand(QQ::Virtual('mul1'))
		));
		$this->assertEquals(2, count($objResArray));
		if (2 == count($objResArray)) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(2.0, $objRes->TestFloat);
				$this->assertEquals(-4.0, $objRes->GetVirtualAttribute('mul1'));
			}
			$objRes = $objResArray[1];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(1.0, $objRes->TestFloat);
				$this->assertEquals(-2.0, $objRes->GetVirtualAttribute('mul1'));
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testOrderByDesc() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 1.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 2.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::LessThan(
			QQ::Virtual('mul1', QQ::Mul(QQN::TypeTest()->TestFloat, -2.0))
			, -1.0
		),
		QQ::Clause(
			QQ::OrderBy(QQ::Virtual('mul1'), 'DESC')
			, QQ::Expand(QQ::Virtual('mul1'))
		));
		$this->assertEquals(2, count($objResArray));
		if (2 == count($objResArray)) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(1.0, $objRes->TestFloat);
				$this->assertEquals(-2.0, $objRes->GetVirtualAttribute('mul1'));
			}
			$objRes = $objResArray[1];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertEquals(2.0, $objRes->TestFloat);
				$this->assertEquals(-4.0, $objRes->GetVirtualAttribute('mul1'));
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}

	public function testSelect() {
		$objTest = new TypeTest();
		$objTest->TestFloat = 1.0;
		$objTest->Save();
		
		$objTest2 = new TypeTest();
		$objTest2->TestFloat = 2.0;
		$objTest2->Save();
		
		$objResArray = TypeTest::QueryArray(QQ::LessThan(
			QQ::Virtual('mul1', QQ::Mul(QQN::TypeTest()->TestFloat, -2.0))
			, -1.0
		),
		QQ::Clause(
			QQ::OrderBy(QQ::Virtual('mul1'))
			, QQ::Expand(QQ::Virtual('mul1'))
			, QQ::Select(QQ::Virtual('mul1'))
		));
		$this->assertEquals(2, count($objResArray));
		if (2 == count($objResArray)) {
			$objRes = $objResArray[0];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertNull($objRes->TestFloat);
				$this->assertEquals(-4.0, $objRes->GetVirtualAttribute('mul1'));
			}
			$objRes = $objResArray[1];
			$this->assertNotNull($objRes);
			if ($objRes) {
				$this->assertNull($objRes->TestFloat);
				$this->assertEquals(-2.0, $objRes->GetVirtualAttribute('mul1'));
			}
		}
		
		$objTest->Delete();
		$objTest2->Delete();
	}
}
?>
