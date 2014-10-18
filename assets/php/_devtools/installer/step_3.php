<?php

	// The final stage of setting the configuration.inc.php to its place. First of all, let us get the variables

	require_once('../../qcubed.inc.php');

	// Before anything, we have to see that the values were recieved.
	// Set Error to null
	$strError = null;
	
	// The directories
	if(!isset($_POST['docroot'])) {
		$strError = 'This file can be accessed only while you follow the configuration wizard step by step. Please go back to <a href="step_1.php">Step 1</a> to start over.';
	}
	$strDocroot = $_POST['docroot'];
	$strVirtDir = $_POST['virtdir'];
	$strSubDir = $_POST['subdir'];
	$strConfigSubPath =  DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'configuration';

	// let us see if there already is a configuration.inc.php file or not.
	$blnConfigFileExists = false;
	if(file_exists($strDocroot . $strVirtDir . $strSubDir . '/project' . $strConfigSubPath . '/configuration.inc.php')) {
		$blnConfigFileExists = true;
	}

	// Database values
	$strDB_Adapter = $_POST['db_server_adapter'];
	$strDB_ServerAddress = $_POST['db_server_address'];
	$strDB_Port = $_POST['db_server_port'];
	$strDB_DbName = $_POST['db_server_dbname'];
	$strDB_Username = $_POST['db_server_username'];
	$strDB_Password = $_POST['db_server_password'];
	// Now read the text from the configuration.inc.php.sample file
	$strConfigSampleText = file_get_contents($strDocroot . $strVirtDir . $strSubDir . '/project' . $strConfigSubPath . '/configuration.inc.php.sample');

	if($strConfigSampleText === false) {
		if($strError == null) {
			$strError = "The sample configuration file is missing. It is needed to generate the final config file.";
		}
	}

	// We now have the sample config file. Time to replace the strings.
	$strConfigText = $strConfigSampleText;
	$strConfigText = str_replace('{C:/xampp/xampp/htdocs}', $strDocroot, $strConfigText);
	$strConfigText = str_replace('{~my_user}', $strVirtDir, $strConfigText);
	$strConfigText = str_replace('{/qcubed2}', $strSubDir, $strConfigText);
	$strConfigText = str_replace('{db1_adapter}', $strDB_Adapter, $strConfigText);
	$strConfigText = str_replace('{db1_serverAddress}', $strDB_ServerAddress, $strConfigText);
	// if the port was left blank, then we replace it with null
	if(trim($strDB_Port) == '') {
		// blank value.set as null
		$strDB_Port = 'null';
	}
	$strConfigText = str_replace("'{db1_serverport}'", $strDB_Port, $strConfigText);
	$strConfigText = str_replace('{db1_dbname}', $strDB_DbName, $strConfigText);
	$strConfigText = str_replace('{db1_username}', $strDB_Username, $strConfigText);
	$strConfigText = str_replace('{db1_password}', $strDB_Password, $strConfigText);

	$strConfigText_Final = $strConfigText;

	// Now the final text should be ready.
	// Display the final text
//	echo '<html><body><textarea cols="160" rows="30"> '. $strConfigText . ' </textarea></body>';
//	exit();

	// Find the current file path.
	$strCurrentFullPath = $_SERVER['SCRIPT_FILENAME'];
	
	// Get the directory name
	$strCurrDir = dirname($strCurrentFullPath);

	// get the length string after the word 'assets' in the path.
	$intExtraLength = strlen(strstr($strCurrDir, 'vendor/'));

	// Current installation directory should be
	$strCurrentInstallationDir = substr($strCurrDir, 0, (strlen($strCurrDir) - $intExtraLength));
	// Try to remove the trailing slash
	if('/' == substr($strCurrentInstallationDir, (strlen($strCurrentInstallationDir) - 1), strlen($strCurrentInstallationDir))) {
		// slash in end
		$strCurrentInstallationDir = substr($strCurrentInstallationDir, 0, (strlen($strCurrentInstallationDir) - 1));
	}
	
	$strStylePath = $strCurrentInstallationDir . str_replace('/', DIRECTORY_SEPARATOR, '/vendor/qcubed/framework/assets/css/styles.css');
	$strExamplesStylePath = $strCurrentInstallationDir . str_replace('/', DIRECTORY_SEPARATOR, '/vendor/qcubed/framework/assets/php/examples/includes/examples.css');
	
	$strCurrentInstallationUrl = substr($strCurrentInstallationDir, strlen(rtrim($_SERVER['DOCUMENT_ROOT'])));
        if (DIRECTORY_SEPARATOR != substr($strCurrentInstallationUrl, 0, 1)) {
                $strCurrentInstallationUrl = DIRECTORY_SEPARATOR . $strCurrentInstallationUrl;
        }
	$strStyleUrl = str_replace('/', DIRECTORY_SEPARATOR, $strCurrentInstallationUrl . '/vendor/qcubed/framework/assets/css/styles.css');
	$strExamplesStyleUrl = str_replace('/', DIRECTORY_SEPARATOR, $strCurrentInstallationUrl . '/vendor/qcubed/framework/assets/php/examples/includes/examples.css');
	$strImagesUrl = str_replace('/', DIRECTORY_SEPARATOR, $strCurrentInstallationUrl . '/vendor/qcubed/framework/assets/images');
	
	// We will start the HTML output now.
