<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>The QImageFileAsset Control</h1>

	<p>The <strong>QImageFileAsset</strong> is a control to handle upload image files.</p>

	<p>This control enable optional limit on size for width and height. If control is required and limits were set the control will throw an
	exeption if the user try to upload and image outside these limits. Otherwise this control will accept any Image File.</p>
</div>

<div id="demoZone">
	<style type="text/css">
		.image_canvas {border-width: 4px; border-style: solid; border-color: #a9f;}
	</style>
	<p><?php $this->ifaSample->RenderWithError(); ?></p>
	<p><?php $this->lblMessage->Render(); ?></p>
	<p><?php $this->btnButton->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>