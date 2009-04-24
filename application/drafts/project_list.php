<?php
	// Load the QCubed Development Framework
	require('../application/configuration/prepend.inc.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do the List All functionality
	 * of the Project class.  It uses the code-generated
	 * ProjectDataGrid control which has meta-methods to help with
	 * easily creating/defining Project columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both project_list.php AND
	 * project_list.tpl.php out of this Form Drafts directory.
	 *
	 * @package My Application
	 * @subpackage Drafts
	 */
	class ProjectListForm extends QForm {
		// Local instance of the Meta DataGrid to list Projects
		protected $dtgProjects;

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
			$this->dtgProjects = new ProjectDataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtgProjects->CssClass = 'datagrid';
			$this->dtgProjects->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgProjects->Paginator = new QPaginator($this->dtgProjects);
			$this->dtgProjects->ItemsPerPage = 20;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$strEditPageUrl = __VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/project_edit.php';
			$this->dtgProjects->MetaAddEditLinkColumn($strEditPageUrl, 'Edit', 'Edit');

			// Create the Other Columns (note that you can use strings for project's properties, or you
			// can traverse down QQN::project() to display fields that are down the hierarchy)
			$this->dtgProjects->MetaAddColumn('Id');
			$this->dtgProjects->MetaAddTypeColumn('ProjectStatusTypeId', 'ProjectStatusType');
			$this->dtgProjects->MetaAddColumn(QQN::Project()->ManagerPerson);
			$this->dtgProjects->MetaAddColumn('Name');
			$this->dtgProjects->MetaAddColumn('Description');
			$this->dtgProjects->MetaAddColumn('StartDate');
			$this->dtgProjects->MetaAddColumn('EndDate');
			$this->dtgProjects->MetaAddColumn('Budget');
			$this->dtgProjects->MetaAddColumn('Spent');
		}
	}

	// Go ahead and run this form object to generate the page and event handlers, implicitly using
	// project_list.tpl.php as the included HTML template file
	ProjectListForm::Run('ProjectListForm');
?>