		/**
		 * Refresh this MetaControl with Data from the local <?= $objTable->ClassName ?> object.
		 * @param boolean $blnReload reload <?= $objTable->ClassName ?> from the database
		 * @return void
		 */
		public function Refresh($blnReload = false) {
			if ($blnReload)
				$this-><?= $objCodeGen->VariableNameFromTable($objTable->Name); ?>->Reload();

<?php

			foreach ($objTable->ColumnArray as $objColumn) {
				if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == 'none') continue;

				$strControlType = $objCodeGen->FormControlClassForColumn($objColumn);
				if ($strControlType == 'QLabel'  ||
						!isset($objColumn->Options['FormGen']) ||
						$objColumn->Options['FormGen'] != 'label') {

					$objReflection = new ReflectionClass ($strControlType);
					$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaRefresh');
					if ($blnHasMethod) {
						echo $strControlType::Codegen_MetaRefresh($objCodeGen, $objTable, $objColumn);
					} else {
						throw new QCallerException ('Can\'t find Codegen_MetaRefresh for ' . $strControlType);
					}
				}
				if ($strControlType != 'QLabel') {
					// also generate a QLabel for each control that is not defaulted as a label already
					echo QLabel::Codegen_MetaRefresh($objCodeGen, $objTable, $objColumn);
				}
			}
			foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
				if ($objReverseReference->Unique) {
					// Use the "control_refresh_" subtemplates to generate the code
					// required to create/setup the control.
					$strObjectName = $objCodeGen->VariableNameFromTable($objTable->Name);
					$strClassName = $objTable->ClassName;
					$strControlId = $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);
					$strLabelId = $objCodeGen->FormLabelVariableNameForUniqueReverseReference($objReverseReference);
					// Get the subtemplate and evaluate
					include('control_refresh_unique_reversereference.tpl.php');
					echo "\n\n";
				}
			}
			foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
				// Use the "control_refresh_manytomany_reference" subtemplate to generate the code
				// required to create/setup the control.
				$strObjectName = $objCodeGen->VariableNameFromTable($objTable->Name);
				$strClassName = $objTable->ClassName;
				$strControlId = $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);
				$strLabelId = $objCodeGen->FormLabelVariableNameForManyToManyReference($objManyToManyReference);
				// Get the subtemplate and evaluate
				include('control_refresh_manytomany_reference.tpl.php');
				echo "\n\n";
			}
?>
		}
		
		/**
		 * Load this MetaControl with a new <?= $objTable->ClassName ?> object.
		 * @param boolean $blnReload reload <?= $objTable->ClassName ?> from the database
		 * @return void
		 */
		 <?php 
		 	foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { 
		 		$aStrs[] = '$' . $objColumn->VariableName . ' = null';
		 	}
		?>		 
		 public function Load(<?= implode (',', $aStrs) ?>) {
			if (<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>strlen($<?= $objColumn->VariableName ?>) && <?php } ?><?php GO_BACK(4); ?>) {
				$this-><?= $objCodeGen->VariableNameFromTable($objTable->Name); ?> = <?= $objTable->ClassName ?>::Load(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?= $objColumn->VariableName ?>, <?php } ?><?php GO_BACK(2); ?>);
				$this->blnEditMode = true;
			}
			else {
				$this-><?= $objCodeGen->VariableNameFromTable($objTable->Name); ?> = new <?= $objTable->ClassName ?>();
				$this->blnEditMode = false;
			}
			$this->Refresh ();
		}
		 