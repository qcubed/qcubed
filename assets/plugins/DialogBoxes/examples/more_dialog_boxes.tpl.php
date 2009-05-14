<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<div class="instruction_title">Server-Side Modal Dialogs</div>
		If you ever need to build server-side "modal" dialog boxes that look better than those that you can
		<a href="../events_actions/javascript_alerts.php">make in plain HTML</a>, QCubed comes with a set of
		pre-built server-side components that might help you. Each of the classes described below is just 
		a subclass of <b>QDialogBox</b> - you can easily build one yourself after you inspect these pre-built
		ones, but these ones can save you time.<br/><br/>

		<b>QConfirmationDialog</b> is a dialog box that displays a confirmation. You couldd use it for
		something like an "are you sure you want to delete this record" dialog. When you instantiate this control,
		you pass in the name of the QForm function that you want to be called when (if) the user presses "proceed".
		You can, if you'd like, also provide a function handler for when the user presses "cancel", but if you
		don't, it's fine, too - the dialog box will disappear and the action will be essentially cancelled.
		<br /><br />
		
		<b>QTextBoxPromptDialog</b> lets you ask the user, in a modal fashion, to provide textual input. This may
		be relevant if you want to, for example, ask the user for to respond to a CAPTCHA before letting them in
		onto the next page.<br/><br/>
		
		<b>QRadioButtonPromptDialog</b> allows you to ask the user to pick one option from a set. You might want to
		do so in a scenario where, you are presenting a datagrid of items, and each item has an editable property
		that the user has to pick one value from - and the options need an explanation. This pre-built dialog box
		gives you a lightweight way to pop-up a friendly dialog that lets the user pick a value, which simultaneously
		explaining each option in detail (your real estate is no longer limited).
		<br /><br />
		
		Keep in mind that many properties of these pre-built dialog boxes can be easily overridden - including the
		styling, width/height, text of any labels, and so on. 
	</div>	
	<?php $this->bntShowDlgConfirmationPrompt->Render() ?>
	<?php $this->btnShowDlgTextPrompt->Render() ?>
	<?php $this->btnShowDlgOptionPrompt->Render() ?>
	
	<br />
		
	<?php $this->dlgConfirmationPrompt->Render() ?>
	<?php $this->dlgTextPrompt->Render() ?>
	<?php $this->dlgOptionPrompt->Render() ?>
	<br /><br />
	<?php $this->lblStatus->Render() ?>
	
	<?php $this->RenderEnd(); ?>
<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/footer.inc.php'); ?>