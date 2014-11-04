<?php
	foreach ($objTable->ColumnArray as $objColumn) {
		if ($objColumn->Options && isset ($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == 'none') continue;
		$strControlType = $objCodeGen->FormControlClassForColumn($objColumn);
		if ($strControlType == 'QLabel'  ||
				!isset($objColumn->Options['FormGen']) ||
				$objColumn->Options['FormGen'] != 'label') {

			$objReflection = new ReflectionClass ($strControlType);
			$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaCreate');
			if ($blnHasMethod) {
				echo $strControlType::Codegen_MetaCreate($objCodeGen, $objTable, $objColumn);
			} else {
				throw new QCallerException ('Can\'t find Codegen_MetaCreate for ' . $strControlType);
			}
		}

		if ($strControlType != 'QLabel') {
			// also generate a QLabel for each control that is not defaulted as a label already
			echo QLabel::Codegen_MetaCreate($objCodeGen, $objTable, $objColumn);
		}
	}


	foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
		if ($objReverseReference->Unique) {
			// Use the "control_create_" subtemplates to generate the code
			// required to create/setup the control.
			$strObjectName = $objCodeGen->VariableNameFromTable($objTable->Name);
			$strClassName = $objTable->ClassName;
			$strControlId = $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);
			$strLabelId = $objCodeGen->FormLabelVariableNameForUniqueReverseReference($objReverseReference);
			// Get the subtemplate and evaluate
			include('control_create_unique_reversereference.tpl.php');
			echo "\n\n";
		}
	}

	foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
		// Use the "control_create_manytomany_reference" subtemplate to generate the code
		// required to create/setup the control.
		$strObjectName = $objCodeGen->VariableNameFromTable($objTable->Name);
		$strClassName = $objTable->ClassName;
		$strControlId = $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);
		$strLabelId = $objCodeGen->FormLabelVariableNameForManyToManyReference($objManyToManyReference);
		// Get the subtemplate and evaluate
		if ($objManyToManyReference->IsTypeAssociation) {
        	include("control_create_manytomany_type.tpl.php");
		} else {
			include('control_create_manytomany_reference.tpl.php');
		}
		echo "\n\n";
	}
?>