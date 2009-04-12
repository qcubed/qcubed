<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<div class="instruction_title">Auto-complete Textbox Controls</div>

		Frequently, you have a need to allow the user to type in a value into a textbox,
		but there are some pre-set values that you'd like the user to pick from. One way
		to solve this problem is to use a <b>QListBox</b> control; that said, when the
		list is really large - or you want to allow the user to pick values not on the list
		- you need true auto-complete functionality. 
		<br/><br/>
		
		QCubed comes with two implementations of auto-suggest, both based on jQuery:
		client-side and server-side. You can see them in action in the example below. <br/><br/>
		
		With <b>QJavaScriptAutoCompleteTextBox</b>, you define the full set of options
		at the time of control definition; that full list is included into the HTML of
		the initial page that the browser loads, and as soon as the user types something
		in the textbox, a lookup is run against that pre-cached list. Note that there's
		no roundtrip happening to the server. Note that the performance tradeoff here is
		to pay the performance cost upfront, at the initial page load time - instead of
		paying a small cost every time the user types something. <br/><br/>
		
		In contrast, <b>QAjaxAutoCompleteTextBox</b> allows you to define a true Ajax-style
		autocomplete experience. Every time the user types a few characters, an asynchronous
		request is sent to the server; the callback function you specified in the constructor
		of the control is called - and the results it returns are presented to the user. A
		good use of this Ajax-based implementation would be when the set of potential options
		is very large - you wouldn't want to download millions of options at the page load
		time with the JavaScript-based implementation.
	</div>

	Try typing the letter "J" into the textboxes below:<br/><br/>
	Client side (QJavaScriptAutoCompleteTextBox): <?php $this->txtClientSide->Render(); ?><br/><br/>
	Server side (QAjaxAutoCompleteTextBox): <?php $this->txtServerSide->Render(); ?>
		
	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>