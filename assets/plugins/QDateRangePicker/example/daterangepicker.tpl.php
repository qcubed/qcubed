<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>
	<style type="text/css">
		div.drp-example { margin: 10px; } 
		.ui-daterangepickercontain {font-size: 75%;}
	</style>

	<div class="instructions">
		<h1 class="instruction_title">QDateRangePicker: Date range picker</h1>
		<b>QDateRangePicker</b> is a date range selection control. It wraps into a QControl the excellent <a href="http://www.filamentgroup.com/lab/date_range_picker_using_jquery_ui_16_and_jquery_ui_css_framework/">Date range picker jQuery plugin</a> from Filament Group.
		Almost all the properties of the original jQuery control are supported.
		<br/>
		Example 1 shows the control with default settings.
		<br/>
		Example 2 shows how to add new <i>presets</i> and <i>preset ranges</i>. You can use one of the many predefined ones from <b>QDateRangePickerPresetRange</b>, such as <b>QDateRangePickerPresetRange::Last7Days()</b>, or you can instantiate one in your code.
		<br/>	  
		The predefined presets from <b>QDateRangePickerPreset</b>, such as <b>QDateRangePickerPreset::AllDatesBefore()</b>, can also be added or removed at will.
		<br/>
		Example 3 shows how to use <b>QDateRangePicker</b> with two inputs.
	</div>

	<div class="drp-example">
		<strong>1. QDateRangePicker with default settings</strong><br/>
		<?php $this->drp1->Render(); ?><br/>
	</div>

	<div class="drp-example">
		<strong>2. QDateRangePicker with custom presets and arrows</strong><br/>
		<?php $this->drp2->Render(); ?><br/>
	</div>

	<div class="drp-example">
		<strong>3. QDateRangePicker with two inputs</strong><br/>
		from: <?php $this->drp3->Input->Render(); ?>
		to: <?php $this->drp3->SecondInput->Render(); ?><br/>
		<?php $this->drp3->Render(); ?><br/>
	</div>

	<?php $this->RenderEnd(); ?>
<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/footer.inc.php'); ?>
