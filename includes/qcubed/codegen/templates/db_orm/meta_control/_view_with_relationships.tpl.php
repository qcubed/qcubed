<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS_GEN__ ?>" TargetFileName="<?php echo $objTable->ClassName ?>ViewWithRelationshipsGen.class.php"/>
<?php print("<?php\n"); ?>
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>ViewWithToolbar.class.php');
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
			$this->pnlMainView = new <?php echo $objTable->ClassName ?>ViewWithToolbar($this->tabs, $obj<?php echo $objTable->ClassName ?>Ref, true, true, true, false);
			$mct<?php echo $objTable->ClassName ?> = $this->pnlMainView->MetaControl;
			$obj<?php echo $objTable->ClassName ?> = $mct<?php echo $objTable->ClassName ?> ? $mct<?php echo $objTable->ClassName ?>-><?php echo $objTable->ClassName ?> : null;
			$headers[] = QApplication::Translate('<?php echo $objTable->ClassName ?>');

<?php
	if ($objTable->ColumnArray) foreach ($objTable->ColumnArray as $objColumn) {
		if ($objColumn->Reference && !$objColumn->Reference->IsType) {
			$objReference = $objColumn->Reference;
			$objReferencedTable = $this->GetTable($objReference->Table);
?>
			if ($obj<?php echo $objTable->ClassName ?> && $obj<?php echo $objTable->ClassName ?>-><?php echo $objColumn->Reference->PropertyName ?> && $obj<?php echo $objTable->ClassName ?>-><?php echo $objColumn->Reference->PropertyName ?>->__Restored) {
				$this->pnl<?php echo $objReference->PropertyName ?>View = new <?php echo $objReferencedTable->ClassName ?>ViewWithToolbar($this->tabs, $obj<?php echo $objTable->ClassName ?>-><?php echo $objColumn->Reference->PropertyName ?>, false, true, false, false);
				$this->int<?php echo $objReference->PropertyName ?>TabIdx = count($headers);
				$headers[] = QApplication::Translate('<?php echo $objReference->PropertyName ?>');
			}
<?php
		}
	}
?>
			$this->tabs->Headers = $headers;
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
