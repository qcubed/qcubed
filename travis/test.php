#!/usr/bin/env php
<?php
/* This file runs the travis unit tests. */

/* Define the working directory for the build */
$workingDir = getcwd();
define('__TRAVIS_DIR__', $workingDir);

// If you need to skip any tests, list them in this array
$filesToSkip = array(
	"QUnitTestCaseBase.php"
	, "QTestForm.tpl.php"
);


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

$arrFiles = QFolder::listFilesInFolder(__QCUBED_CORE__ . '/tests/qcubed-unit/');
$arrTests = array();
foreach ($arrFiles as $filename) {
	require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/' . $filename);
	if (!in_array($filename, $filesToSkip)) {
		$arrTests[] = str_replace(".php", "", $filename);
	}
}


class QHtmlReporter extends HtmlReporter {
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
}

$suite = new TestSuite('QCubed ' . QCUBED_VERSION_NUMBER_ONLY . ' Unit Tests - SimpleTest ' . SimpleTest::getVersion());
foreach ($arrTests as $className) {
	$suite->add(new $className);
}
$suite->run(new QHtmlReporter());
?>
