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

		public static function Codegen_VarName($strPropName) {
			return 'lbl' . $strPropName;
		}

		public static function Codegen_MetaVariableDeclaration (QCodeGen $objCodeGen, QColumn $objColumn) {
			$strClassName = $objCodeGen->MetaControlControlClass($objColumn);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = static::Codegen_VarName($strPropName);

			$strRet = <<<TMPL
		/**
		 * @var {$strClassName} {$strControlVarName}
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
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 */
		public static function Codegen_MetaCreate(QCodeGen $objCodeGen, QTable $objTable, $objReference) {
			$strLabelName = addslashes(QCodeGen::MetaControlControlName($objColumn));
			$strControlType = 'QLabel';

			$strPropName = QCodeGen::PropertyNameFromReference($objReference);
			//$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
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


			$strRet .= static::Codegen_MetaRefresh($objCodeGen, $objTable, $objColumn, true);

			$strRet .= static::Codegen_MetaCreateOptions ($objTable, $objColumn, $strControlVarName);

			$strRet .= <<<TMPL
			return \$this->{$strControlVarName};
		}


TMPL;
			return $strRet;
		}

		/**
		 * Generate code to reload data from the MetaControl into this control, or load it for the first time
		 *
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 * @param boolean $blnInit	Generate initialization code instead of reload
		 */
		public static function Codegen_MetaRefresh(QCodeGen $objCodeGen, QTable $objTable, QColumn $objColumn, $blnInit = false) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = static::Codegen_VarName($strPropName);

			$strIfTest = '';
			if (!$blnInit) {
				$strIfTest = "if (\$this->{$strControlVarName}) ";
			}

			if ($objColumn->Identity ||
					$objColumn->Timestamp) {
				$strRet = "\t\t\t{$strIfTest}\$this->{$strControlVarName}->Text =  \$this->blnEditMode ? \$this->{$strObjectName}->{$strPropName} : QApplication::Translate('N\\A');";
			}
			else if ($objColumn->Reference) {
				if ($objColumn->Reference->IsType) {
					$strRet = "\t\t\t{$strIfTest}\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$objColumn->PropertyName} ? {$objColumn->Reference->VariableType}::\$NameArray[\$this->{$strObjectName}->{$objColumn->PropertyName}] : null;";
				} else {
					$strRet = "\t\t\t{$strIfTest}\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$strPropName} ? \$this->{$strObjectName}->{$strPropName}->__toString() : null;";
				}
			}
			else {
				switch ($objColumn->VariableType) {
					case "boolean":
						$strRet = "\t\t\t{$strIfTest}\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$strPropName} ? QApplication::Translate('Yes') : QApplication::Translate('No');";
						break;

					case "QDateTime":
						$strRet = "\t\t\t{$strIfTest}\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$strPropName} ? \$this->{$strObjectName}->{$strPropName}->qFormat(\$this->str{$strPropName}DateTimeFormat) : null;";
						break;

					default:
						$strRet = "\t\t\t{$strIfTest}\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$strPropName};";
				}
			}
			return $strRet . "\n";
		}

		public static function Codegen_MetaUpdate(QCodeGen $objCodeGen, QTable $objTable, QColumn $objColumn) {
			return '';
		}
}
?>
