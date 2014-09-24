// General Variables
		/**
		 * @var <?php echo $objTable->ClassName;  ?> <?php echo $objCodeGen->VariableNameFromTable($objTable->Name);  ?>

		 * @access protected
		 */
		protected $<?php echo $objCodeGen->VariableNameFromTable($objTable->Name);  ?>;
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

		// Controls that correspond to <?php echo $objTable->ClassName  ?>'s individual data fields
<?php foreach ($objTable->ColumnArray as $objColumn) {
	if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == 'none') continue;

	$strControlType = $objCodeGen->FormControlClassForColumn($objColumn);
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
		 * @var QListBox <?php echo $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);  ?>

		 * @access protected
		 */
		protected $<?php echo $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);  ?>;
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
		protected $<?php echo $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);  ?>;
		protected $str<?php echo $objManyToManyReference->ObjectDescription;  ?>Glue = ', ';
<?php } ?>

		// QLabel Controls (if applicable) to view Unique ReverseReferences and ManyToMany References
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
		/**
		 * @var QLabel <?php echo $objCodeGen->FormLabelVariableNameForUniqueReverseReference($objReverseReference);  ?>

		 * @access protected
		 */
		protected $<?php echo $objCodeGen->FormLabelVariableNameForUniqueReverseReference($objReverseReference);  ?>;
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
		protected $<?php echo $objCodeGen->FormLabelVariableNameForManyToManyReference($objManyToManyReference);  ?>;
<?php } ?>