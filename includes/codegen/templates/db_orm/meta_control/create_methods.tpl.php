<?php
	foreach ($objTable->ColumnArray as $objColumn) {
		if ($objColumn->Options && isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;

		$strControlType = $objCodeGen->MetaControlControlClass($objColumn);
		$objReflection = new ReflectionClass ($strControlType);
		$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaCreate');
		if ($blnHasMethod) {
			echo $strControlType::Codegen_MetaCreate($objCodeGen, $objTable, $objColumn);
		} else {
			throw new QCallerException ('Can\'t find Codegen_MetaCreate for ' . $strControlType);
		}

		if ($strControlType != 'QLabel' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo QLabel::Codegen_MetaCreate($objCodeGen, $objTable, $objColumn);
		}
		echo "\n\n";
	}


	foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
		if (!$objReverseReference->Unique) continue;
		if (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None) continue;

		$strControlType = $objCodeGen->MetaControlControlClass($objReverseReference);
		$objReflection = new ReflectionClass ($strControlType);
		$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaCreate');
		if ($blnHasMethod) {
			echo $strControlType::Codegen_MetaCreate($objCodeGen, $objTable, $objReverseReference);
		} else {
			throw new QCallerException ('Can\'t find Codegen_MetaCreate for ' . $strControlType);
		}

		if ($strControlType != 'QLabel' && (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo QLabel::Codegen_MetaCreate($objCodeGen, $objTable, $objReverseReference);
		}
		echo "\n\n";
	}

	foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
		if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;

		$strControlType = $objCodeGen->MetaControlControlClass($objManyToManyReference);
		$objReflection = new ReflectionClass ($strControlType);
		$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaCreate');
		if ($blnHasMethod) {
			echo $strControlType::Codegen_MetaCreate($objCodeGen, $objTable, $objManyToManyReference);
		} else {
			throw new QCallerException ('Can\'t find Codegen_MetaCreate for ' . $strControlType);
		}

		if ($strControlType != 'QLabel' && (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo QLabel::Codegen_MetaCreate($objCodeGen, $objTable, $objManyToManyReference);
		}
		echo "\n\n";
	}
?>