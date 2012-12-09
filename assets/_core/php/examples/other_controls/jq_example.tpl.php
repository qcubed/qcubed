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
		<h1 class="instruction_title">jQuery Controls: Server-Side Wrappers</h1>
		QCubed offers a new set of experimental wrappers for all widgets that <a href="http://www.jquery.com/ui">jQuery UI</a> 
		ships with. These are simple server-side classes that allow you to create PHP objects that will later on be
		presented as jQuery widgets.<br><br>
		
		Explore the variety of these controls on this page, and proceed to the <a href="js_return_param_example.php">next tutorial</a> 
		to learn how to attach events to these controls and use them in your QForms. 
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
			<?php $this->Autocomplete1->RenderWithName(); ?>
			<?php $this->Autocomplete2->RenderWithName(); ?>
		</div>
		<div class="example"><h3>Ajax Autocomplete</h3>
			 <p>Type "s" to test</p>
			<?php $this->AjaxAutocomplete->Render(); ?>
		</div>
		<div class="example"><h3>Buttons</h3>
			<?php $this->Button->Render(); ?>
			<?php $this->CheckBox->Render(); ?>
			<?php $this->RadioButton->Render(); ?>
			<?php $this->IconButton->Render(); ?>
		</div>
		<div class="example"><h3>Lists</h3>
			<?php $this->CheckList1->RenderWithName(); ?>
			<?php $this->CheckList2->RenderWithName(); ?>
			<?php $this->RadioList1->RenderWithName(); ?>
			<?php $this->RadioList2->RenderWithName(); ?>
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
		<div style="height:10px"></div>
			<?php $this->Slider2->Render(); ?>
		</div>
		<div class="example"><h3>Tabs</h3>
			<?php $this->Tabs->Render(); ?>
		</div>

	<?php $this->RenderEnd(); ?>

<?php require('../includes/footer.inc.php'); ?>