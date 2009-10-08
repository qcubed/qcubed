///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a <%= $objTable->ClassName %> from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this <%= $objTable->ClassName %>::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $arrPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return <%= $objTable->ClassName %>
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $arrPreviousItems = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow) {
				return null;
			}
<%
	$intCount = count($objTable->ManyToManyReferenceArray);
	foreach ($objTable->ReverseReferenceArray as $objReverseReference)
		if (!$objReverseReference->Unique)
			$intCount++;
%><% if ($intCount && (count($objTable->PrimaryKeyColumnArray) == 1)) { %>
			// See if we're doing an array expansion on the previous item
			$strAlias = $strAliasPrefix . '<%= $objTable->PrimaryKeyColumnArray[0]->Name %>';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (($strExpandAsArrayNodes) && is_array($arrPreviousItems) && count($arrPreviousItems)) {
				foreach ($arrPreviousItems as $objPreviousItem) {            
					if ($objPreviousItem-><%= $objTable->PrimaryKeyColumnArray[0]->VariableName %> == $objDbRow->GetColumn($strAliasName, '<%= $objTable->PrimaryKeyColumnArray[0]->DbType %>')) {        
						// We are.  Now, prepare to check for ExpandAsArray clauses
						$blnExpandedViaArray = false;
						if (!$strAliasPrefix)
							$strAliasPrefix = '<%= $objTable->Name %>__';

		<% foreach ($objTable->ManyToManyReferenceArray as $objReference) { %>
						// Expanding many-to-many references: <%=$objReference->ObjectDescription %>
						$strAlias = $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__<%= $objReference->OppositeColumn %>__<%= $objCodeGen->GetTable($objReference->AssociatedTable)->PrimaryKeyColumnArray[0]->Name %>';
						$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
						if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
							(!is_null($objDbRow->GetColumn($strAliasName)))) {
							if ($intPreviousChildItemCount = count($objPreviousItem->_obj<%= $objReference->ObjectDescription %>Array)) {
								$objPreviousChildItems = $objPreviousItem->_obj<%= $objReference->ObjectDescription %>Array;
								$objChildItem = <%= $objReference->VariableType %>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__<%= $objReference->OppositeColumn %>__', $strExpandAsArrayNodes, $objPreviousChildItems, $strColumnAliasArray);
								if ($objChildItem) {
									$objPreviousItem->_obj<%= $objReference->ObjectDescription %>Array[] = $objChildItem;
								}
							} else {
								$objPreviousItem->_obj<%= $objReference->ObjectDescription %>Array[] = <%= $objReference->VariableType %>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__<%= $objReference->OppositeColumn %>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
							}
							$blnExpandedViaArray = true;
						}

		<% } %>

		<% foreach ($objTable->ReverseReferenceArray as $objReference) { %><% if (!$objReference->Unique) { %>
						// Expanding reverse references: <%=$objReference->ObjectDescription %>
						$strAlias = $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__<%= $objCodeGen->GetTable($objReference->Table)->PrimaryKeyColumnArray[0]->Name %>';
						$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
						if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
							(!is_null($objDbRow->GetColumn($strAliasName)))) {
							if ($intPreviousChildItemCount = count($objPreviousItem->_obj<%= $objReference->ObjectDescription %>Array)) {
								$objPreviousChildItems = $objPreviousItem->_obj<%= $objReference->ObjectDescription %>Array;
								$objChildItem = <%= $objReference->VariableType %>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__', $strExpandAsArrayNodes, $objPreviousChildItems, $strColumnAliasArray);
								if ($objChildItem) {
									$objPreviousItem->_obj<%= $objReference->ObjectDescription %>Array[] = $objChildItem;
								}
							} else {
								$objPreviousItem->_obj<%= $objReference->ObjectDescription %>Array[] = <%= $objReference->VariableType %>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
							}
							$blnExpandedViaArray = true;
						}

		<% } %><% } %>
						// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
						if ($blnExpandedViaArray) {
							return false;
						} else if ($strAliasPrefix == '<%= $objTable->Name %>__') {
							$strAliasPrefix = null;
						}
					}
				}
			}
		<% } %>

			// Create a new instance of the <%= $objTable->ClassName %> object
			$objToReturn = new <%= $objTable->ClassName %>();
			$objToReturn->__blnRestored = true;

