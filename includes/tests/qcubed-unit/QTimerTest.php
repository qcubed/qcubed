<?php
/**
 * 
 * @package Tests
 */
class QTimerTests extends QUnitTestCaseBase {    
	public function testTimerBasic() {
		QTimer::start('timer1');
		$this->longOperation();
		$fltValue1 = QTimer::stop('timer1');
		$fltValue2 = QTimer::getTime('timer1');
		
		$this->assertTrue($fltValue1 > 0);
		
		// Comparing doubles for equality - using epsilon
		$this->assertTrue(abs($fltValue1 - $fltValue2) < 0.000000001);
	}

	public function testTimerResume() {
		QTimer::start('timer2');
		$this->longOperation();
		$fltValue1 = QTimer::stop('timer2');
		
		$this->assertTrue($fltValue1 > 0);

		QTimer::start('timer2');
		$this->longOperation();
		$fltValue2 = QTimer::stop('timer2');

		QTimer::start('timer2');
		$this->longOperation();
		$fltValue3 = QTimer::stop('timer2');
		
		// Comparing doubles - using epsilon
		$this->assertTrue($fltValue1 > 0); 
		$this->assertTrue($fltValue2 > 0); 
		$this->assertTrue($fltValue3 > 0); 
		$this->assertTrue($fltValue1 < $fltValue2);
		$this->assertTrue($fltValue2 < $fltValue3);
		
		$objTimer = QTimer::GetTimer('timer2');
		$this->assertEquals($objTimer->CountStarted, 3);
	}
	
	public function testReset() {
		QTimer::start('timerA');
		$this->longOperation();
		$fltValue1 = QTimer::GetTime('timerA');
		$this->longOperation();
		$fltValue2 = QTimer::GetTime('timerA');
		$this->longOperation();
		$fltValue3 = QTimer::reset('timerA');
		$fltValue4 = QTimer::stop('timerA');

		$this->assertTrue($fltValue1 > 0); 
		$this->assertTrue($fltValue2 > 0); 
		$this->assertTrue($fltValue3 > 0); 
		$this->assertTrue($fltValue4 > 0);
		$this->assertTrue($fltValue1 < $fltValue2);
		$this->assertTrue($fltValue2 < $fltValue3);
		$this->assertTrue($fltValue4 < $fltValue3); // because we've reset the timer
		
		$objTimer = QTimer::GetTimer('timerA');
		$this->assertEquals($objTimer->CountStarted, 2);
	}
	

	public function testExceptions1() {
		// requires v 5.3 of PHP UNIT
		$this->setExpectedException("QCallerException");
		QTimer::stop('timer4');
	}
	
	public function testExceptions2() {		
		$this->setExpectedException("QCallerException");
		QTimer::getTime('timer5');
	}
	
	public function testExceptions3() {		
		QTimer::start('timer6');
		$this->setExpectedException("QCallerException");
		QTimer::start('timer6');
	}
	
	public function testExceptions4() {
		$objTimer = QTimer::GetTimer('timer7');
		$this->assertEquals($objTimer, null, "Requests for non-existing timer objects should return null");
	}


	private function longOperation() {
		Person::LoadAll();
	}
}
?>