/**
		 * This will save this object's <?php echo $objTable->ClassName; ?> instance,
		 * updating only the fields which have had a control created for it.
		 */
		public function Save<?php echo $objTable->ClassName; ?>() {
			try {
				// Update any fields for controls that have been created
<?php foreach ($objTable->ColumnArray as $objColumn) {
	if ((!$objColumn->Identity) && (!$objColumn->Timestamp)) {
		// Use the "control_create_" subtemplates to generate the code
		// required to create/setup the control.
		$strObjectName = $objCodeGen->VariableNameFromTable($objTable->Name);
		$strClassName = $objTable->ClassName;
		$strControlId = $objCodeGen->FormControlVariableNameForColumn($objColumn);

		// Figure out WHICH "control_create_" to use
		if ($objColumn->Reference) {
			if ($objColumn->Reference->IsType)
				$strTemplateFilename = 'type';
			else
				$strTemplateFilename = 'reference';
		} else switch ($objColumn->VariableType) {
			case QType::Boolean:
				$strTemplateFilename = 'checkbox';
				break;
			case QType::DateTime:
				$strTemplateFilename = 'calendar';
				break;
			default:
				$strTemplateFilename = 'textbox';
				break;
		}

		// Get the subtemplate and evaluate
		include(sprintf('control_update_%s.tpl.php', $strTemplateFilename));
		echo "\n";
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

				// Save the <?php echo $objTable->ClassName; ?> object
				$this-><?php echo $objCodeGen->VariableNameFromTable($objTable->Name); ?>->Save();

				// Finally, update any ManyToManyReferences (if any)
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
				$this-><?php echo $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); ?>_Update();
<?php } ?>
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}