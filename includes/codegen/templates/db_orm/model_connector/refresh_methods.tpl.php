<?php
	/**
	 * @var QTable $objTable
	 * @var QCodeGenBase $objCodeGen
	 */
?>
		/**
		 * Refresh this ModelConnector with Data from the local <?= $objTable->ClassName ?> object.
		 * @param boolean $blnReload reload <?= $objTable->ClassName ?> from the database
		 * @return void
		 */
		public function Refresh($blnReload = false) {
			if ($blnReload) {
				$this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?>->Reload();
			}
<?php

			foreach ($objTable->ColumnArray as $objColumn) {
				if ($objColumn->Options && isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;

				$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objColumn);
				echo $objControlCodeGenerator->ConnectorRefresh($objCodeGen, $objTable, $objColumn);

				if ($objControlCodeGenerator->GetControlClass() != 'QLabel' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] == QFormGen::Both)) {
					// also generate a QLabel for each control that is not defaulted as a label already
					echo QLabel_CodeGenerator::Instance()->ConnectorRefresh($objCodeGen, $objTable, $objColumn);
				}
				echo "\n\n";
			}
			foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
				if (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None) continue;
				if ($objReverseReference->Unique) {
					$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objReverseReference);
					echo $objControlCodeGenerator->ConnectorRefresh($objCodeGen, $objTable, $objReverseReference);
					if ($objControlCodeGenerator->GetControlClass() != 'QLabel' && (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] == QFormGen::Both)) {
						// also generate a QLabel for each control that is not defaulted as a label already
						echo QLabel_CodeGenerator::Instance()->ConnectorRefresh($objCodeGen, $objTable, $objReverseReference);
					}
					echo "\n\n";
				}
			}
			foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
				if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;

				$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objManyToManyReference);
				echo $objControlCodeGenerator->ConnectorRefresh($objCodeGen, $objTable, $objManyToManyReference);
				if ($objControlCodeGenerator->GetControlClass() != 'QLabel' && (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] == QFormGen::Both)) {
					// also generate a QLabel for each control that is not defaulted as a label already
					echo QLabel_CodeGenerator::Instance()->ConnectorRefresh($objCodeGen, $objTable, $objManyToManyReference);
				}
				echo "\n\n";
			}
?>
		}

<?php
		foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {
			$aStrs[] = '$' . $objColumn->VariableName . ' = null';
		}
?>
		/**
		 * Load this ModelConnector with a new <?= $objTable->ClassName ?> object.
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
		 * @param <?= $objColumn->VariableType ?> $<?= $objColumn->VariableName ?>
<?php } ?>

		 * @param $objClauses
		 * @return void
		 */
		 public function Load(<?= implode (',', $aStrs) ?>, $objClauses = null) {
			if (<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>strlen($<?= $objColumn->VariableName  ?>) && <?php } ?><?php GO_BACK(4); ?>) {
				$this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?> = <?= $objTable->ClassName ?>::Load(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?= $objColumn->VariableName ?>, <?php } ?><?php GO_BACK(2); ?>, $objClauses);
				$this->strTitleVerb = QApplication::Translate('Edit');
				$this->blnEditMode = true;
			}
			else {
				$this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?> = new <?= $objTable->ClassName ?>();
				$this->strTitleVerb = QApplication::Translate('Create');
				$this->blnEditMode = false;
			}
			$this->Refresh ();
		}
		 