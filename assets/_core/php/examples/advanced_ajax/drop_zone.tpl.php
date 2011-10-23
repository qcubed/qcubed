<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">Defining Drop Zones</h1>
		<b>QControls</b> can be droppable, meaning that certain events will get triggered when a
		Moveable object is dropped on to it.
		
		You can set up a moveable control to revert to its original position after it is
		dropped. You can also tell it to revert only when dropped onto a Droppable control,
		or revert when it is NOT dropped on a Droppable control.
	</div>

	<?php $this->pnlDropZone1->Render('BackColor=#cccccc', 'Width=250', 'Height=150', 'Padding=10', 'BorderWidth=1'); ?>
	<?php $this->pnlDropZone2->Render('BackColor=#ccffee', 'Width=250', 'Height=150', 'Padding=10', 'BorderWidth=1'); ?>
	<?php $this->pnlPanel->Render('Cursor=move', 'BackColor=#eeccff', 'Width=130', 'Height=50', 'Padding=10', 'BorderWidth=1'); ?>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>