<?php $objColumnArray = $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray); ?>
		/**
		 * Load a single <?php echo $objTable->ClassName  ?> object,
		 * by <?php echo $objCodeGen->ImplodeObjectArray(', ', '', '', 'PropertyName', $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray))  ?> Index(es)
<?php foreach ($objColumnArray as $objColumn) { ?>
		 * @param <?php echo $objColumn->VariableType  ?> $<?php echo $objColumn->VariableName  ?>

<?php } ?>
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <?php echo $objTable->ClassName  ?>

		*/
		public static function LoadBy<?php echo $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray);  ?>(<?php echo $objCodeGen->ParameterListFromColumnArray($objColumnArray);  ?>, $objOptionalClauses = null) {
			return <?php echo $objTable->ClassName  ?>::QuerySingle(
				QQ::AndCondition(
<?php foreach ($objColumnArray as $objColumn) { ?>
					QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objColumn->PropertyName  ?>, $<?php echo $objColumn->VariableName  ?>),
<?php } ?><?php GO_BACK(2); ?>

				),
				$objOptionalClauses
			);
		}