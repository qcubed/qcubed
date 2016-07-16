<?php require('../includes/header.inc.php'); ?>
<style>
	.header-row {
		color: #780000;
		background-color: #ffffff;
		font-size: 12pt;
	}
	.row {
		background-color: #efefff;
		font-size: 12pt;
	}
	.alt-row {
		background-color: #ffffff;
		font-size: 12pt;
	}
</style>

<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>An Introduction to the QHtmlTable Class</h1>

	<p>The <strong>QHtmlTable</strong> control is used to present a collection of objects or data in a grid-based
		(e.g. &lt;table&gt;) format.  All <strong>QHtmlTable</strong> objects take in a <strong>DataSource</strong>, which can be an array
		of anything (or in our example, an array of Person objects).</p>

	<p>When creating a <strong>QHtmlTable</strong>, you must create a <strong>QHtmlTableColumn</strong> for each column in your table.
		For each <strong>QHtmlTableColumn</strong> you specify its name and how it should be rendered.
		In our example below, we create a <strong>QHtmlTableCallableColumn</strong> column, which takes a
		PHP callable type, and lets you define a callback that will return the text of each cell in the column. The
		callback will be called repeatedly for each row in the table, and each time will be passed the data for the row
		it is to draw.

	<p>Finally, the <strong>QHtmlTable</strong>'s style is fully customizable, at both the column level and the row level.
		You can style the whole table with css, or specify classes for individual parts of the table. You can even specify
		how individual cells are drawn.</p>
</div>

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>