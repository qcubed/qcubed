<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Rollover Images using QImageRollover</h1>

	<p>A frequent component of a web application is an image control that changes as
	the user hovers over it with their mouse. You can find examples of this pattern
	in navigational menus that highlight when you hover over the menu items: each
	item is implemented as an image that has a mouse hover behavior implemented that
	swaps this image out for another one. That other image is basically identical, except
	that it has a "highlight" effect implemented.</p>

	<p>It's really easy to implement functionality described above with a <strong>QImageRollover</strong>
	control. Simply provide the URL's to the two images (standard and "on hover"), and QCubed
	will do the rest.</p>

	<p>To see it in action, just mouse over the image below.</p>
</div>

<div id="demoZone"><?php $this->imgMyRolloverImage->Render(); ?></div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>