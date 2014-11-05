	 * @property-read <?= $objTable->ClassName ?> $<?= $objTable->ClassName ?> the actual <?= $objTable->ClassName ?> data class being edited
<?php 
	foreach ($objTable->ColumnArray as $objColumn) {
		if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == 'none') continue; 
		if (!isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] != 'label') { ?>
	 * @property <?= $objCodeGen->FormControlClassForColumn($objColumn); ?> $<?= $objColumn->PropertyName ?>Control
<?php 	} ?>
	 * @property-read QLabel $<?= $objColumn->PropertyName ?>Label
<?php } ?>
<?php 
	foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
		if ($objReverseReference->Unique) { 
?>
	 * @property QListBox $<?= $objReverseReference->ObjectDescription ?>Control
	 * @property-read QLabel $<?= $objReverseReference->ObjectDescription ?>Label
<?php
		} 
	} 
?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
	 * @property QListBox $<?= $objManyToManyReference->ObjectDescription ?>Control
	 * @property-read QLabel $<?= $objManyToManyReference->ObjectDescription ?>Label
<?php } ?>
	 * @property-read string $TitleVerb a verb indicating whether or not this is being edited or created
	 * @property-read boolean $EditMode a boolean indicating whether or not this is being edited or created