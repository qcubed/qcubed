<?php

/**
 * QTimer class can help you lightweight profiling of your applications. 
 * Use it to measure how long tasks take. 
 * 
 * @author Ago Luberg
 *
 */
class QTimer {
	/**
	 * Name of the timer
	 * @var string
	 */
	protected $strName;
	/**
	 * Total count of timer starts
	 * @var int
	 */
	protected $intCountStarted = 0;
	/**
	 * Timer start time. If -1, then timer is not started
	 * @var float
	 */
	protected $fltTimeStart = -1;
	/**
	 * Timer run time. If timer is stopped, then execution time is kept here
	 * @var float
	 */
	protected $fltTime = 0;

	/**
	 * QTimer - private constructor (Factory Pattern)
	 * @param string $strName Timer name
	 * @param boolean $blnStart Whether timer is started
	 * @return QTimer
	 */
	protected function __construct($strName, $blnStart = false) {
		$this->strName = $strName;
		if ($blnStart) {
			$this->startTimer();
		}
	}

	/**
	 * Starts timer
	 * @return QTimer
	 */
	public function StartTimer() {
		if ($this->fltTimeStart != -1) {
				throw new QCallerException("Timer was already started");
		}
		$this->fltTimeStart = microtime(true);
		$this->intCountStarted++;
		return $this;
	}

	/**
	 * Returns timer's time
	 * @return float Timer's time. If timer is not running, returns saved time.
	 */
	public function GetTimerTime() {
		if ($this->fltTimeStart == -1) {
			return $this->fltTime;
		}
		return $this->fltTime + microtime(true) - $this->fltTimeStart;
	}

	/**
	 * Resets timer
	 * @return float Timer's time before reset
	 */
	public function ResetTimer() {
		$fltTime = $this->StopTimer();
		$this->fltTime = 0;
		$this->StartTimer();
		return $fltTime;
	}

	/**
	 * Stops timer. Saves current time for later usage
	 * @return float Timer's time
	 */
	public function StopTimer() {
		$this->fltTime = $this->GetTimerTime();
		$this->fltTimeStart = -1;
		return $this->fltTime;
	}

	/**
	 * Default toString method for timer
	 * @return string
	 */
	public function __toString() {
		return sprintf("%s - start count: %s - execution time: %f",
		$this->strName,
		$this->intCountStarted,
		$this->GetTimerTime());
	}
	
	public function __get($strName) {
		switch ($strName) {
			case 'CountStarted':
				return $this->intCountStarted;
			case 'TimeStart':
				return $this->fltTimeStart;
			default:
				throw new QCallerException('Invalid property: $strName');
		}		
	}

	// getters/setters

	// static stuff
	/**
	 * Array of QTime instances
	 * @var QTimer[]
	 */
	protected static $objTimerArray = array();

	/**
	 * Starts (new) timer with given name
	 * @param string[optional] $strName Timer name
	 * @return QTimer
	 */
	public static function Start($strName = 'default') {
		$objTimer = static::GetTimerInstance($strName);
		return $objTimer->StartTimer();
	}

	/**
	 * Gets time from timer with given name
	 * @param string[optional] $strName Timer name
	 * @return float Timer's time
	 */
	public static function GetTime($strName = 'default') {
		$objTimer = static::GetTimerInstance($strName, false);
		if ($objTimer) {
			return $objTimer->GetTimerTime();
		} else {
			throw new QCallerException('Timer with name ' . $strName . ' was not started, cannot get its value');
		}
	}

	/**
	 * Stops time for timer with given name
	 * @param string[optional] $strName Timer name
	 * @return float Timer's time
	 */
	public static function Stop($strName = 'default') {
		$objTimer = static::GetTimerInstance($strName, false);
		if ($objTimer) {
			return $objTimer->StopTimer();
		} else {
			throw new QCallerException('Timer with name ' . $strName . ' was not started, cannot stop it');
		}
	}

	/**
	 * Resets timer with given name
	 * @param string[optional] $strName Timer name
	 * @return float Timer's time before reset or null if timer does not exist
	 */
	public static function Reset($strName = 'default') {
		$objTimer = static::GetTimerInstance($strName, false);
		if ($objTimer) {
			return $objTimer->ResetTimer();
		}
		return null;
	}

	/**
	 * Returns timer with a given name
	 * @param string[optional] $strName Timer name
	 * @return QTimer or null if a timer was not found
	 */
	public static function GetTimer($strName = 'default') {
		$objTimer = static::GetTimerInstance($strName, false);
		if ($objTimer) {
			return $objTimer;
		}

		return null;
	}

	protected static function GetTimerInstance($strName, $blnCreateNew = true) {
		if (!isset(static::$objTimerArray[$strName])) {
			if ($blnCreateNew) {
				static::$objTimerArray[$strName] = new QTimer($strName);
			} else {
				return null;
			}
		}
		return static::$objTimerArray[$strName];
	}

	// getters/setters?

	/**
	 * Dumps all the timers and their info
	 * @param boolean[optional] $blnDisplayOutput If true (default), dump will be printed. If false, dump will be returned
	 * @return string
	 */
	public static function VarDump($blnDisplayOutput = true) {
		$strToReturn = '';
		foreach (static::$objTimerArray as $objTimer) {
			$strToReturn .= $objTimer->__toString() . "\n";
		}
		if ($blnDisplayOutput) {
			echo nl2br($strToReturn);
			return '';
		} else {
			return $strToReturn;
		}
	}
}
