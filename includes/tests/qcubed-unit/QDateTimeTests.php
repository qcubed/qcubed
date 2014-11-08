<?php
/**
 *
 * @package Tests
 */

if(!class_exists('TypeTest')){
	require_once __INCLUDES__ .'/model/TypeTest.class.php';
}

class QDateTimeTests extends QUnitTestCaseBase {
	public function testNow() {
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
		//$this->assertTrue($obj1->IsTimeNull());

		$obj2 = new QDateTime("March 2003");
		$this->assertFalse($obj2->IsNull());
		$this->assertFalse($obj2->IsDateNull());
		//$this->assertTrue($obj2->IsTimeNull());
	}

	public function testConstructor() {
		$dtNow = QDateTime::Now();

		$timestamp = time() + 100;
		$obj2 = QDateTime::FromTimestamp($timestamp);

		$this->assertNotEqual($dtNow, $obj2);

		$diff = $obj2->Difference($dtNow);

		$this->assertTrue($diff->IsPositive());
		$this->assertFalse($diff->IsNegative());
		$this->assertFalse($diff->IsZero());
		$this->assertEqual($diff->Minutes, 1);

		// being fuzzy here intentionally
		$this->assertTrue($diff->Seconds > 95);
		$this->assertTrue($diff->Seconds < 105);

		$dt2 = QDateTime::FromTimestamp($dtNow->Timestamp);
		$dt3 = new QDateTime ($dtNow);
		$this->assertTrue ($dt2->IsEqualTo($dt3));

		// test relative date format
		$dt2 = new QDateTime('last Monday');
		$this->assertEqual ($dt2->format('N'), 1);

		// test time only format
		$dt2 = new QDateTime('0:00:00');
		$this->assertEqual ($dt2->Hour, 0);
		$this->assertFalse ($dt2->IsTimeNull());

		// test timestamp constructor
		$dt2 = new QDateTime ('@' . $dtNow->Timestamp);
		$this->assertEqual($dt2->Hour, $dtNow->Hour);
	}


	public function testLimitedConstructors() {
		$dt1 = QDateTime::Now();
		$ts = $dt1->Timestamp;
		$strIso = $dt1->qFormat(QDateTime::FormatIsoCompressed);

		$dt2 = new QDateTime($dt1, null, QDateTime::DateAndTimeType);
		$dt3 = new QDateTime ($dt1);
		$this->assertTrue ($dt2->IsEqualTo($dt3));

		$dt2 = new QDateTime('@' . $ts, null, QDateTime::DateAndTimeType);
		$this->assertTrue ($dt2->IsEqualTo($dt1));
		$this->assertFalse($dt2->IsDateNull());
		$this->assertFalse($dt2->IsTimeNull());

		$dt2 = new QDateTime('@' . $ts, null, QDateTime::DateOnlyType);
		$this->assertFalse($dt2->IsDateNull());
		$this->assertTrue($dt2->IsTimeNull());

		$dt2 = new QDateTime('@' . $ts, null, QDateTime::TimeOnlyType);
		$this->assertTrue($dt2->IsDateNull());
		$this->assertFalse($dt2->IsTimeNull());

		$dt2 = new QDateTime($strIso, null, QDateTime::DateAndTimeType);
		$this->assertTrue ($dt2->IsEqualTo($dt1));
		$this->assertFalse($dt2->IsDateNull());
		$this->assertFalse($dt2->IsTimeNull());

		$dt2 = new QDateTime($strIso, null, QDateTime::DateOnlyType);
		$this->assertFalse($dt2->IsDateNull());
		$this->assertTrue($dt2->IsTimeNull());

		$dt2 = new QDateTime($strIso, null, QDateTime::TimeOnlyType);
		$this->assertTrue($dt2->IsDateNull());
		$this->assertFalse($dt2->IsTimeNull());
	}

	public function testTimeZoneIssues() {
		$tz = new DateTimeZone('America/Los_Angeles');
		$dt1 = new QDateTime ('11/02/14', $tz); // dst boundary date
		$this->assertEqual($dt1->getTimezone()->getName(), 'America/Los_Angeles');

		$dt2 = new QDateTime ($dt1, null, QDateTime::DateOnlyType);
		$this->assertEqual($dt2->getTimezone()->getName(), 'America/Los_Angeles');
		$this->assertTrue($dt2->IsTimeNull());

		$dt2->setTime(7,0,0);
		$this->assertEqual($dt2->Hour, 7);
		$this->assertEqual($dt2->getTimezone()->getName(), 'America/Los_Angeles');

		// Test a specific PHP 'bug'. Not sure if it is a bug, or just a way things work.
		$dt2 = new QDateTime ($dt1->format (DateTime::ISO8601), null, QDateTime::DateOnlyType);
		$dt2->setTime(7,0,0);
		$this->assertEqual($dt2->Hour, 7);

		$dt2 = new QDateTime('1/1/14', new DateTimeZone('America/Los_Angeles'));
		$dt2->Timestamp = 1288486753;
		$this->assertEqual($dt2->getTimezone()->getName(), 'America/Los_Angeles'); // make sure timezone isn't changed
		$this->assertEqual($dt2->Timestamp, 1288486753); // this isn't always true. If this is a dst boundary, it will not be true. Just making sure it is true when its supposed to be

	}

