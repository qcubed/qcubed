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
	if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == 'none') continue;

	$strControlType = $objCodeGen->MetaControlControlClass($objColumn);
	if ($strControlType == 'QLabel'  ||
			!isset($objColumn->Options['FormGen']) ||
			$objColumn->Options['FormGen'] != 'label') {

		$objReflection = new ReflectionClass ($strControlType);
		$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaVariableDeclaration');
		if ($blnHasMethod) {
			echo $strControlType::Codegen_MetaVariableDeclaration($objCodeGen, $objColumn);
		} else {
			throw new QCallerException ('Can\'t find Codegen_MetaVariableDeclaration for ' . $strControlType);
		}
	}

	if ($strControlType != 'QLabel') {
		// also generate a QLabel for each control that is not defaulted as a label already
		echo QLabel::Codegen_MetaVariableDeclaration($objCodeGen, $objColumn);
	}
}
?>

		// QListBox Controls (if applicable) to edit Unique ReverseReferences and ManyToMany References
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
		/**
		 * @var QListBox <?= $objCodeGen->MetaControlVariableName($objReverseReference); ?>

		 * @access protected
		 */
		protected $<?= $objCodeGen->MetaControlVariableName($objReverseReference); ?>;
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
		protected $<?= $objCodeGen->MetaControlVariableName($objManyToManyReference); ?>;
		protected $str<?= $objManyToManyReference->ObjectDescription; ?>Glue = ', ';
<?php } ?>

		// QLabel Controls (if applicable) to view Unique ReverseReferences and ManyToMany References
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
		/**
		 * @var QLabel <?= $objCodeGen->MetaControlLabelVariableName($objReverseReference); ?>

		 * @access protected
		 */
		protected $<?= $objCodeGen->MetaControlLabelVariableName($objReverseReference); ?>;
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
		protected $<?= $objCodeGen->MetaControlLabelVariableName($objManyToManyReference); ?>;
<?php } ?>