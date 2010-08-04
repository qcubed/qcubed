<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<%= __META_CONTROLS_GEN__ %>" TargetFileName="<%= $objTable->ClassName %>DataGridGen.class.php"/>
<?php
	/**
	 * This is the "Meta" DataGrid class for the List functionality
	 * of the <%= $objTable->ClassName %> class.  This code-generated class
	 * contains a QDataGrid class which can be used by any QForm or QPanel,
	 * listing a collection of <%= $objTable->ClassName %> objects.  It includes
	 * functionality to perform pagination and sorting on columns.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create an instance of this DataGrid in a QForm or QPanel.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent re-
	 * code generation.
	 *
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @property QQCondition $AdditionalConditions Any conditions to use during binding
	 * @property QQClause $AdditionalClauses Any clauses to use during binding
	 * @subpackage MetaControls
	 *
	 */
	class <%= $objTable->ClassName %>DataGridGen extends QDataGrid {
		protected $conAdditionalConditions;
		protected $clsAdditionalClauses;

		protected $blnShowFilter = true;

		<%@ constructor('objTable'); %>


		<%@ meta_add_column('objTable'); %>


		<%@ meta_add_type_column('objTable'); %>


		<%@ meta_add_edit_column('objTable'); %>


		<%@ meta_data_binder('objTable'); %>


		<%@ resolve_content_item('objTable'); %>

		/**
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'AdditionalConditions':
					return $this->conAdditionalConditions;
				case 'AdditionalClauses':
					return $this->clsAdditionalClauses;
				default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}

		/**
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'AdditionalConditions':
				try {
					return ($this->conAdditionalConditions = QType::Cast($mixValue, 'QQCondition'));
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
				case 'AdditionalClauses':
				try {
					return ($this->clsAdditionalClauses = QType::Cast($mixValue, QType::ArrayType));
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
				default:
				try {
					parent::__set($strName, $mixValue);
					break;
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}
	}
?>