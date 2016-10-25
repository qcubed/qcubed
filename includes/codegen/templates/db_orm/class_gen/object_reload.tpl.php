<?php
	// TODO: Eventually test the $clauses to see if there is a select, and if not, select only valid data back in to the object.
?>
/**
	 * Reload this <?= $objTable->ClassName ?> from the database.
	 * @return void
	 */
	public function Reload($clauses = null) {
		// Make sure we are actually Restored from the database
		if (!$this->__blnRestored)
			throw new QCallerException('Cannot call Reload() on a new, unsaved <?= $objTable->ClassName ?> object.');

		// throw away all previous state of the object
		$this->DeleteFromCache();
		$this->__blnValid = null;
		$this->__blnDirty = null;

		// Reload the Object
		$objReloaded = <?= $objTable->ClassName ?>::Load(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$this-><?= $objColumn->VariableName ?>, <?php } ?><?php GO_BACK(2); ?>, $clauses);

		// Update $this's local variables to match
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php 	if ($objColumn->Identity) { ?>
		$this->__blnValid[self::<?= strtoupper($objColumn->Name) ?>_FIELD] = true;
<?php 	} elseif ($objColumn->Reference) { ?>
		if (isset($objReloaded->__blnValid[self::<?= strtoupper($objColumn->Name) ?>_FIELD])) {
<?php 	 	if ($objColumn->Reference->IsType) { ?>
			$this-><?= $objColumn->VariableName ?> = $objReloaded-><?= $objColumn->VariableName ?>;
<?php 		} else { ?>
			$this-><?= $objColumn->VariableName ?> = $objReloaded-><?= $objColumn->VariableName ?>;
			$this-><?= $objColumn->Reference->VariableName ?> = $objReloaded-><?= $objColumn->Reference->VariableName ?>;
<?php 		}?>
			$this->__blnValid[self::<?= strtoupper($objColumn->Name) ?>_FIELD] = true;
		}
<?php 	}?>
<?php 	if ($objColumn->PrimaryKey) { ?>
		$this->__<?= $objColumn->VariableName ?> = $this-><?= $objColumn->VariableName ?>;
<?php 	} ?>
<?php } ?>
	}