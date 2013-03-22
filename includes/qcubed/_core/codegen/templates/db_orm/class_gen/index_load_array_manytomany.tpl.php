		/**
		 * Load an array of <?php echo $objManyToManyReference->VariableType  ?> objects for a given <?php echo $objManyToManyReference->ObjectDescription  ?>

		 * via the <?php echo $objManyToManyReference->Table  ?> table
		 * @param <?php echo $objManyToManyReference->OppositeVariableType  ?> $<?php echo $objManyToManyReference->OppositeVariableName  ?>

		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <?php echo $objTable->ClassName  ?>[]
		*/
		public static function LoadArrayBy<?php echo $objManyToManyReference->ObjectDescription  ?>($<?php echo $objManyToManyReference->OppositeVariableName  ?>, $objOptionalClauses = null, $objClauses = null) {
			// Call <?php echo $objTable->ClassName  ?>::QueryArray to perform the LoadArrayBy<?php echo $objManyToManyReference->ObjectDescription  ?> query
			try {
				return <?php echo $objTable->ClassName;  ?>::QueryArray(
					QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objManyToManyReference->ObjectDescription  ?>-><?php echo $objManyToManyReference->OppositePropertyName  ?>, $<?php echo $objManyToManyReference->OppositeVariableName  ?>),
					$objOptionalClauses, $objClauses 
				);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count <?php echo $objTable->ClassNamePlural  ?> for a given <?php echo $objManyToManyReference->ObjectDescription  ?>

		 * via the <?php echo $objManyToManyReference->Table  ?> table
		 * @param <?php echo $objManyToManyReference->OppositeVariableType  ?> $<?php echo $objManyToManyReference->OppositeVariableName  ?>

		 * @return int
		*/
		public static function CountBy<?php echo $objManyToManyReference->ObjectDescription  ?>($<?php echo $objManyToManyReference->OppositeVariableName  ?>) {
			return <?php echo $objTable->ClassName  ?>::QueryCount(
				QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objManyToManyReference->ObjectDescription  ?>-><?php echo $objManyToManyReference->OppositePropertyName  ?>, $<?php echo $objManyToManyReference->OppositeVariableName  ?>)
			);
		}