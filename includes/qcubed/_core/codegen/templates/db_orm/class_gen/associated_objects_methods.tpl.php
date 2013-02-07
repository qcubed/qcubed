///////////////////////////////
		// ASSOCIATED OBJECTS' METHODS
		///////////////////////////////

<?php 
foreach ($objTable->ReverseReferenceArray as $objReverseReference) { 
	if (!$objReverseReference->Unique) { 
		include("associated_object.tpl.php");
	}
} 
foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
    if (substr($objManyToManyReference->AssociatedTable,-5) != '_type') {
    	include("associated_object_manytomany.tpl.php");
    } elseif (substr($objManyToManyReference->AssociatedTable,-5) == '_type') {
        include("associated_object_type_manytomany.tpl.php");
    }
} 
?>