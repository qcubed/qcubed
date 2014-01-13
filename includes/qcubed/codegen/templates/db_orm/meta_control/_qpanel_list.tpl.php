<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS_GEN__  ?>" TargetFileName="<?php echo $objTable->ClassName  ?>ListPanelGen.class.php"/>
<?php print("<?php\n"); ?>
	/**
	 * This is the abstract Panel class for the List All functionality
	 * of the <?php echo $objTable->ClassName  ?> class.  This code-generated class
	 * contains a datagrid to display an HTML page that can
	 * list a collection of <?php echo $objTable->ClassName  ?> objects.  It includes
	 * functionality to perform pagination and sorting on columns.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QPanel which extends this <?php echo $objTable->ClassName  ?>ListPanelBase
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent re-
	 * code generation.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage MetaControls
	 *
	 */
	class <?php echo $objTable->ClassName  ?>ListPanelGen extends QPanel {
		// Local instance of the Meta DataGrid to list <?php echo $objTable->ClassNamePlural  ?>

		/**
		 * @var <?php echo $objTable->ClassName  ?>DataGrid
		 */
		public $dtg<?php echo $objTable->ClassNamePlural  ?>;

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
			$this->Template = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>ListPanel.tpl.php';

			// Instantiate the Meta DataGrid
			$this->dtg<?php echo $objTable->ClassNamePlural  ?> = new <?php echo $objTable->ClassName  ?>DataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtg<?php echo $objTable->ClassNamePlural  ?>->CssClass = 'datagrid';
			$this->dtg<?php echo $objTable->ClassNamePlural  ?>->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtg<?php echo $objTable->ClassNamePlural  ?>->Paginator = new QPaginator($this->dtg<?php echo $objTable->ClassNamePlural  ?>);
			$this->dtg<?php echo $objTable->ClassNamePlural  ?>->ItemsPerPage = __FORM_DRAFTS_PANEL_LIST_ITEMS_PER_PAGE__;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$this->pxyEdit = new QControlProxy($this);
			$this->pxyEdit->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'pxyEdit_Click'));
			$this->dtg<?php echo $objTable->ClassNamePlural  ?>->MetaAddEditProxyColumn($this->pxyEdit, 'Edit', 'Edit');

			// Create the Other Columns (note that you can use strings for <?php echo $objTable->Name  ?>'s properties, or you
			// can traverse down QQN::<?php echo $objTable->Name  ?>() to display fields that are down the hierarchy)
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (!$objColumn->Reference) { ?>
			$this->dtg<?php echo $objTable->ClassNamePlural  ?>->MetaAddColumn('<?php echo $objColumn->PropertyName  ?>');
<?php } ?>
<?php if ($objColumn->Reference && $objColumn->Reference->IsType) { ?>
			$this->dtg<?php echo $objTable->ClassNamePlural  ?>->MetaAddTypeColumn('<?php echo $objColumn->PropertyName  ?>', '<?php echo $objColumn->Reference->VariableType  ?>');
<?php } ?>
<?php if ($objColumn->Reference && !$objColumn->Reference->IsType) { ?>
			$this->dtg<?php echo $objTable->ClassNamePlural  ?>->MetaAddColumn(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objColumn->Reference->PropertyName  ?>);
<?php } ?>
<?php } ?><?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?><?php if ($objReverseReference->Unique) { ?>
			$this->dtg<?php echo $objTable->ClassNamePlural  ?>->MetaAddColumn(QQN::<?php echo $objTable->ClassName;  ?>()-><?php echo $objReverseReference->ObjectDescription  ?>);
<?php } ?><?php } ?>

			// Setup the Create New button
			$this->btnCreateNew = new QButton($this);
			$this->btnCreateNew->Text = QApplication::Translate('Create a New') . ' ' . QApplication::Translate('<?php echo $objTable->ClassName ?>');
			$this->btnCreateNew->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnCreateNew_Click'));
		}

		public function pxyEdit_Click($strFormId, $strControlId, $strParameter) {
			$strParameterArray = explode(',', $strParameter);
			$objEditPanel = new <?php echo $objTable->ClassName  ?>EditPanel($this, $this->strCloseEditPanelMethod<?php $strParameterList = ''; for ($intIndex = 0; $intIndex < count ($objTable->PrimaryKeyColumnArray); $intIndex++) $strParameterList .= ', $strParameterArray[' . $intIndex . ']'; print $strParameterList; ?>);

			$strMethodName = $this->strSetEditPanelMethod;
			$this->objForm->$strMethodName($objEditPanel);
		}

		public function btnCreateNew_Click($strFormId, $strControlId, $strParameter) {
			$objEditPanel = new <?php echo $objTable->ClassName  ?>EditPanel($this, $this->strCloseEditPanelMethod, null);
			$strMethodName = $this->strSetEditPanelMethod;
			$this->objForm->$strMethodName($objEditPanel);
		}
	}
?>
