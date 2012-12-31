	<style>
	.ui-resizable-helper { border: 1px dotted #00F; }
	</style>


<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div id="instructions">
		<h1>Resizing Block Controls</h1>
		Any control can be resizeable by simply setting the <b>Resizable</b> attribute to true.
		As in draggable controls, when you set <b>Resizable</b> to true, you can then access
		the <b>ResizeObj</b> attribute to get access to the JQuery UI <b>resizable</b> functions.
	</div>

		<p><?php $this->pnlLeftTop->Render('BackColor=#f0e0ff','BorderColor=#9966cc','BorderWidth=1px 1px 1px 1px'); ?></p>
		<p><?php $this->txtTextbox->Render(); ?></p>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
