#!/usr/bin/env php
<?php
/* This file runs the travis unit tests. */

/* Define the working directory for the build */
$workingDir = getcwd();

define('__WORKING_DIR__', $workingDir);
$subdir = '';
if (isset ($argv[1])) {
	$subdir = '/' . $argv[1];
	define ('__SUBDIRECTORY__', $subdir);
	$_SERVER['argc']--; // prevents problems in codegen
	unset ($_SERVER['argc'][1]);
}

$__CONFIG_ONLY__ = true;

require( __WORKING_DIR__ . $subdir . '/travis/configuration.inc.php');
define ('__CONFIGURATION__', __WORKING_DIR__ . $subdir . '/travis');


//require_once(__EXTERNAL_LIBRARIES__ . '/lastcraft/simpletest/unit_tester.php');
//require_once(__EXTERNAL_LIBRARIES__ . '/lastcraft/simpletest/reporter.php');

$__CONFIG_ONLY__ = false;
require( __DOCROOT__ . __SUBDIRECTORY__ . '/travis/qcubed.inc.php');

// Codegen for testing
// Running as a Non-Windows Command Name
	$strCommandName = 'codegen.cli';

	// Include the rest of the OS-agnostic script
	require( __DOCROOT__ . __SUBDIRECTORY__ . '/includes/_devtools/codegen.inc.php');

// not using QCubed error handler for unit tests - using the SimpleTest one instead
restore_error_handler();

require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/QUnitTestCaseBase.php');
require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/QTestControl.class.php');

class QTestForm extends QForm {
	public $ctlTest;

	protected function Form_Create() {
		$this->ctlTest = new QTestControl($this);
		$this->runTests();
	}
	
	public function runTests() {
		$cliOptions = [ 'phpunit'];	// first entry is the command
		array_push($cliOptions, '-c', __QCUBED_CORE__ . '/tests');	// the config file is here
		array_push($cliOptions, '--verbose');
		//array_push($cliOptions, '--process-isolation', false);
		array_push($cliOptions, '--bootstrap', __QCUBED_CORE__ . '/../vendor/autoload.php');
		echo __QCUBED_CORE__ . '/../vendor/autoload.php';

		//$cliOptions[] = __QCUBED_CORE__ . '/tests'; // last entry is the directory where the tests are

		$tester = new PHPUnit_TextUI_Command();

		$tester->run($cliOptions);
	}
}

QTestForm::Run('QTestForm', __QCUBED_CORE__ . "/tests/qcubed-unit/QTestForm.tpl.php");


?>
