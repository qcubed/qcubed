<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Using a QControlProxy to Receive Events</h1>

	<p>Sometimes you may want to create buttons, links or other HTML items which can "trigger" a Server or Ajax
		action without actually creating a control.  The typical example of this is if you want to dynamically
		create a large number of links or buttons (e.g. in a <strong>QDataGrid</strong> or <strong>QDataRepeater</strong>) which would trigger
		an action, but because the link/button doesn't have any other state (e.g. you'll never want to
		change its value or style, or you're comfortable doing this in pure javascript), you don't want to
		incur the overhead of creating a whole <strong>QControl</strong> for each of these links or buttons.</p>

	<p>The way you can do this is by creating a <strong>QControlProxy</strong> on your <strong>QForm</strong>, and having
		any manually created links or buttons make hard-coded <strong>RenderAsEvents()</strong> method calls to
		trigger your action/event.</p>

	<p>The example below illustrates the manual creation (see the code for more information) of a list of
		links which makes use of a single <strong>QControlProxy</strong> to trigger our event.  Notice that while there are 4 links
		and 4 buttons which each trigger Ajax-based Actions, there is actually only 1 <strong>QControl</strong> (which of course is
		the <strong>QControlProxy</strong> control itself) defined to handle all these events.</p>
</div>

<div id="demoZone">
	<h2>These A HREF links can take advantage of <em>all</em> Events defined on our proxy control by using RenderAsEvents...</h2>
	<p><a href="#baz" <?php $this->pxyExample->RenderAsEvents('Baz'); ?>">Baz</a> |
		<a href="#foo" <?php $this->pxyExample->RenderAsEvents('Foo'); ?>">Foo</a> |
		<a href="#blah" <?php $this->pxyExample->RenderAsEvents('Blah'); ?>">Blah</a> |
		<a href="#test" <?php $this->pxyExample->RenderAsEvents('Test'); ?>">Test</a></p>

	<h3>Same goes for any other HTML element, like buttons...</h3>
	<p><input type="button" <?php $this->pxyExample->RenderAsEvents('Test 1') ?> value="Test #1"> |
		<input type="button" <?php $this->pxyExample->RenderAsEvents('Test 2') ?> value="Test #2"> |
		<input type="button" <?php $this->pxyExample->RenderAsEvents('Test 3') ?> value="Test #3"> |
		<input type="button" <?php $this->pxyExample->RenderAsEvents('Test 4') ?> value="Test #4"></p>

	<?php $this->lblMessage->Render(); ?>
	<?php $this->pnlHover->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>