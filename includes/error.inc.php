<?php
	class QErrorAttribute {
		public $Label;
		public $Contents;
		public $MultiLine;
		
		public function __construct($strLabel, $strContents, $blnMultiLine) {
			$this->Label = $strLabel;
			$this->Contents = $strContents;
			$this->MultiLine = $blnMultiLine;
		}
	}

	// Exception and Error Handling
	function QcubedHandleException($__exc_objException) {
		if (class_exists('QApplicationBase'))
			QApplicationBase::$ErrorFlag = true;

		global $__exc_strType;
		if (isset($__exc_strType))
			return; // error was already called, avoid endless looping

		$__exc_objReflection = new ReflectionObject($__exc_objException);

		$__exc_strType = "Exception";
		$__exc_errno = $__exc_objException->ErrorNumber;
		$__exc_strMessage = $__exc_objException->getMessage();
		$__exc_strObjectType = $__exc_objReflection->getName();

		if ($__exc_objException instanceof QDatabaseExceptionBase) {
			$__exc_objErrorAttribute = new QErrorAttribute("Database Error Number", $__exc_errno, false);
			$__exc_objErrorAttributeArray[0] = $__exc_objErrorAttribute;

			if ($__exc_objException->Query) {
				$__exc_objErrorAttribute = new QErrorAttribute("Query", $__exc_objException->Query, true);
				$__exc_objErrorAttributeArray[1] = $__exc_objErrorAttribute;
			}
		}

		if ($__exc_objException instanceof QDataBindException) {
			if ($__exc_objException->Query) {
				$__exc_objErrorAttribute = new QErrorAttribute("Query", $__exc_objException->Query, true);
				$__exc_objErrorAttributeArray[1] = $__exc_objErrorAttribute;
			}
		}

		$__exc_strFilename = $__exc_objException->getFile();
		$__exc_intLineNumber = $__exc_objException->getLine();
		$__exc_strStackTrace = trim($__exc_objException->getTraceAsString());

		if (ob_get_length()) {
			$__exc_strRenderedPage = ob_get_contents();
			ob_clean();
		}

		// Call to display the Error Page (as defined in configuration.inc.php)
		if (defined('ERROR_PAGE_PATH')) {
			require(__DOCROOT__ . ERROR_PAGE_PATH);
		} else {
			// Error in installer or similar - ERROR_PAGE_PATH constant is not defined yet.
			echo "error: errno: ". $__exc_errno . "<br/>" . $__exc_strMessage . "<br/>" . $__exc_strFilename . ":" . $__exc_intLineNumber . "<br/>" . $__exc_strStackTrace ;
		}
		if (!defined('HHVM_VERSION')) {
			exit(); // HHVM bug. Will not display output if this gets executed here.
		}
	}

	/**
	 * Returns a stringified version of a backtrace.
	 * Set $blnShowArgs if you want to see a representation of the arguments. Note that if you are sending
	 * in objects, this will unpack the entire structure and display its contents.
	 * $intSkipTraces is how many back traces you want to skip. Set this to at least one to skip the
	 * calling of this function itself.
	 *
	 * @param bool $blnShowArgs
	 * @param int $intSkipTraces
	 * @return string
	 */
	function QcubedGetBacktrace($blnShowArgs = false, $intSkipTraces = 1) {
		if (!$blnShowArgs) {
			$b = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		} else {
			$b = debug_backtrace(false);
		}
		$strRet = "";
		for ($i = $intSkipTraces; $i < count($b); $i++) {
			$item = $b[$i];

			$strFile = (array_key_exists("file", $item)) ? $item["file"] : "";
			$strLine = (array_key_exists("line", $item)) ? $item["line"] : "";
			$strClass = (array_key_exists("class", $item)) ? $item["class"] : "";
			$strType = (array_key_exists("type", $item)) ? $item["type"] : "";
			$strFunction = (array_key_exists("function", $item)) ? $item["function"] : "";

			$vals = [];
			if (!empty($item["args"])) {
				foreach ($item["args"] as $val) {
					$vals[] = JavaScriptHelper::toJsObject($val);
				}
			}
			$strArgs = implode(", ", $vals);

			$strRet .= sprintf("#%s %s(%s): %s%s%s(%s)\n",
				$i,
				$strFile,
				$strLine,
				$strClass,
				$strType,
				$strFunction,
				$strArgs);
		}

		return $strRet;
	}

	function QcubedHandleError($__exc_errno, $__exc_errstr, $__exc_errfile, $__exc_errline, $__exc_errcontext) {
		// If a command is called with "@", then we should return
		if (error_reporting() == 0)
			return true;

		if (class_exists('QApplicationBase'))
			QApplicationBase::$ErrorFlag = true;

		global $__exc_strType;
		if (isset($__exc_strType))
			return true; // Already handled elsewhere, avoi looping

		$__exc_strType = "Error";
		$__exc_strMessage = $__exc_errstr;

		switch ($__exc_errno) {
			case E_ERROR:
				$__exc_strObjectType = "E_ERROR";
				break;
			case E_WARNING:
				$__exc_strObjectType = "E_WARNING";
				break;
			case E_PARSE:
				$__exc_strObjectType = "E_PARSE";
				break;
			case E_NOTICE:
				$__exc_strObjectType = "E_NOTICE";
				break;
			case E_STRICT:
				$__exc_strObjectType = "E_STRICT";
				break;
			case E_CORE_ERROR:
				$__exc_strObjectType = "E_CORE_ERROR";
				break;
			case E_CORE_WARNING:
				$__exc_strObjectType = "E_CORE_WARNING";
				break;
			case E_COMPILE_ERROR:
				$__exc_strObjectType = "E_COMPILE_ERROR";
				break;
			case E_COMPILE_WARNING:
				$__exc_strObjectType = "E_COMPILE_WARNING";
				break;
			case E_USER_ERROR:
				$__exc_strObjectType = "E_USER_ERROR";
				break;
			case E_USER_WARNING:
				$__exc_strObjectType = "E_USER_WARNING";
				break;
			case E_USER_NOTICE:
				$__exc_strObjectType = "E_USER_NOTICE";
				break;
			case E_DEPRECATED:
				$__exc_strObjectType = 'E_DEPRECATED';
				break;
			case E_USER_DEPRECATED:
				$__exc_strObjectType = 'E_USER_DEPRECATED';
				break;
			case E_RECOVERABLE_ERROR:
				$__exc_strObjectType = 'E_RECOVERABLE_ERROR';
				break;
			default:
				$__exc_strObjectType = "Unknown";
				break;
		}

		$__exc_strFilename = $__exc_errfile;
		$__exc_intLineNumber = $__exc_errline;

		$__exc_strStackTrace =  QcubedGetBacktrace();

		if (ob_get_length()) {
			$__exc_strRenderedPage = ob_get_contents();
			ob_clean();
		}

		// Call to display the Error Page (as defined in configuration.inc.php)
		if (defined('ERROR_PAGE_PATH')) {
			require(__DOCROOT__ . ERROR_PAGE_PATH);
		} else {
			// Error in installer or similar - ERROR_PAGE_PATH constant is not defined yet.
			echo "error: errno: ". $__exc_errno . "<br/>" . $__exc_errstr . "<br/>" . $__exc_errfile . ":" . $__exc_errline . "<br/>" . implode(', ', $__exc_errcontext) ;
		}
		exit();
	}

	/**
	 * Some errors are not caught by a php custom error handler, which can cause the system to silently fail.
	 * This shutdown function will catch those errors.
	 */

	function QCubedShutdown() {
		if (defined ('__TIMER_OUT_FILE__')) {
			$strTimerOutput = QTimer::VarDump(false);
			if ($strTimerOutput) {
				file_put_contents(__TIMER_OUT_FILE__, $strTimerOutput . "\n", FILE_APPEND);
			}
		}

		$error = error_get_last();
		if ($error &&
			is_array ($error) &&
			(!defined ('QCodeGen::DebugMode') || QCodeGen::DebugMode)) { // if we are codegenning, only error if we are in debug mode. Prevents chmod error.

			QcubedHandleError (
				$error['type'],
				$error['message'],
				$error['file'],
				$error['line'],
				''
			);
		}
		//flush();	// required for hhvm
		//error_log("Flushed");

	}