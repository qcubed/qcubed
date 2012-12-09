<?php


/**
 * Use this event with the QJsTimer control
 * this event is trigger after a
 * delay specified in QJsTimer (param DeltaTime)
 */
class QTimerExpiredEvent extends QEvent {
	const EventName = 'timerexpiredevent';
}


/**
 * Timer Control:
 * This control uses a javascript timer to execute Actions after a defined time
 *
 * Periodic or one shot timers are possible.
 * You can add only one type of Event to to this control: QTimerExpiredEvent
 * but multiple actions can be registered for this event
 *
 * @property int $DeltaTime		Time till the timer fires and executes the Actions added.
 * @property boolean $Periodic			true: timer is restarted after firing
 *							false: you have to restart the timer by calling Start()
 * @property boolean $Started			true: timer is running / false: stopped
 * @property boolean $RestartOnServerAction
 *							After a 'Server Action' the executed java script
 *							(including the timer) is stopped!
 *							Set this parameter to true to restart the timer automatically.
 *
 * * Notes:
 *		- You do not need to render this control!
 *		- QTimerExpiredEvent
 *			condition and delay parameters of the constructor are ignored (for now)
 */
class QJsTimer extends QControl {
	const Stopped = 0;
	const Started = 1;
	const AutoStart = 2;

	protected $blnPeriodic = true;
	protected $intDeltaTime = 0;
	protected $intState = QJsTimer::Stopped;
	protected $blnRestartOnServerAction = false;


