<?php
	/* This includes library file is used by the codegen.cli and codegen.phpexe scripts
	 * to simply fire up and run the QCodeGen object, itself.
	 */

	// Call the CLI prepend.inc.php
//	require('cli_prepend.inc.php');

	// Include the QCodeGen class library
	function PrintInstructions() {
		global $strCommandName;
		print('QCubed Code Generator (Command Line Interface) -
Copyright (c) 2001 - 2009, QuasIdea Development, LLC, QCubed Project
This program is free software with ABSOLUTELY NO WARRANTY; you may
redistribute it under the terms of The MIT License.

Usage: ' . $strCommandName . ' QCUBED_PROJECT_DIR

Where QCUBED_BASE_DIR is the absolute filepath of the QCubed project base directory (where the QCubed includes subdirectry is).

For more information, please go to http://qcu.be
');
		exit();
	}

	if (!defined('__CONFIGURATION__')) {
		if ($_SERVER['argc'] < 2) {
			PrintInstructions();
		}
		$qcubedBaseDir = $_SERVER['argv'][1];
		if (!is_dir($qcubedBaseDir)) {
			print("Error: $qcubedBaseDir is not a directory\n");
			PrintInstructions();
		}
		$prependFile = $qcubedBaseDir.'/includes/configuration/prepend.inc.php';
		if (!is_file($prependFile)) {
			print("Error: Could not locate prepend.inc.php: $prependFile does not exist\n");
			PrintInstructions();
		}
		require($prependFile);

		if (!defined('__CONFIGURATION__')) {
			print("Error: __CONFIGURATION__ setting is not defined. Make sure $qcubedBaseDir.' is the correct QCubed base directory\n");
			PrintInstructions();
		}
		if (!defined('__QCUBED__')) {
			print("Error: __QCUBED__ setting is not defined. Make sure $qcubedBaseDir is the correct QCubed base directory\n");
			PrintInstructions();
		}
	}

	$settingsFile = __CONFIGURATION__ . '/codegen_settings.xml';

	if (!is_file($settingsFile)) {
		print("Error: Could not locate codegen settings file: $settingsFile does not exist\n");
		PrintInstructions();
	}

	require(__QCUBED__. '/codegen/QCodeGen.class.php');

	/////////////////////
	// Run Code Gen
	QCodeGen::Run($settingsFile);
	/////////////////////


	if ($strErrors = QCodeGen::$RootErrors) {
		printf("The following ROOT ERRORS were reported:\r\n%s\r\n\r\n", $strErrors);
	} else {
		printf("CodeGen settings (as evaluted from %s):\r\n%s\r\n\r\n", $settingsFile, QCodeGen::GetSettingsXml());
	}

	foreach (QCodeGen::$CodeGenArray as $objCodeGen) {
		printf("%s\r\n---------------------------------------------------------------------\r\n", $objCodeGen->GetTitle());
		printf("%s\r\n", $objCodeGen->GetReportLabel());
		printf("%s\r\n", $objCodeGen->GenerateAll());
		if ($strErrors = $objCodeGen->Errors)
			printf("The following errors were reported:\r\n%s\r\n", $strErrors);
		print("\r\n");
	}

	foreach (QCodeGen::GenerateAggregate() as $strMessage) {
		printf("%s\r\n\r\n", $strMessage);
	}
?>