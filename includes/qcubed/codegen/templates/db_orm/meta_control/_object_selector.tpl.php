<?php
    /** @var QTable $objTable */
    /** @var QTable[] $objTableArray */
    /** @var QDatabaseCodeGen $objCodeGen */
    global $_TEMPLATE_SETTINGS;
    $_TEMPLATE_SETTINGS = array(
        'OverwriteFlag' => true,
        'DocrootFlag' => false,
        'DirectorySuffix' => '',
        'TargetDirectory' => __META_CONTROLS_GEN__,
        'TargetFileName' => $objTable->ClassName.'ObjectSelectorGen.class.php'
    );
?>
<?php print("<?php\n"); ?>
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>UpdatePanel.class.php');
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>DataTable.class.php');
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>Popup.class.php');

	/**
	 * @property string $LabelForRequired
	 * @property string $LabelForRequiredUnnamed
	 * @property boolean $ValidateTrimmed
	 * @property-read int $SelectedId
	 * @property-read int $SelectedValue
	 * @property-read <?php echo $objTable->ClassName ?> $SelectedObject
	 * @property-read QAutocomplete $Input
	 * @property-read QPanel $Toolbar
	 * @property-read <?php echo $objTable->ClassName ?>UpdatePanel $EditControl
	 * @property-read <?php echo $objTable->ClassName ?>DataTable $SearchControl
	 * @property-write QCallback $PostSelectionCallback
	 */
	class <?php echo $objTable->ClassName ?>ObjectSelectorGen extends <?php echo $objTable->ClassName ?>Popup {
		/** @var QAutocomplete */
		protected $txtAutocomplete;
		/** @var <?php echo $objTable->ClassName ?>UpdatePanel */
		protected $objEditControl;
		/** @var QGenericSearchOptions */
		protected $objSearchOptions = null;
		/** @var QQCondition */
		protected $objAdditionalConditions = null;
		/** @var QQClause[] */
		protected $objOptionalClauses = null;
		/** @var <?php echo $objTable->ClassName ?>DataTable */
		protected $objSearchControl;
		/** @var QCallback */
		protected $objPostSelectionCallback;
		/** @var QPanel */
		protected $pnlToolbar;
		/** @var boolean */
		protected $blnShowToolbarOnHover = true;
		/** @var QJqButton */
		protected $btnNew;
		/** @var QJqButton */
		protected $btnEdit;
		/** @var QJqButton */
		protected $btnSearch;
		/** @var QJqButton */
		protected $btnClear;
		/** @var string */
		static protected $strObjectIdDelim = ',';

		/** @var <?php echo $objTable->ClassName ?>|null */
		protected $objSelectedObject;

		public function __construct($objParentObject, $blnSearch = true, $blnClear = true, $blnNew = true, $blnEdit = true, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$this->AutoRenderChildren = true;
			$this->UseWrapper = false;
			$this->CssClass = "object_selector";

			$this->txtAutocomplete = new QAutocomplete($this);
			$this->txtAutocomplete->CssClass = 'ui-autocomplete-input ui-corner-all';
			$this->txtAutocomplete->SetDataBinder("update_autocompleteList", $this);
			$this->txtAutocomplete->MustMatch = true;
			$this->txtAutocomplete->UseWrapper = false;
			$this->txtAutocomplete->AddAction(new QAutocomplete_SelectEvent(), new QAjaxControlAction($this, 'autocomplete_selected', 'default', null, 'ui.item.id'));

			$this->pnlToolbar = new QPanel($this);
			$this->pnlToolbar->AutoRenderChildren = true;
			$this->pnlToolbar->UseWrapper = false;
			$this->pnlToolbar->CssClass = 'toolbar';
			$this->pnlToolbar->DisplayStyle = $this->blnShowToolbarOnHover ? QDisplayStyle::None : QDisplayStyle::Block;

			if ($blnClear) {
				$this->btnClear = new QJqButton($this->pnlToolbar);
				$this->btnClear->Visible = false;
				$this->btnClear->Icons = array('primary' => JqIcon::Arrowthickstop1W);
				$this->btnClear->HtmlEntities = false;
				$this->btnClear->Text = '&nbsp;';
				$this->btnClear->ShowText = false;
				$this->btnClear->ToolTip = QApplication::Translate('Clear');
				$this->btnClear->AddAction(new QClickEvent(), new QAjaxControlAction($this, "btnClear_Click"));
				$this->btnClear->CssClass = 'clear';
			}

			if ($blnSearch) {
				$this->btnSearch = new QJqButton($this->pnlToolbar);
				$this->btnSearch->Icons = array('primary' => JqIcon::Search);
				$this->btnSearch->Text = '&nbsp;';
				$this->btnSearch->HtmlEntities = false;
				$this->btnSearch->ShowText = false;
				$this->btnSearch->ToolTip = QApplication::Translate('Search');
				$this->btnSearch->AddAction(new QClickEvent(), new QAjaxControlAction($this, "btnSearch_Click"));
				$this->btnSearch->CssClass = 'search';
			}

			if ($blnNew) {
				$this->btnNew = new QJqButton($this->pnlToolbar);
				$this->btnNew->Icons = array('primary' => JqIcon::Plusthick);
				$this->btnNew->HtmlEntities = false;
				$this->btnNew->Text = '&nbsp;';
				$this->btnNew->ShowText = false;
				$this->btnNew->ToolTip = QApplication::Translate('New');
				$this->btnNew->AddAction(new QClickEvent(), new QAjaxControlAction($this, "btnNew_Click"));
				$this->btnNew->CssClass = 'new';
			}

			if ($blnEdit) {
				$this->btnEdit = new QJqButton($this->pnlToolbar);
				$this->btnEdit->Visible = false;
				$this->btnEdit->Icons = array('primary' => JqIcon::Document);
				$this->btnEdit->HtmlEntities = false;
				$this->btnEdit->Text = '&nbsp;';
				$this->btnEdit->ShowText = false;
				$this->btnEdit->ToolTip = QApplication::Translate('Edit');
				$this->btnEdit->AddAction(new QClickEvent(), new QAjaxControlAction($this, "btnEdit_Click"));
				$this->btnEdit->CssClass = 'edit';
			}
		}

		public function update_autocompleteList() {
			$strTyped = $this->txtAutocomplete->Text;
			$objSearchResult = $this->search($strTyped);

			$data = array();
			if ($objSearchResult instanceof QDatabaseResultBase) {
				// we got a cursor
				while ($item = $this->getNextSearchResult($objSearchResult)) {
					$data[] = new QListItem($this->getObjectLabel($item), $this->getObjectId($item));
				}
			} else {
				foreach ($objSearchResult as $item) {
					$data[] = new QListItem($this->getObjectLabel($item), $this->getObjectId($item));
				}
			}
			$this->txtAutocomplete->DataSource = $data;
		}

		/**
		 * @param <?php echo $objTable->ClassName ?> $objObject
		 * @return string
		 */
		protected function getObjectLabel($objObject) {
			return $objObject->__toString();
		}

		/**
		 * @param string|null $strTyped
		 * @return QDatabaseResultBase|<?php echo $objTable->ClassName ?>[]
		 */
		protected function search($strTyped) {
			return <?php echo $objTable->ClassName ?>::GenericSearchCursor($strTyped, $this->objSearchOptions, $this->objAdditionalConditions, $this->objOptionalClauses);
		}

		/**
		 * @param QDatabaseResultBase $objDbResult
		 * @return <?php echo $objTable->ClassName ?>
		 */
		protected function getNextSearchResult(QDatabaseResultBase $objDbResult) {
			return <?php echo $objTable->ClassName ?>::InstantiateCursor($objDbResult);
		}

		/**
		 * @param <?php echo $objTable->ClassName ?> $objObject
		 * @return string
		 */
		protected function getObjectId($objObject) {
<?php if (count($objTable->PrimaryKeyColumnArray) == 1) { ?>
			return $objObject-><?php echo $objTable->PrimaryKeyColumnArray[0]->PropertyName ?>;
<?php } else { ?>
			return $objObject->__Id;
<?php } ?>
		}

		/**
		 * @param string $strId
		 * @return <?php echo $objTable->ClassName ?>

		 */
		protected function getObjectById($strId) {
<?php if (count($objTable->PrimaryKeyColumnArray) == 1) { ?>
			return <?php echo $objTable->ClassName ?>::LoadBy<?php echo $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objTable->PrimaryKeyColumnArray);  ?>($strId);
<?php } else { ?>
			$objId = explode(<?php echo $objTable->ClassName ?>ObjectSelector::$strObjectIdDelim, $strId);
<?php
	$strArgs = ''; $idx = 0;
	foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {
		if ($strArgs) $strArgs .= ', ';
		$strArgs .= '$objId['.$idx.']';
		++$idx;
	}
?>
			return <?php echo $objTable->ClassName ?>::LoadBy<?php echo $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objTable->PrimaryKeyColumnArray);  ?>(<?php echo $strArgs ?>);
<?php } ?>
		}

		protected function getObjectClassName() {
			return '<?php echo $objTable->ClassName ?>';
		}

		public function SetSelection($objSelectedObject) {
			if (!($objSelectedObject instanceof <?php echo $objTable->ClassName ?>)) {
				$objSelectedObject = $this->getObjectById($objSelectedObject);
			}
			$this->objSelectedObject = $objSelectedObject;
			if (!$objSelectedObject) {
				$this->Clear();
			} else {
				$this->txtAutocomplete->Text = $this->getObjectLabel($objSelectedObject);
				$this->txtAutocomplete->SelectedId = $this->getObjectId($objSelectedObject);
				if ($this->btnClear)
					$this->btnClear->Visible = true;
				if ($this->btnEdit)
					$this->btnEdit->Visible = true;
			}
			if ($this->objPostSelectionCallback) {
				$this->objPostSelectionCallback->Call();
			}
		}

		public function btnSearch_Click($strFormId, $strControlId, $strParameter) {
			$this->objSearchControl = $this->Select(new QMethodCallback($this, 'CloseSearchPane'));
		}

		public function btnNew_Click($strFormId, $strControlId, $strParameter) {
			$this->objEditControl = $this->Create(new QMethodCallback($this, 'CloseEditPane'));
		}

		public function btnEdit_Click($strFormId, $strControlId, $strParameter) {
			$this->objEditControl = $this->Edit($this->objSelectedObject, new QMethodCallback($this, 'CloseEditPane'), new QMethodCallback($this, 'CloseEditPane'));
		}

		public function btnClear_Click($strFormId, $strControlId, $strParameter) {
			$this->Clear();
		}

		public function autocomplete_selected($strFormId, $strControlId, $strParameter) {
			if (!$strParameter) {
				$this->SetSelection(null);
			} else {
				$objSelectedObject = $this->getObjectById($strParameter);
				$this->SetSelection($objSelectedObject);
			}
		}

		public function Clear() {
			$this->objSelectedObject = null;
			$this->txtAutocomplete->Text = '';
			$this->txtAutocomplete->SelectedId = null;
			if ($this->btnClear)
				$this->btnClear->Visible = false;
			if ($this->btnEdit)
				$this->btnEdit->Visible = false;
		}

		public function CloseSearchPane($objSelectedObject = null) {
			if ($objSelectedObject) {
				$this->SetSelection($objSelectedObject);
			}
		}

		public function CloseEditPane($obj<?php echo $objTable->ClassName ?>, $blnUpdatesMade, $blnDeleted = false) {
			if ($blnDeleted) {
				$this->Clear();
			} else if ($blnUpdatesMade && $obj<?php echo $objTable->ClassName ?>) {
				$this->SetSelection($obj<?php echo $objTable->ClassName ?>);
			}
		}

		public function Validate() {
			$this->strValidationError = "";

			if (!$this->txtAutocomplete->Validate()) {
				$this->strValidationError = $this->txtAutocomplete->ValidationError;
				return false;
			}
			// Check for Required
			if ($this->blnRequired) {
				if (!$this->txtAutocomplete->SelectedId) {
					if ($this->strName)
						$this->strValidationError = sprintf($this->LabelForRequired, $this->strName);
					else
						$this->strValidationError = $this->LabelForRequiredUnnamed;
					return false;
				}
			}

			// If we're here, then everything is a-ok.  Return true.
			return true;
		}

		public function GetEndScript() {
			$strJS = parent::GetEndScript();
			if ($this->blnShowToolbarOnHover) {
				$strJS = sprintf('$j("#%s").hover(function () {$j("#%s").show();}, function () {$j("#%s").hide();}); %s',
					$this->ControlId, $this->pnlToolbar->ControlId, $this->pnlToolbar->ControlId, $strJS);
			}

			return $strJS;
		}

		public function __get($strName) {
			switch ($strName) {
				case "SelectedValue":
				case "SelectedId": return $this->txtAutocomplete->SelectedId;
				case "SelectedObject": return $this->objSelectedObject;
				case "Input": return $this->txtAutocomplete;
				case "Toolbar": return $this->pnlToolbar;
				case "EditControl": return $this->objEditControl;
				case "SearchControl": return $this->objSearchControl;
				case "ValidateTrimmed":
				case "LabelForRequired":
				case "LabelForRequiredUnname":
					return $this->txtAutocomplete->__get($strName);

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
			try {
				switch ($strName) {
					case "ShowToolbarOnHover":
						$this->blnShowToolbarOnHover = QType::Cast($mixValue, QType::Boolean);
						break;
					case "Required":
					case "Name":
					case "ValidateTrimmed":
					case "LabelForRequired":
					case "LabelForRequiredUnname":
						parent::__set($strName, $mixValue);
						$this->txtAutocomplete->__set($strName, $mixValue);
						break;
					case "PostSelectionCallback":
						$this->objPostSelectionCallback = QType::Cast($mixValue, 'QCallback');
						break;
					default:
						parent::__set($strName, $mixValue);
						break;
				}
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

	}
