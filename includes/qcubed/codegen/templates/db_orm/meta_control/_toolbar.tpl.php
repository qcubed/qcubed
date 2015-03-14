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
        'TargetFileName' => $objTable->ClassName.'ToolbarGen.class.php'
    );
?>
<?php print("<?php\n"); ?>
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>Popup.class.php');

	/**
	 * @property-read QLabel $Label
	 * @property-read QIntegerTextBox $LoadBox
	 * @property-read QJqButton $LoadButton
	 * @property-read QJqButton $NewButton
	 * @property-read QJqButton $ViewButton
	 * @property-read QJqButton $EditButton
	 * @property-read QJqButton $DeleteButton
	 * @property-read <?php echo $objTable->ClassName ?>MetaControl $MetaControl
	 * @property-write QCallback $LoadCallback
	 * @property-write QCallback $CreateCallback
	 * @property-write QCallback $ViewCallback
	 * @property-write QCallback $EditCallback
	 * @property-write QCallback $DeleteCallback
	 */
	class <?php echo $objTable->ClassName ?>ToolbarGen extends <?php echo $objTable->ClassName ?>Popup {
		/** @var QLabel */
		protected $lblToolbar;
		/** @var QIntegerTextBox */
		protected $txtLoad;
		/** @var QJqButton */
		protected $btnLoad;
		/** @var QJqButton */
		protected $btnNew;
		/** @var QJqButton */
		protected $btnView;
		/** @var QJqButton */
		protected $btnEdit;
		/** @var QJqButton */
		protected $btnDelete;

		/** @var QCallback */
		protected $objLoadCallback;
		/** @var QCallback */
		protected $objCreateCallback;
		/** @var QCallback */
		protected $objEditCallback;
		/** @var QCallback */
		protected $objDeleteCallback;

		/** @var mixed*/
		protected $obj<?php echo $objTable->ClassName ?>Ref;
		/** @var <?php echo $objTable->ClassName ?>MetaControl cached instance */
		protected $mct<?php echo $objTable->ClassName ?>;

		/**
		 * @param QControl|QForm $objParentObject
		 * @param <?php echo $objTable->ClassName ?>MetaControl|<?php echo $objTable->ClassName ?>|string $obj<?php echo $objTable->ClassName ?>Ref
		 * @param boolean $blnNew
		 * @param boolean $blnEdit
		 * @param boolean $blnDelete
		 * @param boolean $blnView
		 * @param string $strControlId
		 * @throws QCallerException
		 */
        public function __construct($objParentObject, $obj<?php echo $objTable->ClassName ?>Ref = null, $blnNew = false, $blnEdit = true, $blnDelete = true, $blnView = false, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$this->obj<?php echo $objTable->ClassName ?>Ref = $obj<?php echo $objTable->ClassName ?>Ref;

			$this->AutoRenderChildren = true;
			$this->CssClass = 'toolbar ui-widget-header ui-corner-all';
			$this->lblToolbar = new QLabel($this);
			$this->lblToolbar->CssClass = 'toolbar_lbl';
			$this->lblToolbar->Text = $this->getLabel();

			if ($blnNew) {
				$this->txtLoad = new QIntegerTextBox($this);
				$this->txtLoad->CssClass = 'load_value textbox ui-corner-all';
				$this->txtLoad->Required = true;
				$this->txtLoad->AddAction(new QEnterKeyEvent(), new QAjaxControlAction($this, 'btnLoad_Click'));

				$this->btnLoad = new QJqButton($this);
				$this->btnLoad->Text = QApplication::Translate('Load');
				$this->btnLoad->HtmlEntities = false;
				$this->btnLoad->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnLoad_Click'));
				$this->btnLoad->Icons = array('primary' => JqIcon::Play);
				$this->btnLoad->CssClass = 'load';
				$this->btnLoad->CausesValidation = $this->txtLoad;

				$this->btnNew = new QJqButton($this);
				$this->btnNew->Text = QApplication::Translate('New');
				$this->btnNew->HtmlEntities = false;
				$this->btnNew->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnNew_Click'));
				$this->btnNew->Icons = array('primary' => JqIcon::Plusthick);
				$this->btnNew->CssClass = 'new';
			}

			if ($blnView) {
				$this->btnView = new QJqButton($this);
				$this->btnView->Text = QApplication::Translate('View');
				$this->btnView->HtmlEntities = false;
				$this->btnView->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnView_Click'));
				$this->btnView->Icons = array('primary' => JqIcon::Info);
				$this->btnView->CssClass = 'View';
			}

			if ($blnEdit) {
				$this->btnEdit = new QJqButton($this);
				$this->btnEdit->Text = QApplication::Translate('Edit');
				$this->btnEdit->HtmlEntities = false;
				$this->btnEdit->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnEdit_Click'));
				$this->btnEdit->Icons = array('primary' => JqIcon::Document);
				$this->btnEdit->CssClass = 'edit';
			}

			if ($blnDelete) {
				$this->btnDelete = new QJqButton($this);
				$this->btnDelete->Text = QApplication::Translate('Delete');
				$this->btnDelete->HtmlEntities = false;
				$this->btnDelete->AddAction(new QClickEvent(), new QConfirmAction(sprintf(QApplication::Translate('Are you SURE you want to DELETE this %s?'),  QApplication::Translate('<?php echo $objTable->ClassName  ?>'))));
				$this->btnDelete->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnDelete_Click'));
				$this->btnDelete->Icons = array('primary' => JqIcon::Trash);
				$this->btnDelete->CssClass = 'delete';
			}
		}

		protected function getLabel() {
			if (!$this->obj<?php echo $objTable->ClassName ?>Ref) {
				return QApplication::Translate('<?php echo $objTable->ClassName ?>');
			}
			if (!is_object($this->obj<?php echo $objTable->ClassName ?>Ref)) {
				return sprintf(QApplication::Translate('<?php echo $objTable->ClassName ?> #%s'), $this->obj<?php echo $objTable->ClassName ?>Ref);
			}
			$obj<?php echo $objTable->ClassName ?> = $this->get<?php echo $objTable->ClassName ?>MetaControl()-><?php echo $objTable->ClassName ?>;
			if ($obj<?php echo $objTable->ClassName ?>->__Restored) {
				return sprintf(QApplication::Translate('<?php echo $objTable->ClassName ?> #%s'), $obj<?php echo $objTable->ClassName ?>->__Id);
			}
			return QApplication::Translate('<?php echo $objTable->ClassName ?>');
		}

		// Control AjaxAction Event Handlers
		public function btnLoad_Click($strFormId, $strControlId, $strParameter) {
			if ($this->objLoadCallback) {
				$strId = $this->txtLoad->Text;
				$this->obj<?php echo $objTable->ClassName ?>Ref = $strId;
				//$obj<?php echo $objTable->ClassName ?> = <?php echo $objTable->ClassName ?>::LoadBy<?php echo $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objTable->PrimaryKeyColumnArray);  ?>($strId);
				$this->objLoadCallback->Call($this->get<?php echo $objTable->ClassName ?>MetaControl()-><?php echo $objTable->ClassName ?>);
			}
		}

		/**
		 * @return <?php echo $objTable->ClassName ?>MetaControl
		 */
		protected function get<?php echo $objTable->ClassName ?>MetaControl() {
			if ($this->obj<?php echo $objTable->ClassName ?>Ref) {
				if (!$this->mct<?php echo $objTable->ClassName ?> || !$this->mct<?php echo $objTable->ClassName ?>->Matches($this->ParentControl, $this->obj<?php echo $objTable->ClassName ?>Ref)) {
					$this->mct<?php echo $objTable->ClassName ?> = <?php echo $objTable->ClassName ?>MetaControl::From($this->ParentControl, $this->obj<?php echo $objTable->ClassName ?>Ref);
				}
			}
			return $this->mct<?php echo $objTable->ClassName ?>;
		}

		public function btnNew_Click($strFormId, $strControlId, $strParameter) {
			$this->Create($this->objCreateCallback);
		}

		public function btnView_Click($strFormId, $strControlId, $strParameter) {
			$this->View($this->get<?php echo $objTable->ClassName ?>MetaControl());
		}

		public function btnEdit_Click($strFormId, $strControlId, $strParameter) {
			$this->Edit($this->get<?php echo $objTable->ClassName ?>MetaControl(), $this->objEditCallback);
		}

		public function btnDelete_Click($strFormId, $strControlId, $strParameter) {
			$obj<?php echo $objTable->ClassName ?>MetaControl = $this->get<?php echo $objTable->ClassName ?>MetaControl();
			$obj<?php echo $objTable->ClassName ?>MetaControl->Delete<?php echo $objTable->ClassName ?>();
			if ($this->objDeleteCallback) {
				$this->objDeleteCallback->Call();
			}
		}

		public function __get($strName) {
			switch ($strName) {
				case "Label": return $this->lblToolbar;
				case "LoadBox": return $this->txtLoad;
				case "LoadButton": return $this->btnLoad;
				case "NewButton": return $this->btnNew;
				case "ViewButton": return $this->btnView;
				case "EditButton": return $this->btnEdit;
				case "DeleteButton": return $this->btnDelete;
				case "MetaControl": return $this->get<?php echo $objTable->ClassName ?>MetaControl();
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
				case "LoadCallback":
					try {
						$this->objLoadCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "EditCallback":
					try {
						$this->objEditCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CreateCallback":
					try {
						$this->objCreateCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "DeleteCallback":
					try {
						$this->objDeleteCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
