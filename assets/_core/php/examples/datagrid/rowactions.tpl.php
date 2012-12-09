<?php require('../includes/header.inc.php'); ?>
<style>
#dtgPersons tr.selectedStyle {
	background-color: #ffaacc;
	cursor: pointer;
}
</style>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">The QDataGrid Row Actions</h1>
		Adding actions to data grid row is very similar to adding actions
		to any other <b>QControl</b>. In a <b>QControl</b>, we use the
		<b>AddAction()</b> method for adding actions. For data grid rows
		we'll use <b>QDataGrid</b>'s <b>AddRowAction()</b> method exactly
		the same way. In a <b>QControl</b>, we use the <b>ActionParameter</b>
		property to pass parameters to the action. For the data grid, we'll
		use the <b>QDataGrid</b>'s <b>RowActionParameterHtml</b> property to
		pass parameters to the row actions. In fact, the <b>RowActionParameterHtml</b>
		uses the same technique and the same variables as seen in the
		<a href="variables.php">QDataGrid variables example.</a><br/><br/>

		In the example below, we'll make the data grid rows respond to mouse
		movements and to clicks anywhere in the row. To achieve it, we'll need
		to add 3 actions to our data grid: one for mouse over, one for mouse out,
		and one for click. We'll use the <b>QCssClassAction</b> action to add a
		CSS class when the mouse is over a row or goes out of the row. We'll
		use a standard <b>QAjaxAction</b> for the click handler to call our
		QForm's method which will simply tell us which row we just clicked on.
	</div>

		<?php $this->dtgPersons->Render(); ?>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
