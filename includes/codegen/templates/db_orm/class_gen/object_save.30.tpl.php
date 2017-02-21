<?php
/**
 * This file is deprecated. It is an aid to migrating to version 3.1. To use this old code, set
 * the privateColumnVars option in codegen_settings.xml to false. This will allow you to refer
 * to the protected variables in you ModelGen classes from within the Model subclass. It is
 * highly recommended to migrate to the new v3.1 models. You will get improved performance, fewer
 * optimistic locking exceptions, and remove the problem of accidentally referring to fields that
 * were not loaded due to QSelect clauses.
 **/
?>
/**
		 * Save this <?= $objTable->ClassName ?>

		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
<?php
	$returnType = 'void';
	foreach ($objArray = $objTable->ColumnArray as $objColumn)
		if ($objColumn->Identity) {
			$returnType = 'int';
			break;
		}
	print '		 * @return '.$returnType;

	$strCols = '';
	$strValues = '';
	$strColUpdates = '';
	foreach ($objTable->ColumnArray as $objColumn) {
		if ((!$objColumn->Identity) && (!$objColumn->Timestamp)) {
			if ($strCols) $strCols .= ",\n";
			if ($strValues) $strValues .= ",\n";
			if ($strColUpdates) $strColUpdates .= ",\n";
			$strCol = '							' . $strEscapeIdentifierBegin.$objColumn->Name.$strEscapeIdentifierEnd;
			$strCols .= $strCol;
			$strValue = '\' . $objDatabase->SqlVariable($this->'.$objColumn->VariableName.') . \'';
			$strValues .= '							' . $strValue;
			$strColUpdates .= $strCol .' = '.$strValue;
		} elseif ($objColumn->Timestamp && $objColumn->AutoUpdate) {
			if ($strCols) $strCols .= ",\n";
			if ($strValues) $strValues .= ",\n";
			if ($strColUpdates) $strColUpdates .= ",\n";
			$strCol = '							' . $strEscapeIdentifierBegin.$objColumn->Name.$strEscapeIdentifierEnd;
			$strCols .= $strCol;
			$strValue = '\' . $objDatabase->SqlVariable(QDateTime::NowToString(QDateTime::FormatIso)) . \'';
			$strValues .= '							' . $strValue;
			$strColUpdates .= $strCol .' = '.$strValue;

		}
	}
	if ($strValues) {
		$strCols = " (\n".$strCols."\n						)";
		$strValues = " VALUES (\n".$strValues."\n						)\n";
	} else {
		$strValues = " DEFAULT VALUES";
	}

	$strIds = '';
	foreach ($objTable->PrimaryKeyColumnArray as $objPkColumn) {
		if ($strIds) $strIds .= " AND \n";
		$strIds .= '							' . $strEscapeIdentifierBegin.$objPkColumn->Name.$strEscapeIdentifierEnd .
			' = \' . $objDatabase->SqlVariable($this->' . ($objPkColumn->Identity ? '' : '__')  . $objPkColumn->VariableName . ') . \'';
	}

?>

		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = <?= $objTable->ClassName ?>::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored && !$blnForceUpdate) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO <?= $strEscapeIdentifierBegin ?><?= $objTable->Name ?><?= $strEscapeIdentifierEnd ?><?= $strCols; echo $strValues; ?>
					');

<?php
	foreach ($objArray = $objTable->PrimaryKeyColumnArray as $objColumn) {
		if ($objColumn->Identity) {
			print sprintf('					// Update Identity column and return its value
					$mixToReturn = $this->%s = $objDatabase->InsertId(\'%s\', \'%s\');',
					$objColumn->VariableName, $objTable->Name, $objColumn->Name);
		}
	}
?>

				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->Timestamp) { ?>
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								<?= $strEscapeIdentifierBegin ?><?= $objColumn->Name ?><?= $strEscapeIdentifierEnd ?>

							FROM
								<?= $strEscapeIdentifierBegin ?><?= $objTable->Name ?><?= $strEscapeIdentifierEnd ?>

							WHERE
<?= $strIds; ?>

						');

						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this-><?= $objColumn->VariableName ?>)
							throw new QOptimisticLockingException('<?= $objTable->ClassName ?>');
					}
<?php } ?>
<?php } ?>

					// Perform the UPDATE query
<?php if ($strColUpdates) { ?>
					$objDatabase->NonQuery('
						UPDATE
							<?= $strEscapeIdentifierBegin ?><?= $objTable->Name ?><?= $strEscapeIdentifierEnd ?>

						SET
<?= $strColUpdates; ?>

						WHERE
<?= $strIds; ?>

					');
<?php } else { ?>
					// Nothing to update
<?php }?>
				}

<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
<?php $objReverseReferenceTable = $objCodeGen->TableArray[strtolower($objReverseReference->Table)]; ?>
<?php $objReverseReferenceColumn = $objReverseReferenceTable->ColumnArray[strtolower($objReverseReference->Column)]; ?>


				// Update the adjoined <?= $objReverseReference->ObjectDescription ?> object (if applicable)
				// TODO: Make this into hard-coded SQL queries
				if ($this->blnDirty<?= $objReverseReference->ObjectPropertyName ?>) {
					// Unassociate the old one (if applicable)
					if ($objAssociated = <?= $objReverseReference->VariableType ?>::LoadBy<?= $objReverseReferenceColumn->PropertyName ?>(<?= $objCodeGen->ImplodeObjectArray(', ', '$this->', '', 'VariableName', $objTable->PrimaryKeyColumnArray) ?>)) {
						$objAssociated-><?= $objReverseReferenceColumn->PropertyName ?> = null;
						$objAssociated->Save();
					}

					// Associate the new one (if applicable)
					if ($this-><?= $objReverseReference->ObjectMemberVariable ?>) {
						$this-><?= $objReverseReference->ObjectMemberVariable ?>-><?= $objReverseReferenceColumn->PropertyName ?> = $this-><?= $objTable->PrimaryKeyColumnArray[0]->VariableName ?>;
						$this-><?= $objReverseReference->ObjectMemberVariable ?>->Save();
					}

					// Reset the "Dirty" flag
					$this->blnDirty<?= $objReverseReference->ObjectPropertyName ?> = false;
				}
<?php } ?>
<?php } ?>
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Update __blnRestored and any Non-Identity PK Columns (if applicable)
			$this->__blnRestored = true;
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
<?php if ((!$objColumn->Identity) && ($objColumn->PrimaryKey)) { ?>
			$this->__<?= $objColumn->VariableName ?> = $this-><?= $objColumn->VariableName ?>;
<?php } ?>
<?php } ?>

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->Timestamp) { ?>
			// Update Local Timestamp
			$objResult = $objDatabase->Query('
				SELECT
					<?= $strEscapeIdentifierBegin ?><?= $objColumn->Name ?><?= $strEscapeIdentifierEnd ?>

				FROM
					<?= $strEscapeIdentifierBegin ?><?= $objTable->Name ?><?= $strEscapeIdentifierEnd ?>

				WHERE
<?= $strIds; ?>

			');

			$objRow = $objResult->FetchArray();
			$this-><?= $objColumn->VariableName ?> = $objRow[0];
<?php } ?>
<?php } ?>

			$this->DeleteFromCache();

			if (static::$blnWatchChanges) {
				QWatcher::MarkTableModified (static::GetDatabase()->Database, '<?= $objTable->Name ?>');
			}

			// Return
			return $mixToReturn;
		}