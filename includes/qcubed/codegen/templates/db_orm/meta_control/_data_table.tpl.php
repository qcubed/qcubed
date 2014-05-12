<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS_GEN__ ?>" TargetFileName="<?php echo $objTable->ClassName ?>DataTableGen.class.php"/>
<?php print("<?php\n"); ?>
	/**
	 * @property QQCondition $ExtraCondition
	 * @property-read QGenericSearchOptions $SearchOptions
	 */
	class <?php echo $objTable->ClassName ?>DataTableGen extends QDataTable {
		/** @var QQCondition */
		protected $objExtraCondition = null;
		/** @var QGenericSearchOptions */
		protected $objSearchOptions = null;

		public function __construct($objParentObject, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$this->UseAjax = true;
			$this->Processing = true;
			$this->PaginationType = 'full_numbers';
			$this->Retrieve = true;
<?php foreach ($objTable->IndexArray as $objIndex) { ?>
<?php if ($objIndex->PrimaryKey) { ?>
<?php $objPkColumnArray = $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray); ?>
<?php foreach ($objPkColumnArray as $objColumn) { ?>
			$objColumn = $this->CreatePropertyColumn(QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->PropertyName)  ?>'), '<?php echo $objColumn->PropertyName ?>');
			$objColumn->OrderByClause = QQ::OrderBy(QQN::<?php echo $objTable->ClassName ?>()-><?php echo $objColumn->PropertyName ?>);
			$objColumn->ReverseOrderByClause = QQ::OrderBy(QQN::<?php echo $objTable->ClassName ?>()-><?php echo $objColumn->PropertyName ?>, 'desc');
<?php } ?>
<?php break; } ?>
<?php } ?>

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->PrimaryKey) { continue; } ?>
			$objColumn = $this->CreatePropertyColumn(QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->PropertyName)  ?>'), '<?php echo $objColumn->PropertyName ?>');
			$objColumn->OrderByClause = QQ::OrderBy(QQN::<?php echo $objTable->ClassName ?>()-><?php echo $objColumn->PropertyName ?>);
			$objColumn->ReverseOrderByClause = QQ::OrderBy(QQN::<?php echo $objTable->ClassName ?>()-><?php echo $objColumn->PropertyName ?>, 'desc');

<?php } ?>
			$this->SetDataBinder('data_bind', $this);
		}

		protected function getSearchCondition() {
			$filter = $this->Filter;
			$objCondition = <?php echo $objTable->ClassName ?>::GenericSearchCondition($filter, $this->objSearchOptions);
			if ($this->objExtraCondition) {
				$objCondition = QQ::AndCondition($objCondition, $this->objExtraCondition);
			}
			return $objCondition;
		}

		/**
		 * get the search clauses for the total count query. Should not include any OrderBy clauses
		 * @return QQClause[]|null
		 */
		protected function getSearchClausesForTotalCount() {
			return null;
		}

		/**
		 * get the OrderBy clauses for the main count query. Should only include OrderBy clauses
		 * @return QQOrderBy[]
		 */
		protected function getOrderByClausesForQuery() {
			return $this->Clause;
		}

		/**
		 * get the search clauses for the main query. Should not include any OrderBy clauses
		 * @param QQClause[] $objClauses
		 * @return boolean true if changes where made to the $objClauses array, false otherwise
		 */
		protected function getSearchClausesForQuery(&$objClauses) {
			if (!$this->LimitInfo)
				return false;
			$objClauses[] = $this->LimitInfo;
			return true;
		}

		public function data_bind() {
			$objCondition = $this->getSearchCondition();
			$objClauses = $this->getSearchClausesForTotalCount();
			$this->TotalItemCount = <?php echo $objTable->ClassName ?>::QueryCount($objCondition, $objClauses);
			if (is_null($objClauses)) {
				$objClauses = array();
			}
			if ($this->getSearchClausesForQuery($objClauses)) {
				$this->FilteredItemCount = <?php echo $objTable->ClassName ?>::QueryCount($objCondition, $objClauses);
			} else {
				$this->FilteredItemCount = $this->TotalItemCount;
			}
			$objOrderByClauses = $this->getOrderByClausesForQuery();
			if ($objOrderByClauses) {
				$objClauses = array_merge($objClauses, $objOrderByClauses);
			}
			$this->DataSource = <?php echo $objTable->ClassName ?>::QueryArray($objCondition, $objClauses);
		}

		/**
		 * Load the <?php echo $objTable->ClassName ?> object given the data from a row of this data table
		 * @param array $strRowDataArray array containing the values from each cell of a table row
		 * @return <?php echo $objTable->ClassName ?>|null
		 */
		public function LoadObjectFromRowData($strRowDataArray) {
<?php
	$strArgs = ''; $idx = 0;
	foreach ($objPkColumnArray as $objColumn) {
		if ($strArgs) $strArgs .= ', ';
		$strArgs .= '$strRowDataArray['.$idx.']';
		++$idx;
	}
?>
			if (!$strRowDataArray || !is_array($strRowDataArray) || count($strRowDataArray) < <?php echo $idx; ?>)
				return null;
			return <?php echo $objTable->ClassName ?>::LoadBy<?php echo $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objPkColumnArray);  ?>(<?php echo $strArgs ?>);
		}

		public function __get($strName) {
			switch ($strName) {
				case "SearchOptions": return $this->objSearchOptions;
				case "ExtraCondition": return $this->objExtraCondition;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			$this->blnModified = true;
			switch ($strName) {
				case 'ExtraCondition':
					try {
						$this->objExtraCondition = QType::Cast($mixValue, 'QQCondition');
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
