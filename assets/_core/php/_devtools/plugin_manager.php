<?php

	require('../../../../includes/configuration/prepend.inc.php');

	class PluginManagerForm extends QForm {
		// Local instance of the Meta DataGrid to list Addresses
		protected $dtgPlugins;
		
		private $objPluginArray;

		protected function Form_Run() {
			QApplication::CheckRemoteAdmin();
		}

		protected function Form_Create() {
			$this->dtgPlugins = new QDataGrid($this);
			$this->dtgPlugins->SetDataBinder('dtgPlugins_Bind');

			$this->dtgPlugins->CssClass = 'datagrid';
			$this->dtgPlugins->AlternateRowStyle->CssClass = 'alternate';
			
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Title',
                    '<a href="plugin_edit.php?strName=<?= $_ITEM->strName ?>"><?= $_ITEM->strName ?></a>', 'HtmlEntities=false'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Files: Controls',
                    '<?= count($_ITEM->objControlFilesArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Files: Misc Includes',
                    '<?= count($_ITEM->objMiscIncludeFilesArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Files: Images',
                    '<?= count($_ITEM->objImageFilesArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Files: CSS',
                    '<?= count($_ITEM->objCssFilesArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Files: JS',
                    '<?= count($_ITEM->objJavascriptFilesArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Files: Examples',
                    '<?= count($_ITEM->objExampleFilesArray) ?>'));			

			$this->dtgPlugins->AddColumn(new QDataGridColumn('Included Classes',
                    '<?= count($_ITEM->objIncludesArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Examples',
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