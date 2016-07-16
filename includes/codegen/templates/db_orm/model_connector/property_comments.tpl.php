<?php
	/**
	 * @var QSqlTable $objTable
	 * @var QCodeGenBase $objCodeGen
	 */
?>
	 * @property-read <?= $objTable->ClassName ?> $<?= $objTable->ClassName ?> the actual <?= $objTable->ClassName ?> data class being edited
<?php 
	foreach ($objTable->ColumnArray as $objColumn) {
		if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;
		$objGenerator = $objCodeGen->GetControlCodeGenerator($objColumn);
		$strClassName = $objGenerator->GetControlClass();
		$blnIsLabel = ($strClassName == 'QLabel');

		if (!$blnIsLabel && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] != QFormGen::LabelOnly)) { ?>
	 * @property <?= $strClassName; ?> $<?= $objColumn->PropertyName ?>Control
<?php 	}
		if ($blnIsLabel || !isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] != QFormGen::ControlOnly) { ?>
	 * @property-read QLabel $<?= $objColumn->PropertyName ?>Label
<?php 	}
		print ($objGenerator->ConnectorPropertyComments($objCodeGen, $objTable, $objColumn));
	}

	foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
		if (!$objReverseReference->Unique || (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None)) continue;
		$strClassName = $objCodeGen->GetControlCodeGenerator($objReverseReference)->GetControlClass();
		$blnIsLabel = ($strClassName == 'QLabel');

		if (!$blnIsLabel && (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] != QFormGen::LabelOnly)) { ?>
	 * @property <?= $strClassName; ?> $<?= $objReverseReference->ObjectDescription ?>Control
<?php 	}
		if ($blnIsLabel || !isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] != QFormGen::ControlOnly) { ?>
	 * @property-read QLabel $<?= $objReverseReference->ObjectDescription ?>Label
<?php
		} 
	} 
?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
		if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;
		$strClassName = $objCodeGen->GetControlCodeGenerator($objManyToManyReference)->GetControlClass();
		$blnIsLabel = ($strClassName == 'QLabel');

		if (!$blnIsLabel && (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] != QFormGen::LabelOnly)) { ?>
	 * @property <?= $strClassName; ?> $<?= $objManyToManyReference->ObjectDescription ?>Control
<?php 	}
		if ($blnIsLabel || !isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] != QFormGen::ControlOnly) { ?>
	 * @property-read QLabel $<?= $objManyToManyReference->ObjectDescription ?>Label
<?php
	 }
	}
?>
	 * @property-read string $TitleVerb a verb indicating whether or not this is being edited or created
	 * @property-read boolean $EditMode a boolean indicating whether or not this is being edited or created