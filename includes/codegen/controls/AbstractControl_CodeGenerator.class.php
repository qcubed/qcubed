<?php

	abstract class AbstractControl_CodeGenerator {
		protected $strControlClassName;

		protected function __construct($strControlClassName) {
			$this->strControlClassName = $strControlClassName;
		}

		public function GetControlClass() {
			return $this->strControlClassName;
		}

		/**
		 * @param string $strPropName
		 * @return string
		 */
		abstract public function VarName($strPropName);

		/**
		 * Generate code that will be inserted into the ModelConnector to connect a database object with this control.
		 * This is called during the codegen process. This is very similar to the QListControl code, but there are
		 * some differences. In particular, this control does not support ManyToMany references.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		abstract public function ConnectorCreate(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn);

		/**
		 * @param QCodeGenBase $objCodeGen
		 * @param QColumn|QReverseReference| QManyToManyReference $objColumn
		 * @return string
		 */
		abstract public function ConnectorVariableDeclaration(QCodeGenBase $objCodeGen, $objColumn);

		/**
		 * Reads the options from the special data file, and possibly the column
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @param string $strControlVarName
		 * @return string
		 */
		abstract public function ConnectorCreateOptions(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn, $strControlVarName);

		/**
		 * Returns code to refresh the control from the saved object.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @param bool $blnInit
		 * @return string
		 */
		abstract public function ConnectorRefresh(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn, $blnInit = false);

		/**
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		abstract public function ConnectorUpdate(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn);

		/**
		 * Generate helper functions for the update process.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @return string
		 */
		abstract public function ConnectorUpdateMethod(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn);

	}