<?php
	require_once('../qcubed.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php _p(QApplication::$EncodingType); ?>" />
		<title>QCubed Development Framework - Start Page</title>
		<link rel="stylesheet" type="text/css" href="<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ . '/styles.css'); ?>"></link>
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
				<p><span class="heading">It worked!</span></p>

				<p><strong>If you are seeing this, then it means that the framework has been successfully installed.</strong></p>
				
				<p>Make sure your database connection properties are up to date, and then you can add tables to your database, and go to any of the following webpages:</p>
				
				<ul>
					<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS__) ?>/codegen.php"><?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS__) ?>/codegen.php</a> - to code generate your tables</li>
					<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __PHP_ASSETS__) ?>/qcubed_unit_tests.php"><?php _p(__VIRTUAL_DIRECTORY__ . __PHP_ASSETS__) ?>/qcubed_unit_tests.php</a> - unit tests for QCubed</li>
					<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__) ?>/index.php"><?php _p(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__) ?></a> - to view the generated Form Drafts of your database</li>
					<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __EXAMPLES__) ?>/index.php"><?php _p(__VIRTUAL_DIRECTORY__ . __EXAMPLES__) ?></a> - to run the QCubed Examples Site locally</li>
					<li><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS__) ?>/plugin_manager.php"><?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS__) ?>/plugin_manager.php</a> - to manage installed plugins</li>
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