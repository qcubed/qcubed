<?php
	/**
	 * @var QSqlTable $objTable
	 * @var QCodeGenBase $objCodeGen
	 */

	foreach ($objTable->ColumnArray as $objColumn) {
		if ($objColumn->Options && isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;

		$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objColumn);
		echo $objControlCodeGenerator->ConnectorCreate($objCodeGen, $objTable, $objColumn);
		if ($objControlCodeGenerator->GetControlClass() != 'QLabel' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo QLabel_CodeGenerator::Instance()->ConnectorCreate($objCodeGen, $objTable, $objColumn);
		}
		echo "\n\n";
	}


	foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
		if (!$objReverseReference->Unique) continue;
		if (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None) continue;

		$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objReverseReference);
		echo $objControlCodeGenerator->ConnectorCreate($objCodeGen, $objTable, $objReverseReference);

		if ($objControlCodeGenerator->GetControlClass() != 'QLabel' && (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo QLabel_CodeGenerator::Instance()->ConnectorCreate($objCodeGen, $objTable, $objReverseReference);
		}
		echo "\n\n";
	}

	foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
		if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;

		$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objManyToManyReference);
		echo $objControlCodeGenerator->ConnectorCreate($objCodeGen, $objTable, $objManyToManyReference);

		if ($objControlCodeGenerator->GetControlClass() != 'QLabel' && (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo QLabel_CodeGenerator::Instance()->ConnectorCreate($objCodeGen, $objTable, $objManyToManyReference);
		}
		echo "\n\n";
	}