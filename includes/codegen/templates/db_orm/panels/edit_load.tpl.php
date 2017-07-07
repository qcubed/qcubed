	/**
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
	 * @param null|<?= $objColumn->VariableType ?> $<?= $objColumn->VariableName ?>

<?php } ?>
	 **/
	public function Load (<?php
			foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {
				echo '$'. $objColumn->VariableName . ' = null, ';
			} GO_BACK(2);?>) {
		if (!$this->mct<?php echo $objTable->ClassName  ?>->Load (<?php
			foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {
				echo '$'. $objColumn->VariableName . ', ';
			} GO_BACK(2);?>)) {
			QApplication::DisplayAlert(QApplication::Translate('Could not load the record. Perhaps it was deleted? Try again.'));
		}
	}

