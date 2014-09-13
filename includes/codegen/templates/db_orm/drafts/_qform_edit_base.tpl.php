<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __FORMBASE_CLASSES__,
		'TargetFileName' => $objTable->ClassName . 'EditFormBase.class.php'
	);
?>
<?php print("<?php\n"); ?>
	/**
	 * This is a quick-and-dirty draft QForm object to do Create, Edit, and Delete functionality
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

	 * @subpackage FormBaseObjects
	 */
	abstract class <?php echo $objTable->ClassName  ?>EditFormBase extends QForm {
		// Local instance of the <?php echo $objTable->ClassName  ?>MetaControl
		/**
		 * @var <?php echo $objTable->ClassName  ?>MetaControlGen mct<?php echo $objTable->ClassName  ?>

		 */
		protected $mct<?php echo $objTable->ClassName  ?>;

		// Controls for <?php echo $objTable->ClassName  ?>'s Data Fields
<?php foreach ($objTable->ColumnArray as $objColumn) {
		if (!isset ($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] == 'meta') {
?>
		protected $<?php echo $objCodeGen->FormControlVariableNameForColumn($objColumn);  ?>;
<?php } ?>
<?php } ?>

		// Other ListBoxes (if applicable) via Unique ReverseReferences and ManyToMany References
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
		protected $<?php echo $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);  ?>;
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
		protected $<?php echo $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);  ?>;
<?php } ?>

		// Other Controls
		/**
		 * @var QButton Save
		 */
		protected $btnSave;
		/**
		 * @var QButton Delete
		 */
		protected $btnDelete;
		/**
		 * @var QButton Cancel
		 */
		protected $btnCancel;

		// Create QForm Event Handlers as Needed

//		protected function Form_Exit() {}
//		protected function Form_Load() {}
//		protected function Form_PreRender() {}

		protected function Form_Run() {
			parent::Form_Run();
		}

		protected function Form_Create() {
			parent::Form_Create();

			// Use the CreateFromPathInfo shortcut (this can also be done manually using the <?php echo $objTable->ClassName  ?>MetaControl constructor)
			// MAKE SURE we specify "$this" as the MetaControl's (and thus all subsequent controls') parent
			$this->mct<?php echo $objTable->ClassName  ?> = <?php echo $objTable->ClassName  ?>MetaControl::CreateFromPathInfo($this);

			// Call MetaControl's methods to create qcontrols based on <?php echo $objTable->ClassName  ?>'s data fields
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
	<?php	if ($objColumn->Options && isset ($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == 'none') continue; ?>
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
			$this->btnSave = new QButton($this);
			$this->btnSave->Text = QApplication::Translate('Save');
			$this->btnSave->AddAction(new QClickEvent(), new QAjaxAction('btnSave_Click'));
			$this->btnSave->CausesValidation = true;

			$this->btnCancel = new QButton($this);
			$this->btnCancel->Text = QApplication::Translate('Cancel');
			$this->btnCancel->AddAction(new QClickEvent(), new QAjaxAction('btnCancel_Click'));

			$this->btnDelete = new QButton($this);
			$this->btnDelete->Text = QApplication::Translate('Delete');
			$this->btnDelete->AddAction(new QClickEvent(), new QConfirmAction(sprintf(QApplication::Translate('Are you SURE you want to DELETE this %s?'), QApplication::Translate('<?php echo $objTable->ClassName  ?>'))));
			$this->btnDelete->AddAction(new QClickEvent(), new QAjaxAction('btnDelete_Click'));
			$this->btnDelete->Visible = $this->mct<?php echo $objTable->ClassName  ?>->EditMode;
		}

		/**
		 * This Form_Validate event handler allows you to specify any custom Form Validation rules.
		 * It will also Blink() on all invalid controls, as well as Focus() on the top-most invalid control.
		 */
		protected function Form_Validate() {
			// By default, we report the result of validation from the parent
			$blnToReturn = parent::Form_Validate();

			// Custom Validation Rules
			// TODO: Be sure to set $blnToReturn to false if any custom validation fails!
			<?php include("qform_validate_unique.tpl.php"); ?>

			$blnFocused = false;
			foreach ($this->GetErrorControls() as $objControl) {
				// Set Focus to the top-most invalid control
				if (!$blnFocused) {
					$objControl->Focus();
					$blnFocused = true;
				}

				// Blink on ALL invalid controls
				$objControl->Blink();
			}

			return $blnToReturn;
		}

		// Button Event Handlers

		protected function btnSave_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Save" processing to the <?php echo $objTable->ClassName  ?>MetaControl
			$this->mct<?php echo $objTable->ClassName  ?>->Save<?php echo $objTable->ClassName  ?>();
			$this->RedirectToListPage();
		}

		protected function btnDelete_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Delete" processing to the <?php echo $objTable->ClassName  ?>MetaControl
			$this->mct<?php echo $objTable->ClassName  ?>->Delete<?php echo $objTable->ClassName  ?>();
			$this->RedirectToListPage();
		}

		protected function btnCancel_Click($strFormId, $strControlId, $strParameter) {
			$this->RedirectToListPage();
		}

		// Other Methods

		protected function RedirectToListPage() {
			QApplication::Redirect(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/<?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) ?>_list.php');
		}
	}
?>