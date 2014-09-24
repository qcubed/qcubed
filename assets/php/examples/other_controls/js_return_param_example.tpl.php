<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
		<h1>jQuery Controls: Adding Actions</h1>
		
		<p>QCubed offers a new set of experimental wrappers for all widgets that <a href="http://www.jquery.com/ui">jQuery UI</a>
		ships with. These are simple server-side classes that allow you to create PHP objects that will later on be
		presented as jQuery widgets.</p>
		
		<p>In the <a href="jq_example.php">previous example</a>, you saw the breadth of these controls; now let's dive in and see how to use them.</p>
		
		<p>These widgets are still QCubed controls - for example, the fancy-looking <b>QJqButton</b> is still 
		a <b>QButton</b>, and you can easily attach event handlers to it using <b>AddAction()</b>. The following examples show possibilities to post 
		data back to the server.</p>

		<p>There are three ways to return JavaScript objects / arrays to the server side on Ajax/Server actions:</p>
		<ol>
			<li>Use a <b>QJsClosure</b> as an <b>ActionParameter</b>: pass a string containing the JavaScript to 
				return an object/array to the constructor of <b>QJsClosure</b>. Note that a <b>QJsClosure</b> actually creates a function - so a return statement 
				should be included! For example:
				 <div style="padding-left: 50px">
					<code>$objControl->ActionParameter = new QJsClosure("return this.id;");</code>
				</div>
			</li>
			<li style="margin-top: 10px; margin-bottom: 10px">Pass the string defining the JavaScript object to QAjaxAction, QServerAction, QAjaxControlAction or 
				QServerControlAction as the last parameter. For example: 
				 <div style="padding-left: 50px">
					<code>
				$strJsParam = '{<br>
					&nbsp;&nbsp;&nbsp;&nbsp;"width": $j("#' . $this->Resizable->ControlId . '").width(), <br>
					&nbsp;&nbsp;&nbsp;&nbsp;"height": $j("#' . $this->Resizable->ControlId . '").height() <br>
				}';<br>
				$objControl->AddAction(new QResizable_StopEvent(), new QAjaxAction("onResize", "default", null, $strJsParam));	<br>
					</code>
				</div>
			</li>
			<li>Create a custom event derived from QEvent that has a constant property called <b>JsReturnParam</b>, e.g.		    
			 <pre><code>class MyQSlider_ChangeEvent extends QEvent {<br>
	const EventName = 'slidechange';<br>
	const JsReturnParam = 'arguments[1].value';<br>
}</code></pre>
			</li>
		</ol>	
		<p>View the source of this example to see all three approaches in action.</p>
		
		<p>NOTE: An object/array JavaScript string passed as a parameter to an action overrides the
		JsReturnParam of the event and the ActionParameter (if defined). A JsReturnParam defined by an event 
		overrides the ActionParameter (if defined).</p>
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
	<div class="example"><h3>Server action + JavaScript return parameters</h3>
		<?php $this->btnSubmit->Render();?>
		<div class="example">
			<?php $this->SubmitResult->Render();?>
		</div>
	</div>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>