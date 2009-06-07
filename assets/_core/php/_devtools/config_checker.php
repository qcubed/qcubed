<?php
require('../../../../includes/configuration/configuration.inc.php');

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
		
	echo "<p>Here's what you need to do:</p><ol>";
	
	foreach ($arrInstallationMessages as $strMessage) {
		echo "<li>" . $strMessage . "</li>";
	}
	
	echo "</ol>";
	echo "<input type='button' value=\"I'm done, continue\" /><br/><br/>" .
		"<a href='start_page.php'>Ignore these warnings and continue</a> (not recommended)";
}

/**
 * Returns an array of installation instructions, or if all instructions /
 * installation requirements were already satisfied, returns an empty array.
 */
function ValidateInstall() {
	$result = array();
	$docrootOnlyPath = __DOCROOT__ . $_SERVER['PHP_SELF'];
	$docrootWithSubdirPath = __DOCROOT__ . __SUBDIRECTORY__ . __DEVTOOLS__ . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], "/"));	

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
		$result[] = 'Set the __DOCROOT__ constant in /includes/configuration/configuration.inc.php. Most likely value: "' . $root . '"';
		return $result;
	}

	if (!file_exists($docrootWithSubdirPath)) {
		$result[] = 'Set the __SUBDIRECTORY__ constant in /includes/configuration/configuration.inc.php. Most likely value: "' . $part1 . '"';
				
		// At this point, we cannot proceed with any more checks - basic config
		// is not set up. Just exit.
		return $result;
	}
	
	// Now that we know that the basic config is correct, we can actually
	// initialize the full QCubed framework. 
	require('../../../../includes/configuration/prepend.inc.php');
	
	$qappValidationResults = QApplication::ValidateInstallation();
	$result = array_merge($result, $qappValidationResults);
	
	// TODO: add database connection string checks
	
	return $result;
}
?>
