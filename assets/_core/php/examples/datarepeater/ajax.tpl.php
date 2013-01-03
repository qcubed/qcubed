<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div id="instructions">
		<h1>Simple QDataRepeater using AJAX-triggered Pagination</h1>

		<p>The main difference between a <strong>QDataGrid</strong> and a <strong>QDataRepeater</strong> is that while a
		<strong>QDataGrid</strong> is in a table
		and has a lot structure to help define how that table should be rendered, a <strong>QDataRepeater</strong>
		is basically without structure.  You simply specify a template file which will be used to
		define how you wish each <strong>Person</strong> object to be rendered.</p>

		<p>This very simple <strong>QDataRepeater</strong> has a <strong>QPaginator</strong> defined with it, and
		its <strong>UseAjax</strong> property set to true.
		With this combination, the user will be able to page through the collection of <strong>Person</strong> items
		without a page refresh.</p>

		<p>Note that because the <strong>QPaginator</strong> is rendered by the <i>form</i> (as opposed to the example
		with <strong>QDataGrid</strong> where the <i>datagrid</i> rendered the paginator), we will set the <i>form</i>
		as the paginator's parent.</p>

		<p>Also, note that QDataRepeater allows you to set <i>two</i> paginators: a <strong>Paginator</strong> and a
		<strong>PaginatorAlternate</strong>.  This is to offer listing pages which have the paginator at the
		top and at the bottom of the page.

		<p>The same variables of <strong>$_FORM</strong>, <strong>$_CONTROL</strong> and <strong>$_ITEM</strong> that
		you would have used with a <strong>QDataGrid</strong>
		are also available to you in your <strong>QDataRepeater</strong> template file.</p>
	</div>

	<div id="demoZone">
		<?php $this->dtrPersons->Paginator->Render(); ?>

		<?php $this->dtrPersons->Render(); ?>

		<?php $this->dtrPersons->PaginatorAlternate->Render(); ?>
	</div>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>