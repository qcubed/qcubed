<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Adding Pagination to Your QDataGrid</h1>

	<p>Now, we will add pagination to our datagrid.</p>

	<p>In order to enable pagination, we need to define a <strong>QPaginator</strong> object and assign it to
		the <strong>QDataGrid</strong>.  Because the <strong>QPaginator</strong> will be rendered by the <strong>QDataGrid</strong>
		(instead of being rendered on the form via a <code>$this->objPaginator->Render()</code>
		call), we will set the <strong>QDataGrid</strong> as the <strong>QPaginator</strong>'s parent in the
		<strong>QPaginator</strong> constructor call.</p>

	<p>In the locally defined <strong>dtgPersons_Bind</strong> method, in addition to setting the datagrid's <strong>DataSource</strong>,
		we also give the datagrid the <strong>TotalItemCount</strong> (via a <strong>Person::CountAll</strong> call).
		And finally, when we make the <strong>Person::LoadAll</strong> call, we make sure to
		pass in the datagrid's <strong>LimitClause</strong>, which will pass the paging information
		into our <strong>LoadAll</strong> call to only retrieve the items on the page we are
		currently viewing.</p>
</div>

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>