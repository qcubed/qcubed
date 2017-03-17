<?php
	$blnAutoInitialize = $objCodeGen->AutoInitialize;
	if ($blnAutoInitialize) {
?>

		/**
		 * Construct a new <?= $objTable->ClassName ?> object.
		 */
		public function __construct($blnInitialize = true) {
            if ($blnInitialize) {
                $this->Initialize();
            }
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
			// do not initialize with a default value
	 	}
	 	else { ?>
			$this-><?= $objColumn->VariableName ?> = <?php
			$defaultVarName = $objTable->ClassName . '::' . $objColumn->PropertyName . 'Default';
			if ($objColumn->VariableType != QType::DateTime)
				print ($defaultVarName);
			else
				print "(" . $defaultVarName . " === null)?null:new QDateTime(" . $defaultVarName . ")";
			?>;
			$this->__blnValid[self::<?= strtoupper($objColumn->Name) ?>_FIELD] = true;
<?php 	} ?>
<?php } ?>
		}
