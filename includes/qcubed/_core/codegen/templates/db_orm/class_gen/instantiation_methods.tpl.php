///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a <?php echo $objTable->ClassName  ?> from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this <?php echo $objTable->ClassName  ?>::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $arrPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return <?php echo $objTable->ClassName  ?>

		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $arrPreviousItems = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow) {
				return null;
			}
<?php 
	$intCount = count($objTable->ManyToManyReferenceArray);
	foreach ($objTable->ReverseReferenceArray as $objReverseReference)
		if (!$objReverseReference->Unique)
			$intCount++;
?><?php if ($intCount && (count($objTable->PrimaryKeyColumnArray) == 1)) { ?>
			// See if we're doing an array expansion on the previous item
			$strAlias = $strAliasPrefix . '<?php echo $objTable->PrimaryKeyColumnArray[0]->Name  ?>';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (($strExpandAsArrayNodes) && is_array($arrPreviousItems) && count($arrPreviousItems)) {
				foreach ($arrPreviousItems as $objPreviousItem) {
					if ($objPreviousItem-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?> == $objDbRow->GetColumn($strAliasName, '<?php echo $objTable->PrimaryKeyColumnArray[0]->DbType  ?>')) {
						// We are.  Now, prepare to check for ExpandAsArray clauses
						$blnExpandedViaArray = false;
						if (!$strAliasPrefix)
							$strAliasPrefix = '<?php echo $objTable->Name  ?>__';

<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
						// Expanding many-to-many references: <?php echo $objReference->ObjectDescription  ?>

						$strAlias = $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__<?php echo $objReference->OppositeColumn  ?>__<?php echo $objCodeGen->GetTable($objReference->AssociatedTable)->PrimaryKeyColumnArray[0]->Name  ?>';
						$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
<?php 
	$objAssociatedTable = $objCodeGen->GetTable($objReference->AssociatedTable);
	$varPrefix = (is_a($objAssociatedTable, 'QTypeTable') ? '_int' : '_obj');
?>
						if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
							(!is_null($objDbRow->GetColumn($strAliasName)))) {
							if(null === $objPreviousItem-><?php echo $varPrefix . $objReference->ObjectDescription  ?>Array)
								$objPreviousItem-><?php echo $varPrefix . $objReference->ObjectDescription  ?>Array = array();
							if ($intPreviousChildItemCount = count($objPreviousItem-><?php echo  $varPrefix . $objReference->ObjectDescription  ?>Array)) {
								$objPreviousChildItems = $objPreviousItem-><?php echo $varPrefix . $objReference->ObjectDescription  ?>Array;
								$objChildItem = <?php echo $objReference->VariableType  ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__<?php echo $objReference->OppositeColumn  ?>__', $strExpandAsArrayNodes, $objPreviousChildItems, $strColumnAliasArray);
								if ($objChildItem) {
									$objPreviousItem-><?php echo $varPrefix . $objReference->ObjectDescription  ?>Array[] = $objChildItem;
								}
							} else {
								$objPreviousItem-><?php echo $varPrefix . $objReference->ObjectDescription  ?>Array[] = <?php echo $objReference->VariableType  ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__<?php echo $objReference->OppositeColumn  ?>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
							}
							$blnExpandedViaArray = true;
						}

<?php } ?>


<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if (!$objReference->Unique) { ?>
						// Expanding reverse references: <?php echo $objReference->ObjectDescription  ?>

						$strAlias = $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__<?php echo $objCodeGen->GetTable($objReference->Table)->PrimaryKeyColumnArray[0]->Name  ?>';
						$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
						if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
							(!is_null($objDbRow->GetColumn($strAliasName)))) {
							if(null === $objPreviousItem->_obj<?php echo $objReference->ObjectDescription  ?>Array)
								$objPreviousItem->_obj<?php echo $objReference->ObjectDescription  ?>Array = array();
							if ($intPreviousChildItemCount = count($objPreviousItem->_obj<?php echo $objReference->ObjectDescription  ?>Array)) {
								$objPreviousChildItems = $objPreviousItem->_obj<?php echo $objReference->ObjectDescription  ?>Array;
								$objChildItem = <?php echo $objReference->VariableType  ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__', $strExpandAsArrayNodes, $objPreviousChildItems, $strColumnAliasArray);
								if ($objChildItem) {
									$objPreviousItem->_obj<?php echo $objReference->ObjectDescription  ?>Array[] = $objChildItem;
								}
							} else {
								$objPreviousItem->_obj<?php echo $objReference->ObjectDescription  ?>Array[] = <?php echo $objReference->VariableType  ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
							}
							$blnExpandedViaArray = true;
						}

<?php } ?><?php } ?>
						// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
						if ($blnExpandedViaArray) {
							return false;
						} else if ($strAliasPrefix == '<?php echo $objTable->Name  ?>__') {
							$strAliasPrefix = null;
						}
					}
				}
			}
