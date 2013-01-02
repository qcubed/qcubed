<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Enabling AJAX-based Sorting and Pagination</h1>

	<p>In this example, we modify our sortable and paginated <strong>QDataGrid</strong> to now
		perform AJAX-based sorting and pagination.</p>

	<p>We literally just add <em>one line</em> of code to enable Ajax.</p>

	<p>By setting <strong>UseAjax</strong> to <strong>true</strong>, the sorting and pagination features will now execute
		via Ajax.  Try it out, and notice how paging and re-sorting doesn't involve the browser
		doing a full page refresh.</p>
</div>

<div class="demo-zone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>