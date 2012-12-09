<?php
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
	 * @subpackage FormBaseObjects
	 */
	abstract class PostListFormBase extends QForm {
		// Local instance of the Meta DataGrid to list Posts
		/**
		 * @var PostDataGrid dtgPosts
		 */
		protected $dtgPosts;

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
			$this->dtgPosts = new PostDataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtgPosts->CssClass = 'datagrid';
			$this->dtgPosts->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgPosts->Paginator = new QPaginator($this->dtgPosts);
			$this->dtgPosts->ItemsPerPage = __FORM_DRAFTS_FORM_LIST_ITEMS_PER_PAGE__;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$strEditPageUrl = __VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/post_edit.php';
			$this->dtgPosts->MetaAddEditLinkColumn($strEditPageUrl, 'Edit', 'Edit');

			// Create the Other Columns (note that you can use strings for post's properties, or you
			// can traverse down QQN::post() to display fields that are down the hierarchy)
			$this->dtgPosts->MetaAddColumn('Id');
			$this->dtgPosts->MetaAddColumn('Title');
			$this->dtgPosts->MetaAddColumn('Body');
		}
	}
?>