<?php } ?>

			// Create a new instance of the <?php echo $objTable->ClassName  ?> object
			$objToReturn = new <?php echo $objTable->ClassName  ?>();
			$objToReturn->__blnRestored = true;

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
			$strAlias = $strAliasPrefix . '<?php echo $objColumn->Name  ?>';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objToReturn-><?php echo $objColumn->VariableName  ?> = $objDbRow->GetColumn($strAliasName, '<?php echo $objColumn->DbType  ?>');
<?php if (($objColumn->PrimaryKey) && (!$objColumn->Identity)) { ?>
			$objToReturn->__<?php echo $objColumn->VariableName  ?> = $objDbRow->GetColumn($strAliasName, '<?php echo $objColumn->DbType  ?>');
<?php } ?>
<?php } ?><?php GO_BACK(1); ?>


			if (isset($arrPreviousItems) && is_array($arrPreviousItems)) {
				foreach ($arrPreviousItems as $objPreviousItem) {
<?php foreach ($objTable->PrimaryKeyColumnArray as $col) { ?>
					if ($objToReturn-><?php echo $col->PropertyName  ?> != $objPreviousItem-><?php echo $col->PropertyName  ?>) {
						continue;
					}
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
					$prevCnt = count($objPreviousItem->_obj<?php echo $objReference->ObjectDescription  ?>Array);
					$cnt = count($objToReturn->_obj<?php echo $objReference->ObjectDescription  ?>Array);
					if ($prevCnt != $cnt)
						continue;
					if ($prevCnt == 0 || $cnt == 0 || !array_diff($objPreviousItem->_obj<?php echo $objReference->ObjectDescription  ?>Array, $objToReturn->_obj<?php echo $objReference->ObjectDescription  ?>Array)) {
						continue;
					}

<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if (!$objReference->Unique) { ?>
					$prevCnt = count($objPreviousItem->_obj<?php echo $objReference->ObjectDescription  ?>Array);
					$cnt = count($objToReturn->_obj<?php echo $objReference->ObjectDescription  ?>Array);
					if ($prevCnt != $cnt)
					    continue;
					if ($prevCnt == 0 || $cnt == 0 || !array_diff($objPreviousItem->_obj<?php echo $objReference->ObjectDescription  ?>Array, $objToReturn->_obj<?php echo $objReference->ObjectDescription  ?>Array)) {
						continue;
					}

<?php } ?><?php } ?>
<?php } ?>

					// complete match - all primary key columns are the same
					return null;
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
			if (!$strAliasPrefix)
				$strAliasPrefix = '<?php echo $objTable->Name  ?>__';

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->Reference && !$objColumn->Reference->IsType) { ?>
			// Check for <?php echo $objColumn->Reference->PropertyName  ?> Early Binding
			$strAlias = $strAliasPrefix . '<?php echo $objColumn->Name  ?>__<?php echo $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->Name  ?>';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn-><?php echo $objColumn->Reference->VariableName  ?> = <?php echo $objColumn->Reference->VariableType  ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?php echo $objColumn->Name  ?>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);

<?php } ?>
<?php } ?>

