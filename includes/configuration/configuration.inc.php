<?php
if (!defined('SERVER_INSTANCE')) {
	// The Server Instance constant is used to help ease web applications with multiple environments.
	// Feel free to use, change or ignore.
	define('SERVER_INSTANCE', 'dev');

	switch (SERVER_INSTANCE) {
		case 'dev':
		case 'test':
		case 'stage':
		case 'prod':
			/* Constant to allow/disallow remote access to the admin pages
			 * e.g. the generated form_drafts, codegen, or any other script that calls QApplication::CheckRemoteAdmin()
			 *
			 * If set to TRUE, anyone can access those pages.
			 * If set to FALSE, only localhost can access those pages.
			 * If set to an IP address (e.g. "12.34.56.78"), then only localhost and 12.34.56.78 can access those pages.
			 * If set to a comma-separate list of IP addresses, then localhoost and any of those IP addresses can access those pages.
			 *
			 * Of course, you can also feel free to remove QApplication::CheckRemoteAdmin() call on any of these pages,
			 * which will completely ignore ALLOW_REMOTE_ADMIN altogether.
			 */
			define('ALLOW_REMOTE_ADMIN', false);


			/* Constants for Document Root (and Virtual Directories / Subfoldering)
			 *
			 * IMPORTANT NOTE FOR WINDOWS USERS
			 * Please note that all paths should use standard "forward" slashes instead of "backslashes".
			 * So windows paths would look like "c:/wwwroot" instead of "c:\wwwroot".
			 *
			 * Please specify the "Document Root" here.  This is the top level filepath for your web application.
			 * If you are on a installation that uses virtual directories, then you must specify that here, as well.
			 *
			 * For example, if your example web application where http://my.domain.com/index.php points to
			 * /home/web/htdocs/index.php, then you must specify:
			 *		__DOCROOT__ is defined as '/home/web/htdocs'
			 *		(note the leading slash and no ending slash)
			 * On Windows, if you have http://my.domain.com/index.php pointing to c:\webroot\files\index.php, then:
			 *		__DOCROOT__ is defined as 'c:/webroot/files'
			 *		(again, note the leading c:/ and no ending slash)
			 *
			 * Next, if you are using Virtual Directories, where http://not.my.domain.com/~my_user/index.php
			 * (for example) points to /home/my_user/public_html/index.php, then:
			 *		__DOCROOT__ is defined as '/home/my_user/public_html'
			 *		__VIRTUAL_DIRECTORY__ is defined as '/~my_user'
			 *
			 * Finally, if you have installed QCubed within a SubDirectory of the Document Root, so for example
			 * the QCubed "index.php" page is accessible at http://my.domain.com/frameworks/qcubed/index.php, then:
			 *		__SUBDIRECTORY__ is defined as '/frameworks/qcubed'
			 *		(again, note the leading and no ending slash)
			 *
			 * In combination with Virtual Directories, if you (for example) have the QCubed "index.php" page
			 * accessible at http://not.my.domain.com/~my_user/qcubed/index.php, and the index.php resides at
			 * c:\users\my_user\public_html\index.php, then:
			 *		__DOCROOT__ is defined as 'c:/users/my_user/public_html'
			 *		__VIRTUAL_DIRECTORY__ is defined as '/~my_user'
			 *		__SUBDIRECTORY__ is defined as '/qcubed'
			 *      /var/www/qcubed/wwwroot
			 */
			define ('__DOCROOT__', 'C:/xampp/xampp/htdocs');
			define ('__VIRTUAL_DIRECTORY__', '');
			define ('__SUBDIRECTORY__', '/qcubed2');

			/*
			 * These definitions will hardly change, but you may change them based on your setup
			 */
			define ('__INCLUDES__', __DOCROOT__ . __SUBDIRECTORY__ . '/includes');
			define ('__CONFIGURATION__', __INCLUDES__ . '/configuration');

			/*
			 * If you are using Apache-based mod_rewrite to perform URL rewrites, please specify "apache" here.
			 * Otherwise, specify as "none"
			 */
			define ('__URL_REWRITE__', 'none');

			/* Absolute File Paths for Internal Directories
			 *
			 * Please specify the absolute file path for all the following directories in your QCubed-based web
			 * application.
			 *
			 * Note that all paths must start with a slash or 'x:\' (for windows users) and must have
			 * no ending slashes.  (We take advantage of the __INCLUDES__ to help simplify this section.
			 * But note that this is NOT required.  These directories can also reside outside of the
			 * Document Root altogether.  So feel free to use or not use the __DOCROOT__ and __INCLUDES__
			 * constants as you wish/need in defining your other directory constants.)
			 */

			// The QCubed Directories
			// Includes subdirectories for QCubed Customizations in CodeGen and QForms, i18n PO files, QCache storage, etc.
			// Also includes the _core subdirectory for the QCubed Core
			define ('__QCUBED__', __INCLUDES__ . '/qcubed');
			define ('__PLUGINS__', __QCUBED__ . '/plugins');

			define ('__CACHE__', __INCLUDES__ . '/tmp/cache');

			// The QCubed Core
			define ('__QCUBED_CORE__', __INCLUDES__ . '/qcubed/_core');

			// Destination for Code Generated class files
			define ('__MODEL__', __INCLUDES__ . '/model' );
			define ('__MODEL_GEN__', __MODEL__ . '/generated' );
			define ('__META_CONTROLS__', __INCLUDES__ . '/meta_controls' );
			define ('__META_CONTROLS_GEN__', __META_CONTROLS__ . '/generated' );

			/* Relative File Paths for Web Accessible Directories
			 *
			 * Please specify the file path RELATIVE FROM THE DOCROOT for all the following web-accessible directories
			 * in your QCubed-based web application.
			 *
			 * For some directories (e.g. the Examples site), if you are no longer using it, you STILL need to
			 * have the constant defined.  But feel free to define the directory constant as blank (e.g. '') or null.
			 *
			 * Note that constants must have a leading slash and no ending slash, and they MUST reside within
			 * the Document Root.
			 *
			 * (We take advantage of the __SUBDIRECTORY__ constant defined above to help simplify this section.
			 * Note that this is NOT required.  Feel free to use or ignore.)
			 */

			// Destination for generated form drafts and panel drafts
			define ('__FORM_DRAFTS__', __SUBDIRECTORY__ . '/drafts');
			define ('__PANEL_DRAFTS__', __SUBDIRECTORY__ . '/drafts/panels');
			define ('__FORMBASE_CLASSES__', __INCLUDES__ . '/formbase_classes_generated');

			// Location of QCubed-specific Web Assets (JavaScripts, CSS, Images, and PHP Pages/Popups)
			define ('__JS_ASSETS__', __SUBDIRECTORY__ . '/assets/_core/js');
			define ('__CSS_ASSETS__', __SUBDIRECTORY__ . '/assets/_core/css');
			define ('__IMAGE_ASSETS__', __SUBDIRECTORY__ . '/assets/_core/images');
			define ('__PHP_ASSETS__', __SUBDIRECTORY__ . '/assets/_core/php');
			define ('__PLUGIN_ASSETS__', __SUBDIRECTORY__ . '/assets/plugins');

			// jQuery folder location, relative to __JS_ASSETS__
			define ('__JQUERY_BASE__',  'jquery/jquery.min.js');
			define ('__JQUERY_EFFECTS__',   'jquery/jquery-ui.custom.min.js');
			define ('__JQUERY_CSS__', 'jquery-ui-themes/ui-lightness/jquery-ui.custom.css');
				
			// Location of the QCubed-specific web-based development tools, like codegen.php
			define ('__DEVTOOLS__', __PHP_ASSETS__ . '/_devtools');

			// Location of the Examples site
			define ('__EXAMPLES__', __PHP_ASSETS__ . '/examples');

			// Location of .po translation files
			define ('__QI18N_PO_PATH__', __QCUBED__ . '/i18n');
			
			/* Database Connection SerialArrays
			 *
			 * Note that all Database Connections are defined as constant serialized arrays.  QCubed supports
			 * connections to an unlimited number of different database sources.  Each database source, referenced by
			 * a numeric index, will have its DB Connection SerialArray stored in a DB_CONNECTION_# constant
			 * (where # is the numeric index).
			 *
			 * The SerialArray can have the following keys:
			 * "adapter" (Required), options are:
			 *		MySql (MySQL v4.x, using the old mysql extension)
			 *		MySqli (MySQL v4.x, using the new mysqli extension)
			 *		MySqli5 (MySQL v5.x, using the new mysqli extension)
			 *		SqlServer (Microsoft SQL Server)
			 *		SqlServer2005 (Microsoft SQL Server 2005/2008 using new sqlsrv extension, Windows only)
			 *		PostgreSql (PostgreSQL)
			 * "server" (Required) is the db server's name or IP address, e.g. localhost, 10.1.1.5, etc.
			 * "port" is the port number - default is the server-specified default
			 * "database", "username", "password" should be self explanatory
			 * "dateformat" is an optional value for the desired db date format, the default value is
			 *		'YYYY-MM-DD hhhh:mm:ss' if not defined or null
			 * "profiling" is true or false, defining whether or not you want to enable DB profiling - default is false
			 *		NOTE: Profiling should only be enabled when you are actively wanting to profile a
			 *		specific PHP script or scripts.  Because of SIGNIFICANT performance degradation,
			 *		it should otherwise always be OFF.
			 * "ScriptPath": you can have CodeGen virtually add additional FKs, even though they are
			 * 		not defined as a DB constraint in the database, by using a script to define what
			 * 		those constraints are.  The path of the script can be defined here. - default is blank or none
			 * Note: any option not used or set to blank will result in using the default value for that option
			 */
			define('DB_CONNECTION_1', serialize(array(
				'adapter' => 'MySqli5',
				'server' => 'localhost',
				'port' => null,
				'database' => 'qcubed',
				'username' => 'root',
				'password' => '',
				'profiling' => false)));

			// Additional Database Connection Strings can be defined here (e.g. for connection #2, #3, #4, #5, etc.)
			//			define('DB_CONNECTION_2', serialize(array('adapter'=>'SqlServer', 'server'=>'localhost', 'port'=>null, 'database'=>'qcubed', 'username'=>'root', 'password'=>'', 'profiling'=>false)));
			//			define('DB_CONNECTION_3', serialize(array('adapter'=>'MySqli', 'server'=>'localhost', 'port'=>null, 'database'=>'qcubed', 'username'=>'root', 'password'=>'', 'profiling'=>false)));
			//			define('DB_CONNECTION_4', serialize(array('adapter'=>'MySql', 'server'=>'localhost', 'port'=>null, 'database'=>'qcubed', 'username'=>'root', 'password'=>'', 'profiling'=>false)));
			//			define('DB_CONNECTION_5', serialize(array('adapter'=>'PostgreSql', 'server'=>'localhost', 'port'=>null, 'database'=>'qcubed', 'username'=>'root', 'password'=>'', 'profiling'=>false)));


			// (For PHP > v5.1) Setup the default timezone (if not already specified in php.ini)
			if ((function_exists('date_default_timezone_set')) && (!ini_get('date.timezone')))
				date_default_timezone_set('America/Los_Angeles');


			/* Form State Handler. Determines which class is used to serialize the form in-between Ajax callbacks.
			*
			* Possible values are:
			* "QFormStateHandler": This is the "standard" FormState handler, storing the base64 encoded session data
			*	(and if requested by QForm, encrypted) as a hidden form variable on the page, itself.
			*
			* "QSessionFormStateHandler": Simple Session-based FormState handler.  Uses PHP Sessions so it's very straightforward
			*	and simple, utilizing the session handling and cleanup functionality in PHP, itself.
			*	The downside is that for long running sessions, each individual session file can get
			*	very, very large, storing all hte various formstate data.  Eventually (if individual
			*	session files are larger than 10MB), you can theoretically observe a geometrical
			*	degradation of performance.
			*
			* "QFileFormStateHandler": This will store the formstate in a pre-specified directory (__FILE_FORM_STATE_HANDLER_PATH__)
			*	on the file system. This offers significant speed advantage over PHP SESSION because EACH
			*	form state is saved in its own file, and only the form state that is needed for loading will
			*	be accessed (as opposed to with session, ALL the form states are loaded into memory
			*	every time).
			*	The downside is that because it doesn't utilize PHP's session management subsystem,
			*	this class must take care of its own garbage collection/deleting of old/outdated
			*	formstate files.
			*/
			define('__FORM_STATE_HANDLER__', 'QSessionFormStateHandler');
				
			// If using the QFileFormStateHandler, specify the path where QCubed will save the session state files (has to be writeable!)
			define('__FILE_FORM_STATE_HANDLER_PATH__', __INCLUDES__ . '/tmp');


			// Define the Filepath for the error page (path MUST be relative from the DOCROOT)
			define('ERROR_PAGE_PATH', __PHP_ASSETS__ . '/error_page.php');

			// Define the Filepath for any logged errors
			define('ERROR_LOG_PATH', __INCLUDES__ . '/error_log');

			// To Log ALL errors that have occurred, set flag to true
			//			define('ERROR_LOG_FLAG', true);

			// To enable the display of "Friendly" error pages and messages, define them here (path MUST be relative from the DOCROOT)
			//			define('ERROR_FRIENDLY_PAGE_PATH', __PHP_ASSETS__ . '/friendly_error_page.php');
			//			define('ERROR_FRIENDLY_AJAX_MESSAGE', 'Oops!  An error has occurred.\r\n\r\nThe error was logged, and we will take a look into this right away.');

			break;
	}
}
?>
