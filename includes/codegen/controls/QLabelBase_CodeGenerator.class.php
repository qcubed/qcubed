<?php

	class QLabelBase_CodeGenerator extends QControl_CodeGenerator {
		private static $instance = null;

		public function __construct($strControlClassName = 'QLabel') {
			parent::__construct($strControlClassName);
		}

		/**
		 * @return QLabel_CodeGenerator
		 */
		public static function Instance() {
			if (!self::$instance) {
				self::$instance = new QLabel_CodeGenerator();
			}
			return self::$instance;
		}

		/**
		 * @param string $strPropName
		 * @return string
		 */
		public function VarName($strPropName) {
			return 'lbl' . $strPropName;
		}

		/**
		 * Generate code that will be inserted into the ModelConnector to connect a database object with this control.
		 * This is called during the codegen process. This is very similar to the QListControl code, but there are
		 * some differences. In particular, this control does not support ManyToMany references.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @throws Exception
		 * @return string
		 */
		public function ConnectorCreate(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			$strLabelName = addslashes(QCodeGen::ModelConnectorControlName($objColumn));
			$strControlType = 'QLabel';

			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
			$strControlVarName = $this->VarName($strPropName);

			$strDateTimeExtra = '';
			$strDateTimeParamExtra = '';
			if ($objColumn->VariableType == 'QDateTime') {
				$strDateTimeExtra = ', $strDateTimeFormat = null';
				$strDateTimeParamExtra = "\n\t\t * @param string \$strDateTimeFormat";
			}

			$strRet = <<<TMPL
		/**
		 * Create and setup $strControlType $strControlVarName
		 *
		 * @param string \$strControlId optional ControlId to use{$strDateTimeParamExtra}
		 * @return $strControlType
		 */
		public function {$strControlVarName}_Create(\$strControlId = null{$strDateTimeExtra}) {

TMPL;
			$strControlIdOverride = $objCodeGen->GenerateControlId($objTable, $objColumn);

			if ($strControlIdOverride) {
				$strRet .= <<<TMPL
			if (!\$strControlId) {
				\$strControlId = '$strControlIdOverride';
			}

TMPL;
			}
			$strRet .= <<<TMPL
			\$this->{$strControlVarName} = new {$strControlType}(\$this->objParentObject, \$strControlId);
			\$this->{$strControlVarName}->Name = QApplication::Translate('{$strLabelName}');

TMPL;
			if ($objColumn->VariableType == 'QDateTime') {
				$strRet .= <<<TMPL
			\$this->str{$strPropName}DateTimeFormat = \$strDateTimeFormat;

TMPL;
			}
			if ($strMethod = QCodeGen::$PreferredRenderMethod) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->PreferredRenderMethod = '$strMethod';

TMPL;
			}


			$strRet .= $this->ConnectorCreateOptions($objCodeGen, $objTable, $objColumn, $strControlVarName);

			$strRet .= $this->ConnectorRefresh($objCodeGen, $objTable, $objColumn, true);

			$strRet .= <<<TMPL
			return \$this->{$strControlVarName};
		}


TMPL;
			return $strRet;
		}

		/**
		 * @param QCodeGenBase $objCodeGen
		 * @param QColumn|QReverseReference| QManyToManyReference $objColumn
		 * @throws Exception
		 * @return string
		 */
		public function ConnectorVariableDeclaration(QCodeGenBase $objCodeGen, $objColumn) {
			$strPropName = $objCodeGen->ModelConnectorPropertyName($objColumn);
			$strControlVarName = $this->VarName($strPropName);

			$strRet = <<<TMPL
		/**
		 * @var QLabel {$strControlVarName}
		 * @access protected
		 */
		protected \${$strControlVarName};


TMPL;

			if ($objColumn->VariableType == 'QDateTime') {
				$strRet .= <<<TMPL
		/**
		* @var str{$strPropName}DateTimeFormat
		* @access protected
		*/
		protected \$str{$strPropName}DateTimeFormat;

TMPL;
			}
			return $strRet;
		}

		/**
		 * Returns code to refresh the control from the saved object.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference $objColumn
		 * @param bool $blnInit
		 * @throws Exception
		 * @return string
		 */
		public function ConnectorRefresh(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn, $blnInit = false) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
			$strControlVarName = $this->VarName($strPropName);

			// Preamble with an if test if not initializing
			$strRet = '';
			if ($objColumn instanceof QColumn) {
				if ($objColumn->Identity ||
					$objColumn->Timestamp
				) {
					$strRet = "\$this->{$strControlVarName}->Text =  \$this->blnEditMode ? \$this->{$strObjectName}->{$strPropName} : QApplication::Translate('N\\A');";
				} else if ($objColumn->Reference) {
					if ($objColumn->Reference->IsType) {
						$strRet = "\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$objColumn->PropertyName} ? {$objColumn->Reference->VariableType}::\$NameArray[\$this->{$strObjectName}->{$objColumn->PropertyName}] : null;";
					} else {
						$strRet = "\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$strPropName} ? \$this->{$strObjectName}->{$strPropName}->__toString() : null;";
					}
				} else {
					switch ($objColumn->VariableType) {
						case "boolean":
							$strRet = "\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$strPropName} ? QApplication::Translate('Yes') : QApplication::Translate('No');";
							break;

						case "QDateTime":
							$strRet = "\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$strPropName} ? \$this->{$strObjectName}->{$strPropName}->qFormat(\$this->str{$strPropName}DateTimeFormat) : null;";
							break;

						default:
							$strRet = "\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$strPropName};";
					}
				}
			} elseif ($objColumn instanceof QReverseReference) {
				if ($objColumn->Unique) {
					$strRet = "\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$objColumn->ObjectPropertyName} ? \$this->{$strObjectName}->{$objColumn->ObjectPropertyName}->__toString() : null;";
				}
			} elseif ($objColumn instanceof QManyToManyReference) {
				$strRet = "\$this->{$strControlVarName}->Text = implode(\$this->str{$objColumn->ObjectDescription}Glue, \$this->{$strObjectName}->Get{$objColumn->ObjectDescription}Array());";
			} else {
				throw new Exception ('Unknown column type.');
			}

			if (!$blnInit) {
				$strRet = "\t\t\tif (\$this->{$strControlVarName}) " . $strRet;
			} else {
				$strRet = "\t\t\t" . $strRet;
			}

			return $strRet . "\n";
		}

		/**
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference $objColumn
		 * @return string
		 */
		public function ConnectorUpdate(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			return '';
		}
	}