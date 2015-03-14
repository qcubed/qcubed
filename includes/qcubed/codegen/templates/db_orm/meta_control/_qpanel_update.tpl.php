<?php
    /** @var QTable $objTable */
    /** @var QTable[] $objTableArray */
    /** @var QDatabaseCodeGen $objCodeGen */
    global $_TEMPLATE_SETTINGS;
    $_TEMPLATE_SETTINGS = array(
        'OverwriteFlag' => true,
        'DocrootFlag' => false,
        'DirectorySuffix' => '',
        'TargetDirectory' => __META_CONTROLS_GEN__,
        'TargetFileName' => $objTable->ClassName.'UpdatePanelGen.class.php'
    );
?>
<?php print("<?php\n"); ?>
	/**
	 * This is a QPanel object to do Create, Edit, and Delete functionality
	 * of the <?php echo $objTable->ClassName  ?> class.  It uses the code-generated
	 * <?php echo $objTable->ClassName  ?>MetaControl class, which has meta-methods to help with
	 * easily creating/defining controls to modify the fields of a <?php echo $objTable->ClassName  ?> columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 *
	 * NOTE: This file is overwritten on any code regenerations.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage MetaControls
	 */

	/**
	 * @property-read <?php echo $objTable->ClassName ?>MetaControl $MetaControl
	 * @property-read <?php echo $objTable->ClassName ?> $SavedObject
	 * @property-read string $TitleVerb
	 * @property-read QJqButton $SaveButton
	 * @property-read QJqButton $CancelButton
	 * @property-read QJqButton $DeleteButton
	 * @property-write QCallback $SaveCallback
	 * @property-write QCallback $CancelCallback
	 * @property-write QCallback $DeleteCallback
	 */
	class <?php echo $objTable->ClassName  ?>UpdatePanelGen extends QPanel {
		// Local instance of the <?php echo $objTable->ClassName  ?>MetaControl
		/** @var <?php echo $objTable->ClassName  ?>MetaControl */
		protected $mct<?php echo $objTable->ClassName  ?>;

		// Controls for <?php echo $objTable->ClassName  ?>'s Data Fields
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
		public $<?php echo $objCodeGen->FormControlVariableNameForColumn($objColumn);  ?>;
<?php } ?>

		// Other ListBoxes (if applicable) via Unique ReverseReferences and ManyToMany References
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
		public $<?php echo $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);  ?>;
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
		public $<?php echo $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);  ?>;
<?php } ?>

		// Other Controls
		/** @var QButton Save */
		public $btnSave;
		/** @var QButton Delete */
		public $btnDelete;
		/** @var QButton Cancel */
		public $btnCancel;

		/** @var QCallback */
		protected $objSaveCallback;
		/** @var QCallback */
		protected $objCancelCallback;
		/** @var QCallback */
		protected $objDeleteCallback;

		public function __construct($objParentObject, $obj<?php echo $objTable->ClassName ?>Ref = null, $blnShowPk = true, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$this->strTemplate = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>UpdatePanel.tpl.php';
			$this->CssClass = 'ui-widget update_panel';

			$this->mct<?php echo $objTable->ClassName  ?> = <?php echo $objTable->ClassName  ?>MetaControl::From($this, $obj<?php echo $objTable->ClassName ?>Ref);

			// Call MetaControl's methods to create qcontrols based on <?php echo $objTable->ClassName  ?>'s data fields
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
			$this-><?php echo $objCodeGen->FormControlVariableNameForColumn($objColumn);  ?> = $this->mct<?php echo $objTable->ClassName  ?>-><?php echo $objCodeGen->FormControlVariableNameForColumn($objColumn);  ?>_Create();
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
			$this-><?php echo $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);  ?> = $this->mct<?php echo $objTable->ClassName  ?>-><?php echo $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);  ?>_Create();
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
			$this-><?php echo $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);  ?> = $this->mct<?php echo $objTable->ClassName  ?>-><?php echo $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);  ?>_Create();
<?php } ?>

			// Create Buttons and Actions on this Form
			$this->btnSave = new QJqButton($this);
			$this->btnSave->Text = QApplication::Translate('Save');
			$this->btnSave->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnSave_Click'));
			$this->btnSave->CausesValidation = $this;
			$this->btnSave->Icons = array('primary' => JqIcon::Disk);

			$this->btnCancel = new QJqButton($this);
			$this->btnCancel->Text = QApplication::Translate('Cancel');
			$this->btnCancel->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnCancel_Click'));
			$this->btnCancel->Icons = array('primary' => JqIcon::Cancel);

			$this->btnDelete = new QJqButton($this);
			$this->btnDelete->Text = QApplication::Translate('Delete');
			$this->btnDelete->AddAction(new QClickEvent(), new QConfirmAction(sprintf(QApplication::Translate('Are you SURE you want to DELETE this %s?'),  QApplication::Translate('<?php echo $objTable->ClassName  ?>'))));
			$this->btnDelete->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnDelete_Click'));
			$this->btnDelete->Visible = $this->mct<?php echo $objTable->ClassName  ?>->EditMode;
			$this->btnDelete->Icons = array('primary' => JqIcon::Trash);
		}

		// Control AjaxAction Event Handlers
		public function btnSave_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Save" processing to the <?php echo $objTable->ClassName  ?>MetaControl
			$this->mct<?php echo $objTable->ClassName  ?>->Save<?php echo $objTable->ClassName  ?>();
			if ($this->objSaveCallback) {
				$this->objSaveCallback->Call($this->mct<?php echo $objTable->ClassName  ?>-><?php echo $objTable->ClassName ?>, true, false);
			}
		}

		public function btnDelete_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Delete" processing to the <?php echo $objTable->ClassName  ?>MetaControl
			$this->mct<?php echo $objTable->ClassName  ?>->Delete<?php echo $objTable->ClassName  ?>();
			if ($this->objDeleteCallback) {
				$this->objDeleteCallback->Call($this->mct<?php echo $objTable->ClassName  ?>-><?php echo $objTable->ClassName ?>, false, true);
			}
		}

		public function btnCancel_Click($strFormId, $strControlId, $strParameter) {
			if ($this->objCancelCallback) {
				$this->objCancelCallback->Call($this->mct<?php echo $objTable->ClassName  ?>-><?php echo $objTable->ClassName ?>, false, false);
			}
		}

		<?php include("qpanel_validate_unique.tpl.php"); ?>

		public function __get($strName) {
			switch ($strName) {
				case "MetaControl": return $this->mct<?php echo $objTable->ClassName ?>;
				case "TitleVerb": return $this->mct<?php echo $objTable->ClassName ?>->TitleVerb;
				case "SavedObject": return $this->mct<?php echo $objTable->ClassName ?>-><?php echo $objTable->ClassName ?>;
				case "DeleteButton": return $this->btnDelete;
				case "CancelButton": return $this->btnCancel;
				case "SaveButton": return $this->btnSave;
				case "SaveCallback": return $this->objSaveCallback;
				case "DeleteCallback": return $this->objDeleteCallback;
				case "CancelCallback": return $this->objCancelCallback;
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
			switch ($strName) {
				case "SaveCallback":
					try {
						$this->objSaveCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "DeleteCallback":
					try {
						$this->objDeleteCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CancelCallback":
					try {
						$this->objCancelCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
?>
