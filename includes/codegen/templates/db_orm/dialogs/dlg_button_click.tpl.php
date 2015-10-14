	/**
	 * A button was clicked. Override to do something different than the default or process further.
	 * @param string $strFormId
	 * @param string $strControlId
	 * @param mixed $strParameter
	 */

	public function ButtonClick($strFormId, $strControlId, $strParameter) {
		switch ($strParameter) {
			case 'save':
				$this->pnl<?= $strPropertyName ?>->Save();
				break;

			case 'delete':
				$this->pnl<?= $strPropertyName ?>->Delete();
				break;

			case 'cancel':
				// do nothing
				break;
		}
		$this->Close();
	}
