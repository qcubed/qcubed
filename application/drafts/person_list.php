<?php
	// Load the QCubed Development Framework
	require('../application/configuration/prepend.inc.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do the List All functionality
	 * of the Person class.  It uses the code-generated
	 * PersonDataGrid control which has meta-methods to help with
	 * easily creating/defining Person columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both person_list.php AND
	 * person_list.tpl.php out of this Form Drafts directory.
	 *
	 * @package My Application
	 * @subpackage Drafts
	 */
	class PersonListForm extends QForm {
		// Local instance of the Meta DataGrid to list People
		protected $dtgPeople;

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
			$this->dtgPeople = new PersonDataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtgPeople->CssClass = 'datagrid';
			$this->dtgPeople->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgPeople->Paginator = new QPaginator($this->dtgPeople);
			$this->dtgPeople->ItemsPerPage = 20;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$strEditPageUrl = __VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/person_edit.php';
			$this->dtgPeople->MetaAddEditLinkColumn($strEditPageUrl, 'Edit', 'Edit');

			// Create the Other Columns (note that you can use strings for person's properties, or you
			// can traverse down QQN::person() to display fields that are down the hierarchy)
			$this->dtgPeople->MetaAddColumn('Id');
			$this->dtgPeople->MetaAddColumn('FirstName');
			$this->dtgPeople->MetaAddColumn('LastName');
			$this->dtgPeople->MetaAddColumn(QQN::Person()->Login);
		}
	}

	// Go ahead and run this form object to generate the page and event handlers, implicitly using
	// person_list.tpl.php as the included HTML template file
	PersonListForm::Run('PersonListForm');
?>