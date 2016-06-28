<?php
	require_once('../qcubed.inc.php');
	QApplication::CheckRemoteAdmin();
	
	// Create an installation status message.
	$arrInstallationMessages = QInstallationValidator::Validate();
	$strConfigStatus = ($arrInstallationMessages) ?
		'<span class="warning">' . count($arrInstallationMessages).' problem(s) found. <a href="' . __VIRTUAL_DIRECTORY__ . __DEVTOOLS_ASSETS__ . '/config_checker.php">Click here</a> to view details.</span>' :
		'<span class="success">all OK.</span>';
	
	$strPageTitle = 'QCubed Development Framework - Start Page';
	require(__CONFIGURATION__ . '/header.inc.php');
?>
	<h1 class="page-title">Welcome to QCubed!</h1>
	<div class="install-status">
		<p><strong>If you are seeing this, the framework has been successfully installed.</strong></p>
		<p>Current installation status:  <?php _p($strConfigStatus, false) ?></p>
	</div>
	<h2>Next Steps</h2>
	<ul class="link-list">
		<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS_ASSETS__) ?>/codegen.php">Code Generator</a> - to create ORM model objects that map to tables in your database, and ModelConnectors
			and form drafts to edit and display the data.</li>
		<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS_ASSETS__) ?>/form_drafts.php">View Form Drafts</a> - to view the generated files (after you run the Code Generator).</li>
		<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __EXAMPLES__) ?>/index.php">QCubed Examples</a> - learn QCubed by studying and modifying the example files locally.</li>
		<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS_ASSETS__) ?>/plugin_manager.php">Plugin Manager</a> - to extend QCubed with community-contributed plugins.</li>
		<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __PHP_ASSETS__) ?>/qcubed_unit_tests.php">QCubed Unit Tests</a> - set of tests that QCubed developers use to verify the integrity of the framework.
			You must install the test SQL database and codegen_options.json file to run the tests. These can be found in the <?php _p(__VIRTUAL_DIRECTORY__ . __PHP_ASSETS__ . '/examples')?> directory.</li>
	</ul>
<?php if (!QApplication::IsRemoteAdminSession()) { ?>
	<pre><code><?php QApplication::VarDump(); ?></code></pre>
<?php } ?>
<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>