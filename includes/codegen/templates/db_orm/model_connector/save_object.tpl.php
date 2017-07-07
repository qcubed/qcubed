<?php
	/**
	 * @var QSqlTable $objTable
	 * @var QCodeGenBase $objCodeGen
	 */
?>
/**
		* This will update this object's <?= $objTable->ClassName; ?> instance,
		* updating only the fields which have had a control created for it.
		*/
		public function Update<?= $objTable->ClassName; ?>() {
			try {
				// Update any fields for controls that have been created
<?php 	foreach ($objTable->ColumnArray as $objColumn) {
			if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;
			$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objColumn);
			echo $objControlCodeGenerator->ConnectorUpdate($objCodeGen, $objTable, $objColumn);
			echo "\n";
		}
?>

				// Update any UniqueReverseReferences for controls that have been created for it
<?php 	foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
			if (!$objReverseReference->Unique) continue;
			if (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None) continue;

			$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objReverseReference);
			echo $objControlCodeGenerator->ConnectorUpdate($objCodeGen, $objTable, $objReverseReference);
			echo "\n";
		}
?>

			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

<?php
    $blnNeedsTransaction = false;
    foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
	    if (isset ($objManyToManyReference->Options['FormGen']) && ($objManyToManyReference->Options['FormGen'] == 'none' || $objManyToManyReference->Options['FormGen'] == 'meta')) continue;
        $blnNeedsTransaction = true;
        break;
    }
?>

		/**
		 * This will save this object's <?= $objTable->ClassName; ?> instance,
		 * updating only the fields which have had a control created for it.
		 */
		public function Save<?= $objTable->ClassName; ?>($blnForceUpdate = false) {
			try {
				$this->Update<?= $objTable->ClassName; ?>();
<?php if ($blnNeedsTransaction) { // no transaction needed ?>
                $objDatabase = <?= $objTable->ClassName; ?>::GetDatabase();
                $objDatabase->TransactionBegin();
<?php } ?>
                $id = $this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?>->Save(false, $blnForceUpdate);

<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
<?php	if (isset ($objManyToManyReference->Options['FormGen']) && ($objManyToManyReference->Options['FormGen'] == 'none' || $objManyToManyReference->Options['FormGen'] == 'meta')) continue; ?>
				$this-><?= $objCodeGen->ModelConnectorVariableName($objManyToManyReference); ?>_Update();
<?php } ?>
<?php if ($blnNeedsTransaction) { ?>
                $objDatabase->TransactionCommit();
<?php } ?>
			} catch (QCallerException $objExc) {
<?php if ($blnNeedsTransaction) { ?>
                $objDatabase->TransactionRollback();
<?php } ?>
				$objExc->IncrementOffset();
				throw $objExc;
			}

			return $id;
		}