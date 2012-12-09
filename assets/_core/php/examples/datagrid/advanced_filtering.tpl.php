<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">Adding More Complicated Filters to Your QDataGrid</h1>
		<p>This example will walk us through more advanced methods and types of filtering that can be done
		on your <b>QDataGrid</b>. Make sure you've got a good handle on the <a href="filtering.php">first 
		example</a> before you dive into these shark-infested waters.</p>
		
		<h2>Changing MetaColumn Filters</h2>
		<p>It's worth noting that since MetaColumns will automatically set filters for you, you may have to
		clear them first before applying new ones of your own configuration. To do so, just set the column's
		FilterType to None before setting up your new filter.</p>

		<h2>Dropdown Lists</h2>
		<p>In <b>Example A's QDataGrid</b>, we've added a column filter that's a list. This is easily accomplished
		using <b>FilterAddListItem</b> and setting the <b>FilterType</b> to <b>QFilterType::ListFilter</b>.</p>
		
		<p>This example is filled from the Project type table, which is almost certainly small enough to avoid 
		any performance problems. You could theoretically do the same with the First Name column, by
		populating the filter list with the entire collection of first names, but to do so you'd have to 
		load all the people records into memory to build that list and then you're still forcing the user to 
		find the one they want in that giant list, so a filter would lose any real performance or usability 
		benefits it had.
		
		<h2>Filter Constants</h2>
		<p>There are rare occasions where you need another filter to apply in addition to any the user supplies. 
		So for example, you want to add a <b>QQ::Equal</b> filter that doesn't depend on the user's input 
		into the filter textbox, but only when there's actually something supplied for that textbox.</p>
		
		<p>In <b>Example A</b>, the Last Name filter only considers users who also have an enabled log in.</p>

		<h2>Custom Filters</h2>
		<p>Filters aren't limited to just datagrids filled by using <b>QQuery</b> -- we've got support for custom 
		filters as well. This will allow you to use filters on <b>QDataGrids</b> filled from arrays, including 
		those created from custom SQL queries.</p>
		
		<p>If you aren't using <b>QQuery</b>, you must use the <b>FilterByCommand</b> property of the 
		<b>QDataGridColumn</b> and the <b>FilterInfo</b> property of the <b>QDataGrid</b> instead of 
		<b>Filter</b> and <b>Conditions</b> respectively. The <b>QDataGridColumn's FilterByCommand</b> property 
		must take the form of an array. When retrieving the <b>QDataGrid's FilterByCommand</b> property, you 
		will receive an array of the applied column's <b>FilterByCommands</b>, with the user's input set at 
		each array's <b>'value'</b> key.</p>
		
		<p>As a result you can pull up that same information during your <b>QDataGrid's Bind</b> function in 
		order to perform the actual filtering on the columns the user has selected.</p>
		
		<p>In <b>Example B</b>, we use raw SQL to allow a filter on an address count column.</p>
		
		<h2>Saving and Restoring Filter States</h2>
		<p>If you want to remember what filters a user has applied to a datagrid, you can use the <b>GetFilters</b>
		and <b>SetFilters</b> functions to do so. Simply call GetFilters every time the contents of the datagrid changes
		(such as during the data bind), and store that somewhere (like the session). Then, when you want to re-create
		that state (such as at Form_Create), use SetFilters to re-apply them.</p>
	</div>

	<h2>Example A</h2>
	<?php $this->dtgProjects->Render(); ?>
	<br/>
	<h2>Example B</h2>
	<?php $this->dtgCustom->Render(); ?>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
