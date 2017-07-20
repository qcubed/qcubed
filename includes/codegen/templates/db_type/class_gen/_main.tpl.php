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

        /**
        * @deprecated. Use NameArray() since its translatable
        */
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
<?php } ?><?php GO_BACK(2); ?>

		);

        public static function NameArray() {
            return [
<?php if (count($objTypeTable->NameArray)) { ?>
<?php   foreach ($objTypeTable->NameArray as $intKey=>$strValue) { ?>
                <?= $intKey ?> => QApplication::Translate('<?= $strValue ?>'),
<?php   } ?><?php GO_BACK(2); ?>
<?php }?>

            ];
        }

		public static function ExtraColumnValuesArray() {
            return  array(
<?php foreach ($objTypeTable->ExtraPropertyArray as $intKey=>$arrColumns) { ?>
			    <?= $intKey ?> => array (
<?php 	foreach ($arrColumns as $strColName=>$mixColValue) { ?>
				    '<?= $strColName ?>' => <?= QTypeTable::Literal($mixColValue) ?>,
<?php 	} ?><?php GO_BACK(2); ?>

			    ),
<?php } ?><?php GO_BACK(2); ?>

		    );
        }


<?php if (count($objTypeTable->ExtraFieldNamesArray)) { ?>
<?php foreach ($objTypeTable->ExtraFieldNamesArray as $strColName) { ?>
		public static function <?= $strColName ?>Array() {
            return array(
<?php foreach ($objTypeTable->ExtraPropertyArray as $intKey=>$arrColumns) { ?>
			    '<?= $intKey ?>' => <?= QTypeTable::Literal($arrColumns[$strColName]) ?>,
<?php } ?><?php GO_BACK(2); ?>

		    );
        }

<?php } ?>
<?php } ?>


<?php }?>
		public static function ToString($int<?= $objTypeTable->ClassName ?>Id) {
			switch ($int<?= $objTypeTable->ClassName ?>Id) {
<?php foreach ($objTypeTable->NameArray as $intKey=>$strValue) { ?>
				case <?= $intKey ?>: return QApplication::Translate('<?= $strValue ?>');
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
		public static function To<?php echo $strColName  ?>($int<?php echo $objTypeTable->ClassName  ?>Id) {
			switch ($int<?php echo $objTypeTable->ClassName  ?>Id) {
<?php foreach ($objTypeTable->ExtraPropertyArray as $intKey=>$arrColumns) { ?>
				case <?php echo $intKey  ?>: return <?= QTypeTable::Literal($arrColumns[$strColName]) ?>;
<?php } ?>
				default:
					throw new QCallerException(sprintf('Invalid int<?php echo $objTypeTable->ClassName  ?>Id: %s', $int<?php echo $objTypeTable->ClassName  ?>Id));
			}
		}

<?php } ?>


		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a <?= $objTypeTable->ClassName ?> from a Database Row.
		 * Simply returns the integer id corresponding to this item.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this <?= $objTypeTable->ClassName ?>::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param QDatabaseRowBase $objDbRow
		 * @param string|null $strAliasPrefix
		 * @param string|null $strExpandAsArrayNodes
		 * @param QBaseClass|null $arrPreviousItem
		 * @param string[]|null $strColumnAliasArray
		 * @return <?= $objTypeTable->ClassName ?>

		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $arrPreviousItems = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow) {
				return null;
			}
			$strAlias = $strAliasPrefix . 'id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$intId = $objDbRow->GetColumn($strAliasName, QDatabaseFieldType::Integer);
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
	class QQNode<?= $objTypeTable->ClassName ?> extends QQTableNode {
		protected $strTableName = '<?= $objTypeTable->Name ?>';
		protected $strPrimaryKey = 'id';
		protected $strClassName = '<?= $objTypeTable->ClassName ?>';
		protected $blnIsType = true;

		public function Fields() {
			return ["id", "name"];
		}

		public function PrimaryKeyFields() {
			return ["id"];
		}

		public function __get($strName) {
			switch ($strName) {
			 	case 'Id':
					return new QQColumnNode('id', 'Id', 'Integer', $this);
				case '_PrimaryKeyNode':
					return new QQColumnNode('id', 'Id', 'Integer', $this);
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
