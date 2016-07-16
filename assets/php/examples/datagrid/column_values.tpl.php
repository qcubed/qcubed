<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>
<style type="text/css">
	table.simple_table td, table.simple_table th {
		padding: 5px;
	}

	table.simple_table {
		border-collapse: collapse;
		border-spacing: 0;
	}

	table.simple_table tbody tr:nth-child(even) {
		background-color: #ffcccc;
	}
	table.simple_table tbody tr:nth-child(odd) {
		background-color: #f6f6f6;
	}

	table.simple_table thead tr {
		background-color: #333;
		color: #ffffff;
	}

	.amount {
		text-align: right;
	}

</style>

<div id="instructions">
	<h1>Styling and Formatting QHtmlTable Column Values</h1>
	<h2>Post-processing Column Values</h2>
	<p>Sometimes the value of the data for a particular column is not displayable, or you would like to further process
	the value before displaying it. All QHtmlTableColumn types have the ability for you to specify the following:</p>
	<ul>
		<li><strong>Format</strong>: If the column returns a string, will apply the Format string as an
			sprintf format string. If the column is a QDateTime type, it will apply it as a qFormat string.</li>
		<li><strong>PostMethod</strong>: If the value of the column is an object, will call the given method
		on the object, and the new value of the column will become the return value of the method.</li>
		<li><strong>PostCallback</strong>: If given, the PostCallback function will be called with the column value
		as a parameter, and the returned value will become the new column value.</li>
	</ul>
	<h2>HtmlEntities</h2>
	<p>By default, any text you provide as the column value will be passed through a call to htmlentities, so that
		it will be converted to HTML and displayed correctly in a browser. However, if your column is already returning html,
		you can turn off this processing by setting <strong>HtmlEntities</strong> to false.</p>
	<h2>Styling</h2>
	<p>There are a variety of ways to style a QHtmlTable:</p>
	<ul>
		<li>Use <strong>AddCssClass()</strong> to add a class to the control, and use css to style the html table. Modern
			css can now do most things that used to be done using row classes and javascript, including alternating
			background colors and hover effects.</li>
		<li>Use <strong>HeaderRowCssClass</strong>,  <strong>RowCssClass</strong>, and  <strong>AlternateRowCssClass</strong>
			properties to assign classes to rows in the html table.</li>
		<li>Assign a callable to the <strong>RowParamsCallback</strong> property that will return attributes for the &lt;tr&gt;
			tag for the row. The <strong>RowParamsCallback</strong> is passed the data item for the corresponding row from the DataSource,
		and should return an array that is keyed by attribute. For example, set the "class" attribute in the array to define the CSS class
		for the row. A null data item indicates a header row.</li>
		<li>Each column has a CellStyler, HeaderCellStyler, and ColStyler that can be used to specify styles and attributes
			for the cell tags in the body rows, cell tags in the header row, and the &lt;col&gt; tags if you set
			<strong>RenderColumnTags</strong> on the table.</li>
		<li>For even more control, you can assign a callable to the <strong>CellParamsCallback</strong> property to
			return html attributes for the cell tag on a row-by-row basis.</li>
	</ul>

</div>

<div id="demoZone">
	<div style="margin-left: 100px">
		<?php $this->tblProjects->Render(); ?>
	</div>
<div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>