<template OverwriteFlag="false" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<%= __FORM_DRAFTS__ %>" TargetFileName="<%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_list.php"/>
<?php
	// Load the QCubed Development Framework
	require('../qcubed.inc.php');

	require(__FORMBASE_CLASSES__ . '/<%= $objTable->ClassName %>ListFormBase.class.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do the List All functionality
	 * of the <%= $objTable->ClassName %> class.  It uses the code-generated
	 * <%= $objTable->ClassName %>DataGrid control which has meta-methods to help with
	 * easily creating/defining <%= $objTable->ClassName %> columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_list.php AND
	 * <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_list.tpl.php out of this Form Drafts directory.
	 *
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @subpackage Drafts
	 */
	class <%= $objTable->ClassName %>ListForm extends <%= $objTable->ClassName %>ListFormBase {
		// Override Form Event Handlers as Needed
//		protected function Form_Run() {}

//		protected function Form_Load() {}

//		protected function Form_Create() {}
	}

	// Go ahead and run this form object to generate the page and event handlers, implicitly using
	// <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_list.tpl.php as the included HTML template file
	<%= $objTable->ClassName %>ListForm::Run('<%= $objTable->ClassName %>ListForm');
?>