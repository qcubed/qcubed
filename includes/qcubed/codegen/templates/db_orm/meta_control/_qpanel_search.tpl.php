<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS_GEN__  ?>" TargetFileName="<?php echo $objTable->ClassName  ?>SearchPanelGen.class.php"/>
<?php print("<?php\n"); ?>
	/**
	 * This is a QPanel object for Search functionality
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
	 * @property-read QJqButton $SearchButton
	 * @property-read QJqButton $ResetButton
	 */
	class <?php echo $objTable->ClassName  ?>SearchPanelGen extends SearchPanel {
		// Controls for <?php echo $objTable->ClassName  ?>'s Data Fields
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
		public $<?php echo $objCodeGen->FormControlVariableNameForColumn($objColumn);  ?>;
<?php } ?>

		// Other Controls
		/** @var QButton Search */
		public $btnSearch;
		/** @var QButton Reset */
		public $btnReset;

		public function __construct($objParentObject, $blnSearchOnPk = true, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, QQN::<?php echo $objTable->ClassName ?>(), $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$this->strTemplate = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>SearchPanel.tpl.php';
			$this->CssClass = 'ui-widget search_panel';

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (!$objColumn->Reference) { ?>
			$this-><?php echo $objCodeGen->FormControlVariableNameForColumn($objColumn);  ?> = $this->AddSearchControl('<?php echo $objColumn->PropertyName ?>', <?php echo QType::Constant($objColumn->VariableType) ?>, QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->PropertyName)  ?>'));
<?php } else if ($objColumn->Reference->IsType) { ?>
			$this-><?php echo $objCodeGen->FormControlVariableNameForColumn($objColumn);  ?> = $this->AddSearchControl('<?php echo $objColumn->PropertyName ?>', <?php echo $objColumn->Reference->VariableType  ?>::$NameArray, QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->PropertyName)  ?>'));
<?php } ?>
<?php } ?>

			// Create Buttons and Actions on this Form
			$this->btnSearch = new QJqButton($this);
			$this->btnSearch->Text = QApplication::Translate('Search');
			$this->btnSearch->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnSearch_Click'));
			$this->btnSearch->CausesValidation = $this;
			$this->btnSearch->Icons = array('primary' => JqIcon::Search);

			$this->btnReset = new QJqButton($this);
			$this->btnReset->Text = QApplication::Translate('Reset');
			$this->btnReset->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnReset_Click'));
			$this->btnReset->Icons = array('primary' => JqIcon::Cancel);
		}

		// Control AjaxAction Event Handlers
		public function btnReset_Click($strFormId, $strControlId, $strParameter) {
			$this->ResetAllControls();
		}

		public function __get($strName) {
			switch ($strName) {
				case "SearchButton": return $this->btnSearch;
				case "ResetButton": return $this->btnReset;
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
