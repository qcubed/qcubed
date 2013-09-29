<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Combining Controls: Image Browser</h1>

	<p>The <b>QImageBrowser</b> control demonstrates how you can combine several controls
	together to create a reusable component. It lets you browse through images 
	in a directory.</p>

	<p>Note that the <a href="http://php.net/manual/en/image.installation.php">GD module for PHP</a>
	is required for this example to work.</p>
</div>

<?php $this->RenderBegin(); ?>

<div id="demoZone">
	<link rel="stylesheet" type="text/css" href="imagebrowser.css" />
	<?php $this->imbBrowser->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>