<?php $objManyToManyReferenceTable = $objCodeGen->TableArray[strtolower($objManyToManyReference->AssociatedTable)]; ?>


		// Related Many-to-Many Objects' Methods for <?php echo $objManyToManyReference->ObjectDescription  ?>

		//-------------------------------------------------------------------

		/**
		 * Gets all many-to-many associated <?php echo $objManyToManyReference->ObjectDescriptionPlural  ?> as an array of <?php echo $objManyToManyReference->VariableType  ?> objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <?php echo $objManyToManyReference->VariableType  ?>[]
		*/
		public function Get<?php echo $objManyToManyReference->ObjectDescription  ?>Array($objOptionalClauses = null) {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				return array();

			try {
				return <?php echo $objManyToManyReference->VariableType  ?>::LoadArrayBy<?php echo $objManyToManyReference->OppositeObjectDescription  ?>($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?>, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all many-to-many associated <?php echo $objManyToManyReference->ObjectDescriptionPlural  ?>

		 * @return int
		*/
		public function Count<?php echo $objManyToManyReference->ObjectDescriptionPlural  ?>() {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				return 0;

			return <?php echo $objManyToManyReference->VariableType  ?>::CountBy<?php echo $objManyToManyReference->OppositeObjectDescription  ?>($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?>);
		}

		/**
		 * Checks to see if an association exists with a specific <?php echo $objManyToManyReference->ObjectDescription  ?>

		 * @param <?php echo $objManyToManyReference->VariableType  ?> $<?php echo $objManyToManyReference->VariableName  ?>

		 * @return bool
		*/
		public function Is<?php echo $objManyToManyReference->ObjectDescription  ?>Associated(<?php echo $objManyToManyReference->VariableType  ?> $<?php echo $objManyToManyReference->VariableName  ?>) {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Is<?php echo $objManyToManyReference->ObjectDescription  ?>Associated on this unsaved <?php echo $objTable->ClassName  ?>.');
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($' . $objManyToManyReference->VariableName . '->', '))', 'PropertyName', $objManyToManyReferenceTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Is<?php echo $objManyToManyReference->ObjectDescription  ?>Associated on this <?php echo $objTable->ClassName  ?> with an unsaved <?php echo $objManyToManyReferenceTable->ClassName  ?>.');

			$intRowCount = <?php echo $objTable->ClassName  ?>::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objTable->PrimaryKeyColumnArray[0]->PropertyName  ?>, $this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?>),
					QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objManyToManyReference->ObjectDescription  ?>-><?php echo $objManyToManyReference->OppositePropertyName  ?>, $<?php echo $objManyToManyReference->VariableName  ?>-><?php echo $objManyToManyReferenceTable->PrimaryKeyColumnArray[0]->PropertyName  ?>)
				)
			);

			return ($intRowCount > 0);
		}

		/**
		 * Associates a <?php echo $objManyToManyReference->ObjectDescription  ?>

		 * @param <?php echo $objManyToManyReference->VariableType  ?> $<?php echo $objManyToManyReference->VariableName  ?>

		 * @return void
		*/
		public function Associate<?php echo $objManyToManyReference->ObjectDescription  ?>(<?php echo $objManyToManyReference->VariableType  ?> $<?php echo $objManyToManyReference->VariableName  ?>) {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Associate<?php echo $objManyToManyReference->ObjectDescription  ?> on this unsaved <?php echo $objTable->ClassName  ?>.');
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($' . $objManyToManyReference->VariableName . '->', '))', 'PropertyName', $objManyToManyReferenceTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Associate<?php echo $objManyToManyReference->ObjectDescription  ?> on this <?php echo $objTable->ClassName  ?> with an unsaved <?php echo $objManyToManyReferenceTable->ClassName  ?>.');

			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				INSERT INTO <?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->Table  ?><?php echo $strEscapeIdentifierEnd  ?> (
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?>,
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->OppositeColumn  ?><?php echo $strEscapeIdentifierEnd  ?>

				) VALUES (
					' . $objDatabase->SqlVariable($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?>) . ',
					' . $objDatabase->SqlVariable($<?php echo $objManyToManyReference->VariableName  ?>-><?php echo $objManyToManyReferenceTable->PrimaryKeyColumnArray[0]->PropertyName  ?>) . '
				)
			');
		}

		/**
		 * Unassociates a <?php echo $objManyToManyReference->ObjectDescription  ?>

		 * @param <?php echo $objManyToManyReference->VariableType  ?> $<?php echo $objManyToManyReference->VariableName  ?>

		 * @return void
		*/
		public function Unassociate<?php echo $objManyToManyReference->ObjectDescription  ?>(<?php echo $objManyToManyReference->VariableType  ?> $<?php echo $objManyToManyReference->VariableName  ?>) {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Unassociate<?php echo $objManyToManyReference->ObjectDescription  ?> on this unsaved <?php echo $objTable->ClassName  ?>.');
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($' . $objManyToManyReference->VariableName . '->', '))', 'PropertyName', $objManyToManyReferenceTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Unassociate<?php echo $objManyToManyReference->ObjectDescription  ?> on this <?php echo $objTable->ClassName  ?> with an unsaved <?php echo $objManyToManyReferenceTable->ClassName  ?>.');

			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->Table  ?><?php echo $strEscapeIdentifierEnd  ?>

				WHERE
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?>) . ' AND
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->OppositeColumn  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($<?php echo $objManyToManyReference->VariableName  ?>-><?php echo $objManyToManyReferenceTable->PrimaryKeyColumnArray[0]->PropertyName  ?>) . '
			');
		}

		/**
		 * Unassociates all <?php echo $objManyToManyReference->ObjectDescriptionPlural  ?>

		 * @return void
		*/
		public function UnassociateAll<?php echo $objManyToManyReference->ObjectDescriptionPlural  ?>() {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAll<?php echo $objManyToManyReference->ObjectDescription  ?>Array on this unsaved <?php echo $objTable->ClassName  ?>.');

			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->Table  ?><?php echo $strEscapeIdentifierEnd  ?>

				WHERE
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?>) . '
			');
		}