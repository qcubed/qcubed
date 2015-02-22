<?php
	/**
	 * QSlider Base File
	 * 
	 * The  QSliderBase class defined here provides an interface between the generated
	 * QSliderGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QSlider.class.php file in
	 * the controls folder instead.
	 *
	 */


	/**
	 * 
	 * Implements a JQuery UI Slider
	 * 
	 * A slider can have one or two handles to represent a range of things, similar to a scroll bar.
	 * 
	 * Use the inherited properties to manipulate it. Call Value or Values to get the values.
	 * 
	 * @link http://jqueryui.com/slider/
	 * @package Controls\Base
	 *
	 */
	class QSliderBase extends QSliderGen	{

		/** Constants to use for setting Orientation */
		const Vertical = 'vertical';
		const Horizontal = 'horizontal';

		public function GetEndScript() {
			$strJS = parent::GetEndScript();
			QApplication::ExecuteJsFunction('qcubed.slider', $this->ControlId);
			return $strJS;
		}
		
		public function __set($strName, $mixValue) {

			switch ($strName) {
				case '_Value':	// Internal Only. Used by JS above. Do Not Call.
					try {
						$this->intValue = QType::Cast($mixValue, QType::Integer);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

				case '_Values': // Internal Only. Used by JS above. Do Not Call.
					try {
						$aValues = explode (',', $mixValue);
						$aValues[0] = QType::Cast( $aValues[0], QType::Integer); // important to make sure JS sends values as ints instead of strings
						$aValues[1] = QType::Cast($aValues[1], QType::Integer); // important to make sure JS sends values as ints instead of strings
						$this->arrValues = $aValues;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
														
				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}


/**** Codegen Helpers, used during the Codegen process only. ****/

		/**
		 * @param string $strPropName
		 * @return string
		 */
		public static function Codegen_VarName($strPropName) {
			return 'sld' . $strPropName;
		}

		/**
		 * Generate code that will be inserted into the ModelConnector to connect a database object with this control.
		 * This is called during the codegen process.
		 *
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 * @return string
		 */
		public static function Codegen_ConnectorCreate(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strClassName = $objTable->ClassName;
			$strControlVarName = $objCodeGen->ModelConnectorVariableName($objColumn);
			$strLabelName = addslashes(QCodeGen::ModelConnectorControlName($objColumn));

			// Read the control type in case we are generating code for a subclass of QTextBox
			$strControlType = $objCodeGen->ModelConnectorControlClass($objColumn);

			$strRet = <<<TMPL
		/**
		 * Create and setup a $strControlType $strControlVarName
		 * @param string \$strControlId optional ControlId to use
		 * @return $strControlType
		 */
		public function {$strControlVarName}_Create(\$strControlId = null) {

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
			\$this->{$strControlVarName} = new $strControlType(\$this->objParentObject, \$strControlId);
			\$this->{$strControlVarName}->Name = QApplication::Translate('$strLabelName');

TMPL;

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
		 * Generate code to reload data from the Model into this control.
		 * @param QDatabaseCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 * @param boolean $blnInit Is initializing a new control verses loading a previously created control
		 * @return string
		 */
		public static function Codegen_ConnectorRefresh(QDatabaseCodeGen $objCodeGen, QTable $objTable, $objColumn, $blnInit = false) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = static::Codegen_VarName($strPropName);

			if ($blnInit) {
				$strRet = "\t\t\t\$this->{$strControlVarName}->Value = \$this->{$strObjectName}->{$strPropName};";
			} else {
				$strRet = "\t\t\tif (\$this->{$strControlVarName}) \$this->{$strControlVarName}->Value = \$this->{$strObjectName}->{$strPropName};";
			}
			return $strRet . "\n";
		}


		/**
		 * Return code to update the Model object with the contents of the control.
		 *
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param $objColumn
		 * @return string
		 */
		public static function Codegen_ConnectorUpdate(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = static::Codegen_VarName($strPropName);
			$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$strPropName} = \$this->{$strControlVarName}->Value;

TMPL;
			return $strRet;
		}


	}

?>
