		/**
		 * Load an array of <?= $objManyToManyReference->VariableType ?> objects for a given <?= $objManyToManyReference->ObjectDescription ?>

		 * via the <?= $objManyToManyReference->Table ?> table
		 * @param <?= $objManyToManyReference->OppositeVariableType ?> $<?= $objManyToManyReference->OppositeVariableName ?>

		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
         * @throws QCallerException
		 * @return <?= $objTable->ClassName ?>[]
		*/
		public static function LoadArrayBy<?= $objManyToManyReference->ObjectDescription ?>($<?= $objManyToManyReference->OppositeVariableName ?>, $objClauses = null) {
			// Call <?= $objTable->ClassName ?>::QueryArray to perform the LoadArrayBy<?= $objManyToManyReference->ObjectDescription ?> query
			try {
				return <?= $objTable->ClassName; ?>::QueryArray(
					QQ::Equal(QQN::<?= $objTable->ClassName ?>()-><?= $objManyToManyReference->ObjectDescription ?>-><?= $objManyToManyReference->OppositePropertyName ?>, $<?= $objManyToManyReference->OppositeVariableName ?>),
					$objClauses
				);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count <?= $objTable->ClassNamePlural ?> for a given <?= $objManyToManyReference->ObjectDescription ?>

		 * via the <?= $objManyToManyReference->Table ?> table
		 * @param <?= $objManyToManyReference->OppositeVariableType ?> $<?= $objManyToManyReference->OppositeVariableName ?>

		 * @return int
		*/
		public static function CountBy<?= $objManyToManyReference->ObjectDescription ?>($<?= $objManyToManyReference->OppositeVariableName ?>) {
			return <?= $objTable->ClassName ?>::QueryCount(
				QQ::Equal(QQN::<?= $objTable->ClassName ?>()-><?= $objManyToManyReference->ObjectDescription ?>-><?= $objManyToManyReference->OppositePropertyName ?>, $<?= $objManyToManyReference->OppositeVariableName ?>)
			);
		}