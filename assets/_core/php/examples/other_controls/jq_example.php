<?php
    require_once('../qcubed.inc.php');

	class ExampleForm extends QForm {
		/** @var QDraggable */
		protected $Draggable;
		/** @var QDroppable */
		protected $Droppable;
		/** @var QResizable */
		protected $Resizable;
		/** @var QSelectable */
		protected $Selectable;
		/** @var QSortable */
		protected $Sortable;
	
		/** @var QAccordion */
		protected $Accordion;
		/** @var QAutocomplete */
		protected $Autocomplete;
		/** @var QAutocomplete */
		protected $AjaxAutocomplete;
		/** @var QButton */
		protected $Button;
		/** @var QDatepicker */
		protected $Datepicker;
		/** @var QDatepickerBox */
		protected $DatepickerBox;
		/** @var QDialog */
		protected $Dialog;
		/** @var QProgressbar */
		protected $Progressbar;
		/** @var QSlider */
		protected $Slider;
		/** @var QTabs */
		protected $Tabs;

		// Array we'll use to demonstrate the autocomplete functionality
		static private $LANGUAGES = array("c++", "java", "php",
			"coldfusion", "javascript", "asp", "ruby");

		protected function Form_Create() {
			// Draggable
			$this->Draggable = new QDraggable($this);
			$this->Draggable->Text = 'Drag me';
			$this->Draggable->CssClass = 'draggable';
	
			// Dropable
			$this->Droppable = new QDroppable($this);
			$this->Droppable->Text = "Drop here";
			$this->Droppable->OnDrop = new QJsClosure("alert('dropped');");
			$this->Droppable->CssClass = 'droppable';
	
			// Resizable
			$this->Resizable = new QResizable($this);
			$this->Resizable->CssClass = 'resizable';
	
			// Selectable
			$this->Selectable = new QSelectable($this);
			$this->Selectable->AutoRenderChildren = true;
			$this->Selectable->CssClass = 'selectable';
			for ($i = 1; $i <= 5; ++$i) {
				$pnl = new QPanel($this->Selectable);
				$pnl->Text = 'Item '.$i;
				$pnl->CssClass = 'selitem';
			}
			$this->Selectable->Filter = 'div.selitem';

			// Sortable
			$this->Sortable = new QSortable($this);
			$this->Sortable->AutoRenderChildren = true;
			$this->Sortable->CssClass = 'sortable';
			for ($i = 1; $i <= 5; ++$i) {
				$pnl = new QPanel($this->Sortable);
				$pnl->Text = 'Item '.$i;
				$pnl->CssClass = 'sortitem';
			}
			$this->Sortable->Items = 'div.sortitem';
	
			// Accordion
			$this->Accordion = new QAccordion($this);
			$lbl = new QLinkButton($this->Accordion);
			$lbl->Text = 'Header 1';
			$pnl = new QPanel($this->Accordion);
			$pnl->Text = 'Section 1';
			$lbl = new QLinkButton($this->Accordion);
			$lbl->Text = 'Header 2';
			$pnl = new QPanel($this->Accordion);
			$pnl->Text = 'Section 2';
			$lbl = new QLinkButton($this->Accordion);
			$lbl->Text = 'Header 3';
			$pnl = new QPanel($this->Accordion);
			$pnl->Text = 'Section 3';

			// Autocomplete

			// Both autocomplete controls below will use the mode
			// "match only on the beginning of the word"
			QAutocomplete::UseFilter(QAutocomplete::FILTER_STARTS_WITH);

			// Client-side only autocomplete
			$this->Autocomplete = new QAutocomplete($this);
			$this->Autocomplete->Source = self::$LANGUAGES;
	
			// Ajax Autocomplete
			$this->AjaxAutocomplete = new QAutocomplete($this);
			$this->AjaxAutocomplete->SetDataBinder("update_autocompleteList");

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
			$tab1 = new QPanel($this->Tabs);
			$tab1->Text = 'First tab is active by default';
			$tab2 = new QPanel($this->Tabs);
			$tab2->Text = 'Tab 2';
			$tab3 = new QPanel($this->Tabs);
			$tab3->Text = 'Tab 3';
			$this->Tabs->Headers = array('One', 'Two', 'Three');
		}

		protected function update_autocompleteList() {
			$strTyped = $this->AjaxAutocomplete->Text;
			$lst = array();
			foreach (self::$LANGUAGES as $lang) {
				if (strpos($lang, $strTyped) === 0)
					$lst[] = $lang;
			}
			$this->AjaxAutocomplete->DataSource = $lst; 
		}
	}

    ExampleForm::Run('ExampleForm');
?>