<% foreach ($objTable->ColumnArray as $objColumn) { %>
			$strAliasName = array_key_exists($strAliasPrefix . '<%= $objColumn->Name %>', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . '<%= $objColumn->Name %>'] : $strAliasPrefix . '<%= $objColumn->Name %>';
			$objToReturn-><%= $objColumn->VariableName %> = $objDbRow->GetColumn($strAliasName, '<%= $objColumn->DbType %>');
	<% if (($objColumn->PrimaryKey) && (!$objColumn->Identity)) { %>
			$objToReturn->__<%= $objColumn->VariableName %> = $objDbRow->GetColumn($strAliasName, '<%= $objColumn->DbType %>');
	<% } %>
<% } %><%-%>

			if (isset($arrPreviousItems) && is_array($arrPreviousItems)) {
				foreach ($arrPreviousItems as $objPreviousItem) {
<% foreach ($objTable->PrimaryKeyColumnArray as $col) { %>
					if ($objToReturn-><%= $col->PropertyName %> != $objPreviousItem-><%= $col->PropertyName %>) {
						continue;
					}
	<% foreach ($objTable->ManyToManyReferenceArray as $objReference) { %>
					if (array_diff($objPreviousItem->_obj<%= $objReference->ObjectDescription %>Array, $objToReturn->_obj<%= $objReference->ObjectDescription %>Array) != null) {
						continue;
					}
	<% } %>
	<% foreach ($objTable->ReverseReferenceArray as $objReference) { %><% if (!$objReference->Unique) { %>
					if (array_diff($objPreviousItem->_obj<%= $objReference->ObjectDescription %>Array, $objToReturn->_obj<%= $objReference->ObjectDescription %>Array) != null) {
						continue;
					}
	<% } %><% } %>
<% } %>

					// complete match - all primary key columns are the same
					return null;
				}
			}

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = '<%= $objTable->Name %>__';

<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if ($objColumn->Reference && !$objColumn->Reference->IsType) { %>
			// Check for <%= $objColumn->Reference->PropertyName %> Early Binding
			$strAlias = $strAliasPrefix . '<%= $objColumn->Name %>__<%= $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->Name %>';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn-><%= $objColumn->Reference->VariableName %> = <%= $objColumn->Reference->VariableType %>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<%= $objColumn->Name %>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);

	<% } %>
<% } %>

<% foreach ($objTable->ReverseReferenceArray as $objReference) { %><% if ($objReference->Unique) { %>
			// Check for <%= $objReference->ObjectDescription %> Unique ReverseReference Binding
			$strAlias = $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__<%= $objCodeGen->GetTable($objReference->Table)->PrimaryKeyColumnArray[0]->Name %>';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if ($objDbRow->ColumnExists($strAliasName)) {
				if (!is_null($objDbRow->GetColumn($strAliasName)))
					$objToReturn->obj<%= $objReference->ObjectDescription %> = <%= $objReference->VariableType %>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					// We ATTEMPTED to do an Early Bind but the Object Doesn't Exist
					// Let's set to FALSE so that the object knows not to try and re-query again
					$objToReturn->obj<%= $objReference->ObjectDescription %> = false;
			}

<% } %><% } %>

<% foreach ($objTable->ManyToManyReferenceArray as $objReference) { %>
			// Check for <%= $objReference->ObjectDescription %> Virtual Binding
			$strAlias = $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__<%= $objReference->OppositeColumn %>__<%= $objCodeGen->GetTable($objReference->AssociatedTable)->PrimaryKeyColumnArray[0]->Name %>';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_obj<%= $objReference->ObjectDescription %>Array[] = <%= $objReference->VariableType %>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__<%= $objReference->OppositeColumn %>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_obj<%= $objReference->ObjectDescription %> = <%= $objReference->VariableType %>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__<%= $objReference->OppositeColumn %>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

<% } %>

<% foreach ($objTable->ReverseReferenceArray as $objReference) { %><% if (!$objReference->Unique) { %>
			// Check for <%= $objReference->ObjectDescription %> Virtual Binding
			$strAlias = $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__<%= $objCodeGen->GetTable($objReference->Table)->PrimaryKeyColumnArray[0]->Name %>';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_obj<%= $objReference->ObjectDescription %>Array[] = <%= $objReference->VariableType %>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_obj<%= $objReference->ObjectDescription %> = <%= $objReference->VariableType %>::InstantiateDbRow($objDbRow, $strAliasPrefix . '<%= strtolower($objReference->ObjectDescription) %>__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

<% } %><% } %>
			return $objToReturn;
		}

		/**
		 * Instantiate an array of <%= $objTable->ClassNamePlural %> from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return <%= $objTable->ClassName %>[]
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
					$objItem = <%= $objTable->ClassName %>::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objToReturn, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = <%= $objTable->ClassName %>::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}