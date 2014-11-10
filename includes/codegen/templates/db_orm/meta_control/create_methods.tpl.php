<?php
	foreach ($objTable->ColumnArray as $objColumn) {
		if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == 'none') continue;
		$strControlType = $objCodeGen->MetaControlControlClass($objColumn);
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
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strClassName = $objTable->ClassName;
			$strControlId = $objCodeGen->MetaControlVariableName($objReverseReference);
			$strLabelId = $objCodeGen->MetaControlLabelVariableName($objReverseReference);
			// Get the subtemplate and evaluate
			include('control_create_unique_reversereference.tpl.php');
			echo "\n\n";
		}
	}

	foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
		if ($objManyToManyReference->IsTypeAssociation) { // temporary until we can do all codegen the new way
			if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == 'none') continue;
			$strControlType = $objCodeGen->MetaControlControlClass($objManyToManyReference);

			if (!isset($objManyToManyReference->Options['FormGen']) ||
				$objManyToManyReference->Options['FormGen'] != 'label') {

				$objReflection = new ReflectionClass ($strControlType);
				$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaRefresh');
				if ($blnHasMethod) {
					echo $strControlType::Codegen_MetaCreate($objCodeGen, $objTable, $objColumn);
				} else {
					throw new QCallerException ('Can\'t find Codegen_MetaCreate for ' . $strControlType);
				}
			}
			if ($strControlType != 'QLabel') {
				// also generate a QLabel for each control that is not defaulted as a label already
				echo QLabel::Codegen_MetaCreate($objCodeGen, $objTable, $objManyToManyReference);
			}

		}
		else {
			// Use the "control_create_manytomany_reference" subtemplate to generate the code
			// required to create/setup the control.
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strClassName = $objTable->ClassName;
			$strControlId = $objCodeGen->MetaControlVariableName($objManyToManyReference);
			$strLabelId = $objCodeGen->MetaControlLabelVariableName($objManyToManyReference);
			// Get the subtemplate and evaluate
			if ($objManyToManyReference->IsTypeAssociation) {
				include("control_create_manytomany_type.tpl.php");
			} else {
				include('control_create_manytomany_reference.tpl.php');
			}
			echo "\n\n";
		}
	}
?>