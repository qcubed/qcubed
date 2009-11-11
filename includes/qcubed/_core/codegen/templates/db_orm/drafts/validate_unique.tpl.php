<?php 
	foreach ($objTable->IndexArray as $objIndex) {
		if ($objIndex->Unique && !$objIndex->PrimaryKey) {
			$objColumnArray = $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray); ?>
			if (($obj<?php print $objTable->ClassName;?> = <?php print $objTable->ClassName;?>::LoadBy<?php print $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray);?>(<?php 
				foreach ($objColumnArray as $intColumnIndex => $objColumn) { 
					print '$this->';
					print $objCodeGen->FormControlVariableNameForColumn($objColumn);
					print '->';
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
				$this-><?php print $objCodeGen->FormControlVariableNameForColumn($objColumn); ?>->Warning = QApplication::Translate("Already in Use");
<?php 				} ?>
			}
<?php 		}
	} ?>
