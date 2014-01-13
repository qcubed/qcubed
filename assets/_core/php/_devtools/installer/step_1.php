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
	if (DIRECTORY_SEPARATOR != substr($strCurrentInstallationUrl, 0, 1)) {
		$strCurrentInstallationUrl = DIRECTORY_SEPARATOR . $strCurrentInstallationUrl;
	}
	$strStyleUrl = str_replace('/', DIRECTORY_SEPARATOR, $strCurrentInstallationUrl . '/assets/_core/css/styles.css');
	$strImagesUrl = str_replace('/', DIRECTORY_SEPARATOR, $strCurrentInstallationUrl . '/assets/_core/images');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>QCubed Installation Wizard - Step 1</title>
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
		<form action="step_2.php" method="get">
			<h1 class="page-title">QCubed Installation Wizard</h1>

			<h2>Step 1: Set the installation directory</h2>

			<p>
				Enter the path where you have copied the framework files. The path would ideally contain the directories
				<em>assets</em>, <em>drafts</em> and <em>includes</em> along with a few other files.<br>
				<strong>Most likely value</strong>: <code><?php echo $strCurrentInstallationDir; ?></code>
			</p>
			<label for="installation_path">Enter installation directory: </label>
			<input type="text" size="40" id="installation_path" name="installation_path"
				   value="<?php echo $strCurrentInstallationDir; ?>"/>
			<input type="submit" value="Next">
		</form>

		<p>
			<span><strong>If you do not want to use this wizard, follow the instructions below to configure QCubed:</strong></span>
		<ol>
			<li>
				Open the installation directory where you have extracted/copied the QCubed installation files. It
				appears you have copied QCubed to <code><?php echo $strCurrentInstallationDir; ?></code>
			</li>
			<li>
				Go to the <em>includes</em> directory within the installation directory. The full path should be
				<code><?php echo $strCurrentInstallationDir; ?>/includes</code>.
			</li>
			<li>
				See if a file named <code>configuration.inc.php</code> already exists.
				<ul>
					<li>
						If it already exists, then probably you have the framework configured and you need not do
						anything.
					</li>
					<li>
						If it does not exist already, then create a new file with that name (<code>configuration.inc.php</code>) and copy the contents of <code>configuration.inc.php.sample</code> into <code>configuration.inc.php</code>.
					</li>
				</ul>
			</li>
		<li>
			Change the variables <code>__DOCROOT__</code>, <code>__SUBDIRECTORY__</code> and <code>__VIRTUAL_DIRECTORY__</code> in the new file. Also alter the settings of <code>DB_CONNECTION_1</code> to desired values. The file (<code>configuration.inc.php</code>) contains required information about the importance and usage of these variables (along with a few suggestions).
		</li>
		<li>
			The framework should get installed at this point of time. Just visit the home page!
		</li>
		</ol>
		</p>
		</section>
		<footer>
			<div id="tagline"><a href="http://qcubed.github.com/" title="QCubed Homepage"><img id="logo" src="<?php _p($strImagesUrl . '/qcubed_logo_footer.png'); ?>" alt="QCubed Framework" /> <span class="version"><?php _p(QCUBED_VERSION); ?></span></a></div>
		</footer>
	</body>
</html>