<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<style type="text/css">
		div.dtp-example { margin: 10px; } 
	</style>

	<div class="instructions">
		<h1 class="instruction_title">QJqDateTimePicker: Date and time picker</h1>
		<b>QJqDateTimePicker</b> is a date and time selection control. It wraps into a QControl the <a href="http://trentrichardson.com/examples/timepicker/">timepicker addon</a>.
		All the properties of the original jQuery control are supported.
		<br/>
		Example 1 shows the control with default settings.
		<br/>
		Example 2 shows how to set the earliest and latest dates and how to change the format.
	</div>

	<div class="dtp-example">
		<strong>1. QJqDateTimePicker with default settings</strong><br/>
		<?php $this->dtp1->Render(); ?><br/>
	</div>
	<br/>

	<div class="dtp-example">
	  <strong>2. QJqDateTimePicker with MinDate, MaxDate, StepMinute, DateFormat and TimeFormat properties customized</strong><br/>
		<?php $this->dtp2->Render(); ?><br/>
	</div>

	<?php $this->RenderEnd(); ?>
<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/footer.inc.php'); ?>
