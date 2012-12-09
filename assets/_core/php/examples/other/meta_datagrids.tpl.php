<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">Generated DataGrid Objects</h1>
		<p>Although the <i>concept</i> is known as a <b>Meta</b> DataGrid... the generated <b>DataGrid</b> objects
		in and of itself is just a subclass of the actual <b>QDataGrid</b> object.  (Note that this is different
		than a <b>MetaControl</b>, which is <i>not</i> a control, but is in fact a single data object
		and a collection of controls that can be generated from and linked to it.)</p>
		
		<p>A generated/meta datagrid is simply a <b>QDataGrid</b> with a bunch of <b>Meta___()</b> methods to
		allow you to easily define and add columns for a given data class.</p>
		
		<p>Using simple string properties or more complex (and more powerful) <b>QCubed Query Nodes</b>, you can
		add any column (even columns from linked tables) to the datagrid, and the meta-functionality
		will automatically take care of things like the column's <b>Title</b>, <b>Html</b>, <b>Filter</b> and 
		<b>Sorting</b> properties.</p>

		<p>It even comes with its own <b>MetaDataBinder()</b>, and the datagrid is already set up to use that
		as its databinder (but of course, even this is override-able). It's also very easy to specify
		a condition on the meta datagrid - you don't even need to define your own data bind function! Simply set
		the <b>AdditionalConditions</b> property to an appropriate QQuery condition, and you're good to go. In
		this example, we'll only show projects whose status is "open". Clauses such as expand can also easily
		be applied by similarly setting the <b>AdditionalClauses</b> property.</p>

		<p>But again, similar to <b>MetaControls</b>, note that the datagrid is just a regular <b>QDataGrid</b> object,
		and the columns are just regular <b>QDataGridColumn</b> objects, which means that you can modify 
		the colums or the datagrid itself however you see fit.</p>
		
		<p>For example, you can change or add a filter to a meta-created <b>QDataGridColumn</b>. Just remember
		that if there's already one auto-generated for you, you may want to clear it first
		by changing the <b>FilterType</b> to <b>None</b> before applying your new filter to it. This
		is particularly important when switching to a <b>ListFilter</b> type from a <b>TextFilter</b> type
		because the old text filter would otherwise show up as an entry in the dropdown labeled "0".</p>
	</div>

	<?php $this->dtgProjects->Render(); ?>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>