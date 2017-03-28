//////////////////////////////////////////////////////////////
//															//
//				GETTERS and SETTERS  						//
//															//
//////////////////////////////////////////////////////////////

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
   /**
	* Gets the value of <?= $objColumn->VariableName ?> <?php if ($objColumn->Identity) print '(Read-Only PK)'; else if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Timestamp) print '(Read-Only Timestamp)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

<?php if (!$objColumn->Identity) { ?>
	* @throws QCallerException
<?php } ?>
	* @return <?= $objColumn->VariableType ?>

	*/
	public function get<?= $objColumn->PropertyName ?>() {
<?php if (!$objColumn->Identity) { ?>
		if (empty($this->__blnValid[self::<?= strtoupper($objColumn->Name) ?>_FIELD])) {
			throw new QCallerException("<?= $objColumn->PropertyName ?> has not been set nor was selected in the most recent query and is not valid.");
		}
<?php } ?>
		return $this-><?= $objColumn->VariableName ?>;
	}
<?php if ($objColumn->Reference && $objColumn->Reference->IsType) { ?>

   /**
	* Gets the value of <?= $objColumn->VariableName ?> as a type name.
	* @return string
	*/
	public function get<?= $objColumn->Reference->PropertyName ?>() {
		return <?= $objColumn->Reference->VariableType ?>::toString($this->get<?= $objColumn->PropertyName ?>());
	}
<?php } ?>

<?php if ($objColumn->Reference && !$objColumn->Reference->IsType) { ?>

    /**
     * Gets the value of the <?= $objColumn->Reference->VariableType ?> object referenced by <?= $objColumn->VariableName ?> <?php if ($objColumn->Identity) print '(Read-Only PK)'; else if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

     * If the object is not loaded, will load the object (caching it) before returning it.
     * @throws QCallerException
     * @return <?= $objColumn->Reference->VariableType ?>

     */
     public function get<?= $objColumn->Reference->PropertyName ?>() {
 		if (empty($this->__blnValid[self::<?= strtoupper($objColumn->Name) ?>_FIELD])) {
			throw new QCallerException("<?= $objColumn->PropertyName ?> has not been set nor was selected in the most recent query and is not valid.");
		}
        if ((!$this-><?= $objColumn->Reference->VariableName ?>) && (!is_null($this-><?= $objColumn->VariableName ?>))) {
            $this-><?= $objColumn->Reference->VariableName ?> = <?= $objColumn->Reference->VariableType ?>::Load($this-><?= $objColumn->VariableName ?>);
        }
        return $this-><?= $objColumn->Reference->VariableName ?>;
     }
<?php } ?>


<?php 	if ((!$objColumn->Identity) && (!$objColumn->Timestamp)) { ?>

   /**
	* Sets the value of <?= $objColumn->VariableName ?> <?php if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

	* Returns $this to allow chaining of setters.
	* @param <?= $objColumn->VariableType ?><?= $objColumn->NotNull ? '' : '|null' ?> $<?= $objColumn->VariableName ?>

    * @throws QCallerException
	* @return <?= $objTable->ClassName ?>

	*/
	public function set<?= $objColumn->PropertyName ?>($<?= $objColumn->VariableName ?>) {
<?php if ($objColumn->NotNull) { ?>
        if ($<?= $objColumn->VariableName ?> === null) {
<?php if (is_null($objColumn->Default)) { ?>
            throw new QCallerException('Cannot set <?= $objColumn->PropertyName ?> to null');
<?php } else { ?>
             $<?= $objColumn->VariableName ?> = static::<?= $objColumn->PropertyName ?>Default;
<?php } ?>
        }
<?php } ?>
		$<?= $objColumn->VariableName ?> = QType::Cast($<?= $objColumn->VariableName ?>, <?= $objColumn->VariableTypeAsConstant ?>);

		if ($this-><?= $objColumn->VariableName ?> !== $<?= $objColumn->VariableName ?>) {
<?php if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
			$this-><?= $objColumn->Reference->VariableName ?> = null; // remove the associated object
<?php } ?>
			$this-><?= $objColumn->VariableName ?> = $<?= $objColumn->VariableName ?>;
			$this->__blnDirty[self::<?= strtoupper($objColumn->Name) ?>_FIELD] = true;
		}
		$this->__blnValid[self::<?= strtoupper($objColumn->Name) ?>_FIELD] = true;
		return $this; // allows chaining
	}

