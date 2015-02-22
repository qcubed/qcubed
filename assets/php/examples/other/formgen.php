<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions" class="full">
	<h1>Integrating QForms and the Code Generator</h1>

	<p>With the understanding of how the <strong>Code Generator</strong> can generate your data
		objects which is the heart of your <strong>Model</strong>, and with the examples
		of how you can use the <strong>QForm</strong> and <strong>QControl</strong> libraries to build out your
		<strong>View</strong> and <strong>Controller</strong>, you should now understand that
		the combination of both provides you a very flexible framework that
		utilizes the MVC architecture.</p>

	<p>Please note that it is <em>very important</em> that you have a good understanding of
		<strong>QForm</strong> and <strong>QControls</strong> before moving forward, otherwise this section will
		be difficult to understand, and even more difficult to utilize.</p>

	<p>The next couple of pages will discuss the three main <strong>QForm</strong>- and <strong>QControl</strong>-related
		components that the Code Generator generates code for:</p>

	<ul>
		<li>ModelConnector Classes</li>
		<li>DataGrid Connector Classes</li>
		<li>QForm and QPanel Drafts</li>
	</ul>
</div>

<style>#viewSource { display: none; }</style>

<?php require('../includes/footer.inc.php'); ?>