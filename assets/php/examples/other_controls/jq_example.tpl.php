<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>jQuery Controls: Server-Side Wrappers</h1>
	
	<p>QCubed offers a new set of experimental wrappers for all widgets that <a href="http://www.jquery.com/ui">jQuery UI</a> 
	ships with. These are simple server-side classes that allow you to create PHP objects that will later on be
	presented as jQuery widgets.</p>
	
	<p>Explore the variety of these controls on this page, and proceed to the <a href="js_return_param_example.php">next tutorial</a> 
	to learn how to attach events to these controls and use them in your QForms.</p>
</div>

<div id="demoZone">
	<style type="text/css">
		.example { border: 1px solid #dedede; margin: 10px; padding: 10px;}
		.draggable, .resizable { background-color: #780000; color: #fff; cursor:move; height: 50px; padding: 10px; width: 100px; }
		.droppable { background-color: #333; color: #fff; height: 80px; width: 150px; }
		.selitem, .sortitem { background-color: #f6f6f6; border: 1px solid #dedede; margin: 3px; padding: 10px; width: 150px;}
		.selectable, .sortable { color: #333; background-color: #f6f6f6; width: 250px; padding: 10px; }
		.selectable .ui-selecting { background: #fff; color: #333; }
		.selectable .ui-selected { background: #780000; color: #fff; }
	</style>
	
	<div class="example"><h2>Draggable</h2>
		<?php $this->Draggable->Render(); ?>
	</div>
	
	<div class="example"><h2>Droppable</h2>
		<?php $this->Droppable->Render(); ?>
	</div>
	
	<div class="example"><h2>Resizable</h2>
		<?php $this->Resizable->Render(); ?>
	</div>
	
	<div class="example"><h2>Selectable</h2>
		<p>Drag a box (aka lasso) with the mouse over the items.
			Items can be selected by click or drag while holding the Ctrl/Meta key, 
			allowing for multiple (non-contiguous) selections.</p>
		<?php $this->Selectable->Render(); ?>
	</div>
	
	<div class="example"><h2>Sortable</h2>
		<p>Drag and drop to reorder</p>
		<?php $this->Sortable->Render(); ?>
	</div>
	
	<div class="example"><h2>Accordion</h2>
		<?php $this->Accordion->Render(); ?>
	</div>
	
	<div class="example"><h2>Autocomplete</h2>
		 <p>Type "c" to test</p>
		<?php $this->Autocomplete->RenderWithName(); ?>
	</div>
	
	<div class="example"><h2>Ajax Autocomplete</h2>
		 <p>Type "s" to test</p>
			<?php $this->AjaxAutocomplete->RenderWithName(); ?>
		<p>See the Autocomplete2 QCubed plugin for additional extensions to the Autocomplete control. </p>
	</div>
	
	<div class="example"><h2>Buttons</h2>
		<?php $this->Button->Render(); ?>
		<?php $this->CheckBox->Render(); ?>
		<?php $this->RadioButton->Render(); ?>
		<?php $this->IconButton->Render(); ?>
	</div>
	
	<div class="example"><h2>Lists</h2>
		<?php $this->CheckList1->RenderWithName(); ?>
		<?php $this->CheckList2->RenderWithName(); ?>
		<?php $this->RadioList1->RenderWithName(); ?>
		<?php $this->RadioList2->RenderWithName(); ?>
		<?php $this->SelectMenu->RenderWithName(); ?>
	</div>
	
	<div class="example"><h2>Datepicker</h2>
		<?php $this->Datepicker->Render(); ?>
	</div>
	
	<div class="example"><h2>DatepickerBox</h2>
		<?php $this->DatepickerBox->Render(); ?>
	</div>
	
	<div class="example"><h2>Dialog box - floating..</h2>
        <?php $this->Dialog->Render(); ?>
        <?php $this->btnShowDialog->Render(); ?>
        <?php $this->txtDlgTitle->RenderWithName(); ?>
        <?php $this->txtDlgText->RenderWithName(); ?>

	</div>
	
	<div class="example"><h2>Progressbar</h2>
		<?php $this->Progressbar->Render(); ?>
	</div>
	
	<div class="example"><h2>Slider</h2>
		<p><?php $this->Slider->Render(); ?></p>
		<p><?php $this->Slider2->Render(); ?></p>
	</div>
	
	<div class="example"><h2>Tabs</h2>
		<?php $this->Tabs->Render(); ?>
	</div>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>