///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
<?php foreach ($objTable->IndexArray as $objIndex) { ?>
<?php if ($objIndex->Unique) { ?>

<?php include("index_load_single.tpl.php"); ?>

<?php } ?><?php if (!$objIndex->Unique) { ?>

<?php include("index_load_array.tpl.php"); ?>

<?php } ?>
<?php } ?>



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
	<?php include("index_load_array_manytomany.tpl.php"); ?>

<?php }