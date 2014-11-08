<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => true,
		'DirectorySuffix' => '',
		'TargetDirectory' => __PANEL_DRAFTS__,
		'TargetFileName' => $objTable->ClassName . 'EditPanel.class.php'
	);
?>
<?php print("<?php\n"); ?>
	/**
	 * This is a quick-and-dirty draft QPanel object to do Create, Edit, and Delete functionality
	 * of the <?= $objTable->ClassName ?> class.  It uses the code-generated
	 * <?= $objTable->ClassName ?>MetaControl class, which has meta-methods to help with
	 * easily creating/defining controls to modify the fields of a <?= $objTable->ClassName ?> columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 *
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both <?= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) ?>_edit.php AND
	 * <?= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) ?>_edit.tpl.php out of this Form Drafts directory.
	 *
	 * @package <?= QCodeGen::$ApplicationName; ?>

	 * @subpackage Drafts
	 */
	class <?= $objTable->ClassName ?>EditPanel extends QPanel {
		// Local instance of the <?= $objTable->ClassName ?>MetaControl
		/**
		 * @var <?= $objTable->ClassName ?>MetaControl
		 */
		protected $mct<?= $objTable->ClassName ?>;

		// Controls for <?= $objTable->ClassName ?>'s Data Fields
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
		/** @var <?= $objCodeGen->FormControlClassForColumn($objColumn); ?>  */
		public $<?= $objCodeGen->FormControlVariableNameForColumn($objColumn); ?>;
<?php } ?>

		// Other ListBoxes (if applicable) via Unique ReverseReferences and ManyToMany References
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
		public $<?= $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference); ?>;
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
		public $<?= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); ?>;
<?php } ?>

		// Other Controls
		/**
		 * @var QButton Save
		 */
		public $btnSave;
		/**
		 * @var QButton Delete
		 */
		public $btnDelete;
		/**
		 * @var QButton Cancel
		 */
		public $btnCancel;

		// Callback
		protected $strClosePanelMethod;

		public function __construct($objParentObject, $strClosePanelMethod, <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?= $objColumn->VariableName; ?> = null, <?php } ?>$strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Setup Callback and Template
			$this->strTemplate = __DOCROOT__ . __PANEL_DRAFTS__ . '/<?= $objTable->ClassName ?>EditPanel.tpl.php';
			$this->strClosePanelMethod = $strClosePanelMethod;

			// Construct the <?= $objTable->ClassName ?>MetaControl
			// MAKE SURE we specify "$this" as the MetaControl's (and thus all subsequent controls') parent
			$this->mct<?= $objTable->ClassName ?> = <?= $objTable->ClassName ?>MetaControl::Create($this<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>, $<?= $objColumn->VariableName; ?><?php } ?>);

			// Call MetaControl's methods to create qcontrols based on <?= $objTable->ClassName ?>'s data fields
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
	<?php	if ($objColumn->Options && isset ($objColumn->Options['FormGen']) && ($objColumn->Options['FormGen'] == 'none' || $objColumn->Options['FormGen'] == 'meta')) continue; ?>
			$this-><?= $objCodeGen->FormControlVariableNameForColumn($objColumn); ?> = $this->mct<?= $objTable->ClassName ?>-><?= $objCodeGen->FormControlVariableNameForColumn($objColumn); ?>_Create();
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
			$this-><?= $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference); ?> = $this->mct<?= $objTable->ClassName ?>-><?= $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference); ?>_Create();
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
			$this-><?= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); ?> = $this->mct<?= $objTable->ClassName ?>-><?= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); ?>_Create();
<?php } ?>

			// Create Buttons and Actions on this Form
			$this->btnSave = new QButton($this);
			$this->btnSave->Text = QApplication::Translate('Save');
			$this->btnSave->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnSave_Click'));
			$this->btnSave->CausesValidation = $this;

			$this->btnCancel = new QButton($this);
			$this->btnCancel->Text = QApplication::Translate('Cancel');
			$this->btnCancel->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnCancel_Click'));

			$this->btnDelete = new QButton($this);
			$this->btnDelete->Text = QApplication::Translate('Delete');
			$this->btnDelete->AddAction(new QClickEvent(), new QConfirmAction(sprintf(QApplication::Translate('Are you SURE you want to DELETE this %s?'),  QApplication::Translate('<?= $objTable->ClassName ?>'))));
			$this->btnDelete->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnDelete_Click'));
			$this->btnDelete->Visible = $this->mct<?= $objTable->ClassName ?>->EditMode;
		}

		// Control AjaxAction Event Handlers
		public function btnSave_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Save" processing to the <?= $objTable->ClassName ?>MetaControl
			$this->mct<?= $objTable->ClassName ?>->Save<?= $objTable->ClassName ?>();
			$this->CloseSelf(true);
		}

		public function btnDelete_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Delete" processing to the <?= $objTable->ClassName ?>MetaControl
			$this->mct<?= $objTable->ClassName ?>->Delete<?= $objTable->ClassName ?>();
			$this->CloseSelf(true);
		}

		public function btnCancel_Click($strFormId, $strControlId, $strParameter) {
			$this->CloseSelf(false);
		}

		// Close Myself and Call ClosePanelMethod Callback
		protected function CloseSelf($blnChangesMade) {
			$strMethod = $this->strClosePanelMethod;
			$this->objForm->$strMethod($blnChangesMade);
		}

		<?php include("qpanel_validate_unique.tpl.php"); ?>

	}
?>