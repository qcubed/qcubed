<?php
    require_once('../qcubed.inc.php');

/**
 * Class InjectForm
 * This is aa test of javascript injection. It tests the ability to use ajax to insert a control into a form that also
 * depends on other javascript files.
 *
 * The autocomplete2 plugin in particular requires two separate javascript files to run correctly, so its a good test
 * of the mechanism in qcubed.js that uses jQuery deferred actions to load javascript files ahead of the actions.
 */
class InjectForm extends QForm {
		protected $panel;
		protected $auto1;

		protected $btnServer;
		protected $btnAjax;

		protected function Form_Create() {
			$this->panel = new QPanel($this);
			$this->panel->AutoRenderChildren = true;
			$this->panel->SetCssStyle('border', '2px solid black');
			$this->panel->Width = 200;
			$this->panel->Height = 100;

			$this->btnServer = new QButton ($this);
			$this->btnServer->Text = 'Server Submit';
			$this->btnServer->AddAction(new QClickEvent(), new QServerAction('submit_click'));

			$this->btnAjax = new QButton ($this);
			$this->btnAjax->Text = 'Ajax Submit';
			$this->btnAjax->AddAction(new QClickEvent(), new QAjaxAction('submit_click'));
		}

		protected function submit_click($strFormId, $strControlId, $strParameter) {
			$this->insertAutoComplete();
		}

		protected function insertAutoComplete() {
			$this->auto1 = new QAutocomplete2($this->panel);
			$this->auto1->Name = 'Autocomplete';

			$a = [new QListItem ('A', 1),
				new QListItem ('B', 2),
				new QListItem ('C', 3),
				new QListItem ('D', 4)
			];

			$this->auto1->Source = $a;
		}
	}
	InjectForm::Run('InjectForm');
?>