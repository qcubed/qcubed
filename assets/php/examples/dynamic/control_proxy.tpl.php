<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Using a QControlProxy to Receive Events</h1>

	<p>Sometimes you may want to create buttons, links or other HTML items which can "trigger" a Server or Ajax
		action without actually creating a QControl.  The typical example of this is if you want to dynamically
		create a large number of links or buttons (e.g. in a <strong>QDataGrid</strong> or <strong>QDataRepeater</strong>) which would trigger
		an action, but because the link/button doesn't have any other state (e.g. you'll never want to
		change its value or style, or you're comfortable doing this in pure javascript), you don't want to
		incur the overhead of creating a whole <strong>QControl</strong> for each of these links or buttons.</p>

	<p>The way you can do this is by creating a <strong>QControlProxy</strong> on your <strong>QForm</strong>, and attaching
		it to a link, button or other html item by rendering it specially.</p>

	<p>The example below illustrates the manual creation (see the code for more information) of a list of
		links which makes use of a single <strong>QControlProxy</strong> to trigger our event.  Notice that while there are many links
		and buttons which each trigger Ajax-based Actions, there is actually only 1 <strong>QControlProxy</strong>
		defined to handle all these events.</p>
</div>

<div id="demoZone">
	<p><em>QControlProxy</em>s can be rendered as links...</p>
	<p><?= $this->pxyExample->RenderAsLink('Baz', 'Baz'); ?> |
		<?= $this->pxyExample->RenderAsLink('Foo', 'Foo'); ?> |
		<?= $this->pxyExample->RenderAsLink('Blah', 'Blah'); ?> |
		<?= $this->pxyExample->RenderAsLink('Test', 'Test'); ?></p>
	<p>Or buttons...</p>
	<p><?= $this->pxyExample->RenderAsButton('Baz', 'Baz'); ?> |
		<?= $this->pxyExample->RenderAsButton('Foo', 'Foo'); ?> |
		<?= $this->pxyExample->RenderAsButton('Blah', 'Blah'); ?> |
		<?= $this->pxyExample->RenderAsButton('Test', 'Test'); ?></p>

	<p>Or embedded in any kind of tag.<p>
	<p><input type="checkbox" <?= $this->pxyExample->RenderAttributes('Test 1') ?> > |
		<span <?= $this->pxyExample->RenderAttributes('Test 2') ?> >Test 2</span>  |
		<input type="radio" <?= $this->pxyExample->RenderAttributes('Test 3') ?> >

	<?php $this->lblMessage->Render(); ?>
	<?php $this->pnlHover->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>