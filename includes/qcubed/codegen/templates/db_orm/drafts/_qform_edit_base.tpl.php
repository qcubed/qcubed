<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __FORMBASE_CLASSES__  ?>" TargetFileName="<?php echo $objTable->ClassName  ?>EditFormBase.class.php"/>
<?php print("<?php\n"); ?>
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>UpdatePanel.class.php');
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
		/** @var <?php echo $objTable->ClassName  ?>EditPanel */
		protected $pnl<?php echo $objTable->ClassName  ?>;
		// Create QForm Event Handlers as Needed

//		protected function Form_Exit() {}
//		protected function Form_Load() {}
//		protected function Form_PreRender() {}

		protected function Form_Run() {
			parent::Form_Run();
		}

		protected function Form_Create() {
			parent::Form_Create();

			$this->pnl<?php echo $objTable->ClassName  ?> = new <?php echo $objTable->ClassName  ?>UpdatePanel($this, QApplication::PathInfo(0));
		}

		/**
		 * This Form_Validate event handler allows you to specify any custom Form Validation rules.
		 * It will also Blink() on all invalid controls, as well as Focus() on the top-most invalid control.
		 */
		protected function Form_Validate() {
			// By default, we report the result of validation from the parent
			$blnToReturn = parent::Form_Validate();

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

		// Other Methods

		public function RedirectToListPage() {
			QApplication::Redirect(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/<?php echo strtolower($objTable->Name)  ?>_list.php');
		}
	}
?>