<?php       if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>

    /**
     * Sets the value of the <?= $objColumn->Reference->VariableType ?> object referenced by <?= $objColumn->VariableName ?> <?php if ($objColumn->Identity) print '(Read-Only PK)'; else if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

     * @param null|<?= $objColumn->Reference->VariableType ?> $<?= $objColumn->Reference->VariableName ?>

     * @throws QCallerException
     * @return <?= $objTable->ClassName ?>

     */
    public function set<?= $objColumn->Reference->PropertyName ?>($<?= $objColumn->Reference->VariableName ?>) {
        if (is_null($<?= $objColumn->Reference->VariableName ?>)) {
            $this->set<?= $objColumn->PropertyName ?>(null);
        } else {
            $<?= $objColumn->Reference->VariableName ?> = QType::Cast($<?= $objColumn->Reference->VariableName ?>, '<?= $objColumn->Reference->VariableType ?>');

            // Make sure its a SAVED <?= $objColumn->Reference->VariableType ?> object
            if (is_null($<?= $objColumn->Reference->VariableName ?>-><?= $objCodeGen->TableArray[strtolower($objColumn->Reference->Table)]->ColumnArray[strtolower($objColumn->Reference->Column)]->PropertyName ?>)) {
                throw new QCallerException('Unable to set an unsaved <?= $objColumn->Reference->PropertyName ?> for this <?= $objTable->ClassName ?>');
            }

            // Update Local Member Variables
            $this->set<?= $objColumn->PropertyName ?>($<?= $objColumn->Reference->VariableName ?>->get<?= $objCodeGen->TableArray[strtolower($objColumn->Reference->Table)]->ColumnArray[strtolower($objColumn->Reference->Column)]->PropertyName ?>());
            $this-><?= $objColumn->Reference->VariableName ?> = $<?= $objColumn->Reference->VariableName ?>;
        }
        return $this;
    }
<?php       } ?>


<?php 	} ?>

<?php } ?>

<?php
    // Unique reverse reference properties
foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
	if ($objReverseReference->Unique) {
		$objReverseReferenceTable = $objCodeGen->TableArray[strtolower($objReverseReference->Table)];
		$objReverseReferenceColumn = $objReverseReferenceTable->ColumnArray[strtolower($objReverseReference->Column)]; ?>

   /**
    * Gets the value of the <?= $objReverseReference->VariableType ?> object that uniquely references this <?= $objTable->ClassName ?>

    * by <?= $objReverseReference->ObjectMemberVariable ?> (Unique)
    * Returns null if the object does not exist.
    * @return null|<?= $objReverseReference->VariableType ?>

    */
    public function get<?= $objReverseReference->ObjectPropertyName ?>() {
        if (!$this->__blnRestored ||
            $this-><?= $objReverseReference->ObjectMemberVariable ?> === false) {
            // Either this is a new object, or we've attempted early binding -- and the reverse reference object does not exist
            return null;
        }
        if (!$this-><?= $objReverseReference->ObjectMemberVariable ?>) {
            $this-><?= $objReverseReference->ObjectMemberVariable ?> = <?= $objReverseReference->VariableType ?>::LoadBy<?= $objReverseReferenceColumn->PropertyName ?>(<?= $objCodeGen->ImplodeObjectArray(', ', '$this->', '', 'VariableName', $objTable->PrimaryKeyColumnArray) ?>);
        }
        return $this-><?= $objReverseReference->ObjectMemberVariable ?>;
    }

   /**
    * Sets the value of the <?= $objReverseReference->VariableType ?> object that uniquely references this <?= $objTable->ClassName ?>
    * @param null|<?= $objReverseReference->VariableType ?> $<?= $objReverseReference->ObjectMemberVariable ?>

    * by <?= $objReverseReference->ObjectMemberVariable ?> (Unique)
    * @return <?= $objTable->ClassName ?>

    */
    public function set<?= $objReverseReference->ObjectPropertyName ?>($<?= $objReverseReference->ObjectMemberVariable ?>) {
        if (is_null($<?= $objReverseReference->ObjectMemberVariable ?>)) {
            $this-><?= $objReverseReference->ObjectMemberVariable ?> = null;

            // Make sure we update the adjoined <?= $objReverseReference->VariableType ?> object the next time we call Save()
            $this->blnDirty<?= $objReverseReference->ObjectPropertyName ?> = true;
        } else {
            $<?= $objReverseReference->ObjectMemberVariable ?> = QType::Cast($<?= $objReverseReference->ObjectMemberVariable ?>, '<?= $objReverseReference->VariableType ?>');

            // Are we setting <?= $objReverseReference->ObjectMemberVariable ?> to a DIFFERENT $<?= $objReverseReference->ObjectMemberVariable ?>?
            if ((!$this-><?= $objReverseReference->ObjectPropertyName ?>) || ($this-><?= $objReverseReference->ObjectPropertyName ?>-><?= $objCodeGen->GetTable($objReverseReference->Table)->PrimaryKeyColumnArray[0]->PropertyName ?> != $<?= $objReverseReference->ObjectMemberVariable ?>-><?= $objCodeGen->GetTable($objReverseReference->Table)->PrimaryKeyColumnArray[0]->PropertyName ?>)) {
                // Yes -- therefore, set the "Dirty" flag to true
                // to make sure we update the adjoined <?= $objReverseReference->VariableType ?> object the next time we call Save()
                $this->blnDirty<?= $objReverseReference->ObjectPropertyName ?> = true;

                // Update Local Member Variable
                $this-><?= $objReverseReference->ObjectMemberVariable ?> = $<?= $objReverseReference->ObjectMemberVariable ?>;
            }
        }
        return $this;
    }


<?php }
} ?>
