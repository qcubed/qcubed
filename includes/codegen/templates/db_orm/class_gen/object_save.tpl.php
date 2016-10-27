<?php
$idsAsSql = [];
$idsAsParams = [];
foreach ($objTable->PrimaryKeyColumnArray as $objPkColumn) {
	$strLocal = '$this->' . ($objPkColumn->Identity ? '' : '__')  . $objPkColumn->VariableName;
	$strCol = $strEscapeIdentifierBegin.$objPkColumn->Name.$strEscapeIdentifierEnd;
	$strValue = '$objDatabase->SqlVariable(' . $strLocal . ')';
	$idsAsSql[] = $strCol . ' = \' . ' . $strValue;
	$idsAsParams[] = $strLocal;
}

$strIds = implode (" . ' AND \n", $idsAsSql);
$strIdsAsParams = implode(", ", $idsAsParams);

foreach ($objTable->ColumnArray as $objColumn) {
	if ($objColumn->Timestamp) {
		$timestampColumn = $objColumn;
	}
	if ($objColumn->Identity) {
		$identityColumn = $objColumn;
	}
}
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


?>

		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = <?= $objTable->ClassName ?>::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored && !$blnForceUpdate) || ($blnForceInsert)) {
					$mixToReturn = $this->Insert();
				} else {
					$this->Update($blnForceUpdate);
				}
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

<?php if (isset($timestampColumn)) { ?>
			// Update Local Timestamp
			$objResult = $objDatabase->Query('
				SELECT
					<?= $strEscapeIdentifierBegin ?><?= $timestampColumn->Name ?><?= $strEscapeIdentifierEnd ?>

				FROM
					<?= $strEscapeIdentifierBegin ?><?= $objTable->Name ?><?= $strEscapeIdentifierEnd ?>

				WHERE
<?= $strIds; ?>

			);

			$objRow = $objResult->FetchArray();
			$this-><?= $timestampColumn->VariableName ?> = $objRow[0];
<?php } ?>

			$this->DeleteFromCache();

			$this->__blnDirty = null; // reset dirty values

			// Return
			return $mixToReturn;
		}

   /**
	* Insert into <?= $objTable->ClassName ?>

	*/
	protected function Insert() {
		$mixToReturn = null;
		$objDatabase = <?= $objTable->ClassName ?>::GetDatabase();
		$objDatabase->NonQuery('
			INSERT INTO <?= $strEscapeIdentifierBegin ?><?= $objTable->Name ?><?= $strEscapeIdentifierEnd ?><?= $strCols; echo $strValues; ?>
		');
<?php if (isset($identityColumn)) { ?>
		// Update Identity column and return its value
		$mixToReturn = $this-><?= $identityColumn->VariableName ?> = $objDatabase->InsertId('<?= $objTable->Name ?>', '<?= $identityColumn->Name ?>');
		$this->__blnValid[self::<?= strtoupper($identityColumn->Name) ?>_FIELD] = true;
<?php } ?>

		static::BroadcastInsert($this->PrimaryKey());

		return $mixToReturn;
	}

   /**
	* Update this <?= $objTable->ClassName ?>

	*/
	protected function Update($blnForceUpdate = false) {
		$objDatabase = static::GetDatabase();
		if (empty($this->__blnDirty)) {
			return; // nothing has changed
		}

<?php if (isset($timestampColumn)) { ?>
		if (!$blnForceUpdate) {
			$this->OptimisticLockingCheck();
		}
<?php } ?>

		$strValues = $this->GetValueClause();

		$strSql = '
UPDATE
	<?= $strEscapeIdentifierBegin ?><?= $objTable->Name ?><?= $strEscapeIdentifierEnd ?>

SET
	' . $strValues . '

WHERE
<?= $strIds ?>;

		$objDatabase->NonQuery($strSql);
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
<?php $objReverseReferenceTable = $objCodeGen->TableArray[strtolower($objReverseReference->Table)]; ?>
<?php $objReverseReferenceColumn = $objReverseReferenceTable->ColumnArray[strtolower($objReverseReference->Column)]; ?>


		// Update the foreign key in the <?= $objReverseReference->ObjectDescription ?> object (if applicable)
		if ($this->blnDirty<?= $objReverseReference->ObjectPropertyName ?>) {
			// Unassociate the old one (if applicable)
			if ($objAssociated = <?= $objReverseReference->VariableType ?>::LoadBy<?= $objReverseReferenceColumn->PropertyName ?>(<?= $objCodeGen->ImplodeObjectArray(', ', '$this->', '', 'VariableName', $objTable->PrimaryKeyColumnArray) ?>)) {
				// TODO: Select and update only the foreign key rather than the whole record
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

		static::BroadcastUpdate($this->PrimaryKey(), array_keys($this->__blnDirty));
	}

   /**
	* Creates a value clause for the currently changed fields.
	*
	* @return string
	*/
	protected function GetValueClause() {
		$values = [];
		$objDatabase = static::GetDatabase();

<?php
foreach ($objTable->ColumnArray as $objColumn) {
	if ((!$objColumn->Identity) && (!$objColumn->Timestamp)) {
?>
		if (isset($this->__blnDirty[self::<?= strtoupper($objColumn->Name) ?>_FIELD])) {
			$strCol = '<?= $strEscapeIdentifierBegin ?><?= $objColumn->Name ?><?= $strEscapeIdentifierEnd ?>';
			$strValue = $objDatabase->SqlVariable($this-><?= $objColumn->VariableName ?>);
			$values[] = $strCol . ' = ' . $strValue;
		}
<?php
	} elseif ($objColumn->Timestamp && $objColumn->AutoUpdate) {	// Must update timestamp from PHP side
?>
		$strCol = '<?= $strEscapeIdentifierBegin ?><?= $objColumn->Name ?><?= $strEscapeIdentifierEnd ?>';
		$strValue = $objDatabase->SqlVariable(QDateTime::NowToString(QDateTime::FormatIso));
		$values[] = $strCol . ' = ' . $strValue;
<?php
	}
}
?>
		if ($values) {
			return implode(",\n", $values);
		}
		else {
			return "";
		}
	}


<?php if (isset($timestampColumn)) { ?>
	protected function OptimisticLockingCheck() {
		$objDatabase = static::GetDatabase();
		$objResult = $objDatabase->Query('
SELECT
	<?= $strEscapeIdentifierBegin ?><?= $timestampColumn->Name ?><?= $strEscapeIdentifierEnd ?>

FROM
	<?= $strEscapeIdentifierBegin ?><?= $objTable->Name ?><?= $strEscapeIdentifierEnd ?>

WHERE
<?= $strIds; ?>
		);

		$objRow = $objResult->FetchArray();
		if ($objRow[0] != $this-><?= $timestampColumn->VariableName ?>) {
			// Row was updated since we got the row, now check to see if we actually changed fields that were previously changed.
			$changed = false;
			$obj<?= $objTable->ClassName ?> = <?= $objTable->ClassName ?>::Load(<?= $strIdsAsParams ?>);
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
			$changed = $changed || (isset($this->__blnDirty[self::<?= strtoupper($objColumn->Name) ?>_FIELD]) && ($this-><?= $objColumn->VariableName ?> !== $obj<?= $objTable->ClassName ?>-><?= $objColumn->VariableName ?>));
<?php } ?>
			if ($changed) {
				throw new QOptimisticLockingException('<?= $objTable->ClassName ?>');
			}
		}
	}
<?php } ?>
