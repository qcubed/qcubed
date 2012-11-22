<?php
	/**
	 * This file contains the QDateRangePicker class.
	 *
	 * @package Controls
	 *
	 */

	require_once 'QDateRangePickerPreset.class.php';
	require_once 'QDateRangePickerPresetRange.class.php';

	/*
	 * @package Controls
	 * 
	 * @property QControl $Input
	 * @property QControl $SecondInput
	 * @property String $DateFormat
	 *
	 */
	class QDateRangePicker extends QDateRangePickerBase {
		protected $txtInput;
		protected $txtSecondInput;
		protected $strRangeSplitter = '-';
		protected $strDateFormat;

		public function  __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->AddPluginJavascriptFile("QDateRangePicker", "daterangepicker.jQuery.js");
			$this->AddPluginCssFile("QDateRangePicker", "collisions.css");
			$this->AddPluginCssFile("QDateRangePicker", "ui.daterangepicker.css");
		}

		public function getJqControlId() {
			if ($this->SecondInput) {
				return $this->Input->ControlId.', #'.$this->SecondInput->ControlId;
			}
			return $this->Input->ControlId;
		}

		public function AddPreset(QDateRangePickerPreset $preset, $strLabel = null) {
			if (!$strLabel) $strLabel = $preset->DefaultLabel;
			if (null === $this->arrPresets) $this->arrPresets = array();
			$this->arrPresets[$preset->Preset] = $strLabel;
		}

		public function RemovePreset(QDateRangePickerPreset $preset) {
			if ($this->arrPresets)
				unset($this->arrPresets[$preset->Preset]);
		}

		public function RemoveAllPresets() {
			$this->arrPresets = array();
		}

		public function ResetToDefaultPresets() {
			$this->arrPresets = null;
		}

		public function AddPresetRange(QDateRangePickerPresetRange $presetRange) {
			if (null === $this->arrPresetRanges) $this->arrPresetRanges = array();
			array_push($this->arrPresetRanges, $presetRange);
		}

		public function RemovePresetRange(QDateRangePickerPresetRange $presetRange) {
			if ($this->arrPresetRanges) {
				while (($key = array_search($presetRange, $this->arrPresetRanges)) !== false) {
					unset($this->arrPresetRanges[$key]);
				}
				// reindex
				$this->arrPresetRanges = array_values($this->arrPresetRanges);
			}
		}

		public function RemoveAllPresetRanges() {
			$this->arrPresetRanges = array();
		}

		public function ResetToDefaultPresetRanges() {
			$this->arrPresetRanges = null;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				case "Input" : return $this->txtInput;
				case "SecondInput" : return $this->txtSecondInput;
				case "DateFormat" : return $this->strDateFormat;
				default :
					try {
						return parent::__get($strName);
					}
					catch (QCallerException $objExc) {
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
				case "Input" : {
					try {
						$this->txtInput = QType::Cast($mixValue, 'QControl');
						break;
					}
					catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				}

				case "SecondInput" : {
					try {
						$this->txtSecondInput = QType::Cast($mixValue, 'QControl');
						break;
					}
					catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				}

				case "JqDateFormat": {
					try {
						$this->strJqDateFormat = QType::Cast($mixValue, QType::String);
						$this->strDateFormat = QCalendar::qcFrmt($this->strJqDateFormat);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				}

				case "DateFormat": {
					try {
						$this->strDateFormat = QType::Cast($mixValue, QType::String);
						$this->strJqDateFormat = QCalendar::jqFrmt($this->strDateFormat);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				}

				default :
					try {
						parent::__set($strName, $mixValue);
					}
					catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}

	}


?>
