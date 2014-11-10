<?php
 foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
	 if ($objManyToManyReference->IsTypeAssociation) {
		 if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == 'none') continue;
		 $strControlType = $objCodeGen->MetaControlControlClass($objManyToManyReference);

		 if (!isset($objManyToManyReference->Options['FormGen']) ||
			 $objManyToManyReference->Options['FormGen'] != 'label') {

			 $objReflection = new ReflectionClass ($strControlType);
			 $blnHasMethod = $objReflection->hasMethod ('Codegen_MetaRefresh');
			 if ($blnHasMethod) {
				 echo $strControlType::Codegen_MetaUpdate($objCodeGen, $objTable, $objManyToManyReference);
			 } else {
				 throw new QCallerException ('Can\'t find Codegen_MetaUpdate for ' . $strControlType);
			 }
		 }
	 }
	 else {
		 // Use the "control_update_manytomany_reference" subtemplate to generate the code
		 // required to create/setup the control.
		 $strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
		 $strClassName = $objTable->ClassName;
		 $strControlId = $objCodeGen->MetaControlVariableName($objManyToManyReference);

		 // Get the subtemplate and evaluate
		 include('control_update_manytomany_reference.tpl.php');
		 echo "\n";
	 }

}
?>