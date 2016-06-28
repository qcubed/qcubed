<?php
	/**
	 * Created by vaibhav on 12/22/12 (10:16 AM).
	 */
	require_once('../../qcubed.inc.php');

//	$blnGetInstPath = true;
	// See that the installation directory was supplied.
	if(!isset($_GET['installation_path'])) {
		// It was not supplied. Show error and exit
		echo '
		<html>
		<head>
		    <title>QCubed Installation Wizard - Step 2</title>
		</head>
		<body>
		<div style="display: block; font-family: Arial, Sans-Serif;">
		    <div style="display: block; margin-left: auto; margin-right: auto; width: 800px; background: #FFDDDD; padding: 10px; border: 1px solid #DD0000">
		            <h1>
		                QCubed Installation Wizard
		            </h1>
			<div style="color: #DD3333">
			<strong>Error:</strong> Installation path was not recieved. Please go to <a href="step_1.php">Step 1</a> and set it.
			</div>
		    </div>
		</body>
		</html>';

		exit();
	}

	// Installation directory was supplied. Get it into a variable
	$strInstallationDir = $_GET['installation_path'];
	// decode it properly to be safe
	$strInstallationDir = urldecode($strInstallationDir);
	// Get the current Server DocumentRoot
	$strServerDocumentRoot = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR);
	// Check that the current installation directory is within the document root
	$intPos = strpos($strInstallationDir, $strServerDocumentRoot);
	if($intPos !== 0) {
		// The installation path does not begin with the server installation path.
		// Show the error and exit.
		$strOutput = sprintf('
		<html>
		<head>
		    <title>QCubed Installation Wizard - Step 2</title>
		</head>
		<body>
		<div style="display: block; font-family: Arial, Sans-Serif;">
		    <div style="display: block; margin-left: auto; margin-right: auto; width: 800px; background: #FFDDDD; padding: 10px; border: 1px solid #DD0000">
		            <h1>
		                QCubed Installation Wizard
		            </h1>
			<div style="color: #DD3333">
			<strong>Error:</strong> The installation directory entered in step 1 (%s) does not seem to be under the DocumentRoot of the server (%s). Please go back to <a href="step_1.php">Step 1</a> and set it.
			</div>
		    </div>
		</body>
		</html>
		', $strInstallationDir, $strServerDocumentRoot);

		echo $strOutput;

		exit();
	}

	// Installation directory seems to be under document root.
	// Try to figure out the subdirectory
	$strSubDirectory = substr($strInstallationDir, strlen($strServerDocumentRoot), strlen($strInstallationDir) - strlen($strServerDocumentRoot));
	if (DIRECTORY_SEPARATOR !== mb_substr($strSubDirectory, 0, 1)) {
		$strSubDirectory = DIRECTORY_SEPARATOR . $strSubDirectory;
	}
	
	if ($strSubDirectory == DIRECTORY_SEPARATOR) {
		$strSubDirectory = '';
	}

	// Make sure the installation directory supplied exists
	if(!is_dir($strInstallationDir)) {
		// The supplied path does not exist
		// It was not supplied. Show error and exit
		echo '
		<html>
		<head>
		    <title>QCubed Installation Wizard - Step 2</title>
		</head>
		<body>
		<div style="display: block; font-family: Arial, Sans-Serif;">
		    <div style="display: block; margin-left: auto; margin-right: auto; width: 800px; background: #FFDDDD; padding: 10px; border: 1px solid #DD0000">
		            <h1>
		                QCubed Installation Wizard
		            </h1>
			<div style="color: #DD3333">
			<strong>Error:</strong> Directory supplied for Installation path (' . $strInstallationDir . ') does not seem to be a directory. Please go to <a href="step_1.php">Step 1</a> and set it correctly.
			</div>
		    </div>
		</body>
		</html>';

		exit();
	}

	// Make sure that the directory has all 3 folders inside - includes, assets and drafts
	if(!is_dir($strInstallationDir . '/project/includes') || !is_dir($strInstallationDir . '/project/assets')) {
		// The supplied value does not contain the required folders(directories).
		// Show error and exit coz this can't be the right path.
		echo '
		<html>
		<head>
		    <title>QCubed Installation Wizard - Step 2</title>
		</head>
		<body>
		<div style="display: block; font-family: Arial, Sans-Serif;">
		    <div style="display: block; margin-left: auto; margin-right: auto; width: 800px; background: #FFDDDD; padding: 10px; border: 1px solid #DD0000">
		            <h1>
		                QCubed Installation Wizard
		            </h1>
			<div style="color: #DD3333">
			<strong>Error:</strong> Directory supplied for Installation path (' . $strInstallationDir . ') does not seem to have the directories <strong>includes</strong> and <strong>assets</strong>. Please go to <a href="step_1.php">Step 1</a> to set up the Installation path correctly.
			</div>
		    </div>
		</body>
		</html>';

		exit();
	}

	//Create the array for Databases
	$arrDatabaseAdapters = array(
		"MySqli5",
		"PostgreSql",
		"SqlServer",
		"Oracle",
	);
	// Try to remove the trailing slash
	if('/' == substr($strServerDocumentRoot, (strlen($strServerDocumentRoot) - 1), strlen($strServerDocumentRoot))) {
		// slash in end
		$strServerDocumentRoot = substr($strServerDocumentRoot, 0, (strlen($strServerDocumentRoot) - 1));
	}

	// Set. Now we can create the HTML
	$strCurrentInstallationDir = $strInstallationDir;
	$strStylePath = $strCurrentInstallationDir . str_replace('/', DIRECTORY_SEPARATOR, '/vendor/qcubed/framework/assets/css/styles.css');
	$strExamplesStylePath = $strCurrentInstallationDir . str_replace('/', DIRECTORY_SEPARATOR, '/vendor/qcubed/framework/assets/php/examples/includes/examples.css');
	
	$strCurrentInstallationUrl = substr($strCurrentInstallationDir, strlen(rtrim($_SERVER['DOCUMENT_ROOT'])));
        if (DIRECTORY_SEPARATOR != substr($strCurrentInstallationUrl, 0, 1)) {
                $strCurrentInstallationUrl = DIRECTORY_SEPARATOR . $strCurrentInstallationUrl;
        }
	$strStyleUrl = str_replace('/', DIRECTORY_SEPARATOR, $strCurrentInstallationUrl . '/vendor/qcubed/framework/assets/css/styles.css');
	$strExamplesStyleUrl = str_replace('/', DIRECTORY_SEPARATOR, $strCurrentInstallationUrl . '/vendor/qcubed/framework/assets/php/examples/includes/examples.css');
	$strImagesUrl = str_replace('/', DIRECTORY_SEPARATOR, $strCurrentInstallationUrl . '/vendor/qcubed/framework/assets/images');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>QCubed Installation Wizard - Step 2</title>
		<?php
			if (file_exists($strStylePath)) {
		?>
		<style type="text/css">@import url("<?php _p($strStyleUrl, false); ?>");</style>
		<?php
			}
		?>
		<?php
			if (file_exists($strExamplesStylePath)) {
		?>
		<style type="text/css">@import url("<?php _p($strExamplesStyleUrl, false); ?>");</style>
		<?php
			}
		?>
	</head>
	<body>
		<section id="content">
			<h1 class="page-title">QCubed Installation Wizard</h1>

			<h2>Step 2: Set the variables and database information</h2>

			<div id="instructions" class="full">
				<p>Make sure that the configuration directory is writable:<br/>
				<code>chmod 777 <?= $strServerDocumentRoot . $strSubDirectory . DIRECTORY_SEPARATOR . 'project' . DIRECTORY_SEPARATOR . 'includes/configuration' ; ?></code></p>
			</div>

            <form action="step_3.php" method="post">
                <div id="instructions" class="full">
                    <p>This value should be the same as your server (e.g. Apache) DocumentRoot value.</p>
                    <p>For example, if your example web application where <code>http://my.domain.com/index.php</code>
                    points to <code>/home/web/htdocs/index.php</code>, then you must specify:
                    <code>__DOCROOT__</code> is defined as <code>/home/web/htdocs</code> (note the leading slash and no ending slash)</p>
                    <p>On Windows, if you have <code>http://my.domain.com/index.php</code> pointing to <code>c:\webroot\files\index.php</code>,
                    then: <code>__DOCROOT__</code> is defined as <code>c:/webroot/files</code> (again, note the leading c:/ and no ending slash)</p>
                </div>

				<div id="demoZone" class="full">
					<label for="docroot">__DOCROOT__</label>
					<input type="text" id="docroot" name="docroot" value="<?= $strServerDocumentRoot; ?>"/>
				</div>

                <div id="instructions" class="full">
                    <p>Next, if you are using Virtual Directories, where
                    <code>http://not.my.domain.com/~my_user/index.php</code>
                    (for example) points to <code>/home/my_user/public_html/index.php</code>, then:
                    <code>__DOCROOT__</code> is defined as <code>/home/my_user/public_html</code>
                    <code>__VIRTUAL_DIRECTORY__</code> is defined as <code>/~my_user</code></p>
                </div>

				<div id="demoZone" class="full">
					<label for="virtdir">__VIRTUAL_DIRECTORY__</label>
					<input type="text" id="virtdir" name="virtdir" value=""/>
				</div>

                <div id="instructions" class="full">
                    <p>If you have installed QCubed within a SubDirectory of the Document Root, so for example
                    the QCubed "index.php" page is accessible at
                    <code>http://my.domain.com/frameworks/qcubed/index.php</code>, then:
                    <code>__SUBDIRECTORY__</code> is defined as <code>/frameworks/qcubed</code>
                    (again, note the leading and no ending slash)</p>
                </div>

				<div id="demoZone" class="full">
					<label for="subdir">__SUBDIRECTORY__</label>
					<input type="text" id="subdir" name="subdir" value="<?= $strSubDirectory; ?>"/>
				</div>

                <div id="instructions" class="full">
					<h2>First Database configuration</h2>
                    <p>The database type you are about to use.</p>
                </div>
				
				<div id="demoZone" class="full">
					<label for="db_server_adapter">Database Adapter</label>
					<select id="db_server_adapter" name="db_server_adapter">
<?php
	foreach($arrDatabaseAdapters as $strAdapter) {
?>
						<option value="<?php _p($strAdapter, false) ?>"><?php _p($strAdapter) ?></option>
<?php
	}
?>
					</select>
				</div>

                <div id="instructions" class="full">
                    <p>The IP address/hostname where database server is located.</p>
                </div>

				<div id="demoZone" class="full">
					<label for="db_server_address">Database Server Address</label>
					<input type="text" name="db_server_address" id="db_server_address" value="localhost"/>
				</div>

                <div id="instructions" class="full">
                    <p>Leaving this field blank will automatically select the default port for the selected database. (e.g.
                    3306 for MySQL)</p>
                </div>

				<div id="demoZone" class="full">
					<label for="db_server_port">Database Server Port</label>
					<input type="text" name="db_server_port" id="db_server_port" value=""/>
				</div>

                <div id="instructions" class="full">
                    <p>Name of the database you want to use in your application.</p>
                </div>

				<div id="demoZone" class="full">
					<label for="db_server_dbname">Database Name</label>
					<input type="text" name="db_server_dbname" id="db_server_dbname" value="qcubed"/>
				</div>

                <div id="instructions" class="full">
                    <p>The database user should typically have the write permissions on the database you chose.</p>
                </div>

				<div id="demoZone" class="full">
					<label for="db_server_username">Database Server Username</label>
					<input type="text" name="db_server_username" id="db_server_username" value="root"/>
				</div>

                <div id="instructions" class="full">
                    <p><strong>NOTE:</strong> This field will accept the password for the database user you supply in the
                    <em>Database Server Username</em> field but is a normal textbox (not the password one). This is to help you
                    write the password correctly.</p>
                </div>

				<div id="demoZone" class="full">
					<label for="db_server_password">Database Server Password</label>
					<input type="text" name="db_server_password" id="db_server_password" value=""/>
				</div>

				<div id="instructions" class="full">
					<p><strong><em>NOTE:</em></strong> Caching and profiling will be disabled by default on the database. If
					you want to enable them, please change settings by hand in <code>configuration.inc.php</code> after this
					setup wizard completes.</p>
				</div>

                <input type="submit" value="Write configuration"/>
            </form>
		</section>
		<footer>
			<div id="tagline"><a href="http://qcubed.github.com/" title="QCubed Homepage"><img id="logo" src="<?php _p($strImagesUrl . '/qcubed_logo_footer.png', false); ?>" alt="QCubed Framework" /> <span class="version"><?php _p(QCUBED_VERSION); ?></span></a></div>
		</footer>
	</body>
</html>
