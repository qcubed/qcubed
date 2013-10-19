<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>
	
	<div id="instructions">
		<h1>Dynamically Created Image Labels</h1>
		
		<p>The <strong>QImageLabel</strong> allows you to create dynamically generated images based on text strings.
		The image label can also take font, size, color and positioning attributes to allow you to
		add a great level of sophistication/visual polish to your web applications.</p>

		<p>The control <i>requires</i> the <strong>GD</strong> library.  In order for fonts to render properly, you must provide
		either a <strong>TrueType</strong> (.ttf) file or a pair of <strong>PostScript Type 1</strong> (.pfb and .afm) typeface files.  Note that rendering <strong>TrueType</strong> 
		will require the <strong>FreeType</strong> library, and rendering <strong>PostScript Type 1</strong> will require the
		<strong>T1Lib</strong> library.  See the <strong><a href="http://www.php.net/" class="bodyLink">PHP Documentation</a></strong>
		for more information.  The typeface files can either be put in the current directory, or it can be placed in
		<strong>/includes/qform/fonts</strong>.</p>
		
		<p>Note that events/actions can be defined on the image label, as we have defined a <strong>QClickEvent</strong> in our
		example <strong>QImageLabel</strong> below.</p>
		
		<p>Finally, the <strong>QImageLabel</strong> provides a great deal of functionality to help layout the text string <em>within</em>
		the image itself.  The width/height and internal positioning of the image is determined by the following steps:</p>
		<ol>
			<li>If no width/height is set, then calculate the bounding box.  Set the <strong>Width</strong> or <strong>Height</strong> to be
			the dimensions of the bounding box, plus the <strong>PaddingWidth</strong> or <strong>PaddingHeight</strong> (if specified).</li>
			<li>Otherwise, if an alignment is specified, set the internal X- or Y- coordinate of the text to match
			the requested alignment (e.g. left/center/right or top/middle/bottom)</li>
			<li>Otherwise, set the internal X- or Y- coordinate to be the explictly specified <strong>XCoordinate</strong> or
			<strong>YCoordinate</strong> value</li>
		</ol>
		
		<p>In this example, we left <strong>Width</strong> and <strong>Height</strong> unspecified, and we set the padding width
		and height at 10.</p>
		
		<p>Similar to <strong>QImageControl</strong>, a <strong>CacheFolder</strong> property can be specified within
		the docroot to store rendered images, which will be used in the future
		if the same image file with the same specifications is rendered again.</p>
		
		<p><strong>Note!</strong> It is highly recommended to set the <strong>CacheFolder</strong> property,
		otherwise, you can easily reach the filesystem maximum file name length limit for the generated image URLs.
		The limit is set to 255 by default.</p>
	</div>

<div id="demoZone">
	<p><?php $this->lblMessage->Render(); ?></p>

	<h2>Messages that this image will toggle between:</h2>
	<div>
		Message 1: <?php $this->txtMessage1->Render(); ?><br/>
		Message 2: <?php $this->txtMessage2->Render(); ?></p>
	</div>

	<div>
		Selected Font: <?php $this->lstFont->Render(); ?>
	</div>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>