<?php
	/** @var QTable[] $objTableArray */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __META_CONTROLS_GEN__,
		'TargetFileName' => 'AppControlFactory.class.php'
	);
?>
<?php print("<?php\n"); ?>
	require_once(__META_CONTROLS_GEN__ . '/DefaultControlFactory.class.php');

	class AppControlFactory {
		static private $inst;

		/**
		 * @param DefaultControlFactory $inst
		 */
		public static function SetInst($inst) {
			self::$inst = $inst;
		}

		/**
		 * @return DefaultControlFactory
		 */
		static public function Inst() {
			return self::$inst;
		}
	}

	AppControlFactory::SetInst(new DefaultControlFactory());
