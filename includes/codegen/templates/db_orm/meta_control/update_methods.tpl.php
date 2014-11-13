<?php
 foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
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
	 elseif (isset ($objManyToManyReference->Options['FormGen']) &&
		 ($objManyToManyReference->Options['FormGen'] == 'label' ||
			 $objManyToManyReference->Options['FormGen'] == 'both')) {
		 echo QLabel::Codegen_MetaUpdate($objCodeGen, $objTable, $objManyToManyReference);
	 }
}
?>