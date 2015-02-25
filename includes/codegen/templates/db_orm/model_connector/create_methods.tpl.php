<?php
	/**
	 * @var QTable $objTable
	 * @var QCodeGenBase $objCodeGen
	 */

	foreach ($objTable->ColumnArray as $objColumn) {
		if ($objColumn->Options && isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;

		$objControlHelper = $objCodeGen->GetModelConnectorControlHelper($objColumn);
		echo $objControlHelper->ConnectorCreate($objCodeGen, $objTable, $objColumn);
		if ($objControlHelper->GetControlClass() != 'QLabel' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo ModelConnectorControlHelper_QLabel::Instance()->ConnectorCreate($objCodeGen, $objTable, $objColumn);
		}
		echo "\n\n";
	}


	foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
		if (!$objReverseReference->Unique) continue;
		if (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None) continue;

		$objControlHelper = $objCodeGen->GetModelConnectorControlHelper($objReverseReference);
		echo $objControlHelper->ConnectorCreate($objCodeGen, $objTable, $objReverseReference);

		if ($objControlHelper->GetControlClass() != 'QLabel' && (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo ModelConnectorControlHelper_QLabel::Instance()->ConnectorCreate($objCodeGen, $objTable, $objReverseReference);
		}
		echo "\n\n";
	}

	foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
		if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;

		$objControlHelper = $objCodeGen->GetModelConnectorControlHelper($objManyToManyReference);
		echo $objControlHelper->ConnectorCreate($objCodeGen, $objTable, $objManyToManyReference);

		if ($objControlHelper->GetControlClass() != 'QLabel' && (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] == QFormGen::Both)) {
			// also generate a QLabel for each control that generates both
			echo ModelConnectorControlHelper_QLabel::Instance()->ConnectorCreate($objCodeGen, $objTable, $objManyToManyReference);
		}
		echo "\n\n";
	}
?>