<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

	<div id="instructions">
		<h1>Generated List Objects</h1>

		<p>Although the <em>concept</em> is known as a List <strong>Connector</strong> ... the generated <strong>ListConnector</strong> objects
			are subclasses <strong>QControl</strong> objects that are designed for listing database tables.
			<strong>QDataGrid</strong> objects are the default control for this, but other types of controls are possible. (Note that this is different
			than a <strong>ModelConnector</strong>, which is <em>/not</em> a control, but is in fact a single data object
			and a collection of controls that can be generated from and linked to it.)</p>

		<p>Using simple string properties or more complex (and more powerful) <strong>QCubed Query Nodes</strong>, you can
			add any column (even columns from linked tables) to the datagrid, and the Connector functionality
			will automatically take care of things like the column's <strong>Title</strong>, <strong>Html</strong>, <strong>Filter</strong> and
			<strong>Sorting</strong> properties.</p>

		<p>It even comes with its own <strong>DataBinder()</strong>, and the list connector is already set up to use that
			as its databinder (but of course, even this is override-able). It's also very easy to specify
			a condition on the datagrid connector - you don't even need to define your own data bind function! Simply set
			the <strong>AdditionalConditions</strong> property to an appropriate QQuery condition, and you're good to go. In
			this example, we'll only show projects whose status is "open". Clauses such as expand can also easily
			be applied by similarly setting the <strong>AdditionalClauses</strong> property.</p>

	</div>

	<div id="demoZone">
		<?php $this->dtgProjects->Render(); ?>
	</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
