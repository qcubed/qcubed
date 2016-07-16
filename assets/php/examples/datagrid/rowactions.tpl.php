<?php require('../includes/header.inc.php'); ?>
<style>
	#dtgPersons tr.selectedStyle {
		background-color: #ffcccc;
		cursor: pointer;
	}
</style>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>The QCellClick Event</h1>

	<p>The <strong>QCellClickEvent</strong> class lets you detect clicks on cells and rows in <strong>QHtmlTable</strong> and
	<strong>QDataGrid</strong> tables. This class attaches a JavaScript event to the table which detects clicks that
	bubble up to it. It accepts a parameter that lets you specify what information to send to the action that handles the
	click event.</p>

	<p>The action parameter is a JavaScript code fragment whose 'this' variable is defined as the object clicked on.
		<strong>QCellClickEvent</strong> also has some predefined constants that let you return the row index or
		id that was clicked, the column index or id, or the cell id. It also has some helper functions &mdash;
	<strong>RowDataValue</strong> and <strong>CellDataValue</strong> &mdash; that will return the value of a 'data-*' attribute
		that is attached to the row or cell tags.</p>

	<p>In the example, you can click on any of the rows, and the id of the row clicked on will be past to the action.
		The action then looks up the person clicked on and displays the person's name in a dialog. If you examine the html
		source generated, you will see that each row is given a 'data-value' attribute that is the id of the person being clicked on.
		The <strong>QCellClickEvent</strong> reads that value and sends it to the action handler.</p>
</div>

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>