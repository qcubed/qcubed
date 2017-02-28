   /**
    * Process a click on the Save button.
    */
    protected function Save() {
        try {
		    $this->pnl<?= $strPropertyName ?>->Save();
        }
        catch (QOptimisticLockingException $e) {
            $dlg = QDialog::Alert (
                QApplication::Translate("Another user has changed the information while you were editing it. Would you like to overwrite their changes, or refresh the page and try editing again?"),
                ["Refresh", "Overwrite"]);
            $dlg->AddAction(new QDialog_ButtonEvent(0, null, null, true), new QAjaxControlAction($this, "dlgOptimisticLocking_ButtonEvent"));
            return;
        }
        $this->Close();
	}

   /**
    * An optimistic lock exception has fired and we have put a dialog on the screen asking the user what they want to do.
    * The user can either overwrite the data, or refresh and start the edit process over.
    *
    * @param string $strFormId      The form id
    * @param string $strControlId   The control id of the dialog
    * @param string $btn            The text on the button
    */
    protected function dlgOptimisticLocking_ButtonEvent($strFormId, $strControlId, $btn) {
        if ($btn == "Overwrite") {
            $this->pnl<?= $strPropertyName ?>->Save(true);
            $this->Form->GetControl($strControlId)->Close();
            $this->Close();
        } else { // Refresh
            $this->Form->GetControl($strControlId)->Close();
            $this->pnl<?= $strPropertyName ?>->Refresh(true);
        }
    }
