<?php
	$blnAutoInitialize = $objCodeGen->AutoInitialize;
	if ($blnAutoInitialize) {
?>

		/**
		 * Construct a new <?= $objTable->ClassName ?> object.
		 */
		public function __construct() {
			$this->Initialize();
		}
<?php } ?>

		/**
		 * Initialize each property with default values from database definition
		 */
		public function Initialize()
		{
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php 	if ($objColumn->Identity ||
				$objColumn->Timestamp) {
			// do nothing
	 	} else { ?>
			$this->set<?= $objColumn->PropertyName ?>(<?php
	$defaultVarName = $objTable->ClassName . '::' . $objColumn->PropertyName . 'Default';
	if ($objColumn->VariableType != QType::DateTime)
		print ($defaultVarName);
	else
		print "(" . $defaultVarName . " === null)?null:new QDateTime(" . $defaultVarName . ")";
	?>);
<?php 	} ?>
<?php } ?>
		}
