<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<style type="text/css">
		.image_canvas {border-width: 4px; border-style: solid; border-color: #a9f;}
	</style>

	<div id="instructions">
		<h1>What time is it?</h1>

		QCubed includes several different QControls that assist with user input of dates and datetimes.
		<br/><br/>

		<b>QDateTimePicker</b> is the "default" control, in that the templates for ModelConnectors for tables with date
		or datetime columns will, by default, generate <b>QDateTimePicker</b> instances.  While not "sexy" or glamourous by
		any stretch of the imagination, it offers an immense amount of utility, in that it allows for very distinct
		control over date, time and datetime components.  By contrast, the DHTML-based <b>QCalendar</b> control offers, by definition,
		no support for any time-based component.
		<br/><br/>
		
		<b>QDateTimeTextBox</b> allows for textbox-based input of date and datetime values, utilizing QDateTime's constructor
		to parse a wide number of date and datetime formats.
		<br/><br/>
		
		And finally, <b>QCalendar</b> is a jQuery-based visual calendar picker control.
	</div>

<div id="demoZone">
	<div style="margin: 10px 0; background: #f6f6f6; border:1px solid #dedede; border-radius: 3px; display: inline-block; padding: 10px;">
		<?php $this->lblResult->Render('HtmlEntities=false'); ?>
	</div>
	<div class="ui-helper-clearfix" style="margin-bottom: 20px;">
		<div style="float: left;">
			<strong>QDateTimeTextBox</strong><br/>
			<?php $this->dtxDateTimeTextBox->Render(); ?>
			<?php $this->btnDateTimeTextBox->Render(); ?>
		</div>
		<div style="float: left; margin-left: 45px;">
			<strong>QCalendar</strong><br/>
			<?php $this->calQJQCalendar->Render(); ?>
			<?php $this->btnQJQCalendar->Render(); ?>
		</div>
	</div>
	<div class="ui-helper-clearfix">
		<div style="float: left;">
			<strong>QDateTimePicker</strong> (Date only)<br/>
			<?php $this->dtpDatePicker->Render(); ?>
			<?php $this->btnDatePicker->Render(); ?>
		</div>
		<div style="float: left; margin-left: 45px;">
			<strong>QDateTimePicker</strong> (Date and Time)<br/>
			<?php $this->dtpDateTimePicker->Render(); ?>
			<?php $this->btnDateTimePicker->Render(); ?>
		</div>
	</div>
</div>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>