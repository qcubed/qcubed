<?php
	/**
	 * Created by vaibhav on 12/22/12 (10:16 AM).
	 */

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
	$strServerDocumentRoot = $_SERVER['DOCUMENT_ROOT'];
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
	$strSubDirectory = substr($strInstallationDir, (strlen($strServerDocumentRoot)), (strlen($strInstallationDir) - 1));

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
	if(!is_dir($strInstallationDir . '/includes') || !is_dir($strInstallationDir . '/assets') || !is_dir($strInstallationDir . '/drafts')) {
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
			<strong>Error:</strong> Directory supplied for Installation path (' . $strInstallationDir . ') does not seem to have the directories <strong>includes</strong>, <strong>assets</strong> and <strong>drafts</strong>. Please go to <a href="step_1.php">Step 1</a> to set up the Installation path correctly.
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
?>

<html>
<head>
    <title>QCubed Installation Wizard - Step 2</title>
    <style type="text/css">
        label {
            font-weight: bold;
        }

        div.helptext {
            border: 1px solid #444444;
            background: #EEEEEE;
            padding: 10px;;
        }
    </style>
</head>
<body>
<div style="display: block; font-family: Arial, Sans-Serif;">
    <div style="display: block; margin-left: auto; margin-right: auto; width: 800px; background: #FFDDDD; padding: 10px; border: 1px solid #DD0000">
        <h1>
            QCubed Installation Wizard
        </h1>

        <h2 style="color: #AA3333">Step 2: Set the variables and database information</h2>

        <div>
			<div class="helptext">
				Make sure that the configuration directory is writable:<br/>
				<code>chmod 777 <?php echo $strServerDocumentRoot . $strSubDirectory . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'configuration' ; ?></code>
			</div>
			<p></p>
            <form action="step_3.php" method="post">
                <label for="docroot">__DOCROOT__</label>
                <input type="text" id="docroot" name="docroot" value="<?php echo $strServerDocumentRoot; ?>"/>
                <br>

                <div class="helptext">
                    This value should be the same as your server (e.g. Apache) DocumentRoot value.
                    <br><br>
                    For example, if your example web application where <code>http://my.domain.com/index.php</code>
                    points to
                    <br>
                    <code>/home/web/htdocs/index.php</code>, then you must specify:<br>
                    <code>__DOCROOT__</code> is defined as <code>/home/web/htdocs</code><br>
                    (note the leading slash and no ending slash)<br>
                    On Windows, if you have <code>http://my.domain.com/index.php</code> pointing to <code>c:\webroot\files\index.php</code>,
                    then:<br>
                    <code>__DOCROOT__</code> is defined as <code>c:/webroot/files</code><br>
                    (again, note the leading c:/ and no ending slash)<br>
                </div>
                <hr/>
                <br/>


                <label for="docroot">__VIRTUAL_DIRECTORY__</label>
                <input type="text" id="virtdir" name="virtdir" value=""/>
                <br>

                <div class="helptext">
                    Next, if you are using Virtual Directories, where
                    <code>http://not.my.domain.com/~my_user/index.php</code><br>
                    (for example) points to <code>/home/my_user/public_html/index.php</code>, then:<br>
                    <code>__DOCROOT__</code> is defined as <code>/home/my_user/public_html</code><br>
                    <code>__VIRTUAL_DIRECTORY__</code> is defined as <code>/~my_user</code><br>
                </div>
                <hr/>
                <br/>


                <label for="docroot">__SUBDIRECTORY__</label>
                <input type="text" id="subdir" name="subdir" value="<?php echo $strSubDirectory; ?>"/>
                <br>

                <div class="helptext">
                    If you have installed QCubed within a SubDirectory of the Document Root, so for example<br>
                    the QCubed "index.php" page is accessible at
                    <code>http://my.domain.com/frameworks/qcubed/index.php</code>, then:<br>
                    <code>__SUBDIRECTORY__</code> is defined as <code>/frameworks/qcubed</code><br>
                    (again, note the leading and no ending slash)<br>
                </div>
                <hr/>
                <br/>

                <h2>First Database configuration</h2>
                <label for="db_server_adapter">Database Adapter</label>
                <select id="db_server_adapter" name="db_server_adapter">
			<?php
			foreach($arrDatabaseAdapters as $strAdapter) {
				echo '<option value="' . $strAdapter . '">' . $strAdapter . '</option>';
			}
			?>
                </select>
                <br/>

                <label for="db_server_address">Database Server Address</label>
                <input type="text" name="db_server_address" id="db_server_address" value="localhost"/>
                <br>

                <div class="helptext">
                    The IP address/hostname where database server is located.
                </div>
                <hr/>
                <br/>
                <label for="db_server_port">Database Server Port</label>
                <input type="text" name="db_server_port" id="db_server_port" value=""/>
                <br>

                <div class="helptext">
                    Leaving this field blank will automatically select the default port for the selected database. (e.g.
                    3306 for MySQL)
                </div>
                <hr/>
                <br/>
                <label for="db_server_dbname">Database Name</label>
                <input type="text" name="db_server_dbname" id="db_server_dbname" value="qcubed"/>
                <br>

                <div class="helptext">
                    Name of the database you want to use in your application.
                </div>
                <hr/>
                <br/>
                <label for="db_server_username">Database Server Username</label>
                <input type="text" name="db_server_username" id="db_server_username" value="root"/>
                <br>

                <div class="helptext">
                    The database user should typically have the write permissions on the database you chose.
                </div>
                <hr/>
                <br/>
                <label for="db_server_password">Database Server Password</label>
                <input type="text" name="db_server_password" id="db_server_password" value=""/>
                <br>

                <div class="helptext">
                    <strong>NOTE:</strong> This field will accept the password for the database user you supply in the
                    <em>Database
                        Server Username</em> field but is a normal textbox (not the password one). This is to help you
                    write the
                    password correctly.
                </div>
                <hr/>
                <br/>
                <strong><em>NOTE:</em></strong> Caching and profiling will be disabled by default on the database. If
                you want to enable them, please change settings by hand in <code>configuration.inc.php</code> after this
                setup wizard completes.
                <br>
                <hr>
                <input type="submit" value="Write configuration"/>
            </form>
        </div>
</body>
</html>