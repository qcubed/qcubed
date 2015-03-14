<?php
	/** @var QTypeTable $objTypeTable */
    /** @var QDatabaseCodeGen $objCodeGen */
    global $_TEMPLATE_SETTINGS;
    $_TEMPLATE_SETTINGS = array(
        'OverwriteFlag' => true,
        'DocrootFlag' => false,
        'DirectorySuffix' => '',
        'TargetDirectory' => __MODEL_GEN__,
        'TargetFileName' => $objTypeTable->ClassName.'Gen.class.php'
    );
?>
<?php print("<?php\n"); ?>
	/**
	 * The <?php echo $objTypeTable->ClassName  ?> class defined here contains
	 * code for the <?php echo $objTypeTable->ClassName  ?> enumerated type.  It represents
	 * the enumerated values found in the "<?php echo $objTypeTable->Name  ?>" table
	 * in the database.
	 *
	 * To use, you should use the <?php echo $objTypeTable->ClassName  ?> subclass which
	 * extends this <?php echo $objTypeTable->ClassName  ?>Gen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the <?php echo $objTypeTable->ClassName  ?> class.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage GeneratedDataObjects
	 */
	abstract class <?php echo $objTypeTable->ClassName  ?>Gen extends QBaseClass {
<?php $intKey = 0; $strUnknown = null; ?>
<?php foreach ($objTypeTable->TokenArray as $intKey=>$strValue) {
	if (strtolower($strValue) == 'unknown') {
		$strUnknown = $objTypeTable->ClassName.'::'.$strValue;
	}
?>
		const <?php echo $strValue  ?> = <?php echo $intKey  ?>;
<?php } ?>

		const MaxId = <?php echo $intKey  ?>;

		public static $NameArray = array(<?php if (count($objTypeTable->NameArray)) { ?>

<?php foreach ($objTypeTable->NameArray as $intKey=>$strValue) { ?>
			<?php echo $intKey  ?> => '<?php echo $strValue  ?>',
<?php } ?><?php GO_BACK(2); ?><?php }?>);

		public static $TokenArray = array(<?php if (count($objTypeTable->TokenArray)) { ?>

<?php foreach ($objTypeTable->TokenArray as $intKey=>$strValue) { ?>
			<?php echo $intKey  ?> => '<?php echo $strValue  ?>',
<?php } ?><?php GO_BACK(2); ?><?php }?>);

<?php if (count($objTypeTable->ExtraFieldNamesArray)) { ?>
		public static $ExtraColumnNamesArray = array(
<?php foreach ($objTypeTable->ExtraFieldNamesArray as $strColName) { ?>
			'<?php echo $strColName  ?>',
<?php } ?><?php GO_BACK(2); ?>);

		public static $ExtraColumnValuesArray = array(
<?php foreach ($objTypeTable->ExtraPropertyArray as $intKey=>$arrColumns) { ?>
			<?php echo $intKey  ?> => array (
<?php foreach ($arrColumns as $strColName=>$strColValue) { ?>
						'<?php echo $strColName  ?>' => '<?php echo str_replace("'", "\\'", $strColValue)  ?>',
<?php } ?><?php GO_BACK(2); ?>),
<?php } ?><?php GO_BACK(2); ?>);


<?php }?>
		public static function ToString($int<?php echo $objTypeTable->ClassName  ?>Id) {
			if (is_null($int<?php echo $objTypeTable->ClassName  ?>Id)) {
<?php if ($strUnknown) { ?>
				$int<?php echo $objTypeTable->ClassName  ?>Id = <?php echo $strUnknown; ?>;
<?php } else { ?>
				return null;
<?php } ?>
			}
			switch ($int<?php echo $objTypeTable->ClassName  ?>Id) {
<?php foreach ($objTypeTable->NameArray as $intKey=>$strValue) { ?>
				case <?php echo $intKey  ?>: return '<?php echo $strValue  ?>';
<?php } ?>
				default:
					throw new QCallerException(sprintf('Invalid int<?php echo $objTypeTable->ClassName  ?>Id: %s', $int<?php echo $objTypeTable->ClassName  ?>Id));
			}
		}

		public static function ToTranslatedString($int<?php echo $objTypeTable->ClassName  ?>Id) {
			$strName = <?php echo $objTypeTable->ClassName  ?>::ToString($int<?php echo $objTypeTable->ClassName  ?>Id);
			if (!$strName) {
				return $strName;
			}
			return QApplication::Translate($strName);
		}

		public static function ToToken($int<?php echo $objTypeTable->ClassName  ?>Id) {
			if (is_null($int<?php echo $objTypeTable->ClassName  ?>Id)) {
<?php if ($strUnknown) { ?>
				$int<?php echo $objTypeTable->ClassName  ?>Id = <?php echo $strUnknown; ?>;
<?php } else { ?>
				return null;
<?php } ?>
			}
			switch ($int<?php echo $objTypeTable->ClassName  ?>Id) {
<?php foreach ($objTypeTable->TokenArray as $intKey=>$strValue) { ?>
				case <?php echo $intKey  ?>: return '<?php echo $strValue  ?>';
<?php } ?>
				default:
					throw new QCallerException(sprintf('Invalid int<?php echo $objTypeTable->ClassName  ?>Id: %s', $int<?php echo $objTypeTable->ClassName  ?>Id));
			}
		}

<?php foreach ($objTypeTable->ExtraFieldNamesArray as $strColName) { ?>
		public static function To<?php echo $strColName  ?>($int<?php echo $objTypeTable->ClassName  ?>Id) {
			if (is_null($int<?php echo $objTypeTable->ClassName  ?>Id)) {
<?php if ($strUnknown) { ?>
				$int<?php echo $objTypeTable->ClassName  ?>Id = <?php echo $strUnknown; ?>;
<?php } else { ?>
				return null;
<?php } ?>
			}
			if (array_key_exists($int<?php echo $objTypeTable->ClassName  ?>Id, <?php echo $objTypeTable->ClassName  ?>::$ExtraColumnValuesArray))
				return <?php echo $objTypeTable->ClassName  ?>::$ExtraColumnValuesArray[$int<?php echo $objTypeTable->ClassName  ?>Id]['<?php echo $strColName  ?>'];
			else
				throw new QCallerException(sprintf('Invalid int<?php echo $objTypeTable->ClassName  ?>Id: %s', $int<?php echo $objTypeTable->ClassName  ?>Id));
		}

		public static function ToTranslated<?php echo $strColName  ?>($int<?php echo $objTypeTable->ClassName  ?>Id) {
			$str = <?php echo $objTypeTable->ClassName  ?>::To<?php echo $strColName  ?>($int<?php echo $objTypeTable->ClassName  ?>Id);
			if (!$str) {
				return $str;
			}
			return QApplication::Translate($str);
		}

<?php } ?>
	}
?>
