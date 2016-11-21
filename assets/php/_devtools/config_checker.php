<?php
	$__CONFIG_ONLY__ = true;
	require('../qcubed.inc.php');
	require(__QCUBED_CORE__ . '/framework/QInstallationValidator.class.php');

	$arrInstallationMessages = QInstallationValidator::Validate();
	if (sizeof($arrInstallationMessages) == 0) {
		header("Location: start_page.php");
	} else {
		$strPageTitle = 'QCubed Configuration Wizard';
		require(__CONFIGURATION__ . '/header.inc.php');
?>
	<h1>Welcome to QCubed!</h1>
	<h2>PHP5 - PHP7 Model-View-Controller framework</h2>
	<p>
		This simple wizard will help you configure QCubed for first use.
		It'll take you just a couple minutes. If you have any questions along the way, feel free to ping us on the 
		<a target='_blank' href='https://github.com/qcubed/qcubed/issues'> support forums</a>, a vibrant community
		is there to help you all the time. There's also a <a target='_blank' href='http://qcu.be/chat'>chat room</a>
		where you can get help right away.
	</p>
	<p>Here's what you need to do:</p>
	<ol>
<?php // Output commands that can help fix these issues
		$commands = "";
		foreach ($arrInstallationMessages as $objResult) {
			if (isset($objResult->strCommandToFix) && strlen($objResult->strCommandToFix) > 0) {
				$commands .= $objResult->strCommandToFix . "\n";
			}
			echo "<li>" . $objResult->strMessage . "</li>\n";
		}
?>
	</ol>
<?php	if (!strtoupper(substr(PHP_OS, 0, 3) == 'WIN') && strlen($commands) > 0) { // On non-windows only, and only if there's at least 1 command to show ?>
	<p>Here are commands that can fix several of these issues:</p>
	<pre><code><?php _p($commands); ?></code></pre>
<?php	} ?>
	<p><button onclick="window.location.reload()">I'm done, continue</button></p>
	<p><a href="start_page.php">Ignore these warnings and continue</a> (not recommended)</p>
<?php
		require(__CONFIGURATION__ . '/footer.inc.php');
	}
