/**
		 * Main constructor.  Constructor OR static create methods are designed to be called in either
		 * a parent QPanel or the main QForm when wanting to create a
		 * <?php echo $objTable->ClassName  ?>MetaControl to edit a single <?php echo $objTable->ClassName  ?> object within the
		 * QPanel or QForm.
		 *
		 * This constructor takes in a single <?php echo $objTable->ClassName  ?> object, while any of the static
		 * create methods below can be used to construct based off of individual PK ID(s).
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this <?php echo $objTable->ClassName  ?>MetaControl
		 * @param <?php echo $objTable->ClassName  ?> $<?php echo $objCodeGen->VariableNameFromTable($objTable->Name);  ?> new or existing <?php echo $objTable->ClassName  ?> object
		 */
		 public function __construct($objParentObject, <?php echo $objTable->ClassName  ?> $<?php echo $objCodeGen->VariableNameFromTable($objTable->Name);  ?>) {
			// Setup Parent Object (e.g. QForm or QPanel which will be using this <?php echo $objTable->ClassName  ?>MetaControl)
			$this->objParentObject = $objParentObject;

			// Setup linked <?php echo $objTable->ClassName  ?> object
			$this-><?php echo $objCodeGen->VariableNameFromTable($objTable->Name);  ?> = $<?php echo $objCodeGen->VariableNameFromTable($objTable->Name);  ?>;

			// Figure out if we're Editing or Creating New
			if ($this-><?php echo $objCodeGen->VariableNameFromTable($objTable->Name);  ?>->__Restored) {
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
		 * @param mixed $objParentObject QForm or QPanel which will be using this <?php echo $objTable->ClassName  ?>MetaControl
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
		 * @param <?php echo $objColumn->VariableType  ?> $<?php echo $objColumn->VariableName  ?> primary key value
<?php } ?>
		 * @param QMetaControlCreateType $intCreateType rules governing <?php echo $objTable->ClassName  ?> object creation - defaults to CreateOrEdit
 		 * @return <?php echo $objTable->ClassName  ?>MetaControl
		 */
		public static function Create($objParentObject, <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?php echo $objColumn->VariableName  ?> = null, <?php } ?>$intCreateType = QMetaControlCreateType::CreateOrEdit) {
			// Attempt to Load from PK Arguments
			if (<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>strlen($<?php echo $objColumn->VariableName  ?>) && <?php } ?><?php GO_BACK(4); ?>) {
				$<?php echo $objCodeGen->VariableNameFromTable($objTable->Name);  ?> = <?php echo $objTable->ClassName  ?>::Load(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?php echo $objColumn->VariableName  ?>, <?php } ?><?php GO_BACK(2); ?>);

				// <?php echo $objTable->ClassName  ?> was found -- return it!
				if ($<?php echo $objCodeGen->VariableNameFromTable($objTable->Name);  ?>)
					return new <?php echo $objTable->ClassName  ?>MetaControl($objParentObject, $<?php echo $objCodeGen->VariableNameFromTable($objTable->Name);  ?>);

				// If CreateOnRecordNotFound not specified, throw an exception
				else if ($intCreateType != QMetaControlCreateType::CreateOnRecordNotFound)
					throw new QCallerException('Could not find a <?php echo $objTable->ClassName  ?> object with PK arguments: ' . <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?php echo $objColumn->VariableName  ?> . ', ' . <?php } ?><?php GO_BACK(10); ?>);

			// If EditOnly is specified, throw an exception
			} else if ($intCreateType == QMetaControlCreateType::EditOnly)
				throw new QCallerException('No PK arguments specified');

			// If we are here, then we need to create a new record
			return new <?php echo $objTable->ClassName  ?>MetaControl($objParentObject, new <?php echo $objTable->ClassName  ?>());
		}

		/**
		 * Static Helper Method to Create using PathInfo arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this <?php echo $objTable->ClassName  ?>MetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing <?php echo $objTable->ClassName  ?> object creation - defaults to CreateOrEdit
		 * @return <?php echo $objTable->ClassName  ?>MetaControl
		 */
		public static function CreateFromPathInfo($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
<?php $_INDEX = 0; foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
			$<?php echo $objColumn->VariableName  ?> = QApplication::PathInfo(<?php echo $_INDEX  ?>);
<?php $_INDEX++; } ?>
			return <?php echo $objTable->ClassName  ?>MetaControl::Create($objParentObject, <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?php echo $objColumn->VariableName  ?>, <?php } ?>$intCreateType);
		}

		/**
		 * Static Helper Method to Create using QueryString arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this <?php echo $objTable->ClassName  ?>MetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing <?php echo $objTable->ClassName  ?> object creation - defaults to CreateOrEdit
		 * @return <?php echo $objTable->ClassName  ?>MetaControl
		 */
		public static function CreateFromQueryString($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
			$<?php echo $objColumn->VariableName  ?> = QApplication::QueryString('<?php echo $objColumn->VariableName  ?>');
<?php } ?>
			return <?php echo $objTable->ClassName  ?>MetaControl::Create($objParentObject, <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?php echo $objColumn->VariableName  ?>, <?php } ?>$intCreateType);
		}

		/**
		 * Static Helper Method to Create using a reference to the <?php echo $objTable->ClassName ?> object
		 *
		 * @param QForm|QControl $objParentObject QForm or QPanel which will be using this <?php echo $objTable->ClassName  ?>MetaControl
		 * @param mixed $obj<?php echo $objTable->ClassName ?>Ref reference to the <?php echo $objTable->ClassName ?> object, could be the metacontrol, the object itself, or anything that can be used to load the object
		 * @param int|QMetaControlCreateType $intCreateType rules governing <?php echo $objTable->ClassName  ?> object creation - defaults to CreateOrEdit
		 * @throws QCallerException
		 * @return <?php echo $objTable->ClassName  ?>MetaControl
		 */
		public static function From($objParentObject, $obj<?php echo $objTable->ClassName ?>Ref, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			if (!$obj<?php echo $objTable->ClassName ?>Ref || !is_object($obj<?php echo $objTable->ClassName ?>Ref)) {
				return <?php echo $objTable->ClassName ?>MetaControl::Create($objParentObject, $obj<?php echo $objTable->ClassName ?>Ref, $intCreateType);
			}
			if ($obj<?php echo $objTable->ClassName ?>Ref instanceof <?php echo $objTable->ClassName ?>MetaControl) {
				if ($obj<?php echo $objTable->ClassName ?>Ref->objParentObject == $objParentObject)
					return $obj<?php echo $objTable->ClassName ?>Ref;
				$obj<?php echo $objTable->ClassName ?>Ref = $obj<?php echo $objTable->ClassName ?>Ref-><?php echo $objTable->ClassName ?>;
			}
			if ($obj<?php echo $objTable->ClassName ?>Ref instanceof <?php echo $objTable->ClassName ?>) {
				return new <?php echo $objTable->ClassName ?>MetaControl($objParentObject, $obj<?php echo $objTable->ClassName ?>Ref);
			}
			throw new QCallerException('Cannot create a <?php echo $objTable->ClassName ?>MetaControl object from an object of type '.get_class($obj<?php echo $objTable->ClassName ?>Ref));
		}

		/**
		 * Static Helper Method to Create using a reference to the <?php echo $objTable->ClassName ?> object
		 *
		 * @param QForm|QControl $objParentObject QForm or QPanel which will be using this <?php echo $objTable->ClassName  ?>MetaControl
		 * @param mixed $obj<?php echo $objTable->ClassName ?>Ref reference to the <?php echo $objTable->ClassName ?> object, could be the metacontrol, the object itself, or anything that can be used to load the object
		 * @return boolean
		 */
		public function Matches($objParentObject, $obj<?php echo $objTable->ClassName ?>Ref) {
			if (!$obj<?php echo $objTable->ClassName ?>Ref)
				return false;
			if ($objParentObject != $this->objParentObject)
				return false;

			if (!is_object($obj<?php echo $objTable->ClassName ?>Ref)) {
<?php if (count($objTable->PrimaryKeyColumnArray) == 1) { ?>
				return $this-><?php echo $objTable->ClassName ?>-><?php echo $objTable->PrimaryKeyColumnArray[0]->PropertyName ?> === $obj<?php echo $objTable->ClassName ?>Ref;
<?php } else { ?>
				return $this-><?php echo $objTable->ClassName ?>->__IdAsArray === $obj<?php echo $objTable->ClassName ?>Ref;
<?php } ?>
			}
			if ($obj<?php echo $objTable->ClassName ?>Ref instanceof <?php echo $objTable->ClassName ?>MetaControl) {
				return $obj<?php echo $objTable->ClassName ?>Ref != $this;
			}
			if ($obj<?php echo $objTable->ClassName ?>Ref instanceof <?php echo $objTable->ClassName ?>) {
				return $obj<?php echo $objTable->ClassName ?>Ref != $this-><?php echo $objTable->ClassName ?>;
			}
			return false;
		}
