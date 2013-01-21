<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions" class="full">
	<h1>About Sections 1 - 3</h1>

	<p>Sections 1 through 3 are dedicated to just the <strong>Code Generator</strong>.  In order
		to focus on just the code generated functionality, no <strong>QForm</strong> or <strong>QControl</strong>
		components are included in these examples.</p>

	<p>In order to illustrate what is going on in these objects, many of the examples will
		be printing/outputting data directly from the objects themselves.
		If you <strong>View Source</strong> to view the PHP source on any of these examples, you will note
		that these scripts will have inline PHP calls throughout the HTML.</p>

	<p>In general, it is <strong>not</strong> recommended that you architect the presentation layer of your PHP
		application with such seemingly haphazard integration of PHP and HTML.  But note that
		this is done here for purposes of demonstrating the <strong>Object Relational Model</strong> <em class="warning">only</em>.</p>

	<p>For more information on how to better architect the control and view layers of a QCubed-based
		application, we recommend you check out sections 4 - 10 of the examples.</p>
</div>

<style>#viewSource { display: none; }</style>

<?php require('../includes/footer.inc.php'); ?>