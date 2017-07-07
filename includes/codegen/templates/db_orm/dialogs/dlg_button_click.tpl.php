	/**
	 * A button was clicked. Override to do something different than the default or process further.
	 * @param string $strFormId
	 * @param string $strControlId
	 * @param mixed $strParameter
	 */

	public function ButtonClick($strFormId, $strControlId, $strParameter) {
		switch ($strParameter) {
			case 'save':
				$this->Save();
				break;

			case 'delete':
				$this->pnl<?= $strPropertyName ?>->Delete();
                $this->Close();
				break;

			case 'cancel':
                $this->Close();
                break;
		}
	}
