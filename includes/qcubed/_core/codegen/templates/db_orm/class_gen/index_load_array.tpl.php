<?php $objColumnArray = $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray); ?>
		/**
		 * Load an array of <?php echo $objTable->ClassName  ?> objects,
		 * by <?php echo $objCodeGen->ImplodeObjectArray(', ', '', '', 'PropertyName', $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray))  ?> Index(es)
<?php foreach ($objColumnArray as $objColumn) { ?>
		 * @param <?php echo $objColumn->VariableType  ?> $<?php echo $objColumn->VariableName  ?>

<?php } ?>
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <?php echo $objTable->ClassName  ?>[]
		*/
		public static function LoadArrayBy<?php echo $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray);  ?>(<?php echo $objCodeGen->ParameterListFromColumnArray($objColumnArray);  ?>, $objOptionalClauses = null) {
			// Call <?php echo $objTable->ClassName  ?>::QueryArray to perform the LoadArrayBy<?php echo $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray);  ?> query
			try {
				return <?php echo $objTable->ClassName;  ?>::QueryArray(
<?php if (count($objColumnArray) > 1) { ?>
					QQ::AndCondition(
<?php } ?>
<?php foreach ($objColumnArray as $objColumn) { ?>
					QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objColumn->PropertyName  ?>, $<?php echo $objColumn->VariableName  ?>),
<?php } ?><?php GO_BACK(2); ?>
<?php if (count($objColumnArray) > 1) { ?>
					)
<?php } ?>,
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count <?php echo $objTable->ClassNamePlural  ?>

		 * by <?php echo $objCodeGen->ImplodeObjectArray(', ', '', '', 'PropertyName', $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray))  ?> Index(es)
<?php foreach ($objColumnArray as $objColumn) { ?>
		 * @param <?php echo $objColumn->VariableType  ?> $<?php echo $objColumn->VariableName  ?>

<?php } ?>
		 * @return int
		*/
		public static function CountBy<?php echo $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray);  ?>(<?php echo $objCodeGen->ParameterListFromColumnArray($objColumnArray);  ?>) {
			// Call <?php echo $objTable->ClassName  ?>::QueryCount to perform the CountBy<?php echo $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray);  ?> query
			return <?php echo $objTable->ClassName  ?>::QueryCount(
<?php if (count($objColumnArray) > 1) { ?>
				QQ::AndCondition(
<?php } ?>
<?php foreach ($objColumnArray as $objColumn) { ?>
				QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objColumn->PropertyName  ?>, $<?php echo $objColumn->VariableName  ?>),
<?php } ?><?php GO_BACK(2); ?>
<?php if (count($objColumnArray) > 1) { ?>
				)
<?php } ?>

			);
		}