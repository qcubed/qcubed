/////////////////////////////////////
	// ADDITIONAL CLASSES for QCubed QUERY
	/////////////////////////////////////

<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
    /**
     * @uses QQAssociationNode
     *
     * @property-read QQColumnNode $<?= $objReference->OppositePropertyName ?>

     * @property-read QQNode<?= $objReference->VariableType ?> $<?= $objReference->VariableType ?>

     * @property-read QQNode<?= $objReference->VariableType ?> $_ChildTableNode
     **/
	class QQNode<?= $objTable->ClassName ?><?= $objReference->ObjectDescription ?> extends QQAssociationNode {
		protected $strType = QType::Association;
		protected $strName = '<?= strtolower($objReference->ObjectDescription); ?>';

		protected $strTableName = '<?= $objReference->Table ?>';
		protected $strPrimaryKey = '<?= $objReference->Column ?>';
		protected $strClassName = '<?= $objReference->VariableType ?>';
		protected $strPropertyName = '<?= $objReference->ObjectDescription ?>';
		protected $strAlias = '<?= strtolower($objReference->ObjectDescription); ?>';

		public function __get($strName) {
			switch ($strName) {
				case '<?= $objReference->OppositePropertyName ?>':
					return new QQColumnNode('<?= $objReference->OppositeColumn ?>', '<?= $objReference->OppositePropertyName ?>', '<?= $objReference->OppositeDbType ?>', $this);
				case '<?= $objReference->VariableType ?>':
					return new QQNode<?= $objReference->VariableType ?>('<?= $objReference->OppositeColumn ?>', '<?= $objReference->OppositePropertyName ?>', '<?= $objReference->OppositeDbType ?>', $this);
				case '_ChildTableNode':
					return new QQNode<?= $objReference->VariableType ?>('<?= $objReference->OppositeColumn ?>', '<?= $objReference->OppositePropertyName ?>', '<?= $objReference->OppositeDbType ?>', $this);
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

<?php } ?>
    /**
     * @uses QQTableNode
     *
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
     * @property-read QQColumnNode $<?= $objColumn->PropertyName ?>

<?php if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
     * @property-read QQNode<?= $objColumn->Reference->VariableType; ?> $<?= $objColumn->Reference->PropertyName ?>

<?php } ?>
<?php } ?>
     *
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
     * @property-read QQNode<?= $objTable->ClassName ?><?= $objReference->ObjectDescription ?> $<?= $objReference->ObjectDescription ?>

<?php } ?>
     *
<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?>
     * @property-read QQReverseReferenceNode<?= $objReference->VariableType ?> $<?= $objReference->ObjectDescription ?>

<?php } ?>
<?php $objPkColumn = $objTable->PrimaryKeyColumnArray[0]; ?>

     * @property-read QQNode<?php if (($objPkColumn->Reference) && (!$objPkColumn->Reference->IsType)) print $objPkColumn->Reference->VariableType; ?> $_PrimaryKeyNode
     **/
	class QQNode<?= $objTable->ClassName ?> extends QQTableNode {
		protected $strTableName = '<?= $objTable->Name ?>';
		protected $strPrimaryKey = '<?= $objTable->PrimaryKeyColumnArray[0]->Name ?>';
		protected $strClassName = '<?= $objTable->ClassName ?>';

		public function Fields() {
			return [
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
				"<?= $objColumn->Name ?>",
<?php } ?>
			];
		}

		public function PrimaryKeyFields() {
			return [
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
				"<?= $objColumn->Name ?>",
<?php } ?>
			];
		}

		protected function database() {
			return QApplication::$Database[<?= $objCodeGen->DatabaseIndex; ?>];
		}


		public function __get($strName) {
			switch ($strName) {
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
				case '<?= $objColumn->PropertyName ?>':
					return new QQColumnNode('<?= $objColumn->Name ?>', '<?= $objColumn->PropertyName ?>', '<?= $objColumn->DbType ?>', $this);
<?php if ($objColumn->Reference) { ?>
				case '<?= $objColumn->Reference->PropertyName ?>':
					return new QQNode<?= $objColumn->Reference->VariableType; ?>('<?= $objColumn->Name ?>', '<?= $objColumn->Reference->PropertyName ?>', '<?= $objColumn->DbType ?>', $this);
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
				case '<?= $objReference->ObjectDescription ?>':
					return new QQNode<?= $objTable->ClassName ?><?= $objReference->ObjectDescription ?>($this);
<?php } ?><?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?>
				case '<?= $objReference->ObjectDescription ?>':
					return new QQReverseReferenceNode<?= $objReference->VariableType ?>($this, '<?= strtolower($objReference->ObjectDescription); ?>', QType::ReverseReference, '<?= $objReference->Column ?>', '<?= $objReference->ObjectDescription ?>');
<?php } ?><?php $objPkColumn = $objTable->PrimaryKeyColumnArray[0]; ?>

				case '_PrimaryKeyNode':
<?php if (($objPkColumn->Reference) && (!$objPkColumn->Reference->IsType)) {?>
					return new QQNode<?= $objPkColumn->Reference->VariableType; ?>('<?= $objPkColumn->Name ?>', '<?= $objPkColumn->PropertyName ?>', '<?= $objPkColumn->DbType ?>', $this);
<?php } else { ?>
					return new QQColumnNode('<?= $objPkColumn->Name ?>', '<?= $objPkColumn->PropertyName ?>', '<?= $objPkColumn->DbType ?>', $this);
<?php } ?>
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

    /**
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
     * @property-read QQColumnNode $<?= $objColumn->PropertyName ?>

<?php if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
     * @property-read QQNode<?= $objColumn->Reference->VariableType; ?> $<?= $objColumn->Reference->PropertyName ?>

<?php } ?>
<?php } ?>
     *
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
     * @property-read QQNode<?= $objTable->ClassName ?><?= $objReference->ObjectDescription ?> $<?= $objReference->ObjectDescription ?>

<?php } ?>
     *
<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?>
     * @property-read QQReverseReferenceNode<?= $objReference->VariableType ?> $<?= $objReference->ObjectDescription ?>

<?php } ?>
<?php $objPkColumn = $objTable->PrimaryKeyColumnArray[0]; ?>

     * @property-read QQNode<?php if (($objPkColumn->Reference) && (!$objPkColumn->Reference->IsType)) print $objPkColumn->Reference->VariableType; ?> $_PrimaryKeyNode
     **/
	class QQReverseReferenceNode<?= $objTable->ClassName ?> extends QQReverseReferenceNode {
		protected $strTableName = '<?= $objTable->Name ?>';
		protected $strPrimaryKey = '<?= $objTable->PrimaryKeyColumnArray[0]->Name ?>';
		protected $strClassName = '<?= $objTable->ClassName ?>';

		public function Fields() {
			return [
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
				"<?= $objColumn->Name ?>",
<?php } ?>
			];
		}

		public function PrimaryKeyFields() {
			return [
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
				"<?= $objColumn->Name ?>",
<?php } ?>
			];
		}

		public function __get($strName) {
			switch ($strName) {
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
				case '<?= $objColumn->PropertyName ?>':
					return new QQColumnNode('<?= $objColumn->Name ?>', '<?= $objColumn->PropertyName ?>', '<?= $objColumn->DbType ?>', $this);
<?php if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
				case '<?= $objColumn->Reference->PropertyName ?>':
					return new QQNode<?= $objColumn->Reference->VariableType; ?>('<?= $objColumn->Name ?>', '<?= $objColumn->Reference->PropertyName ?>', '<?= $objColumn->DbType ?>', $this);
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
				case '<?= $objReference->ObjectDescription ?>':
					return new QQNode<?= $objTable->ClassName ?><?= $objReference->ObjectDescription ?>($this);
<?php } ?><?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?>
				case '<?= $objReference->ObjectDescription ?>':
					return new QQReverseReferenceNode<?= $objReference->VariableType ?>($this, '<?= strtolower($objReference->ObjectDescription); ?>', QType::ReverseReference, '<?= $objReference->Column ?>', '<?= $objReference->ObjectDescription ?>');
<?php } ?><?php $objPkColumn = $objTable->PrimaryKeyColumnArray[0]; ?>

				case '_PrimaryKeyNode':
<?php if (($objPkColumn->Reference) && (!$objPkColumn->Reference->IsType)) {?>
					return new QQNode<?= $objPkColumn->Reference->VariableType; ?>('<?= $objPkColumn->Name ?>', '<?= $objPkColumn->PropertyName ?>', '<?= $objPkColumn->DbType ?>', $this);
<?php } else { ?>
					return new QQColumnNode('<?= $objPkColumn->Name ?>', '<?= $objPkColumn->PropertyName ?>', '<?= $objPkColumn->DbType ?>', $this);
<?php } ?>
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
