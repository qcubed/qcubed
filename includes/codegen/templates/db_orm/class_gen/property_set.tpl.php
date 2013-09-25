		/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				///////////////////
				// Member Variables
				///////////////////
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ((!$objColumn->Identity) && (!$objColumn->Timestamp)) { ?>
				case '<?php echo $objColumn->PropertyName  ?>':
					/**
					 * Sets the value for <?php echo $objColumn->VariableName  ?> <?php if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

					 * @param <?php echo $objColumn->VariableType  ?> $mixValue
					 * @return <?php echo $objColumn->VariableType  ?>

					 */
					try {
<?php if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
						$this-><?php echo $objColumn->Reference->VariableName  ?> = null;
<?php } ?>
						return ($this-><?php echo $objColumn->VariableName  ?> = QType::Cast($mixValue, <?php echo $objColumn->VariableTypeAsConstant  ?>));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

<?php } ?>
<?php } ?>

				///////////////////
				// Member Objects
				///////////////////
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
				case '<?php echo $objColumn->Reference->PropertyName  ?>':
					/**
					 * Sets the value for the <?php echo $objColumn->Reference->VariableType  ?> object referenced by <?php echo $objColumn->VariableName  ?> <?php if ($objColumn->Identity) print '(Read-Only PK)'; else if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

					 * @param <?php echo $objColumn->Reference->VariableType  ?> $mixValue
					 * @return <?php echo $objColumn->Reference->VariableType  ?>

					 */
					if (is_null($mixValue)) {
						$this-><?php echo $objColumn->VariableName  ?> = null;
						$this-><?php echo $objColumn->Reference->VariableName  ?> = null;
						return null;
					} else {
						// Make sure $mixValue actually is a <?php echo $objColumn->Reference->VariableType  ?> object
						try {
							$mixValue = QType::Cast($mixValue, '<?php echo $objColumn->Reference->VariableType  ?>');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}

						// Make sure $mixValue is a SAVED <?php echo $objColumn->Reference->VariableType  ?> object
						if (is_null($mixValue-><?php echo $objCodeGen->TableArray[strtolower($objColumn->Reference->Table)]->ColumnArray[strtolower($objColumn->Reference->Column)]->PropertyName  ?>))
							throw new QCallerException('Unable to set an unsaved <?php echo $objColumn->Reference->PropertyName  ?> for this <?php echo $objTable->ClassName  ?>');

						// Update Local Member Variables
						$this-><?php echo $objColumn->Reference->VariableName  ?> = $mixValue;
						$this-><?php echo $objColumn->VariableName  ?> = $mixValue-><?php echo $objCodeGen->TableArray[strtolower($objColumn->Reference->Table)]->ColumnArray[strtolower($objColumn->Reference->Column)]->PropertyName  ?>;

						// Return $mixValue
						return $mixValue;
					}
					break;

<?php } ?>
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
				case '<?php echo $objReverseReference->ObjectPropertyName  ?>':
					/**
					 * Sets the value for the <?php echo $objReverseReference->VariableType  ?> object referenced by <?php echo $objReverseReference->ObjectMemberVariable  ?> (Unique)
					 * @param <?php echo $objReverseReference->VariableType  ?> $mixValue
					 * @return <?php echo $objReverseReference->VariableType  ?>

					 */
					if (is_null($mixValue)) {
						$this-><?php echo $objReverseReference->ObjectMemberVariable  ?> = null;

						// Make sure we update the adjoined <?php echo $objReverseReference->VariableType  ?> object the next time we call Save()
						$this->blnDirty<?php echo $objReverseReference->ObjectPropertyName  ?> = true;

						return null;
					} else {
						// Make sure $mixValue actually is a <?php echo $objReverseReference->VariableType  ?> object
						try {
							$mixValue = QType::Cast($mixValue, '<?php echo $objReverseReference->VariableType  ?>');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}

						// Are we setting <?php echo $objReverseReference->ObjectMemberVariable  ?> to a DIFFERENT $mixValue?
						if ((!$this-><?php echo $objReverseReference->ObjectPropertyName  ?>) || ($this-><?php echo $objReverseReference->ObjectPropertyName  ?>-><?php echo $objCodeGen->GetTable($objReverseReference->Table)->PrimaryKeyColumnArray[0]->PropertyName  ?> != $mixValue-><?php echo $objCodeGen->GetTable($objReverseReference->Table)->PrimaryKeyColumnArray[0]->PropertyName  ?>)) {
							// Yes -- therefore, set the "Dirty" flag to true
							// to make sure we update the adjoined <?php echo $objReverseReference->VariableType  ?> object the next time we call Save()
							$this->blnDirty<?php echo $objReverseReference->ObjectPropertyName  ?> = true;

							// Update Local Member Variable
							$this-><?php echo $objReverseReference->ObjectMemberVariable  ?> = $mixValue;
						} else {
							// Nope -- therefore, make no changes
						}

						// Return $mixValue
						return $mixValue;
					}
					break;

<?php } ?>
<?php } ?>
				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}