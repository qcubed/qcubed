<?php

	require('../../../../includes/configuration/prepend.inc.php');

	class PluginManagerForm extends QForm {
		// Local instance of the Meta DataGrid to list Addresses
		protected $dtgPlugins;
		
		private $objPluginArray;

		protected function Form_Run() {
			// Security check for ALLOW_REMOTE_ADMIN
			// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
			QApplication::CheckRemoteAdmin();
		}

		protected function Form_Create() {
			$this->dtgPlugins = new QDataGrid($this);
			$this->dtgPlugins->SetDataBinder('dtgPlugins_Bind');

			$this->dtgPlugins->CssClass = 'datagrid';
			$this->dtgPlugins->AlternateRowStyle->CssClass = 'alternate';
			
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Title',
                    '<a href="plugin_edit.php?strName=<?= $_ITEM->strName ?>"><?= $_ITEM->strName ?></a>', 'HtmlEntities=false'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Controls',
                    '<?= count($_ITEM->objControlsArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Misc Includes',
                    '<?= count($_ITEM->objMiscIncludesArray) ?>'));

			$this->dtgPlugins->AddColumn(new QDataGridColumn('Images',
                    '<?= count($_ITEM->objImagesArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('CSS',
                    '<?= count($_ITEM->objCssArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('JS',
                    '<?= count($_ITEM->objJavascriptArray) ?>'));

			$this->dtgPlugins->AddColumn(new QDataGridColumn('Example Files',
                    '<?= count($_ITEM->objExamplesArray) ?>'));

			$this->dtgPlugins->AddColumn(new QDataGridColumn('Description',
                    '<?= $_ITEM->strDescription ?>'));
		}
		
		public function dtgPlugins_Bind() {			
			$this->dtgPlugins->DataSource = QPluginConfigFile::parse();
		}
	}	

	PluginManagerForm::Run('PluginManagerForm');
?>