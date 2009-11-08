<template OverwriteFlag="false" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<%= __FORM_DRAFTS__ %>" TargetFileName="<%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_edit.php"/>
<?php
	// Load the QCubed Development Framework
	require('../qcubed.inc.php');

	require(__FORMBASE_CLASSES__ . '/<%= $objTable->ClassName %>EditFormBase.class.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do Create, Edit, and Delete functionality
	 * of the <%= $objTable->ClassName %> class.  It uses the code-generated
	 * <%= $objTable->ClassName %>MetaControl class, which has meta-methods to help with
	 * easily creating/defining controls to modify the fields of a <%= $objTable->ClassName %> columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_edit.php AND
	 * <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_edit.tpl.php out of this Form Drafts directory.
	 *
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @subpackage Drafts
	 */
	class <%= $objTable->ClassName %>EditForm extends <%= $objTable->ClassName %>EditFormBase {
		// Override Form Event Handlers as Needed
//		protected function Form_Run() {}

//		protected function Form_Load() {}

//		protected function Form_Create() {}
	}

	// Go ahead and run this form object to render the page and its event handlers, implicitly using
	// <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_edit.tpl.php as the included HTML template file
	<%= $objTable->ClassName %>EditForm::Run('<%= $objTable->ClassName %>EditForm');
?>