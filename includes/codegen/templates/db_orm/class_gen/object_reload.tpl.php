/**
		 * Reload this <?= $objTable->ClassName ?> from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved <?= $objTable->ClassName ?> object.');

			$this->DeleteCache();

			// Reload the Object
			$objReloaded = <?= $objTable->ClassName ?>::Load(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$this-><?= $objColumn->VariableName ?>, <?php } ?><?php GO_BACK(2); ?>);

			// Update $this's local variables to match
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (!$objColumn->Identity) { ?>
<?php if ($objColumn->Reference) { ?>
			$this-><?= $objColumn->PropertyName ?> = $objReloaded-><?= $objColumn->PropertyName ?>;
<?php } ?><?php if (!$objColumn->Reference) { ?>
			$this-><?= $objColumn->VariableName ?> = $objReloaded-><?= $objColumn->VariableName ?>;
<?php } ?><?php if ($objColumn->PrimaryKey) { ?>
			$this->__<?= $objColumn->VariableName ?> = $this-><?= $objColumn->VariableName ?>;
<?php } ?><?php } ?><?php } ?>
		}