<?php
$__CONFIG_ONLY__ = true;
require('../qcubed.inc.php');
require(__QCUBED_CORE__ . '/framework/QInstallationValidator.class.php');

$arrInstallationMessages = QInstallationValidator::Validate();
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

?>
