<?php
	/**
	 * This is the main exception to be thrown by any
	 * method to indicate that the CALLER is responsible for
	 * causing the exception.  This works in conjunction with QCubed's
	 * error handling/reporting, so that the correct file/line-number is
	 * displayed to the user.
	 *
	 * So for example, for a class that contains the method GetItemAtIndex($intIndex),
	 * it is conceivable that the caller could call GetItemAtIndex(15), where 15 does not exist.
	 * GetItemAtIndex would then thrown an IndexOutOfRangeException (which extends CallerException).
	 * If the CallerException is not caught, then the Exception will be reported to the user.  The CALLER
	 * (the script who CALLED GetItemAtIndex) would have that line highlighted as being responsible
	 * for calling the error.
	 *
	 * The PHP default for exeception reporting would normally say that the "throw Exception" line in GetItemAtIndex
	 * is responsible for throwing the exception.  While this is technically true, in reality, it was the line that
	 * CALLED GetItemAtIndex which is responsible.  In short, this allows for much cleaner exception reporting.
	 *
	 * On a more in-depth note, in general, suppose a method OuterMethod takes in parameters, and ends up passing those
	 * paremeters into ANOTHER method InnerMethod which could throw a CallerException.  OuterMethod is responsible
	 * for catching and rethrowing the caller exception.  And when this is done, IncrementOffset() MUST be called on
	 * the exception object, to indicate that OuterMethod's CALLER is responsible for the exception.
	 *
	 * So the code snippet to call InnerMethod by OuterMethod should look like:
	 * <code>
	 *	function OuterMethod($mixValue) {
	 *		try {
	 *			InnerMethod($mixValue);
	 *		} catch (CallerException $objExc) {
	 *			$objExc->IncrementOffset();
	 *			throw $objExc;
	 *		}
	 *		// Do Other Stuff
	 *	}
	 * </code>
	 * Again, this will assure the user that the line of code responsible for the excpetion is properly being reported
	 * by the QCubed error reporting/handler.
	 * @property-read int $Offset The exception offset.
	 * @property-read string $BackTrace The exception backtrace.
	 * @property-read string $TraceArray The exception backtrace in a form of an array.
	 */
	class QCallerException extends Exception {
		/**
		 * @var int Exception offset
		 *          The element in the stack trace array indicated by this index is marked
		 *          as the point which caused the exception
		 */
		private $intOffset;
		/** @var array The stack trace array as caputred by debug_backtrace() */
		private $strTraceArray;

		/**
		 * Set message for the exception
		 *
		 * @param string $strMessage
		 */
		public function setMessage($strMessage) {
			$this->message = $strMessage;
		}

		/**
		 * The constructor of CallerExceptions.  Takes in a message string
		 * as well as an optional Offset parameter (defaults to 1).
		 * The Offset specificies how many calls up the call stack is responsible
		 * for the exception.  By definition, when a CallerException is called,
		 * at the very least the Caller of the most immediate function, which is
		 * 1 up the call stack, is responsible.  So therefore, by default, intOffset
		 * is set to 1.
		 * 
		 * It is rare for intOffset to be set to an integer other than 1.
		 *
		 * Normally, the Offset would be altered by calls to IncrementOffset
		 * at every step the CallerException is caught/rethrown up the call stack.
		 *
		 * @param string  $strMessage the Message of the exception
		 * @param integer $intOffset  the optional Offset value (currently defaulted to 1)
		 */
		public function __construct($strMessage, $intOffset = 1) {
			parent::__construct($strMessage);
			$this->intOffset = $intOffset;
			$this->strTraceArray = debug_backtrace();

			$this->file = $this->strTraceArray[$this->intOffset]['file'];
			$this->line = $this->strTraceArray[$this->intOffset]['line'];
		}

		public function IncrementOffset() {
			$this->intOffset++;
			if (array_key_exists('file', $this->strTraceArray[$this->intOffset]))
				$this->file = $this->strTraceArray[$this->intOffset]['file'];
			else
				$this->file = '';
			if (array_key_exists('line', $this->strTraceArray[$this->intOffset]))
				$this->line = $this->strTraceArray[$this->intOffset]['line'];
			else
				$this->line = '';
		}

		public function DecrementOffset() {
			$this->intOffset--;
			if (array_key_exists('file', $this->strTraceArray[$this->intOffset]))
				$this->file = $this->strTraceArray[$this->intOffset]['file'];
			else
				$this->file = '';
			if (array_key_exists('line', $this->strTraceArray[$this->intOffset]))
				$this->line = $this->strTraceArray[$this->intOffset]['line'];
			else
				$this->line = '';
		}

		/**
		 * PHP magic method
		 * @param $strName
		 *
		 * @return array|int|mixed
		 */
		public function __get($strName) {
			if ($strName == "Offset")
				return $this->intOffset;
			else if ($strName == "BackTrace") {
				$objTraceArray = debug_backtrace();
				return (var_export($objTraceArray, true));
			} else if ($strName == "TraceArray") {
				return $this->strTraceArray;
			}
		}
	}

	/**
	 * Used when trying to access a table object which does not have a primary key defined on it
	 */
	class QUndefinedPrimaryKeyException extends QCallerException {
		/**
		 * Constructor method
		 * @param string $strMessage
		 */
		public function __construct($strMessage) {
			parent::__construct($strMessage, 2);
		}
	}

	/**
	 * Thrown when trying to access an element in an array whose index does not exist
	 * NOTE: this exception will not fire automatically for you unless you use it with the try-catch block
	 */
	class QIndexOutOfRangeException extends QCallerException {
		/**
		 * Constructor method
		 * @param string $intIndex
		 * @param int    $strMessage
		 */
		public function __construct($intIndex, $strMessage) {
			if ($strMessage)
				$strMessage = ": " . $strMessage;
			parent::__construct(sprintf("Index (%s) is out of range%s", $intIndex, $strMessage), 2);
		}
	}

	/**
	 * Thrown when a particular property of class is not defined and we try to access it
	 */
	class QUndefinedPropertyException extends QCallerException {
		/**
		 * Constructor method
		 * @param string $strType
		 * @param int    $strClass
		 * @param string $strProperty
		 */
		public function __construct($strType, $strClass, $strProperty) {
			parent::__construct(sprintf("Undefined %s property or variable in '%s' class: %s", $strType, $strClass, $strProperty), 2);
		}
	}

	/**
	 * Thrown when we try to call an undefined method. Helpful for codegen.
	 */
	class QUndefinedMethodException extends QCallerException {
		public function __construct($strClass, $strMethod) {
			parent::__construct(sprintf("Undefined method in '%s' class: %s", $strClass, $strMethod), 2);
		}
	}

	/**
	 * Thrown when optimistic locking (in ORM Save() method) detects that DB data was updated
	 */
	class QOptimisticLockingException extends QCallerException {
		/**
		 * Constructor method
		 * @param string $strClass
		 */
		public function __construct($strClass) {
			parent::__construct(sprintf('Optimistic Locking constraint when trying to update %s object.  To update anyway, call ->Save() with $blnForceUpdate set to true', $strClass, 2));
		}
	}

	/**
	 * Thrown when the desired page is protected by ALLOW REMOTE ADMIN feature and the request does not qualify
	 */
	class QRemoteAdminDeniedException extends QCallerException {
		/**
		 * Constructor method
		 */
		public function __construct() {
			parent::__construct('Remote access to "' . QApplication::$RequestUri . '" has been disabled.' .
				"\r\nTo allow remote access to this script, set the ALLOW_REMOTE_ADMIN constant to TRUE\r\nor to \"" . $_SERVER['REMOTE_ADDR'] . '" in "configuration.inc.php".', 2);
		}
	}

	/**
	 * Thrown when formstate is not found
	 */
	class QInvalidFormStateException extends QCallerException {
		/**
		 * Constructor method
		 * @param string $strFormId Form ID for which the state was not found
		 */
		public function __construct($strFormId) {
			parent::__construct(sprintf('Invalid Form State Data for "%s" object (session may have been lost)', $strFormId), 2);
		}
	}
?>