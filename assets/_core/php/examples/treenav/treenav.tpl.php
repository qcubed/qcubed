<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">Tree Navigation Control</h1>

		This example shows off the <b>QTreeNav</b> control.<br/><br/>

		The control uses it's own internal tree data structure, combined with javascript/DOM caching and
		recursion to store and render the items/nodes within the tree navigation. The internal structure can
		be built before the control is rendered, or on demand as branches are expanded.<br/><br/>
		
		Note that the <i>first</i> time you expand a node, the tree navigation item will make a <b>postajax</b>
		call to retrieve the child nodes for that node.  However, on subsequent expand/collapse events
		for that node, it's purely client-side (no <b>postajax</b> call is made).<br/><br/>
		
		Below are two treenavs built using the two methods of building internal data. 
		Please be sure to view the <b>tnvExample_AddItems</b> and <b>tnvExampleDynamic_AddItems</b>calls in 
		the <b>treenav.php</b> code to see how we recurse through the includes/ filesystem directory to 
		recursively add the treenav nodes/items to the tree nav control in either situation.<br/><br/>
	</div>

	<?php $this->tnvExample->Render(); ?>
	<br clear="all" style="clear:both;" />
  	<hr />
	<?php $this->tnvExampleDynamic->Render(); ?>

	<?php $this->pnlCode->Render(); ?>
	<br clear="all" style="clear:both;" />
	<p><?php $this->objDefaultWaitIcon->Render('Position=Absolute','Top=430px','Left=40px'); ?></p>
	
	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>