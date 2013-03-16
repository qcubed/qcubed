<?php
	/**
	 * This is the abstract Panel class for the List All functionality
	 * of the PersonWithLock class.  This code-generated class
	 * contains a datagrid to display an HTML page that can
	 * list a collection of PersonWithLock objects.  It includes
	 * functionality to perform pagination and sorting on columns.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QPanel which extends this PersonWithLockListPanelBase
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent re-
	 * code generation.
	 *
	 * @package My QCubed Application
	 * @subpackage Drafts
	 *
	 */
	class PersonWithLockListPanel extends QPanel {
		// Local instance of the Meta DataGrid to list PersonWithLocks
		/**
		 * @var PersonWithLockDataGrid
		 */
		public $dtgPersonWithLocks;

		// Other public QControls in this panel
		/**
		 * @var QButton CreateNew
		 */
		public $btnCreateNew;
		/**
		 * @var QControlProxy ProxyEdit
		 */
		public $pxyEdit;

		// Callback Method Names
		/**
		 * @var string SetEditPanelMethod
		 */
		protected $strSetEditPanelMethod;
		/**
		 * @var string CloseEditPanelMethod
		 */
		protected $strCloseEditPanelMethod;

		public function __construct($objParentObject, $strSetEditPanelMethod, $strCloseEditPanelMethod, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Record Method Callbacks
			$this->strSetEditPanelMethod = $strSetEditPanelMethod;
			$this->strCloseEditPanelMethod = $strCloseEditPanelMethod;

			// Setup the Template
			$this->Template = __DOCROOT__ . __PANEL_DRAFTS__ . '/PersonWithLockListPanel.tpl.php';

			// Instantiate the Meta DataGrid
			$this->dtgPersonWithLocks = new PersonWithLockDataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtgPersonWithLocks->CssClass = 'datagrid';
			$this->dtgPersonWithLocks->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgPersonWithLocks->Paginator = new QPaginator($this->dtgPersonWithLocks);
			$this->dtgPersonWithLocks->ItemsPerPage = __FORM_DRAFTS_PANEL_LIST_ITEMS_PER_PAGE__;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$this->pxyEdit = new QControlProxy($this);
			$this->pxyEdit->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'pxyEdit_Click'));
			$this->dtgPersonWithLocks->MetaAddEditProxyColumn($this->pxyEdit, 'Edit', 'Edit');

			// Create the Other Columns (note that you can use strings for person_with_lock's properties, or you
			// can traverse down QQN::person_with_lock() to display fields that are down the hierarchy)
			$this->dtgPersonWithLocks->MetaAddColumn('Id');
			$this->dtgPersonWithLocks->MetaAddColumn('FirstName');
			$this->dtgPersonWithLocks->MetaAddColumn('LastName');
			$this->dtgPersonWithLocks->MetaAddColumn('SysTimestamp');

			// Setup the Create New button
			$this->btnCreateNew = new QButton($this);
			$this->btnCreateNew->Text = QApplication::Translate('Create a New') . ' ' . QApplication::Translate('PersonWithLock');
			$this->btnCreateNew->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnCreateNew_Click'));
		}

		public function pxyEdit_Click($strFormId, $strControlId, $strParameter) {
			$strParameterArray = explode(',', $strParameter);
			$objEditPanel = new PersonWithLockEditPanel($this, $this->strCloseEditPanelMethod, $strParameterArray[0]);

			$strMethodName = $this->strSetEditPanelMethod;
			$this->objForm->$strMethodName($objEditPanel);
		}

		public function btnCreateNew_Click($strFormId, $strControlId, $strParameter) {
			$objEditPanel = new PersonWithLockEditPanel($this, $this->strCloseEditPanelMethod, null);
			$strMethodName = $this->strSetEditPanelMethod;
			$this->objForm->$strMethodName($objEditPanel);
		}
	}
?>