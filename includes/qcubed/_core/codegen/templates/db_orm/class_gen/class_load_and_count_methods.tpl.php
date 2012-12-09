///////////////////////////////
		// CLASS-WIDE LOAD AND COUNT METHODS
		///////////////////////////////

		/**
		 * Static method to retrieve the Database object that owns this class.
		 * @return QDatabaseBase reference to the Database object that can query this class
		 */
		public static function GetDatabase() {
			return QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex;  ?>];
		}

		/**
		 * Load a <?php echo $objTable->ClassName  ?> from PK Info
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->PrimaryKey) { ?>
		 * @param <?php echo $objColumn->VariableType  ?> $<?php echo $objColumn->VariableName  ?>
<?php } ?>
<?php } ?>

		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <?php echo $objTable->ClassName  ?>

		 */
		public static function Load(<?php echo $objCodeGen->ParameterListFromColumnArray($objTable->PrimaryKeyColumnArray);  ?>, $objOptionalClauses = null) {
			$strCacheKey = false;
			if (QApplication::$objCacheProvider && !$objOptionalClauses && QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]->Caching) {
				$strCacheKey = QApplication::$objCacheProvider->CreateKey('<?php echo $this->objDb->Database ?>', '<?php echo $objTable->ClassName ?>', <?php echo $objCodeGen->ParameterListFromColumnArray($objTable->PrimaryKeyColumnArray);  ?>);
				$objCachedObject = QApplication::$objCacheProvider->Get($strCacheKey);
				if ($objCachedObject !== false) {
					return $objCachedObject;
				}
			}
			// Use QuerySingle to Perform the Query
			$objToReturn = <?php echo $objTable->ClassName  ?>::QuerySingle(
				QQ::AndCondition(
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
					QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objColumn->PropertyName  ?>, $<?php echo $objColumn->VariableName  ?>),
<?php } ?><?php GO_BACK(2); ?>

				),
				$objOptionalClauses
			);
			if ($strCacheKey !== false) {
				QApplication::$objCacheProvider->Set($strCacheKey, $objToReturn);
			}
			return $objToReturn;
		}

		/**
		 * Load all <?php echo $objTable->ClassNamePlural  ?>

		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <?php echo $objTable->ClassName  ?>[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			if (func_num_args() > 1) {
				throw new QCallerException("LoadAll must be called with an array of optional clauses as a single argument");
			}
			// Call <?php echo $objTable->ClassName  ?>::QueryArray to perform the LoadAll query
			try {
				return <?php echo $objTable->ClassName;  ?>::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all <?php echo $objTable->ClassNamePlural  ?>

		 * @return int
		 */
		public static function CountAll() {
			// Call <?php echo $objTable->ClassName  ?>::QueryCount to perform the CountAll query
			return <?php echo $objTable->ClassName  ?>::QueryCount(QQ::All());
		}
