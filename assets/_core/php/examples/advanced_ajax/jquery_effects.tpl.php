<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<style>
		.ui-effects-transfer { border: 2px solid #6666FF; }
	</style>

	<div class="instructions">
		<h1 class="instruction_title">jQuery Effects</h1>

		QCubed comes with built in support of jQuery effects.
		<a href="http://jquery.com">jQuery</a> is one of the most popular 
		JavaScript libraries out there; the native integration with it 
		allows for some really nice effects to show/hide/animate various HTML 
		elements on a page, as well as QCubed controls.<br /><br />
        
		To see this in action: in the example below, use the buttons to apply
		effects on the <b>QLabel</b> control. To make it happen, use the
		following QJQ (abbreviation for QCubed jQuery) actions in your code: <br /><br />
		
		To control visibility: 
		<ul>
			<li><b>QJQShowAction</b>: show a control (if it's hidden)</li>
			<li><b>QJQShowEffectAction</b>: show a control using one of the additional effects</li>
			<li><b>QJQHideAction</b>: hide a control</li>
			<li><b>QJQHideEffectAction</b>: hide a control using one of the additional effects</li>
			<li><b>QJQToggleAction</b>: toggle visibility of a control</li>
			<li><b>QJQToggleEffectAction</b>: toggle visibility of a control using one of the additional effects</li>
		</ul>
		
		To perform animations:
		<ul>
			<li><b>QJQBounceAction</b>: make a control bounce up and down</li>
			<li><b>QJQShakeAction</b>: make a control shake left and right</li>
			<li><b>QJQHighlightAction</b>: highlight a control</li>
			<li><b>QJQPulsateAction</b>: pulsate the contents of a control</li>
			<li><b>QJQSizeAction</b>: resize a control</li>
			<li><b>QJQTransferAction</b>: transfer the border of a control to another control</li>
		</ul>
		
		More information on the parameters of each of the available animations
		can be found on the <a target="_blank" href="http://docs.jquery.com/UI/Effects">
		JQuery UI Effects</a> site.
	</div>

	<?php $this->btnToggle->Render() ?>
	<?php $this->btnHide->Render() ?>
	<?php $this->btnShow->Render() ?>
	<?php $this->btnBounce->Render() ?>
	<?php $this->btnHighlight->Render() ?>
	<?php $this->btnShake->Render() ?>
	<?php $this->btnPulsate->Render() ?>
	<?php $this->btnSize->Render() ?>
	<?php $this->btnTransfer->Render() ?>

	<p><?php $this->txtTextbox->Render(); ?></p>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
