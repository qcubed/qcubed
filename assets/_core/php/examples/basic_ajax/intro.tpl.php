<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Hello World, Revisited</h1>

	<p>This example revisits our original Hello World example to show how you can easily
	change a postback-based form and interactions into AJAX-postback based ones.</p>

	<p>Whereas before, we executed a <strong>QServerAction</strong> on the button's click, we have now changed
	that to a <strong>QAjaxAction</strong>.  Everything else remains the same.</p>

	<p>The result is the exact same interaction, but now performed Asynchronously via Ajax.  Note
	that after clicking the button, the page doesn't "refresh" -- but the label's content
	changes as defined in the PHP method <strong>btnButton_Click</strong>.</p>
</div>

<div id="demoZone">
	<p><?php $this->lblMessage->Render(); ?></p>
	<p><?php $this->btnButton->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>