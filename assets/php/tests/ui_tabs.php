<?php
    require_once('../qcubed.inc.php');
    class NestedTabForm extends QForm
	{
		/**
		 * @var QTabs
		 */
		protected $tabs;
		/**
		 * @var QPanel
		 */
		protected $log;
		/**
		 * @var QPanel[]
		 */
		protected $panels = [];

		protected function Form_Create()
		{
			$this->log = new QPanel($this);
			$this->tabs = new QTabs($this);
			$this->tabs->Headers = ['one', 'two'];
			$this->panels[] = $this->CreatePanel("hi", $this->tabs);
			$pnl = new QPanel($this->tabs);
			$pnl->AutoRenderChildren = true;
			$this->panels[] = $pnl;
			$tabs = new QTabs($this->panels[0]);
			$tabs->Headers = ['three', 'four'];
			$this->CreatePanel("aaa2", $tabs);
			$this->CreatePanel("bbb", $tabs);
			$this->tabs->AddAction(new QTabs_ActivateEvent(), new QAjaxAction('tabs_Load'));
			//$tabs->AddAction(new QTabs_ActivateEvent(), new QAjaxAction('tabs2_Load'));
		}

		public function CreatePanel($strContent, $objTab)
		{
			$pnl = new QPanel($objTab);
			$pnl->AutoRenderChildren = true;
			$pnlContent = new QPanel($pnl);
			$pnlContent->Text = $strContent;
			return $pnl;
		}

		public function tabs_Load($strForm, $strControl, $strParam, $params)
		{
			/**
			 * @var $objControl QTabs
			 */
			$objControl = $this->GetControl($strControl);
			if (!$objControl) {
				return;
			}
			$this->log->Text = $objControl->SelectedId . ' activated';
			if ($objControl->Active == 1) {
				$pnlParent = $this->GetControl($objControl->SelectedId);
				if (count($pnlParent->GetChildControls()) > 0) {
					return;
				}
				$pnlContent = new QPanel($this->panels[1]);
				$pnlContent->Text = "there ";
				$this->tabs->Refresh();
			}
		}
	}
    NestedTabForm::Run('NestedTabForm');
