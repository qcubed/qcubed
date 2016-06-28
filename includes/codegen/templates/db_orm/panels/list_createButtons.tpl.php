	/**
	 *
	 **/
	protected function CreateButtonPanel() {
		$this->pnlButtons = new QPanel ($this);
		$this->pnlButtons->AutoRenderChildren = true;

		$this->btnNew = new <?= QCodeGen::$DefaultButtonClass ?> ($this->pnlButtons);
		$this->btnNew->Text = QApplication::Translate ('New');
		$this->btnNew->AddAction (new QClickEvent(), new QAjaxControlAction ($this, 'btnNew_Click'));
	}

	protected function btnNew_Click($strFormId, $strControlId, $strParameter) {
		$this->EditItem();
	}
