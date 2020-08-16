<?php
	/** @var QSqlTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;

    $strPropertyName = QCodeGen::DataListPropertyName($objTable);
    $strPropertyNamePlural = QCodeGen::DataListPropertyNamePlural($objTable);

    $_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => false,
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
     * @property-read string $ObjectName         The name of the object we are editing
     * @property-read string $ObjectNamePlural   The plural name of the object we are editing
	 *
	 * @package <?= QCodeGen::$ApplicationName; ?>

	 */
	class <?= $objTable->ClassName ?>ListForm extends QForm {
        /** @var NavPanel */
		protected $pnlNav;
        /** @var <?= $objTable->ClassName ?>ListPanel */
		protected $pnl<?= $objTable->ClassName ?>List;
        /** @var string */
        protected $strObjectName;
        /** @var string */
        protected $strObjectNamePlural;

		// Override Form Event Handlers as Needed
		protected function Form_Run() {
			parent::Form_Run();

			// Security check for ALLOW_REMOTE_ADMIN
			// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
			QApplication::CheckRemoteAdmin();		    
		}

		protected function Form_Create() {
            $this->strObjectName = QApplication::Translate('<?= $strPropertyName ?>');
            $this->strObjectNamePlural = QApplication::Translate('<?= $strPropertyNamePlural ?>');
			$this->pnlNav = new NavPanel($this);
			$this->pnl<?= $objTable->ClassName ?>List = new <?= $objTable->ClassName ?>ListPanel($this);
		}

        /**
		 * PHP __get magic method implementation
		 * @param string $strName Name of the property
		 *
		 * @return mixed
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case "ObjectName": return $this->strObjectName;
				case "ObjectNamePlural": return $this->strObjectNamePlural;

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

	// Go ahead and run this form object to generate the page and event handlers, implicitly using
	// <?= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) ?>_list.tpl.php as the included HTML template file
	<?= $objTable->ClassName ?>ListForm::Run('<?= $objTable->ClassName ?>ListForm');