<?php
	// The Server Instance constant is used to help ease web applications with multiple environments.
	// Feel free to use, change or ignore.
	define('SERVER_INSTANCE', 'dev');

	switch (SERVER_INSTANCE) {
		case 'dev':
		case 'test':
		case 'stage':
		case 'prod':
			define('ALLOW_REMOTE_ADMIN', false);
			define ('__DOCROOT__', 'C:/xampp/xampp/htdocs/qcubed');
			define ('__VIRTUAL_DIRECTORY__', '');
			define ('__SUBDIRECTORY__', '');
			define ('__INCLUDES__', __DOCROOT__ . __SUBDIRECTORY__ . '/includes');
            define ('__CONFIGURATION__', __INCLUDES__ . '/configuration');
			define ('__URL_REWRITE__', 'none');
			define ('__QCUBED__', __INCLUDES__ . '/qcubed');
			define ('__QCUBED_CORE__', __INCLUDES__ . '/qcubed/_core');
            define ('__MODEL__', __INCLUDES__ . '/model' );
			define ('__MODEL_GEN__', __MODEL__ . '/generated' );
			define ('__META_CONTROLS__', __INCLUDES__ . '/meta_controls' );
			define ('__META_CONTROLS_GEN__', __META_CONTROLS__ . '/generated' );
			define ('__FORM_DRAFTS__', __SUBDIRECTORY__ . '/drafts');
			define ('__PANEL_DRAFTS__', __SUBDIRECTORY__ . '/drafts/panels');
			define ('__EXAMPLES__', __SUBDIRECTORY__ . '/examples');
			define ('__JS_ASSETS__', __SUBDIRECTORY__ . '/assets/_core/js');
			define ('__CSS_ASSETS__', __SUBDIRECTORY__ . '/assets/_core/css');
			define ('__IMAGE_ASSETS__', __SUBDIRECTORY__ . '/assets/_core/images');
			define ('__PHP_ASSETS__', __SUBDIRECTORY__ . '/assets/_core/php');
			define ('__DEVTOOLS__', __PHP_ASSETS__ . '/_devtools');

			define('DB_CONNECTION_1', serialize(array(
				'adapter' => 'MySqli5',
				'server' => 'localhost',
				'port' => null,
				'database' => 'qcubed',
				'username' => 'root',
				'password' => '',
				'profiling' => false)));

			if ((function_exists('date_default_timezone_set')) && (!ini_get('date.timezone')))
				date_default_timezone_set('America/Los_Angeles');

			define('ERROR_PAGE_PATH', __PHP_ASSETS__ . '/error_page.php');
			define('ERROR_LOG_PATH', __INCLUDES__ . '/error_log');
			break;
	}
?>