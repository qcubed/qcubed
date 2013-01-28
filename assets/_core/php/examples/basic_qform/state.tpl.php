<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Understanding State</h1>
	
	<p>Note that when you clicked on the button, the form actually posted back to itself.  However,
		the state of the form was remembered from one webpage view to the next.  This is known as
		<strong>FormState</strong>.</p>

	<p><strong>QForm</strong> objects, in fact, are stateful objects that maintain its state from one post to the next.</p>

	<p>In this example, we have an <strong>$intCounter</strong> defined in the form.  And basically, whenever
		you click on the button, we will increment <strong>$intCounter</strong> by one.  Note that the HTML template
		file is displaying <strong>$intCounter</strong> directly via a standard PHP <strong>print</strong> statement.</p>

	<p>Also note that session variables, cookies, etc. are <i>not</i> being used here -- only <strong>FormState</strong>.  In fact,
		you can get an idea if you do <strong>View Source...</strong> in your browser of the HTML on this page.
		You will see a bunch of cryptic letters and numbers for the <strong>Qform__FormState</strong> hidden variable.
		Those letters and numbers actually represent the serialized version of this <strong>QForm</strong> object.</p>
</div>

<div id="demoZone">
	<p>The current count is: <?php _p($this->intCounter); ?></p>
	<p><?php $this->btnButton->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>