<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>
<style type="text/css">
	tr.odd_row {
		background-color: #ffddff;
	}

	tr.even_row {
		background-color: #ccccff;
	}

	tr.header_row {
		background-color: #420182;
		color: #ddeeff;
	}

	table.simple_table td, table.simple_table th {
		padding: 5px;
	}

	table.simple_table {
		border-collapse: collapse;
		border-spacing: 0;
	}
	
	.instructions {
		max-height: none;
	}
</style>

<div class="instructions">
	<h1 class="instruction_title">Displaying a simple table with QSimpleTable</h1>
	The <b>QSimpleTable</b> control is similar to <b>QDataGrid</b> in that it allows to present a collection of objects
	or data in a grid-based
	(&lt;table&gt;) format. The data binding for QSimpleTable is done the same way as for QDataGrid - by simply setting
	its <b>DataSource</b> property.<br/><br/>

	Since QSimpleTable extends QPaginatedControl, all the pagination related feature are also present in this
	control.<br/><br/>

	Also, similar to QDataGrid, you must define a new <b>QSimpleTableColumn</b> for each column in your table.
	And this is where the differences with QDataGrid begin. While QDataGrid uses a string with php code in special tags
	to
	specify how the values for each cell have to be fetched from the DataSource object rows, QSimpleTable uses user
	specified
	functions (or <a href="http://php.net/manual/en/functions.anonymous.php">PHP 5.3 Closures</a>). This means that
	unlike QDataGrid, QSimpleTable <strong>does not</strong> use the PHP eval() function to calculate the cell values.
	PHP's eval(), while a very powerful tool, has many drawbacks such as potential security risks and difficulties it
	creates for optimizing compilers.<br/><br/>

	In addition to closures <b>QSimpleTableColumn</b> provides two other <i>Accessor</i>s for fetching the cell values.
	All these Accessors are explained in the examples below.<br/><br/>

	Note, that as the name indicates, QSimpleTable is very simple, it does not provide several of the features that are
	built
	into QDataGrid, such as sorting, filtering, row actions, etc.<br/><br/>

	The main reason for QSimpleTable is to serve as a base class for fully featured JavaScript datagrid controls such
	as the <a href="http://www.trirand.com/blog/">jqGrid</a> and <a href="http://datatables.net/">DataTables</a> jQuery
	plugins. QSimpleTableColumn has a simple method called FetchCellValue(), that returns the
	cell value, without any html rendering. This method makes it very easy to generate the table data in any format
	necessary
	for the javascript control (e.g. json, xml, etc).<br/><br/>

	<p>The first example demonstrates how to use different Accessors when the DataSource is an array of objects.</p>
	
	<p>The first column is using a Closure (for PHP 5.3 and later) or a user defined function (for PHP 5.2 and earlier), to
	compute the value of the cells.</p>
	<p>The second column shows that the column name itself can be used as the Accessor in which case it's used as the
	property name of the object to compute the value of the cells.</p>
	<p>The third column passes a property name explicitly as the Accessor.</p>
</div>
<div style="margin-left: 100px">
	<?php $this->tblPersons->Render(); ?>
</div>

<div class="instructions">
	<p>The second example demonstrates how to use different Accessors when the DataSource is an array of arrays. This is
	typically necessary in complex reports, when the data comes from external sources or cannot be easily generated with
	a simple QQuery.</p>
	<p>The first 4 columns will use an indexed access to the DataSource arrays.</p>
	<p>The last column will use "#count" as the key into the array.</p>
	<p>Of course in a real world case, these two types of Accessors will not be mixed - one would either use a simple
	indexed array, or a fully associative array.</p>
</div>

<div style="margin-left: 100px">
	<?php $this->tblReport->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
