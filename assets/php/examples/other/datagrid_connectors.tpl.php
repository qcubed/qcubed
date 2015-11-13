<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

	<div id="instructions">
		<h1>Generated DataGrid Objects</h1>

		<p>Although the <em>concept</em> is known as a DataGrid <strong>Connector</strong> ... the generated <strong>DataGrid</strong> objects
			in and of itself is just a subclass of the actual <strong>QDataGrid</strong> object.  (Note that this is different
			than a <strong>ModelConnector</strong>, which is <em>/not</em> a control, but is in fact a single data object
			and a collection of controls that can be generated from and linked to it.)</p>

		<p>A generated datagrid connector is simply a <strong>QDataGrid</strong> with a bunch of <strong>Connector___()</strong> methods to
			allow you to easily define and add columns for a given data class.</p>

		<p>Using simple string properties or more complex (and more powerful) <strong>QCubed Query Nodes</strong>, you can
			add any column (even columns from linked tables) to the datagrid, and the Connector functionality
			will automatically take care of things like the column's <strong>Title</strong>, <strong>Html</strong>, <strong>Filter</strong> and
			<strong>Sorting</strong> properties.</p>

		<p>It even comes with its own <strong>ConnectorDataBinder()</strong>, and the datagrid is already set up to use that
			as its databinder (but of course, even this is override-able). It's also very easy to specify
			a condition on the datagrid connector - you don't even need to define your own data bind function! Simply set
			the <strong>AdditionalConditions</strong> property to an appropriate QQuery condition, and you're good to go. In
			this example, we'll only show projects whose status is "open". Clauses such as expand can also easily
			be applied by similarly setting the <strong>AdditionalClauses</strong> property.</p>

		<p>But again, similar to <strong>ModelConnectors</strong>, note that the datagrid is just a regular <strong>QDataGrid</strong> object,
			and the columns are just regular <strong>QDataGridColumn</strong> objects, which means that you can modify
			the colums or the datagrid itself however you see fit.</p>

		<p>For example, you can change or add a filter to a connector-created <strong>QDataGridColumn</strong>. Just remember
			that if there's already one auto-generated for you, you may want to clear it first
			by changing the <strong>FilterType</strong> to <strong>None</strong> before applying your new filter to it. This
			is particularly important when switching to a <strong>ListFilter</strong> type from a <strong>TextFilter</strong> type
			because the old text filter would otherwise show up as an entry in the dropdown labeled "0".</p>
	</div>

	<div id="demoZone">
		<?php $this->dtgProjects->Render(); ?>
	</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
