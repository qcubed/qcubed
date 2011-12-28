<?php
	require_once('../qcubed.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php _p(QApplication::$EncodingType); ?>" />
		<title>QCubed Development Framework - Start Page</title>
		<link rel="stylesheet" type="text/css" href="<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ . '/styles.css'); ?>"></link>
		<style>
			.shortcuts li {
				margin-top: 7px;
			}
		</style>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<div id="headerLeft">
					<div id="codeVersion">QCubed Development Framework <?= QCUBED_VERSION ?></div>
					<div id="pageName">Start Page</div>
				</div>
			</div>
			<div id="content">
				<p><span class="heading">Welcome to QCubed!</span></p>

				<p><strong>If you are seeing this, the framework has been successfully installed. Say hi on <a href="http://qcu.be/forum">QCubed Forum</a>, we're here help you!</strong></p>
				
				<ul class="shortcuts">
					<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS__) ?>/codegen.php">Code Generator</a> - to create ORM objects that map to tables in your database.</li>
					<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__) ?>/index.php">View Form Drafts</a> - to view the generated UI scaffolding (after you run the Code Generator).</li>
					<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __EXAMPLES__) ?>/index.php">QCubed Examples</a> - learn QCubed by studying and modifying the example files locally.</li>
					<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS__) ?>/plugin_manager.php">Plugin Manager</a> - to extend QCubed with community-contributed plugins.</li>
					<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __PHP_ASSETS__) ?>/qcubed_unit_tests.php">QCubed Unit Tests</a> - set of tests that QCubed developers use to verify the integrity of the framework. Test dataset required. </li>
				<?php
					$arrInstallationMessages = QInstallationValidator::Validate();
					$strConfigStatus = ($arrInstallationMessages) ? 
						"<span style='color:red;'>" . count($arrInstallationMessages)." problem(s) found. <a href='" . __VIRTUAL_DIRECTORY__ . __DEVTOOLS__ . "/config_checker.php'>Click here</a> to view details.</span>" : 
						"<span style='color:green'>all OK.</span>";	
				?>
					<li>QCubed Configuration Checker - monitors the health of your installation. Current status:  <?php _p($strConfigStatus, false) ?></li>
				</ul>

				<div class="code"><?php if (!QApplication::IsRemoteAdminSession()) QApplication::VarDump(); ?></div>
			</div>
			<div id="footer">
				<div id="footerLeft"><a href="http://qcu.be/"><img src="<?php _p(__VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__ . '/qcubed_logo_footer.png'); ?>" alt="QCubed - A Rapid Prototyping PHP5 Framework" /></a></div>
				<div id="footerRight">
					<div><span class="footerSmall">For more information, please visit the QCubed website at <a href="http://www.qcu.be/" class="footerLink">http://www.qcu.be/</a></span></div>
					<div><span class="footerSmall">Questions, comments, or issues can be discussed at the <a href="http://qcu.be/forum" class="footerLink">Examples Site Forum</a></span></div>
				</div>
			</div>
		</div>
	</body>
</html>