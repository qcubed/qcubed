<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS_GEN__ ?>" TargetFileName="<?php echo $objTable->ClassName ?>ListDetailViewGen.class.php"/>
<?php print("<?php\n"); ?>
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>DataTable.class.php');
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>ViewWithRelationships.class.php');
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>SearchPanel.class.php');

	/**
	 * @property-read <?php echo $objTable->ClassName ?>DataTable $DataTable
	 * @property-read <?php echo $objTable->ClassName ?>ViewWithRelationships $DetailPanel
	 * @property-read <?php echo $objTable->ClassName ?>SearchPanel $SearchPanel
	 */
	class <?php echo $objTable->ClassName ?>ListDetailViewGen extends QPanel {
		/** @var <?php echo $objTable->ClassName ?>DataTable */
		protected $tbl<?php echo $objTable->ClassNamePlural ?>;
		/** @var QPanel */
		protected $pnlMain;
		/** @var <?php echo $objTable->ClassName ?>ViewWithRelationships */
		protected $pnlDetail;
		/** @var <?php echo $objTable->ClassName ?>SearchPanel */
		protected $pnlSearch;

		public function __construct($objParentObject, $strControlId = null) {
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$this->AutoRenderChildren = true;
			$this->CssClass = "list_detail";

			$this->pnlSearch = new <?php echo $objTable->ClassName ?>SearchPanel($this);
			$this->pnlSearch->SearchCallback = new QMethodCallback($this, 'searchCallback');

			$this->tbl<?php echo $objTable->ClassNamePlural ?> = new <?php echo $objTable->ClassName ?>DataTable(new QDiv($this, 'list_panel'));
			$this->tbl<?php echo $objTable->ClassNamePlural ?>->Language = array("sEmptyTable" => QApplication::Translate('No <?php echo $objTable->ClassNamePlural ?>'));

			$this->tbl<?php echo $objTable->ClassNamePlural ?>->AddAction(new QDataTable_RowClickEvent(), new QAjaxControlAction($this, "tableRow_Click"));
			$this->pnlMain = new QDiv($this, 'detail_panel');

			$this->ReloadDetail();
		}

		/**
		 * @param string[] $arrColumnsToRemove
		 */
		public function RemoveColumns($arrColumnsToRemove) {
			if ($arrColumnsToRemove) foreach ($arrColumnsToRemove as $col) {
				$this->tbl<?php echo $objTable->ClassNamePlural ?>->RemoveColumnByName(QApplication::Translate($col));
			}
		}

		public function searchCallback(QQCondition $objSearchCondition) {
			// note: this will also mark $this->tbl<?php echo $objTable->ClassNamePlural ?> as modified and thus trigger a refresh of the table
			$this->tbl<?php echo $objTable->ClassNamePlural ?>->ExtraCondition = $objSearchCondition;
		}

		public function tableRow_Click($strFormId, $strControlId, $strParameter) {
			if (!$strParameter) {
				return;
			}
			$obj<?php echo $objTable->ClassName ?> = $this->tbl<?php echo $objTable->ClassNamePlural ?>->LoadObjectFromRowData($strParameter);
			$this->ReloadDetail($obj<?php echo $objTable->ClassName ?>);
		}

		public function ReloadDetailAndRefreshList($obj<?php echo $objTable->ClassName ?> = null) {
			$this->ReloadDetail($obj<?php echo $objTable->ClassName ?>);
			$this->tbl<?php echo $objTable->ClassNamePlural ?>->Refresh();
		}

		/**
		 * @param <?php echo $objTable->ClassName ?> $obj<?php echo $objTable->ClassName ?>

		 * @return <?php echo $objTable->ClassName ?>ViewWithRelationships
		 */
		public function ReloadDetail($obj<?php echo $objTable->ClassName ?> = null) {
			$this->pnlMain->RemoveChildControls(true);
			$this->pnlDetail = $this->CreateDetailPanel($obj<?php echo $objTable->ClassName ?>);
			$this->pnlDetail->Toolbar->LoadCallback = new QMethodCallback($this, 'ReloadDetailAndRefreshList');
			$this->pnlDetail->Toolbar->CreateCallback = new QMethodCallback($this, 'ReloadDetailAndRefreshList');
			$this->pnlDetail->Toolbar->EditCallback = new QMethodCallback($this, 'ReloadDetailAndRefreshList', $obj<?php echo $objTable->ClassName ?>);
			$this->pnlDetail->Toolbar->DeleteCallback = new QMethodCallback($this, 'ReloadDetailAndRefreshList');
			return $this->pnlDetail;
		}

		/**
		 * @param <?php echo $objTable->ClassName ?> $obj<?php echo $objTable->ClassName ?>

		 * @return <?php echo $objTable->ClassName ?>ViewWithRelationships
		 */
		protected function CreateDetailPanel($obj<?php echo $objTable->ClassName ?>) {
			return new <?php echo $objTable->ClassName ?>ViewWithRelationships($this->pnlMain, $obj<?php echo $objTable->ClassName ?>);
		}

		public function __get($strName) {
			switch ($strName) {
				case "DataTable": return $this->tbl<?php echo $objTable->ClassNamePlural ?>;
				case "DetailPanel": return $this->pnlDetail;
				case "SearchPanel": return $this->pnlSearch;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
