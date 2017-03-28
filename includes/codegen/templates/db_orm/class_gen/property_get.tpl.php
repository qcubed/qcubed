		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {

				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
<?php 
	$objAssociatedTable = $objCodeGen->GetTable($objReference->AssociatedTable);
	$varPrefix = (is_a($objAssociatedTable, 'QTypeTable') ? '_int' : '_obj');
	$varType = (is_a($objAssociatedTable, 'QTypeTable') ? 'integer' : $objReference->VariableType);
?>
				case '<?= $objReference->ObjectDescription ?>':
				case '_<?= $objReference->ObjectDescription ?>': // for backwards compatibility
					/**
					 * Gets the value of the protected <?= $varPrefix . $objReference->ObjectDescription ?> (Read-Only)
					 * if set due to an expansion on the <?= $objReference->Table ?> association table
					 * @return <?= $varType ?>

					 */
					return $this-><?= $varPrefix . $objReference->ObjectDescription ?>;

				case '<?= $objReference->ObjectDescription ?>Array':
				case '_<?= $objReference->ObjectDescription ?>Array': // for backwards compatibility
					/**
					 * Gets the value of the protected <?= $varPrefix . $objReference->ObjectDescription ?>Array (Read-Only)
					 * if set due to an ExpandAsArray on the <?= $objReference->Table ?> association table
					 * @return <?= $varType ?>[]
					 */
					return $this-><?= $varPrefix . $objReference->ObjectDescription ?>Array;


<?php } ?><?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if (!$objReference->Unique) { ?>
				case '<?= $objReference->ObjectDescription ?>':
				case '_<?= $objReference->ObjectDescription ?>':
					/**
					 * Gets the value of the protected _obj<?= $objReference->ObjectDescription ?> (Read-Only)
					 * if set due to an expansion on the <?= $objReference->Table ?>.<?= $objReference->Column ?> reverse relationship
					 * @return <?= $objReference->VariableType ?>

					 */
					return $this->_obj<?= $objReference->ObjectDescription ?>;

				case '<?= $objReference->ObjectDescription ?>Array':
				case '_<?= $objReference->ObjectDescription ?>Array':
					/**
					 * Gets the value of the protected _obj<?= $objReference->ObjectDescription ?>Array (Read-Only)
					 * if set due to an ExpandAsArray on the <?= $objReference->Table ?>.<?= $objReference->Column ?> reverse relationship
					 * @return <?= $objReference->VariableType ?>[]
					 */
					return $this->_obj<?= $objReference->ObjectDescription ?>Array;

<?php } ?><?php } ?>

				case '__Restored':
					return $this->__blnRestored;

				default:
					try {
        			    // Use getter if it exists
                        $strMethod = 'get' . $strName;
                        if (method_exists($this, $strMethod)) {
                            return $this->$strMethod();
                        }

						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}