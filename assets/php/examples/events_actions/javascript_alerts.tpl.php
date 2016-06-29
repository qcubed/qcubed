<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Triggering Arbitrary JavaScript, Alerts and Confirms</h1>
	
	<p>QCubed includes several commonly used Javascript-based actions:</p>
	<ul>
		<li><b>QAlertAction</b> - to display a javascript "alert" type of dialog box</li>
		<li><b>QConfirmAction</b> - to display a javascript "confirm" type of dialog box, and execute following optional actions if the user hits "Ok"</li>
		<li><b>QJavaScriptAction</b> - to run any arbitrary javascript command(s)</li>
	</ul>
	
	<p>This example shows three different <b>QButton</b> controls which use all three of these action types.</p>
	
	<p>Specifically for the <b>QJavaScriptAction</b>, we've defined a simple <b>SomeArbitraryJavaScript()</b>
	javascript function on the page itself, so that the button has some javascript to perform.</p>
	
	<p>If you are interested in more advanced and flexible types of confirmation or prompts, see the examples at
		<a href="../advanced_ajax/dialog_box.php">Extending QPanels to Create Modal "Dialog Boxes"</a>.
	</p>
</div>

<div id="demoZone">
	<p><?php $this->btnAlert->Render(); ?></p>
	<p><?php $this->btnConfirm->Render(); ?></p>
	<p><?php $this->btnJavaScript->Render(); ?></p>
	<p><?php $this->lblMessage->Render(); ?></p>
	
	<script type="text/javascript">
		function SomeArbitraryJavaScript() {
			var strName = prompt('What is your name?');
			if (strName){ alert('Hello, ' + strName + '!'); }
		}
	</script>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>