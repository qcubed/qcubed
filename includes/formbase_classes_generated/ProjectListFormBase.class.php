<?php
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
	 * @package My QCubed Application
	 * @subpackage FormBaseObjects
	 */
	abstract class ProjectListFormBase extends QForm {
		// Local instance of the Meta DataGrid to list Projects
		/**
		 * @var ProjectDataGrid dtgProjects
		 */
		protected $dtgProjects;

		// Create QForm Event Handlers as Needed

//		protected function Form_Exit() {}
//		protected function Form_Load() {}
//		protected function Form_PreRender() {}
//		protected function Form_Validate() {}

		protected function Form_Run() {
			parent::Form_Run();
		}

		protected function Form_Create() {
			parent::Form_Create();

			// Instantiate the Meta DataGrid
			$this->dtgProjects = new ProjectDataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtgProjects->CssClass = 'datagrid';
			$this->dtgProjects->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgProjects->Paginator = new QPaginator($this->dtgProjects);
			$this->dtgProjects->ItemsPerPage = __FORM_DRAFTS_FORM_LIST_ITEMS_PER_PAGE__;

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
?>
