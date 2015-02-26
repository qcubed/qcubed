<?= '<?php' ?>
<?php
	/** @var JqDoc $objJqDoc */
	$strQcBaseClass = $objJqDoc->strQcBaseClass;
	while (!class_exists($strQcBaseClass.'_CodeGenerator')) {
		$strQcBaseClass = get_parent_class($strQcBaseClass);
	}
?>
	class <?= $objJqDoc->strQcClass ?>Gen_CodeGenerator extends <?= $strQcBaseClass ?>_CodeGenerator	{
		public function __construct($strControlClassName = '<?= $objJqDoc->strQcClass ?>Gen') {
			parent::__construct($strControlClassName);
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
<?php foreach ($objJqDoc->options as $option) { ?>
<?php 	if ($option->phpQType) { ?>
				new QModelConnectorParam (get_called_class(), '<?= $option->propName ?>', '<?= addslashes(trim(str_replace(array("\n", "\r"), '', $option->description))) ?>', <?= $option->phpQType ?>),
<?php 	} ?>
<?php } ?>			));
		}
	}


