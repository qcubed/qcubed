<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Tree Navigation Control</h1>

	<p>This example shows off the <strong>QTreeNav</strong> control.</p>

	<p>The control uses it's own internal tree data structure, combined with Javascript/DOM caching and
		recursion to store and render the items/nodes within the tree navigation. The internal structure can
		be built before the control is rendered, or on demand as branches are expanded.</p>

	<p>Note that the <em>first</em> time you expand a node, the tree navigation item will make a <strong>postajax</strong>
		call to retrieve the child nodes for that node.  However, on subsequent expand/collapse events
		for that node, it's purely client-side (no <strong>postajax</strong> call is made).</p>

	<p>Below are two treenavs built using the two methods of building internal data. 
		Please be sure to view the <strong>tnvExample_AddItems</strong> and <strong>tnvExampleDynamic_AddItems</strong>calls in 
		the <strong>treenav.php</strong> code to see how we recurse through the includes/ filesystem directory to 
		recursively add the treenav nodes/items to the tree nav control in either situation.</p>
</div>

<div id="demoZone">
	<?php $this->tnvExample->Render(); ?>
	<hr />
	<?php $this->tnvExampleDynamic->Render(); ?>
	<p><?php $this->objDefaultWaitIcon->Render(); ?></p>
	<?php $this->pnlCode->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>