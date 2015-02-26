<?php

	abstract class QControl_CodeGenerator extends AbstractControl_CodeGenerator {

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
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @param string $strControlVarName
		 * @return string
		 */
		public function ConnectorCreateOptions(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn, $strControlVarName) {
			$strRet = '';

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

			if (($options = $objColumn->Options) &&
				isset ($options['Overrides'])
			) {

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
		 * This is called during the codegen process. This is very similar to the QListControl code, but there are
		 * some differences. In particular, this control does not support ManyToMany references.
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
		 * If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		 * used by the ModelConnector designer dialog to display a list of options for the control.
		 * @return QModelConnectorParam[]
		 */
		public function GetModelConnectorParams() {
			return array(
				new QModelConnectorParam ('QControl', 'CssClass', 'Css Class assigned to the control', QType::String),
				new QModelConnectorParam ('QControl', 'AccessKey', 'Access Key to focus control', QType::String),
				new QModelConnectorParam ('QControl', 'CausesValidation', 'How and what to validate. Can also be set to a control.', QModelConnectorParam::SelectionList,
					array(
						null=>'None',
						'QCausesValidation::AllControls'=>'All Controls',
						'QCausesValidation::SiblingsAndChildren'=>'Siblings And Children',
						'QCausesValidation::SiblingsOnly'=>'Siblings Only'
					)
				),
				new QModelConnectorParam ('QControl', 'Enabled', 'Will it start as enabled (default true)?', QType::Boolean),
				new QModelConnectorParam ('QControl', 'Required', 'Will it fail validation if nothing is entered (default depends on data definition, if NULL is allowed.)?', QType::Boolean),
				new QModelConnectorParam ('QControl', 'TabIndex', '', QType::Integer),
				new QModelConnectorParam ('QControl', 'ToolTip', '', QType::String),
				new QModelConnectorParam ('QControl', 'Visible', '', QType::Boolean),
				new QModelConnectorParam ('QControl', 'Height', 'Height in pixels. However, you can specify a different unit (e.g. 3.0 em).', QType::String),
				new QModelConnectorParam ('QControl', 'Width', 'Width in pixels. However, you can specify a different unit (e.g. 3.0 em).', QType::String),
				new QModelConnectorParam ('QControl', 'Instructions', 'Additional help for user.', QType::String),
				new QModelConnectorParam ('QControl', 'Moveable', '', QType::Boolean),
				new QModelConnectorParam ('QControl', 'Resizable', '', QType::Boolean),
				new QModelConnectorParam ('QControl', 'Droppable', '', QType::Boolean),
				new QModelConnectorParam ('QControl', 'UseWrapper', 'Control will be forced to be wrapped with a div', QType::Boolean),
				new QModelConnectorParam ('QControl', 'WrapperCssClass', '', QType::String)
			);

		}


	}