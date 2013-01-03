<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>



<div id="instructions">
	<h1>Creating Your Own Control</h1>

	<p>Many developers may want to create their own, custom QControl to perform a very specific interaction.
	Alternatively, developers may wish to utilize exernal JavaScript libraries like Dojo, Yahoo! YUI, etc.
	to create their own set of QControls with a polished "Web 2.0" shine.</p>

	<p>Whatever the case may be, QCubed makes it easy to implement custom controls, complete with javascript
	input and output hooks, within the QControl architecture.</p>

	<p>The core distribution comes with QSampleControl, which can act as a vanilla, sample starting point from
	which a developer can begin implementing his or her custom control.</p>
</div>

<div id="demoZone">
	<style type="text/css">
		.image_canvas {border-width: 4px; border-style: solid; border-color: #a9f;}
	</style>
	<?php $this->ctlCustom->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>