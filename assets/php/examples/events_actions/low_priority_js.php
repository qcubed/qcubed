<?php
	require_once('../qcubed.inc.php');
	
	class ExampleForm extends QForm {
		// Declare the DataGrid
		protected $dtgButtons;
                public $arRows=array();
                protected $intHitCnt;

		protected function Form_Create() {
			// Define the DataGrid
			$this->dtgButtons = new QDataGrid($this);

                        $this->dtgButtons->UseAjax=true;
                        $this->intHitCnt=0;

                        for($ii=1;$ii<11;$ii++) {
                            $this->arRows[]="row" . $ii;
                        }

			$col = $this->dtgButtons->CreateCallableColumn('Name', [$this, 'renderName']);
			$col->HtmlEntities=false;
			$col = $this->dtgButtons->CreateCallableColumn('Start standard priority javascript', [$this, 'renderButton']);
			$col->HtmlEntities=false;
			$col = $this->dtgButtons->CreateCallableColumn('Start low priority javascript', [$this, 'renderLowPriorityButton']);
			$col->HtmlEntities=false;
			$this->dtgButtons->SetDataBinder('dtgButtons_Bind');
		}
		
		public function renderName($rowName) {
			return "<i>" . $rowName . "</i> ";
		}
		
		public function renderLowPriorityButton($row) {
			$objControlId = "editButton" . $row . "lowPriority";
                        $objControl = $this->GetControl($objControlId);
			if (!$objControl) {
				$objControl = new QJqButton($this->dtgButtons, $objControlId);
                                $objControl->Text = true;

				$objControl->AddAction(new QClickEvent(), new QAjaxAction("renderLowPriorityButton_Click"));
			}
                        $objControl->Label = "update & low priority alert " . $this->intHitCnt;

			// We pass the parameter of "false" to make sure the control doesn't render
			// itself RIGHT HERE - that it instead returns its string rendering result.
			return $objControl->Render(false);
		}
		
		public function renderButton($row) {
			$objControlId = "editButton" . $row;
                        $objControl = $this->GetControl($objControlId);
			if (!$objControl) {
				$objControl = new QJqButton($this->dtgButtons, $objControlId);
                                $objControl->Text = true;

				$objControl->AddAction(new QClickEvent(), new QAjaxAction("renderButton_Click"));
			}
                        $objControl->Label = "update & alert " . $this->intHitCnt;

			// We pass the parameter of "false" to make sure the control doesn't render
			// itself RIGHT HERE - that it instead returns its string rendering result.
			return $objControl->Render(false);
		}
		
		public function renderButton_Click($strFormId, $strControlId, $strParameter) {
			$this->intHitCnt++;
			$this->dtgButtons->MarkAsModified();
			QApplication::ExecuteJsFunction('alert', 'alert 2: a standard priority script');
			QApplication::ExecuteJsFunction('alert', 'alert 1: a standard priority script');
			QApplication::ExecuteJsFunction('alert', 'Just updated the datagrid: the javascript for adding the css class to the buttons is not executed yet!');
			QApplication::ExecuteSelectorFunction(".ui-button", 'addClass', "ui-state-error");
		}

        public function renderLowPriorityButton_Click($strFormId, $strControlId, $strParameter) {
			$this->intHitCnt++;
			$this->dtgButtons->MarkAsModified();

			QApplication::ExecuteJsFunction('alert', 'alert 2: a low priority script',  QJsPriority::Low);
			QApplication::ExecuteJsFunction('alert', 'alert 1: a low priority script',  QJsPriority::Low);
			QApplication::ExecuteJsFunction('alert', 'Just updated the datagrid: --> the javascript for adding the css class to the buttons is executed first!',  QJsPriority::Low);
			QApplication::ExecuteSelectorFunction(".ui-button", 'addClass', "ui-state-error");

/*			QApplication::ExecuteJavaScript("alert('alert 3: a low priority script')",  QJsPriority::Low);
			QApplication::ExecuteJavaScript("alert('alert 1: a low priority script')", QJsPriority::Low);
			QApplication::ExecuteJavaScript("alert('Just updated the datagrid: --> the javascript for adding the css class to the buttons is executed first!')",  QJsPriority::Low);
			QApplication::ExecuteJavaScript('$j(".ui-button").addClass("ui-state-error")'); //change the button color: this is executed with standard priority
*/
		}

		protected function dtgButtons_Bind() {
			// We load the data source, and set it to the datagrid's DataSource parameter
			$this->dtgButtons->DataSource = $this->arRows;
		}
	}

	ExampleForm::Run('ExampleForm');
?>
