<?php
	class Examples {
		public static $Categories = array();
		public static $AdditionalCode = array();
		public static $PluginExamples = array();
		public static $PluginAdditions = array();

		public static function Init() {
			$intIndex = -1;

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Basic CodeGen';
			Examples::$Categories[$intIndex]['description'] = 'An introduction to the QCubed Code Generator';
 			self::AddCoreExampleFile($intIndex, '/code_generator/about.php (About Sections 1 - 3)');
			self::AddCoreExampleFile($intIndex, '/code_generator/intro.php About the Database');
			self::AddCoreExampleFile($intIndex, '/code_generator/objects.php * Object Relational Model');
			self::AddCoreExampleFile($intIndex, '/code_generator/indexes.php * Loading Objects');
			self::AddCoreExampleFile($intIndex, '/code_generator/save_delete.php * Saving and Deleting');
			self::AddCoreExampleFile($intIndex, '/code_generator/relationships.php * Relationships (Foreign Keys)');
			self::AddCoreExampleFile($intIndex, '/code_generator/reverse_relationships.php * Reverse Relationships (One-to-One and One-to-Many)');
			self::AddCoreExampleFile($intIndex, '/code_generator/relationships_many.php * Many-to-Many Relationships');
			self::AddCoreExampleFile($intIndex, '/code_generator/script_path.php Defining Relationships without Foreign Keys');
			self::AddCoreExampleFile($intIndex, '/code_generator/primary_keys.php Explanation of QCubed Primary Key Requirement');
			Examples::$AdditionalCode[__VIRTUAL_DIRECTORY__ . __EXAMPLES__ . '/code_generator/intro.php'] = array('mysql_innodb.sql','sql_server.sql');
			Examples::$AdditionalCode[__VIRTUAL_DIRECTORY__  . __EXAMPLES__ . '/code_generator/script_path.php'] = array('mysql_myisam.sql', 'relationships.txt');

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'More About the Object Relational Model';
			Examples::$Categories[$intIndex]['description'] = 'Looking more in depth at the functionality of the generated ORM';
			self::AddCoreExampleFile($intIndex, '/more_codegen/sort_limit.php * Sorting and Limiting Array Results');
			self::AddCoreExampleFile($intIndex, '/more_codegen/late_bind.php * Late Binding of Related Objects');
			self::AddCoreExampleFile($intIndex, '/more_codegen/early_bind.php * Early Binding of Related Objects');
			self::AddCoreExampleFile($intIndex, '/more_codegen/virtual_attributes.php * Virtual Attributes');
			self::AddCoreExampleFile($intIndex, '/more_codegen/type_tables.php * Type Tables');
			self::AddCoreExampleFile($intIndex, '/more_codegen/custom.php * Customized Business Logic');
			self::AddCoreExampleFile($intIndex, '/more_codegen/custom_load.php * Customized Load Methods');
			self::AddCoreExampleFile($intIndex, '/more_codegen/optimistic_locking.php * Optimistic Locking');

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Querying in QCubed';
			Examples::$Categories[$intIndex]['description'] = 'Ad Hoc Queries, Custom Load Methods, and QCubed Query';
			self::AddCoreExampleFile($intIndex, '/qcubed_query/qq.php * QCubed Query: Object Oriented Database Querying');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/qqnode.php * QCubed Query: The QQ Node Classes');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/qqcondition.php * QCubed Query: The QQ Condition Classes');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/qqclause.php * QCubed Query: The QQ Clause Classes');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/conditional_expand.php * QCubed Query: Conditional Joins');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/association.php * QCubed Query: Handling Association Tables');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/reverse.php * QCubed Query: Handling Reverse Relationships');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/expandasarray.php * ExpandAsArray: Multiple related tables in one swift query');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/alias.php * SQL Aliases for QQuery');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/qqselect.php * Picking database columns for QQuery');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/qqfuncnmath.php * SQL functions and math operations for QQuery');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/subsql.php * Custom SQL Subqueries for QQuery');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/intro.php * Performing Custom SQL Queries');
			self::AddCoreExampleFile($intIndex, '/qcubed_query/qcache.php * Caching Query Results with QueryArrayCached');

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Basic QForms';
			Examples::$Categories[$intIndex]['description'] = 'An introduction to QForms and QControls';
			self::AddCoreExampleFile($intIndex, '/basic_qform/about.php (About Sections 4 - 10)');
			self::AddCoreExampleFile($intIndex, '/basic_qform/intro.php Hello World Example');
			self::AddCoreExampleFile($intIndex, '/basic_qform/state.php QForms: Stateful, Event-Driven Objects');
			self::AddCoreExampleFile($intIndex, '/basic_qform/process_flow.php Understanding Process Flow');
			self::AddCoreExampleFile($intIndex, '/basic_qform/calculator.php Calculator Example');
			self::AddCoreExampleFile($intIndex, '/basic_qform/calculator_2.php Calculator Example with Validation');
			self::AddCoreExampleFile($intIndex, '/basic_qform/calculator_3.php Calculator Example with &quot;Design&quot;');
			self::AddCoreExampleFile($intIndex, '/basic_qform/listbox.php * Introduction to QListControl');
			self::AddCoreExampleFile($intIndex, '/basic_qform/hlist.php * Generating Html Lists');
			self::AddCoreExampleFile($intIndex, '/basic_qform/textbox.php * Introduction to QTextBoxControls');
			
			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Basic AJAX in QForms';
			Examples::$Categories[$intIndex]['description'] = 'A look at how to AJAX-enable your QForms';
			self::AddCoreExampleFile($intIndex, '/basic_ajax/intro.php Hello World Example using AJAX');
			self::AddCoreExampleFile($intIndex, '/basic_ajax/calculator_2.php Calculator Example using AJAX');
			self::AddCoreExampleFile($intIndex, '/basic_ajax/wait_icon.php Adding a Wait Icon');

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'More About Events and Actions';
			Examples::$Categories[$intIndex]['description'] = 'Looking more in depth at the capabilities of the QEvent and QAction libraries';
			self::AddCoreExampleFile($intIndex, '/events_actions/editable_listbox.php Editable ListBox');
			self::AddCoreExampleFile($intIndex, '/events_actions/editable_listbox_2.php Conditional Events');
			self::AddCoreExampleFile($intIndex, '/events_actions/delayed.php Trigger-Delayed Events');
			self::AddCoreExampleFile($intIndex, '/events_actions/javascript_alerts.php Javascript Actions, Alerts and Confirmations');
			self::AddCoreExampleFile($intIndex, '/events_actions/other_actions.php Other Client-Side QActions');
			self::AddCoreExampleFile($intIndex, '/events_actions/event_propagation.php Controlling Event Bubbling');
			self::AddCoreExampleFile($intIndex, '/events_actions/event_delegation.php Event Delegation');
			self::AddCoreExampleFile($intIndex, '/events_actions/synchronizing.php Blocking Unwanted Events');
			self::AddCoreExampleFile($intIndex, '/events_actions/low_priority_js.php JavaScript Priorities');
			self::AddCoreExampleFile($intIndex, '/events_actions/watcher.php Automatic Refreshing of Controls');
			self::AddCoreExampleFile($intIndex, '/events_actions/control_proxy.php Using QControlProxies to have Non-QControls Trigger Events');

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Paginated Controls';
			Examples::$Categories[$intIndex]['description'] = 'The QHtmlTable, QDataGrid and QDataRepeater controls';
			self::AddCoreExampleFile($intIndex, '/datagrid/intro.php * QHtmlTable');
			self::AddCoreExampleFile($intIndex, '/datagrid/columns.php * QHtmlTable Columns');
			self::AddCoreExampleFile($intIndex, '/datagrid/column_values.php * QHtmlTable Formatting and Styling');
			self::AddCoreExampleFile($intIndex, '/datagrid/link_column.php * QHtmlTable Link Column');
			self::AddCoreExampleFile($intIndex, '/datagrid/links_images.php * Dynamically adding Controls to QHtmlTable');
			self::AddCoreExampleFile($intIndex, '/datagrid/sorting.php * The QDataGrid Class and Sorting');
			self::AddCoreExampleFile($intIndex, '/datagrid/pagination.php * QDataGrid Pagination');
			self::AddCoreExampleFile($intIndex, '/datagrid/rowactions.php * Detecting Row and Cell Clicks');

			self::AddCoreExampleFile($intIndex, '/datagrid/qcheckboxcolumn.php * The Easy Way to Add a Checkbox Column to a Datagrid');
			self::AddCoreExampleFile($intIndex, '/dynamic/inline_editing.php * Datagrid with Inline Editing');

			self::AddCoreExampleFile($intIndex, '/master_detail/project_list.php * Nested QDataGrid');
			Examples::$AdditionalCode[__VIRTUAL_DIRECTORY__ . __EXAMPLES__  . '/master_detail/project_list.php'] = array(
				'records.summary.php',
				'records.summary.tpl.php',
				'styles.css');
			self::AddCoreExampleFile($intIndex, '/datarepeater/ajax.php * Simple QDataRepeater using AJAX-triggered Pagination');
			Examples::$AdditionalCode[__VIRTUAL_DIRECTORY__ . __EXAMPLES__  . '/datarepeater/ajax.php'] = array('dtr_persons.tpl.php');


			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Advanced Controls Manipulation';
			Examples::$Categories[$intIndex]['description'] = 'Dynamically creating controls, Implementing custom controls';
			self::AddCoreExampleFile($intIndex, '/dynamic/qpanel.php Introduction to QBlockControls');
			self::AddCoreExampleFile($intIndex, '/dynamic/qpanel_2.php Dynamically Changing a Control\'s Parent');
			self::AddCoreExampleFile($intIndex, '/other_controls/persist.php Persistent Controls: Performance for Reusable Components');
			self::AddCoreExampleFile($intIndex, '/other_controls/sample.php Creating Your Own Control');
			self::AddCoreExampleFile($intIndex, '/composite/intro.php Creating a Composite Control');
			self::AddCoreExampleFile($intIndex, '/multiple_qform/intro.php "Multiple QForms" Functionality via Custom QPanels');
			self::AddCoreReferencedFile('/dynamic/qpanel.php', 'pnl_panel.tpl.php');
			self::AddCoreReferencedFile('/other_controls/sample.php', '__CORE_CONTROL__QSampleControl.class.php');
			self::AddCoreReferencedFile('/composite/intro.php', 'SampleComposite.class.php');
			self::AddCoreReferencedFile('/multiple_qform/intro.php', array(
				'ProjectViewPanel.class.php', 'ProjectViewPanel.tpl.php',
				'ProjectEditPanel.class.php', 'ProjectEditPanel.tpl.php',
				'PersonEditPanel.class.php', 'PersonEditPanel.tpl.php'));

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Advanced AJAX';
			Examples::$Categories[$intIndex]['description'] = 'Advanced AJAX functionality like drag and drop, selection and cinematic effects';
			self::AddCoreExampleFile($intIndex, '/advanced_ajax/renamer.php Renameable Labels');
			self::AddCoreExampleFile($intIndex, '/advanced_ajax/renamer_2.php Porting Code to the Client Side');
			self::AddCoreExampleFile($intIndex, '/advanced_ajax/move.php Moveable Controls (a.k.a. Drag and Drop)');
			self::AddCoreExampleFile($intIndex, '/advanced_ajax/scrolling.php Automatic Scrolling');
			self::AddCoreExampleFile($intIndex, '/advanced_ajax/move_target.php Move Handle: Specifying Which Controls to Move');
			self::AddCoreExampleFile($intIndex, '/advanced_ajax/drop_zone.php Move Handle: Defining Drop Zones');
			self::AddCoreExampleFile($intIndex, '/advanced_ajax/resize_jquery.php Resizing Block Controls');
			self::AddCoreExampleFile($intIndex, '/advanced_ajax/dialog_box.php Modal "Dialog Boxes"');
			self::AddCoreExampleFile($intIndex, '/other_controls/jq_example.php * Server-side wrappers for all jQuery UI Controls');
			self::AddCoreExampleFile($intIndex, '/other_controls/js_return_param_example.php Post data back to the server from jQuery UI controls');
			self::AddCoreExampleFile($intIndex, '/advanced_ajax/jquery_effects.php JQuery Effects');
			
			Examples::$AdditionalCode[__VIRTUAL_DIRECTORY__ . __EXAMPLES__ . '/advanced_ajax/dialog_box.php'] = array('CalculatorWidget.class.php','CalculatorWidget.tpl.php');

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Other Advanced Controls';
			Examples::$Categories[$intIndex]['description'] = 'A collection of examples for some of the more advanced/complex QControls';
			self::AddCoreExampleFile($intIndex, '/image_label/intro.php Introduction to QImageLabel');
			self::AddCoreExampleFile($intIndex, '/image_rollover/intro.php Image rollovers using QImageRollover');
			self::AddCoreExampleFile($intIndex, '/treenav/treenav.php Introduction to QTreeNav');
			self::AddCoreExampleFile($intIndex, '/other_controls/image.php Introduction to QImageControl');
			self::AddCoreExampleFile($intIndex, '/other_controls/datetime.php Date and DateTime-based QControls');
			self::AddCoreExampleFile($intIndex, '/other_controls/file_asset.php Combining Controls, Ex. 1: A Better Way to Upload Files');
			self::AddCoreExampleFile($intIndex, '/image_browser/test_image_browser.php Combining Controls, Ex. 2: Browsing Image Files');
			self::AddCoreExampleFile($intIndex, '/other_controls/image_file_asset.php Upload Control Just for Images');
			self::AddCoreExampleFile($intIndex, '/other_controls/timer_js.php JavaScript Timer');
			self::AddCoreReferencedFile('/image_browser/test_image_browser.php', array('image_browser.tpl.php', 'imagebrowser.css'));

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Model Connectors, DataGrid Connectors, and the Drafts';
			Examples::$Categories[$intIndex]['description'] = 'Combining the Code Generator with the QForm Library';
			self::AddCoreExampleFile($intIndex, '/other/formgen.php QForm and CodeGen, a Winning Combination to RAD');
			self::AddCoreExampleFile($intIndex, '/other/model_connectors.php * Introduction to Model Connectors');
			self::AddCoreExampleFile($intIndex, '/other/datagrid_connectors.php * Introduction to DataGrid Connectors');
			self::AddCoreExampleFile($intIndex, '/other/form_drafts.php * Introduction to the Drafts');

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Beyond HTML';
			Examples::$Categories[$intIndex]['description'] = 'Other libraries in QCubed, including support for Email and RSS';
			self::AddCoreExampleFile($intIndex, '/communication/email.php Introduction to QEmailServer');
			self::AddCoreExampleFile($intIndex, '/communication/rss.php * Introduction to QRssFeed');
			self::AddCoreExampleFile($intIndex, '/other/parsing.php Introduction to parsing with QLexer and QRegex');
			self::AddCoreExampleFile($intIndex, '/communication/crypto.php Introduction to QCryptography');
			self::AddCoreExampleFile($intIndex, '/communication/i18n.php Introduction to QI18n (Internationalization)');
			self::AddCoreExampleFile($intIndex, '/communication/advanced_intl.php Advanced Internationalization techniques');
			self::AddCoreExampleFile($intIndex, '/communication/soap.php * Introduction to QSoapService');
			self::AddCoreExampleFile($intIndex, '/other/timer.php * Measuring Performance using QTimer');
			self::AddCoreReferencedFile('/communication/rss.php', 'rss_feed.php');
			self::AddCoreReferencedFile('/communication/i18n.php', array('en.po', 'es.po'));
			self::AddCoreReferencedFile('/communication/soap.php', 'example_service.php');
			self::AddCoreReferencedFile('/other/parsing.php', 'bb_parser.php');
			self::AddCoreReferencedFile('/communication/advanced_intl.php', 'sample_translator.class.php');
			

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Other Tidbits';
			Examples::$Categories[$intIndex]['description'] = 'Other random examples, samples and tutorials';
			self::AddCoreExampleFile($intIndex, '/basic_qform/xss.php * Security: Preventing Cross-Site Scripting');
			self::AddCoreExampleFile($intIndex, '/other/optimistic_locking.php * Optimistic Locking and QForms');
			self::AddCoreExampleFile($intIndex, '/other/attribute_overriding.php Attribute Overriding');
			self::AddCoreExampleFile($intIndex, '/other/alternate_template.php Specifying a Template Filepath');
			self::AddCoreExampleFile($intIndex, '/other/single.php Single File QForms');
			self::AddCoreExampleFile($intIndex, '/other/form_state.php Working with FormState Handlers');
			self::AddCoreExampleFile($intIndex, '/other/print.php PHP Print Command Shortcuts');
			self::AddCoreExampleFile($intIndex, '/other/includes_outside.php Includes outside of the __DOCROOT__ for Security and Shared Installs');
			self::AddCoreReferencedFile('/other/alternate_template.php', 'some_template_file.tpl.php');

			$intIndex++;
			Examples::$Categories[$intIndex] = array();
			Examples::$Categories[$intIndex]['name'] = 'Plugin System';
			Examples::$Categories[$intIndex]['description'] = 'Infrastructure that lets the community extend QCubed';
			self::AddCoreExampleFile($intIndex, '/plugins/about.php Plugin Ecosystem: Introduction');
			self::AddCoreExampleFile($intIndex, '/plugins/components.php Writing your own plugins, Part 1: components of a plugin');
			self::AddCoreExampleFile($intIndex, '/plugins/packaging.php Writing your own plugins, Part 2: packaging a plugin');
			//self::AddCoreExampleFile($intIndex, '/plugins/unattended.php Automatic Installation of Plugins');
			self::AddCoreReferencedFile('/plugins/components.php', '__CORE_FRAMEWORK__QPluginInterface.class.php');

			// Scan plugin folders for examples to include
			if (defined ('__PLUGINS__') &&
				is_dir(__PLUGINS__)) {
				$plugins = scandir(__PLUGINS__);

				if (count($plugins) > 0) {
					//$intIndex++;
					//Examples::$Categories[$intIndex] = array();
					//Examples::$Categories[$intIndex]['name'] = 'Examples for Plugins';
					//Examples::$Categories[$intIndex]['description'] = 'Examples and Documentation for Community-Written Plugins';

					foreach ($plugins as $dirName) {
						if ($dirName != '.' && $dirName != '..' && file_exists(__PLUGINS__ . '/' . $dirName . '/composer.json')) {
							// read composer file for examples file name
							$composerDetails = json_decode(file_get_contents(__PLUGINS__ . '/' . $dirName . '/composer.json' ), true);
							if (!empty($composerDetails['extra']['examples'])) { // embed example page name into composer file for convenience
								foreach ($composerDetails['extra']['examples'] as $strExample) {
									$strExamplePath = __PLUGINS__ . '/' . $dirName . '/examples/' . $strExample;
									if (file_exists ($strExamplePath)) {
										self::AddPluginExampleFile($dirName, $strExample);
										if (!empty($composerDetails['extra']['exampleExtras'][$strExample])) {
											self::AddPluginExtraFile($dirName, $strExample, $composerDetails['extra']['exampleExtras'][$strExample]);
										}
									}
								}
							}
						}
					}
				}
			}
		}

		public static function AddPluginExampleFile($strPluginName, $strExampleFileName) {
			Examples::$PluginExamples[$strPluginName][] = $strExampleFileName;
		}

		public static function AddPluginExtraFile($strPluginName, $strExampleFileName, $extras) {
			Examples::$PluginAdditions[$strPluginName][$strExampleFileName] = $extras;
		}

		private static function AddCoreExampleFile($intIndex, $strExampleFileName) {
			array_push(Examples::$Categories[$intIndex], __VIRTUAL_DIRECTORY__ . __EXAMPLES__ . $strExampleFileName);
		}
		
		private static function AddCoreReferencedFile($strExampleFileName, $mixReferencedFile) {
			if (!is_array($mixReferencedFile)) {
				$mixReferencedFile = array($mixReferencedFile);
			}
			Examples::$AdditionalCode[__VIRTUAL_DIRECTORY__ . __EXAMPLES__ . $strExampleFileName] = $mixReferencedFile;
		}

		public static function GetCategoryId() {
			$numCategories = count(Examples::$Categories);
			$categories = Examples::$Categories;
			for ($intCategoryIndex = 0; $intCategoryIndex < count(Examples::$Categories); $intCategoryIndex++) {
				$objExampleCategory = Examples::$Categories[$intCategoryIndex];
				
				for ($intExampleIndex = 0; $intExampleIndex < count($objExampleCategory); $intExampleIndex++) {
					if (array_key_exists($intExampleIndex, $objExampleCategory)) {
						$strExample = $objExampleCategory[$intExampleIndex];
						$intPosition = strpos($strExample, ' ');
						$strScriptPath = substr($strExample, 0, $intPosition);

						if (substr_count($strScriptPath, QApplicationBase::$ScriptName) > 0) {
							return $intCategoryIndex;
						}
					}
				}
			}

			// Might be a plugin
			$strScript = QApplicationBase::$ScriptName;
			if ($offset = strpos (QApplicationBase::$ScriptFilename, '/plugin/')) {
				return 'plugin';
			}

			return null;
		}

		public static function GetExampleId() {
			for ($intCategoryIndex = 0; $intCategoryIndex < count(Examples::$Categories); $intCategoryIndex++) {
				$objExampleCategory = Examples::$Categories[$intCategoryIndex];
				
				for ($intExampleIndex = 0; $intExampleIndex < count($objExampleCategory); $intExampleIndex++) {
					if (array_key_exists($intExampleIndex, $objExampleCategory)) {
						$strExample = $objExampleCategory[$intExampleIndex];
						$intPosition = strpos($strExample, ' ');
						$strScriptPath = strtolower(substr($strExample, 0, $intPosition));
						$strName = substr($strExample, $intPosition + 1);
						
						$scriptName = QApplicationBase::$ScriptName;
						$portionOfScriptName = strtolower(substr($scriptName, strlen(QApplicationBase::$ScriptName) - strlen($strScriptPath)));

						if ($portionOfScriptName == $strScriptPath) {
							return $intExampleIndex;					
						}
					}
				}
			}

			// Might be a plugin
			if ($offset = strpos (QApplicationBase::$ScriptFilename, '/plugin/')) {
				$offset += strlen ('/plugin/');
				$endoffset = strpos (QApplicationBase::$ScriptFilename, '/', $offset);
				$strCat = substr (QApplicationBase::$ScriptFilename, $offset, $endoffset - $offset);

				// Make sure it exists
				if (!empty(Examples::$PluginExamples[$strCat])) {
					return $strCat;
				}
			}

			return null;
		}

		public static function GetPluginFile() {
			if ($offset = strpos (QApplicationBase::$ScriptFilename, '/plugin/')) {
				$offset += strlen ('/plugin/');
				$endoffset = strpos (QApplicationBase::$ScriptFilename, '/', $offset);
				$strCat = substr (QApplicationBase::$ScriptFilename, $offset, $endoffset - $offset);

				// make sure it exists (prevents cross-script attack)
				if (!empty(Examples::$PluginExamples[$strCat])) {
					$endoffset = strrpos (QApplicationBase::$ScriptFilename, '/', $offset);
					$strCurFile = substr(QApplicationBase::$ScriptFilename, $endoffset + 1);
					for ($intExampleIndex = 0; $intExampleIndex < count(Examples::$PluginExamples[$strCat]); $intExampleIndex++) {
						if (Examples::$PluginExamples[$strCat][$intExampleIndex] == $strCurFile) {
							return $strCurFile;
						}
					}
				}
			}
			return null;
		}

		public static function GetExampleName($mixCategoryId, $mixExampleId) {
			if ($mixCategoryId == 'plugin') {
				$strExample = Examples::$PluginExamples[$mixExampleId][0];
				return $strExample;
			}
			$strExample = Examples::$Categories[$mixCategoryId][$mixExampleId];
			$intPosition = strpos($strExample, ' ');
			$strScriptPath = substr($strExample, 0, $intPosition);
			$strName = substr($strExample, $intPosition + 1);
			return $strName;
		}
		
		public static function GetExampleScriptPath($mixCategoryId, $mixExampleId, $mixSubId = null) {
			if ($mixCategoryId == 'plugin') {
				$strScriptPath =  __PLUGIN_ASSETS__ . "/{$mixExampleId}/examples/{$mixSubId}";
				return $strScriptPath;
			}
			$strExample = Examples::$Categories[$mixCategoryId][$mixExampleId];
			$intPosition = strpos($strExample, ' ');
			$strScriptPath = substr($strExample, 0, $intPosition);
			return $strScriptPath;
		}

		public static function PageName($strReference = null) {
			if (is_null($strReference))
				$strReference = QApplication::$ScriptName;

			foreach (Examples::$Categories as $objExampleCategory)
				foreach ($objExampleCategory as $strKey => $strExample)
					if (is_numeric($strKey)) {
						// Pull out the URL fragment from the example tree
						$intPosition = strpos($strExample, ' ');
						$strScriptName = substr($strExample, 0, $intPosition);

						if (strpos($strReference, $strScriptName) !== false)
							return(substr($strExample, $intPosition + 1));
					}

			return 'Main Page';
		}
		
		public static function PageLinkName($strReference = null) {
			if (is_null($strReference))
				$strReference = QApplication::$ScriptName;

			foreach (Examples::$Categories as $objExampleCategory)
				foreach ($objExampleCategory as $strKey => $strExample)
					if (is_numeric($strKey)) {
						// Pull out the URL fragment from the example tree
						$intPosition = strpos($strExample, ' ');
						$strScriptName = substr($strExample, 0, $intPosition);

						if (strpos($strReference, $strScriptName) !== false)
							return($strScriptName);
					}
		}
		
		public static function CodeLinks($strCategoryId, $strExampleId, $strSubId, $strCurrentScript) {
			$blnIsScript = false;

			$strReference = Examples::GetExampleScriptPath($strCategoryId, $strExampleId, $strSubId);

			if ($strCurrentScript == 'header.inc.php') {
				$strToReturn = '<span class="headerGray">header.inc.php</span>';
				$blnIsScript = true;
			} else {
				$strToReturn = sprintf('<a href="%s/../header.inc.php" class="headerLink">header.inc.php</a>', QApplication::$RequestUri);
			}

			$strToReturn .= ' &nbsp; | &nbsp; ';

			if ($strCurrentScript == 'footer.inc.php') {
				$strToReturn .= '<span class="headerGray">footer.inc.php</span>';
				$blnIsScript = true;
			} else {
				$strToReturn .= sprintf('<a href="%s/../footer.inc.php" class="headerLink">footer.inc.php</a>', QApplication::$RequestUri);
			}
			
			$strToReturn .= ' &nbsp; | &nbsp; ';
			if ($strCurrentScript == 'examples.css') {
				$strToReturn .= '<span class="headerGray">examples.css</span>';
				$blnIsScript = true;
			} else
				$strToReturn .= sprintf('<a href="%s/../examples.css" class="headerLink">examples.css</a>', QApplication::$RequestUri);

			$strToReturn .= ' &nbsp; | &nbsp; ';

			$strScriptname = substr($strReference, strrpos($strReference, '/') + 1);
			if ($strCurrentScript == $strScriptname) {
				$strToReturn .= sprintf('<span class="headerGray">%s</span>', $strScriptname);
				$blnIsScript = true;
			} else
				$strToReturn .= sprintf('<a href="%s/../%s" class="headerLink">%s</a>', QApplication::$RequestUri, $strScriptname, $strScriptname);


			// Current Number of Code Links
			$intCount = 4;

			if (file_exists(__DOCROOT__ . str_replace('.php', '.tpl.php', $strReference))) {
				$strToReturn .= ' &nbsp; | &nbsp; ';

				$strScriptname = substr(str_replace('.php', '.tpl.php', $strReference), strrpos(str_replace('.php', '.tpl.php', $strReference), '/') + 1);
				if ($strCurrentScript == $strScriptname) {
					$strToReturn .= sprintf('<span class="headerGray">%s</span>', $strScriptname);
					$blnIsScript = true;
				} else {
					$strToReturn .= sprintf('<a href="%s/../%s" class="headerLink">%s</a>', QApplication::$RequestUri, $strScriptname, $strScriptname);
				}
				
				$intCount++;
			}

			if ($strCategoryId == 'plugin') {
				if (!empty(Examples::$PluginAdditions[$strExampleId][$strSubId])) {
					$extraFiles = Examples::$PluginAdditions[$strExampleId][$strSubId];
				}
			}
			if(array_key_exists($strReference, Examples::$AdditionalCode)) {
				$extraFiles = Examples::$AdditionalCode[$strReference];
			}

			if (!empty($extraFiles)) {
				foreach ($extraFiles as $strCode) {
					if (($intCount % 7) == 0)
						$strToReturn .= '<br/>';
					else
						$strToReturn .= ' &nbsp; | &nbsp; ';

					$strScriptname = $strCode;
					if ($strCurrentScript == $strScriptname) {
						$strToReturn .= sprintf('<span class="headerGray">%s</span>', preg_replace('/__.*__/', '', $strScriptname));
						$blnIsScript = true;
					} else {
						$strToReturn .= sprintf('<a href="%s/../%s" class="headerLink">%s</a>', QApplication::$RequestUri, $strScriptname, preg_replace('/__.*__/', '', $strScriptname));
					}

					$intCount++;
				}
			}
				
			if ($blnIsScript) {
				return $strToReturn;
			}
			else {
				QApplication::CloseWindow();
			}
		}

		public static function PageLinks() {
			$strPrevious = null;
			$strNext = null;
			$blnFound = false;

			$strScript = QApplication::$ScriptName;

			if (strpos($strScript, "plugin") !== false &&
				strpos($strScript, "vendor") !== false) {
				// a plugin
				$strLink = QHtml::RenderLink(QHtml::MakeUrl(__DEVTOOLS_ASSETS__ . '/plugin_manager.php'), "Plugin Manager", ["class"=>"headerLink"]);
				return $strLink;
			}

			foreach (Examples::$Categories as $objExampleCategory) {
				if (!$blnFound) {
					$strPrevious = null;
					$strNext = null;

					foreach ($objExampleCategory as $strKey => $strExample) {
						if (is_numeric($strKey)) {
							// Pull out the URL fragment from the example tree
							$intPosition = strpos($strExample, ' ');
							$strScriptName = substr($strExample, 0, $intPosition);
							$strDescription = substr($strExample, $intPosition + 1);
							$qapp = QApplication::$ScriptName;

							if (!$blnFound) {
								if (strpos(QApplication::$ScriptName, $strScriptName) !== false || // for core examples
									strpos($strScriptName, QApplication::$ScriptName) !== false) { // for plugins examples
									$blnFound = true;
								} else {
									$strPrevious = sprintf('<strong><a href="%s" class="headerLink">&lt;&lt; %s</a></strong>',
										$strScriptName, $strDescription);
								}
							} else if (!$strNext) {
								$strNext = sprintf('<strong><a href="%s" class="headerLink">%s &gt;&gt;</a></strong>',
									$strScriptName, $strDescription);
							}
						}
					}
				}
			}
			
			$strToReturn = '';
			
			if ($strPrevious)
				$strToReturn = $strPrevious;
			else
				$strToReturn = '<span class="headerGray">&lt;&lt; Previous</span>';

			$intCategoryId = Examples::GetCategoryId();
			if ($intCategoryId < 3)
				$intPartId = 1;
			else if ($intCategoryId < 10)
				$intPartId = 2;
			else
				$intPartId = 3;

			$strToReturn .= ' &nbsp; | &nbsp; ';
			$strToReturn .= sprintf('<strong><a href="%s/index.php%s" class="headerLink">Back to Main</a></strong>',
				__VIRTUAL_DIRECTORY__ . __EXAMPLES__, $intPartId == 1 ? "" : "/" . $intPartId);
			$strToReturn .= ' &nbsp; | &nbsp; ';

			if ($strNext)
				$strToReturn .= $strNext;
			else
				$strToReturn .= '<span class="headerGray">Next &gt;&gt;</span>';

			return $strToReturn;				
		}
	}
	
	//require_once(__PLUGINS__ . "/plugin_examples.php");

	Examples::Init();
?>
