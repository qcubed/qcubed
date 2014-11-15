		/**
		 * Refresh this MetaControl with Data from the local <?= $objTable->ClassName ?> object.
		 * @param boolean $blnReload reload <?= $objTable->ClassName ?> from the database
		 * @return void
		 */
		public function Refresh($blnReload = false) {
			if ($blnReload)
				$this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?>->Reload();

<?php

			foreach ($objTable->ColumnArray as $objColumn) {
				if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;

				$strControlType = $objCodeGen->MetaControlControlClass($objColumn);
				$objReflection = new ReflectionClass ($strControlType);
				$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaRefresh');
				if ($blnHasMethod) {
					echo $strControlType::Codegen_MetaRefresh($objCodeGen, $objTable, $objColumn);
				} else {
					throw new QCallerException ('Can\'t find Codegen_MetaRefresh for ' . $strControlType);
				}

				if ($strControlType != 'QLabel' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] == QFormGen::Both)) {
					// also generate a QLabel for each control that is not defaulted as a label already
					echo QLabel::Codegen_MetaRefresh($objCodeGen, $objTable, $objColumn);
				}
				echo "\n\n";
			}
			foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
				if (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None) continue;
				if ($objReverseReference->Unique) {
					$strControlType = $objCodeGen->MetaControlControlClass($objReverseReference);
					$objReflection = new ReflectionClass ($strControlType);
					$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaRefresh');
					if ($blnHasMethod) {
						echo $strControlType::Codegen_MetaRefresh($objCodeGen, $objTable, $objReverseReference);
					} else {
						throw new QCallerException ('Can\'t find Codegen_MetaRefresh for ' . $strControlType);
					}
					if ($strControlType != 'QLabel' && (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] == QFormGen::Both)) {
						// also generate a QLabel for each control that is not defaulted as a label already
						echo QLabel::Codegen_MetaRefresh($objCodeGen, $objTable, $objReverseReference);
					}
					echo "\n\n";
				}
			}
			foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
				if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;

				$strControlType = $objCodeGen->MetaControlControlClass($objManyToManyReference);
				$objReflection = new ReflectionClass ($strControlType);
				$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaRefresh');
				if ($blnHasMethod) {
					echo $strControlType::Codegen_MetaRefresh($objCodeGen, $objTable, $objManyToManyReference);
				} else {
					throw new QCallerException ('Can\'t find Codegen_MetaRefresh for ' . $strControlType);
				}
				if ($strControlType != 'QLabel' && (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] == QFormGen::Both)) {
					// also generate a QLabel for each control that is not defaulted as a label already
					echo QLabel::Codegen_MetaRefresh($objCodeGen, $objTable, $objManyToManyReference);
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
		 * Load this MetaControl with a new <?= $objTable->ClassName ?> object.
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
		 * @param <?= $objColumn->VariableType ?> $<?= $objColumn->VariableName ?>
<?php } ?>

		 * @return void
		 */
		 public function Load(<?= implode (',', $aStrs) ?>) {
			if (<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>strlen($<?= $objColumn->VariableName  ?>) && <?php } ?><?php GO_BACK(4); ?>) {
				$this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?> = <?= $objTable->ClassName ?>::Load(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?= $objColumn->VariableName ?>, <?php } ?><?php GO_BACK(2); ?>);
				$this->blnEditMode = true;
			}
			else {
				$this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?> = new <?= $objTable->ClassName ?>();
				$this->blnEditMode = false;
			}
			$this->Refresh ();
		}
		 