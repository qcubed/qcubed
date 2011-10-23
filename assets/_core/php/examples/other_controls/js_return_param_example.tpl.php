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

		The following examples show possibilities to post data back to the servers
		There are three ways to return javascript objects / arrays to the server side on Ajax/Server actions:<br/>
		<p>* Use a QJsClosure as an ActionParameter:<br/>
		     pass a string containing the javascript to return an object/array to the constructor of QJsClosure <br/>
			 Note: a QJsClosure creates actually a function - so a return statement should be included!
			 i.e.: new QJsClosure("return this.id;");</p>
		<p>* Pass the string defining the javascript object to QAjaxAction, QServerAction, QAjaxControlAction or QServerControlAction as the last parameter</p>
		<p>* create a custom event derived from QEvent <br/>
		     do this by adding a constant member called JsReturnParam to the event <br/>
		     i.e.: const JsReturnParam = "this.id"; </p>
		<p>
		An object/array js string passed as a parameter to an Ajax/Server action overrides the
		JsReturnParam of the event and the ActionParameter (if defined)
		a JsReturnParam defined by an event overrides the ActionParameter (if defined)</p>

	</div>

		<div class="example"><h3>Slider</h3>
			<?php $this->Slider->Render(); ?>
			<div class="example">
				<?php $this->SliderResult->Render();?>
			</div>
		</div>
		<div class="example"><h3>Resizable</h3>
			<?php $this->Resizable->Render(); ?>
			<div class="example">
				<?php $this->ResizableResult->Render();?>
			</div>
		</div>
		<div class="example"><h3>Selectable</h3>
			<p>Drag a box (aka lasso) with the mouse over the items.
				Items can be selected by click or drag while holding the Ctrl/Meta key,
				allowing for multiple (non-contiguous) selections.</p>
			<?php $this->Selectable->Render(); ?>
			<div class="example">
				<?php $this->SelectableResult->Render();?>
			</div>
		</div>
		<div class="example"><h3>Sortable</h3>
			<p>Drag and drop to reorder</p>
			<?php $this->Sortable->Render(); ?>
			<div class="example">
				<?php $this->SortableResult->Render();?>
			</div>
			<p>Drag and drop to reorder + Drag to me from the previous list</p>
			<?php $this->Sortable2->Render(); ?>
			<div class="example">
				<?php $this->Sortable2Result->Render();?>
			</div>
		</div>
		<div class="example"><h3>Server action + javascript return parameters</h3>
			<?php $this->btnSubmit->Render();?>
			<div class="example">
				<?php $this->SubmitResult->Render();?>
			</div>
		</div>


	<?php $this->RenderEnd(); ?>

<?php require('../includes/footer.inc.php'); ?>
