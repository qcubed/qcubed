<?php 
	// Preliminary calculations and helper routines here

	$blnImmediateExpansions = $objTable->HasImmediateArrayExpansions();
	$blnExtendedExpansions = $objTable->HasExtendedArrayExpansions($objCodeGen);

	if (count($objTable->PrimaryKeyColumnArray) > 1 &&
			$blnImmediateExpansions) {
		throw QCallerException ("Multi-key table with array expansion not supported.");
	}
	
		
?>///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a <?= $objTable->ClassName ?> from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this <?= $objTable->ClassName ?>::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param QQNode $objExpandAsArrayNode
		 * @param QBaseClass $objPreviousItemArray Used by expansion code to aid in expanding arrays
		 * @param string[] $strColumnAliasArray Array of column aliases mapping names in the query to items in the object
		 * @param boolean $blnCheckDuplicate Used by ExpandArray to indicate we should not create a new object if this is a duplicate of a previoius object
		 * @param string $strParentExpansionKey If this is part of an expansion, indicates what the parent item is
		 * @param mixed $objExpansionParent If this is part of an expansion, is the object corresponding to the key so that we can refer back to the parent object
		 * @return mixed Either a <?= $objTable->ClassName ?>, or false to indicate the dbrow was used in an expansion, or null to indicate that this leaf is a duplicate.
		*/
		public static function InstantiateDbRow(
				$objDbRow,
				$strAliasPrefix = null,
				$objExpandAsArrayNode = null,
				$objPreviousItemArray = null,
				$strColumnAliasArray = array(),
				$blnCheckDuplicate = false,
				$strParentExpansionKey = null,
				$objExpansionParent = null) {
			// If blank row, return null
			if (!$objDbRow) {
				return null;
			}

			$strColumns = $objDbRow->GetColumnNameArray();
			$strColumnKeys = array_fill_keys(array_keys($strColumns), 1); // to be able to use isset

<?php if ($objTable->PrimaryKeyColumnArray)  { // Optimize top level accesses?>
			$key = static::GetRowPrimaryKey ($objDbRow, $strAliasPrefix, $strColumnAliasArray);
			if (empty ($strAliasPrefix) && $objPreviousItemArray) {
				$objPreviousItemArray = (!empty ($objPreviousItemArray[$key]) ? $objPreviousItemArray[$key] : null);
			}
<?php } ?>			
			
<?php 
	if ($blnImmediateExpansions || $blnExtendedExpansions) { 
?>
			// See if we're doing an array expansion on the previous item
			if ($objExpandAsArrayNode && 
					is_array($objPreviousItemArray) && 
					count($objPreviousItemArray)) {

				$expansionStatus = static::ExpandArray ($objDbRow, $strAliasPrefix, $objExpandAsArrayNode, $objPreviousItemArray, $strColumnAliasArray);
				if ($expansionStatus) {
					return false; // db row was used but no new object was created
				} elseif ($expansionStatus === null) {
					$blnCheckDuplicate = true;
				}
			}
<?php 
	} // if 
?>

<?php if ($objTable->PrimaryKeyColumnArray)  { ?>

			$objToReturn = static::GetFromCache ($key);
			if (empty($objToReturn)) {
<?php } ?>
				// Create a new instance of the <?= $objTable->ClassName ?> object
				$objToReturn = new <?= $objTable->ClassName ?>();
				$objToReturn->__blnRestored = true;
				$blnNoCache = false;

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
				$strAlias = $strAliasPrefix . '<?= $objColumn->Name ?>';
				$strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if (isset ($strColumnKeys[$strAliasName])) {
					$mixVal = $strColumns[$strAliasName];
<?php if ($objColumn->VariableType == QType::Boolean) { ?>
					$objToReturn-><?= $objColumn->VariableName ?> = $objDbRow->ResolveBooleanValue($mixVal);
<?php } else { ?>
<?php 	if ($s = QDatabaseCodeGen::GetCastString($objColumn)) { ?>
					if ($mixVal !== null) {
						<?= $s ?>

					}
<?php 	} ?>
					$objToReturn-><?= $objColumn->VariableName ?> = $mixVal;
<?php } ?>
<?php if (($objColumn->PrimaryKey) && (!$objColumn->Identity)) { ?>
					$objToReturn->__<?= $objColumn->VariableName ?> = $mixVal;
<?php } ?>
				}
				else {
					$blnNoCache = true;
				}
<?php } ?>
<?php if ($objTable->PrimaryKeyColumnArray)  { ?>

				assert ('$key === null || $objToReturn->PrimaryKey() == $key');

				if (!$blnNoCache) {
					$objToReturn->WriteToCache();
				}
			}
<?php } ?>

			if (isset($objPreviousItemArray) && is_array($objPreviousItemArray) && $blnCheckDuplicate) {
				foreach ($objPreviousItemArray as $objPreviousItem) {
<?php foreach ($objTable->PrimaryKeyColumnArray as $col) { ?>
					if ($objToReturn-><?= $col->PropertyName ?> != $objPreviousItem-><?= $col->PropertyName ?>) {
						continue;
					}
<?php } ?>
					// this is a duplicate in a complex join
					return null; // indicates no object created and the db row has not been used
				}
			}
			
			// Instantiate Virtual Attributes
			$strVirtualPrefix = $strAliasPrefix . '__';
			$strVirtualPrefixLength = strlen($strVirtualPrefix);
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				if (strncmp($strColumnName, $strVirtualPrefix, $strVirtualPrefixLength) == 0)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}


			// Prepare to Check for Early/Virtual Binding

			$objExpansionAliasArray = array();
			if ($objExpandAsArrayNode) {
				$objExpansionAliasArray = $objExpandAsArrayNode->ChildNodeArray;
			}

			if (!$strAliasPrefix)
				$strAliasPrefix = '<?= $objTable->Name ?>__';

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->Reference && !$objColumn->Reference->IsType) { ?>
			// Check for <?= $objColumn->Reference->PropertyName ?> Early Binding
			$strAlias = $strAliasPrefix . '<?= $objColumn->Name ?>__<?= $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->Name ?>';
			$strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (isset ($strColumns[$strAliasName])) {
				$objExpansionNode = (empty($objExpansionAliasArray['<?= $objColumn->Name ?>']) ? null : $objExpansionAliasArray['<?= $objColumn->Name ?>']);
				$objToReturn-><?= $objColumn->Reference->VariableName ?> = <?= $objColumn->Reference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= $objColumn->Name ?>__', $objExpansionNode, null, $strColumnAliasArray, false, '<?= strtolower($objColumn->Reference->ReverseReference->ObjectDescription) ?>', $objToReturn);
			}
		    elseif ($strParentExpansionKey === '<?= $objColumn->Name ?>' && $objExpansionParent) {
				$objToReturn-><?= $objColumn->Reference->VariableName ?> = $objExpansionParent;
		    }

	<?php } ?>
