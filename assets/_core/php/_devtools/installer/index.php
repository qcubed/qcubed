<?php
	/**
	 * Created by vaibhav on 12/22/12 (9:43 AM).
	 */

	require_once('../../qcubed.inc.php');
	// The first thing to do is to see if the variables are set.

	// Find the current file path.
	$strCurrentFullPath = $_SERVER['SCRIPT_FILENAME'];
	
	// Get the directory name
	$strCurrDir = dirname($strCurrentFullPath);

	// get the length string after the word 'assets' in the path.
	$intExtraLength = strlen(strstr($strCurrDir, 'assets/'));

	// Current installation directory should be
	$strCurrentInstallationDir = substr($strCurrDir, 0, (strlen($strCurrDir) - $intExtraLength));
	// Try to remove the trailing slash
	if('/' == substr($strCurrentInstallationDir, (strlen($strCurrentInstallationDir) - 1), strlen($strCurrentInstallationDir))) {
		// slash in end
		$strCurrentInstallationDir = substr($strCurrentInstallationDir, 0, (strlen($strCurrentInstallationDir) - 1));
	}
	$strStylePath = $strCurrentInstallationDir . str_replace('/', DIRECTORY_SEPARATOR, '/assets/_core/css/styles.css');
	
	$strCurrentInstallationUrl = substr($strCurrentInstallationDir, strlen(rtrim($_SERVER['DOCUMENT_ROOT'])));
	$strStyleUrl = str_replace('/', DIRECTORY_SEPARATOR, $strCurrentInstallationUrl . '/assets/_core/css/styles.css');
	$strImagesUrl = str_replace('/', DIRECTORY_SEPARATOR, $strCurrentInstallationUrl . '/assets/_core/images');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>QCubed Installation Wizard</title>
		<?php
			if (file_exists($strStylePath)) {
		?>
			<style type="text/css">@import url("<?php _p($strStyleUrl); ?>");</style>
		<?php
			}
		?>
	</head>
	<body>
		<section id="content">
			<h1>QCubed Installation - Make a choice</h1>
			<p>
				<ul>
			<li>If you have QCubed installed successfully, <strong><a href="../config_checker.php">click here to launch the start page</a></strong>.</li>
			<li>If this is the first time you are seeing this page and want to install QCubed, <strong><a href="step_1.php">click here to start the installation wizard</a></strong>.</li>
				</ul>
			</p>
		</section>
		<footer>
			<div id="tagline"><a href="http://qcubed.github.com/" title="QCubed Homepage"><img id="logo" src="<?php _p($strImagesUrl . '/qcubed_logo_footer.png'); ?>" alt="QCubed Framework" /> <span class="version"><?php _p(QCUBED_VERSION); ?></span></a></div>
		</footer>
	</body>
</html>