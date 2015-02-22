<?php
 foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
	 if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;

	 $strControlType = $objCodeGen->ModelConnectorControlClass($objManyToManyReference);

	 $objReflection = new ReflectionClass ($strControlType);
	 $blnHasMethod = $objReflection->hasMethod ('Codegen_ConnectorRefresh');
	 if ($blnHasMethod) {
		 echo $strControlType::Codegen_ConnectorUpdateMethod($objCodeGen, $objTable, $objManyToManyReference);
	 } else {
		 throw new QCallerException ('Can\'t find Codegen_ConnectorUpdate for ' . $strControlType);
	 }
}
?>