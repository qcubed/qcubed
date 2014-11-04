<?php
	if ($strControlType == 'QLabel'  ||
		!isset($objColumn->Options['FormGen']) ||
		$objColumn->Options['FormGen'] != 'label') {
?>
				case '<?= $strPropertyName ?>Control':
					if (!$this-><?= $strControlId ?>) return $this-><?= $strControlId ?>_Create();
					return $this-><?= $strControlId ?>;
<?php } ?>
				case '<?= $strPropertyName ?>Label':
					if (!$this-><?= $strLabelId ?>) return $this-><?= $strLabelId ?>_Create();
					return $this-><?= $strLabelId ?>;