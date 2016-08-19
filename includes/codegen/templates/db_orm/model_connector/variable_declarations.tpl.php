<?php
	/**
	 * @var QSqlTable $objTable
	 * @var QCodeGenBase $objCodeGen
	 */
?>
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

	$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objColumn);
	echo $objControlCodeGenerator->ConnectorVariableDeclaration($objCodeGen, $objColumn);

	if ($objControlCodeGenerator->GetControlClass() != 'QLabel' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] == QFormGen::Both)) {
		// also generate a QLabel for each control that is not defaulted as a label already
		echo QLabel_CodeGenerator::Instance()->ConnectorVariableDeclaration($objCodeGen, $objColumn);
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
	$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objReverseReference);
	echo $objControlCodeGenerator->ConnectorVariableDeclaration($objCodeGen, $objReverseReference);

	if ($objControlCodeGenerator->GetControlClass() != 'QLabel' && (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] == QFormGen::Both)) {
		// also generate a QLabel for each control that is not defaulted as a label already
		echo QLabel_CodeGenerator::Instance()->ConnectorVariableDeclaration($objCodeGen, $objReverseReference);
	}
} ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
	if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;
	$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objManyToManyReference);
	echo $objControlCodeGenerator->ConnectorVariableDeclaration($objCodeGen, $objManyToManyReference);

	if ($objControlCodeGenerator->GetControlClass() != 'QLabel' && (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] == QFormGen::Both)) {
	// also generate a QLabel for each control that is not defaulted as a label already
		echo QLabel_CodeGenerator::Instance()->ConnectorVariableDeclaration($objCodeGen, $objManyToManyReference);
	}
?>
		protected $str<?= $objManyToManyReference->ObjectDescription; ?>Glue = ', ';
<?php }