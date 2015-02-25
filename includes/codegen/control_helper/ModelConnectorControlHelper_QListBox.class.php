<?php

class ModelConnectorControlHelper_QListBox extends ModelConnectorControlHelper_QControl {
	/** @var  ModelConnectorControlHelper_QListBox */
	private static $instance = null;

	protected function __construct() {
		parent::__construct('QListBox');
	}

	/**
	 * @return ModelConnectorControlHelper_QLabel
	 */
	public static function Instance() {
		if (!self::$instance) {
			self::$instance = new ModelConnectorControlHelper_QListBox();
		}
		return self::$instance;
	}

	/**
	 * Reads the options from the special data file, and possibly the column
	 * @param QCodeGenBase $objCodeGen
	 * @param QTable $objTable
	 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
	 * @param string $strControlVarName
	 * @return string
	 */
	public function ConnectorCreateOptions(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn, $strControlVarName) {
		$strRet = parent::ConnectorCreateOptions($objCodeGen, $objTable, $objColumn, $strControlVarName);

		if ($objColumn instanceof QManyToManyReference) {
			$strRet .= <<<TMPL
			\$this->{$strControlVarName}->SelectionMode = QSelectionMode::Multiple;

TMPL;
		}
		return $strRet;
	}
}