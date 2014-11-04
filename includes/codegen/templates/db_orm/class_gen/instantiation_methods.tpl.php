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
		 * Do a possible array expansion on the given node. If the node is an ExpandAsArray node,
		 * it will add to the corresponding array in the object. Otherwise, it will follow the node
		 * so that any leaf expansions can be handled.
		 *  
		 * @param DatabaseRowBase $objDbRow
		 * @param QQBaseNode $objChildNode
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 */
		
		public static function ExpandArray ($objDbRow, $strAliasPrefix, $objNode, $objPreviousItemArray, $strColumnAliasArray) {
			if (!$objNode->ChildNodeArray) {
				return null;
			}
			
			$strAlias = $strAliasPrefix . '<?= $objTable->PrimaryKeyColumnArray[0]->Name ?>';
			$strColumnAlias = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$blnExpanded = null;
			
			foreach ($objPreviousItemArray as $objPreviousItem) {
				if ($objPreviousItem-><?= $objTable->PrimaryKeyColumnArray[0]->VariableName ?> != $objDbRow->GetColumn($strColumnAlias, '<?= $objTable->PrimaryKeyColumnArray[0]->DbType ?>')) {
					continue;
				}
				
				foreach ($objNode->ChildNodeArray as $objChildNode) {	
					$strPropName = $objChildNode->_PropertyName;
					$strClassName = $objChildNode->_ClassName;
					$strLongAlias = $objChildNode->ExtendedAlias();
					$blnExpandAsArray = false;
					
					if ($objChildNode->ExpandAsArray) {
						$strPostfix = 'Array';
						$blnExpandAsArray = true;
					} else {
						$strPostfix = '';
					}
					$nodeType = $objChildNode->_Type;
					if ($nodeType == 'reverse_reference') {
						$strPrefix = '_obj';
					} elseif ($nodeType == 'association') {
						$objChildNode = $objChildNode->FirstChild();
						if ($objChildNode->IsType) {
							$strPrefix = '_int';
						} else {
							$strPrefix = '_obj';
						}
					} else {	
						$strPrefix = 'obj';
					}
					
					$strVarName = $strPrefix . $strPropName . $strPostfix;					
				
					if ($blnExpandAsArray) {				
						if (null === $objPreviousItem->$strVarName) {
							$objPreviousItem->$strVarName = array();
						}
						if (count($objPreviousItem->$strVarName)) {
							$objPreviousChildItems = $objPreviousItem->$strVarName;
							$nextAlias = $objChildNode->ExtendedAlias() . '__';
							
							$objChildItem = call_user_func(array ($strClassName, 'InstantiateDbRow'), $objDbRow, $nextAlias, $objChildNode, $objPreviousChildItems, $strColumnAliasArray, true);
							if ($objChildItem) {
								$objPreviousItem->{$strVarName}[] = $objChildItem;
								$blnExpanded = true;
							} elseif ($objChildItem === false) {
								$blnExpanded = true;
							}
						}
					} elseif (!$objChildNode->IsType) {
	
						// Follow single node if keys match						
						if (null === $objPreviousItem->$strVarName) {
							return false;
						}
						$objPreviousChildItems = array($objPreviousItem->$strVarName);
						$blnResult = call_user_func(array ($strClassName, 'ExpandArray'), $objDbRow, $strLongAlias . '__', $objChildNode, $objPreviousChildItems, $strColumnAliasArray);

						if ($blnResult) {
							$blnExpanded = true;
						}
					}
				}	
			}
			return $blnExpanded;
		}
		
		/**
		 * Instantiate a <?= $objTable->ClassName ?> from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this <?= $objTable->ClassName ?>::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param QQBaseNode $objExpandAsArrayNode
		 * @param QBaseClass $arrPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @param boolean $blnCheckDuplicate Used by ExpandArray to indicate we should not create a new object if this is a duplicate of a previoius object
		 * @return mixed Either a <?= $objTable->ClassName ?>, or false to indicate the dbrow was used in an expansion, or null to indicate that this leaf is a duplicate.
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $objExpandAsArrayNode = null, $objPreviousItemArray = null, $strColumnAliasArray = array(), $blnCheckDuplicate = false) {
			// If blank row, return null
			if (!$objDbRow) {
				return null;
			}

<?php if ($objTable->PrimaryKeyColumnArray)  { // Optimize top level accesses?>
			if (empty ($strAliasPrefix) && $objPreviousItemArray) {
				$strColumnAlias = !empty($strColumnAliasArray['<?= $objTable->PrimaryKeyColumnArray[0]->Name ?>']) ? $strColumnAliasArray['<?= $objTable->PrimaryKeyColumnArray[0]->Name ?>'] : '<?= $objTable->PrimaryKeyColumnArray[0]->Name ?>';
				$key = $objDbRow->GetColumn($strColumnAlias, '<?= $objTable->PrimaryKeyColumnArray[0]->DbType ?>');
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

				$expansionStatus = <?= $objTable->ClassName ?>::ExpandArray ($objDbRow, $strAliasPrefix, $objExpandAsArrayNode, $objPreviousItemArray, $strColumnAliasArray);
				if ($expansionStatus) {
					return false; // db row was used but no new object was created
				} elseif ($expansionStatus === null) {
					$blnCheckDuplicate = true;
				}
			}
<?php 
	} // if 
?>

			// Create a new instance of the <?= $objTable->ClassName ?> object
			$objToReturn = new <?= $objTable->ClassName ?>();
			$objToReturn->__blnRestored = true;

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
			$strAlias = $strAliasPrefix . '<?= $objColumn->Name ?>';
			$strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objToReturn-><?= $objColumn->VariableName ?> = $objDbRow->GetColumn($strAliasName, '<?= $objColumn->DbType ?>');
<?php if (($objColumn->PrimaryKey) && (!$objColumn->Identity)) { ?>
			$objToReturn->__<?= $objColumn->VariableName ?> = $objDbRow->GetColumn($strAliasName, '<?= $objColumn->DbType ?>');
<?php } ?>
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
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				$objExpansionNode = (empty($objExpansionAliasArray['<?= $objColumn->Name ?>']) ? null : $objExpansionAliasArray['<?= $objColumn->Name ?>']);
				$objToReturn-><?= $objColumn->Reference->VariableName ?> = <?= $objColumn->Reference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= $objColumn->Name ?>__', $objExpansionNode, null, $strColumnAliasArray);
			}
<?php } ?>
<?php } ?>

<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if ($objReference->Unique) { ?>
			// Check for <?= $objReference->ObjectDescription ?> Unique ReverseReference Binding
			$strAlias = $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__<?= $objCodeGen->GetTable($objReference->Table)->PrimaryKeyColumnArray[0]->Name ?>';
			$strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if ($objDbRow->ColumnExists($strAliasName)) {
				if (!is_null($objDbRow->GetColumn($strAliasName))) {
					$objExpansionNode = (empty($objExpansionAliasArray['<?= strtolower($objReference->ObjectDescription) ?>']) ? null : $objExpansionAliasArray['<?= strtolower($objReference->ObjectDescription) ?>']);
					$objToReturn->obj<?= $objReference->ObjectDescription ?> = <?= $objReference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__', $objExpansionNode, null, $strColumnAliasArray);
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
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if ($blnExpanded) {
					$objToReturn-><?= $varPrefix . $objReference->ObjectDescription ?>Array[] = <?= $objReference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__<?= $objReference->OppositeColumn ?>__', $objExpansionNode, null, $strColumnAliasArray);
				} elseif (is_null($objToReturn-><?= $varPrefix . $objReference->ObjectDescription ?>)) {
					$objToReturn-><?= $varPrefix . $objReference->ObjectDescription ?> = <?= $objReference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__<?= $objReference->OppositeColumn ?>__', $objExpansionNode, null, $strColumnAliasArray);
				}
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
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if ($blnExpanded) {
					$objToReturn->_obj<?= $objReference->ObjectDescription ?>Array[] = <?= $objReference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__', $objExpansionNode, null, $strColumnAliasArray);
				} elseif (is_null($objToReturn->_obj<?= $objReference->ObjectDescription ?>)) {
					$objToReturn->_obj<?= $objReference->ObjectDescription ?> = <?= $objReference->VariableType ?>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<?= strtolower($objReference->ObjectDescription) ?>__', $objExpansionNode, null, $strColumnAliasArray);
				}
			}

<?php } ?><?php } ?>
			return $objToReturn;
		}
		
		/**
		 * Instantiate an array of <?= $objTable->ClassNamePlural ?> from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param QQBaseNode $objExpandAsArrayNode
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
