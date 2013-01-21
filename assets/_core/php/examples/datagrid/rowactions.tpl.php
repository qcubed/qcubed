<?php require('../includes/header.inc.php'); ?>
<style>
	#dtgPersons tr.selectedStyle {
		background-color: #ffcccc;
		cursor: pointer;
	}
</style>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>The QDataGrid Row Actions</h1>

	<p>Adding actions to data grid row is very similar to adding actions
		to any other <strong>QControl</strong>. In a <strong>QControl</strong>, we use the
		<strong>AddAction()</strong> method for adding actions. For data grid rows
		we'll use <strong>QDataGrid</strong>'s <strong>AddRowAction()</strong> method exactly
		the same way. In a <strong>QControl</strong>, we use the <strong>ActionParameter</strong>
		property to pass parameters to the action. For the data grid, we'll
		use the <strong>QDataGrid</strong>'s <strong>RowActionParameterHtml</strong> property to
		pass parameters to the row actions. In fact, the <strong>RowActionParameterHtml</strong>
		uses the same technique and the same variables as seen in the
		<a href="variables.php">QDataGrid variables example.</a></p>

	<p>In the example below, we'll make the data grid rows respond to mouse
		movements and to clicks anywhere in the row. To achieve it, we'll need
		to add 3 actions to our data grid: one for mouse over, one for mouse out,
		and one for click. We'll use the <strong>QCssClassAction</strong> action to add a
		CSS class when the mouse is over a row or goes out of the row. We'll
		use a standard <strong>QAjaxAction</strong> for the click handler to call our
		QForm's method which will simply tell us which row we just clicked on.</p>
</div>

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>