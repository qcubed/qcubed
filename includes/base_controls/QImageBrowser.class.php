<?php
/**
 * This file contains the QImageBrowser class and supporting classes.
 *
 * @package Controls
 */

/**
 * Control for a simple image browser.
 *
 * The browser can have one or two navigation bars (with 4 buttons allowing to go back and forward,
 * and to the first and last images). It can also have a caption textbox, which can be editable.
 * A thumbnails panel is also provided with the browser. The layout is fully controlled by css.
 *
 * It is designed to allow almost every aspect of the browser to be customized. However typical defaults
 * are provided so in a simple case it can be used "out-of-the-box" (see the example).
 *
 * QImageBrowserBase is the abstract class that you may want to subclass if you need to customize
 * some functionality (such us the source of the images or how to load/save the captions). See the comments in
 * this class for details about how it can be customized. A concrete implementation called QImageBrowser is also
 * provided, which loads the images from a directory.
 *
 * QImageBrowserNav represents the simple 4 button navigation panel, it has getters and setters for all the
 * buttons, so you can replace the default buttons by anything you'd like (such as image buttons).
 *
 * QImageBrowserThumbnails represents the tumbnails navigation panel.
 *
 * @package Controls
 * @property QButton FirstButton the button to go to the first image
 * @property QButton PrevButton the button to go to the previous image
 * @property QButton NextButton the button to go to the next image
 * @property QButton LastButton the button to go to the last image
 *
 */
	class QImageBrowserNav extends QPanel {
		protected $btnFirst;
		protected $btnPrev;
		protected $btnNext;
		protected $btnLast;
		
		public function __construct($objParentObject, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$this->AutoRenderChildren = true;
			
			$this->btnFirst = new QButton($this);
			$this->btnFirst->Text = QApplication::Translate('First');
			$this->btnFirst->Enabled = false;
			$this->btnFirst->CssClass = 'button ib_nav_button ib_nav_button_first';

			$this->btnPrev = new QButton($this);
			$this->btnPrev->Text = QApplication::Translate('Previous');
			$this->btnPrev->Enabled = false;
			$this->btnPrev->CssClass = 'button ib_nav_button ib_nav_button_prev';

			$this->btnNext = new QButton($this);
			$this->btnNext->Text = QApplication::Translate('Next');
			$this->btnNext->Enabled = false;
			$this->btnNext->CssClass = 'button ib_nav_button ib_nav_button_next';

			$this->btnLast = new QButton($this);
			$this->btnLast->Text = QApplication::Translate('Last');
			$this->btnLast->Enabled = false;
			$this->btnLast->CssClass = 'button ib_nav_button ib_nav_button_last';

			$this->setButtonActions();
		}
		
		protected function setButtonActions(array $arrButtons = null) {
			// get the QImageBrowser control
			$objImageBrowser = $this->ParentControl;
			while ( !($objImageBrowser instanceof QImageBrowserBase) ) {
				$objImageBrowser = $objImageBrowser->ParentControl;
				if (is_null($objImageBrowser) || $objImageBrowser instanceof QForm) {
					throw new QCallerException("QImageBrowserNav must be inside a QImageBrowser");
				}
			}
			if (!$arrButtons) {
				$arrButtons = array(
					"btnFirst_Click" 	=> $this->btnFirst, 
					"btnPrev_Click" 	=> $this->btnPrev, 
					"btnNext_Click" 	=> $this->btnNext, 
					"btnLast_Click" 	=> $this->btnLast);
			}
			
			foreach ($arrButtons as $strActionCalback => $objButton) {
				$objButton->RemoveAllActions(QClickEvent::EventName);
				$objButton->AddAction(new QClickEvent(), new QAjaxControlAction($objImageBrowser, $strActionCalback));
			}
		}
		
		public function BackButtonsEnabled($blnEnable) {
			$this->btnFirst->Enabled = $blnEnable;
			$this->btnPrev->Enabled = $blnEnable;
		}
		
		public function ForwardButtonsEnabled($blnEnable) {
			$this->btnNext->Enabled = $blnEnable;
			$this->btnLast->Enabled = $blnEnable;
		}

		public function __get($strName) {
			switch ($strName) {
				case "FirstButton":	return $this->btnFirst;
				case "PrevButton":	return $this->btnPrev;
				case "NextButton":	return $this->btnNext;
				case "LastButton":	return $this->btnLast;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				case "FirstButton":
					try {
						$this->RemoveChildControl($this->btnFirst->ControlId, true);
						$this->btnFirst = QType::Cast($mixValue, 'QControl');
						$this->setButtonActions(array("btnFirst_Click" => $this->btnFirst));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "PrevButton":
					try {
						$this->RemoveChildControl($this->btnPrev->ControlId, true);
						$this->btnPrev = QType::Cast($mixValue, 'QControl');
						$this->setButtonActions(array("btnPrev_Click" => $this->btnPrev));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "NextButton":
					try {
						$this->RemoveChildControl($this->btnNext->ControlId, true);
						$this->btnNext = QType::Cast($mixValue, 'QControl');
						$this->setButtonActions(array("btnNext_Click" => $this->btnNext));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "LastButton":
					try {
						$this->RemoveChildControl($this->btnLast->ControlId, true);
						$this->btnLast = QType::Cast($mixValue, 'QControl');
						$this->setButtonActions(array("btnLast_Click" => $this->btnLast));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

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
	}
	
	/**
	 * @package Controls
	 */
	class QImageBrowserThumbnails extends QPanel {
		public function __construct($objParentObject, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$this->AutoRenderChildren = true;
		}

		public function reload() {
			$this->RemoveChildControls(true);
			$img = null;
			// get the QImageBrowser control
			$objImageBrowser = $this->ParentControl;
			while ( !($objImageBrowser instanceof QImageBrowserBase) ) {
				$objImageBrowser = $objImageBrowser->ParentControl;
				if (is_null($objImageBrowser) || $objImageBrowser instanceof QForm) {
					throw new QCallerException("QImageBrowserThumbnails must be inside a QImageBrowser");
				}
			}
			$iEnd = $objImageBrowser->ImageCount();
			for ($i = 0; $i < $iEnd; ++$i) {
				$strImagePath = $objImageBrowser->ThumbnailImagePath($i);
				$img = new QImageControl($this);
				$img->CssClass = 'ib_thm_image';
				$img->ImagePath = $strImagePath;
				$img->AlternateText = $strImagePath;
				$img->ActionParameter = $i;
			
				// And finally, let's specify a CacheFolder so that the images are cached
				// Notice that this CacheFolder path is a complete web-accessible relative-to-docroot path
				$img->CacheFolder = __IMAGE_CACHE_ASSETS__;
				
				$img->AddAction(new QClickEvent(), new QAjaxControlAction($objImageBrowser, "imgThm_Click"));
			}
			if ($img) {
				$img->CssClass = 'ib_thm_image ib_thm_image_last';
				$this->Text = '';
			} else {
				$this->Text = QApplication::Translate('No thumbnails');
			}
		}
	}

/**
 *
 * @property-read QImageControl MainImage the main image control
 * @property QTextBox Caption the caption control
 * @property QButton SaveButton the save button control
 * @property QImageBrowserNav Navigation1 the first navigation panel
 * @property QImageBrowserNav Navigation2 the second navigation panel
 * @property QImageBrowserThumbnails Thumbnails the thumbnails panel
 */
	abstract class QImageBrowserBase extends QPanel {
		protected $intCurrentImage;
		protected $imgMainImage;
		protected $txtCaption;
		protected $btnSave;
		protected $ibnNavigation1;
		protected $ibnNavigation2;
		protected $ibtThumbnails;
		
		/**
		 * @param $objParentObject
		 * @param bool $blnReadOnlyCaption if true (default) don't allow captions to be edited (and don't show the save button)
		 * @param bool $blnTwoNavBars if true (default false),will show two navigation bars (which can layout with template/css)
		 * @param bool $blnThumbnails if true (default), will show two thumbnails panel (which you can layout with template/css)
		 * @param null $strControlId
		 */
		public function __construct($objParentObject, $blnReadOnlyCaption = true, $blnTwoNavBars = false, $blnThumbnails = true, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$this->intCurrentImage = null;
			
			// main image
			$this->imgMainImage = new QImageControl($this);
			$this->imgMainImage->CssClass = 'ib_main_image';
			$this->imgMainImage->ImagePath = $this->invalidImagePath();
			
			// And finally, let's specify a CacheFolder so that the images are cached
			// Notice that this CacheFolder path is a complete web-accessible relative-to-docroot path
			$this->imgMainImage->CacheFolder = __IMAGE_CACHE_ASSETS__;
			
			// caption
			$this->txtCaption = new QTextBox($this);
			$this->txtCaption->Name = 'Caption';
			$this->txtCaption->TextMode = QTextMode::MultiLine;
			$this->txtCaption->Rows = 2;
			$this->txtCaption->Enabled = false;
			if ($blnReadOnlyCaption) {
				$this->txtCaption->CssClass = 'textbox ib_caption ib_caption_readonly';
				$this->txtCaption->ReadOnly = true;
			} else {
				$this->txtCaption->CssClass = 'textbox ib_caption';
				$this->txtCaption->AddAction(new QChangeEvent(), new QAjaxControlAction($this, "txtCaption_Change"));

				$this->btnSave = new QButton($this);
				$this->btnSave->Text = QApplication::Translate('Save');
				$this->btnSave->Enabled = false;
				$this->btnSave->AddAction(new QClickEvent(), new QAjaxControlAction($this, "btnSave_Click"));
			}
			
			// nav bars
			$this->ibnNavigation1 = new QImageBrowserNav($this);
			$this->ibnNavigation1->CssClass = 'ib_nav ib_nav1';
			if ($blnTwoNavBars) {
				$this->ibnNavigation2 = new QImageBrowserNav($this);
				$this->ibnNavigation2->CssClass = 'ib_nav ib_nav2';
			}
			
			// thumbnails
			if ($blnThumbnails) {
				$this->ibtThumbnails = new QImageBrowserThumbnails($this);
				$this->ibtThumbnails->CssClass = 'ib_thm';
			}
		}
		
		protected function reload() {
			$this->ibtThumbnails->reload();
			$this->intCurrentImage = 0;
			$this->setMainImage($this->intCurrentImage);
		}

		protected function setMainImage($intIdx) {
			$intCount = $this->ImageCount();
			$blnBackButtonsEnabled = $intCount > 1 && $intIdx > 0;
			$blnForwardButtonsEnabled = $intCount > 1 && $intIdx+1 < $intCount;
			$this->ibnNavigation1->BackButtonsEnabled($blnBackButtonsEnabled);
			if ($this->ibnNavigation2) {
				$this->ibnNavigation2->BackButtonsEnabled($blnBackButtonsEnabled);
			}
			$this->ibnNavigation1->ForwardButtonsEnabled($blnForwardButtonsEnabled);
			if ($this->ibnNavigation2) {
				$this->ibnNavigation2->ForwardButtonsEnabled($blnForwardButtonsEnabled);
			}
			if ($this->btnSave) {
				$this->btnSave->Enabled = false;
			}
			if ($intIdx < 0 || $intIdx >= $intCount) {
				$this->intCurrentImage = null;
				$this->imgMainImage->ImagePath = $this->invalidImagePath();
				$this->txtCaption->Enabled = false;
				$this->txtCaption->Text = '';
				return;
			}
			$this->txtCaption->Enabled = true;
			$strImagePath = $this->ImagePath($intIdx);
			$this->imgMainImage->ImagePath = $strImagePath;
			if ($this->ibtThumbnails) {
				foreach ($this->ibtThumbnails->GetChildControls() as $ctrl) {
					if ($ctrl instanceof QImageControl) {
						if ($ctrl->ImagePath == $strImagePath) {
							$ctrl->AddCssClass($this->selectThumbnailCssClass());
						} else {
							$ctrl->RemoveCssClass($this->selectThumbnailCssClass());
						}
					}
				}
			}
			$this->intCurrentImage = $intIdx;
			$this->txtCaption->Text = $this->loadCaption($intIdx);
		}
		
		public function btnFirst_Click($strFormId, $strControlId, $strParameter) {
			$this->setMainImage(0);
		}
		
		public function btnNext_Click($strFormId, $strControlId, $strParameter) {
			if (!is_null($this->intCurrentImage)) {
				$this->setMainImage($this->intCurrentImage + 1);
			}
		}
		
		public function btnPrev_Click($strFormId, $strControlId, $strParameter) {
			if (!is_null($this->intCurrentImage)) {
				$this->setMainImage($this->intCurrentImage - 1);
			}
		}
		
		public function btnLast_Click($strFormId, $strControlId, $strParameter) {
			$this->setMainImage($this->ImageCount()-1);
		}
		
		public function imgThm_Click($strFormId, $strControlId, $strParameter) {
			$this->setMainImage($strParameter);
		}
		
		public function btnSave_Click($strFormId, $strControlId, $strParameter) {
			$this->saveCaption($this->intCurrentImage, $this->txtCaption->Text);
			if ($this->btnSave) {
				$this->btnSave->Enabled = false;
			}
		}
		
		public function txtCaption_Change($strFormId, $strControlId, $strParameter) {
			if ($this->btnSave) {
				$this->btnSave->Enabled = !is_null($this->intCurrentImage);
			}
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Methods that need to be implemented or customized
		/////////////////////////////////////////////////////////////////////////////////

		/**
		 * Return the total number of the images in this image browser
		 *
		 * @abstract
		 */
		abstract public function ImageCount();

		/**
		 * Return the absolute path of the corresponding image.
		 *
		 * @abstract
		 * @param $intIdx index of the image (between 0 and ImageCount()-1)
		 */
		abstract public function ImagePath($intIdx);	

		/**
		 * Return the absolute path of the corresponding thumbnail image.
		 * This could be the same as the image, and the browser will scale it to the size of the thumbnail.
		 *
		 * @abstract
		 * @param $intIdx index of the image (between 0 and ImageCount()-1)
		 */
		abstract public function ThumbnailImagePath($intIdx);

		/**
		 * Return the corresponding image caption
		 *
		 * @abstract
		 * @param $intIdx index of the image (between 0 and ImageCount()-1)
		 */
		abstract protected function loadCaption($intIdx);
		
		// Saves the caption for an image.
		/**
		 * @abstract
		 * @param $intIdx index of the image (between 0 and ImageCount()-1)
		 * @param $strCaption caption to save
		 */
		abstract protected function saveCaption($intIdx, $strCaption);
		

		/**
		 * Return the added CSS class for the selected thumbnail image.
		 * Overwrite this method if you'd like a different CSS class.
		 *
		 * @return string
		 */
		protected function selectThumbnailCssClass() {
			return 'ib_thm_selected';
		}

		/**
		 * The absolute path of an image that indicates that the current image path is invalid.
		 * This is needed since we cannot render the QImageControl without a valid ImagePath.
		 *
		 * @return string
		 */
		protected function invalidImagePath() {
			return __DOCROOT__ . __IMAGE_ASSETS__ . '/file_asset_blank.png';
		}
		
		public function __get($strName) {
			switch ($strName) {
				case "MainImage": return $this->imgMainImage;
				case "Caption": return $this->txtCaption;
				case "SaveButton": return $this->btnSave;
				case "Navigation1": return $this->ibnNavigation1;
				case "Navigation2": return $this->ibnNavigation2;
				case "Thumbnails": return $this->ibtThumbnails;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				case "Navigation1":
					try {
						if ($this->ibnNavigation1)
							$this->RemoveChildControl($this->ibnNavigation1->ControlId, true);
						$this->ibnNavigation1 = QType::Cast($mixValue, 'QImageBrowserNav');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Navigation2":
					try {
						if ($this->ibnNavigation2)
							$this->RemoveChildControl($this->ibnNavigation2->ControlId, true);
						$this->ibnNavigation2 = QType::Cast($mixValue, 'QImageBrowserNav');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Thumbnails":
					try {
						if ($this->ibtThumbnails)
							$this->RemoveChildControl($this->ibtThumbnails->ControlId, true);
						$this->ibtThumbnails = QType::Cast($mixValue, 'QImageBrowserThumbnails');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Caption":
					try {
						if ($this->txtCaption)
							$this->RemoveChildControl($this->txtCaption->ControlId, true);
						$this->txtCaption = QType::Cast($mixValue, 'QControl');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "SaveButton":
					try {
						if ($this->btnSave) {
							$this->btnSave->RemoveAllActions(QClickEvent::EventName);
							$this->RemoveChildControl($this->btnSave->ControlId, true);
						}
						$this->btnSave = QType::Cast($mixValue, 'QControl');
						$this->btnSave->AddAction(new QClickEvent(), new QAjaxControlAction($this, "btnSave_Click"));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

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
	}
	
	/**
	 * A simple implementation of the QImageBrowserBase, which takes the images from a provided
	 * array of image paths (must be absolute paths). It has a method that you can use to load
	 * all the images from a directory. By default it aassumes that the captions are saved in the same
	 * directory in files with an additional ".txt" extension.
	 *
	 * @package Controls
	 * @property array ImagePaths the array of absolute paths for the images
	 *
	 */
	class QImageBrowser extends QImageBrowserBase {
		protected $arrImagePaths;

		public function LoadImagesFromDirectory($strDir, $strPattern) {
			if (!is_dir($strDir)) {
				throw new QCallerException("$strDir is not a directory"); 
			}

			$dh = opendir($strDir);
			if ($dh === false) {
				throw new QCallerException("Could not open directory $strDir");
			}
			$this->arrImagePaths = array();
			while ($strFile = readdir($dh)) {
				if ("." == $strFile || ".." == $strFile) {
					continue;
				}
				if (preg_match($strPattern, $strFile) > 0) {
					$this->arrImagePaths[] = $strDir.'/'.$strFile;
				}
			}
			closedir($dh);
			$this->reload();
		}
		
		public function ImageCount() {
			if (!$this->arrImagePaths) return 0;
			return count($this->arrImagePaths);
		}

		public function ImagePath($intIdx) {
			return $this->arrImagePaths[$intIdx];
		}

		public function ThumbnailImagePath($intIdx) {
			return $this->ImagePath($intIdx);
		}

		protected function captionFileName($intIdx) {
			$strImagePath = $this->ImagePath($intIdx);
			return $strImagePath.'.txt';
		}
		
		protected function loadCaption($intIdx) {
			$strCaptionFile = $this->captionFileName($intIdx);
			if (!file_exists($strCaptionFile)) {
				//return $strCaptionFile;
				return '';
			}
			if (false === ($strCaption = file_get_contents($strCaptionFile))) {
				//return $strCaptionFile;
				return '';
			}
			return $strCaption;
		}
		
		protected function saveCaption($intIdx, $strCaption) {
			$strCaptionFile = $this->captionFileName($intIdx);
			file_put_contents($strCaptionFile, $strCaption, LOCK_EX);
		}
		
		public function __get($strName) {
			switch ($strName) {
				case "ImagePaths": return $this->arrImagePaths;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				case "ImagePaths":
					try {
						$this->arrImagePaths = QType::Cast($mixValue, QType::ArrayType);
						$this->reload();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

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
	}