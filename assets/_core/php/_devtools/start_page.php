<?php
	require_once('../qcubed.inc.php');
	$strPageName = 'Start Page';
	require('../examples/includes/header.inc.php'); ?>
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

<?php require('../examples/includes/footer.inc.php'); ?>