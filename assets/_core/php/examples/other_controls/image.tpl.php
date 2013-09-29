<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>QImageControl</h1>

	<p>This example shows off the <b>QImageControl</b> control.  It <b>REQUIRES</b> that GD be installed.  Moreover, for <strong>QImageControl</strong> support
	with JPEG, PNG and/or GIF images, it requires that GD be installed <em>with</em> those respective graphic file format libraries.</p>

	<p>The <strong>QImageControl</strong> control is capable of scaling image files from anywhere in the filesystem (not just in docroot), and displaying it as a control.
	The <strong>Width</strong> and <strong>Height</strong> properties define the maximum size of the image canvas.  While the <strong>ScaleImageUp</strong>
	and <strong>ScaleCanvasDown</strong> properties are flags that act accordingly if the original image size is smaller or ends up being smaller than the
	canvas defined by <strong>Width</strong> and <strong>Height</strong>.</p>

	<p>Similar to <strong>QImageLabel</strong>, a <strong>CacheFolder</strong> property can be specified within
	the docroot to store rendered images, which will be used in the future
	if the same image file with the same specifications is rendered again.</p>

	<p><strong>Note!</strong> It is highly recommended to set the <strong>CacheFolder</strong> property,
	otherwise, you can easily reach the filesystem maximum file name length limit for the generated image URLs.
	The limit is set to 255 by default.</p>

	<p>Also, a <strong>BackColor</strong> can be defined for the canvas, itself. An <strong>ImageType</strong> can also be specified to "convert" the image type to a different
	type (e.g. JPEG, PNG or GIF).</p>

	<p>Finally, note that any of <strong>Width</strong>, <strong>Height</strong> and <strong>ImageType</strong> can all be left blank, which would cause QCubed to
	make the best educated guesses as to what to set them to at render time.</p>
	
	<p><strong>Note:</strong> Notice that <strong>QImageControl</strong> can be constructed outside of the QForm context, allowing you to call
	<strong>RenderImage($strDestinationFilePath)</strong> independently (outside of QForms/QControls), giving a
	nice, modular class to help with standard image rescaling for image files without the need of QForms (e.g. if you want
	perform batch or back-end operations to rescale whole directories of images, etc.).</p>
</div>

<div id="demoZone">
	<style type="text/css">
		.image_canvas { border-width: 2px; border-style: dashed; border-color: #780000; }
	</style>

	<div style="overflow:hidden; position:relative">
		<div style="float: left;">
			<?php $this->txtWidth->RenderWithName('Width=50','MaxLength=3'); ?>
		</div>
		<div style="float: left; margin-left: 20px;">
			<?php $this->txtHeight->RenderWithName('Width=50','MaxLength=3'); ?>
		</div>
		<div style="float: left; margin-left: 20px;"><br /><?php $this->chkScaleCanvasDown->Render(); ?></div>
		<div style="float: left; margin-left: 20px;"><br /><?php $this->btnUpdate->Render(); ?></div>
	</div>

	<div style="margin:10px 0;"><?php $this->imgSample->Render(); ?></div>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>