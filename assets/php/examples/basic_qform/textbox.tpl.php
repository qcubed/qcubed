<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>The QTextBox Family of Controls</h1>

	<p><strong>QTextBox</strong> controls handle basic user input.  Different flavors of controls
	are available for various forms of basic user input.</p>

</div>

<div id="demoZone">
	<p>Basic (limited to 5 chars): <?php $this->txtBasic->RenderWithError(); ?></p>
	<p>Integer (max value of 10): <?php $this->txtInt->RenderWithError(); ?></p>
	<p>Float: <?php $this->txtFlt->RenderWithError(); ?></p>
	<p>Email: <?php $this->txtEmail->RenderWithError(); ?></p>
	<p><?php $this->btnValidate->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>