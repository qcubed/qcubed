<?php

/* This file is the file to point the browser to to launch unit tests */

require('./qcubed.inc.php');

// not using QCubed error handler for unit tests
restore_error_handler();

require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/QUnitTestCaseBase.php');
require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/QTestControl.class.php');


class QHtmlReporter extends PHPUnit_TextUI_ResultPrinter {
	protected $results;
	protected $currentSuite;
	protected $currentTest;

	public function write($buffer)
	{
	}
	public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		$this->currentSuite = $suite->getName();
	}

	public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		$this->currentSuite = null;
	}


	public function startTest(PHPUnit_Framework_Test $test)
	{
		$this->currentTest = $test->getName();
		$this->results[$this->currentSuite][$test->getName()]['test'] = $test;
	}

	public function addError(PHPUnit_Framework_Test $test, Exception $e, $time) {
		$this->results[$this->currentSuite][$test->getName()]['status'] = 'error';
		$this->results[$this->currentSuite][$test->getName()]['errors'][] = compact('e', 'time');
	}
	public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time) {
		$this->results[$this->currentSuite][$test->getName()]['status'] = 'failed';
		$this->results[$this->currentSuite][$test->getName()]['results'][] = compact('e', 'time');
	}
	public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time) {
		$this->results[$this->currentSuite][$test->getName()]['status'] = 'incomplete';
		$this->results[$this->currentSuite][$test->getName()]['errors'][] = compact('e', 'time');
	}
	public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time) {
		$this->results[$this->currentSuite][$test->getName()]['status'] = 'skipped';
		$this->results[$this->currentSuite][$test->getName()]['errors'][] = compact('e', 'time');
	}

	public function endTest(PHPUnit_Framework_Test $test, $time) {
		$t = &$this->results[$this->currentSuite][$test->getName()];
		if (!isset($t['status'])) {
			$t['status'] = 'passed';
		}
		$t['time'] = $time;
		$this->currentTest = null;
	}

	public function printResult(PHPUnit_Framework_TestResult $result)
	{
		echo('<h1>QCubed ' . QCUBED_VERSION_NUMBER_ONLY . ' Unit Tests - PHPUnit ' . PHPUnit_Runner_Version::id() . '</h1>');

		foreach ($this->results as $suiteName=>$suite) {
			$strHtml = "<b>$suiteName</b><br />";
			foreach ($suite as $testName=>$test) {
				$status = $test['status'];
				$status = ucfirst($status);
				if ($test['status'] !== 'passed') {
					$status = '<span style="color:red">' . $status . '</span>';
				} else {
					$status = '<span style="color:green">' . $status . '</span>';
				}

				$strHtml .= "$status: $testName";
				$strHtml = "$strHtml<br />";
				if (isset($test['errors'])) foreach ($test['errors'] as $error){
					$strHtml .= nl2br(htmlentities($error['e']->__toString())) . '<br />';
				}
				if (isset($test['results'])) foreach ($test['results'] as $error) {
					$strMessage = $error['e']->toString() . "\n";
					// get first line
					$lines = explode ("\n", PHPUnit_Util_Filter::getFilteredStacktrace($error['e']));
					$strMessage .= $lines[0] . "\n";
					$strHtml .= nl2br(htmlentities($strMessage)) . '<br />';
				}
			}
			echo $strHtml;

		}

		$str = "\nRan " . $result->count() . " tests in " . $result->time() . " seconds.\n";
		$str .= $result->failureCount() . " assertions failed.\n";
		$str .= $result->errorCount() . " exceptions were thrown.\n";
		echo nl2br($str);

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
	public $btnRunTests;
	public $lblRunning;
	public $pnlOutput;

	protected function Form_Create() {
		$this->ctlTest = new QTestControl($this);
		$this->pnlOutput = new QPanel($this, 'outputPanel');
		$this->btnRunTests = new QButton($this);
		$this->btnRunTests->Text = "Run Tests";
		$this->btnRunTests->AddAction(new QClickEvent(), new QAjaxAction('startTesting'));

		$this->lblRunning = new QLabel($this);
		$this->lblRunning->Text = "Running, please wait...";
		$this->lblRunning->Visible = false;
	}

	protected function startTesting() {
		$this->lblRunning->Visible = true;
		
		$t1 = new QJsTimer($this, 50, false, true, 'timer1');
		$t1->AddAction(new QTimerExpiredEvent(), new QAjaxAction ('preTest'));
		$t2 = new QJsTimer($this, 51, false, true, 'timer2');
		$t2->AddAction(new QTimerExpiredEvent(0,null,null,true), new QAjaxAction ('preTest2'));
		$t3 = new QJsTimer($this, 52, false, true, 'timer3');
		$t3->AddAction(new QTimerExpiredEvent(), new QAjaxAction ('preTest3'));
		$t4 = new QJsTimer($this, 600, false, true, 'timer4');
		$t4->AddAction(new QTimerExpiredEvent(), new QServerAction ('runTests'));
	}
	
	public function preTest() {
		$this->ctlTest->savedValue1 = 2;	// for test in QControlBaseTests
	}

	public function preTest2() {
		$this->ctlTest->savedValue2 = $this->ctlTest->savedValue1;	// for test in QControlBaseTests
	}

	public function preTest3() {
		$this->ctlTest->savedValue3 = 1;	// This should NOT happen, since previous event should block it.
	}

	
	public function runTests() {
		$cliOptions = [ 'phpunit'];	// first entry is the command
		array_push($cliOptions, '-c', __QCUBED_CORE__ . '/tests/phpunithtml.xml');	// the config file is here

		//$cliOptions[] = __QCUBED_CORE__ . '/tests'; // last entry is the directory where the tests are

		$tester = new PHPUnit_TextUI_Command();

		$tester->run($cliOptions);
	}
}

QTestForm::Run('QTestForm', __QCUBED_CORE__ . "/tests/qcubed-unit/QTestForm.tpl.php");