	/**
	 * @param QForm|QControl $objParentObject the form or parent control
	 * @param int $intTime			timer interval in ms
	 * @param boolean $blnPeriodic		if true the timer is "restarted" automatically after it has fired
	 * @param boolean $blnStartNow		starts the timer automatically after adding the first action
	 * @param string $strTimerId
	 * @return QJsTimer
	 *
	 */
	public function __construct($objParentObject, $intTime = 0, $blnPeriodic = true, $blnStartNow = true, $strTimerId = null) {
		try {
			parent::__construct($objParentObject, $strTimerId);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		$this->intDeltaTime = $intTime;
		$this->blnPeriodic = $blnPeriodic;
		if ($intTime != QJsTimer::Stopped && $blnStartNow)
			$this->intState = QJsTimer::AutoStart; //prepare to start the timer after the first action gets added
	}

	private function callbackString() {
		return "qcubed._objTimers['" . $this->strControlId . "_cb']";
	}

	private function tidString() {
		return "qcubed._objTimers['" . $this->strControlId . "_tId']";
	}

	/**
	 * @param int $intTime (optional)
	 *				sets the interval/delay, after that the timer executes the registered actions
	 *				if no parameter is given the time stored in $intDeltaTime is used
	 */
	public function Start($intTime = null) {
		$this->Stop();
		if ($intTime != null && is_int($intTime))
			$this->intDeltaTime = $intTime;
		$event = $this->getEvent();
		if (!$event)
			throw new QCallerException("Can't start the timer: add an Event/Action first!");

		if ($this->blnPeriodic) {
			$strJS = $this->tidString() . ' = window.setInterval("' . $this->callbackString() . '()", '. $this->intDeltaTime . ');';
		} else {
			$strJS = $this->tidString() .  ' = window.setTimeout("' . $this->callbackString() . '()", '. $this->intDeltaTime . ');';
		}
		QApplication::ExecuteJavaScript($strJS);
		$this->intState = QJsTimer::Started;
	}

	/**
     * stops the timer
     */
	public function Stop() {
		$event = $this->getEvent();
		if (!$event)
			throw new QCallerException('Can\'t stop the timer: no Event/Action present!');
		if ($this->blnPeriodic) {
			$strJS = 'window.clearInterval(' . $this->tidString() . ');';
		} else {
			$strJS =  'window.clearTimeout(' . $this->tidString() . ');';
		}
		QApplication::ExecuteJavaScript($strJS);
		$this->intState = QJsTimer::Stopped;
	}

	/**
	 * Adds an action to the control
	 * @param QEvent $objEvent		has to be an instance of QTimerExpiredEvent
	 * @param QAction $objAction
	 *
	 * Only a QTimerExpiredEvent can be added,
	 * but multiple Actions using the same event are possible!
	 */
	public function AddAction($objEvent, $objAction) {
		if (!($objEvent instanceof QTimerExpiredEvent)) {
			throw new QCallerException('First parameter of QJsTimer::AddAction is expecting an object of type QTimerExpiredEvent');
		}
		if (!($objAction instanceof QAction)) {
			throw new QCallerException('Second parameter of AddAction is expecting an object of type QAction');
		}

		$strEventName = $objEvent->EventName;
		if (!count($this->objActionArray)) {
			//no event registerd yet
			$this->objActionArray[$strEventName] = array();
		}

		// Store the Event object in the Action object
		$objAction->Event = $objEvent;

		array_push($this->objActionArray[$strEventName], $objAction);

		if ($this->intState === QJsTimer::AutoStart && $this->intDeltaTime != 0)
			$this->Start(); //autostart the timer

		$this->blnModified = true;

	}


	public function GetAllActions($strEventType, $strActionType = null) {
		if (($strEventType == 'QTimerExpiredEvent' && $this->blnPeriodic == false) &&
			(($strActionType == 'QAjaxAction' && $this->objForm->CallType == QCallType::Ajax) ||
			 ($strActionType == 'QServerAction' && $this->objForm->CallType == QCallType::Server))
		) {
			//if we are in an ajax or server post and our timer is not periodic
			//and this method gets called then the timer has finished(stopped) --> set the State flag to "stopped"
			$this->intState = QJsTimer::Stopped;
		}
		return parent::GetAllActions($strEventType, $strActionType);
	}

	public function  RemoveAllActions($strEventName = null) {
		$this->Stop(); //no actions are registered for this timer stop it
		parent::RemoveAllActions($strEventName);
	}

	public function GetEvent() {
		if (!count($this->objActionArray))
			return null;
		$arActions = reset($this->objActionArray);
		return reset($arActions)->Event;
	}

	/**
	 * Returns all action attributes
	 *
	 * @return string
	 */
	public function GetActionAttributes() {
		$strToReturn = $this->callbackString() . " = ";
		if (!count($this->objActionArray))
			return $strToReturn . 'null;';

		$strToReturn .= 'function() {';

		foreach (reset($this->objActionArray) as $objAction) {
			$strToReturn .= ' ' . $objAction->RenderScript($this);
		}
		if ($this->ActionsMustTerminate) {
			if (QApplication::IsBrowser(QBrowserType::InternetExplorer_6_0))
				$strToReturn .= ' qc.terminateEvent(event);';
			else
				$strToReturn .= ' return false;';
		}
		$strToReturn .= ' }; ';
		return $strToReturn;
	}

	public function GetEndScript() {
		if ($this->objForm->CallType == QCallType::Server) {
			//this point is not reached on initial rendering
			if ($this->blnRestartOnServerAction && $this->intState === QJsTimer::Started)
				$this->Start(); //restart after a server action
			else
				$this->intState = QJsTimer::Stopped;
		}
		return parent::GetEndScript();
	}

	public function __get($strName) {
		switch ($strName) {
			case 'DeltaTime':
				return $this->intDeltaTime;
			case 'Periodic':
				return $this->blnPeriodic;
			case 'Started':
				return ($this->intState === QJsTimer::Started);
			case 'RestartOnServerAction':
				return $this->blnRestartOnServerAction;
			case 'Rendered':
				return true;
			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

	public function __set($strName, $mixValue) {
		switch ($strName) {
			case "DeltaTime":
				try {
					$this->intDeltaTime = QType::Cast($mixValue, QType::Integer);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
				break;
			case 'Periodic':
				try {
					$newMode = QType::Cast($mixValue, QType::Boolean);
					if ($this->blnPeriodic != $newMode) {
						if ($this->intState === QJsTimer::Started) {
							$this->Stop();
							$this->blnPeriodic = $newMode;
							$this->Start();
						}
						else
							$this->blnPeriodic = $newMode;
					}
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
				break;
			case 'RestartOnServerAction':
				try {
					$this->blnRestartOnServerAction = QType::Cast($mixValue, QType::Boolean);
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
				break;
			case "Rendered": //ensure that the control is marked as Rendered to get js updates
				$this->blnRendered = true;
				break;
			default:
				try {
					parent::__set($strName, $mixValue);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
				break;
		}
	}


	public function Render($blnDisplayOutput = true) {
		throw new QCallerException('Do not render QJsTimer!');
	}

	public function  AddChildControl(QControl $objControl) {
		throw new QCallerException('Do not add child-controls to an instance of QJsTimer!');
	}

	public function RemoveChildControl($strControlId, $blnRemoveFromForm) {
	}

	protected function GetControlHtml() {
		// no control html
		return "";
	}

	public function  ParsePostData() {
	}

	public function  Validate() {
		return true;
	}
}

?>
