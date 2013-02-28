<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Resizing Block Controls</h1>
	<p>Any control can be resizeable by simply setting the <strong>Resizable</strong> attribute to true.
		As in draggable controls, when you set <strong>Resizable</strong> to true, you can then access
		the <strong>ResizeObj</strong> attribute to get access to the JQuery UI <strong>resizable</strong> functions.</p>
</div>

<style>
	.ui-resizable-helper { border: 2px dotted #78000; }
</style>

<div id="demoZone">
	<p><?php $this->pnlLeftTop->Render('BackColor=#f6f6f6', 'BorderColor=#dedede', 'BorderWidth=1px 1px 1px 1px'); ?></p>
	<p><?php $this->txtTextbox->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>