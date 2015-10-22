	/**
	 * @param QForm|QControl $objParentObject
	 * @param null|string $strControlId
	 * @throws Exception
	 * @throws QCallerException
	 */
	public function __construct($objParentObject, $strControlId = null) {
		// Call the Parent
		try {
			parent::__construct($objParentObject, $strControlId);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		// Construct the <?= $strPropertyName ?>Connector
		// MAKE SURE we specify "$this" as the Connector's (and thus all subsequent controls') parent
		$this->mct<?= $strPropertyName ?> = <?= $strPropertyName ?>Connector::Create($this);

		$this->CreateObjects();
	}
