<?php

/**
 * Class QMetaParam
 *
 * Encapsulates a description of an editable meta parameter.
 *
 * For example, this class would be used to tell the QMetaEditDlg that you can set the
 * name of a control using a text box, or the visibility state of a control using boolean selector.
 *
 * You can currently specify a boolean value, a text value, an integer value, or a list of options.
 */

class QMetaParam extends QBaseClass {
	protected $strName;
	protected $strDescription;
	protected $controlType;
	protected $options;
	protected $blnQuoteValue;

	const Quote = true;

	protected $objControl;

	public function __construct($strName, $strDescription, $controlType, $options = null, $blnQuoteValue = false) {
		$this->strName  = $strName;
		$this->strDescription = $strDescription;
		$this->controlType = $controlType;
		$this->options = $options;
		$this->blnQuoteValue = $blnQuoteValue;
	}

	public function GetControl ($objParent) {
		if ($this->objControl) {
			return $this->objControl;
		} else {
			$this->objControl = $this->CreateControl($objParent);
			return $this->objControl;
		}
	}

	protected function CreateControl($objParent) {
		switch ($this->controlType) {
			case QType::Boolean:
				$ctl = new QRadioButtonList($objParent);
				$ctl->AddItem('True', true);
				$ctl->AddItem('False', false);
				$ctl->AddItem('None', null);
				$ctl->RepeatColumns = 3;
				break;

			case QType::String:
				$ctl = new QTextBox($objParent);
				break;

			case QType::Integer:
				$ctl = new QIntegerTextBox($objParent);
				break;

			case QType::ArrayType:
				$ctl = new QListBox($objParent);
				foreach ($this->options as $key=>$val) {
					$ctl->AddItem ($val, $key === '' ? null : $key); // allow null item keys
				}
				break;
		}

		$ctl->Name = $this->strName;
		$ctl->ToolTip = $this->strDescription;
		return $ctl;

	}

	public function __get ($strName) {
		switch ($strName) {
			case 'Name':
				return $this->strName;
				break;

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

}

