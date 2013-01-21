/** @var string */
		static public $strCompositeIdGlue = ',';

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
		/**
		 * Protected member variable that maps to the database <?php if ($objColumn->PrimaryKey) print 'PK '; ?><?php if ($objColumn->Identity) print 'Identity '; ?>column <?php echo $objTable->Name  ?>.<?php echo $objColumn->Name  ?>

<?php if ($objColumn->Comment) { ?>		 * <?php echo $objColumn->Comment  ?>
<?php } ?>
		 * @var <?php echo $objColumn->VariableType  ?> <?php echo $objColumn->VariableName  ?>

		 */
		protected $<?php echo $objColumn->VariableName  ?>;
<?php if (($objColumn->VariableType == QType::String) && (is_numeric($objColumn->Length))) { ?>
		const <?php echo $objColumn->PropertyName  ?>MaxLength = <?php echo $objColumn->Length  ?>;
<?php } ?>
		const <?php echo $objColumn->PropertyName  ?>Default = <?php
	if (is_null($objColumn->Default))
		print 'null';
	else if (is_numeric($objColumn->Default))
		print $objColumn->Default;
	else
		print "'" . addslashes($objColumn->Default) . "'";
?>;

<?php if ((!$objColumn->Identity) && ($objColumn->PrimaryKey)) { ?>

		/**
		 * Protected internal member variable that stores the original version of the PK column value (if restored)
		 * Used by Save() to update a PK column during UPDATE
		 * @var <?php echo $objColumn->VariableType  ?> __<?php echo $objColumn->VariableName  ?>;
		 */
		protected $__<?php echo $objColumn->VariableName  ?>;
<?php } ?>

<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
		/**
		 * Private member variable that stores a reference to a single <?php echo $objReference->ObjectDescription  ?> object
		 * (of type <?php echo $objReference->VariableType  ?>), if this <?php echo $objTable->ClassName  ?> object was restored with
		 * an expansion on the <?php echo $objReference->Table  ?> association table.
		 * @var <?php echo $objReference->VariableType  ?> _obj<?php echo $objReference->ObjectDescription  ?>;
		 */
		private $_obj<?php echo $objReference->ObjectDescription  ?>;

		/**
		 * Private member variable that stores a reference to an array of <?php echo $objReference->ObjectDescription  ?> objects
		 * (of type <?php echo $objReference->VariableType  ?>[]), if this <?php echo $objTable->ClassName  ?> object was restored with
		 * an ExpandAsArray on the <?php echo $objReference->Table  ?> association table.
		 * @var <?php echo $objReference->VariableType  ?>[] _obj<?php echo $objReference->ObjectDescription  ?>Array;
		 */
		private $_obj<?php echo $objReference->ObjectDescription  ?>Array = null;

<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if (!$objReference->Unique) { ?>
		/**
		 * Private member variable that stores a reference to a single <?php echo $objReference->ObjectDescription  ?> object
		 * (of type <?php echo $objReference->VariableType  ?>), if this <?php echo $objTable->ClassName  ?> object was restored with
		 * an expansion on the <?php echo $objReference->Table  ?> association table.
		 * @var <?php echo $objReference->VariableType  ?> _obj<?php echo $objReference->ObjectDescription  ?>;
		 */
		private $_obj<?php echo $objReference->ObjectDescription  ?>;

		/**
		 * Private member variable that stores a reference to an array of <?php echo $objReference->ObjectDescription  ?> objects
		 * (of type <?php echo $objReference->VariableType  ?>[]), if this <?php echo $objTable->ClassName  ?> object was restored with
		 * an ExpandAsArray on the <?php echo $objReference->Table  ?> association table.
		 * @var <?php echo $objReference->VariableType  ?>[] _obj<?php echo $objReference->ObjectDescription  ?>Array;
		 */
		private $_obj<?php echo $objReference->ObjectDescription  ?>Array = null;

<?php } ?><?php } ?>
		/**
		 * Protected array of virtual attributes for this object (e.g. extra/other calculated and/or non-object bound
		 * columns from the run-time database query result for this object).  Used by InstantiateDbRow and
		 * GetVirtualAttribute.
		 * @var string[] $__strVirtualAttributeArray
		 */
		protected $__strVirtualAttributeArray = array();

		/**
		 * Protected internal member variable that specifies whether or not this object is Restored from the database.
		 * Used by Save() to determine if Save() should perform a db UPDATE or INSERT.
		 * @var bool __blnRestored;
		 */
		protected $__blnRestored;
