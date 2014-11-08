<?php
	/** @var QTypeTable $objTypeTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __MODEL_GEN__,
		'TargetFileName' => $objTypeTable->ClassName . 'Gen.class.php'
	);
?>
<?php print("<?php\n"); ?>
	/**
	 * The <?= $objTypeTable->ClassName ?> class defined here contains
	 * code for the <?= $objTypeTable->ClassName ?> enumerated type.  It represents
	 * the enumerated values found in the "<?= $objTypeTable->Name ?>" table
	 * in the database.
	 *
	 * To use, you should use the <?= $objTypeTable->ClassName ?> subclass which
	 * extends this <?= $objTypeTable->ClassName ?>Gen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the <?= $objTypeTable->ClassName ?> class.
	 *
	 * @package <?= QCodeGen::$ApplicationName; ?>

	 * @subpackage GeneratedDataObjects
	 */
	abstract class <?= $objTypeTable->ClassName ?>Gen extends QBaseClass {
<?= ($intKey = 0) == 1; ?><?php foreach ($objTypeTable->TokenArray as $intKey=>$strValue) { ?>
		const <?= $strValue ?> = <?= $intKey ?>;
<?php } ?>

		const MaxId = <?= $intKey ?>;

		public static $NameArray = array(<?php if (count($objTypeTable->NameArray)) { ?>

<?php foreach ($objTypeTable->NameArray as $intKey=>$strValue) { ?>
			<?= $intKey ?> => '<?= $strValue ?>',
<?php } ?><?php GO_BACK(2); ?><?php }?>);

		public static $TokenArray = array(<?php if (count($objTypeTable->TokenArray)) { ?>

<?php foreach ($objTypeTable->TokenArray as $intKey=>$strValue) { ?>
			<?= $intKey ?> => '<?= $strValue ?>',
<?php } ?><?php GO_BACK(2); ?><?php }?>);

<?php if (count($objTypeTable->ExtraFieldNamesArray)) { ?>
		public static $ExtraColumnNamesArray = array(
<?php foreach ($objTypeTable->ExtraFieldNamesArray as $strColName) { ?>
			'<?= $strColName ?>',
<?php } ?><?php GO_BACK(2); ?>);

		public static $ExtraColumnValuesArray = array(
<?php foreach ($objTypeTable->ExtraPropertyArray as $intKey=>$arrColumns) { ?>
			<?= $intKey ?> => array (
<?php foreach ($arrColumns as $strColName=>$strColValue) { ?>
						'<?= $strColName ?>' => '<?= str_replace("'", "\\'", $strColValue) ?>',
<?php } ?><?php GO_BACK(2); ?>),
<?php } ?><?php GO_BACK(2); ?>);


<?php }?>
		public static function ToString($int<?= $objTypeTable->ClassName ?>Id) {
			switch ($int<?= $objTypeTable->ClassName ?>Id) {
<?php foreach ($objTypeTable->NameArray as $intKey=>$strValue) { ?>
				case <?= $intKey ?>: return '<?= $strValue ?>';
<?php } ?>
				default:
					throw new QCallerException(sprintf('Invalid int<?= $objTypeTable->ClassName ?>Id: %s', $int<?= $objTypeTable->ClassName ?>Id));
			}
		}

		public static function ToToken($int<?= $objTypeTable->ClassName ?>Id) {
			switch ($int<?= $objTypeTable->ClassName ?>Id) {
<?php foreach ($objTypeTable->TokenArray as $intKey=>$strValue) { ?>
				case <?= $intKey ?>: return '<?= $strValue ?>';
<?php } ?>
				default:
					throw new QCallerException(sprintf('Invalid int<?= $objTypeTable->ClassName ?>Id: %s', $int<?= $objTypeTable->ClassName ?>Id));
			}
		}

<?php foreach ($objTypeTable->ExtraFieldNamesArray as $strColName) { ?>
		public static function To<?= $strColName ?>($int<?= $objTypeTable->ClassName ?>Id) {
			if (array_key_exists($int<?= $objTypeTable->ClassName ?>Id, <?= $objTypeTable->ClassName ?>::$ExtraColumnValuesArray))
				return <?= $objTypeTable->ClassName ?>::$ExtraColumnValuesArray[$int<?= $objTypeTable->ClassName ?>Id]['<?= $strColName ?>'];
			else
				throw new QCallerException(sprintf('Invalid int<?= $objTypeTable->ClassName ?>Id: %s', $int<?= $objTypeTable->ClassName ?>Id));
		}

<?php } ?>

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this <?= $objTypeTable->ClassName ?>

		 * For use in association tables linked to this type.
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null, QQSelect $objSelect = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = '<?= $objTypeTable->Name; ?>';
				$strAliasPrefix = '';
			}

            if ($objSelect) {
			    $objBuilder->AddSelectItem($strTableName, 'id', $strAliasPrefix . 'id');
                $objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
            } else {
			    $objBuilder->AddSelectItem($strTableName, 'id', $strAliasPrefix . 'id');
			    $objBuilder->AddSelectItem($strTableName, 'name', $strAliasPrefix . 'name');
            }
		}

		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a <?= $objTypeTable->ClassName ?> from a Database Row.
		 * Simply returns the integer id corresponding to this item.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this <?= $objTypeTable->ClassName ?>::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $arrPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return <?= $objTypeTable->ClassName ?>

		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $arrPreviousItems = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow) {
				return null;
			}
			$strAlias = $strAliasPrefix . 'id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$intId = $objDbRow->GetColumn($strAliasName, 'Integer');
			return $intId;
		}
	}


    /**
     * @uses QQNode
     *
     * @property-read QQNode $Id
     * @property-read QQNode $Name
     * @property-read QQNode $_PrimaryKeyNode
     **/
	class QQNode<?= $objTypeTable->ClassName ?> extends QQNode {
		protected $strTableName = '<?= $objTypeTable->Name ?>';
		protected $strPrimaryKey = 'id';
		protected $strClassName = '<?= $objTypeTable->ClassName ?>';
		protected $blnIsType = true;
		public function __get($strName) {
			switch ($strName) {
			 	case 'Id':
					return new QQNode('id', 'Id', 'Integer', $this);
				case '_PrimaryKeyNode':
					return new QQNode('id', 'Id', 'Integer', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

?>
