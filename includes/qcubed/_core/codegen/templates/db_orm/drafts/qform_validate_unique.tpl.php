<?php
$blnHasUnique = false;
foreach ($objTable->IndexArray as $objIndex) {
	if ($objIndex->Unique && !$objIndex->PrimaryKey){
		$blnHasUnique = true;
		continue;
	}
}
if ($blnHasUnique) {
?>
// Check for records that may violate Unique Clauses
<?php 
	require 'validate_unique.tpl.php';

} ?>
