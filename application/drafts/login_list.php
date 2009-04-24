<?php
	// Load the QCubed Development Framework
	require('../application/configuration/prepend.inc.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do the List All functionality
	 * of the Login class.  It uses the code-generated
	 * LoginDataGrid control which has meta-methods to help with
	 * easily creating/defining Login columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both login_list.php AND
	 * login_list.tpl.php out of this Form Drafts directory.
	 *
	 * @package My Application
	 * @subpackage Drafts
	 */
	class LoginListForm extends QForm {
		// Local instance of the Meta DataGrid to list Logins
		protected $dtgLogins;

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
			$this->dtgLogins = new LoginDataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtgLogins->CssClass = 'datagrid';
			$this->dtgLogins->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgLogins->Paginator = new QPaginator($this->dtgLogins);
			$this->dtgLogins->ItemsPerPage = 20;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$strEditPageUrl = __VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/login_edit.php';
			$this->dtgLogins->MetaAddEditLinkColumn($strEditPageUrl, 'Edit', 'Edit');

			// Create the Other Columns (note that you can use strings for login's properties, or you
			// can traverse down QQN::login() to display fields that are down the hierarchy)
			$this->dtgLogins->MetaAddColumn('Id');
			$this->dtgLogins->MetaAddColumn(QQN::Login()->Person);
			$this->dtgLogins->MetaAddColumn('Username');
			$this->dtgLogins->MetaAddColumn('Password');
			$this->dtgLogins->MetaAddColumn('IsEnabled');
		}
	}

	// Go ahead and run this form object to generate the page and event handlers, implicitly using
	// login_list.tpl.php as the included HTML template file
	LoginListForm::Run('LoginListForm');
?>