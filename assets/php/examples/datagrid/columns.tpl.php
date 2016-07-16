<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>
<style type="text/css">
	tr.odd_row {
		background-color: #f6f6f6;
	}

	tr.even_row {
		background-color: #ffcccc;
	}

	tr.header_row {
		background-color: #333;
		color: #ffffff;
	}

	table.simple_table td, table.simple_table th {
		padding: 5px;
	}

	table.simple_table {
		border-collapse: collapse;
		border-spacing: 0;
	}
</style>

<div id="instructions">
	<h1>QHtmlTable Columns</h1>
	<h2>ColumnTypes</h2>
	<p>The following is a quick overview of each column type you can add to a <strong>QHtmlTable</strong>.</p>
	<ul>
		<li><strong>QHtmlTableCallableColumn</strong>: this is the most versatile of the column types, and lets you
			specify a callback which returns the text of each cell in the column. The callback must be a valid PHP
			<a href="http://php.net/manual/en/language.types.callable.php">callable callback function</a>.
			The one caveat is that you cannot use PHP <strong>Closures</strong>strong> here,
			because QCubed needs to serialize everything in the form to preserve its state, and closures cannot
			be serialized.</li>
		<li><strong>QHtmlTablePropertyColumn</strong>: this is useful when the data source is an array of objects,
			and cell data can be fetched by simply calling a property on each item. A property can be a member
			variable, or a property returned by the <strong>__get</strong> PHP magic method. Properites can be chained.</li>
		<li><strong>QHtmlTableIndexedColumn</strong>: this is useful when the DataSource is an array of arrays.
			You can specify which item in the array should be drawn in the column.</li>
		<li><strong>QHtmlTableNodeColumn</strong>: If the DataSource is an array of database objects, like the array
			returned by the <strong>QueryArray</strong> method, you can
			specify what data should be displayed using a QCubed node object. For example, to display the first name of
			a person, you would enter <strong>QQN::Person()->FirstName</strong>. Nodes are chainable.</li>
		<li><strong>QVirtualAttributeColumn</strong>: If the DataSource is an array of database objects, and those
			objects also have <strong>QVirtualAttributes</strong> in them, a QVirtualAttributeColumn lets
			you easily display the value of the attribute.</li>
		<li><strong>QHtmlTableCheckBoxColumn</strong>: This column lets you display a column of checkboxes that the
			user can select, and then query later. Checkbox columns can be complex, and is described more fully in
			another example.</li>
		<li><strong>QHtmlTableLinkColumn</strong>: This column lets you display an html link (anchor tag), or a button, whose
			contents are dependent on the values in the DataSource row. Link columns can have many options for how to
			set them up, and are described more in a different example.</li>


	</ul>

	<p>Each of these columns can be created and then added to the table, and most can also be created and added in
		one step using the following shortcuts in QHtmlTable:</p>
	<ul>
		<li>CreateCallableColumn()</li>
		<li>CreateIndexedColumn()</li>
		<li>CreatePropertyColumn()</li>
		<li>CreateNodeColumn()</li>
		<li>CreateVirtualAttributeColumn()</li>
		<li>CreateLinkColumn()</li>
	</ul>

	<p>QHtmlTable lets you build quite complex HTML tables, and can also serve as a base class for fully JavaScript
		datagrid controls such as the <a href="http://www.trirand.com/blog/">jqGrid</a> and <a href="http://datatables.net/">DataTables</a> jQuery
		plugins.</p>

	<h2>First Example</h2>

	<p>The first example demonstrates how to use property and callable based columns when the DataSource is an array of objects.</p>

	<p>The first column is using a Callable, to
		compute the value of the cells.</p>

	<p>The second column uses the "LastName" property to get the value of the cells.</p>

	<h2>Second Example</h2>

	<p>The second example demonstrates how to use the indexed columns when the DataSource is an array of arrays. This is
		typically necessary in complex reports, when the data comes from external sources or cannot be easily generated with
		a simple QQuery.</p>

	<p>The first 4 columns will use an indexed access to the DataSource arrays.</p>

	<p>The last column will use "#count" as the key into the array.</p>

	<p>Of course in a real world case, these two types of columns will not be mixed - one would either use a simple
		indexed array, or a fully associative array.</p>

	<h2>Third Example</h2>

	<p>This example demonstrates how to override a column to create a complex header. HtmlTableColumn and its subclasses have
		a variety of hooks to return ids, classes and other attributes for whole rows, columns or individual cells.</p>

	<p>This example creates a colspan for the top header row of the 2nd column to span the rest of the columns.</p>


</div>

<div id="demoZone">
	<h2>Example One</h2>
	<div style="margin-left: 100px">
		<?php $this->tblPersons->Render(); ?>
	</div>

	<h2>Example Two</h2>
	<div style="margin-left: 100px">
		<?php $this->tblReport->Render(); ?>
	</div>

	<h2>Example Three</h2>
	<div style="margin-left: 100px">
		<?php $this->tblComplex->Render(); ?>
	</div>
<div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>