<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if ($objReference->Unique) { ?>
			// Check for <?php echo $objReference->ObjectDescription  ?> Unique ReverseReference Binding
			$strAlias = $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__<?php echo $objCodeGen->GetTable($objReference->Table)->PrimaryKeyColumnArray[0]->Name  ?>';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if ($objDbRow->ColumnExists($strAliasName)) {
				if (!is_null($objDbRow->GetColumn($strAliasName)))
					$objToReturn->obj<?php echo $objReference->ObjectDescription  ?> = <?php echo $objReference->VariableType  ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					// We ATTEMPTED to do an Early Bind but the Object Doesn't Exist
					// Let's set to FALSE so that the object knows not to try and re-query again
					$objToReturn->obj<?php echo $objReference->ObjectDescription  ?> = false;
			}

<?php } ?><?php } ?>

<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
			// Check for <?php echo $objReference->ObjectDescription  ?> Virtual Binding
			$strAlias = $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__<?php echo $objReference->OppositeColumn  ?>__<?php echo $objCodeGen->GetTable($objReference->AssociatedTable)->PrimaryKeyColumnArray[0]->Name  ?>';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$blnExpanded = $strExpandAsArrayNodes && array_key_exists($strAlias, $strExpandAsArrayNodes);
			if ($blnExpanded && null === $objToReturn->_obj<?php echo $objReference->ObjectDescription  ?>Array)
				$objToReturn->_obj<?php echo $objReference->ObjectDescription  ?>Array = array();
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if ($blnExpanded)
					$objToReturn->_obj<?php echo $objReference->ObjectDescription  ?>Array[] = <?php echo $objReference->VariableType  ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__<?php echo $objReference->OppositeColumn  ?>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_obj<?php echo $objReference->ObjectDescription  ?> = <?php echo $objReference->VariableType  ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__<?php echo $objReference->OppositeColumn  ?>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

<?php } ?>

<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if (!$objReference->Unique) { ?>
			// Check for <?php echo $objReference->ObjectDescription  ?> Virtual Binding
			$strAlias = $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__<?php echo $objCodeGen->GetTable($objReference->Table)->PrimaryKeyColumnArray[0]->Name  ?>';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$blnExpanded = $strExpandAsArrayNodes && array_key_exists($strAlias, $strExpandAsArrayNodes);
			if ($blnExpanded && null === $objToReturn->_obj<?php echo $objReference->ObjectDescription  ?>Array)
				$objToReturn->_obj<?php echo $objReference->ObjectDescription  ?>Array = array();
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if ($blnExpanded)
					$objToReturn->_obj<?php echo $objReference->ObjectDescription  ?>Array[] = <?php echo $objReference->VariableType  ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_obj<?php echo $objReference->ObjectDescription  ?> = <?php echo $objReference->VariableType  ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?php echo strtolower($objReference->ObjectDescription)  ?>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

<?php } ?><?php } ?>
			return $objToReturn;
		}

		/**
		 * Instantiate an array of <?php echo $objTable->ClassNamePlural  ?> from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return <?php echo $objTable->ClassName  ?>[]
		 */
		public static function InstantiateDbResult(QDatabaseResultBase $objDbResult, $strExpandAsArrayNodes = null, $strColumnAliasArray = null) {
			$objToReturn = array();

			if (!$strColumnAliasArray)
				$strColumnAliasArray = array();

			// If blank resultset, then return empty array
			if (!$objDbResult)
				return $objToReturn;

			// Load up the return array with each row
			if ($strExpandAsArrayNodes) {
				$objToReturn = array();
				while ($objDbRow = $objDbResult->GetNextRow()) {
					$objItem = <?php echo $objTable->ClassName  ?>::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objToReturn, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = <?php echo $objTable->ClassName  ?>::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}


		/**
		 * Instantiate a single <?php echo $objTable->ClassName  ?> object from a query cursor (e.g. a DB ResultSet).
		 * Cursor is automatically moved to the "next row" of the result set.
		 * Will return NULL if no cursor or if the cursor has no more rows in the resultset.
		 * @param QDatabaseResultBase $objDbResult cursor resource
		 * @return <?php echo $objTable->ClassName  ?> next row resulting from the query
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

			// Pull Expansions (if applicable)
			$strExpandAsArrayNodes = $objDbResult->QueryBuilder->ExpandAsArrayNodes;

			// Load up the return result with a row and return it
			return <?php echo $objTable->ClassName  ?>::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, null, $strColumnAliasArray);
		}
