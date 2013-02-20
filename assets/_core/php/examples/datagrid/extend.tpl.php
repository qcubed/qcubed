<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Customizing the QDataGrid subclass</h1>

	<p>All QDataGrids, by default, can be customized by altering the <strong>QDataGrid</strong>
		custom subclass in <strong>/includes/qform/QDataGrid.inc</strong>.  This class extends from
		the <strong>QDataGridBase</strong> class which is in the QCubed core.</p>

	<p>In the subclass, you can feel free to override rendering methods, including
		<strong>GetPaginatorRowHtml</strong>, <strong>GetHeaderRowHtml</strong>, <strong>GetDataGridRowHtml</strong> and
		<strong>GetFooterRowHtml</strong>.</p>

	<p>In our example below, we have defined a <strong>PaginatorAlternate</strong> (so that we can
		render 2 paginators for this single datagrid), then set <strong>ShowFooter</strong> to true,
		and then finally implemented our own custom <strong>GetFooterRowHtml</strong> method (which
		basically just calls <strong>GetPaginatorRowHtml</strong> with the <strong>PaginatorAlternate</strong>
		object.</p>
</div>

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>