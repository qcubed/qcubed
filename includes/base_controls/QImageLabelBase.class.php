<?php
	/**
	 * This file contains the QImageLabelBase class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render an Image/Bitmapped version of any Text string
	 *
	 * @package Controls
	 *
	 * @property string $Text is the Text that you want rendered as an image
	 * @property integer $XCoordinate
	 * @property integer $YCoordinate
	 * @property boolean $BackgroundTransparent
	 * @property string $ImageType
	 * @property integer $Quality
	 * @property boolean $SmoothFont
	 * @property string $HorizontalAlign
	 * @property string $VerticalAlign
	 * @property integer $PaddingWidth
	 * @property integer $PaddingHeight
	 * @property integer $Space
	 * @property integer $Tightness
	 * @property integer $Angle
	 * @property string $CacheFolder
	 * @property-read string $CachedImageFilePath
	 * @property-write string $ForeColor
	 * @property-write string $BackColor
	 * @property-write integer $FontSize
	 * @property-write integer $Width
	 * @property-write integer $Height
	 * @property-write string $FontNames
	 */
	abstract class QImageLabelBase extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		protected $strForeColor = '000000';
		protected $strBackColor = 'ffffff';
		protected $strFontSize = 12;
		protected $strFontNames = null;

		protected $strText = null;
		protected $intXCoordinate = 0;
		protected $intYCoordinate = 0;		
		protected $blnBackgroundTransparent = false;

		// BEHAVIOR
		protected $strImageType = QImageType::Png;
		protected $intQuality = 100;
		protected $blnSmoothFont = true;

		// LAYOUT
		protected $strHorizontalAlign = QHorizontalAlign::Left;
		protected $strVerticalAlign = QVerticalAlign::Top;
		protected $intPaddingWidth = 0;
		protected $intPaddingHeight = 0;
		protected $intSpace = 0;
		protected $intTightness = 0;
		protected $intAngle = 0;

		// CacheFolder Location for Cached Generated Images
		protected $strCacheFolder = null;
		protected $strCachedImageFilePath = null;

		public function ParsePostData() {}

		protected function GetControlHtml() {
			if (!$this->strFontNames)
//				throw new QCallerException('Must specify a FontNames value before rendering this QImageLabel');
				return;

			$strSerialized = $this->Serialize();
			if ($this->strCacheFolder) {
				$strHash = md5($strSerialized);
				$strFilePath = sprintf('%s%s/%s.%s',
					__DOCROOT__,
					str_replace(__VIRTUAL_DIRECTORY__, '', $this->strCacheFolder),
					$strHash,
					$this->strImageType);
				if (!file_exists($strFilePath))
					$this->RenderImage($strFilePath);

				$strPath = sprintf('%s/%s.%s',
					$this->strCacheFolder,
					$strHash,
					$this->strImageType);

				$this->strCachedImageFilePath = $strPath;
			} else {
				if (strlen($strSerialized) > 255/* safe max filename size */) {
					throw new QCallerException(
						sprintf(
							QApplication::Translate(
								"The filename size exceeded for the serialized QImageLabel control." .
								" The size is %s. The maximum value is 255. Try to set the CacheFolder property to solve the problem."
							)
							, strlen($strSerialized)
						)
					);
				}
				$strPath = sprintf('%s/image_label.php/%s/q.%s',
					__VIRTUAL_DIRECTORY__ . __PHP_ASSETS__,
					$strSerialized,
					$this->strImageType
				);
			}

			$attrOverrides['src'] = $strPath;
			$attrOverrides['alt'] = QApplication::HtmlEntities($this->strText);
			return $this->RenderTag ('img', $attrOverrides, null, null, true);
		}
		public function Validate() {return true;}

		public function Serialize() {
			$objControl = clone($this);
			$objControl->objForm = null;
			$objControl->objParentControl = null;
			$objControl->strCachedImageFilePath = null;
			$strData = serialize($objControl);
			if (function_exists('gzcompress'))
				$strData = base64_encode(gzcompress($strData, 9));
			else
				$strData = base64_encode($strData);

			$strData = str_replace('+', '-', $strData);
			$strData = str_replace('/', '_', $strData);
			
			return $strData;
		}
		
		public static function Run() {
			$strData = QApplication::PathInfo(0);
			$strData = str_replace('-', '+', $strData);
			$strData = str_replace('_', '/', $strData);

			$strData = base64_decode($strData);

			if (function_exists('gzcompress'))
				$strData = gzuncompress($strData);

			$objLabel = unserialize($strData);
			$objLabel->RenderImage();
		}
		
		protected function RenderImage($strPath = null) {
			$strWidth = $this->Width;

			// Make Sure Font File Exists
			if (file_exists($this->strFontNames))
				$strFontPath = $this->strFontNames;
			else
				throw new QCallerException('Cannot find font file: ' . $this->strFontNames);

			// Figure Out Font Type
			$strFontExtension = substr($this->strFontNames, strlen($this->strFontNames) - 3);
			$strFontExtension = strtolower($strFontExtension);
			
			// Based on Font Type, Calculate Bounding Box
			switch($strFontExtension) {
				case 'ttf':
					$blnTrueType = true;
					$objBox = imagettfbbox($this->strFontSize, $this->intAngle, $strFontPath, $this->strText);

					// Calculate Bounding Box Dimensions
					$intXCoordinate1 = $objBox[0];
					$intYCoordinate1 = $objBox[5];
					$intXCoordinate2 = $objBox[4];
					$intYCoordinate2 = $objBox[1];
					break;
				case 'pfb':
					$blnTrueType = false;

					// Load Font and Calculate
					$objFont = imagepsloadfont($strFontPath);
					$objBox = imagepsbbox($this->strText, $objFont, $this->strFontSize, $this->intSpace, $this->intTightness, $this->intAngle);

					// Calculate Bounding Box Dimensions
					$intXCoordinate1 = $objBox[0];
					$intYCoordinate1 = $objBox[1];
					$intXCoordinate2 = $objBox[2];
					$intYCoordinate2 = $objBox[3];
					break;
				default:
					throw new QCallerException('Cannot Determine Font Type: ' . $this->strFontNames);
			}

			$intBoxWidth = $intXCoordinate2 - $intXCoordinate1;
			$intBoxHeight = $intYCoordinate2 - $intYCoordinate1;

			// Figure Out Image Width and Height:
			// 1. If no width/height set, then use bounding box + padding
			// 2. otherwise, if alignment, we set to alignment
			// 3. otherwise, use coordinates
			if (!$strWidth) {
				// Step 1 -- Use Bounding Box + Padding
				$intWidth = $intBoxWidth + ($this->intPaddingWidth * 2);
				$intX = $this->intPaddingWidth;
			} else {
				// Step 2 - Alignment
				switch ($this->strHorizontalAlign) {
					case QHorizontalAlign::Left:
						$intX = -1 * $intXCoordinate1 + 2 + $this->intPaddingWidth;
						break;
					case QHorizontalAlign::Right:
						$intX = $strWidth - $intBoxWidth - 2 - $this->intPaddingWidth;
						break;
					case QHorizontalAlign::Center:
						$intX = round(($strWidth - $intBoxWidth) / 2);
						break;

					// Step 3 - Use Coordinates
					default:
						$intX = $this->intXCoordinate;
						break;
				}
				
				$intWidth = $strWidth;
			}

			if (!$this->Height) {
				// Step 1 -- Use Bounding Box + Padding
				$intHeight = $intBoxHeight + ($this->intPaddingHeight * 2);
				
				if ($blnTrueType)
					$intY = $intBoxHeight - $intYCoordinate2 + $this->intPaddingHeight;
				else
					$intY = $intYCoordinate2 + $this->intPaddingHeight + 1;
			} else {
				// Step 2 - Alignment
				switch ($this->strVerticalAlign) {
					case QVerticalAlign::Top:
						if ($blnTrueType)
							$intY = $intBoxHeight - $intYCoordinate2 + $this->intPaddingHeight;
						else
							$intY = $intYCoordinate2 + 2 + $this->intPaddingHeight;
						break;
					case QVerticalAlign::Bottom;
						if ($blnTrueType)
							$intY = $this->Height - $intYCoordinate2 - $this->intPaddingHeight;
						else
							$intY = $this->Height + $intYCoordinate1 - 2 - $this->intPaddingHeight;
						break;
					case QVerticalAlign::Middle:
						if ($blnTrueType)
							$intY = round(($this->Height - $intBoxHeight) / 2) + $intBoxHeight - $intYCoordinate2;
						else
							$intY = round(($this->Height - $intBoxHeight) / 2) + $intYCoordinate2;
						break;

					// Step 3 - Use Coordinates
					default:
						$intY = $this->intYCoordinate;
						break;
				}
				
				$intHeight = $this->Height;
			}
			
			if ($intWidth <= 0)
				$intWidth = 100;
			if ($intHeight <= 0)
				$intHeight = 100;

			$objImage = imagecreate($intWidth, $intHeight);

			// Define Colors
			$intRed = hexdec(substr($this->strBackColor, 0, 2));
			$intGreen = hexdec(substr($this->strBackColor, 2, 2));
			$intBlue = hexdec(substr($this->strBackColor, 4));
			$clrBackground = imagecolorallocate($objImage, $intRed, $intGreen, $intBlue);

			$intRed = hexdec(substr($this->strForeColor, 0, 2));
			$intGreen = hexdec(substr($this->strForeColor, 2, 2));
			$intBlue = hexdec(substr($this->strForeColor, 4));
			$clrForeground = imagecolorallocate($objImage, $intRed, $intGreen, $intBlue);
			
			if ($this->blnBackgroundTransparent)
				imagecolortransparent($objImage, $clrBackground);

			imagefilledrectangle($objImage, 0, 0, $intWidth, $intHeight, $clrBackground);

			if ($blnTrueType) {
				imagettftext($objImage, $this->strFontSize, $this->intAngle, $intX, $intY, $clrForeground, $strFontPath, $this->strText);
			} else {
				// Anti Aliasing
				if ($this->blnSmoothFont)
					$intAntiAliasing = 16;
				else
					$intAntiAliasing = 4;

				// Draw Text and Free Font
				imagepstext($objImage, $this->strText, $objFont, $this->strFontSize, $clrForeground, $clrBackground,
					$intX, $intY, $this->intSpace, $this->intTightness, $this->intAngle, $intAntiAliasing);
				imagepsfreefont($objFont);
			}

			// Output the Image (if path isn't specified, output to buffer.  Otherwise, output to disk)
			if (!$strPath) {
				// TODO: Update Cache Parameters
				QApplication::$ProcessOutput = false;
				header('Cache-Control: cache');
				header('Expires: Wed, 20 Mar 2019 05:00:00 GMT');
				header('Pragma: cache');

				switch ($this->strImageType) {
					case QImageType::Gif:
						header('Content-type: image/gif');
						imagegif($objImage);
						break;
					case QImageType::Jpeg:
						header('Content-type: image/jpeg');
						imagejpeg($objImage, null, $this->intQuality);
						break;
					default:
						header('Content-type: image/png');
						imagepng($objImage);
						break;
				}
			} else {
				switch ($this->strImageType) {
					case QImageType::Gif:
						imagegif($objImage, $strPath);
						break;
					case QImageType::Jpeg:
						imagejpeg($objImage, $strPath, $this->intQuality);
						break;
					default:
						imagepng($objImage, $strPath);
						break;
				}
			}

			imagedestroy($objImage);
		}



		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "XCoordinate": return $this->intXCoordinate;
				case "YCoordinate": return $this->intYCoordinate;
				case "BackgroundTransparent": return $this->blnBackgroundTransparent;

				// BEHAVIOR
				case "ImageType": return $this->strImageType;
				case "Quality": return $this->intQuality;
				case "SmoothFont": return $this->blnSmoothFont;

				// LAYOUT
				case "HorizontalAlign": return $this->strHorizontalAlign;
				case "VerticalAlign": return $this->strVerticalAlign;
				case "PaddingWidth": return $this->intYCoordinate;
				case "PaddingHeight": return $this->intYCoordinate;
				case "Space": return $this->intYCoordinate;
				case "Tightness": return $this->intYCoordinate;
				case "Angle": return $this->intYCoordinate;

				case "CacheFolder": return $this->strCacheFolder;

				case "CachedImageFilePath": return $this->strCachedImageFilePath;

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

				case "XCoordinate":
					try {
						$this->intXCoordinate = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "YCoordinate":
					try {
						$this->intYCoordinate = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "BackgroundTransparent":
					try {
						$this->blnBackgroundTransparent = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				// BEHAVIOR
				case "ImageType":
					try {
						$this->strImageType = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Quality":
					try {
						$this->intQuality = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "SmoothFont":
					try {
						$this->blnSmoothFont = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				


				// LAYOUT
				case "HorizontalAlign":
					try {
						$this->strHorizontalAlign = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "VerticalAlign":
					try {
						$this->strVerticalAlign = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "PaddingWidth":
					try {
						$this->intPaddingWidth = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "PaddingHeight":
					try {
						$this->intPaddingHeight = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Space":
					try {
						$this->intSpace = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Tightness":
					try {
						$this->intTightness = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Angle":
					try {
						$this->intAngle = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				case "CacheFolder":
					try {
						$this->strCacheFolder = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				// OVERRIDDEN SETTERS
				case "ForeColor":
					try {
						$mixValue = strtolower(QType::Cast($mixValue, QType::String));
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					if (strlen($mixValue) != 6)
						throw new QInvalidCastException('ForeColor must be a 6-digit hexadecimal value');

					// Verify ControlId is only Hexadecimal Digits
					$strMatches = array();
					$strPattern = '/[a-f0-9]*/';
					preg_match($strPattern, $mixValue, $strMatches);
					if (count($strMatches) && ($strMatches[0] == $mixValue))
						return ($this->strForeColor = $mixValue);
					else
						throw new QInvalidCastException('ForeColor must be a 6-digit hexadecimal value');

					break;
					
				case "BackColor":
					try {
						$mixValue = strtolower(QType::Cast($mixValue, QType::String));
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					if (strlen($mixValue) != 6)
						throw new QInvalidCastException('BackColor must be a 6-digit hexadecimal value');

					// Verify ControlId is only Hexadecimal Digits
					$strMatches = array();
					$strPattern = '/[a-f0-9]*/';
					preg_match($strPattern, $mixValue, $strMatches);
					if (count($strMatches) && ($strMatches[0] == $mixValue))
						return ($this->strBackColor = $mixValue);
					else
						throw new QInvalidCastException('BackColor must be a 6-digit hexadecimal value');

					break;

				case "FontSize":
					try {
						$this->strFontSize = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Width":
					try {
						$this->strWidth = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Height":
					try {
						$this->strHeight = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "FontNames":
					try {
						$mixValue = QType::Cast($mixValue, QType::String);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					// Make Sure Font File Exists
					if (file_exists(dirname(QApplication::$ScriptFilename) . '/' . $mixValue))
						$strFontPath = dirname(QApplication::$ScriptFilename) . '/' . $mixValue;
					else if (file_exists(sprintf('%s/fonts/%s', __QCUBED_CORE__, $mixValue)))
						$strFontPath = sprintf('%s/fonts/%s', __QCUBED_CORE__, $mixValue);
					else
						throw new QCallerException('Cannot find font file: ' . $mixValue);

					$this->strFontNames = $strFontPath;

					// Figure Out Font Type
					$strFontExtension = substr($mixValue, strlen($mixValue) - 3);
					$strFontExtension = strtolower($strFontExtension);

					// Based on Font Type, Calculate Bounding Box
					switch($strFontExtension) {
						case 'ttf':
							break;
						case 'pfb':
							$strFontPath = substr($strFontPath, 0, strlen($strFontPath) - 3) . 'afm';
							if (!file_exists($strFontPath))
								throw new QCallerException('Cannot find accompanying Font Metrics file: ' .
									substr($mixValue, 0, strlen($mixValue) - 3) . 'afm');
							break;
						case 'afm':
							throw new QCallerException('AFM is only a Font Metrics file.  You must provide a PFB file for PostScript Type 1 Typefaces: ' . $mixValue);
						default:
							throw new QCallerException('Cannot Determine Font Type: ' . $mixValue);
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
	}