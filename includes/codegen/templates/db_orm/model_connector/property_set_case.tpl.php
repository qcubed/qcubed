<?php
	if ($strClassName != 'QLabel' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] != QFormGen::LabelOnly)) { ?>
					case '<?= $strPropertyName ?>Control':
						return ($this-><?= $strControlVarName ?> = QType::Cast($mixValue, '<?= $strClassName ?>'));
<?php }
	if ($strClassName == 'QLabel' || !isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] != QFormGen::ControlOnly) { ?>
					case '<?= $strPropertyName ?>Label':
						return ($this-><?= $strLabelVarName ?> = QType::Cast($mixValue, 'QLabel'));
<?php }