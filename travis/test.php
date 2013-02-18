#!/usr/bin/env php
<?php
/* This file runs the travis unit tests. */

/* Define the working directory for the build */
$workingDir = getcwd();
define('__TRAVIS_DIR__', $workingDir);

$__CONFIG_ONLY__ = true;
require('travis/configuration.inc.php');

require_once(__QCUBED_CORE__ . '/tests/simpletest/unit_tester.php');
require_once(__QCUBED_CORE__ . '/tests/simpletest/reporter.php');

$__CONFIG_ONLY__ = false;
require('travis/qcubed.inc.php');

// Codegen for testing
// Running as a Non-Windows Command Name
	$strCommandName = 'codegen.cli';

	// Include the rest of the OS-agnostic script
	require('includes/qcubed/_core/_devtools_cli/codegen.inc.php');


// not using QCubed error handler for unit tests - using the SimpleTest one instead
restore_error_handler();

require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/QUnitTestCaseBase.php');


class QTravisReporter extends TextReporter {
	function paintMethodStart($test_name) {
		$tempBreadcrumb = $this->getTestList();
		array_shift($tempBreadcrumb);
		$breadcrumb = implode("-&gt;", $tempBreadcrumb);
        echo "\r\n**********************************\r\n";
		echo "{$breadcrumb} - {$test_name}\r\n";
        echo "**********************************\r\n";
	}

	function paintMethodEnd($test_name) {
		
	}

	function paintPass($message) {
		parent::paintPass($message);

		$messageWithoutTrace = trim(substr($message, 0, strpos($message, " at [")));
		if (strlen($messageWithoutTrace) == 0) {
			// don't show empty messages (they appear if debugging is conditionally disabled)
			return;
		}

		print "Pass: ";

		print "{$messageWithoutTrace}\n";
	}
	
	function paintFail($message) {
		SimpleReporter::paintFail($message);

		$messageWithoutTrace = trim(substr($message, 0, strpos($message, " at [")));
		if (strlen($messageWithoutTrace) == 0) {
			// don't show empty messages (they appear if debugging is conditionally disabled)
			return;
		}

		print "Fail: ";

		print "{$messageWithoutTrace}\n";
	}
}

/**
 * @var QTravisReporter 
 */
$rptReporter = null;

class QTestForm extends QForm {

	protected function Form_Create() {
		$filesToSkip = array(
			"QUnitTestCaseBase.php"
			, "QTestForm.tpl.php"
		);

		$arrFiles = QFolder::listFilesInFolder(__QCUBED_CORE__ . '/tests/qcubed-unit/');
		$arrTests = array();
		foreach ($arrFiles as $filename) {
			if (!in_array($filename, $filesToSkip)) {
				require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/' . $filename);
				$arrTests[] = str_replace(".php", "", $filename);
			}
		}

		$suite = new TestSuite('QCubed ' . QCUBED_VERSION_NUMBER_ONLY . ' Unit Tests - SimpleTest ' . SimpleTest::getVersion());
		foreach ($arrTests as $className) {
			$suite->add(new $className($this));
		}
		global $rptReporter;
		$rptReporter = new QTravisReporter();
		$suite->run($rptReporter);
	}
}

QTestForm::Run('QTestForm', __DOCROOT__ . "/travis/QTestForm.tpl.php");

// Need to return value greater then zero in a case of an error.
return ($rptReporter->getFailCount() + $rptReporter->getExceptionCount());

?>
