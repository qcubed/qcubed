<?php
/**
 * 
 * @package Tests
 */
class QDateTimeTests extends QUnitTestCaseBase {    
	public function testNull() {
		$obj1 = QDateTime::Now();
		$this->assertFalse($obj1->IsNull());
		$this->assertFalse($obj1->IsDateNull());
		$this->assertFalse($obj1->IsTimeNull());
		
		$obj2 = QDateTime::Now(false);
		$this->assertFalse($obj2->IsNull());
		$this->assertFalse($obj2->IsDateNull());
		$this->assertTrue($obj2->IsTimeNull());
	}

	public function testIncompleteDates() {
		$obj1 = new QDateTime("Feb 12");
		$this->assertFalse($obj1->IsNull());
		$this->assertFalse($obj1->IsDateNull());
		$this->assertTrue($obj1->IsTimeNull());

		$obj2 = new QDateTime("March 2003");
		$this->assertFalse($obj2->IsNull());
		$this->assertFalse($obj2->IsDateNull());
		$this->assertTrue($obj2->IsTimeNull());
	}
	
	public function testConstructor() {
		$obj1 = QDateTime::Now();
		
		$timestamp = time() + 100;
		$obj2 = QDateTime::FromTimestamp($timestamp);
		
		$this->assertNotEqual($obj1, $obj2);
		
		$diff = $obj2->Difference($obj1);
		
		$this->assertTrue($diff->IsPositive());
		$this->assertFalse($diff->IsNegative());
		$this->assertFalse($diff->IsZero());
		$this->assertEqual($diff->Minutes, 1);
				
		// being fuzzy here intentionally
		$this->assertTrue($diff->Seconds > 95); 
		$this->assertTrue($diff->Seconds < 105);
	}
	
	public function testOperations() {
		$obj1 = QDateTime::Now();
		$obj1->AddYears(-1);
		$obj1->AddSeconds(-10);
		
		$obj2 = QDateTime::Now();
		$obj2->AddMonths(3);	
		
		$diff = $obj2->Difference($obj1);
		$this->assertTrue($diff->IsPositive());
		$this->assertEqual($diff->Months, 15);
	}
	
	public function testOperations2() {
		$obj1 = QDateTime::Now();
		$obj2 = new QDateTime($obj1); // exact same time

		$obj1->Year = $obj1->Year + 1;
		$obj1->AddDays(1);
		
		$diff = $obj2->Difference($obj1);
		$this->assertTrue($diff->IsNegative());
		$this->assertEqual($diff->Years, -1);
	}

	public function testRoundtrip() {
		$obj1 = QDateTime::Now();
		$obj2 = QDateTime::FromTimestamp($obj1->Timestamp);
		
		$this->assertTrue($obj1->IsEqualTo($obj2));
	}
	
	public function testSetProperties() {
		$obj1 = new QDateTime();
		$obj1->setDate(2002, 3, 15);
		
		$obj2 = new QDateTime("2002-03-15");
		$obj3 = new QDateTime("2002-03-15 13:15");
		$obj4 = new QDateTime("2002-03-16");
							  
		$this->assertTrue($obj1->IsEqualTo($obj2));
		$this->assertTrue($obj1->IsEqualTo($obj3)); // dates are the same!
		
		$this->assertFalse($obj3->IsEqualTo($obj4)); // dates are different!
	}
	
	public function testFormat() {
		$obj1 = new QDateTime("2002-3-5 13:15");
			
		$this->assertEqual($obj1->qFormat("M/D/YY h:mm z"), "3/5/02 1:15 pm");
		$this->assertEqual($obj1->qFormat("DDD MMM D YYYY"), "Tue Mar 5 2002");		
		$this->assertEqual($obj1->qFormat("One random DDDD in MMMM"), "One random Tuesday in March");
		
		//  Back compat
		$this->assertEqual($obj1->qFormat("M/D/YY h:mm z"), $obj1->__toString("M/D/YY h:mm z"));
	}
}
?>