/**
		 * Delete this <?php echo $objTable->ClassName  ?>

		 * @return void
		 */
		public function Delete() {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Cannot delete this <?php echo $objTable->ClassName  ?> with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
<?php if (!$objReverseReference->NotNull) { ?>
<?php $objReverseReferenceTable = $objCodeGen->TableArray[strtolower($objReverseReference->Table)]; ?>
<?php $objReverseReferenceColumn = $objReverseReferenceTable->ColumnArray[strtolower($objReverseReference->Column)]; ?>


			// Update the adjoined <?php echo $objReverseReference->ObjectDescription  ?> object (if applicable) and perform the unassociation

			// Optional -- if you **KNOW** that you do not want to EVER run any level of business logic on the disassocation,
			// you *could* override Delete() so that this step can be a single hard coded query to optimize performance.
			if ($objAssociated = <?php echo $objReverseReference->VariableType  ?>::LoadBy<?php echo $objReverseReferenceColumn->PropertyName  ?>(<?php echo $objCodeGen->ImplodeObjectArray(', ', '$this->', '', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)) {
				$objAssociated-><?php echo $objReverseReferenceColumn->PropertyName  ?> = null;
				$objAssociated->Save();
			}
<?php } ?><?php if ($objReverseReference->NotNull) { ?>
<?php $objReverseReferenceTable = $objCodeGen->TableArray[strtolower($objReverseReference->Table)]; ?>
<?php $objReverseReferenceColumn = $objReverseReferenceTable->ColumnArray[strtolower($objReverseReference->Column)]; ?>

		
			// Update the adjoined <?php echo $objReverseReference->ObjectDescription  ?> object (if applicable) and perform a delete

			// Optional -- if you **KNOW** that you do not want to EVER run any level of business logic on the disassocation,
			// you *could* override Delete() so that this step can be a single hard coded query to optimize performance.
			if ($objAssociated = <?php echo $objReverseReference->VariableType  ?>::LoadBy<?php echo $objReverseReferenceColumn->PropertyName  ?>(<?php echo $objCodeGen->ImplodeObjectArray(', ', '$this->', '', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)) {
				$objAssociated->Delete();
			}
<?php } ?>
<?php } ?>
<?php } ?>

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objTable->Name  ?><?php echo $strEscapeIdentifierEnd  ?>

				WHERE
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->PrimaryKey) { ?>
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objColumn->Name  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($this-><?php echo $objColumn->VariableName  ?>) . ' AND
<?php } ?>
<?php } ?><?php GO_BACK(5); ?>');

			$this->DeleteCache();
		}

        /**
 	     * Delete this <?php echo $objTable->ClassName ?> ONLY from the cache
 		 * @return void
		 */
		public function DeleteCache() {
			if (QApplication::$objCacheProvider && QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]->Caching) {
				$strCacheKey = QApplication::$objCacheProvider->CreateKey(QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]->Database, '<?php echo $objTable->ClassName ?>', <?php echo $objCodeGen->ImplodeObjectArray(', ', '$this->', '', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>);
				QApplication::$objCacheProvider->Delete($strCacheKey);
			}
		}

		/**
		 * Delete all <?php echo $objTable->ClassNamePlural  ?>

		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objTable->Name  ?><?php echo $strEscapeIdentifierEnd  ?>');

			if (QApplication::$objCacheProvider && QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]->Caching) {
				QApplication::$objCacheProvider->DeleteAll();
			}
		}

		/**
		 * Truncate <?php echo $objTable->Name  ?> table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE <?php echo $strEscapeIdentifierBegin  ?><?php echo $objTable->Name  ?><?php echo $strEscapeIdentifierEnd  ?>');

			if (QApplication::$objCacheProvider && QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]->Caching) {
				QApplication::$objCacheProvider->DeleteAll();
			}
		}