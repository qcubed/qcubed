<?php

	abstract class QControlBase_CodeGenerator extends AbstractControl_CodeGenerator {

		/**
		 * @param QCodeGenBase $objCodeGen
		 * @param QColumn|QReverseReference| QManyToManyReference $objColumn
		 * @return string
		 */
		public function ConnectorVariableDeclaration(QCodeGenBase $objCodeGen, $objColumn) {
			$strClassName = $this->GetControlClass();
			$strControlVarName = $objCodeGen->ModelConnectorVariableName($objColumn);

			$strRet = <<<TMPL
		/**
		 * @var {$strClassName} {$strControlVarName}

		 * @access protected
		 */
		protected \${$strControlVarName};


TMPL;
			return $strRet;
		}

		/**
		 * Reads the options from the special data file, and possibly the column
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param null|QColumn|QReverseReference|QManyToManyReference $objColumn	A null column means we want the table options
		 * @param string $strControlVarName
		 * @return string
		 */
		public function ConnectorCreateOptions(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn, $strControlVarName) {
			$strRet = '';

			if (!$objColumn) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->LinkedNode = QQN::{$objTable->ClassName}();

TMPL;
				$options = $objTable->Options;
			}
			else {
				if ($objColumn instanceof QColumn) {
					$strPropName = ($objColumn->Reference && !$objColumn->Reference->IsType) ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
					$strClass = $objTable->ClassName;
				} elseif ($objColumn instanceof QManyToManyReference ||
					$objColumn instanceof QReverseReference
				) {
					$strPropName = $objColumn->ObjectDescription;
					$strClass = $objTable->ClassName;
				}

				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->LinkedNode = QQN::{$strClass}()->{$strPropName};

TMPL;
				$options = $objColumn->Options;
			}
			if (isset ($options['Overrides'])) {

				foreach ($options['Overrides'] as $name => $val) {
					if (is_numeric($val)) {
						// looks like a number
						$strVal = $val;
					} elseif (is_string($val)) {
						if (strpos($val, '::') !== false &&
							strpos($val, ' ') === false
						) {
							// looks like a constant
							$strVal = $val;
						} else {
							$strVal = var_export($val, true);
							$strVal = 'QApplication::Translate(' . $strVal . ')';
						}
					} else {
						$strVal = var_export($val, true);
					}
					$strRet .= <<<TMPL
			\$this->{$strControlVarName}->{$name} = {$strVal};

TMPL;
				}
			}
			return $strRet;
		}

		/**
		 * @param string $strPropName
		 * @throws QCallerException
		 * @return string
		 */
		public function VarName($strPropName) {
			throw new QCallerException('VarName() method not implemented');
		}

		/**
		 * Generate code that will be inserted into the ModelConnector to connect a database object with this control.
		 * This is called during the codegen process. 
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @throws QCallerException
		 * @return string
		 */
		public function ConnectorCreate(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			throw new QCallerException('ConnectorCreate() method not implemented');
		}

		/**
		 * Returns code to refresh the control from the saved object.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 * @param bool $blnInit
		 * @throws QCallerException
		 * @return string
		 */
		public function ConnectorRefresh(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn, $blnInit = false) {
			throw new QCallerException('ConnectorRefresh() method not implemented');
		}

		/**
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference $objColumn
		 * @throws QCallerException
		 * @return string
		 */
		public function ConnectorUpdate(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			throw new QCallerException('ConnectorUpdate() method not implemented');
		}

		/**
		 * Generate helper functions for the update process.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @throws QCallerException
		 * @return string
		 */
		public function ConnectorUpdateMethod(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			throw new QCallerException('ConnectorUpdateMethod() method not implemented');
		}

		/**
		 * Generate extra set options for the connector.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @throws QCallerException
		 * @return string
		 */
		public function ConnectorSet(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			return "";
		}

		/**
		 * Generate extra set options for the connector.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @throws QCallerException
		 * @return string
		 */
		public function ConnectorGet(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			return "";
		}

		/**
		 * Generate extra property comments for the connector.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @throws QCallerException
		 * @return string
		 */
		public function ConnectorPropertyComments(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			return "";
		}


	}