	/*
	public function testDateAndTimeConstructor() {
		$tz = new DateTimeZone('America/Los_Angeles');
		$dt1 = new QDateTime ('11/1/14', $tz, QDateTime::DateOnlyType); // dst date
		$dt2 = new QDateTime ('7:00', $tz, QDateTime::TimeOnlyType);

		$dt1->SetTime ($dt2);
		$this->assertEqual($dt2->Hour, 7);
	}
*/

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
		$this->assertTrue($obj1->IsTimeNull(), "Setting only a date after null constructor keeps time null");

		$obj2 = new QDateTime("2002-03-15");
		$obj3 = new QDateTime("2002-03-15 13:15");
		$obj4 = new QDateTime("2002-03-16");

		$this->assertTrue($obj1->IsEqualTo($obj2));
		$this->assertTrue($obj1->IsEqualTo($obj3)); // dates are the same!

		$this->assertFalse($obj3->IsEqualTo($obj4)); // dates are different!


		$obj5 = new QDateTime ('13:15:02', null, QDateTime::TimeOnlyType);
		$this->assertTrue($obj5->IsDateNull(), "Setting only a date after null constructor keeps time null");
		$obj6 = new QDateTime ('2002-03-15 13:15:02');


		$obj1->SetTime($obj5);

		$this->assertFalse($obj1->IsTimeNull(), "Setting a time with object results in a change in null time status");
		$this->assertTrue($obj1->IsEqualTo($obj6), "SetTime correctly combines date only and time only values");
	}

	public function testFormat() {
		$obj1 = new QDateTime("2002-3-5 13:15");

		$this->assertEqual($obj1->qFormat("M/D/YY h:mm z"), "3/5/02 1:15 pm");
		$this->assertEqual($obj1->qFormat("DDD MMM D YYYY"), "Tue Mar 5 2002");
		$this->assertEqual($obj1->qFormat("One random DDDD in MMMM"), "One random Tuesday in March");

		//  Back compat
		$this->assertEqual($obj1->qFormat("M/D/YY h:mm z"), $obj1->qFormat("M/D/YY h:mm z"));
	}

	public function testFirstOfMonth() {
		$dt1 = new QDateTime("2/23/2009");
		$this->assertEqual($dt1->FirstDayOfTheMonth, new QDateTime("2/1/2009"));

		$dt2 = new QDateTime("12/2/2015");
		$this->assertEqual($dt2->FirstDayOfTheMonth, new QDateTime("12/1/2015"));

		// static function test
		$this->assertEqual(QDateTime::FirstDayOfTheMonth(1,1923), new QDateTime("1/1/1923"));
	}

	public function testLastOfMonth() {
		$dt1 = new QDateTime("2/23/2009");
		$this->assertEqual($dt1->LastDayOfTheMonth, new QDateTime("2/28/2009"));

		$dt2 = new QDateTime("1/1/1923");
		$this->assertEqual($dt2->LastDayOfTheMonth, new QDateTime("1/31/1923"));

		// Leap year tests
		$dt3 = new QDateTime("2/4/2000");
		$this->assertEqual($dt3->LastDayOfTheMonth, new QDateTime("2/29/2000"));

		$dt4 = new QDateTime("2/4/2016");
		$this->assertEqual($dt4->LastDayOfTheMonth, new QDateTime("2/29/2016"));

		// static function test
		$this->assertEqual(QDateTime::LastDayOfTheMonth(12, 2015), new QDateTime("12/31/2015"));
	}

	public function testSerialize() {
		$dt1 = QDateTime::Now();

		$str = serialize($dt1);
		$dt2 = unserialize($str);

		$this->assertTrue ($dt1->IsEqualTo($dt2));
		$this->assertEqual ($dt1->getTimezone()->getName(), $dt2->getTimezone()->getName());

	}

	public function testSave() {
		$objTest = new TypeTest();

		// Test null saves
		$objTest->Save();
		$id = $objTest->Id;

		$objTest2 = TypeTest::Load($id);
		$this->assertNull ($objTest2->Date, 'Null date is saved as a NULL in database');
		$this->assertNull ($objTest2->Time, 'Null time is saved as a NULL in database');
		$this->assertNull ($objTest2->DateTime, 'Null datetime is saved as a NULL in database');
		$objTest2->Delete();

		// Test empty saves
		$objTest = new TypeTest();
		$objTest->Date = new QDateTime();
		$objTest->Time = new QDateTime();
		$objTest->DateTime = new QDateTime();
		$objTest->Save();
		$id = $objTest->Id;
		$objTest2 = TypeTest::Load($id);
		$this->assertNull ($objTest2->Date, 'Empty date is saved as a NULL in database');
		$this->assertNull ($objTest2->Time, 'Empty time is saved as a NULL in database');
		$this->assertNull ($objTest2->DateTime, 'Empty datetime is saved as a NULL in database');
		$objTest2->Delete();

		// Test value combinations
		$objTest = new TypeTest();
		$objTest->Date = new QDateTime('2002-3-5', null, QDateTime::DateOnlyType);
		$objTest->Time = new QDateTime('13:15:02', null, QDateTime::TimeOnlyType);
		$objTest->DateTime = new QDateTime('2002-3-5 13:15:02');
		$objTest->Save();

		$id = $objTest->Id;
		$objTest2 = TypeTest::Load($id);
		$dt = clone($objTest2->Date);
		$dt->SetTime($objTest2->Time);
		$this->assertTrue ($dt->IsEqualTo($objTest2->DateTime), 'Combined dates and times after a save are correct.');
		$objTest2->Delete();
	}
}
?>