<?php } ?>

<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if ($objReference->Unique) { ?>
			// Check for <?= $objReference->ObjectDescription ?> Unique ReverseReference Binding
			$strAlias = $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__<?= $objCodeGen->GetTable($objReference->Table)->PrimaryKeyColumnArray[0]->Name ?>';
			$strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (array_key_exists ($strAliasName, $strColumns)) {
				if (!is_null($strColumns[$strAliasName])) {
					$objExpansionNode = (empty($objExpansionAliasArray['<?= strtolower($objReference->ObjectDescription) ?>']) ? null : $objExpansionAliasArray['<?= strtolower($objReference->ObjectDescription) ?>']);
					$objToReturn->obj<?= $objReference->ObjectDescription ?> = <?= $objReference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__', $objExpansionNode, null, $strColumnAliasArray, false, '<?= $objReference->Column ?>', $objToReturn);
				}
				else {
					// We ATTEMPTED to do an Early Bind but the Object Doesn't Exist
					// Let's set to FALSE so that the object knows not to try and re-query again
					$objToReturn->obj<?= $objReference->ObjectDescription ?> = false;
				}
			}

<?php } ?><?php } ?>
				
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
<?php 
	$objAssociatedTable = $objCodeGen->GetTable($objReference->AssociatedTable);
	$varPrefix = (is_a($objAssociatedTable, 'QTypeTable') ? '_int' : '_obj');
?>
			// Check for <?= $objReference->ObjectDescription ?> Virtual Binding
			$strAlias = $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__<?= $objReference->OppositeColumn ?>__<?= $objCodeGen->GetTable($objReference->AssociatedTable)->PrimaryKeyColumnArray[0]->Name ?>';
			$strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objExpansionNode = (empty($objExpansionAliasArray['<?= strtolower($objReference->ObjectDescription) ?>']) ? null : $objExpansionAliasArray['<?= strtolower($objReference->ObjectDescription) ?>']);
			$blnExpanded = ($objExpansionNode && $objExpansionNode->ExpandAsArray);
			if ($blnExpanded && null === $objToReturn-><?= $varPrefix . $objReference->ObjectDescription ?>Array) {
				$objToReturn-><?= $varPrefix . $objReference->ObjectDescription ?>Array = array();
			}
			if (isset ($strColumns[$strAliasName])) {
				if ($blnExpanded) {
					$objToReturn-><?= $varPrefix . $objReference->ObjectDescription ?>Array[] = <?= $objReference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__<?= $objReference->OppositeColumn ?>__', $objExpansionNode, null, $strColumnAliasArray, false, '<?= strtolower($objReference->OppositeObjectDescription) ?>', $objToReturn);
				} elseif (is_null($objToReturn-><?= $varPrefix . $objReference->ObjectDescription ?>)) {
					$objToReturn-><?= $varPrefix . $objReference->ObjectDescription ?> = <?= $objReference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__<?= $objReference->OppositeColumn ?>__', $objExpansionNode, null, $strColumnAliasArray, false, '<?= strtolower($objReference->OppositeObjectDescription) ?>', $objToReturn);
				}
			}
			elseif ($strParentExpansionKey === '<?= strtolower($objReference->ObjectDescription) ?>' && $objExpansionParent) {
				$objToReturn-><?= $varPrefix . $objReference->ObjectDescription ?> = $objExpansionParent;
			}

