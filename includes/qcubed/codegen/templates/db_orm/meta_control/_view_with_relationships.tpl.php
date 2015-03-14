<?php
    /** @var QTable $objTable */
    /** @var QTable[] $objTableArray */
    /** @var QDatabaseCodeGen $objCodeGen */
    global $_TEMPLATE_SETTINGS;
    $_TEMPLATE_SETTINGS = array(
        'OverwriteFlag' => true,
        'DocrootFlag' => false,
        'DirectorySuffix' => '',
        'TargetDirectory' => __META_CONTROLS_GEN__,
        'TargetFileName' => $objTable->ClassName.'ViewWithRelationshipsGen.class.php'
    );
?>
<?php print("<?php\n"); ?>
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>ViewWithToolbar.class.php');
	require_once(__META_CONTROLS_GEN__ . '/AppControlFactory.class.php');
<?php
	if ($objTable->ColumnArray) foreach ($objTable->ColumnArray as $objColumn) {
		if ($objColumn->Reference && !$objColumn->Reference->IsType) {
			$objReference = $objColumn->Reference;
			$objReferencedTable = $this->GetTable($objReference->Table);
?>
	require_once(__META_CONTROLS__ . '/<?php echo $objReferencedTable->ClassName ?>ViewWithToolbar.class.php');
<?php
		}
	}
?>

	/**
	 * @property-read <?php echo $objTable->ClassName ?>Toolbar $Toolbar
	 * @property-read <?php echo $objTable->ClassName ?>ViewWithToolbar $MainView
	 * @property-read <?php echo $objTable->ClassName ?>ViewWithToolbar $<?php echo $objTable->ClassName ?>View
<?php
	if ($objTable->ColumnArray) foreach ($objTable->ColumnArray as $objColumn) {
		if ($objColumn->Reference && !$objColumn->Reference->IsType) {
			$objReference = $objColumn->Reference;
			$objReferencedTable = $this->GetTable($objReference->Table);
?>
	 * @property-read <?php echo $objReferencedTable->ClassName ?>ViewWithToolbar $<?php echo $objReference->PropertyName ?>View
<?php
		}
	}
?>
	 */
	class <?php echo $objTable->ClassName ?>ViewWithRelationshipsGen extends QPanel {
		/** @var <?php echo $objTable->ClassName ?>ViewWithToolbar */
		protected $pnlMainView;
		/** @var QTabs */
		protected $tabs;
<?php
	if ($objTable->ColumnArray) foreach ($objTable->ColumnArray as $objColumn) {
		if ($objColumn->Reference && !$objColumn->Reference->IsType) {
			$objReference = $objColumn->Reference;
			$objReferencedTable = $this->GetTable($objReference->Table);
?>
		/** @var <?php echo $objReferencedTable->ClassName ?>ViewWithToolbar */
		protected $pnl<?php echo $objReference->PropertyName ?>View;
		/** @var integer */
		protected $int<?php echo $objReference->PropertyName ?>TabIdx;
<?php
		}
	}
?>

		public function __construct($objParentObject, $obj<?php echo $objTable->ClassName ?>Ref = null, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$this->AutoRenderChildren = true;
			$this->Reload($obj<?php echo $objTable->ClassName ?>Ref);
		}

		public function Reload($obj<?php echo $objTable->ClassName ?>Ref = null) {
			if ($this->tabs) {
				$this->RemoveChildControl($this->tabs->ControlId, true);
			}
			$this->tabs = new QTabs($this);
			$headers = array();
			$this->pnlMainView = $this->create<?php echo $objTable->ClassName ?>ViewPanel($obj<?php echo $objTable->ClassName ?>Ref, true, true, true, false);
			$mct<?php echo $objTable->ClassName ?> = $this->pnlMainView->MetaControl;
			$obj<?php echo $objTable->ClassName ?> = $mct<?php echo $objTable->ClassName ?> ? $mct<?php echo $objTable->ClassName ?>-><?php echo $objTable->ClassName ?> : null;
			$headers[] = QApplication::Translate('<?php echo $objTable->ClassName ?>');

<?php
	$references = array();
	$references[$objTable->ClassName] = 1;
	if ($objTable->ColumnArray) foreach ($objTable->ColumnArray as $objColumn) {
		if ($objColumn->Reference && !$objColumn->Reference->IsType) {
			$objReference = $objColumn->Reference;
			$objReferencedTable = $this->GetTable($objReference->Table);
			$references[$objReferencedTable->ClassName] = 1;
?>
			if ($obj<?php echo $objTable->ClassName ?> && $this->showTab($obj<?php echo $objTable->ClassName ?>-><?php echo $objReference->PropertyName ?>)) {
				$this->pnl<?php echo $objReference->PropertyName ?>View = $this->create<?php echo $objReferencedTable->ClassName ?>ViewPanel($obj<?php echo $objTable->ClassName ?>-><?php echo $objReference->PropertyName ?>, false, true, false, false);
				$this->int<?php echo $objReference->PropertyName ?>TabIdx = count($headers);
				$headers[] = QApplication::Translate('<?php echo preg_replace('/Object$/', '', $objReference->PropertyName) ?>');
			}
<?php
		}
	}
?>
			$this->tabs->Headers = $headers;
		}

<?php
	foreach ($references as $refClassName => $_) {
?>
		protected function create<?php echo $refClassName ?>ViewPanel($objRef, $blnNew = false, $blnEdit = true, $blnDelete = true, $blnShowPk = true) {
			return AppControlFactory::Inst()->Create<?php echo $refClassName ?>ViewWithToolbarPanel($this->tabs, $objRef, $blnNew, $blnEdit, $blnDelete, $blnShowPk);
		}

<?php
	}
?>
		protected function showTab($objRelated) {
			return $objRelated && $objRelated->__Restored;
		}

		public function __get($strName) {
			switch ($strName) {
				case "Toolbar": return $this->pnlMainView->Toolbar;
				case "Tabs": return $this->tabs;
				case "MainView":
				case "<?php echo $objTable->ClassName ?>View": return $this->pnlMainView;
<?php
	if ($objTable->ColumnArray) foreach ($objTable->ColumnArray as $objColumn) {
		if ($objColumn->Reference && !$objColumn->Reference->IsType) {
			$objReference = $objColumn->Reference;
			$objReferencedTable = $this->GetTable($objReference->Table);
?>
				case "<?php echo $objReference->PropertyName ?>View": return $this->pnl<?php echo $objReference->PropertyName ?>View;
<?php
		}
	}
?>

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
