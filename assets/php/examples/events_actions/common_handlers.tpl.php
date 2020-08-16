<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Common action handlers</h1>
	
	<p>Some common QActions like QClickEvent and QChangeEvent can be created using alias methods, this makes for cleaner code and less typing.</p>
	
	<p>View the code for the details, and for more information or for a listing of <i>all</i> the <b>QActions</b> and <b>QEvents</b>, please
	see the <b>Documentation</b> section of the QCubed website.</p>
</div>

<div id="demoZone">
	<style type="text/css">
		.panelHover { background-color: #eeeeff; border:1px solid #000078; width: 400px; padding: 10px;}
		.panelHighlight { background-color: #ffeeee; border-color: #780000; cursor: pointer;}
	</style>
	
	<table>
		<tr>
			<td colspan="2"><b>Set the Focus / Select to the Textbox</b> (Note that Select only works on QTextBox)</td>
		</tr>
		<tr>
			<td style="width:250px;"><?php $this->btnFocus->Render(); ?> <?php $this->btnSelect->Render(); ?></td>
			<td><?php $this->txtFocus->Render(); ?></td>
		</tr>
		
		<tr>
			<td colspan="2"><br/><b>normal QClickEvent on a button</b></td>
		</tr>
		<tr>
			<td style="width:250px;"><?php $this->btnRegular->Render(); ?></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2"><br/><b>QClickEvent with event parameters (delayed click and blocking call)</b></td>
		</tr>
		<tr>
			<td style="width:250px;"><?php $this->btnBlocking->Render(); ?></td>
			<td></td>
		</tr>
	</table>

	<p><?php $this->pnlHover->Render(); ?></p>
	<p>Override a single CSS property using <b>QCssAction</b>:</p>
	<p><?php $this->btnCssAction->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>