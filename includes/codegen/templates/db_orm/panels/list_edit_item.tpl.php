	protected function EditItem ($strKey = null) {
<?php
	if ($blnUseDialog) { ?>
		$this->dlgEdit->Load($strKey);
		$this->dlgEdit->Open();
<?php
	}
	  elseif (QCodeGen::$CreateMethod == 'queryString') {
?>
		$strQuery = '';
		if ($strKey) {
<?php 	if (count($objTable->PrimaryKeyColumnArray) == 1) { ?>
			$strQuery =  '?<?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName?>=' . $strKey;
<?php 	} else { ?>
			$keys = explode (':', $strKey);
<?php 		for($i = 0; $i < count($objTable->PrimaryKeyColumnArray); $i++) { ?>
			$params['<?=$objTable->PrimaryKeyColumnArray[$i]->VariableName?>'] = $keys[<?= $i ?>];
<?php 		} ?>
			$strQuery = '?' . http_build_query($params, '', '&');
<?php 	} ?>
		}
		$strEditPageUrl = __VIRTUAL_DIRECTORY__ . __FORMS__ . '/<?php echo QConvertNotation::UnderscoreFromCamelCase($strPropertyName) ?>_edit.php' . $strQuery;
		QApplication::Redirect ($strEditPageUrl);
<?php }
	else {	// pathinfo type request
?>
		$strQuery = '';
		if ($strKey) {
<?php 	if (count($objTable->PrimaryKeyColumnArray) == 1) { ?>
			$strQuery =  '/' . $strKey;
<?php 	} else { ?>
			$keys = explode (':', $strKey);
<?php 		for($i = 0; $i < count($objTable->PrimaryKeyColumnArray); $i++) { ?>
			$params['<?=$objTable->PrimaryKeyColumnArray[$i]->VariableName?>'] = $keys[<?= $i ?>];
<?php 		} ?>
		$strQuery = '/' . implode('/', $keys);
<?php 	} ?>
		}
		$strEditPageUrl = __VIRTUAL_DIRECTORY__ . __FORMS__ . '/<?php echo QConvertNotation::UnderscoreFromCamelCase($strPropertyName) ?>_edit.php' . $strQuery;
		QApplication::Redirect ($strEditPageUrl);
<?php }?>
	}
