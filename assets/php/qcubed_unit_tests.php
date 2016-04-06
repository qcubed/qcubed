<?php

/* This file is the file to point the browser to to launch unit tests */

define ('__NO_OUTPUT_BUFFER__', 1);

require('./qcubed.inc.php');

// not using QCubed error handler for unit tests
restore_error_handler();

require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/QUnitTestCaseBase.php');
require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/QTestControl.class.php');


class QHtmlReporter extends PHPUnit_TextUI_ResultPrinter {
	public function write($buffer)
	{
		if ($this->out) {
			fwrite($this->out, $buffer);

			if ($this->autoFlush) {
				$this->incrementalFlush();
			}
		} else {

			if (PHP_SAPI != 'cli' && PHP_SAPI != 'phpdbg') {
				$buffer = htmlspecialchars($buffer);
			}

			print $buffer;

			if ($this->autoFlush) {
				$this->incrementalFlush();
			}
		}
	}
	public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		parent::startTestSuite($suite);
		$this->write('<h2>' . $suite->getName() . '</h2>');

	}

	public function startTest(PHPUnit_Framework_Test $test)
	{
		$this->write('<p>' . PHPUnit_Util_Test::describe($test) . '</p>');
	}

	public function endTest(PHPUnit_Framework_Test $test, $time)
	{
		if (!$this->lastTestFailed) {
			//$this->writeProgress('.');
		}

		if ($test instanceof PHPUnit_Framework_TestCase) {
			$this->numAssertions += $test->getNumAssertions();
		} elseif ($test instanceof PHPUnit_Extensions_PhptTestCase) {
			$this->numAssertions++;
		}

		$this->lastTestFailed = false;

		if ($test instanceof PHPUnit_Framework_TestCase) {
			if (!$test->hasExpectationOnOutput()) {
				$this->write($test->getActualOutput());
			}
		}
	}

}



/*
function paintMethodStart($test_name) {
		$tempBreadcrumb = $this->getTestList();
		array_shift($tempBreadcrumb);
		$breadcrumb = implode("-&gt;", $tempBreadcrumb);

		echo "<b>{$breadcrumb} > {$test_name}</b><br />";
	}

	function paintMethodEnd($test_name) {
		echo "<br />";
	}

	function paintPass($message) {
		parent::paintPass($message);

		$messageWithoutTrace = trim(substr($message, 0, strpos($message, " at [")));
		if (strlen($messageWithoutTrace) == 0) {
			// don't show empty messages (they appear if debugging is conditionally disabled)
			return;
		}

		print "<span class=\"pass\">Pass</span>: ";

		print "{$messageWithoutTrace}<br />\n";
	}
}
*/

class QTestForm extends QForm {
	public $ctlTest;
	public $pnlOutput;

	protected function Form_Create() {
		$this->ctlTest = new QTestControl($this);
		$this->pnlOutput = new QPanel($this, 'outputPanel');
		
		$t1 = new QJsTimer($this, 200, false, true, 'timer1');
		$t1->AddAction(new QTimerExpiredEvent(), new QAjaxAction ('preTest'));
		$t2 = new QJsTimer($this, 201, false, true, 'timer2');
		$t2->AddAction(new QTimerExpiredEvent(), new QAjaxAction ('preTest2'));
		$t3 = new QJsTimer($this, 400, false, true, 'timer3');
		$t3->AddAction(new QTimerExpiredEvent(), new QServerAction ('runTests'));
	}
	
	public function preTest() {
		$this->ctlTest->savedValue1 = 2;	// for test in QControlBaseTests
	}
	
	public function preTest2() {
		$this->ctlTest->savedValue2 = $this->ctlTest->savedValue1;	// for test in QControlBaseTests
	}
	
	
	public function runTests() {
		$cliOptions = [ 'phpunit'];	// first entry is the command
		array_push($cliOptions, '-c', __QCUBED_CORE__ . '/tests');	// the config file is here

		//$cliOptions[] = __QCUBED_CORE__ . '/tests'; // last entry is the directory where the tests are

		echo('<h1>QCubed ' . QCUBED_VERSION_NUMBER_ONLY . ' Unit Tests - PHPUnit ' . PHPUnit_Runner_Version::id() . '</h1>');
		$tester = new PHPUnit_TextUI_Command();

		$tester->run($cliOptions);
	}
}

QTestForm::Run('QTestForm', __QCUBED_CORE__ . "/tests/qcubed-unit/QTestForm.tpl.php");

?>
