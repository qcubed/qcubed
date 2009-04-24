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
			define ('__DOCROOT__', '/var/www/qcubed/wwwroot');
			define ('__VIRTUAL_DIRECTORY__', '');
			define ('__SUBDIRECTORY__', '');
            define ('__APPLICATION__', __DOCROOT__ . __SUBDIRECTORY__ . '/application' );
            define ('__MODEL__', __APPLICATION__ . '/model' );
            define ('__INCLUDES__', __APPLICATION__ . '/connfiguration');
			define ('__URL_REWRITE__', 'none');
			define ('__QCUBED__', __APPLICATION__ . '/qcodo');
			define ('__QCUBED_CORE__', __APPLICATION__ . '/qcodo/_core');
			define ('__DATA_CLASSES__', __MODEL__ . '/data_classes');
			define ('__DATA_META_CONTROLS__', __MODEL__ . '/meta_controls');
			define ('__DATAGEN_CLASSES__', __MODEL__ . '/base_generated/base_data_classes');
			define ('__DATAGEN_META_CONTROLS__', __MODEL__ . '/base_generated/base_meta_controls');
    		define ('__DEVTOOLS__', __DOCROOT__ . __SUBDIRECTORY__ . '/_devtools');
			define ('__FORM_DRAFTS__', __APPLICATION__. '/drafts');
			define ('__PANEL_DRAFTS__', __APPLICATION__ . '/drafts/dashboard');
			define ('__EXAMPLES__', __DOCROOT__ . __SUBDIRECTORY__ . '/examples');
            define ('__JS_ASSETS__', __DOCROOT__ . __SUBDIRECTORY__ . '/assets/_core/js');
			define ('__CSS_ASSETS__', __DOCROOT__ . __SUBDIRECTORY__ . '/assets/_core/css');
			define ('__IMAGE_ASSETS__', __DOCROOT__ . __SUBDIRECTORY__ . '/assets/_core/images');
			define ('__PHP_ASSETS__', __DOCROOT__ . __SUBDIRECTORY__ . '/assets/_core/php');

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