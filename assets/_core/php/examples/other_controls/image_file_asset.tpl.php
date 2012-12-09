<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<style type="text/css">
		.image_canvas {border-width: 4px; border-style: solid; border-color: #a9f;}
	</style>

	<div class="instructions">
		<h1 class="instruction_title">The QImageFileAsset Control</h1>

		The <strong>QImageFileAsset</strong> is a control to handle upload image files.
		<br/><br/>
		
		This control enable optional limit on size for width and height. If control is required and limits were set the control will throw an
		exeption if the user try to upload and image outside these limits. Otherwise this control will accept any Image File.
		<br/><br/>		
	</div>

		<p><?php $this->ifaSample->RenderWithError(); ?></p>
		<br/><br/>
		<p><?php $this->lblMessage->Render(); ?></p>
		<p><?php $this->btnButton->Render(); ?></p>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>