<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<style>
	.ui-effects-transfer { border: 2px dotted #780000; }
</style>

<div id="instructions">
	<h1>jQuery Effects</h1>

	<p>QCubed comes with built-in support of jQuery effects.
		<a href="http://jquery.com" title="jQuery website">jQuery</a> is one of the most popular
		JavaScript libraries out there; the native integration with it
		allows for some really nice effects to show/hide/animate various HTML
		elements on a page, as well as QCubed controls.</p>

	<p>To see this in action: in the example below, use the buttons to apply
		effects on the <strong>QLabel</strong> control. To make it happen, use the
		following QJQ (abbreviation for QCubed jQuery) actions in your code: </p>

	<h2>To control visibility:</h2>
	<ul>
		<li><strong>QJQShowAction</strong>: show a control (if it's hidden)</li>
		<li><strong>QJQShowEffectAction</strong>: show a control using one of the additional effects</li>
		<li><strong>QJQHideAction</strong>: hide a control</li>
		<li><strong>QJQHideEffectAction</strong>: hide a control using one of the additional effects</li>
		<li><strong>QJQToggleAction</strong>: toggle visibility of a control</li>
		<li><strong>QJQToggleEffectAction</strong>: toggle visibility of a control using one of the additional effects</li>
	</ul>

	<h2>To perform animations:</h2>
	<ul>
		<li><strong>QJQBounceAction</strong>: make a control bounce up and down</li>
		<li><strong>QJQShakeAction</strong>: make a control shake left and right</li>
		<li><strong>QJQHighlightAction</strong>: highlight a control</li>
		<li><strong>QJQPulsateAction</strong>: pulsate the contents of a control</li>
		<li><strong>QJQSizeAction</strong>: resize a control</li>
		<li><strong>QJQTransferAction</strong>: transfer the border of a control to another control</li>
	</ul>

	<p>More information on the parameters of each of the available animations
		can be found on the <a target="_blank" href="http://docs.jquery.com/UI/Effects">JQuery UI Effects</a> site.</p>
</div>

<div id="demoZone">
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
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>