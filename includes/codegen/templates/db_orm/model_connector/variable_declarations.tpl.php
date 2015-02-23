// General Variables
		/**
		 * @var <?= $objTable->ClassName; ?> <?= $objCodeGen->ModelVariableName($objTable->Name); ?>

		 * @access protected
		 */
		protected $<?= $objCodeGen->ModelVariableName($objTable->Name); ?>;
		/**
		 * @var QForm|QControl objParentObject
		 * @access protected
		 */
		protected $objParentObject;
		/**
		 * @var string strTitleVerb
		 * @access protected
		 */
		protected $strTitleVerb;
		/**
		 * @var boolean blnEditMode
		 * @access protected
		 */
		protected $blnEditMode;

		// Controls that correspond to <?= $objTable->ClassName ?>'s individual data fields
<?php foreach ($objTable->ColumnArray as $objColumn) {
	if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;

	$strControlType = $objCodeGen->ModelConnectorControlClass($objColumn);

	$objReflection = new ReflectionClass ($strControlType);
	$blnHasMethod = $objReflection->hasMethod ('Codegen_ConnectorVariableDeclaration');
	if ($blnHasMethod) {
		echo $strControlType::Codegen_ConnectorVariableDeclaration($objCodeGen, $objColumn);
	} else {
		throw new QCallerException ('Can\'t find Codegen_ConnectorVariableDeclaration for ' . $strControlType);
	}

	if ($strControlType != 'QLabel' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] == QFormGen::Both)) {
		// also generate a QLabel for each control that is not defaulted as a label already
		echo QLabel::Codegen_ConnectorVariableDeclaration($objCodeGen, $objColumn);
	}
}
?>
<?php
	foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
		if ($objReverseReference->Unique) $blnHasUnique = true;
	}
?>
<?php if (isset($blnHasUnique) || count($objTable->ManyToManyReferenceArray)) {?>

		// Controls to edit Unique ReverseReferences and ManyToMany References

<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
	if (!$objReverseReference->Unique) continue;
	if (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None) continue;
	$strControlType = $objCodeGen->ModelConnectorControlClass($objReverseReference);

	$objReflection = new ReflectionClass ($strControlType);
	$blnHasMethod = $objReflection->hasMethod ('Codegen_ConnectorVariableDeclaration');
	if ($blnHasMethod) {
		echo $strControlType::Codegen_ConnectorVariableDeclaration($objCodeGen, $objReverseReference);
	} else {
		throw new QCallerException ('Can\'t find Codegen_ConnectorVariableDeclaration for ' . $strControlType);
	}

	if ($strControlType != 'QLabel' && (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] == QFormGen::Both)) {
		// also generate a QLabel for each control that is not defaulted as a label already
		echo QLabel::Codegen_ConnectorVariableDeclaration($objCodeGen, $objReverseReference);
	}
} ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
	if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;
	$strControlType = $objCodeGen->ModelConnectorControlClass($objManyToManyReference);

	$objReflection = new ReflectionClass ($strControlType);
	$blnHasMethod = $objReflection->hasMethod ('Codegen_ConnectorVariableDeclaration');
	if ($blnHasMethod) {
		echo $strControlType::Codegen_ConnectorVariableDeclaration($objCodeGen, $objManyToManyReference);
	} else {
		throw new QCallerException ('Can\'t find Codegen_ConnectorVariableDeclaration for ' . $strControlType);
	}

	if ($strControlType != 'QLabel' && (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] == QFormGen::Both)) {
	// also generate a QLabel for each control that is not defaulted as a label already
		echo QLabel::Codegen_ConnectorVariableDeclaration($objCodeGen, $objManyToManyReference);
	}
?>
		protected $str<?= $objManyToManyReference->ObjectDescription; ?>Glue = ', ';
<?php } ?>