<?php } ?>

<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if (!$objReference->Unique) { ?>
			// Check for <?= $objReference->ObjectDescription ?> Virtual Binding
			$strAlias = $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__<?= $objCodeGen->GetTable($objReference->Table)->PrimaryKeyColumnArray[0]->Name ?>';
			$strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objExpansionNode = (empty($objExpansionAliasArray['<?= strtolower($objReference->ObjectDescription) ?>']) ? null : $objExpansionAliasArray['<?= strtolower($objReference->ObjectDescription) ?>']);
			$blnExpanded = ($objExpansionNode && $objExpansionNode->ExpandAsArray);
			if ($blnExpanded && null === $objToReturn->_obj<?= $objReference->ObjectDescription ?>Array)
				$objToReturn->_obj<?= $objReference->ObjectDescription ?>Array = array();
			if (isset ($strColumns[$strAliasName])) {
				if ($blnExpanded) {
					$objToReturn->_obj<?= $objReference->ObjectDescription ?>Array[] = <?= $objReference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__', $objExpansionNode, null, $strColumnAliasArray, false, '<?= $objReference->Column ?>', $objToReturn);
				} elseif (is_null($objToReturn->_obj<?= $objReference->ObjectDescription ?>)) {
					$objToReturn->_obj<?= $objReference->ObjectDescription ?> = <?= $objReference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__', $objExpansionNode, null, $strColumnAliasArray, false, '<?= $objReference->Column ?>', $objToReturn);
				}
			}
			elseif ($strParentExpansionKey === '<?= strtolower($objReference->ObjectDescription) ?>' && $objExpansionParent) {
				$objToReturn->_obj<?= $objReference->ObjectDescription ?> = $objExpansionParent;
			}

<?php } ?><?php } ?>
			return $objToReturn;
		}
		
		/**
		 * Instantiate an array of <?= $objTable->ClassNamePlural ?> from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param QQNode $objExpandAsArrayNode
		 * @param string[] $strColumnAliasArray
		 * @return <?= $objTable->ClassName ?>[]
		 */
		public static function InstantiateDbResult(QDatabaseResultBase $objDbResult, $objExpandAsArrayNode = null, $strColumnAliasArray = null) {
			$objToReturn = array();

			if (!$strColumnAliasArray)
				$strColumnAliasArray = array();

			// If blank resultset, then return empty array
			if (!$objDbResult)
				return $objToReturn;

			// Load up the return array with each row
			if ($objExpandAsArrayNode) {
				$objToReturn = array();
				$objPrevItemArray = array();
				while ($objDbRow = $objDbResult->GetNextRow()) {
					$objItem = <?= $objTable->ClassName ?>::InstantiateDbRow($objDbRow, null, $objExpandAsArrayNode, $objPrevItemArray, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
<?php if ($objTable->PrimaryKeyColumnArray)  {?>
						$objPrevItemArray[$objItem-><?= $objTable->PrimaryKeyColumnArray[0]->VariableName ?>][] = $objItem;
<?php } else { ?>
						$objPrevItemArray[] = $objItem;
		
<?php } ?>		
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = <?= $objTable->ClassName ?>::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}


		/**
		 * Instantiate a single <?= $objTable->ClassName ?> object from a query cursor (e.g. a DB ResultSet).
		 * Cursor is automatically moved to the "next row" of the result set.
		 * Will return NULL if no cursor or if the cursor has no more rows in the resultset.
		 * @param QDatabaseResultBase $objDbResult cursor resource
		 * @return <?= $objTable->ClassName ?> next row resulting from the query
		 */
		public static function InstantiateCursor(QDatabaseResultBase $objDbResult) {
			// If blank resultset, then return empty result
			if (!$objDbResult) return null;

			// If empty resultset, then return empty result
			$objDbRow = $objDbResult->GetNextRow();
			if (!$objDbRow) return null;

			// We need the Column Aliases
			$strColumnAliasArray = $objDbResult->QueryBuilder->ColumnAliasArray;
			if (!$strColumnAliasArray) $strColumnAliasArray = array();

			// Pull Expansions
			$objExpandAsArrayNode = $objDbResult->QueryBuilder->ExpandAsArrayNode;
			if (!empty ($objExpandAsArrayNode)) {
				throw new QCallerException ("Cannot use InstantiateCursor with ExpandAsArray");
			}

			// Load up the return result with a row and return it
			return <?= $objTable->ClassName ?>::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
		}
