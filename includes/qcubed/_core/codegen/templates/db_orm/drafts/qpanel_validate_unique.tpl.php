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
		public function Validate() {
			$blnToReturn = true;
<?php
	require 'validate_unique.tpl.php';
?>
	return $blnToReturn;
		}
<?php } ?>