?>

<!DOCTYPE html>
<html>
	<head>
		<title>QCubed Installation Wizard - Step 3</title>
		<?php
			if (file_exists($strStylePath)) {
		?>
			<style type="text/css">@import url("<?php _p($strStyleUrl); ?>");</style>
		<?php
			}
		?>
		<?php
			if (file_exists($strExamplesStylePath)) {
		?>
		<style type="text/css">@import url("<?php _p($strExamplesStyleUrl); ?>");</style>
		<?php
			}
		?>
		<style type="text/css">
			#final_config {
				color: black;
				width: 100%;
			}
		</style>
	</head>
	<body>
		<section id="content">
			<h1 class="page-title">QCubed Installation Wizard</h1>

			<h2>Step 3: Save the configuration.inc.php file</h2>

			<?php

			if($strError != null) {
				// There was error. Display it
				echo '
					<div style="color: #DD3333">
						<strong>Error:</strong>' . $strError . '
					</div>';
			} else {
				// No errors till now.
				// File creation status indicator
				$strFileCreationStatus = 'unknown';
				// Is there a configuration.inc.php file already?
				if($blnConfigFileExists) {
					// it is already there.
					?>
			<div id="instructions" class="full">
				<p>There already is a <code>configuration.inc.php</code> file located at <?php _p($strDocroot . $strVirtDir . $strSubDir . $strConfigSubPath) ?>. The existing file will not be overwritten. However, the text generated by the wizard is available to you, if you want to use it manually.</p>
			</div>
					<?php
					$strFileCreationStatus = 'exists';
				} else {
					// The configuration file is not there. Try to create one.
					// use the @ to prevent exception traces for special debug serever configurations, like 
					$rscFileHandle = @fopen($strDocroot . $strVirtDir . $strSubDir . '/project' . $strConfigSubPath . '/configuration.inc.php', 'w');
					if($rscFileHandle === false) {
						// File creation failed.
						?>
							<div id="instructions" class="full">
								<strong>Error:</strong> File creation failed. It is possible that the wizard does not have the permission to create a file in 
								<code>
									<?php _p($strDocroot . $strVirtDir . $strSubDir . $strConfigSubPath) ?>
								</code>.
								The generated text is available here. You can create the configuration file (filename should be <code>configuration.inc.php</code>) manually in the directory: 
								<code> <?php _p($strDocroot . $strVirtDir . $strSubDir . $strConfigSubPath) ?> </code>
								 and put the generated contents into it.
							</div>
						<?php
						$strFileCreationStatus = 'creation_failed';
					} else {
						// File created. Now we will write the data into it.
						fwrite($rscFileHandle, $strConfigText_Final);
						// close the handle
						fclose($rscFileHandle);
						// Tell the user that the file was created.
						echo 'The configuration file has been generated with the contents below.';
						$strFileCreationStatus = 'created';
					}
				}

				// Show the contents of the file and disable editing
				?>
				<textarea name="final_config" id="final_config" disabled="disabled" rows="20" cols="100"><?php _p($strConfigText_Final) ?></textarea>
				<?php
				// depending on the file creation status, show the message at the bottom.
				switch ($strFileCreationStatus) {
					case 'exists':
					case 'creation_failed':
						echo '<br/> <a href="' . $strVirtDir . $strSubDir . '/vendor/qcubed/framework/assets/php/_devtools/config_checker.php">Launch the config checker</a>';
						break;
					case 'created':
						?>
				<br/><br/>
				<div id="instructions" class="full">
					<p><strong>Configuration file was created!</strong></p>
					<p>Make sure to revert directory permissions back for security:
					<code>chmod 775 <?= $strDocroot . $strSubDir . DIRECTORY_SEPARATOR . 'project/includes' . DIRECTORY_SEPARATOR . 'configuration' ; ?></code></p>
				</div>
				<?php
						echo '<a href="' . $strVirtDir . $strSubDir . '/vendor/qcubed/framework/assets/php/_devtools/config_checker.php">Launch the config checker</a> to make sure everything went fine.';
						break;
					default:
						// do nothing
						break;
				}
			}

		?>

		</section>
		<footer>
			<div id="tagline"><a href="http://qcubed.github.com/" title="QCubed Homepage"><img id="logo" src="<?php _p($strImagesUrl . '/qcubed_logo_footer.png'); ?>" alt="QCubed Framework" /> <span class="version"><?php _p(QCUBED_VERSION); ?></span></a></div>
		</footer>
	</body>
</html>
