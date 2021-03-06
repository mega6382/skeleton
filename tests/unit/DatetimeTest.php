<?php

Mock::Generate ('A_Datetime_Range');
Mock::Generate ('A_Datetime_Duration');

class DatetimeTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDatetime_parseDate() {
  		$datetime = new A_Datetime();

		$datetime->parseDate('2001/12/20');
		$this->assertEqual($datetime->getDate(), '2001-12-20');

		$datetime->parseDate('12//20//2001');
		$this->assertEqual($datetime->getDate(), '2001-12-20');

		$datetime->parseDate('20.12.2001');
		$this->assertEqual($datetime->getDate(), '2001-12-20');

		$datetime->parseDate('12/20/01');
		$this->assertEqual($datetime->getDate(), '2001-12-20');

		$datetime->parseDate('12/20/01');
		$this->assertEqual($datetime->getDate(), '2001-12-20');

		$datetime->parseDate('1/2/01');
		$this->assertEqual($datetime->getDate(), '2001-02-01');

		$datetime->parseDate('020301');
		$this->assertEqual($datetime->getDate(), '2001-03-02');

		$datetime->parseDate('19990706');
		$this->assertEqual($datetime->getDate(), '1999-07-06');
		
		$datetime->setDayMonthOrder(false);
		$datetime->parseDate('1/2/01');
		$this->assertEqual($datetime->getDate(), '2001-01-02');

		$datetime->parseDate('040501');
		$this->assertEqual($datetime->getDate(), '2001-04-05');
		
	}
	
	function testDatetime_toString() {
  		$datetime = new A_Datetime();

  		$datetime->parseDate('2001/12/20 12:11:10');
		// default format is 'U' for timestamp so check
		$this->assertEqual("$datetime", '2001-12-20 12:11:10');
 		$datetime->setFormat('d.m.Y');
		$this->assertEqual("$datetime", '20.12.2001');
		
	}
	
	function testDatetime_Getters() {
  		$datetime = new A_Datetime();

  		$datetime->parseDate('2008/12/20 21:11:10');
		// default format is 'U' for timestamp so check
		$this->assertTrue($datetime->getYear() == 2008);
		$this->assertTrue($datetime->getMonth() == 12);
		$this->assertTrue($datetime->getDay() == 20);
		$this->assertTrue($datetime->getHour() == 21);
		$this->assertTrue($datetime->getMinute() == 11);
		$this->assertTrue($datetime->getSecond() == 10);
		
	}
	
	function testDatetime_getDate() {
  		$datetime = new A_Datetime();
  		$datetime->parseDate('2008-12-20 21:11:10');
  		
		$str = $datetime->getDate();
		$this->assertEqual($str, '2008-12-20');

		$str = $datetime->getDate(true);
		$this->assertEqual($str, '2008-12-20 21:11:10');
	}
	
	function testDatetime_getTime() {
  		$datetime = new A_Datetime();

		$str = $datetime->getTime();
		$this->assertEqual($str, date('G:i'));

		$str = $datetime->getTime(false, true);
		$this->assertEqual($str, date('G:i:s'));

		$str = $datetime->getTime(true);
		$this->assertEqual($str, date('g:i a'));

		$str = $datetime->getTime(true, true);
		$this->assertEqual($str, date('g:i:s a'));
	}
	
	function testDatetime_BeforeAfter() {
  		$date1 = new A_Datetime();
 		$date1->parseDate('2008/12/20 21:11:10');

  		$date2 = new A_Datetime();
 		$date2->parseDate('2008/12/21');
		$this->assertTrue($date1->isBefore($date2));

  		$date2->parseDate('2008/12/19');
		$this->assertTrue($date1->isAfter($date2));
		
	}

	function testIsWithinRangeReturnsTrue()	{
		$date1 = new A_Datetime();
		$date1->parseDate ('2008/12/22');
		$date2 = new A_Datetime();
		$date2->parseDate ('2008/12/21');
		$date3 = new A_Datetime();
		$date3->parseDate ('2008/12/23');
		$range = new MockA_Datetime_Range();
		$range->setReturnValue ('getStart', $date2->getTimestamp());
		$range->setReturnValue ('getEnd', $date3->getTimestamp());
		$this->assertTrue ($date1->isWithin ($range));
	}

	function testIsNotWithinRangeReturnsFalse()	{
		$date1 = new A_Datetime();
		$date1->parseDate ('2008/12/20');
		$date2 = new A_Datetime();
		$date2->parseDate ('2008/12/21');
		$date3 = new A_Datetime();
		$date3->parseDate ('2008/12/23');
		$range = new MockA_Datetime_Range();
		$range->setReturnValue ('getStart', $date2->getTimestamp());
		$range->setReturnValue ('getEnd', $date3->getTimestamp());
		$this->assertFalse ($date1->isWithin ($range));
	}

	function testAddReturnsNewDate()	{
		$date = new A_Datetime();
		$duration = new MockA_Datetime_Duration();
		$duration->setReturnValue ('toString', '+1 day');
		$duration->expectOnce ('setPositive');
		$duration->expectOnce ('toString');
		$date2 = $date->add ($duration);
		$this->assertEqual ($date2->getTimestamp(), strtotime ('+1 day', $date->getTimestamp()));
	}
	
	function testRemoveReturnsNewDate()	{
		$date = new A_Datetime();
		$duration = new MockA_Datetime_Duration();
		$duration->setReturnValue ('toString', '-1 days');
		$duration->expectOnce ('setNegative');
		$duration->expectOnce ('toString');
		$date2 = $date->remove ($duration);
		$this->assertEqual ($date2->getTimestamp(), strtotime ('-1 day', $date->getTimestamp())); 
	}
	
	function testArrayToString()	{
		$array1 = array('year'=>'2008','mon'=>'12','mday'=>'20','hours'=>'21','minutes'=>'11','seconds'=>'10');
		$array2 = array('year'=>'2008','month'=>'12','day'=>'20','hour'=>'21','minute'=>'11','second'=>'10');
		$array3 = array('year'=>'2009','month'=>'11','day'=>'10','hour'=>'01','minute'=>'21','second'=>'11');
		$datetime = new A_Datetime($array2);
		$str = $datetime->getDate(true);
		$this->assertEqual($str, '2008-12-20 21:11:10');

		$str = $datetime->arrayToString($array1);
		$this->assertEqual($str, '2008-12-20 21:11:10');

		$str = $datetime->arrayToString($array2);
		$this->assertEqual($str, '2008-12-20 21:11:10');
	}
	
}