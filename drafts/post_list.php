<?php
	// Load the QCubed Development Framework
	require('../qcubed.inc.php');

	require(__FORMBASE_CLASSES__ . '/PostListFormBase.class.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do the List All functionality
	 * of the Post class.  It uses the code-generated
	 * PostDataGrid control which has meta-methods to help with
	 * easily creating/defining Post columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both post_list.php AND
	 * post_list.tpl.php out of this Form Drafts directory.
	 *
	 * @package My QCubed Application
	 * @subpackage Drafts
	 */
	class PostListForm extends PostListFormBase {
		// Override Form Event Handlers as Needed
		protected function Form_Run() {
			parent::Form_Run();

			// Security check for ALLOW_REMOTE_ADMIN
			// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
			QApplication::CheckRemoteAdmin();		    
		}

//		protected function Form_Load() {}

//		protected function Form_Create() {}
	}

	// Go ahead and run this form object to generate the page and event handlers, implicitly using
	// post_list.tpl.php as the included HTML template file
	PostListForm::Run('PostListForm');
?>