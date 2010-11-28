<?php
	/* Unless otherwise specified, all files in the QCubed Development Framework
	 * are under the following copyright and licensing policies:
	 * 
	 * QCubed Development Framework for PHP
	 * http://www.qcu.be
	 * 
	 * The QCubed Development Framework is distributed by the QCubed Project
	 * under the terms of The MIT License.  More information can be found at
	 * http://www.opensource.org/licenses/mit-license.php
	 * 
	 * Copyright (c) 2001 - 2009, Quasidea Development, LLC; QCubed Project
	 * 
	 * Permission is hereby granted, free of charge, to any person obtaining a copy of
	 * this software and associated documentation files (the "Software"), to deal in
	 * the Software without restriction, including without limitation the rights to
	 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
	 * of the Software, and to permit persons to whom the Software is furnished to do
	 * so, subject to the following conditions:
	 * 
	 * The above copyright notice and this permission notice shall be included in all
	 * copies or substantial portions of the Software.
	 * 
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	 * SOFTWARE.
	 */

	// Versioning Information
	define('QCUBED_VERSION_NUMBER_ONLY', '2.0.2');
	define('QCUBED_VERSION', QCUBED_VERSION_NUMBER_ONLY . ' Development Release (QCubed ' . QCUBED_VERSION_NUMBER_ONLY . ')');
	
	define('__JQUERY_CORE_VERSION__', '1.4.4');
	define('__JQUERY_UI_VERSION__', '1.8.6');
	

	// Preload Required Framework Classes
	require(__QCUBED_CORE__ . '/framework/QBaseClass.class.php');
	require(__QCUBED_CORE__ . '/framework/QExceptions.class.php');
	require(__QCUBED_CORE__ . '/framework/QType.class.php');
	require(__QCUBED_CORE__ . '/framework/QApplicationBase.class.php');

	// Setup the Error Handler
	require(__QCUBED_CORE__ . '/error.inc.php');
	
	// Start Output Buffering	
	function __ob_callback($strBuffer) {
		return QApplication::OutputPage($strBuffer);
	}
	ob_start('__ob_callback');

	// Preload Other Framework Classes
	require(__QCUBED_CORE__ . '/framework/QDatabaseBase.class.php');
	require(__QCUBED_CORE__ . '/database/QPdoDatabase.class.php');
	if (version_compare(PHP_VERSION, '5.2.0', '<'))
		// Use the Legacy (Pre-5.2.0) QDateTime class
		require(__QCUBED_CORE__ . '/framework/QDateTime.legacy.class.php');
	else
		// Use the New QDateTime class (which extends PHP DateTime)
		require(__QCUBED_CORE__ . '/framework/QDateTime.class.php');

	// Define Classes to be Preloaded on QApplication::Initialize()
	QApplicationBase::$PreloadedClassFile['_enumerations'] = __QCUBED_CORE__ . '/base_controls/_enumerations.inc.php';
	QApplicationBase::$PreloadedClassFile['qcontrolbase'] = __QCUBED_CORE__ . '/base_controls/QControlBase.class.php';
	QApplicationBase::$PreloadedClassFile['qcontrol'] = __QCUBED__ . '/controls/QControl.class.php';
	QApplicationBase::$PreloadedClassFile['qformbase'] = __QCUBED_CORE__ . '/base_controls/QFormBase.class.php';
	QApplicationBase::$PreloadedClassFile['qform'] = __QCUBED__ . '/controls/QForm.class.php';
	QApplicationBase::$PreloadedClassFile['_actions'] = __QCUBED_CORE__ . '/base_controls/_actions.inc.php';
	QApplicationBase::$PreloadedClassFile['_effect_actions'] = __QCUBED_CORE__ . '/base_controls/_effect_actions.inc.php';
	QApplicationBase::$PreloadedClassFile['_events'] = __QCUBED_CORE__ . '/base_controls/_events.inc.php';
	QApplicationBase::$PreloadedClassFile['qq'] = __QCUBED_CORE__ . '/framework/QQuery.class.php';

	// Define ClassFile Locations for FormState Handlers
	QApplicationBase::$ClassFile['qformstatehandler'] = __QCUBED_CORE__ . '/qform_state_handlers/QFormStateHandler.class.php';
	QApplicationBase::$ClassFile['qsessionformstatehandler'] = __QCUBED_CORE__ . '/qform_state_handlers/QSessionFormStateHandler.class.php';
	QApplicationBase::$ClassFile['qfileformstatehandler'] = __QCUBED_CORE__ . '/qform_state_handlers/QFileFormStateHandler.class.php';

	// Define ClassFile Locations for Framework Classes
	QApplicationBase::$ClassFile['qrssfeed'] = __QCUBED_CORE__ . '/framework/QRssFeed.class.php';
	QApplicationBase::$ClassFile['qrssimage'] = __QCUBED_CORE__ . '/framework/QRssFeed.class.php';
	QApplicationBase::$ClassFile['qrsscategory'] = __QCUBED_CORE__ . '/framework/QRssFeed.class.php';
	QApplicationBase::$ClassFile['qrssitem'] = __QCUBED_CORE__ . '/framework/QRssFeed.class.php';
	QApplicationBase::$ClassFile['qemailserver'] = __QCUBED_CORE__ . '/framework/QEmailServer.class.php';
	QApplicationBase::$ClassFile['qemailmessage'] = __QCUBED_CORE__ . '/framework/QEmailServer.class.php';
	QApplicationBase::$ClassFile['qmimetype'] = __QCUBED_CORE__ . '/framework/QMimeType.class.php';
	QApplicationBase::$ClassFile['qdatetime'] = __QCUBED_CORE__ . '/framework/QDateTime.class.php';
	QApplicationBase::$ClassFile['qstring'] = __QCUBED_CORE__ . '/framework/QString.class.php';
	QApplicationBase::$ClassFile['qstack'] = __QCUBED_CORE__ . '/framework/QStack.class.php';
	QApplicationBase::$ClassFile['qcryptography'] = __QCUBED_CORE__ . '/framework/QCryptography.class.php';
	QApplicationBase::$ClassFile['qsoapservice'] = __QCUBED_CORE__ . '/framework/QSoapService.class.php';
	QApplicationBase::$ClassFile['qi18n'] = __QCUBED_CORE__ . '/framework/QI18n.class.php';
	QApplicationBase::$ClassFile['qtranslationbase'] = __QCUBED_CORE__ . '/framework/QTranslationBase.class.php';
	QApplicationBase::$ClassFile['qtranslationpoparser'] = __QCUBED_CORE__ . '/framework/QTranslationPoParser.class.php';
	QApplicationBase::$ClassFile['qqn'] = __MODEL_GEN__ . '/QQN.class.php';
	QApplicationBase::$ClassFile['qqueryexpansion'] = __QCUBED_CORE__ . '/framework/QQueryExpansion.class.php';
	QApplicationBase::$ClassFile['qconvertnotation'] = __QCUBED__ . '/codegen/QConvertNotation.class.php';
	QApplicationBase::$ClassFile['qfolder'] = __QCUBED_CORE__ . '/framework/QFolder.class.php';
	QApplicationBase::$ClassFile['qfile'] = __QCUBED_CORE__ . '/framework/QFile.class.php';
	QApplicationBase::$ClassFile['qarchive'] = __QCUBED_CORE__ . '/framework/QArchive.class.php';
	QApplicationBase::$ClassFile['qlexer'] = __QCUBED_CORE__ . '/framework/QLexer.class.php';
	QApplicationBase::$ClassFile['qregex'] = __QCUBED_CORE__ . '/framework/QRegex.class.php';
	QApplicationBase::$ClassFile['qtimer'] = __QCUBED_CORE__ . '/framework/QTimer.class.php';

	QApplicationBase::$ClassFile['qinstallationvalidator'] = __QCUBED_CORE__ . '/framework/QInstallationValidator.class.php';
	
	QApplicationBase::$ClassFile['qplugin'] = __QCUBED_CORE__ . '/framework/QPluginInterface.class.php';
	QApplicationBase::$ClassFile['qpluginconfigparser'] = __QCUBED_CORE__ . '/framework/QPluginConfigParser.class.php';
	QApplicationBase::$ClassFile['qplugininstallerbase'] = __QCUBED_CORE__ . '/framework/QPluginInstallerBase.class.php';
	QApplicationBase::$ClassFile['qplugininstaller'] = __QCUBED_CORE__ . '/framework/QPluginInstaller.class.php';
	QApplicationBase::$ClassFile['qpluginuninstaller'] = __QCUBED_CORE__ . '/framework/QPluginUninstaller.class.php';
	QApplicationBase::$ClassFile['qpluginuninstaller'] = __QCUBED_CORE__ . '/framework/QPluginUninstaller.class.php';

	QApplicationBase::$ClassFile['qcache'] = __QCUBED_CORE__ . '/framework/QCache.class.php';
	QApplicationBase::$ClassFile['qdatetimespan'] = __QCUBED_CORE__ . '/framework/QDateTimeSpan.class.php';

	// Define ClassFile Locations for Qform Classes
	QApplicationBase::$ClassFile['qfontfamily'] = __QCUBED_CORE__ . '/base_controls/QFontFamily.class.php';

	QApplicationBase::$ClassFile['qcalendar'] = __QCUBED_CORE__ . '/base_controls/QCalendar.class.php';
	QApplicationBase::$ClassFile['qdatetimepicker'] = __QCUBED_CORE__ . '/base_controls/QDateTimePicker.class.php';
	QApplicationBase::$ClassFile['qdatetimetextbox'] = __QCUBED_CORE__ . '/base_controls/QDateTimeTextBox.class.php';

	QApplicationBase::$ClassFile['qcheckbox'] = __QCUBED_CORE__ . '/base_controls/QCheckBox.class.php';
	QApplicationBase::$ClassFile['qfilecontrol'] = __QCUBED_CORE__ . '/base_controls/QFileControl.class.php';
	QApplicationBase::$ClassFile['qradiobutton'] = __QCUBED_CORE__ . '/base_controls/QRadioButton.class.php';

	QApplicationBase::$ClassFile['qblockcontrol'] = __QCUBED_CORE__ . '/base_controls/QBlockControl.class.php';
	QApplicationBase::$ClassFile['qlabel'] = __QCUBED_CORE__ . '/base_controls/QLabel.class.php';
	QApplicationBase::$ClassFile['qpanel'] = __QCUBED_CORE__ . '/base_controls/QPanel.class.php';
	QApplicationBase::$ClassFile['qcontrolproxy'] = __QCUBED_CORE__ . '/base_controls/QControlProxy.class.php';
	QApplicationBase::$ClassFile['qdialogbox'] = __QCUBED_CORE__ . '/base_controls/QDialogBox.class.php';
		
	QApplicationBase::$ClassFile['qimagebase'] = __QCUBED_CORE__ . '/base_controls/QImageBase.class.php';
	QApplicationBase::$ClassFile['qimagelabelbase'] = __QCUBED_CORE__ . '/base_controls/QImageLabelBase.class.php';
	QApplicationBase::$ClassFile['qimagelabel'] = __QCUBED__ . '/controls/QImageLabel.class.php';
	QApplicationBase::$ClassFile['qimagecontrolbase'] = __QCUBED_CORE__ . '/base_controls/QImageControlBase.class.php';
	QApplicationBase::$ClassFile['qimagecontrol'] = __QCUBED__ . '/controls/QImageControl.class.php';
	QApplicationBase::$ClassFile['qimagerollover'] = __QCUBED_CORE__ . '/base_controls/QImageRollover.class.php';

	QApplicationBase::$ClassFile['qfileasset'] = __QCUBED__ . '/controls/QFileAsset.class.php';
	QApplicationBase::$ClassFile['qimagefileasset'] = __QCUBED__ . '/controls/QImageFileAsset.class.php';
	QApplicationBase::$ClassFile['qfileassetbase'] = __QCUBED_CORE__ . '/base_controls/QFileAssetBase.class.php';
	QApplicationBase::$ClassFile['qfileassetdialog'] = __QCUBED_CORE__ . '/base_controls/QFileAssetDialog.class.php';

	QApplicationBase::$ClassFile['qcontrollabel'] = __QCUBED_CORE__ . '/base_controls/QControlLabel.class.php';

	QApplicationBase::$ClassFile['qactioncontrol'] = __QCUBED_CORE__ . '/base_controls/QActionControl.class.php';
	QApplicationBase::$ClassFile['qbuttonbase'] = __QCUBED_CORE__ . '/base_controls/QButtonBase.class.php';
	QApplicationBase::$ClassFile['qbutton'] = __QCUBED__ . '/controls/QButton.class.php';
	QApplicationBase::$ClassFile['qimagebutton'] = __QCUBED_CORE__ . '/base_controls/QImageButton.class.php';
	QApplicationBase::$ClassFile['qlinkbutton'] = __QCUBED_CORE__ . '/base_controls/QLinkButton.class.php';

	QApplicationBase::$ClassFile['qlistcontrol'] = __QCUBED_CORE__ . '/base_controls/QListControl.class.php';
	QApplicationBase::$ClassFile['qlistitem'] = __QCUBED_CORE__ . '/base_controls/QListItem.class.php';
	QApplicationBase::$ClassFile['qlistboxbase'] = __QCUBED_CORE__ . '/base_controls/QListBoxBase.class.php';
	QApplicationBase::$ClassFile['qlistbox'] = __QCUBED__ . '/controls/QListBox.class.php';
	QApplicationBase::$ClassFile['qlistitemstyle'] = __QCUBED_CORE__ . '/base_controls/QListItemStyle.class.php';
	QApplicationBase::$ClassFile['qcheckboxlist'] = __QCUBED_CORE__ . '/base_controls/QCheckBoxList.class.php';
	QApplicationBase::$ClassFile['qradiobuttonlist'] = __QCUBED_CORE__ . '/base_controls/QRadioButtonList.class.php';
	QApplicationBase::$ClassFile['qtreenav'] = __QCUBED_CORE__ . '/base_controls/QTreeNav.class.php';
	QApplicationBase::$ClassFile['qtreenavitem'] = __QCUBED_CORE__ . '/base_controls/QTreeNavItem.class.php';

	QApplicationBase::$ClassFile['qtextboxbase'] = __QCUBED_CORE__ . '/base_controls/QTextBoxBase.class.php';
	QApplicationBase::$ClassFile['qtextbox'] = __QCUBED__ . '/controls/QTextBox.class.php';
	QApplicationBase::$ClassFile['qfloattextbox'] = __QCUBED_CORE__ . '/base_controls/QFloatTextBox.class.php';
	QApplicationBase::$ClassFile['qintegertextbox'] = __QCUBED_CORE__ . '/base_controls/QIntegerTextBox.class.php';
	QApplicationBase::$ClassFile['qwritebox'] = __QCUBED_CORE__ . '/base_controls/QWriteBox.class.php';

	QApplicationBase::$ClassFile['qpaginatedcontrol'] = __QCUBED_CORE__ . '/base_controls/QPaginatedControl.class.php';
	QApplicationBase::$ClassFile['qpaginatorbase'] = __QCUBED_CORE__ . '/base_controls/QPaginatorBase.class.php';
	QApplicationBase::$ClassFile['qpaginator'] = __QCUBED__ . '/controls/QPaginator.class.php';

	QApplicationBase::$ClassFile['qdatagridbase'] = __QCUBED_CORE__ . '/base_controls/QDataGridBase.class.php';
	QApplicationBase::$ClassFile['qdatagridcolumn'] = __QCUBED_CORE__ . '/base_controls/QDataGridColumn.class.php';
	QApplicationBase::$ClassFile['qcheckboxcolumn'] = __QCUBED_CORE__ . '/base_controls/QCheckBoxColumn.class.php'; 
	QApplicationBase::$ClassFile['qdatagridrowstyle'] = __QCUBED_CORE__ . '/base_controls/QDataGridRowStyle.class.php';
	QApplicationBase::$ClassFile['qdatagrid'] = __QCUBED__ . '/controls/QDataGrid.class.php';

	QApplicationBase::$ClassFile['qdatarepeater'] = __QCUBED_CORE__ . '/base_controls/QDataRepeater.class.php';

	QApplicationBase::$ClassFile['qwaiticon'] = __QCUBED_CORE__ . '/base_controls/QWaitIcon.class.php';
	QApplicationBase::$ClassFile['qcontrolgrouping'] = __QCUBED_CORE__ . '/base_controls/QControlGrouping.class.php';
	QApplicationBase::$ClassFile['qdropzonegrouping'] = __QCUBED_CORE__ . '/base_controls/QDropZoneGrouping.class.php';
	
	QApplicationBase::$ClassFile['qsamplecontrol'] = __QCUBED__ . '/controls/QSampleControl.class.php';
	
	// jQuery controls
	QApplicationBase::$ClassFile['qdraggablebase'] = __QCUBED_CORE__ . '/base_controls/QDraggableBase.class.php';
	QApplicationBase::$ClassFile['qdroppablebase'] = __QCUBED_CORE__ . '/base_controls/QDroppableBase.class.php';
	QApplicationBase::$ClassFile['qresizablebase'] = __QCUBED_CORE__ . '/base_controls/QResizableBase.class.php';
	QApplicationBase::$ClassFile['qselectablebase'] = __QCUBED_CORE__ . '/base_controls/QSelectableBase.class.php';
	QApplicationBase::$ClassFile['qsortablebase'] = __QCUBED_CORE__ . '/base_controls/QSortableBase.class.php';
	QApplicationBase::$ClassFile['qaccordionbase'] = __QCUBED_CORE__ . '/base_controls/QAccordionBase.class.php';
	QApplicationBase::$ClassFile['qautocompletebase'] = __QCUBED_CORE__ . '/base_controls/QAutocompleteBase.class.php';
	QApplicationBase::$ClassFile['qjqbuttonbase'] = __QCUBED_CORE__ . '/base_controls/QJqButtonBase.class.php';
	QApplicationBase::$ClassFile['qdatepickerbase'] = __QCUBED_CORE__ . '/base_controls/QDatepickerBase.class.php';
	QApplicationBase::$ClassFile['qdatepickerboxbase'] = __QCUBED_CORE__ . '/base_controls/QDatepickerBoxBase.class.php';
	QApplicationBase::$ClassFile['qdialogbase'] = __QCUBED_CORE__ . '/base_controls/QDialogBase.class.php';
	QApplicationBase::$ClassFile['qprogressbarbase'] = __QCUBED_CORE__ . '/base_controls/QProgressbarBase.class.php';
	QApplicationBase::$ClassFile['qsliderbase'] = __QCUBED_CORE__ . '/base_controls/QSliderBase.class.php';
	QApplicationBase::$ClassFile['qtabsbase'] = __QCUBED_CORE__ . '/base_controls/QTabsBase.class.php';
        
	QApplicationBase::$ClassFile['qdraggable'] = __QCUBED__ . '/controls/QDraggable.class.php';
	QApplicationBase::$ClassFile['qdroppable'] = __QCUBED__ . '/controls/QDroppable.class.php';
	QApplicationBase::$ClassFile['qresizable'] = __QCUBED__ . '/controls/QResizable.class.php';
	QApplicationBase::$ClassFile['qselectable'] = __QCUBED__ . '/controls/QSelectable.class.php';
	QApplicationBase::$ClassFile['qsortable'] = __QCUBED__ . '/controls/QSortable.class.php';
	QApplicationBase::$ClassFile['qaccordion'] = __QCUBED__ . '/controls/QAccordion.class.php';
	QApplicationBase::$ClassFile['qautocomplete'] = __QCUBED__ . '/controls/QAutocomplete.class.php';        
	QApplicationBase::$ClassFile['qjqbutton'] = __QCUBED__ . '/controls/QJqButton.class.php';        
	QApplicationBase::$ClassFile['qdatepicker'] = __QCUBED__ . '/controls/QDatepicker.class.php';        
	QApplicationBase::$ClassFile['qdatepickerBox'] = __QCUBED__ . '/controls/QDatepickerBox.class.php';        
	QApplicationBase::$ClassFile['qdialog'] = __QCUBED__ . '/controls/QDialog.class.php';        
	QApplicationBase::$ClassFile['qprogressbar'] = __QCUBED__ . '/controls/QProgressbar.class.php';        
	QApplicationBase::$ClassFile['qslider'] = __QCUBED__ . '/controls/QSlider.class.php';        
	QApplicationBase::$ClassFile['qtabs'] = __QCUBED__ . '/controls/QTabs.class.php';
	
	QApplicationBase::$ClassFile['qjsclosure'] = __QCUBED_CORE__ . '/framework/JavaScriptHelper.class.php';
	QApplicationBase::$ClassFile['javascripthelper'] = __QCUBED_CORE__ . '/framework/JavaScriptHelper.class.php';

	if (__MODEL_GEN__) {
		@include(__MODEL_GEN__ . '/_class_paths.inc.php');
		@include(__MODEL_GEN__ . '/_type_class_paths.inc.php');
	}
	
	// Includes for Plugin files
	require(__PLUGINS__ . '/plugin_includes.php');
	
	// Special Print Functions / Shortcuts
	// NOTE: These are simply meant to be shortcuts to actual QCubed functional
	// calls to make your templates a little easier to read.  By no means do you have to
	// use them.  Your templates can just as easily make the fully-named method/function calls.
		/**
		 * Standard Print function.  To aid with possible cross-scripting vulnerabilities,
		 * this will automatically perform QApplication::HtmlEntities() unless otherwise specified.
		 *
		 * @param string $strString string value to print
		 * @param boolean $blnHtmlEntities perform HTML escaping on the string first
		 */
		function _p($strString, $blnHtmlEntities = true) {
			// Standard Print
			if ($blnHtmlEntities && (gettype($strString) != 'object'))
				print(QApplication::HtmlEntities($strString));
			else
				print($strString);
		}

		/**
		 * Standard Print as Block function.  To aid with possible cross-scripting vulnerabilities,
		 * this will automatically perform QApplication::HtmlEntities() unless otherwise specified.
		 * 
		 * Difference between _b() and _p() is that _b() will convert any linebreaks to <br/> tags.
		 * This allows _b() to print any "block" of text that will have linebreaks in standard HTML.
		 *
		 * @param string $strString
		 * @param boolean $blnHtmlEntities
		 */
		function _b($strString, $blnHtmlEntities = true) {
			// Text Block Print
			if ($blnHtmlEntities && (gettype($strString) != 'object'))
				print(nl2br(QApplication::HtmlEntities($strString)));
			else
				print(nl2br($strString));
		}

		/**
		 * Standard Print-Translated function.  Note: Because translation typically
		 * occurs on coded text strings, NO HTML ESCAPING will be performed on the string.
		 * 
		 * Uses QApplication::Translate() to perform the translation (if applicable)
		 *
		 * @param string $strString string value to print via translation
		 */
		function _t($strString) {
			// Print, via Translation (if applicable)
			print(QApplication::Translate($strString));
		}

		function _i($intNumber) {
			// Not Yet Implemented
			// Print Integer with Localized Formatting
		}

		function _f($intNumber) {
			// Not Yet Implemented
			// Print Float with Localized Formatting
		}

		function _c($strString) {
			// Not Yet Implemented
			// Print Currency with Localized Formatting
		}
	//////////////////////////////////////
?>