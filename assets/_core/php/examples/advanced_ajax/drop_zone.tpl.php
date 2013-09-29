<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Defining Drop Zones</h1>

	<p><strong>QControls</strong> can be Droppable, meaning that certain events will get triggered when a
		Moveable object is dropped on to it.</p>

	<p>You can set up a moveable control to revert to its original position after it is
		dropped. You can also tell it to revert only when dropped onto a Droppable control,
		or revert when it is NOT dropped on a Droppable control.</p>
</div>

<div id="demoZone">
<?php $this->pnlDropZone1->Render('BackColor=#dedede', 'Width=250', 'Height=150', 'Padding=10', 'BorderWidth=1', 'CssClass=ui-corner-all'); ?>
<?php $this->pnlDropZone2->Render('BackColor=#ffeeee', 'Width=250', 'Height=150', 'Padding=10', 'BorderWidth=1', 'CssClass=ui-corner-all'); ?>
<?php $this->pnlPanel->Render('Cursor=move', 'BackColor=#f6f6f6', 'Width=130', 'Height=50', 'Padding=10', 'BorderWidth=1', 'CssClass=ui-corner-all'); ?>
</div>
<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>