<?php

	require('../../../../includes/configuration/prepend.inc.php');

	class PluginManagerForm extends QForm {
		// Local instance of the Meta DataGrid to list Addresses
		protected $dtgPlugins;
		protected $btnNewPlugin;
		protected $dlgUpload;
		
		private $objPluginArray;

		protected function Form_Run() {
			QApplication::CheckRemoteAdmin();
		}

		protected function Form_Create() {
			$this->dtgPlugins_Create();
			$this->btnNewPlugin_Create();
			$this->dlgUpload_Create();
		}
				
		private function btnNewPlugin_Create() {
			$this->btnNewPlugin = new QButton($this);
			$this->btnNewPlugin->Text = "Install a New Plugin";
			$this->btnNewPlugin->AddAction(new QClickEvent(), new QAjaxAction('btnNewPlugin_Click'));
		}
		
		public function btnNewPlugin_Click() {
			$this->dlgUpload->ShowDialogBox();
		}
		
		private function dtgPlugins_Create() {
			$this->dtgPlugins = new QDataGrid($this);
			$this->dtgPlugins->SetDataBinder('dtgPlugins_Bind');

			$this->dtgPlugins->CssClass = 'datagrid';
			$this->dtgPlugins->AlternateRowStyle->CssClass = 'alternate';
			
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Title',
                    '<a href="plugin_edit.php?strType=installed&strName=<?= $_ITEM->strName ?>"><?= $_ITEM->strName ?></a>', 'HtmlEntities=false'));
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
			$this->dtgPlugins->DataSource = QPluginConfigFile::parseInstalledPlugins();
		}
		
        private function dlgUpload_Create() {
            $this->dlgUpload = new QFileAssetDialog($this, 'dlgUpload_done');
            $this->dlgUpload->lblMessage->Text = "Please upload a plugin .zip file";
            $this->dlgUpload->btnUpload->Text = "Upload";
            $this->dlgUpload->btnCancel->Text = "Cancel";
            $this->dlgUpload->SetCustomStyle("background-color", "rgb(238, 255, 221)");
            $this->dlgUpload->SetCustomStyle("padding", "10px");
		}
		
        public function dlgUpload_done($strFormId, $strControlId, $strParameter) {
            $this->dlgUpload->HideDialogBox();
            
            $fileAsset = $this->dlgUpload->flcFileAsset;
            
            if (substr($fileAsset->FileName, -3) != "zip") {
                QApplication::DisplayAlert("Invalid uploaded plugin file type: " . $fileAsset->Type);
				return;
            }
			
			$entropy = substr(md5(uniqid()), 0, 6);                        
            self::extractZip($fileAsset->File, __INCLUDES__ . '/tmp/plugin.tmp/' . $entropy . '/');
			QApplication::Redirect('plugin_edit.php?strType=new&strName=' . $entropy);
        }
				
        /**
         * Extract a ZIP compressed file to a given path
         *
         * @param       string  $archive        Path to ZIP archive to extract
         * @param       string  $destination    Path to extract archive into
         * @return      boolean True if successful
         */
        private static function extractZip($archive, $destination) {
			if ($zip = zip_open($archive)) {
				if ($zip) {
					// Create the destination folder
					if (!mkdir($destination)) {
						QApplication::DisplayAlert("Unable to create extraction destination folder");
						return false;
					}

					// Read files in the archive
					$createdFolders = array();
					while ($file = zip_read($zip)) {
						if (zip_entry_open($zip, $file, "r")) {							
							if (substr(zip_entry_name($file), strlen(zip_entry_name($file)) - 1) != "/") {
								
//								echo zip_entry_name($file) . "<br>";
								
								$folderStack = split("/", zip_entry_name($file));
								if (sizeof($folderStack) > 1) {
									for ($i = 0; $i < sizeof($folderStack) - 1; $i++) {
										$item = $folderStack[$i];
										
										if (!in_array($item, $createdFolders)) {
//											echo "- " . $destination . $item . "<br>";
											$createdFolders[] = $item;
											mkdir($destination . $item);
										}
									}
								}

								
								$buffer = zip_entry_read($file, zip_entry_filesize($file));
								
								

								if (!$handle = fopen($destination . zip_entry_name($file), 'w')) {
									QApplication::DisplayAlert("Cannot open file " . destination . zip_entry_name($file));
									return false;
								}
							   
								// Write $somecontent to our opened file.
								if (fwrite($handle, $buffer) === false) {
									QApplication::DisplayAlert("Unable to write extracted file");
									return false;
								}
								
								zip_entry_close($file);
							}
						} else {
							QApplication::DisplayAlert("Unable to read zip entry");
							return false;
						}
					}
					zip_close($zip);
				}
			} else {
				QApplication::DisplayAlert("Unable to open uploaded archive");
				return false;
			}
			return true;
        }
		
	}	

	PluginManagerForm::Run('PluginManagerForm');
?>