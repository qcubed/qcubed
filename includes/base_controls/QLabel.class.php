<?php
	/**
	 * QLabel class is used to create text on the client side.
	 * By default it will not accept raw HTML for text.
	 * Set Htmlentities to false to enable that behavior
	 * @package Controls
	 */
	class QLabel extends QBlockControl {
		///////////////////////////
		// Protected Member Variables
		///////////////////////////
		/** @var string HTML tag to be used when rendering this control */
		protected $strTagName = 'span';
		/** @var bool Should htmlentities be run on the contents of this control? */
		protected $blnHtmlEntities = true;


		/**** Codegen Helpers, used during the Codegen process only. ****/

		/**
		 * @param string $strPropName
		 * @return string
		 */
		public static function Codegen_VarName($strPropName) {
			return 'lbl' . $strPropName;
		}

		/**
		 * Outputs the code at the top of the ModelConnector to declare the variable that will hold the control.
		 * @param QCodeGen $objCodeGen
		 * @param $objColumn
		 * @return string
		 */
		public static function Codegen_ConnectorVariableDeclaration (QCodeGen $objCodeGen, $objColumn) {
			$strPropName = $objCodeGen->ModelConnectorPropertyName($objColumn);
			$strControlVarName = static::Codegen_VarName($strPropName);

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
		 * Codegen the create method and any other support methods to be part of the meta control.
		 *
		 * @param QCodeGen                                       $objCodeGen
		 * @param QTable                                         $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @return string The function definition
		 */
		public static function Codegen_ConnectorCreate(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strLabelName = addslashes(QCodeGen::ModelConnectorControlName($objColumn));
			$strControlType = 'QLabel';

			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
			$strControlVarName = static::Codegen_VarName($strPropName);

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


			$strRet .= static::Codegen_ConnectorCreateOptions ($objCodeGen, $objTable, $objColumn, $strControlVarName);

			$strRet .= static::Codegen_ConnectorRefresh($objCodeGen, $objTable, $objColumn, true);

			$strRet .= <<<TMPL
			return \$this->{$strControlVarName};
		}


TMPL;
			return $strRet;
		}

		/**
		 * Generate code to reload data from the ModelConnector into this control, or load it for the first time
		 *
		 * @param QCodeGen                                       $objCodeGen
		 * @param QTable                                         $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @param boolean                                        $blnInit Generate initialization code instead of reload
		 *
		 * @return string Function definition
		 * @throws Exception
		 */
		public static function Codegen_ConnectorRefresh(QCodeGen $objCodeGen, QTable $objTable, $objColumn, $blnInit = false) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
			$strControlVarName = static::Codegen_VarName($strPropName);

			// Preamble with an if test if not initializing
			if ($objColumn instanceof QColumn){
				if ($objColumn->Identity ||
					$objColumn->Timestamp) {
					$strRet = "\$this->{$strControlVarName}->Text =  \$this->blnEditMode ? \$this->{$strObjectName}->{$strPropName} : QApplication::Translate('N\\A');";
				}
				else if ($objColumn->Reference) {
					if ($objColumn->Reference->IsType) {
						$strRet = "\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$objColumn->PropertyName} ? {$objColumn->Reference->VariableType}::\$NameArray[\$this->{$strObjectName}->{$objColumn->PropertyName}] : null;";
					} else {
						$strRet = "\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$strPropName} ? \$this->{$strObjectName}->{$strPropName}->__toString() : null;";
					}
				}
				else {
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
			}
			elseif ($objColumn instanceof QReverseReference) {
				if ($objColumn->Unique) {
					$strRet = "\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$objColumn->ObjectPropertyName} ? \$this->{$strObjectName}->{$objColumn->ObjectPropertyName}->__toString() : null;";
				}
			}
			elseif ($objColumn instanceof QManyToManyReference) {
				$strRet = "\$this->{$strControlVarName}->Text = implode(\$this->str{$objColumn->ObjectDescription}Glue, \$this->{$strObjectName}->Get{$objColumn->ObjectDescription}Array());";
			}
			else {
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
		 * Return blank string since labels do not send data to the database.
		 *
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		public static function Codegen_ConnectorUpdate(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			return '';
		}
}
?>
