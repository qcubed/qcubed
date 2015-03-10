<?php
	/** @var QTable[] $objTableArray */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __META_CONTROLS_GEN__,
		'TargetFileName' => 'DefaultControlFactory.class.php'
	);
?>
<?php print("<?php\n"); ?>
	class DefaultControlFactory {
<?php
	$references = array();
	foreach ($objTableArray as $objTable) {
		$references[$objTable->ClassName] = 1;
		if ($objTable->ColumnArray) {
			foreach ($objTable->ColumnArray as $objColumn) {
				if ($objColumn->Reference && !$objColumn->Reference->IsType) {
					$objReference = $objColumn->Reference;
					$objReferencedTable = $this->GetTable($objReference->Table);
					$references[$objReferencedTable->ClassName] = 1;
				}
			}
		}
?>
		public function Create<?php echo $objTable->ClassName ?>SearchPanel($objParent) {
			return new <?php echo $objTable->ClassName ?>SearchPanel($objParent);
		}

		public function Create<?php echo $objTable->ClassName ?>DataTablePanel($objParent) {
			return new <?php echo $objTable->ClassName ?>DataTable($objParent);
		}

		public function Create<?php echo $objTable->ClassName ?>DetailPanel($objParent, $obj<?php echo $objTable->ClassName ?>Ref) {
			return new <?php echo $objTable->ClassName ?>ViewWithRelationships($objParent, $obj<?php echo $objTable->ClassName ?>Ref);
		}

		public function Create<?php echo $objTable->ClassName ?>ViewPanel($objParent, $obj<?php echo $objTable->ClassName ?>Ref, $blnShowPk = true) {
			return new <?php echo $objTable->ClassName ?>ViewPanel($objParent, $obj<?php echo $objTable->ClassName ?>Ref, $blnShowPk);
		}

		public function Create<?php echo $objTable->ClassName ?>EditPanel($objParent, $obj<?php echo $objTable->ClassName ?>Ref, $blnShowPk = false) {
			return new <?php echo $objTable->ClassName ?>UpdatePanel($objParent, $obj<?php echo $objTable->ClassName ?>Ref, $blnShowPk);
		}

		public function Create<?php echo $objTable->ClassName ?>ToolbarPanel($objParent, $obj<?php echo $objTable->ClassName ?>Ref, $blnNew, $blnEdit, $blnDelete) {
			return new <?php echo $objTable->ClassName ?>Toolbar($objParent, $obj<?php echo $objTable->ClassName ?>Ref, $blnNew, $blnEdit, $blnDelete);
		}
<?php
}
?>

<?php
	foreach ($references as $refClassName => $_) {
?>
		public function Create<?php echo $refClassName ?>ViewWithToolbarPanel($objParent, $objRef, $blnNew = false, $blnEdit = true, $blnDelete = true, $blnShowPk = true) {
			return new <?php echo $refClassName ?>ViewWithToolbar($objParent, $objRef, $blnNew, $blnEdit, $blnDelete, $blnShowPk);
		}

<?php
	}
?>
	}