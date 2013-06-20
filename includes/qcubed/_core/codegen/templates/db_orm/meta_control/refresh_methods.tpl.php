		/**
		 * Refresh this MetaControl with Data from the local <?php echo $objTable->ClassName ?> object.
		 * @param boolean $blnReload reload <?php echo $objTable->ClassName ?> from the database
		 * @return void
		 */
		public function Refresh($blnReload = false) {
			if ($blnReload)
				$this-><?php echo $objCodeGen->VariableNameFromTable($objTable->Name); ?>->Reload();

<?php

			foreach ($objTable->ColumnArray as $objColumn) {
				// Use the "control_refresh_" subtemplates to generate the code
				// required to create/setup the control.
				$strObjectName = $objCodeGen->VariableNameFromTable($objTable->Name);
				$strClassName = $objTable->ClassName;
				$strControlId = $objCodeGen->FormControlVariableNameForColumn($objColumn);
				$strLabelId = $objCodeGen->FormLabelVariableNameForColumn($objColumn);

				// Figure out WHICH "control_refresh_" to use
				if ($objColumn->Identity) {
					$strTemplateFilename = 'identity';
				} else if ($objColumn->Timestamp) {
					$strTemplateFilename = 'identity';
				} else if ($objColumn->Reference) {
					if ($objColumn->Reference->IsType)
						$strTemplateFilename = 'type';
					else
						$strTemplateFilename = 'reference';
				} else {
					$strTemplateFilename = $objColumn->VariableType;
				}

				// Get the subtemplate and evaluate
				include(sprintf('control_refresh_%s.tpl.php', $strTemplateFilename));
				echo "\n\n";
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