<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

		<style type="text/css">
			.example {border-width: 1px; border-style: solid; border-color: #a9f; margin: 20px; padding: 20px;}
		</style>

	<div class="instructions">
		QCubed offers a new set of experimental wrappers for all widgets that <a href="http://www.jquery.com/ui">jQuery UI</a> 
		ships with. These are simple server-side classes that allow you to create PHP objects that will later on be
		presented as jQuery widgets.<br><br>
		
		The best part is that these widgets are still QCubed controls - for example, the fancy-looking QJqButton is still 
		a QButton, and you can easily attach event handlers to it using AddAction().
	</div>

	<?php if ($this->blnControlBasePatchedForWrapperCssClass) { ?>
		<div class="example"><h3>Accordion:</h3>
			<?php $this->Accordion->Render(); ?>
		</div>
	<?php } ?>
		<div class="example"><h3>Autocomplete (type "c" to test):</h3>
			<?php $this->Autocomplete->Render(); ?>
		</div>
		<div class="example"><h3>Button:</h3>
			<?php $this->Button->Render(); ?>
		</div>
		<div class="example"><h3>Datepicker:</h3>
			<?php $this->Datepicker->Render(); ?>
		</div>
		<div class="example"><h3>DatepickerBox:</h3>
			<?php $this->DatepickerBox->Render(); ?>
		</div>
		<div class="example"><h3>Dialog box - floating..</h3>
			<?php $this->Dialog->Render(); ?>
		</div>
		<div class="example"><h3>Progressbar:</h3>
			<?php $this->Progressbar->Render(); ?>
		</div>
		<div class="example"><h3>Slider:</h3>
			<?php $this->Slider->Render(); ?>
		</div>
		<div class="example"><h3>Tabs:</h3>
			<?php $this->Tabs->Render(); ?>
		</div>

	<?php $this->RenderEnd(); ?>

<?php require('../includes/footer.inc.php'); ?>