<?php
require_once('../qcubed.inc.php');

class ExampleForm extends QForm {
	protected $txtTextbox;

	protected $btnToggle;
	protected $btnHide;
	protected $btnShow;
	protected $btnBounce;
	protected $btnHighlight;
	protected $btnShake;
	protected $btnPulsate;
	protected $btnSize;
	protected $btnTransfer;

	protected function Form_Create() {
		$this->txtTextbox = new QTextbox($this);
		$this->txtTextbox->TextMode = QTextMode::MultiLine;
		$this->txtTextbox->Text = 'Click a button to start an animation.';
		$this->txtTextbox->Height = 200;

		$this->btnToggle = new QButton($this);
		$this->btnToggle->Text = "toggle";

		$this->btnShow = new QButton($this);
		$this->btnShow->Text = "show";

		$this->btnHide = new QButton($this);
		$this->btnHide->Text = "hide";

		$this->btnBounce = new QButton($this);
		$this->btnBounce->Text = "bounce";

		$this->btnHighlight = new QButton($this);
		$this->btnHighlight->Text = "highlight";

		$this->btnShake = new QButton($this);
		$this->btnShake->Text = "shake";

		$this->btnPulsate = new QButton($this);
		$this->btnPulsate->Text = "pulsate";

		$this->btnSize = new QButton($this);
		$this->btnSize->Text = "resize";

		$this->btnTransfer = new QButton($this);
		$this->btnTransfer->Text = "transfer and hide";

		$this->btnToggle->AddAction     (new QClickEvent(), new QJQToggleEffectAction($this->txtTextbox, "scale", ""));
		$this->btnHide->AddAction       (new QClickEvent(), new QJQHideEffectAction($this->txtTextbox, "blind"));
		$this->btnShow->AddAction       (new QClickEvent(), new QJQShowEffectAction($this->txtTextbox, "slide", "direction: 'up'"));
		$this->btnBounce->AddAction     (new QClickEvent(), new QJQBounceAction($this->txtTextbox, "", 300));
		$this->btnHighlight->AddAction  (new QClickEvent(), new QJQHighlightAction($this->txtTextbox, "", 2000));
		$this->btnShake->AddAction      (new QClickEvent(), new QJQShakeAction($this->txtTextbox,"",300));
		$this->btnPulsate->AddAction    (new QClickEvent(), new QJQPulsateAction($this->txtTextbox,"times:2",700));
		$this->btnSize->AddAction       (new QClickEvent(), new QJQSizeAction($this->txtTextbox,"to: {width: 100, height: 100}, scale: 'box'"));

		// 3 events, one after the other, for the Shake action.
		$this->btnTransfer->AddAction   (new QClickEvent(), new QJQShowAction($this->txtTextbox, "fast"));
		$this->btnTransfer->AddAction   (new QClickEvent(), new QJQTransferAction($this->txtTextbox, $this->btnTransfer));
		$this->btnTransfer->AddAction   (new QClickEvent(), new QJQHideAction($this->txtTextbox, "fast"));
	}
}

ExampleForm::Run('ExampleForm');
?>