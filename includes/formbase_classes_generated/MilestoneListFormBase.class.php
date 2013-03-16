<?php
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
	 * @package My QCubed Application
	 * @subpackage FormBaseObjects
	 */
	abstract class MilestoneListFormBase extends QForm {
		// Local instance of the Meta DataGrid to list Milestones
		/**
		 * @var MilestoneDataGrid dtgMilestones
		 */
		protected $dtgMilestones;

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
			$this->dtgMilestones = new MilestoneDataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtgMilestones->CssClass = 'datagrid';
			$this->dtgMilestones->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgMilestones->Paginator = new QPaginator($this->dtgMilestones);
			$this->dtgMilestones->ItemsPerPage = __FORM_DRAFTS_FORM_LIST_ITEMS_PER_PAGE__;

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
?>
