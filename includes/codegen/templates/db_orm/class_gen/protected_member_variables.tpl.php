///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
		/**
		 * Protected member variable that maps to the database <?php if ($objColumn->PrimaryKey) print 'PK '; ?><?php if ($objColumn->Identity) print 'Identity '; ?>column <?= $objTable->Name ?>.<?= $objColumn->Name ?>

<?php if ($objColumn->Comment) { ?>		 * <?= $objColumn->Comment ?>
<?php } ?>
		 * @var <?= $objColumn->VariableType ?> <?= $objColumn->VariableName ?>

		 */
		protected $<?= $objColumn->VariableName ?>;
<?php if (($objColumn->VariableType == QType::String) && (is_numeric($objColumn->Length))) { ?>
		const <?= $objColumn->PropertyName ?>MaxLength = <?= $objColumn->Length ?>;
<?php } ?>
		const <?= $objColumn->PropertyName ?>Default = <?php
	if (is_null($objColumn->Default))
		print 'null';
	elseif (is_numeric($objColumn->Default))
		print $objColumn->Default;
	elseif ($objColumn->Default == 'CURRENT_TIMESTAMP') {
		print 'QDateTime::Now';
	}
	else {
		print "'" . addslashes($objColumn->Default) . "'";
	}
?>;

<?php if ((!$objColumn->Identity) && ($objColumn->PrimaryKey)) { ?>

		/**
		 * Protected internal member variable that stores the original version of the PK column value (if restored)
		 * Used by Save() to update a PK column during UPDATE
		 * @var <?= $objColumn->VariableType ?> __<?= $objColumn->VariableName ?>;
		 */
		protected $__<?= $objColumn->VariableName ?>;
<?php } ?>

<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
<?php 
		$objAssociatedTable = $objCodeGen->GetTable($objReference->AssociatedTable);
		if (is_a($objAssociatedTable, 'QTypeTable')) {
?>
		/**
		 * Private member variable that stores a <?= $objReference->VariableType ?> id,
		 * if this <?= $objTable->ClassName ?> object was restored with
		 * an expansion on the <?= $objReference->Table ?> association table.
		 * @var integer _int<?= $objReference->ObjectDescription ?>;
		 */
		private $_int<?= $objReference->ObjectDescription ?>;

		/**
		 * Private member variable that stores an array of <?= $objReference->VariableType ?> ids,
		 * if this <?= $objTable->ClassName ?> object was restored with
		 * an ExpandAsArray on the <?= $objReference->ObjectDescription ?> association table.
		 * @var integer[] _int<?= $objReference->ObjectDescription ?>Array;
		 */
		private $_int<?= $objReference->ObjectDescription ?>Array = null;

<?php 	} else { ?>
		/**
		 * Private member variable that stores a reference to a single <?= $objReference->ObjectDescription ?> object
		 * (of type <?= $objReference->VariableType ?>), if this <?= $objTable->ClassName ?> object was restored with
		 * an expansion on the <?= $objReference->Table ?> association table.
		 * @var <?= $objReference->VariableType ?> _obj<?= $objReference->ObjectDescription ?>;
		 */
		private $_obj<?= $objReference->ObjectDescription ?>;

		/**
		 * Private member variable that stores a reference to an array of <?= $objReference->ObjectDescription ?> objects
		 * (of type <?= $objReference->VariableType ?>[]), if this <?= $objTable->ClassName ?> object was restored with
		 * an ExpandAsArray on the <?= $objReference->Table ?> association table.
		 * @var <?= $objReference->VariableType ?>[] _obj<?= $objReference->ObjectDescription ?>Array;
		 */
		private $_obj<?= $objReference->ObjectDescription ?>Array = null;
<?php 	} ?>
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if (!$objReference->Unique) { ?>
		/**
		 * Private member variable that stores a reference to a single <?= $objReference->ObjectDescription ?> object
		 * (of type <?= $objReference->VariableType ?>), if this <?= $objTable->ClassName ?> object was restored with
		 * an expansion on the <?= $objReference->Table ?> association table.
		 * @var <?= $objReference->VariableType ?> _obj<?= $objReference->ObjectDescription ?>;
		 */
		private $_obj<?= $objReference->ObjectDescription ?>;

		/**
		 * Private member variable that stores a reference to an array of <?= $objReference->ObjectDescription ?> objects
		 * (of type <?= $objReference->VariableType ?>[]), if this <?= $objTable->ClassName ?> object was restored with
		 * an ExpandAsArray on the <?= $objReference->Table ?> association table.
		 * @var <?= $objReference->VariableType ?>[] _obj<?= $objReference->ObjectDescription ?>Array;
		 */
		private $_obj<?= $objReference->ObjectDescription ?>Array = null;

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
