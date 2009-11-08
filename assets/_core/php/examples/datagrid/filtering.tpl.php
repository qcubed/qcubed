<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">Adding Filters to Your QDataGrid</h1>
		Now, we will add a filter bar to our datagrid.<br/><br/>

		Enabling the filter bar is as easy as setting the datagrid's <b>ShowFilter</b> property to true. 
		Specifying the filters to use is all that's left.<br/><br/>
		
		While creating the datagrid's columns, you can also specify a filter to use for that column.
		These can either be custom filters for you to handle yourself, or <b>QConditions</b> (specifically, any 
		<b>QQConditionComparison</b>) for your use with QQuerys. We'll be looking at the latter in this example.<br/><br/>
		
		So the first step is defining the filters. In this example, we set up an <b>Equal</b> filter for the 
		Person Id and <b>Like</b> filters for the name columns. Note that if you just want to use the default
		filter for that database column's type, you can just use the node's <b>SetFilteredDataGridColumnFilter</b>
		function, as shown for the Last Name column.<br/><br/>
		
		Then, in our <b>dtgPersons_Bind</b> function we just make sure we use the conditions the datagrid 
		provides us with. And there you have it, a filtered data grid.<br/><br/>
		
		But know what's even easier than that? The <b>MetaDataGrids</b> generated for you already include these
		filters by default! Any time you use <b>MetaAddColumn</b>, it will set any appropriate filters for that 
		column without a single line of extra code on your part. :) See the <a href="../other/meta_datagrids.php">
		Introduction to Meta DataGrids</a> for more information.
	</div>

		<?php $this->dtgPersons->Render(); ?>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
