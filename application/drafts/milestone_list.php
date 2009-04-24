<?php
	// Load the QCubed Development Framework
	require('../application/configuration/prepend.inc.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do the List All functionality
	 * of the Milestone class.  It uses the code-generated
	 * MilestoneDataGrid control which has meta-methods to help with
	 * easily creating/defining Milestone columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both milestone_list.php AND
	 * milestone_list.tpl.php out of this Form Drafts directory.
	 *
	 * @package My Application
	 * @subpackage Drafts
	 */
	class MilestoneListForm extends QForm {
		// Local instance of the Meta DataGrid to list Milestones
		protected $dtgMilestones;

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
			$this->dtgMilestones = new MilestoneDataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtgMilestones->CssClass = 'datagrid';
			$this->dtgMilestones->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgMilestones->Paginator = new QPaginator($this->dtgMilestones);
			$this->dtgMilestones->ItemsPerPage = 20;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$strEditPageUrl = __VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/milestone_edit.php';
			$this->dtgMilestones->MetaAddEditLinkColumn($strEditPageUrl, 'Edit', 'Edit');

			// Create the Other Columns (note that you can use strings for milestone's properties, or you
			// can traverse down QQN::milestone() to display fields that are down the hierarchy)
			$this->dtgMilestones->MetaAddColumn('Id');
			$this->dtgMilestones->MetaAddColumn(QQN::Milestone()->Project);
			$this->dtgMilestones->MetaAddColumn('Name');
		}
	}

	// Go ahead and run this form object to generate the page and event handlers, implicitly using
	// milestone_list.tpl.php as the included HTML template file
	MilestoneListForm::Run('MilestoneListForm');
?>