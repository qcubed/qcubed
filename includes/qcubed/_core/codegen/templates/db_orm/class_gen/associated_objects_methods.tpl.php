///////////////////////////////
		// ASSOCIATED OBJECTS' METHODS
		///////////////////////////////

<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?><?php if (!$objReverseReference->Unique) { ?>
<?php include("associated_object.tpl.php"); ?>
<?php } ?><?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
<?php include("associated_object_manytomany.tpl.php"); ?>
<?php } ?>
