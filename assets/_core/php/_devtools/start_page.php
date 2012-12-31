<?php
	require_once('../qcubed.inc.php');
	QApplication::CheckRemoteAdmin();
	
	// Create an installation status message.
	$arrInstallationMessages = QInstallationValidator::Validate();
	$strConfigStatus = ($arrInstallationMessages) ?
		'<span class="warning">' . count($arrInstallationMessages).' problem(s) found. <a href="' . __VIRTUAL_DIRECTORY__ . __DEVTOOLS__ . '/config_checker.php">Click here</a> to view details.</span>' :
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
		<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS__) ?>/codegen.php">Code Generator</a> - to create ORM objects that map to tables in your database.</li>
		<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__) ?>/index.php">View Form Drafts</a> - to view the generated UI scaffolding (after you run the Code Generator).</li>
		<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __EXAMPLES__) ?>/index.php">QCubed Examples</a> - learn QCubed by studying and modifying the example files locally.</li>
		<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS__) ?>/plugin_manager.php">Plugin Manager</a> - to extend QCubed with community-contributed plugins.</li>
		<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS__) ?>/update_checker.php">Update Checker</a> - check for updates for QCubed core and plugins.</li>
		<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __PHP_ASSETS__) ?>/qcubed_unit_tests.php">QCubed Unit Tests</a> - set of tests that QCubed developers use to verify the integrity of the framework. Test dataset required. </li>
	</ul>
<?php if (!QApplication::IsRemoteAdminSession()) { ?>
	<code><pre><?php QApplication::VarDump(); ?></pre></code>
<?php } ?>
<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>