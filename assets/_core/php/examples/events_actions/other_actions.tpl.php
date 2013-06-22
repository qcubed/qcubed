<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Other Client-Side Action Types</h1>
	
	<p>Below is a sampling of just <i>some</i> of the other <b>QAction</b> types that are available to you
	as part of the core QCubed distribution.</p>
	
	<p>Notice that all of these <b>QActions</b> simply render out JavaScript to perform the action,
	so the interaction the user experience is completely done on the client-side (e.g. no server/Ajax calls here).</p>
	
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
			<td colspan="2"><br/><b>Set the Display on the Textbox</b></td>
		</tr>
		<tr>
			<td style="width:250px;"><?php $this->btnToggleDisplay->Render(); ?></td>
			<td><?php $this->txtDisplay->Render(); ?></td>
		</tr>
		<tr>
			<td colspan="2"><br/><b>Set the Enabled on the Textbox</b></td>
		</tr>
		<tr>
			<td style="width:250px;"><?php $this->btnToggleEnable->Render(); ?></td>
			<td><?php $this->txtEnable->Render(); ?></td>
		</tr>
	</table>

	<p><?php $this->pnlHover->Render(); ?></p>
	<p>Override a single CSS property using <b>QCssAction</b>:</p>
	<p><?php $this->btnCssAction->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>