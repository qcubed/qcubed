/**
		 * Main constructor.  Constructor OR static create methods are designed to be called in either
		 * a parent QPanel or the main QForm when wanting to create a
		 * <?= $objTable->ClassName ?>Connector to edit a single <?= $objTable->ClassName ?> object within the
		 * QPanel or QForm.
		 *
		 * This constructor takes in a single <?= $objTable->ClassName ?> object, while any of the static
		 * create methods below can be used to construct based off of individual PK ID(s).
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this <?= $objTable->ClassName ?>Connector
		 * @param <?= $objTable->ClassName ?> $<?= $objCodeGen->ModelVariableName($objTable->Name); ?> new or existing <?= $objTable->ClassName ?> object
		 */
		 public function __construct($objParentObject, <?= $objTable->ClassName ?> $<?= $objCodeGen->ModelVariableName($objTable->Name); ?>) {
			// Setup Parent Object (e.g. QForm or QPanel which will be using this <?= $objTable->ClassName ?>Connector)
			$this->objParentObject = $objParentObject;

			// Setup linked <?= $objTable->ClassName ?> object
			$this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?> = $<?= $objCodeGen->ModelVariableName($objTable->Name); ?>;

			// Figure out if we're Editing or Creating New
			if ($this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?>->__Restored) {
				$this->strTitleVerb = QApplication::Translate('Edit');
				$this->blnEditMode = true;
			} else {
				$this->strTitleVerb = QApplication::Translate('Create');
				$this->blnEditMode = false;
			}
		 }

		/**
		 * Static Helper Method to Create using PK arguments
		 * You must pass in the PK arguments on an object to load, or leave it blank to create a new one.
		 * If you want to load via QueryString or PathInfo, use the CreateFromQueryString or CreateFromPathInfo
		 * static helper methods.  Finally, specify a CreateType to define whether or not we are only allowed to
		 * edit, or if we are also allowed to create a new one, etc.
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this <?= $objTable->ClassName ?>Connector
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
		 * @param null|<?= $objColumn->VariableType ?> $<?= $objColumn->VariableName ?> primary key value
<?php } ?>
		 * @param integer $intCreateType rules governing <?= $objTable->ClassName ?> object creation - defaults to CreateOrEdit
 		 * @return <?= $objTable->ClassName ?>Connector
		 * @throws QCallerException
		 */
		public static function Create($objParentObject, <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?= $objColumn->VariableName ?> = null, <?php } ?>$intCreateType = QModelConnectorCreateType::CreateOrEdit) {
			// Attempt to Load from PK Arguments
			if (<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>strlen($<?= $objColumn->VariableName ?>) && <?php } ?><?php GO_BACK(4); ?>) {
				$<?= $objCodeGen->ModelVariableName($objTable->Name); ?> = <?= $objTable->ClassName ?>::Load(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?= $objColumn->VariableName ?>, <?php } ?><?php GO_BACK(2); ?>);

				// <?= $objTable->ClassName ?> was found -- return it!
				if ($<?= $objCodeGen->ModelVariableName($objTable->Name); ?>)
					return new <?= $objTable->ClassName ?>Connector($objParentObject, $<?= $objCodeGen->ModelVariableName($objTable->Name); ?>);

				// If CreateOnRecordNotFound not specified, throw an exception
				else if ($intCreateType != QModelConnectorCreateType::CreateOnRecordNotFound)
					throw new QCallerException('Could not find a <?= $objTable->ClassName ?> object with PK arguments: ' . <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?= $objColumn->VariableName ?> . ', ' . <?php } ?><?php GO_BACK(10); ?>);

			// If EditOnly is specified, throw an exception
			} else if ($intCreateType == QModelConnectorCreateType::EditOnly)
				throw new QCallerException('No PK arguments specified');

			// If we are here, then we need to create a new record
			return new <?= $objTable->ClassName ?>Connector($objParentObject, new <?= $objTable->ClassName ?>());
		}

		/**
		 * Static Helper Method to Create using PathInfo arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this <?= $objTable->ClassName ?>Connector
		 * @param integer $intCreateType rules governing <?= $objTable->ClassName ?> object creation - defaults to CreateOrEdit
		 * @return <?= $objTable->ClassName ?>Connector
		 */
		public static function CreateFromPathInfo($objParentObject, $intCreateType = QModelConnectorCreateType::CreateOrEdit) {
<?php $_INDEX = 0; foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
			$<?= $objColumn->VariableName ?> = QApplication::PathInfo(<?= $_INDEX ?>);
<?php $_INDEX++; } ?>
			return <?= $objTable->ClassName ?>Connector::Create($objParentObject, <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?= $objColumn->VariableName ?>, <?php } ?>$intCreateType);
		}

		/**
		 * Static Helper Method to Create using QueryString arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this <?= $objTable->ClassName ?>Connector
		 * @param integer $intCreateType rules governing <?= $objTable->ClassName ?> object creation - defaults to CreateOrEdit
		 * @return <?= $objTable->ClassName ?>Connector
		 */
		public static function CreateFromQueryString($objParentObject, $intCreateType = QModelConnectorCreateType::CreateOrEdit) {
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
			$<?= $objColumn->VariableName ?> = QApplication::QueryString('<?= $objColumn->VariableName ?>');
<?php } ?>
			return <?= $objTable->ClassName ?>Connector::Create($objParentObject, <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?= $objColumn->VariableName ?>, <?php } ?>$intCreateType);
		}