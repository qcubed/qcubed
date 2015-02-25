<?php

abstract class ModelConnectorControlHelper_QControl extends ModelConnectorControlHelper {

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
}