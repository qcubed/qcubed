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
        'TargetFileName' => $objTable->ClassName.'ViewPanelGen.class.php'
    );
?>
<?php print("<?php\n"); ?>

	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>UpdatePanel.class.php');

	/**
	 * This is a quick-and-dirty draft QPanel object to do Create, Edit, and Delete functionality
	 * of the <?php echo $objTable->ClassName  ?> class.  It uses the code-generated
	 * <?php echo $objTable->ClassName  ?>MetaControl class, which has meta-methods to help with
	 * easily creating/defining controls to modify the fields of a <?php echo $objTable->ClassName  ?> columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 *
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_edit.php AND
	 * <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_edit.tpl.php out of this Form Drafts directory.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage Drafts
	 *
	 *
	 * @property-read <?php echo $objTable->ClassName ?>MetaControl $MetaControl
	 * @property-read string $TitleVerb
	 */
	class <?php echo $objTable->ClassName  ?>ViewPanelGen extends QPanel {
		// Local instance of the <?php echo $objTable->ClassName  ?>MetaControl
		/** @var <?php echo $objTable->ClassName  ?>MetaControl */
		protected $mct<?php echo $objTable->ClassName  ?>;

		// Controls for <?php echo $objTable->ClassName  ?>'s Data Fields
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
		public $<?php echo $objCodeGen->FormLabelVariableNameForColumn($objColumn);  ?>;
<?php } ?>

		// Other ListBoxes (if applicable) via Unique ReverseReferences and ManyToMany References
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
public $<?php echo $objCodeGen->FormLabelVariableNameForUniqueReverseReference($objReverseReference);  ?>;
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
public $<?php echo $objCodeGen->FormLabelVariableNameForManyToManyReference($objManyToManyReference);  ?>;
<?php } ?>

		// Other Controls
		/** @var QPanel */
		public $pnlToolbar;

		public function __construct($objParentObject, $obj<?php echo $objTable->ClassName ?>Ref = null, $blnShowPk = true, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$this->strTemplate = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>ViewPanel.tpl.php';
			$this->CssClass = 'ui-widget view_panel';

			$this->mct<?php echo $objTable->ClassName  ?> = <?php echo $objTable->ClassName  ?>MetaControl::From($this, $obj<?php echo $objTable->ClassName ?>Ref);

			// Call MetaControl's methods to create qcontrols based on <?php echo $objTable->ClassName  ?>'s data fields
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
			$this-><?php echo $objCodeGen->FormLabelVariableNameForColumn($objColumn);  ?> = $this->mct<?php echo $objTable->ClassName  ?>-><?php echo $objCodeGen->FormLabelVariableNameForColumn($objColumn);  ?>_Create();
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
			$this-><?php echo $objCodeGen->FormLabelVariableNameForUniqueReverseReference($objReverseReference);  ?> = $this->mct<?php echo $objTable->ClassName  ?>-><?php echo $objCodeGen->FormLabelVariableNameForUniqueReverseReference($objReverseReference);  ?>_Create();
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
			$this-><?php echo $objCodeGen->FormLabelVariableNameForManyToManyReference($objManyToManyReference);  ?> = $this->mct<?php echo $objTable->ClassName  ?>-><?php echo $objCodeGen->FormLabelVariableNameForManyToManyReference($objManyToManyReference);  ?>_Create();
<?php } ?>

		}

		public function __get($strName) {
			switch ($strName) {
				case "MetaControl": return $this->mct<?php echo $objTable->ClassName ?>;
				case "TitleVerb": return $this->mct<?php echo $objTable->ClassName ?>->TitleVerb;
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
