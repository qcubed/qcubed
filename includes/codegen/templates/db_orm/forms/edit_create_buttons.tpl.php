	/**
	 * Create the buttons at the bottom of the dialog.
	 */
	protected function CreateButtons() {
		// Create Buttons and Actions on this Form
		$this->btnSave = new <?= QCodeGen::$DefaultButtonClass ?>($this);
		$this->btnSave->Text = QApplication::Translate('Save');
		$this->btnSave->AddAction(new QClickEvent(), new QAjaxAction('btnSave_Click'));
		$this->btnSave->CausesValidation = true;

		$this->btnCancel = new <?= QCodeGen::$DefaultButtonClass ?>($this);
		$this->btnCancel->Text = QApplication::Translate('Cancel');
		$this->btnCancel->AddAction(new QClickEvent(), new QAjaxAction('btnCancel_Click'));

		$this->btnDelete = new <?= QCodeGen::$DefaultButtonClass ?>($this);
		$this->btnDelete->Text = QApplication::Translate('Delete');
		$this->btnDelete->AddAction(new QClickEvent(), new QConfirmAction(sprintf(QApplication::Translate('Are you SURE you want to DELETE this %s?'), QApplication::Translate('<?= $objTable->ClassName ?>'))));
		$this->btnDelete->AddAction(new QClickEvent(), new QAjaxAction('btnDelete_Click'));
		$this->btnDelete->Visible = $this->pnl<?= $strPropertyName ?>->mct<?= $objTable->ClassName ?>->EditMode;
	}
