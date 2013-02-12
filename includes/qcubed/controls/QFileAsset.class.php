<?php
	/**
	 * QFileAsset class is defined here
	 * @package Controls
	 * @filesource
	 */

	/**
	 * QFileAsset class is control you can use to upload files to the server
	 *
	 * It extends the QFileAssetBase class. When writing your code, use this class instead of QFileAssetBase.
	 * This class is intended to be modified by the developer to add functions and alter the functions
	 * already present in QFileAssetBase class.
	 * @package Controls
	 */
	class QFileAsset extends QFileAssetBase {
		/** @var string File Path for Temporary Upload, default to '/tmp' directory inside includes */
		protected $strTemporaryUploadPath = '/tmp';

		/**
		 * The QFileAsset constructor
		 *
		 * @param mixed  $objParentObject The parent control
		 * @param string $strControlId The ID to be assigned to this control
		 *
		 * @return \QFileAsset
		 */
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			// Setup Default Properties
			$this->strTemplate = __DOCROOT__ . __PHP_ASSETS__ . '/QFileAsset.tpl.php';
			$this->dlgFileAsset->Width = '300';
			$this->UploadText = QApplication::Translate('Upload');
			$this->CancelText = QApplication::Translate('Cancel');
			$this->btnUpload->Text = '<img src="' . __VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__ . '/add.png" alt="' . QApplication::Translate('Upload') . '" border="0"/> ' . QApplication::Translate('Upload');
			$this->btnDelete->Text = '<img src="' . __VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__ . '/delete.png" alt="' . QApplication::Translate('Delete') . '" border="0"/> ' . QApplication::Translate('Delete');
			$this->DialogBoxHtml = '<p>' . QApplication::Translate('Please select a file to upload.') . '</p>';
		}
	}

?>