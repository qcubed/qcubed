<?php
	/**
	 * QImageFileAsset is defined in this file
	 * @package Controls
	 * @filesource
	 */

	/**
	 * ImageFileAsset is derived from QFileAsset and is a dedicated control for uploading images (images only).
	 * @package Controls
	 */
	class QImageFileAsset extends QFileAsset {
		/** @var integer the variable is used to contain minimum width of image in pixels */
		protected $intMinWidth;
		/** @var integer the variable is used to contain maxiimum width of image in pixels */
		protected $intMaxWidth;
		/** @var integer the variable is used to contain minimum height of image in pixels */
		protected $intMinHeight;
		/** @var integer the variable is used to contain maxiimum width of image in pixels */
		protected $intMaxHeight;

		/**
		 * Constructor function to create a new QImageFileAsset
		 *
		 * @param mixed $objParentObject Should be a QControl
		 * @param null  $strControlId    The Control ID of the control (optional)
		 */
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->SetFileAssetType(QFileAssetType::Image);
		}

		/**
		 * This function tests whether everything was as needed or not
		 * (uploaded image was within the range specified)
		 * @return bool
		 */
		public function Validate() {
			$blnToReturn = parent::Validate();

			if ($blnToReturn) {
				if ($this->blnRequired) {
					list($width, $height) = getimagesize($this->File);

					if (isset($this->intMinWidth) AND $this->intMinWidth > $width) {
						$blnToReturn = false;
						$this->ValidationError = $this->strName . QApplication::Translate(' is too short the min width is ') . $this->intMinWidth;
					}

					if (isset($this->intMaxWidth) AND $this->intMaxWidth < $width) {
						$blnToReturn = false;
						$this->ValidationError = $this->strName . QApplication::Translate(' is too big the max width is ') . $this->intMaxWidth;
					}

					if (isset($this->intMinHeight) AND $this->intMinHeight > $height) {
						$blnToReturn = false;
						$this->ValidationError = $this->strName . QApplication::Translate(' is too short the min height is ') . $this->intMinHeight;
					}

					if (isset($this->intMaxHeight) AND $this->intMaxHeight < $height) {
						$blnToReturn = false;
						$this->ValidationError = $this->strName . QApplication::Translate(' is too big the max height is ') . $this->intMaxHeight;
					}
				}
			}

			return $blnToReturn;
		}

		/**
		 * PHP magic function to handle object properties
		 *
		 * @param string $strName  Name of the property
		 * @param string $mixValue Value of the property
		 *
		 * @return mixed|null|string|void
		 * @throws QCallerException
		 */
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
						return ($this->intMaxHeight = QType::Cast($mixValue, QType::Integer));
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
