/**
		 * Initialize each property with default values from database definition
		 */
		public function Initialize()
		{
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
			$this-><?= $objColumn->VariableName ?> = <?php
			$defaultVarName = $objTable->ClassName . '::' . $objColumn->PropertyName . 'Default';
			if ($objColumn->VariableType != QType::DateTime)
				print ($defaultVarName);
			else
				print "(" . $defaultVarName . " === null)?null:new QDateTime(" . $defaultVarName . ")";
			?>;
<?php } ?>
		}
