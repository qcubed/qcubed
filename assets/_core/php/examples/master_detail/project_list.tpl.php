<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div id="instructions">
		<h1>Nested QDataGrid - Drilling Into a Dataset</h1>
		In this example, we will demonstrate how to create a nested <b>QDataGrid</b>.<br/><br />

		In a top-level grid, we will list projects; for each of the projects, and through an expand/collapse button,
		we'll see the list of team members involved in the project. For each of those people, we'll be able to drill down to 
		the list of addresses that we have on file. <br/><br/>
		
		Moreover, you can enable inline editing for any of these datagrids - as demostrated in the example below 
		for the addresses. If you want to learn the basics of inline editing for datagrids, go through <a href='../dynamic/inline_editing.php'>this example</a>.<br><br>

		Some pieces to pay attention to:<br/>
		- Master QDataGrid for Project should go on the FORM. <br/>
		- Children (Team Members and Addresses) must be wrapped in a QPanel, that in turn contains a QDataGrid.		
	</div>

		
		<?php $this->dtgProjects->Render(); ?>
		
		<?php $this->RenderEnd(); ?>

<?php require('../includes/footer.inc.php'); ?>