<?php

	// These Aid with the PHP 5.2 DateTime error handling
	class QDateTimeNullException extends QCallerException {}
	function QDateTimeErrorHandler() {}

	/**
	 * QDateTime
	 * 
	 * This DateTime class provides a nice wrapper around the PHP DateTime class,
	 * which is included with all versions of PHP >= 5.2.0. It includes many enhancements,
	 * including the ability to specify a null date or time portion to represent a date only or
	 * time only object.
	 *
	 * Inherits from the php DateTime object, and the built-in methods are available for you to call
	 * as well. In particular, note that the built-in format, and the qFormat routines here take different
	 * specifiers. Feel free to use either.
	 *
	 * @property null|integer $Month
	 * @property null|integer $Day
	 * @property null|integer $Year
	 * @property null|integer $Hour
	 * @property null|integer $Minute
	 * @property null|integer $Second
	 * @property integer $Timestamp
	 * @property-read string $Age A string representation of the age relative to now.
	 * @property-read QDateTime $LastDayOfTheMonth A new QDateTime representing the last day of this date's month.
	 * @property-read QDateTime $FirstDayOfTheMonth A new QDateTime representing the first day of this date's month.
	 */
	class QDateTime extends DateTime {
		const Now = 'now';
		const FormatIso = 'YYYY-MM-DD hhhh:mm:ss';
		const FormatIsoCompressed = 'YYYYMMDDhhhhmmss';
		const FormatDisplayDate = 'MMM DD YYYY';
		const FormatDisplayDateFull = 'DDD, MMMM D, YYYY';
		const FormatDisplayDateTime = 'MMM DD YYYY hh:mm zz';
		const FormatDisplayDateTimeFull = 'DDDD, MMMM D, YYYY, h:mm:ss zz';
		const FormatDisplayTime = 'hh:mm:ss zz';
		const FormatRfc822 = 'DDD, DD MMM YYYY hhhh:mm:ss ttt';
		const FormatRfc5322 = 'DDD, DD MMM YYYY hhhh:mm:ss ttttt';

		const FormatSoap = 'YYYY-MM-DDThhhh:mm:ss';

		const UnknownType = 0;
		const DateOnlyType = 1;
		const TimeOnlyType = 2;
		const DateAndTimeType = 3;

		/** @var bool true if date is null */
		protected $blnDateNull = true;
		/** @var bool  true if time is null, rather than just zero (beginning of day) */
		protected $blnTimeNull = true;


		/**
		 * The "Default" Display Format
		 * @var string $DefaultFormat
		 */
		public static $DefaultFormat = QDateTime::FormatDisplayDateTime;
		
		/**
		 * The "Default" Display Format for Times
		 * @var string $DefaultTimeFormat
		 */
		public static $DefaultTimeFormat = QDateTime::FormatDisplayTime;

		/**
		 * The "Default" Display Format for Dates with null times
		 * @var string $DefaultDateOnlyFormat
		 */
		public static $DefaultDateOnlyFormat = QDateTime::FormatDisplayDate;


		/**
		 * Returns a new QDateTime object that's set to "Now"
		 * Set blnTimeValue to true (default) for a DateTime, and set blnTimeValue to false for just a Date
		 *
		 * @param boolean $blnTimeValue whether or not to include the time value
		 * @return QDateTime the current date and/or time
		 */
		public static function Now($blnTimeValue = true) {
			$dttToReturn = new QDateTime(QDateTime::Now);
			if (!$blnTimeValue) {
				$dttToReturn->blnTimeNull = true;
				$dttToReturn->ReinforceNullProperties();
			}
			return $dttToReturn;
		}

		/**
		 * Return Now as a string. Uses the default datetime format if none speicifed.
		 * @param string|null $strFormat
		 * @return string
		 */
		public static function NowToString($strFormat = null) {
			$dttNow = new QDateTime(QDateTime::Now);
			return $dttNow->qFormat($strFormat);
		}

		/**
		 * @return bool
		 */
		public function IsDateNull() {
			return $this->blnDateNull;
		}

		/**
		 * @return bool
		 */
		public function IsNull() {
			return ($this->blnDateNull && $this->blnTimeNull);
		}

		/**
		 * @return bool
		 */
		public function IsTimeNull() {
			return $this->blnTimeNull;
		}

		/**
		 * @param $strFormat
		 * @return string
		 */
		public function PhpDate($strFormat) {
			// This just makes a call to format
			return parent::format($strFormat);
		}

		/**
		 * @param $dttArray
		 * @return array|null
		 */
		public function GetSoapDateTimeArray($dttArray) {
			if (!$dttArray)
				return null;

			$strArrayToReturn = array();
			foreach ($dttArray as $dttItem)
				array_push($strArrayToReturn, $dttItem->qFormat(QDateTime::FormatSoap));
			return $strArrayToReturn;
		}

		/**
		 * Create from a unix timestamp. Improves over php by taking into consideration the
		 * timezone, so that the internal format is automatically converted to the internal timezone,
		 * or the default timezone.
		 *
		 * @param integer $intTimestamp
		 * @param DateTimeZone $objTimeZone
		 * @return QDateTime
		 */
		public static function FromTimestamp($intTimestamp, DateTimeZone $objTimeZone = null) {
			return new QDateTime(date('Y-m-d H:i:s', $intTimestamp), $objTimeZone);
		}

		/**
		 * Construct a QDateTime. Does a few things differently than the php version:
		 * - Always stores timestamps in local or given timezone, so time extraction is easy
		 * - Has settings to determine if you want a date only or time only type
		 * - Will NOT throw exceptions. Errors simply result in a null datetime.
		 *
		 * @param null|integer|string|QDateTime|DateTime $mixValue
		 * @param DateTimeZone $objTimeZone
		 * @param int $intType
		 */
		public function __construct($mixValue = null, DateTimeZone $objTimeZone = null, $intType = QDateTime::UnknownType) {
			if ($mixValue instanceof QDateTime) {
				// Cloning from another QDateTime object
				if ($objTimeZone)
					throw new QCallerException('QDateTime cloning cannot take in a DateTimeZone parameter');
				parent::__construct($mixValue->format('Y-m-d H:i:s'), $mixValue->GetTimeZone());
				$this->blnDateNull = $mixValue->IsDateNull();
				$this->blnTimeNull = $mixValue->IsTimeNull();
				$this->ReinforceNullProperties();

			} else if ($mixValue instanceof DateTime) {
				// Subclassing from a PHP DateTime object
				if ($objTimeZone)
					throw new QCallerException('QDateTime subclassing of a DateTime object cannot take in a DateTimeZone parameter');
				parent::__construct($mixValue->format('Y-m-d H:i:s'), $mixValue->getTimezone());

				// By definition, a DateTime object doesn't have anything nulled
				$this->blnDateNull = false;
				$this->blnTimeNull = false;
			} else if (!$mixValue) {
				// Set to "null date"
				// And Do Nothing Else -- Default Values are already set to Nulled out
				parent::__construct('2000-01-01 00:00:00', $objTimeZone);
			} else if (strtolower($mixValue) == QDateTime::Now) {
				// very common, so quickly deal with now string
				parent::__construct('now', $objTimeZone);
				$this->blnDateNull = false;
				$this->blnTimeNull = false;
			} else if (substr($mixValue, 0, 1) == '@') {
				// unix timestamp. PHP superclass will always store ts in UTC. Our class will store in given timezone, or local tz
				parent::__construct(date('Y-m-d H:i:s', substr($mixValue, 1)), $objTimeZone);
				$this->blnDateNull = false;
				$this->blnTimeNull = false;
			}
			else {
				// string relative date or time
				try {
					parent::__construct($mixValue, $objTimeZone);
					$this->blnDateNull = false;
					$this->blnTimeNull = false;
				} catch (Exception $objExc) {}

				$this->ReinforceNullProperties(); // in case error occurred, will set everything to null
			}

			// User is requesting to force a particular type.
			switch ($intType) {
				case QDateTime::DateOnlyType:
					$this->blnDateNull = false;
					$this->blnTimeNull = true;
					$this->ReinforceNullProperties();
					return;
				case QDateTime::TimeOnlyType:
					$this->blnDateNull = true;
					$this->blnTimeNull = false;
					$this->ReinforceNullProperties();
					return;
				case QDateTime::DateAndTimeType:
					$this->blnDateNull = false;
					$this->blnTimeNull = false;
				default:
					break;
			}
		}
		
		/**
		* Returns a new QDateTime object set to the last day of the specified month.
		* 
		* @param int month
		* @param int year
		* @return QDateTime the last day to a month in a year
		*/
		public static function LastDayOfTheMonth($intMonth, $intYear) {
			$temp = date('Y-m-t',mktime(0,0,0,$intMonth,1,$intYear));
			return new QDateTime($temp);
		}
		
		/**
		* Returns a new QDateTime object set to the first day of the specified month.
		* 
		* @param int month
		* @param int year
		* @return QDateTime the first day of the month
		*/
		public static function FirstDayOfTheMonth($intMonth, $intYear) {
			$temp = date('Y-m-d',mktime(0,0,0,$intMonth,1,$intYear));
			return new QDateTime($temp);
		}

		/**
		 * Formats a date as a string using the default format type.
		 * @return string
		 */
		public function __toString() {
			return $this->qFormat();
		}

		/**
		 * Outputs the date as a string given the format strFormat.  Will use
		 * the static defaults if none given.
		 *
		 * Properties of strFormat are (using Sunday, March 2, 1977 at 1:15:35 pm
		 * in the following examples):
		 *
		 *	M - Month as an integer (e.g., 3)
		 *	MM - Month as an integer with leading zero (e.g., 03)
		 *	MMM - Month as three-letters (e.g., Mar)
		 *	MMMM - Month as full name (e.g., March)
		 *
		 *	D - Day as an integer (e.g., 2)
		 *	DD - Day as an integer with leading zero (e.g., 02)
		 *	DDD - Day of week as three-letters (e.g., Wed)
		 *	DDDD - Day of week as full name (e.g., Wednesday)
		 *
		 *	YY - Year as a two-digit integer (e.g., 77)
		 *	YYYY - Year as a four-digit integer (e.g., 1977)
		 *
		 *	h - Hour as an integer in 12-hour format (e.g., 1)
		 *	hh - Hour as an integer in 12-hour format with leading zero (e.g., 01)
		 *	hhh - Hour as an integer in 24-hour format (e.g., 13)
		 *	hhhh - Hour as an integer in 24-hour format with leading zero (e.g., 13)
		 *
		 *	mm - Minute as a two-digit integer
		 *
		 *	ss - Second as a two-digit integer
		 *
		 *	z - "pm" or "am"
		 *	zz - "PM" or "AM"
		 *	zzz - "p.m." or "a.m."
		 *	zzzz - "P.M." or "A.M."
		 * 
		 *  ttt - Timezone Abbreviation as a three-letter code (e.g. PDT, GMT)
		 *  tttt - Timezone Identifier (e.g. America/Los_Angeles)
		 *
		 * @param string $strFormat the format of the date
		 * @return string the formatted date as a string
		 */
		public function qFormat($strFormat = null) {
			if (is_null($strFormat)) {
				if ($this->blnDateNull && !$this->blnTimeNull) {
					$strFormat = QDateTime::$DefaultTimeFormat;
				} elseif (!$this->blnDateNull && $this->blnTimeNull) {
					$strFormat = QDateTime::$DefaultDateOnlyFormat;
				} else {
					$strFormat = QDateTime::$DefaultFormat;
				}
			}

			/*
				(?(?=D)([D]+)|
					(?(?=M)([M]+)|
						(?(?=Y)([Y]+)|
							(?(?=h)([h]+)|
								(?(?=m)([m]+)|
									(?(?=s)([s]+)|
										(?(?=z)([z]+)|
											(?(?=t)([t]+)|
				))))))))
			*/

//			$strArray = preg_split('/([^D^M^Y^h^m^s^z^t])+/', $strFormat);
			preg_match_all('/(?(?=D)([D]+)|(?(?=M)([M]+)|(?(?=Y)([Y]+)|(?(?=h)([h]+)|(?(?=m)([m]+)|(?(?=s)([s]+)|(?(?=z)([z]+)|(?(?=t)([t]+)|))))))))/', $strFormat, $strArray);
			$strArray = $strArray[0];
			$strToReturn = '';

			$intStartPosition = 0;
			for ($intIndex = 0; $intIndex < count($strArray); $intIndex++) {
				$strToken = trim($strArray[$intIndex]);
				if ($strToken) {
					$intEndPosition = strpos($strFormat, $strArray[$intIndex], $intStartPosition);
					$strToReturn .= substr($strFormat, $intStartPosition, $intEndPosition - $intStartPosition);
					$intStartPosition = $intEndPosition + strlen($strArray[$intIndex]);

					switch ($strArray[$intIndex]) {
						case 'M':
							$strToReturn .= parent::format('n');
							break;
						case 'MM':
							$strToReturn .= parent::format('m');
							break;
						case 'MMM':
							$strToReturn .= parent::format('M');
							break;
						case 'MMMM':
							$strToReturn .= parent::format('F');
							break;
			
						case 'D':
							$strToReturn .= parent::format('j');
							break;
						case 'DD':
							$strToReturn .= parent::format('d');
							break;
						case 'DDD':
							$strToReturn .= parent::format('D');
							break;
						case 'DDDD':
							$strToReturn .= parent::format('l');
							break;
			
						case 'YY':
							$strToReturn .= parent::format('y');
							break;
						case 'YYYY':
							$strToReturn .= parent::format('Y');
							break;
			
						case 'h':
							$strToReturn .= parent::format('g');
							break;
						case 'hh':
							$strToReturn .= parent::format('h');
							break;
						case 'hhh':
							$strToReturn .= parent::format('G');
							break;
						case 'hhhh':
							$strToReturn .= parent::format('H');
							break;

						case 'mm':
							$strToReturn .= parent::format('i');
							break;
			
						case 'ss':
							$strToReturn .= parent::format('s');
							break;
			
						case 'z':
							$strToReturn .= parent::format('a');
							break;
						case 'zz':
							$strToReturn .= parent::format('A');
							break;
						case 'zzz':
							$strToReturn .= sprintf('%s.m.', substr(parent::format('a'), 0, 1));
							break;
						case 'zzzz':
							$strToReturn .= sprintf('%s.M.', substr(parent::format('A'), 0, 1));
							break;

						case 'ttt':
							$strToReturn .= parent::format('T');
							break;
						case 'tttt':
							$strToReturn .= parent::format('e');
							break;
						case 'ttttt':
							$strToReturn .= parent::format('O');
							break;

						default:
							$strToReturn .= $strArray[$intIndex];
					}
				}
			}

			if ($intStartPosition < strlen($strFormat))
				$strToReturn .= substr($strFormat, $intStartPosition);

			return $strToReturn;
		}

		/**
		 * Sets the time portion to the given time. If a QDateTime is given, will use the time portion of that object.
		 * Works around a problem in php that if you set the time across a daylight savings time boundary, the timezone
		 * does not advance. This version will detect that and advance the timezone.
		 *
		 * @param int|QDateTime $mixValue
		 * @param int|null $intMinute
		 * @param int|null $intSecond
		 * @return QDateTime
		 */
		public function setTime($mixValue, $intMinute = null, $intSecond = null) {
			if ($mixValue instanceof QDateTime) {
				if ($mixValue->IsTimeNull()) {
					$this->blnTimeNull = true;
					$this->ReinforceNullProperties();
					return $this;
				}
				// normalize the timezones
				$tz = $this->getTimezone();
				$name = $tz->getName();
				if (!preg_match('/[0-9]+/', $name)) {
					// php limits you to ID only timezones here, so make sure we have a timezone without numbers in it
					$mixValue->setTimezone ($this->getTimezone());
				}
				$intHour = $mixValue->Hour;
				$intMinute = $mixValue->Minute;
				$intSecond = $mixValue->Second;
			} else {
				$intHour = $mixValue;
			}
			// If HOUR or MINUTE is NULL...
			if (is_null($intHour) || is_null($intMinute)) {
				parent::setTime($intHour, $intMinute, $intSecond);
				$this->blnTimeNull = true;
				$this->ReinforceNullProperties();
				return $this;
			}

			$intHour = QType::Cast($intHour, QType::Integer);
			$intMinute = QType::Cast($intMinute, QType::Integer);
			$intSecond = QType::Cast($intSecond, QType::Integer);
			$this->blnTimeNull = false;

			/*
			// Possible fix for a PHP problem. Can't reproduce, so leaving code here just in case it comes back.
			// The problem is with setting times across dst barriers
			if ($this->Hour == 0 && preg_match('/[0-9]+/', $this->getTimezone()->getName())) {
				// fix a php problem with GMT and relative timezones
				$s = 'PT' . $intHour . 'H' . $intMinute . 'M' . $intSecond . 'S';
				$this->add (new DateInterval ($s));
				// will continue and set again to make sure, because boundary crossing will change the time
			}*/

			parent::setTime($intHour, $intMinute, $intSecond);

			return $this;
		}

		/**
		 * Set the date.
		 *
		 * @param int $intYear
		 * @param int $intMonth
		 * @param int $intDay
		 * @return $this|DateTime
		 */
		public function setDate($intYear, $intMonth, $intDay) {
			$intYear = QType::Cast($intYear, QType::Integer);
			$intMonth = QType::Cast($intMonth, QType::Integer);
			$intDay = QType::Cast($intDay, QType::Integer);
			$this->blnDateNull = false;
			parent::setDate($intYear, $intMonth, $intDay);
			return $this;
		}

		protected function ReinforceNullProperties() {
			if ($this->blnDateNull)
				parent::setDate(2000, 1, 1);
			if ($this->blnTimeNull)
				parent::setTime(0, 0, 0);
		}
		
		/**
		 * Converts the current QDateTime object to a different TimeZone.
		 * 
		 * TimeZone should be passed in as a string-based identifier.
		 * 
		 * Note that this is different than the built-in DateTime::SetTimezone() method which expicitly
		 * takes in a DateTimeZone object.  QDateTime::ConvertToTimezone allows you to specify any
		 * string-based Timezone identifier.  If none is specified and/or if the specified timezone
		 * is not a valid identifier, it will simply remain unchanged as opposed to throwing an exeception
		 * or error.
		 * 
		 * @param string $strTimezoneIdentifier a string-based parameter specifying a timezone identifier (e.g. America/Los_Angeles)
		 * @return void
		 */
		public function ConvertToTimezone($strTimezoneIdentifier) {
			try {
				$dtzNewTimezone = new DateTimeZone($strTimezoneIdentifier);
				$this->SetTimezone($dtzNewTimezone);
			} catch (Exception $objExc) {}
		}

		/**
		 * Returns true if give QDateTime is the same.
		 *
		 * @param QDateTime $dttCompare
		 * @return bool
		 */
		public function IsEqualTo(QDateTime $dttCompare) {
			// All comparison operations MUST have operands with matching Date Nullstates
			if ($this->blnDateNull != $dttCompare->blnDateNull)
				return false;

			// If mismatched Time nullstates, then only compare the Date portions
			if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
				// Let's "Null Out" the Time
				$dttThis = new QDateTime($this);
				$dttThat = new QDateTime($dttCompare);
				$dttThis->Hour = null;
				$dttThat->Hour = null;

				// Return the Result
				return ($dttThis->Timestamp == $dttThat->Timestamp);
			} else {
				// Return the Result for the both Date and Time components
				return ($this->Timestamp == $dttCompare->Timestamp);
			}
		}

		/**
		 * Returns true if current date time is earlier than the given one.
		 * @param QDateTime $dttCompare
		 * @return bool
		 */
		public function IsEarlierThan(QDateTime $dttCompare) {
			// All comparison operations MUST have operands with matching Date Nullstates
			if ($this->blnDateNull != $dttCompare->blnDateNull)
				return false;

			// If mismatched Time nullstates, then only compare the Date portions
			if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
				// Let's "Null Out" the Time
				$dttThis = new QDateTime($this);
				$dttThat = new QDateTime($dttCompare);
				$dttThis->Hour = null;
				$dttThat->Hour = null;

				// Return the Result
				return ($dttThis->Timestamp < $dttThat->Timestamp);
			} else {
				// Return the Result for the both Date and Time components
				return ($this->Timestamp < $dttCompare->Timestamp);
			}
		}

		/**
		 * Returns true if current date time is earlier than the given one.
		 * @param QDateTime $dttCompare
		 * @return bool
		 */
		public function IsEarlierOrEqualTo(QDateTime $dttCompare) {
			// All comparison operations MUST have operands with matching Date Nullstates
			if ($this->blnDateNull != $dttCompare->blnDateNull)
				return false;

			// If mismatched Time nullstates, then only compare the Date portions
			if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
				// Let's "Null Out" the Time
				$dttThis = new QDateTime($this);
				$dttThat = new QDateTime($dttCompare);
				$dttThis->Hour = null;
				$dttThat->Hour = null;

				// Return the Result
				return ($dttThis->Timestamp <= $dttThat->Timestamp);
			} else {
				// Return the Result for the both Date and Time components
				return ($this->Timestamp <= $dttCompare->Timestamp);
			}
		}

		/**
		 * Returns true if current date time is later than the given one.
		 * @param QDateTime $dttCompare
		 * @return bool
		 */
		public function IsLaterThan(QDateTime $dttCompare) {
			// All comparison operations MUST have operands with matching Date Nullstates
			if ($this->blnDateNull != $dttCompare->blnDateNull)
				return false;

			// If mismatched Time nullstates, then only compare the Date portions
			if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
				// Let's "Null Out" the Time
				$dttThis = new QDateTime($this);
				$dttThat = new QDateTime($dttCompare);
				$dttThis->Hour = null;
				$dttThat->Hour = null;

				// Return the Result
				return ($dttThis->Timestamp > $dttThat->Timestamp);
			} else {
				// Return the Result for the both Date and Time components
				return ($this->Timestamp > $dttCompare->Timestamp);
			}
		}

		/**
		 * Returns true if current date time is later than or equal to the given one.
		 * @param QDateTime $dttCompare
		 * @return bool
		 */
		public function IsLaterOrEqualTo(QDateTime $dttCompare) {
			// All comparison operations MUST have operands with matching Date Nullstates
			if ($this->blnDateNull != $dttCompare->blnDateNull)
				return false;

			// If mismatched Time nullstates, then only compare the Date portions
			if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
				// Let's "Null Out" the Time
				$dttThis = new QDateTime($this);
				$dttThat = new QDateTime($dttCompare);
				$dttThis->Hour = null;
				$dttThat->Hour = null;

				// Return the Result
				return ($dttThis->Timestamp >= $dttThat->Timestamp);
			} else {
				// Return the Result for the both Date and Time components
				return ($this->Timestamp >= $dttCompare->Timestamp);
			}
		}

		/**
		 * Returns the difference as a QDateSpan, which is easier to work with and more full featured than
		 * the php DateTimeInterval class.
		 *
		 * @param QDateTime $dttDateTime
		 * @return QDateTimeSpan
		 */
		public function Difference(QDateTime $dttDateTime) {
			$intDifference = $this->Timestamp - $dttDateTime->Timestamp;
			return new QDateTimeSpan($intDifference);
		}

		/**
		 * Add a datespan or interval to the current date.
		 *
		 * @param DateInterval|QDateTimeSpan $dtsSpan
		 * @return QDateTime
		 * @throws QCallerException
		 */
		public function Add($dtsSpan){
			if ($dtsSpan instanceof DateInterval) {
				parent::add($dtsSpan);
				return $this;
			}
			elseif (!$dtsSpan instanceof QDateTimeSpan) {
				throw new QCallerException("Can only add DateTimeInterval or QDateTimeSpan objects");
			}
			// Get this DateTime timestamp
			$intTimestamp = $this->Timestamp;

			// And add the Span Second count to it
			$this->Timestamp = $this->Timestamp + $dtsSpan->Seconds;
			return $this;
		}

		/**
		 * Add a number of seconds. Use negative value to go earlier in time.
		 *
		 * @param integer $intSeconds
		 * @return QDateTime
		 */
		public function AddSeconds($intSeconds){
			$this->Second += $intSeconds;
			return $this;
		}

		/**
		 * Add minutes to the time.
		 *
		 * @param integer $intMinutes
		 * @return QDateTime
		 */
		public function AddMinutes($intMinutes){
			$this->Minute += $intMinutes;
			return $this;
		}

		/**
		 * Add hours to the time.
		 *
		 * @param integer $intHours
		 * @return QDateTime
		 */
		public function AddHours($intHours){
			$this->Hour += $intHours;
			return $this;
		}

		/**
		 * Add days to the time.
		 *
		 * @param integer $intDays
		 * @return QDateTIme
		 */
		public function AddDays($intDays){
			$this->Day += $intDays;
			return $this;
		}

		/**
		 * Add months to the time.
		 *
		 * @param integer $intMonths
		 * @return QDateTime
		 */
		public function AddMonths($intMonths){
			$this->Month += $intMonths;
			return $this;
		}

		/**
		 * Add years to the time.
		 *
		 * @param integer $intYears
		 * @return QDateTime
		 */
		public function AddYears($intYears){
			$this->Year += $intYears;
			return $this;
		}

		/**
		 * Modifies the date or time based on values found int a string.
		 *
		 * @see DateTime::modify()
		 * @param string $mixValue
		 * @return QDateTime
		 */
		public function Modify($mixValue) {
			parent::modify($mixValue);
			return $this;
		}

		public function __get($strName) {
			switch ($strName) {
				case 'Month':
					if ($this->blnDateNull)
						return null;
					else
						return (int) parent::format('m');

				case 'Day':
					if ($this->blnDateNull)
						return null;
					else
						return (int) parent::format('d');

				case 'Year':
					if ($this->blnDateNull)
						return null;
					else
						return (int) parent::format('Y');

				case 'Hour':
					if ($this->blnTimeNull)
						return null;
					else
						return (int) parent::format('H');

				case 'Minute':
					if ($this->blnTimeNull)
						return null;
					else
						return (int) parent::format('i');

				case 'Second':
					if ($this->blnTimeNull)
						return null;
					else
						return (int) parent::format('s');

				case 'Timestamp':
					return (int) parent::format('U'); // range depends on the platform's max and min integer values

				case 'Age':
					// Figure out the Difference from "Now"
					$dtsFromCurrent = $this->Difference(QDateTime::Now());
					
					// It's in the future ('about 2 hours from now')
					if ($dtsFromCurrent->IsPositive())
						return $dtsFromCurrent->SimpleDisplay() . QApplication::Translate(' from now');

					// It's in the past ('about 5 hours ago')
					else if ($dtsFromCurrent->IsNegative()) {
						$dtsFromCurrent->Seconds = abs($dtsFromCurrent->Seconds);
						return $dtsFromCurrent->SimpleDisplay() . QApplication::Translate(' ago');

					// It's current
					} else
						return QApplication::Translate('right now');

				case 'LastDayOfTheMonth':
					return self::LastDayOfTheMonth($this->Month, $this->Year);
				case 'FirstDayOfTheMonth':
					return self::FirstDayOfTheMonth($this->Month, $this->Year);
				default:
					throw new QUndefinedPropertyException('GET', 'QDateTime', $strName);
			}
		}

		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'Month':
						if ($this->blnDateNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Month property on a null date.  Use SetDate().');
						if (is_null($mixValue)) {
							$this->blnDateNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setDate(parent::format('Y'), $mixValue, parent::format('d'));
						return $mixValue;

					case 'Day':
						if ($this->blnDateNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Day property on a null date.  Use SetDate().');
						if (is_null($mixValue)) {
							$this->blnDateNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setDate(parent::format('Y'), parent::format('m'), $mixValue);
						return $mixValue;

					case 'Year':
						if ($this->blnDateNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Year property on a null date.  Use SetDate().');
						if (is_null($mixValue)) {
							$this->blnDateNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setDate($mixValue, parent::format('m'), parent::format('d'));
						return $mixValue;

					case 'Hour':
						if ($this->blnTimeNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Hour property on a null time.  Use SetTime().');
						if (is_null($mixValue)) {
							$this->blnTimeNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setTime($mixValue, parent::format('i'), parent::format('s'));
						return $mixValue;

					case 'Minute':
						if ($this->blnTimeNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Minute property on a null time.  Use SetTime().');
						if (is_null($mixValue)) {
							$this->blnTimeNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setTime(parent::format('H'), $mixValue, parent::format('s'));
						return $mixValue;

					case 'Second':
						if ($this->blnTimeNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Second property on a null time.  Use SetTime().');
						if (is_null($mixValue)) {
							$this->blnTimeNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setTime(parent::format('H'), parent::format('i'), $mixValue);
						return $mixValue;

					case 'Timestamp':
						$mixValue = QType::Cast($mixValue, QType::Integer);
						$this->setTimestamp($mixValue);
						$this->blnDateNull = false;
						$this->blnTimeNull = false;
						return $mixValue;

					default:
						throw new QUndefinedPropertyException('SET', 'QDateTime', $strName);
				}
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}

/*
	This is a reference to the documentation for hte PHP DateTime classes (as of PHP 5.2)

      DateTime::ATOM
      DateTime::COOKIE
      DateTime::ISO8601
      DateTime::RFC822
      DateTime::RFC850
      DateTime::RFC1036
      DateTime::RFC1123
      DateTime::RFC2822
      DateTime::RFC3339
      DateTime::RSS
      DateTime::W3C

      DateTime::__construct([string time[, DateTimeZone object]])
      - Returns new DateTime object
      
      string DateTime::format(string format)
      - Returns date formatted according to given format
      
      long DateTime::getOffset()
      - Returns the DST offset
      
      DateTimeZone DateTime::getTimezone()
      - Return new DateTimeZone object relative to give DateTime
      
      void DateTime::modify(string modify)
      - Alters the timestamp
      
      array DateTime::parse(string date)
      - Returns associative array with detailed info about given date
      
      void DateTime::setDate(long year, long month, long day)
      - Sets the date
      
      void DateTime::setISODate(long year, long week[, long day])
      - Sets the ISO date
      
      void DateTime::setTime(long hour, long minute[, long second])
      - Sets the time
      
      void DateTime::setTimezone(DateTimeZone object)
      - Sets the timezone for the DateTime object
*/

/* Some quick and dirty test harnesses
	$dtt1 = new QDateTime();
	$dtt2 = new QDateTime();
	printTable($dtt1, $dtt2);
	$dtt2->setDate(2000, 1, 1);
	$dtt1->setTime(0,0,3);
	$dtt2->setTime(0,0,2);
//	$dtt2->Month++;
	printTable($dtt1, $dtt2);

	function printTable($dtt1, $dtt2) {
		print('<table border="1" cellpadding="2"><tr><td>');
		printDate($dtt1);
		print('</td><td>');
		printDate($dtt2);
		print ('</td></tr>');
		
		print ('<tr><td colspan="2" align="center">IsEqualTo: <b>' . (($dtt1->IsEqualTo($dtt2)) ? 'Yes' : 'No') . '</b></td></tr>');
		print ('<tr><td colspan="2" align="center">IsEarlierThan: <b>' . (($dtt1->IsEarlierThan($dtt2)) ? 'Yes' : 'No') . '</b></td></tr>');
		print ('<tr><td colspan="2" align="center">IsLaterThan: <b>' . (($dtt1->IsLaterThan($dtt2)) ? 'Yes' : 'No') . '</b></td></tr>');
		print ('<tr><td colspan="2" align="center">IsEarlierOrEqualTo: <b>' . (($dtt1->IsEarlierOrEqualTo($dtt2)) ? 'Yes' : 'No') . '</b></td></tr>');
		print ('<tr><td colspan="2" align="center">IsLaterOrEqualTo: <b>' . (($dtt1->IsLaterOrEqualTo($dtt2)) ? 'Yes' : 'No') . '</b></td></tr>');
		print('</table>');
	}
	
	function printDate($dtt) {
		print ('Time Null: ' . (($dtt->IsTimeNull()) ? 'Yes' : 'No'));
		print ('<br/>');
		print ('Date Null: ' . (($dtt->IsDateNull()) ? 'Yes' : 'No'));
		print ('<br/>');
		print ('Date: ' . $dtt->qFormat(QDateTime::FormatDisplayDateTimeFull));
		print ('<br/>');
		print ('Month: ' . $dtt->Month . '<br/>');
		print ('Day: ' . $dtt->Day . '<br/>');
		print ('Year: ' . $dtt->Year . '<br/>');
		print ('Hour: ' . $dtt->Hour . '<br/>');
		print ('Minute: ' . $dtt->Minute . '<br/>');
		print ('Second: ' . $dtt->Second . '<br/>');
	}*/
?>
