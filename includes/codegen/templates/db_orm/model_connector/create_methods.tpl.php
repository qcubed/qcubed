<?php
	foreach ($objTable->ColumnArray as $objColumn) {
		if ($objColumn->Options && isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;

		$strControlType = $objCodeGen->ModelConnectorControlClass($objColumn);
		$objReflection = new ReflectionClass ($strControlType);
		$blnHasMethod = $objReflection->hasMethod ('Codegen_ConnectorCreate');
		if ($blnHasMethod) {
			echo $strControlType::Codegen_ConnectorCreate($objCodeGen, $objTable, $objColumn);
		} else {
			throw new QCallerException ('Can\'t find Codegen_ConnectorCreate for ' . $strControlType);
		}

		if ($strControlType != 'QLabel' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo QLabel::Codegen_ConnectorCreate($objCodeGen, $objTable, $objColumn);
		}
		echo "\n\n";
	}


	foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
		if (!$objReverseReference->Unique) continue;
		if (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None) continue;

		$strControlType = $objCodeGen->ModelConnectorControlClass($objReverseReference);
		$objReflection = new ReflectionClass ($strControlType);
		$blnHasMethod = $objReflection->hasMethod ('Codegen_ConnectorCreate');
		if ($blnHasMethod) {
			echo $strControlType::Codegen_ConnectorCreate($objCodeGen, $objTable, $objReverseReference);
		} else {
			throw new QCallerException ('Can\'t find Codegen_ConnectorCreate for ' . $strControlType);
		}

		if ($strControlType != 'QLabel' && (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo QLabel::Codegen_ConnectorCreate($objCodeGen, $objTable, $objReverseReference);
		}
		echo "\n\n";
	}

	foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
		if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;

		$strControlType = $objCodeGen->ModelConnectorControlClass($objManyToManyReference);
		$objReflection = new ReflectionClass ($strControlType);
		$blnHasMethod = $objReflection->hasMethod ('Codegen_ConnectorCreate');
		if ($blnHasMethod) {
			echo $strControlType::Codegen_ConnectorCreate($objCodeGen, $objTable, $objManyToManyReference);
		} else {
			throw new QCallerException ('Can\'t find Codegen_ConnectorCreate for ' . $strControlType);
		}

		if ($strControlType != 'QLabel' && (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo QLabel::Codegen_ConnectorCreate($objCodeGen, $objTable, $objManyToManyReference);
		}
		echo "\n\n";
	}
?>