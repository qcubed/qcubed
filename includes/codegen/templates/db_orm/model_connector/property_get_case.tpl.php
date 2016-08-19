<?php
	if ($strClassName != 'QLabel' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] != QFormGen::LabelOnly)) { ?>
				case '<?= $strPropertyName ?>Control':
					if (!$this-><?= $strControlVarName ?>) return $this-><?= $strControlVarName ?>_Create();
					return $this-><?= $strControlVarName ?>;
<?php }
	if ($strClassName == 'QLabel' || !isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] != QFormGen::ControlOnly) { ?>
				case '<?= $strPropertyName ?>Label':
					if (!$this-><?= $strLabelVarName ?>) return $this-><?= $strLabelVarName ?>_Create();
					return $this-><?= $strLabelVarName ?>;
<?php }