<?php
	/**
	 * This is the abstract Panel class for the List All functionality
	 * of the Project class.  This code-generated class
	 * contains a datagrid to display an HTML page that can
	 * list a collection of Project objects.  It includes
	 * functionality to perform pagination and sorting on columns.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QPanel which extends this ProjectListPanelBase
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent re-
	 * code generation.
	 * 
	 * @package My Application
	 * @subpackage Drafts
	 * 
	 */
	class ProjectListPanel extends QPanel {
		// Local instance of the Meta DataGrid to list Projects
		public $dtgProjects;

		// Other public QControls in this panel
		public $btnCreateNew;
		public $pxyEdit;

		// Callback Method Names
		protected $strSetEditPanelMethod;
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
			$this->Template = 'ProjectListPanel.tpl.php';

			// Instantiate the Meta DataGrid
			$this->dtgProjects = new ProjectDataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtgProjects->CssClass = 'datagrid';
			$this->dtgProjects->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtgProjects->Paginator = new QPaginator($this->dtgProjects);
			$this->dtgProjects->ItemsPerPage = 8;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$this->pxyEdit = new QControlProxy($this);
			$this->pxyEdit->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'pxyEdit_Click'));
			$this->dtgProjects->MetaAddEditProxyColumn($this->pxyEdit, 'Edit', 'Edit');

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

			// Setup the Create New button
			$this->btnCreateNew = new QButton($this);
			$this->btnCreateNew->Text = QApplication::Translate('Create a New') . ' ' . QApplication::Translate('Project');
			$this->btnCreateNew->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnCreateNew_Click'));
		}

		public function pxyEdit_Click($strFormId, $strControlId, $strParameter) {
			$strParameterArray = explode(',', $strParameter);
			$objEditPanel = new ProjectEditPanel($this, $this->strCloseEditPanelMethod, $strParameterArray[0]);

			$strMethodName = $this->strSetEditPanelMethod;
			$this->objForm->$strMethodName($objEditPanel);
		}

		public function btnCreateNew_Click($strFormId, $strControlId, $strParameter) {
			$objEditPanel = new ProjectEditPanel($this, $this->strCloseEditPanelMethod, null);
			$strMethodName = $this->strSetEditPanelMethod;
			$this->objForm->$strMethodName($objEditPanel);
		}
	}
?>