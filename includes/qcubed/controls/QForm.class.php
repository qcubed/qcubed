<?php
	/**
	 * QForm is defined in this file
	 *
	 * @package Controls
	 * @filesource
	 */

	/**
	 * QForm is one of the most important parts of QCubed - it is what ultimately creates the web page
	 *
	 * QForm is based on QFormBase class and is supposed to be used against QForm. The reason for such
	 * recommendation is - QFormBase is a lengthy, confusing and <b>very</b> important file. If you want
	 * to modify any behavior or QFormBase, please override the corresponding method here. If you want to
	 * add a new feature, add it as a method in this class.
	 *
	 * @package Controls
	 * @author Qcubed
	 * @copyright
	 * @version 2011
	 * @access public
	 */
	abstract class QForm extends QFormBase {
		///////////////////////////
		// Form Preferences
		///////////////////////////

		/**
		 * If you wish to encrypt the resulting formstate data to be put on the form (via
		 * QCryptography), please specify a key to use.  The default cipher and encrypt mode
		 * on QCryptography will be used, and because the resulting encrypted data will be
		 * sent via HTTP POST, it will be Base64 encoded.
		 *
		 * @var string EncryptionKey the key to use, or NULL if no encryption is required
		 */
		public static $EncryptionKey = null;

		/**
		 * The QFormStateHandler to use to handle the actual serialized form.
		 * Please refer configuration.inc.php file (in includes/configuration directory) to learn more
		 * about what __FORM_STATE_HANDLER__ does. Though you can change it here,
		 * try to change the __FORM_STATE_HANDLER__ in the configuration file alone.
		 *
		 * It overrides the default value in the QFormBase Class file
		 *
		 * @var string FormStateHandler the classname of the FormState handler to use
		 */
		public static $FormStateHandler = __FORM_STATE_HANDLER__;

		// TODO: Improve explanation here.
		/**
		 * These are the list of core QForm JavaScript files, or JavaScript files needed by
		 * a QControl, which QForm should IGNORE trying to load during a RenderBegin() or RenderAjax() call.
		 *
		 * It is used in the ProcessJavaScriptList function in the QFormBase class.
		 * @var array
		 */
		protected $strIgnoreJavaScriptFileArray = array();
		/* protected $strIgnoreJavaScriptFileArray = array(
			'date_time_picker.js',
			'treenav.js'); */

		/**
		 * This should be very rarely used.
		 *
		 * This mechanism acts similarly to the strIgnoreJavascriptFileArray, except it applies to StyleSheets.
		 * However, any QControl that specifies a StyleSheet file to include is MEANT to have that property
		 * be modified / customized.
		 *
		 * Therefore, there should be little to no need for this attribute.  However, it is here anyway, just in case.
		 * Also note that QFormBase does not implement its feature. It is there if you (a developer building
		 * your application using QCubed) want to use it.
		 *
		 * @var array
		 */
		protected $strIgnoreStyleSheetFileArray = array();
		// protected $strIgnoreStyleSheetFileArray = array('datagrid.css', 'calendar.css', 'textbox.css', 'listbox.css');
	}
?>
