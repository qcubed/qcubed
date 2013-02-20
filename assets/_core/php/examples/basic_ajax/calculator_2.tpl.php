<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div id="instructions">
		<h1>AJAX Calculator</h1>

		<p>To show the ease of Ajax in a slightly more complex QForm, we take our <strong>Calculator Example
		with Validation</strong> from before... and we only change <em>one word</em>.</p>
		
		<p>We change the <strong>QServerAction</strong> call to a <strong>QAjaxAction</strong> call, and now, we've
		created an Ajax-based calculator.  Note that even things like validation messages, etc.,
		will appear via Ajax and without a page refresh.</p>
	</div>

	<div id="demoZone">
		<p>Value 1: <?php $this->txtValue1->RenderWithError(); ?></p>
		
		<p>Value 2: <?php $this->txtValue2->RenderWithError(); ?></p>
		
		<p>Operation: <?php $this->lstOperation->Render(); ?></p>
		
		<?php $this->btnCalculate->Render(); ?>
		<hr />
		<?php $this->lblResult->Render(); ?>
	</div>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>