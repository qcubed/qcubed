<?php
/**
 * Code generator for the DataGrid2 object.
 */

class QDataGrid2Base_CodeGenerator extends QSimpleTable_CodeGenerator {
	/** @var  string */
	protected $strControlClassName;

	public function __construct($strControlClassName = 'QDataGrid2') {
		$this->strControlClassName = $strControlClassName;
	}
	
}