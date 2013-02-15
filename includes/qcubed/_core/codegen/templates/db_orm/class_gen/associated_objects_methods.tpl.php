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
	if ($objManyToManyReference->IsTypeAssociation) {
        include("associated_object_type_manytomany.tpl.php");
    } else {
    	include("associated_object_manytomany.tpl.php");
    }
} 
?>