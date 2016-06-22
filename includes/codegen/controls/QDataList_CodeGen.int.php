<?php

/**
 * This interface describes the minimum functions to implement in order to create a code generator for a data list.
 * See QSimpleTable_CodeGenerator for an example
 *
 * Interface QListConnector_CodeGenerator
 */
interface QDataList_CodeGenerator_Interface {

	// To create the gen subclass of the object
	public function DataListConnectorComments(QCodeGenBase $objCodeGen, QTable $objTable);
	public function DataListConnector(QCodeGenBase $objCodeGen, QTable $objTable);

	// to create the panel
	public function DataListInstantiate(QCodeGenBase $objCodeGen, QTable $objTable);			// Create a new list in the parent constructor
	public function DataListHelperMethods(QCodeGenBase $objCodeGen, QTable $objTable);  // Additional functions called by the list creator
	public function DataListRefresh(QCodeGenBase $objCodeGen, QTable $objTable);		// How to refresh the data in the control. Only needed if using the parent filter. Can just call MarkAsModified.
	public function DataListHasFilter();	// Returns boolean if the control has its own filter, and thus the parent should not create a filter

	// for the sub-panel
	public function DataListSubclassOverrides(QCodeGenBase $objCodeGen, QTable $objTable);
}