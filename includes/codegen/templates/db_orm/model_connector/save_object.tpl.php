<?php
	/**
	 * @var QTable $objTable
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


		/**
		 * This will save this object's <?= $objTable->ClassName; ?> instance,
		 * updating only the fields which have had a control created for it.
		 */
		public function Save<?= $objTable->ClassName; ?>() {
			try {
				$this->Update<?= $objTable->ClassName; ?>();

				// Save the <?= $objTable->ClassName; ?> object
				$id = $this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?>->Save();

				// Finally, update any ManyToManyReferences (if any)
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
				$this-><?= $objCodeGen->ModelConnectorVariableName($objManyToManyReference); ?>_Update();
<?php } ?>
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			return $id;
		}