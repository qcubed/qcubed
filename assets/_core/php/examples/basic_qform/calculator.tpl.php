<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>The Four-Function Calculator: Our First Simple Application</h1>

	<p>We can combine this understanding of statefulness and events to make our first simple
		Qforms application.</p>

	<p>This calculator is just a collection of two <strong>QTextBox</strong> objects (one for each operand), a
		<strong>QListBox</strong> object containing the four arithmetic functions, a <strong>QButton</strong> object to execute
		the operation, and a <strong>QLabel</strong> to view the result.</p>

	<p>Note that there is no validation, checking, etc. currently in the QForm.  Any string data
		will be parsed by PHP to see if there is any numeric data, and if not, it will be parsed as 0.  Dividing
		by zero will throw a PHP error.</p>
</div>

<div id="demoZone">
	<p>Value 1: <?php $this->txtValue1->Render(); ?></p>

	<p>Value 2: <?php $this->txtValue2->Render(); ?></p>

	<p>Operation: <?php $this->lstOperation->Render(); ?></p>

	<?php $this->btnCalculate->Render(); ?>
	<hr/>
	<?php $this->lblResult->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>