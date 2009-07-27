<?php
$__CONFIG_ONLY__ = true;
require('../qcubed.inc.php');

$arrInstallationMessages = ValidateInstall();
if (sizeof($arrInstallationMessages) == 0) {
	header("Location: start_page.php");
} else {
	echo "<h1>Welcome to QCubed!</h1>";
	echo "<h3>PHP5 Model-View-Controller framework</h3>";
	echo "<p>This simple wizard will help you configure QCubed for first use. " .
		"It'll take you just a couple minutes. If you have any questions along " .
		"the way, feel free to ping us on the " .
		"<a target='_blank' href='http://qcu.be/forums/qcubed-framework/help'> support forums</a>" .
		", a vibrant community is there to help you all the time. There's also a " .
		"<a target='_blank' href='http://qcu.be/chat'>chat room</a> where you can get help right away. </p>";
		
	echo "<p>Here's what you need to do:</p>\n<ol>";	
	foreach ($arrInstallationMessages as $objResult) {
		echo "<li>" . $objResult->strMessage . "</li>\n";
	}
	echo "</ol>\n";
	
	// Output commands that can help fix these issues
	$commands = "";
	foreach ($arrInstallationMessages as $objResult) {
		if (isset($objResult->strCommandToFix) && strlen($objResult->strCommandToFix) > 0) {
			$commands .= $objResult->strCommandToFix . "<br />";
		}
	}
	
	// On non-windows only, and only if there's at least 1 command to show
	if (!strtoupper(substr(PHP_OS, 0, 3) == 'WIN') && strlen($commands) > 0) {
		echo "<p>Here are commands that can fix several of these issues:</p>";
		echo "<pre style='background-color: #CCC'>" . $commands . "</pre>";
	}

	echo "<input type='button' value=\"I'm done, continue\" onclick='window.location.reload()' /><br/><br/>" .
		"<a href='start_page.php'>Ignore these warnings and continue</a> (not recommended)";
}

/**
 * Returns an array of QInstallationValidationResult objects, or if all
 * instructions / installation requirements were already satisfied, returns
 * an empty array.
 */
function ValidateInstall() {
	$result = array();
	$docrootOnlyPath = __DOCROOT__ . $_SERVER['PHP_SELF'];
	$docrootWithSubdirPath = __DOCROOT__ . __DEVTOOLS__ . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], "/"));	

	$root = substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF']));
	$part1 = substr($_SERVER['PHP_SELF'], 1, strpos($_SERVER['PHP_SELF'], "/", 1) - 1);
	$part2 = substr($root, strrpos($root, "/") + 1);
	
/*
	// Debugging stuff - there until this code stabilizes across multiple platforms. 
	
	print("DOCROOT = " . __DOCROOT__ . "<br>");
	print("SUBDIR = " . __SUBDIRECTORY__ . "<br>");
	print("DEVTOOLS = " . __DEVTOOLS__ . "<br>"); 
		
	print("PHP_SELF = " . $_SERVER['PHP_SELF'] . "<br>");
	print("SCRIPT_FILENAME = " . $_SERVER['SCRIPT_FILENAME'] . "<br>");

	print("root = " . $root . "<br>");
	print("part1 = " . $part1 . "<br>");
	print("part2 = " . $part2 . "<br>");
*/	
	
	if (!file_exists($docrootOnlyPath)) {
		$obj = new stdClass();
		$obj->strMessage = 'Set the __DOCROOT__ constant in ' .
			'/includes/configuration/configuration.inc.php. ' .
			'Most likely value: "' . $root . '"';
		$result[] = $obj;

		// At this point, we cannot proceed with any more checks - basic config
		// is not set up. Just exit.
		return $result;
	}

	if (!file_exists($docrootWithSubdirPath)) {
		$obj = new stdClass();
		$obj->strMessage = 'Set the __SUBDIRECTORY__ constant in ' .
			'/includes/configuration/configuration.inc.php. ' .
			'Most likely value: "/' . $part1 . '"';
		$result[] = $obj;
				
		// At this point, we cannot proceed with any more checks - basic config
		// is not set up. Just exit.
		return $result;
	}
	
	if (!file_exists(__INCLUDES__)) {
		// Did the user move the __INCLUDES__ directory out of the docroot? 
		$obj = new stdClass();
		$obj->strMessage = 'Set the __INCLUDES__ constant in ' .
			'includes/configuration/configuration.inc.php. ';
		$result[] = $obj;
				
		// At this point, we cannot proceed with any more checks - basic config
		// is not set up. Just exit.
		return $result;
	}
	
	// Now that we know that the basic config is correct, we can actually
	// initialize the full QCubed framework. 
	require(__CONFIGURATION__. '/prepend.inc.php');
	
	$qappValidationResults = QInstallationValidator::Validate();
	$result = array_merge($result, $qappValidationResults);
	
	return $result;
}
?>
