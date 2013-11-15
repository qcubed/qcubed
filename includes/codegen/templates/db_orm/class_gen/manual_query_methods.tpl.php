////////////////////////////////////////////////////////
		// METHODS for MANUAL QUERY SUPPORT (aka Beta 2 Queries)
		////////////////////////////////////////////////////////

		/**
		 * Internally called method to assist with SQL Query options/preferences for single row loaders.
		 * Any Load (single row) method can use this method to get the Database object.
		 * @param string $objDatabase reference to the Database object to be queried
		 */
		protected static function QueryHelper(&$objDatabase) {
			// Get the Database
			$objDatabase = QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex;  ?>];
		}



		/**
		 * Internally called method to assist with SQL Query options/preferences for array loaders.
		 * Any LoadAll or LoadArray method can use this method to setup SQL Query Clauses that deal
		 * with OrderBy, Limit, and Object Expansion.  Strings that contain SQL Query Clauses are
		 * passed in by reference.
		 * @param string $strOrderBy reference to the Order By as passed in to the LoadArray method
		 * @param string $strLimit the Limit as passed in to the LoadArray method
		 * @param string $strLimitPrefix reference to the Limit Prefix to be used in the SQL
		 * @param string $strLimitSuffix reference to the Limit Suffix to be used in the SQL
		 * @param string $strExpandSelect reference to the Expand Select to be used in the SQL
		 * @param string $strExpandFrom reference to the Expand From to be used in the SQL
		 * @param array $objExpansionMap map of referenced columns to be immediately expanded via early-binding
		 * @param string $objDatabase reference to the Database object to be queried
		 */
		protected static function ArrayQueryHelper(&$strOrderBy, $strLimit, &$strLimitPrefix, &$strLimitSuffix, &$strExpandSelect, &$strExpandFrom, $objExpansionMap, &$objDatabase) {
			// Get the Database
			$objDatabase = QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex;  ?>];

			// Setup OrderBy and Limit Information (if applicable)
			$strOrderBy = $objDatabase->SqlSortByVariable($strOrderBy);
			$strLimitPrefix = $objDatabase->SqlLimitVariablePrefix($strLimit);
			$strLimitSuffix = $objDatabase->SqlLimitVariableSuffix($strLimit);

			// Setup QueryExpansion (if applicable)
			if ($objExpansionMap) {
				$objQueryExpansion = new QQueryExpansion('<?php echo $objTable->ClassName  ?>', '<?php echo $objTable->Name  ?>', $objExpansionMap);
				$strExpandSelect = $objQueryExpansion->GetSelectSql();
				$strExpandFrom = $objQueryExpansion->GetFromSql();
			} else {
				$strExpandSelect = null;
				$strExpandFrom = null;
			}
		}



		/**
		 * Internally called method to assist with early binding of objects
		 * on load methods.  Can only early-bind references that this class owns in the database.
		 * @param string $strParentAlias the alias of the parent (if any)
		 * @param string $strAlias the alias of this object
		 * @param array $objExpansionMap map of referenced columns to be immediately expanded via early-binding
		 * @param QueryExpansion an already instantiated QueryExpansion object (used as a utility object to assist with object expansion)
		 */
		public static function ExpandQuery($strParentAlias, $strAlias, $objExpansionMap, QQueryExpansion $objQueryExpansion) {
			if ($strAlias) {
				$objQueryExpansion->AddFromItem(sprintf('LEFT JOIN <?php echo $strEscapeIdentifierBegin  ?><?php echo $objTable->Name  ?><?php echo $strEscapeIdentifierEnd  ?> AS <?php echo $strEscapeIdentifierBegin  ?>%s__%s<?php echo $strEscapeIdentifierEnd  ?> ON <?php echo $strEscapeIdentifierBegin  ?>%s<?php echo $strEscapeIdentifierEnd  ?>.<?php echo $strEscapeIdentifierBegin  ?>%s<?php echo $strEscapeIdentifierEnd  ?> = <?php echo $strEscapeIdentifierBegin  ?>%s__%s<?php echo $strEscapeIdentifierEnd  ?>.<?php echo $strEscapeIdentifierBegin  ?><?php echo $objTable->PrimaryKeyColumnArray[0]->Name  ?><?php echo $strEscapeIdentifierEnd  ?>', $strParentAlias, $strAlias, $strParentAlias, $strAlias, $strParentAlias, $strAlias));

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
				$objQueryExpansion->AddSelectItem(sprintf('<?php echo $strEscapeIdentifierBegin  ?>%s__%s<?php echo $strEscapeIdentifierEnd  ?>.<?php echo $strEscapeIdentifierBegin  ?><?php echo $objColumn->Name  ?><?php echo $strEscapeIdentifierEnd  ?> AS <?php echo $strEscapeIdentifierBegin  ?>%s__%s__<?php echo $objColumn->Name  ?><?php echo $strEscapeIdentifierEnd  ?>', $strParentAlias, $strAlias, $strParentAlias, $strAlias));
<?php } ?>

				$strParentAlias = $strParentAlias . '__' . $strAlias;
			}

			if (is_array($objExpansionMap))
				foreach ($objExpansionMap as $strKey=>$objValue) {
					switch ($strKey) {
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->Reference && (!$objColumn->Reference->IsType)) { ?>
						case '<?php echo $objColumn->Name  ?>':
							try {
								<?php echo $objColumn->Reference->VariableType  ?>::ExpandQuery($strParentAlias, $strKey, $objValue, $objQueryExpansion);
								break;
							} catch (QCallerException $objExc) {
								$objExc->IncrementOffset();
								throw $objExc;
							}
<?php } ?>
<?php } ?>
						default:
							throw new QCallerException(sprintf('Unknown Object to Expand in %s: %s', $strParentAlias, $strKey));
					}
				}
		}




		////////////////////////////////////////
		// COLUMN CONSTANTS for OBJECT EXPANSION
		////////////////////////////////////////
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->Reference && (!$objColumn->Reference->IsType)) { ?>
		const Expand<?php echo $objColumn->Reference->PropertyName  ?> = '<?php echo $objColumn->Name  ?>';
<?php } ?>
<?php } ?>
