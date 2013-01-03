<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>An Introduction to the QDataGrid Class</h1>

	<p>The <strong>QDataGrid</strong> control is used to present a collection of objects or data in a grid-based
		(e.g. &lt;table&gt;) format.  All <strong>QDataGrid</strong> objects take in a <strong>DataSource</strong>, which can be an array
		of anything (or in our example, an array of Person objects).</p>

	<p>In defining a <strong>QDataGrid</strong>, you must define a new <strong>QDataGridColumn</strong> for each column in your table.
		For each <strong>QDataGridColumn</strong> you can specify its name and how it should be rendered.
		The HTML definition in your <strong>QDataGridColumn</strong> will be rendered directly
		into your HTML output.  Inside your HTML definition, you can also specify PHP commands, methods,
		function calls and/or variables which can be used to output item-specific data.</p>

	<p>Calls to PHP can be made by using &lt;?= and ?&gt; tags (see this example's code for more
		information).  Note that these PHP short tags are being used by QCubed <em>internally</em> as delimiters
		on when the PHP engine should be used.  <strong>QDataGrid</strong> (and QCubed in general, for that matter) offers
		full support of PHP installations with <strong>php_short_tags</strong> set to off.</p>

	<p>Finally, the <strong>QDataGrid</strong>'s style is fully customizable, at both the column level and the row level.
		You can specify specific column style attributes (e.g. the last name should be in bold), and you can specify
		row attributes for all rows, just the header, and just alternating rows.</p>
</div>

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>