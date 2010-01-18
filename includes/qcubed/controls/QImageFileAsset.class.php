<?php

/**
 * @package Controls
 */
class QImageFileAsset extends QFileAsset {
	
	protected $intMinWidth;
	protected $intMaxWidth;
	protected $intMinHeight;
	protected $intMaxHeight;

	public function __construct($objParentObject, $strControlId = null) {
		parent::__construct($objParentObject, $strControlId);
		$this->SetFileAssetType(QFileAssetType::Image);
	}
	
	public function Validate() {
		$blnToReturn = parent::Validate();

		if ($blnToReturn) {
			if ($this->blnRequired) {
				list($width,$height) = getimagesize($this->File);
					
				if(isset($this->intMinWidth) AND $this->intMinWidth > $width){
					$blnToReturn = false;
					$this->strValidationError = $this->strName . QApplication::Translate(' is too short the min width is ') . $this->intMinWidth;
				}
					
				if(isset($this->intMaxWidth) AND $this->intMaxWidth < $width){
					$blnToReturn = false;
					$this->strValidationError = $this->strName . QApplication::Translate(' is too big the max width is ') . $this->intMaxWidth;
				}
					
				if(isset($this->intMinHeight) AND $this->intMinHeight > $height){
					$blnToReturn = false;
					$this->strValidationError = $this->strName . QApplication::Translate(' is too short the min height is ') . $this->intMinHeight;
				}
					
				if(isset($this->intMaxHeight) AND $this->intMaxHeight < $height){
					$blnToReturn = false;
					$this->strValidationError = $this->strName . QApplication::Translate(' is too big the max height is ') . $this->intMaxHeight;
				}
			}
		}

		return $blnToReturn;
	}

	public function __set($strName, $mixValue) {
		$this->blnModified = true;

		switch ($strName) {

			case 'MinWidth':
				try {
					return ($this->intMinWidth = QType::Cast($mixValue, QType::Integer));
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case 'MaxWidth':
				try {
					return ($this->intMaxWidth = QType::Cast($mixValue, QType::Integer));
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
					
			case 'MinHeight':
				try {
					return ($this->intMinHeight = QType::Cast($mixValue, QType::Integer));
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case 'MaxHeight':
				try {
					return ($this->intMinHeight = QType::Cast($mixValue, QType::Integer));
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
					
			default:
				try {
					return parent::__set($strName, $mixValue);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}
}

?>