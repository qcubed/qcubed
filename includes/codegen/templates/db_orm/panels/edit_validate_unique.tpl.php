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
	foreach ($objTable->IndexArray as $objIndex) {
		if ($objIndex->Unique && !$objIndex->PrimaryKey) {
			$objColumnArray = $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray);

			$blnSkipIt = false;
			foreach($objColumnArray as $intColumnIndex => $objColumn) {
				if (isset($objColumn->Options['FormGen']) && ($objColumn->Options['FormGen'] == QFormGen::None || $objColumn->Options['FormGen'] == QFormGen::LabelOnly)) {
					$blnSkipIt = true;
					break;
				}
			}
			if ($blnSkipIt) continue; // one of the needed data items is not being edited
?>
		if ((<?php
			foreach($objColumnArray as $intColumnIndex => $objColumn) {
				print '$this->' . $objCodeGen->ModelConnectorVariableName($objColumn);
				if ($intColumnIndex != count($objIndex->ColumnNameArray) - 1) {
					print ' && ';
				}
			}

		?>) && ($obj<?php print $objTable->ClassName;?> = <?php print $objTable->ClassName;?>::LoadBy<?php print $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray);?>(<?php
				foreach ($objColumnArray as $intColumnIndex => $objColumn) {
					print '$this->' . $objCodeGen->ModelConnectorVariableName($objColumn) . '->';
					if ($objColumn->VariableType == QType::DateTime) {
						print 'DateTime';
					} elseif ($objColumn->VariableType == QType::Boolean) {
						print 'Checked';
					} elseif ($objColumn->Reference) {
						print 'SelectedValue';
					} else {
						print 'Text';
					}
					if ($intColumnIndex != count($objIndex->ColumnNameArray)-1) { print ','; }
				}?>))<?php
					foreach ($objPrimaryColumnArray = $objTable->PrimaryKeyColumnArray as $objColumn){
						if ($objColumn->PrimaryKey){
							print ' && ($obj'.$objTable->ClassName.'->'.$objColumn->PropertyName.' != $this->mct'.$objTable->ClassName.'->'.$objTable->ClassName.'->'.$objColumn->PropertyName.' )';
						}
					}?>){
				$blnToReturn = false;
<?php 				foreach ($objColumnArray as $intColumnIndex => $objColumn) { ?>
				$this-><?php print $objCodeGen->ModelConnectorVariableName($objColumn); ?>->Warning = QApplication::Translate("This value is already in use.");
<?php 				} ?>
			}
<?php 		}
	} ?>
		return $blnToReturn;
	}
<?php }