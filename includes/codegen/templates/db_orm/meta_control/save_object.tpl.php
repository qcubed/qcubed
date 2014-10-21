/**
		* This will update this object's <?= $objTable->ClassName; ?> instance,
		* updating only the fields which have had a control created for it.
		*/
		public function Update<?= $objTable->ClassName; ?>() {
			try {
				// Update any fields for controls that have been created
			<?php foreach ($objTable->ColumnArray as $objColumn) {
				if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == 'none') continue;
				$strControlType = $objCodeGen->FormControlClassForColumn($objColumn);
				if ($strControlType == 'QLabel'  ||
						!isset($objColumn->Options['FormGen']) ||
						$objColumn->Options['FormGen'] != 'label') {

					$objReflection = new ReflectionClass ($strControlType);
					$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaUpdate');
					if ($blnHasMethod) {
						echo $strControlType::Codegen_MetaUpdate($objCodeGen, $objTable, $objColumn);
					} else {
						throw new QCallerException ('Can\'t find Codegen_MetaUpdate for ' . $strControlType);
					}
				}
			} ?>

			// Update any UniqueReverseReferences (if any) for controls that have been created for it
			<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
				if ($objReverseReference->Unique) {
					// Use the "control_update_unique_reversereference" subtemplate to generate the code
					// required to create/setup the control.
					$strObjectName = $objCodeGen->VariableNameFromTable($objTable->Name);
					$strClassName = $objTable->ClassName;
					$strControlId = $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);

					// Get the subtemplate and evaluate
					include('control_update_unique_reversereference.tpl.php');
					echo "\n";
				}
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
				$id = $this-><?= $objCodeGen->VariableNameFromTable($objTable->Name); ?>->Save();

				// Finally, update any ManyToManyReferences (if any)
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
				$this-><?= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); ?>_Update();
<?php } ?>
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			return $id;
		}