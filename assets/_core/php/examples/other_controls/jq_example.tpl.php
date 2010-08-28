<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

		<style type="text/css">
			.example {border-width: 1px; border-style: solid; border-color: #a9f; margin: 20px; padding: 20px;}
			.draggable, .resizable { background-color: #90ee90; width: 100px; height: 50px; cursor:move; }
			.droppable { color: white; background-color: green; width: 150px; height: 80px; }
			.selitem, .sortitem { background-color: #EB8F00; border: 1px solid black; margin: 3px; width: 150px;}
			.selectable, .sortable { background-color: #90ee90; width: 250px; padding: 10px; }
			.selectable .ui-selecting { background: silver; }
			.selectable .ui-selected { background: gray; }
		</style>

	<div class="instructions">
		QCubed offers a new set of experimental wrappers for all widgets that <a href="http://www.jquery.com/ui">jQuery UI</a> 
		ships with. These are simple server-side classes that allow you to create PHP objects that will later on be
		presented as jQuery widgets.<br><br>
		
		The best part is that these widgets are still QCubed controls - for example, the fancy-looking QJqButton is still 
		a QButton, and you can easily attach event handlers to it using AddAction().
	</div>

		<div class="example"><h3>Draggable</h3>
			<?php $this->Draggable->Render(); ?>
		</div>
		<div class="example"><h3>Droppable</h3>
			<?php $this->Droppable->Render(); ?>
		</div>
		<div class="example"><h3>Resizable</h3>
			<?php $this->Resizable->Render(); ?>
		</div>
		<div class="example"><h3>Selectable</h3>
			<p>Drag a box (aka lasso) with the mouse over the items.
				Items can be selected by click or drag while holding the Ctrl/Meta key, 
				allowing for multiple (non-contiguous) selections.</p>
			<?php $this->Selectable->Render(); ?>
		</div>
		<div class="example"><h3>Sortable</h3>
			<p>Drag and drop to reorder</p>
			<?php $this->Sortable->Render(); ?>
		</div>
		<div class="example"><h3>Accordion</h3>
			<?php $this->Accordion->Render(); ?>
		</div>
		<div class="example"><h3>Autocomplete</h3>
			 <p>Type "c" to test</p>
			<?php $this->Autocomplete->Render(); ?>
		</div>
		<div class="example"><h3>Ajax Autocomplete</h3>
			 <p>Type "c" to test</p>
			<?php $this->AjaxAutocomplete->Render(); ?>
		</div>
		<div class="example"><h3>Button</h3>
			<?php $this->Button->Render(); ?>
		</div>
		<div class="example"><h3>Datepicker</h3>
			<?php $this->Datepicker->Render(); ?>
		</div>
		<div class="example"><h3>DatepickerBox</h3>
			<?php $this->DatepickerBox->Render(); ?>
		</div>
		<div class="example"><h3>Dialog box - floating..</h3>
			<?php $this->Dialog->Render(); ?>
		</div>
		<div class="example"><h3>Progressbar</h3>
			<?php $this->Progressbar->Render(); ?>
		</div>
		<div class="example"><h3>Slider</h3>
			<?php $this->Slider->Render(); ?>
		</div>
		<div class="example"><h3>Tabs</h3>
			<?php $this->Tabs->Render(); ?>
		</div>

	<?php $this->RenderEnd(); ?>

<?php require('../includes/footer.inc.php'); ?>