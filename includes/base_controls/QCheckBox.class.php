<?php
	/**
	 * This file contains the QCheckBox class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render an HTML Checkbox.
	 *
	 * @package Controls
	 *
	 * @property string $Text is used to display text that is displayed next to the checkbox.  The text is rendered as an html "Label For" the checkbox.
	 * @property string $TextAlign specifies if "Text" should be displayed to the left or to the right of the checkbox.
	 * @property boolean $Checked specifices whether or not hte checkbox is checked
	 * @property boolean $HtmlEntities specifies whether the checkbox text will have to be run through htmlentities or not.
	 */
	class QCheckBox extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		/** @var string Text opposite to the checkbox */
		protected $strText = null;
		/** @var QTextAlign|string the alignment of the string */
		protected $strTextAlign = QTextAlign::Right;
		
		// BEHAVIOR
		/** @var bool Should the htmlentities function be run on the control's text (strText)? */
		protected $blnHtmlEntities = true;

		// MISC
		/** @var bool Determines whether the checkbox is checked? */
		protected $blnChecked = false;

		//////////
		// Methods
		//////////
		/**
		 * Parses the Post Data submitted for the control and sets the values
		 * according to the data submitted
		 */
		public function ParsePostData() {
			if (QApplication::$RequestMode == QRequestMode::Ajax) {
				$this->blnChecked = QType::Cast ($_POST[$this->strControlId], QType::Boolean);
			}
			elseif ($this->objForm->IsCheckableControlRendered($this->strControlId)) {
				if (array_key_exists($this->strControlId, $_POST)) {
					if ($_POST[$this->strControlId])
						$this->blnChecked = true;
					else
						$this->blnChecked = false;
				} else {
					$this->blnChecked = false;
				}
			}
		}

		/**
		 * Returns the HTML code for the control which can be sent to the client
		 * @return string THe HTML for the control
		 */
		protected function GetControlHtml() {
			if (!$this->blnEnabled)
				$strDisabled = 'disabled="disabled" ';
			else
				$strDisabled = "";

			if ($this->intTabIndex)
				$strTabIndex = sprintf('tabindex="%s" ', $this->intTabIndex);
			else
				$strTabIndex = "";

			if ($this->strToolTip)
				$strToolTip = sprintf('title="%s" ', $this->strToolTip);
			else
				$strToolTip = "";

			if ($this->strCssClass)
				$strCssClass = sprintf('class="%s" ', $this->strCssClass);
			else
				$strCssClass = "";

			if ($this->strAccessKey)
				$strAccessKey = sprintf('accesskey="%s" ', $this->strAccessKey);
			else
				$strAccessKey = "";
				
			if ($this->blnChecked)
				$strChecked = 'checked="checked" ';
			else
				$strChecked = "";

			$strStyle = $this->GetStyleAttributes();
			if (strlen($strStyle) > 0)
				$strStyle = sprintf('style="%s" ', $strStyle);

			$strCustomAttributes = $this->GetCustomAttributes();

			if (strlen($this->strText)) {
				$this->blnIsBlockElement = true;
				$strCheckHtml = sprintf('<input type="checkbox" id="%s" name="%s" %s%s%s%s />',
					$this->strControlId,
					$this->strControlId,
					$strDisabled,
					$strChecked,
					$strAccessKey,
					$strTabIndex);

				$strLabelHtml = sprintf ('<label for="%s">%s</label>',
					$this->strControlId,
					($this->blnHtmlEntities) ? QApplication::HtmlEntities($this->strText) : $this->strText);
				if ($this->strTextAlign == QTextAlign::Left) {
					$strCombined = $strLabelHtml .  $strCheckHtml;
				} else {
					$strCombined = $strCheckHtml . $strLabelHtml;
				}

				$strToReturn = sprintf('<div %s%s%s%s%s>%s</div>',
					$strCssClass, $strToolTip, $strStyle, $strCustomAttributes, $strDisabled, $strCombined);

			} else {
				$this->blnIsBlockElement = false;
				$strToReturn = sprintf('<input type="checkbox" id="%s" name="%s" %s%s%s%s%s%s%s%s />',
					$this->strControlId,
					$this->strControlId,
					$strCssClass,
					$strDisabled,
					$strChecked,
					$strAccessKey,
					$strToolTip,
					$strTabIndex,
					$strCustomAttributes,
					$strStyle);
			}

			return $strToReturn;
		}

		/**
		 * Send end script to detect the change on the control before other actions.
		 * @return string
		 */
		public function GetEndScript() {
			$str = parent::GetEndScript();
			$str = sprintf ('$j("#%s").change(qc.formObjChanged);', $this->ControlId) . $str;
			return $str;
		}

		/**
		 * Checks whether the post data submitted for the control is valid or not
		 * Right now it tests whether or not the control was marked as required and then tests whether it
		 * was checked or not
		 * @return bool
		 */
		public function Validate() {
			if ($this->blnRequired) {
				if (!$this->blnChecked) {
					if ($this->strName)
						$this->strValidationError = QApplication::Translate($this->strName) . ' ' . QApplication::Translate('is required');
					else
						$this->strValidationError = QApplication::Translate('Required');
					return false;
				}
			}

			$this->strValidationError = null;
			return true;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP __get magic method implementation
		 * @param string $strName Name of the property
		 *
		 * @return mixed
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "TextAlign": return $this->strTextAlign;

				// BEHAVIOR
				case "HtmlEntities": return $this->blnHtmlEntities;

				// MISC
				case "Checked": return $this->blnChecked;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		/**
		 * PHP __set magic method implementation
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed
		 * @throws QInvalidCastException|QCallerException
		 */
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				// APPEARANCE
				case "Text":
					try {
						$this->strText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "TextAlign":
					try {
						$this->strTextAlign = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "HtmlEntities":
					try {
						$this->blnHtmlEntities = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// MISC
				case "Checked":
					try {
						$this->blnChecked = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
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

		/* === Codegen Helpers, used during the Codegen process only. === */

		/**
		 * /**
		 * Returns the variable name for a control of this type during code generation process
		 *
		 * @param string $strPropName Property name for which the control to be generated is being generated
		 *
		 * @return string
		 */
		public static function Codegen_VarName($strPropName) {
			return 'chk' . $strPropName;
		}

		/**
		 * Generate code that will be inserted into the MetaControl to connect a database object with this control.
		 * This is called during the codegen process.
		 *
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 * @return string
		 */
		public static function Codegen_MetaCreate(QCodeGen $objCodeGen, QTable $objTable, QColumn $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strControlVarName = $objCodeGen->MetaControlVariableName($objColumn);
			$strLabelName = addslashes(QCodeGen::MetaControlControlName($objColumn));

			// Read the control type in case we are generating code for a subclass
			$strControlType = $objCodeGen->MetaControlControlClass($objColumn);

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
			\$this->{$strControlVarName}->Checked = \$this->{$strObjectName}->{$objColumn->PropertyName};

TMPL;

			if ($strMethod = QCodeGen::$PreferredRenderMethod) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->PreferredRenderMethod = '$strMethod';

TMPL;
			}
			$strRet .= static::Codegen_MetaCreateOptions ($objCodeGen, $objTable, $objColumn, $strControlVarName);

			$strRet .= <<<TMPL
			return \$this->{$strControlVarName};
		}


TMPL;

			return $strRet;

		}

		/**
		 * Generate code to reload data from the MetaControl into this control, or load it for the first time
		 *
		 * @param QCodeGen                                       $objCodeGen
		 * @param QTable                                         $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @return string
		 * @return string
		 */
		public static function Codegen_MetaRefresh(QCodeGen $objCodeGen, QTable $objTable, QColumn $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = static::Codegen_VarName($strPropName);

			$strRet = "\t\t\tif (\$this->{$strControlVarName}) \$this->{$strControlVarName}->Checked = \$this->{$strObjectName}->{$strPropName};";
			return $strRet . "\n";
		}


		/**
		 * Generate the code to move data from the control to the database.
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 * @return string
		 */
		public static function Codegen_MetaUpdate(QCodeGen $objCodeGen, QTable $objTable, QColumn $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = static::Codegen_VarName($strPropName);
			$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$objColumn->PropertyName} = \$this->{$strControlVarName}->Checked;

TMPL;
			return $strRet;
		}
	}
?>