<?php
    require_once('../qcubed.inc.php');

		class QTabsHeader extends QPanel {
			protected $objTabPanelArray = null;

			protected function RenderOutput($strOutput, $blnDisplayOutput, $blnForceAsBlockElement = false) {
				return $strOutput;
			}
			protected function GetControlHtml() {
				$strResult = '<ul>';
				foreach ($this->objTabPanelArray as $strControlId => $strTabTitle) {
					$strResult .= '<li><a href="#'.$strControlId.'"><span>'.$strTabTitle.'</span></a></li>';
				}
				$strResult .= '</ul>';
				return $strResult;
			}

			public function SetTabPanels($objTabPanelArray) {
				$this->objTabPanelArray = $objTabPanelArray;
			}
		}

    class ExampleForm extends QForm {
			protected $blnControlBasePatchedForWrapperCssClass = false;
			
			protected $Accordion;
			protected $Autocomplete;
			protected $Button;
			protected $Datepicker;
			protected $DatepickerBox;
			protected $Dialog;
			protected $Progressbar;
			protected $Slider;
			protected $Tabs;

			protected function Form_Create() {
				// Accordion
				if ($this->blnControlBasePatchedForWrapperCssClass) {
					$this->Accordion = new QAccordion($this);
					$this->Accordion->AutoRenderChildren = true;
					$this->Accordion->Header = 'div.acchead';
					$lbl = new QLinkButton($this->Accordion);
					$lbl->Text = 'Header 1';
					$lbl->WrapperCssClass = 'acchead';
					$pnl = new QPanel($this->Accordion);
					$pnl->Text = 'Section 1';
					$pnl->WrapperCssClass = 'accsec';
					$lbl = new QLinkButton($this->Accordion);
					$lbl->Text = 'Header 2';
					$lbl->WrapperCssClass = 'acchead';
					$pnl = new QPanel($this->Accordion);
					$pnl->Text = 'Section 2';
					$pnl->WrapperCssClass = 'accsec';
					$lbl = new QLinkButton($this->Accordion);
					$lbl->Text = 'Header 3';
					$lbl->WrapperCssClass = 'acchead';
					$pnl = new QPanel($this->Accordion);
					$pnl->Text = 'Section 3';
					$pnl->WrapperCssClass = 'accsec';
				}

				// Autocomplete
				$this->Autocomplete = new QAutocomplete($this);
				$this->Autocomplete->Source = array("c++", "java", "php", "coldfusion", "javascript", "asp", "ruby");

				// Button
				$this->Button = new QJqButton($this);
				$this->Button->Label = "Click me";
				$this->Button->AddAction(new QClickEvent, new QJavaScriptAction("alert('hi!')"));

				// Datepicker
				$this->Datepicker = new QDatepicker($this);

				// DatepickerBox
				$this->DatepickerBox = new QDatepickerBox($this);

				// Dialog
				$this->Dialog = new QDialog($this);
				$this->Dialog->Text = 'a non modal dialog';

				// Progressbar
				$this->Progressbar = new QProgressbar($this);
				$this->Progressbar->Value = 37;

				// Slider
				$this->Slider = new QSlider($this);

				// Tabs
				$this->Tabs = new QTabs($this);
				$this->Tabs->AutoRenderChildren = true;
				$tabsHeader = new QTabsHeader($this->Tabs);
				$tab1 = new QPanel($this->Tabs);
				$tab1->Text = 'First tab is active by default';
				$tab2 = new QPanel($this->Tabs);
				$tab2->Text = 'Tab 2';
				$tab3 = new QPanel($this->Tabs);
				$tab3->Text = 'Tab 3';
				$tabsHeader->SetTabPanels(array($tab1->ControlId => 'One', $tab2->ControlId => 'Two', $tab3->ControlId => 'Three'));
			}
		}

    ExampleForm::Run('ExampleForm');
?>