///////////////////////////////
		// CLASS-WIDE LOAD AND COUNT METHODS
		///////////////////////////////

		/**
		 * Static method to retrieve the Database object that owns this class.
		 * @return QDatabaseBase reference to the Database object that can query this class
		 */
		public static function GetDatabase() {
			return QApplication::$Database[<?= $objCodeGen->DatabaseIndex; ?>];
		}

		/**
		 * Load a <?= $objTable->ClassName ?> from PK Info
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->PrimaryKey) { ?>
		 * @param <?= $objColumn->VariableType ?> $<?= $objColumn->VariableName ?>
<?php } ?>
<?php } ?>

		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <?= $objTable->ClassName ?>

		 */
		public static function Load(<?= $objCodeGen->ParameterListFromColumnArray($objTable->PrimaryKeyColumnArray); ?>, $objOptionalClauses = null) {
			$strCacheKey = false;
			if (QApplication::$objCacheProvider && !$objOptionalClauses && QApplication::$Database[<?= $objCodeGen->DatabaseIndex; ?>]->Caching) {
				$strCacheKey = QApplication::$objCacheProvider->CreateKey(QApplication::$Database[<?= $objCodeGen->DatabaseIndex; ?>]->Database, '<?= $objTable->ClassName ?>', <?= $objCodeGen->ParameterListFromColumnArray($objTable->PrimaryKeyColumnArray); ?>);
				$objCachedObject = QApplication::$objCacheProvider->Get($strCacheKey);
				if ($objCachedObject !== false) {
					return $objCachedObject;
				}
			}
			// Use QuerySingle to Perform the Query
			$objToReturn = <?= $objTable->ClassName ?>::QuerySingle(
				QQ::AndCondition(
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
					QQ::Equal(QQN::<?= $objTable->ClassName ?>()-><?= $objColumn->PropertyName ?>, $<?= $objColumn->VariableName ?>),
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
		 * Load all <?= $objTable->ClassNamePlural ?>

		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <?= $objTable->ClassName ?>[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			if (func_num_args() > 1) {
				throw new QCallerException("LoadAll must be called with an array of optional clauses as a single argument");
			}
			// Call <?= $objTable->ClassName ?>::QueryArray to perform the LoadAll query
			try {
				return <?= $objTable->ClassName; ?>::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all <?= $objTable->ClassNamePlural ?>

		 * @return int
		 */
		public static function CountAll() {
			// Call <?= $objTable->ClassName ?>::QueryCount to perform the CountAll query
			return <?= $objTable->ClassName ?>::QueryCount(QQ::All());
		}
