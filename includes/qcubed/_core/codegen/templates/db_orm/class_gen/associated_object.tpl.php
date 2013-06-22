<?php $objReverseReferenceTable = $objCodeGen->TableArray[strtolower($objReverseReference->Table)]; ?>
<?php $objReverseReferenceColumn = $objReverseReferenceTable->ColumnArray[strtolower($objReverseReference->Column)]; ?>


		// Related Objects' Methods for <?php echo $objReverseReference->ObjectDescription  ?>

		//-------------------------------------------------------------------

		/**
		 * Gets all associated <?php echo $objReverseReference->ObjectDescriptionPlural  ?> as an array of <?php echo $objReverseReference->VariableType  ?> objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <?php echo $objReverseReference->VariableType  ?>[]
		*/
		public function Get<?php echo $objReverseReference->ObjectDescription  ?>Array($objOptionalClauses = null) {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				return array();

			try {
				return <?php echo $objReverseReference->VariableType  ?>::LoadArrayBy<?php echo $objReverseReferenceColumn->PropertyName  ?>(<?php echo $objCodeGen->ImplodeObjectArray(', ', '$this->', '', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated <?php echo $objReverseReference->ObjectDescriptionPlural  ?>

		 * @return int
		*/
		public function Count<?php echo $objReverseReference->ObjectDescriptionPlural  ?>() {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				return 0;

			return <?php echo $objReverseReference->VariableType  ?>::CountBy<?php echo $objReverseReferenceColumn->PropertyName  ?>(<?php echo $objCodeGen->ImplodeObjectArray(', ', '$this->', '', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>);
		}

		/**
		 * Associates a <?php echo $objReverseReference->ObjectDescription  ?>

		 * @param <?php echo $objReverseReference->VariableType  ?> $<?php echo $objReverseReference->VariableName  ?>

		 * @return void
		*/
		public function Associate<?php echo $objReverseReference->ObjectDescription  ?>(<?php echo $objReverseReference->VariableType  ?> $<?php echo $objReverseReference->VariableName  ?>) {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Associate<?php echo $objReverseReference->ObjectDescription  ?> on this unsaved <?php echo $objTable->ClassName  ?>.');
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($' . $objReverseReference->VariableName . '->', '))', 'PropertyName', $objReverseReferenceTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Associate<?php echo $objReverseReference->ObjectDescription  ?> on this <?php echo $objTable->ClassName  ?> with an unsaved <?php echo $objReverseReferenceTable->ClassName  ?>.');

			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Table  ?><?php echo $strEscapeIdentifierEnd  ?>

				SET
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?>) . '
				WHERE
<?php foreach ($objReverseReferenceTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->PrimaryKey) { ?>
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objColumn->Name  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($<?php echo $objReverseReference->VariableName  ?>-><?php echo $objColumn->PropertyName  ?>) . ' AND
<?php } ?><?php } ?><?php GO_BACK(5); ?>

			');
		}

		/**
		 * Unassociates a <?php echo $objReverseReference->ObjectDescription  ?>

		 * @param <?php echo $objReverseReference->VariableType  ?> $<?php echo $objReverseReference->VariableName  ?>

		 * @return void
		*/
		public function Unassociate<?php echo $objReverseReference->ObjectDescription  ?>(<?php echo $objReverseReference->VariableType  ?> $<?php echo $objReverseReference->VariableName  ?>) {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Unassociate<?php echo $objReverseReference->ObjectDescription  ?> on this unsaved <?php echo $objTable->ClassName  ?>.');
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($' . $objReverseReference->VariableName . '->', '))', 'PropertyName', $objReverseReferenceTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Unassociate<?php echo $objReverseReference->ObjectDescription  ?> on this <?php echo $objTable->ClassName  ?> with an unsaved <?php echo $objReverseReferenceTable->ClassName  ?>.');

			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Table  ?><?php echo $strEscapeIdentifierEnd  ?>

				SET
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?> = null
				WHERE
<?php foreach ($objReverseReferenceTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->PrimaryKey) { ?>
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objColumn->Name  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($<?php echo $objReverseReference->VariableName  ?>-><?php echo $objColumn->PropertyName  ?>) . ' AND
<?php } ?><?php } ?><?php GO_BACK(1); ?>

					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?>) . '
			');
		}

		/**
		 * Unassociates all <?php echo $objReverseReference->ObjectDescriptionPlural  ?>

		 * @return void
		*/
		public function UnassociateAll<?php echo $objReverseReference->ObjectDescriptionPlural  ?>() {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Unassociate<?php echo $objReverseReference->ObjectDescription  ?> on this unsaved <?php echo $objTable->ClassName  ?>.');

			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Table  ?><?php echo $strEscapeIdentifierEnd  ?>

				SET
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?> = null
				WHERE
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?>) . '
			');
		}

		/**
		 * Deletes an associated <?php echo $objReverseReference->ObjectDescription  ?>

		 * @param <?php echo $objReverseReference->VariableType  ?> $<?php echo $objReverseReference->VariableName  ?>

		 * @return void
		*/
		public function DeleteAssociated<?php echo $objReverseReference->ObjectDescription  ?>(<?php echo $objReverseReference->VariableType  ?> $<?php echo $objReverseReference->VariableName  ?>) {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Unassociate<?php echo $objReverseReference->ObjectDescription  ?> on this unsaved <?php echo $objTable->ClassName  ?>.');
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($' . $objReverseReference->VariableName . '->', '))', 'PropertyName', $objReverseReferenceTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Unassociate<?php echo $objReverseReference->ObjectDescription  ?> on this <?php echo $objTable->ClassName  ?> with an unsaved <?php echo $objReverseReferenceTable->ClassName  ?>.');

			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Table  ?><?php echo $strEscapeIdentifierEnd  ?>

				WHERE
<?php foreach ($objReverseReferenceTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->PrimaryKey) { ?>
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objColumn->Name  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($<?php echo $objReverseReference->VariableName  ?>-><?php echo $objColumn->PropertyName  ?>) . ' AND
<?php } ?><?php } ?><?php GO_BACK(1); ?>

					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?>) . '
			');
		}

		/**
		 * Deletes all associated <?php echo $objReverseReference->ObjectDescriptionPlural  ?>

		 * @return void
		*/
		public function DeleteAll<?php echo $objReverseReference->ObjectDescriptionPlural  ?>() {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Unable to call Unassociate<?php echo $objReverseReference->ObjectDescription  ?> on this unsaved <?php echo $objTable->ClassName  ?>.');

			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Table  ?><?php echo $strEscapeIdentifierEnd  ?>

				WHERE
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objReverseReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?>) . '
			');
		}
