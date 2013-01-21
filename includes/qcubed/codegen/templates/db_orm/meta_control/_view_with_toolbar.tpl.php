<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS_GEN__ ?>" TargetFileName="<?php echo $objTable->ClassName ?>ViewWithToolbarGen.class.php"/>
<?php print("<?php\n"); ?>
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>Toolbar.class.php');

	/**
	 * @property-read <?php echo $objTable->ClassName ?>Toolbar $Toolbar
	 * @property-read <?php echo $objTable->ClassName ?>ViewPanel $ViewPanel
	 * @property-read <?php echo $objTable->ClassName ?>MetaControl $MetaControl
	 */
	class <?php echo $objTable->ClassName ?>ViewWithToolbarGen extends QPanel {
		/** @var <?php echo $objTable->ClassName ?>Toolbar */
		protected $pnlToolbar;
		/** @var <?php echo $objTable->ClassName ?>ViewPanel */
		protected $pnlView;
		/** @var boolean */
		protected $blnShowPk;

		public function __construct($objParentObject, $obj<?php echo $objTable->ClassName ?>Ref = null, $blnNew = false, $blnEdit = true, $blnDelete = true, $blnShowPk = true, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$this->AutoRenderChildren = true;
			$this->blnShowPk = $blnShowPk;

			if ($obj<?php echo $objTable->ClassName ?>Ref) {
				$this->pnlToolbar = new <?php echo $objTable->ClassName ?>Toolbar($this, $obj<?php echo $objTable->ClassName ?>Ref, $blnNew, $blnEdit, $blnDelete);
				$this->pnlView = new <?php echo $objTable->ClassName ?>ViewPanel($this, $obj<?php echo $objTable->ClassName ?>Ref, $blnShowPk);
			} else {
				$this->pnlToolbar = new <?php echo $objTable->ClassName ?>Toolbar($this, $obj<?php echo $objTable->ClassName ?>Ref, $blnNew, false, false);
			}
			$this->pnlToolbar->LoadCallback = new QMethodCallback($this, 'ReloadView');
			$this->pnlToolbar->CreateCallback = new QMethodCallback($this, 'ReloadView');
			$this->pnlToolbar->EditCallback = new QMethodCallback($this, 'ReloadView');
			$this->pnlToolbar->DeleteCallback = new QMethodCallback($this, 'ReloadView');
		}

		public function ReloadView($obj<?php echo $objTable->ClassName ?> = null) {
			if ($this->pnlView && $obj<?php echo $objTable->ClassName ?>) {
				$this->pnlView->MetaControl->Refresh();
			}
		}

		public function __get($strName) {
			switch ($strName) {
				case "Toolbar": return $this->pnlToolbar;
				case "ViewPanel": return $this->pnlView;
				case "MetaControl": return $this->pnlToolbar->MetaControl;
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
