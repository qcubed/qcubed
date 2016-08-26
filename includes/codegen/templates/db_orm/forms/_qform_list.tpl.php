<?php
	/** @var QSqlTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,	// TODO: Change to false
		'DocrootFlag' => true,
		'DirectorySuffix' => '',
		'TargetDirectory' => __FORMS__,
		'TargetFileName' => QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) . '_list.php'
	);
?>
<?php print("<?php\n"); ?>
	// Load the QCubed Development Framework
	require('../qcubed.inc.php');

	require(__PANEL__ . '/<?= $objTable->ClassName ?>ListPanel.class.php');

	/**
	 * This is a draft QForm object to do the List All functionality
	 * of the <?= $objTable->ClassName ?> class, and is a starting point for the form object.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 *
	 * @package <?= QCodeGen::$ApplicationName; ?>

	 */
	class <?= $objTable->ClassName ?>ListForm extends QForm {
		protected $pnlNav;
		protected $pnl<?= $objTable->ClassName ?>List;

		// Override Form Event Handlers as Needed
		protected function Form_Run() {
			parent::Form_Run();

			// Security check for ALLOW_REMOTE_ADMIN
			// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
			QApplication::CheckRemoteAdmin();		    
		}

		protected function Form_Create() {
			$this->pnlNav = new NavPanel($this);
			$this->pnl<?= $objTable->ClassName ?>List = new <?= $objTable->ClassName ?>ListPanel($this);
		}
	}

	// Go ahead and run this form object to generate the page and event handlers, implicitly using
	// <?= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) ?>_list.tpl.php as the included HTML template file
	<?= $objTable->ClassName ?>ListForm::Run('<?= $objTable->ClassName ?>ListForm');