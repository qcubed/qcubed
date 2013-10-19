<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * User: vakopian
	 * Date: 6/24/12
	 * Time: 11:36 AM
	 * To change this template use File | Settings | File Templates.
	 */
	class SearchControl extends SearchTerm {
		public static $strRangeDelim = ' - ';
		/** @var QControl */
		private $objControl;

		public function __construct($objControl, $strProperty, $strType) {
			parent::__construct($strProperty, $strType, null);
			$this->objControl = $objControl;
		}

		public function GetValue() {
			if ($this->objControl instanceof QListControl) {
				return $this->objControl->SelectedValue;
			}
			if ($this->objControl instanceof QCheckBox) {
				return $this->objControl->Checked;
			}
			if ($this->objControl instanceof QTextBox) {
				return $this->objControl->Text;
			}
			if ($this->objControl instanceof QDateRangePicker) {
				return $this->objControl->Input->Text;
			}
			return null;
		}

		public function SetValue($value) {
			if ($this->objControl instanceof QListControl) {
				$this->objControl->SelectedValue = $value;
			} else if ($this->objControl instanceof QCheckBox) {
				$this->objControl->Checked = $value;
			} else if ($this->objControl instanceof QTextBox) {
				$this->objControl->Text = $value;
			} else if ($this->objControl instanceof QDateRangePicker) {
				$this->objControl->Input->Text = $value;
			}
		}

		public function GetTerm() {
			$txt = $this->GetValue();
			if ($this->strType == QType::Integer || $this->strType == QType::Float || $this->strType == QType::DateTime) {
				$parts = explode(self::$strRangeDelim, $txt, 2);
				if (count($parts) > 1)
					return $parts;
			}
			return $txt;
		}

		/**
		 * @return \QControl
		 */
		public function GetControl() {
			return $this->objControl;
		}
	}


	/**
	 * @property-read QQCondition $CurrentCondition
	 * @property-read QGenericSearchOptions $SearchOptions
	 * @property-write QCallback $SearchCallback
	 */
	class SearchPanel extends QPanel {
		/** @var QQBaseNode */
		protected $objBaseNode;
		/** @var QGenericSearchOptions */
		protected $objSearchOptions;
		/** @var SearchControl[] */
		protected $objSearchControls = array();
		/** @var QQCondition */
		protected $objCurrentCondition;
		/** @var QCallback */
		protected $objSearchCallback;
		/** @var boolean */
		protected $blnAutosSearch = true;
		/** @var int */
		protected $intDelay = 600;

		public function __construct($objParentObject, QQBaseNode $objBaseNode, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->CssClass = 'search_panel';
			$this->UseWrapper = false;
			$this->objSearchOptions = new QGenericSearchOptions();
			$this->objBaseNode = $objBaseNode;
			$this->objCurrentCondition = QQ::All();
		}

		/**
		 * @param string $property
		 * @param string|array $type
		 * @param string|null $name
		 * @return \QControl
		 * @throws QCallerException
		 */
		public function AddSearchControl($property, $type, $name = null) {
			$css = 'search_'.preg_replace('/\W+/', '_', strtolower($property));
			$action = new QAjaxControlAction($this, "btnSearch_Click");
			if (is_string($type)) switch ($type) {
				case QType::DateTime:
					$objControl = new QDateRangePicker($this);
					$objControl->AutoRenderChildren = true;
					$objControl->Input = new QTextBox($objControl);
					$objControl->Input->CssClass = 'textbox ui-corner-all search_date '.$css;
					if ($this->blnAutosSearch)
						$objControl->AddAction(new QDateRangePicker_CloseEvent(), $action);
					break;
				case QType::Boolean:
					$objControl = new QListBox($this);
					$objControl->CssClass = 'listbox ui-state-default ui-corner-all search_list '.$css;
					$objControl->AddItem("", "");
					$objControl->AddItem(_tr("Yes"), 1);
					$objControl->AddItem(_tr("No"), 0);
					if ($this->blnAutosSearch)
						$objControl->AddAction(new QChangeEvent(), $action);
					break;
				case QType::Integer:
				case QType::Float:
					$objControl = new QTextBox($this);
					$objControl->CssClass = 'textbox ui-corner-all search_num '.$css;
					if ($this->blnAutosSearch)
						$objControl->AddAction(new QKeyUpEvent($this->intDelay), $action);
					break;
				default:
					$objControl = new QTextBox($this);
					$objControl->CssClass = 'textbox ui-corner-all search_txt '.$css;
					if ($this->blnAutosSearch)
						$objControl->AddAction(new QKeyUpEvent($this->intDelay), $action);
					break;
			} else if (is_array($type)) {
				$objControl = new QListBox($this);
				$objControl->CssClass = 'listbox ui-state-default ui-corner-all search_list '.$css;
				$item = new QListItem("", "");
				$objControl->AddItem($item);
				$valueType = null;
				foreach ($type as $value => $label) {
					$item = new QListItem($label, $value);
					$objControl->AddItem($item);
					if (!$valueType) {
						if (is_integer($value)) {
							$valueType = QType::Integer;
						} else if (is_double($item)) {
							$valueType = QType::Float;
						}
					}
				}
				$type = $valueType ? $valueType : QType::String;
				if ($this->blnAutosSearch)
					$objControl->AddAction(new QChangeEvent(), $action);
			} else {
				throw new QCallerException("argument type must be either a QType or an array, " . get_class($type) . " was given");
			}
			$objControl->Name = $name ? $name : _tr($property);
			$objControl->UseWrapper = false;
			$this->objSearchControls[$property] = new SearchControl($objControl, $property, $type);
			$this->objCurrentCondition = null;
			return $objControl;
		}

		public function btnSearch_Click($strFormId, $strControlId, $strParameter) {
			$this->objCurrentCondition = $this->GetCondition();
			if ($this->objSearchCallback) {
				$this->objSearchCallback->Call($this->objCurrentCondition);
			}
		}

		public function ResetAllControls() {
			foreach ($this->objSearchControls as $objSearchControl) {
				$objSearchControl->SetValue(null);
			}
			$this->objCurrentCondition = QQ::All();
			if ($this->objSearchCallback) {
				$this->objSearchCallback->Call($this->objCurrentCondition);
			}
		}

		/**
		 * @param string $property
		 * @return null|QControl
		 */
		public function GetSearchControl($property) {
			if (!array_key_exists($property, $this->objSearchControls)) {
				return null;
			}
			return $this->objSearchControls[$property]->GetControl();
		}

		/**
		 * @param string $property
		 * @return null|QControl
		 */
		public function RemoveSearchControl($property) {
			if (!array_key_exists($property, $this->objSearchControls)) {
				return null;
			}
			$objControl = $this->objSearchControls[$property]->GetControl();
			$this->RemoveChildControl($objControl->ControlId, true);
			unset($this->objSearchControls[$property]);
			$this->objCurrentCondition = null;
			return $objControl;
		}

		/**
		 * @return QQCondition
		 */
		public function GetCondition() {
			return $this->objSearchOptions->SearchCondition0($this->objBaseNode, $this->objSearchControls);
		}

		/**
		 * @return array
		 */
		public function SaveValues() {
			$values = array();
			foreach ($this->objSearchControls as $objSearchControl) {
				$values[$objSearchControl->strProperty] = $objSearchControl->GetValue();
			}
			return $values;
		}

		/**
		 * @param array $values
		 */
		public function LoadValues($values) {
			foreach ($this->objSearchControls as $objSearchControl) {
				if (array_key_exists($objSearchControl->strProperty, $values)) {
					$objSearchControl->SetValue($values[$objSearchControl->strProperty]);
				}
			}
			$this->objCurrentCondition = null;
		}

		/**
		 * @return \QGenericSearchOptions
		 */
		public function GetSearchOptions() {
			return $this->objSearchOptions;
		}


		public function __get($strName) {
			switch ($strName) {
				case "SearchOptions": return $this->objSearchOptions;
				case "Condition":
					if (!$this->objCurrentCondition) {
						$this->objCurrentCondition = $this->GetCondition();
					}
					return $this->objCurrentCondition;
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
			switch ($strName) {
				case 'SearchCallback':
					try {
						$this->objSearchCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QCallerException $objExc) {
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
			}
		}

	}
