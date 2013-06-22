<?php
	require_once('../qcubed.inc.php');

	// Define the Qform with all our Qcontrols
	class ExamplesForm extends QForm {
		// Local declarations of our Qcontrols
		protected $pnlPanel;
		protected $pnlDropZone1;
		protected $pnlDropZone2;

		// Initialize our Controls during the Form Creation process
		protected function Form_Create() {
			// Define the Panel
			$this->pnlPanel = new QPanel($this);
			$this->pnlPanel->Text = 'You can click on me to drag me around.';

			// Make the Panel's Positioning Absolute, and specify a starting location
			$this->pnlPanel->Position = QPosition::Absolute;
			$this->pnlPanel->Top = 40;
			$this->pnlPanel->Left = -20;

			// Make the Panel Moveable, which also creates a DragObj on the panel
			$this->pnlPanel->Moveable = true;

			// Create some larger panels to use as Drop Zones
			$this->pnlDropZone1 = new QPanel($this);
			$this->pnlDropZone1->Position = QPosition::Absolute;
			$this->pnlDropZone1->Top = 10;
			$this->pnlDropZone1->Left = 10;
			$this->pnlDropZone1->Text = 'Drop Zone 1';

			$this->pnlDropZone2 = new QPanel($this);
			$this->pnlDropZone2->Position = QPosition::Absolute;
			$this->pnlDropZone2->Top = 200;
			$this->pnlDropZone2->Left = 10;
			$this->pnlDropZone2->Text = 'Drop Zone 2';

			$this->pnlDropZone1->Droppable = true;
			$this->pnlDropZone2->Droppable = true;

			// tell drag panel to go back to original location when not dropped correctly
			$this->pnlPanel->DragObj->Revert = QDraggable::RevertInvalid;

			$this->pnlDropZone1->AddAction(new QDragDropEvent(), new QAlertAction("dropped on zone 1"));
			$this->pnlDropZone2->AddAction(new QDragDropEvent(), new QAlertAction("dropped on zone 2"));
		}
	}

	// Run the Form we have defined
	ExamplesForm::Run('ExamplesForm');
?>