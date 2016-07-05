<?php
    require_once('../qcubed.inc.php');

    class ExampleForm extends QForm {
        protected $objPanel1;
        protected $objPanel2;
        protected $objPanel3;
        protected $objPanel4;

        protected function Form_Create() {
            /*
            * These two panels here will demonstrate even bubbling
            */
            $this->objPanel1 = new QPanel($this);
            $this->objPanel1->AutoRenderChildren = true;
            $this->objPanel1->CssClass = 'container';
            $this->objPanel1->Text = "I'm panel 1";
            $this->objPanel1->AddAction(new QClickEvent(), new QAjaxAction('objPanel1_Click'));

            $this->objPanel2 = new QPanel($this->objPanel1);
            $this->objPanel2->CssClass = 'container';
            $this->objPanel2->AddCssClass('insidePanel');
            $this->objPanel2->Text = "I'm panel 2 and I'm a child of panel 1.<br/><br/>If you click me, both my click action and panel 1's click action will fire";
            $this->objPanel2->AddAction(new QClickEvent(), new QAjaxAction('objPanel2_Click'));

            /*
            * These two panels here will demenstrate how to STOP even bubbling
            */
            $this->objPanel3 = new QPanel($this);
            $this->objPanel3->CssClass = 'container';
            $this->objPanel3->AutoRenderChildren = true;
            $this->objPanel3->Text = "I'm panel 3";
            $this->objPanel3->AddAction(new QClickEvent(), new QAjaxAction('objPanel3_Click'));

            $this->objPanel4 = new QPanel($this->objPanel3);
            $this->objPanel4->CssClass = 'container';
            $this->objPanel4->AddCssClass('insidePanel');
            $this->objPanel4->Text = "I'm panel 4 and I'm a child of panel 3.<br/><br/>If you click me only my click action will fire thanks to QStopPropagationAction";
            // Note the addition of QStopPropagationAction()
            $this->objPanel4->AddAction(new QClickEvent(), new QStopPropagationAction());
            $this->objPanel4->AddAction(new QClickEvent(), new QAjaxAction('objPanel4_Click'));

        }

        public function objPanel1_Click($strFormId, $strControlId, $strParameter) {
            QApplication::DisplayAlert('Panel 1 Clicked');
        }

        public function objPanel2_Click($strFormId, $strControlId, $strParameter) {
            QApplication::DisplayAlert('Panel 2 Clicked');
        }

        public function objPanel3_Click($strFormId, $strControlId, $strParameter) {
            QApplication::DisplayAlert('Panel 3 Clicked');
        }

        public function objPanel4_Click($strFormId, $strControlId, $strParameter) {
            QApplication::DisplayAlert('Panel 4 Clicked, panel 3 will not trigger a click');
        }

    }

    ExampleForm::Run('ExampleForm');
?>