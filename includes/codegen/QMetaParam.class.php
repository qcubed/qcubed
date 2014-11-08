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
 *
 * @property-read string $Category
 * @property-read string $Name
 */

class QMetaParam extends QBaseClass {
	protected $strCategory;
	protected $strName;
	protected $strDescription;
	protected $controlType;
	protected $options;

	/** @var  QControl caching the created control */
	protected $objControl;

	public function __construct($strCategory, $strName, $strDescription, $controlType, $options = null) {
		$this->strCategory = QApplication::Translate($strCategory);
		$this->strName  = QApplication::Translate($strName);
		$this->strDescription = QApplication::Translate($strDescription);
		$this->controlType = $controlType;

		$this->options = $options;
	}

	/**
	 * Called by the QMetaEditDlg dialog. Creates a control that will allow the user to edit the value
	 * associated with this parameter, and caches that control so that its easy to get to.
	 *
	 * @param QControl|null $objParent
	 * @return null|QControl|QIntegerTextBox|QListBox|QRadioButtonList|QTextBox
	 */
	public function GetControl ($objParent = null) {
		if ($this->objControl) {
			if ($objParent) {
				$this->objControl->SetParentControl($objParent);
			}
			return $this->objControl;
		} elseif ($objParent) {
			$this->objControl = $this->CreateControl($objParent);
			return $this->objControl;
		}
		return null;
	}

	/**
	 * Creates the actual control that will edit the value.
	 *
	 * @param QControl $objParent
	 * @return QIntegerTextBox|QListBox|QRadioButtonList|QTextBox
	 */
	protected function CreateControl(QControl $objParent) {
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

			case 'Category':
				return $this->strCategory;
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

