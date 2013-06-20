<?php
 foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
	// Use the "control_update_manytomany_reference" subtemplate to generate the code
	// required to create/setup the control.
	$strObjectName = $objCodeGen->VariableNameFromTable($objTable->Name);
	$strClassName = $objTable->ClassName;
	$strControlId = $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);

	// Get the subtemplate and evaluate
	include('control_update_manytomany_reference.tpl.php');
	echo "\n";
}
?>