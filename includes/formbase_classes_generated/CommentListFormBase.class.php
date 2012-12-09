<?php
	/**
	 * This is a quick-and-dirty draft QForm object to do the List All functionality
	 * of the Comment class.  It uses the code-generated
	 * CommentDataGrid control which has meta-methods to help with
	 * easily creating/defining Comment columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both comment_list.php AND
	 * comment_list.tpl.php out of this Form Drafts directory.
	 *
	 * @package My QCubed Application
	 * @subpackage FormBaseObjects
	 */
	abstract class CommentListFormBase extends QForm {
		// Local instance of the Meta DataGrid to list Comments
		/**
		 * @var CommentDataGrid dtgComments
		 */
		protected $dtgComments;

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
			$this->dtgComments = new CommentDataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtgComments->CssClass = 'datagrid';
			$this->dtgComments->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgComments->Paginator = new QPaginator($this->dtgComments);
			$this->dtgComments->ItemsPerPage = __FORM_DRAFTS_FORM_LIST_ITEMS_PER_PAGE__;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$strEditPageUrl = __VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/comment_edit.php';
			$this->dtgComments->MetaAddEditLinkColumn($strEditPageUrl, 'Edit', 'Edit');

			// Create the Other Columns (note that you can use strings for comment's properties, or you
			// can traverse down QQN::comment() to display fields that are down the hierarchy)
			$this->dtgComments->MetaAddColumn('Id');
			$this->dtgComments->MetaAddColumn(QQN::Comment()->Post);
			$this->dtgComments->MetaAddColumn('CommentBody');
		}
	}
?>
