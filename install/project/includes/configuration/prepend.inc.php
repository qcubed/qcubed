<?php
	if (!defined('__PREPEND_INCLUDED__')) {
		// Ensure prepend.inc is only executed once
		define('__PREPEND_INCLUDED__', 1);


		///////////////////////////////////
		// Define Server-specific constants
		///////////////////////////////////	
		/*
		 * This assumes that the configuration include file is in the same directory
		 * as this prepend include file.  For security reasons, you can feel free
		 * to move the configuration file anywhere you want.  But be sure to provide
		 * a relative or absolute path to the file.
		 */
		if (file_exists(dirname(__FILE__) . '/configuration.inc.php')) {
			require(dirname(__FILE__) . '/configuration.inc.php');
		}
		else {
			// The minimal constants set to work
			define ('__DOCROOT__', dirname(__FILE__) . '/../../..');
			define ('__PROJECT__', dirname(__FILE__) . '/../..');
			define ('__INCLUDES__', dirname(__FILE__) . '/..');
			define ('__QCUBED__', __INCLUDES__); // needs to be reconfigured
			define ('__PLUGINS__', __PROJECT__ . '/generated/plugins');
			define ('__QCUBED_CORE__', __DOCROOT__ . '/vendor/qcubed/qcubed/includes');
			define ('__APP_INCLUDES__', __INCLUDES__ . '/app_includes');
			define ('__MODEL__', __INCLUDES__ . '/model' );
			define ('__MODEL_CONNECTOR__', __INCLUDES__ . '/meta_controls');
			define ('__META_CONTROLS_GEN__', __PROJECT__ . '/generated/meta_base');
			define ('__MODEL_GEN__', __PROJECT__ . '/generated/model_base' );
		}


		//////////////////////////////
		// Include the QCubed Framework
		//////////////////////////////
		require(__QCUBED_CORE__ . '/qcubed.inc.php');

		///////////////////////////////
		// Define the Application Class
		///////////////////////////////
		/**
		 * The Application class is an abstract class that statically provides
		 * information and global utilities for the entire web application.
		 *
		 * Custom constants for this webapp, as well as global variables and global
		 * methods should be declared in this abstract class (declared statically).
		 *
		 * This Application class should extend from the ApplicationBase class in
		 * the framework.
		 */
		abstract class QApplication extends QApplicationBase {
			/**
			 * This is called by the PHP5 Autoloader.  This method overrides the
			 * one in ApplicationBase.
			 *
			 * @return void
			 */
			public static function Autoload($strClassName) {
				// First use the QCubed Autoloader
				if (!parent::Autoload($strClassName)) {
					// TODO: Run any custom autoloading functionality (if any) here...
				}
			}

			////////////////////////////
			// QApplication Customizations (e.g. EncodingType, etc.)
			////////////////////////////
			// public static $EncodingType = 'ISO-8859-1';

			////////////////////////////
			// Additional Static Methods
			////////////////////////////
			// TODO: Define any other custom global WebApplication functions (if any) here...
		}

        if (file_exists (__QCUBED_CORE__ . '/../../../autoload.php')) {
            require __QCUBED_CORE__ . '/../../../autoload.php'; // Add the Composer autoloader if using Composer
        }

        // Register the autoloader, making sure we go BEFORE the composer autoloader
        spl_autoload_register(array('QApplication', 'Autoload'), true, true);


		//////////////////////////
		// Custom Global Functions
		//////////////////////////	
		// TODO: Define any custom global functions (if any) here...


		////////////////
		// Include Files
		////////////////
		// TODO: Include any other include files (if any) here...


		///////////////////////
		// Setup Error Handling
		///////////////////////
		/*
		 * Set Error/Exception Handling to the default
		 * QCubed HandleError and HandlException functions
		 * (Only in non CLI mode)
		 *
		 * Feel free to change, if needed, to your own
		 * custom error handling script(s).
		 */
		if (array_key_exists('SERVER_PROTOCOL', $_SERVER)) {
			set_error_handler('QcubedHandleError', error_reporting());
			set_exception_handler('QcubedHandleException');
			register_shutdown_function('QCubedShutdown');
		}


		////////////////////////////////////////////////
		// Initialize the Application and DB Connections
		////////////////////////////////////////////////
		QApplication::Initialize();
		QApplication::InitializeDatabaseConnections();
		// Check if we are going to override PHP's default session handler
		QApplication::SessionOverride();


		/////////////////////////////
		// Start Session Handler (if required)
		/////////////////////////////
		session_start();


		//////////////////////////////////////////////
		// Setup Internationalization and Localization (if applicable)
		// Note, this is where you would implement code to do Language Setting discovery, as well, for example:
		// * Checking against $_GET['language_code']
		// * checking against session (example provided below)
		// * Checking the URL
		// * etc.
		// TODO: options to do this are left to the developer
		//////////////////////////////////////////////
		if (isset($_SESSION)) {
			if (array_key_exists('country_code', $_SESSION))
				QApplication::$CountryCode = $_SESSION['country_code'];
			if (array_key_exists('language_code', $_SESSION))
				QApplication::$LanguageCode = $_SESSION['language_code'];
		}

		// Initialize I18n if QApplication::$LanguageCode is set
		if (QApplication::$LanguageCode)
			QI18n::Initialize();
		else {
			// QApplication::$CountryCode = 'us';
			// QApplication::$LanguageCode = 'en';
			// QI18n::Initialize();
			//
			// QDateTime::$DefaultFormat = 'M/D/YY h:mm z';
			// QDateTime::$DefaultDateOnlyFormat = 'M/D/YY';
			// QDateTime::$DefaultTimeFormat = 'h:mm z';

		}
		require(__APP_INCLUDES__ . '/app_includes.inc.php');
	}