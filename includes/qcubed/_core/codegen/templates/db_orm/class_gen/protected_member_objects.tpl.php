///////////////////////////////
		// PROTECTED MEMBER OBJECTS
		///////////////////////////////

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column <?php echo $objTable->Name  ?>.<?php echo $objColumn->Name  ?>.
		 *
		 * NOTE: Always use the <?php echo $objColumn->Reference->PropertyName  ?> property getter to correctly retrieve this <?php echo $objColumn->Reference->VariableType  ?> object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var <?php echo $objColumn->Reference->VariableType  ?> <?php echo $objColumn->Reference->VariableName  ?>

		 */
		protected $<?php echo $objColumn->Reference->VariableName  ?>;

<?php } ?>
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
		/**
		 * Protected member variable that contains the object which points to
		 * this object by the reference in the unique database column <?php echo $objReverseReference->Table  ?>.<?php echo $objReverseReference->Column  ?>.
		 *
		 * NOTE: Always use the <?php echo $objReverseReference->ObjectPropertyName  ?> property getter to correctly retrieve this <?php echo $objReverseReference->VariableType  ?> object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var <?php echo $objReverseReference->VariableType  ?> <?php echo $objReverseReference->ObjectMemberVariable  ?>

		 */
		protected $<?php echo $objReverseReference->ObjectMemberVariable  ?>;

		/**
		 * Used internally to manage whether the adjoined <?php echo $objReverseReference->ObjectDescription  ?> object
		 * needs to be updated on save.
		 *
		 * NOTE: Do not manually update this value
		 */
		protected $blnDirty<?php echo $objReverseReference->ObjectPropertyName  ?>;

<?php } ?>
<?php } ?>
