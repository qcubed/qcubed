<?php
	/**
	 * @var QSqlTable $objTable
	 * @var QCodeGenBase $objCodeGen
	 */
	foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
		if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;

		$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objManyToManyReference);
		echo $objControlCodeGenerator->ConnectorUpdateMethod($objCodeGen, $objTable, $objManyToManyReference);
	}