	// See QModelBase.class.php
	// protected static function BuildQueryStatement(&$objQueryBuilder, QQCondition $objConditions, $objOptionalClauses, $mixParameterArray, $blnCountOnly) {
	// public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
	// public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
	// public static function QueryCursor(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
	// public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
	// public static function QueryArrayCached(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null, $blnForceUpdate = false) {

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this <?= $objTable->ClassName ?>

		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null, QQSelect $objSelect = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = '<?= $objTable->Name; ?>';
				$strAliasPrefix = '';
			}

			if ($objSelect) {
				if (!$objSelect->SkipPrimaryKey()) {
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
					$objBuilder->AddSelectItem($strTableName, '<?= $objColumn->Name ?>', $strAliasPrefix . '<?= $objColumn->Name ?>');
<?php } ?>
				}
                $objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
			} else {
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
				$objBuilder->AddSelectItem($strTableName, '<?= $objColumn->Name ?>', $strAliasPrefix . '<?= $objColumn->Name ?>');
<?php } ?>
			}
		}