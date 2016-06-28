<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Adding Filters to Your QDataGrid</h1>

	<p>Now, we will add a filter bar to our datagrid.</p>

	<p>Enabling the filter bar is as easy as setting the datagrid's <strong>ShowFilter</strong> property to true.
		Specifying the filters to use is all that's left.</p>

	<p>While creating the datagrid's columns, you can also specify a filter to use for that column.
		These can either be custom filters for you to handle yourself, or <strong>QConditions</strong> (specifically, any
		<strong>QQConditionComparison</strong>) for your use with QQuerys. We'll be looking at the latter in this example.</p>

	<p>So the first step is defining the filters. In this example, we set up an <strong>Equal</strong> filter for the
		Person Id and <strong>Like</strong> filters for the name columns. Note that if you just want to use the default
		filter for that database column's type, you can just use the node's <strong>SetFilteredDataGridColumnFilter</strong>
		function, as shown for the Last Name column.</p>

	<p>Then, in our <strong>dtgPersons_Bind</strong> function we just make sure we use the conditions the datagrid
		provides us with. And there you have it, a filtered data grid.</p>

	<p>But know what's even easier than that? The <strong>MetaDataGrids</strong> generated for you already include these
		filters by default! Any time you use <strong>AddConnectedColumn</strong>, it will set any appropriate filters for that
		column without a single line of extra code on your part. :) See the <a href="../other/datagrid_connectors.php">
			Introduction to Meta DataGrids</a> for more information.</p>
</div>

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>