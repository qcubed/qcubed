<?php
	// Load the QCubed Development Framework
	require('../application/configuration/prepend.inc.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do the List All functionality
	 * of the PersonWithLock class.  It uses the code-generated
	 * PersonWithLockDataGrid control which has meta-methods to help with
	 * easily creating/defining PersonWithLock columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both person_with_lock_list.php AND
	 * person_with_lock_list.tpl.php out of this Form Drafts directory.
	 *
	 * @package My Application
	 * @subpackage Drafts
	 */
	class PersonWithLockListForm extends QForm {
		// Local instance of the Meta DataGrid to list PersonWithLocks
		protected $dtgPersonWithLocks;

		// Create QForm Event Handlers as Needed

//		protected function Form_Exit() {}
//		protected function Form_Load() {}
//		protected function Form_PreRender() {}
//		protected function Form_Validate() {}

		protected function Form_Run() {
			// Security check for ALLOW_REMOTE_ADMIN
			// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
			QApplication::CheckRemoteAdmin();
		}

		protected function Form_Create() {
			// Instantiate the Meta DataGrid
			$this->dtgPersonWithLocks = new PersonWithLockDataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtgPersonWithLocks->CssClass = 'datagrid';
			$this->dtgPersonWithLocks->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgPersonWithLocks->Paginator = new QPaginator($this->dtgPersonWithLocks);
			$this->dtgPersonWithLocks->ItemsPerPage = 20;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$strEditPageUrl = __VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/person_with_lock_edit.php';
			$this->dtgPersonWithLocks->MetaAddEditLinkColumn($strEditPageUrl, 'Edit', 'Edit');

			// Create the Other Columns (note that you can use strings for person_with_lock's properties, or you
			// can traverse down QQN::person_with_lock() to display fields that are down the hierarchy)
			$this->dtgPersonWithLocks->MetaAddColumn('Id');
			$this->dtgPersonWithLocks->MetaAddColumn('FirstName');
			$this->dtgPersonWithLocks->MetaAddColumn('LastName');
			$this->dtgPersonWithLocks->MetaAddColumn('SysTimestamp');
		}
	}

	// Go ahead and run this form object to generate the page and event handlers, implicitly using
	// person_with_lock_list.tpl.php as the included HTML template file
	PersonWithLockListForm::Run('PersonWithLockListForm');
?>