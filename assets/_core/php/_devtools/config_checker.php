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
	
	if (!file_exists(__DOCROOT__ . __SUBDIRECTORY__ . $_SERVER['PHP_SELF'])) {
		$errorMessage = "Set __DOCROOT__ and __SUBDIRECTORY__ constants in /includes/configuration/configuration.inc.php. Most likely values: ";
				
		$root = substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF']));
		$part1 = substr($_SERVER['PHP_SELF'], 1, strpos($_SERVER['PHP_SELF'], "/", 1) - 1);
		$part2 = substr($root, strrpos($root, "/") + 1);
		
		// Attempt to calculate what the constants should be, based on the current
		// absolute script path (SCRIPT_FILENAME) and the web server root-relative
		// path (PHP_SELF)
		if ($part1 != $part2) {
			$errorMessage .= '__DOCROOT__ = "' . $root . '", __SUBDIRECTORY__ = ""';
		} else {
			$errorMessage .= '__DOCROOT__ = "' . substr($root, 0, 0 - strlen($part2) - 1) .
				'", __SUBDIRECTORY__ = "/' . $part2 . '"';
		}
		$result[] = $errorMessage;

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