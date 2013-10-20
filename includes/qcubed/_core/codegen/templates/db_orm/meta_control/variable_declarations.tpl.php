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

		// Controls that allow the editing of <?php echo $objTable->ClassName  ?>'s individual data fields
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
		/**
		 * @var <?php echo $objCodeGen->FormControlClassForColumn($objColumn);  ?> <?php echo $objCodeGen->FormControlVariableNameForColumn($objColumn);  ?>

		 * @access protected
		 */
		protected $<?php echo $objCodeGen->FormControlVariableNameForColumn($objColumn);  ?>;
<?php } ?>

		// Controls that allow the viewing of <?php echo $objTable->ClassName  ?>'s individual data fields
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (!$objColumn->Identity && !$objColumn->Timestamp) { ?>
		/**
		 * @var QLabel <?php echo $objCodeGen->FormLabelVariableNameForColumn($objColumn);  ?>

		 * @access protected
		 */
		protected $<?php echo $objCodeGen->FormLabelVariableNameForColumn($objColumn);  ?>;
		
<?php if ($objColumn->VariableType == 'QDateTime') {?>
		/**
		 * @var str<?php echo $objColumn->PropertyName  ?>DateTimeFormat
		 * @access protected
		 */
		protected $str<?php echo $objColumn->PropertyName  ?>DateTimeFormat;
<?php } ?>
<?php } ?>
<?php } ?>

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
		protected $str<?php echo $objManyToManyReference->ObjectDescription;  ?>Glue;
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