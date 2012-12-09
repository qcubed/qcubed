<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">Adding QControls to QDataGrids</h1>
		In the previous examples, you learned about using basic <b>QDataGrid</b>
		elements QDataGridColumn, and the all important $_ITEM. Let's now put this
		knowledge to some use: let's add some custom QControls to our datagrid. <br/><br/>
		
		The scenario is simple: you want to build a simple Employee Directory,
		showing a grid of People. For each person, you want to show their picture
		and an Edit button - with the edit button leading to the employee editing
		page.<br/><br/>
		
		Take a look at the example below. You'll see exactly this type of
		functionality. Then glance through the code; you'll be able to learn
		something from each column, let's examine them one-by-one.<br/><br/>
		
		<b>Full Name</b>: Notice how the QDataGridColumn definition contains
			this very interesting statement:<br/><br/>
					<div style="padding-left: 50px;">
						<code>
							$this->dtgPersons->AddColumn(new QDataGridColumn(<br>
									&nbsp;&nbsp;&nbsp;'Full Name',<br>
									&nbsp;&nbsp;&nbsp;'&lt;?= $_FORM->renderFullName($_ITEM) ?&gt;',<br>
									&nbsp;&nbsp;&nbsp;'HtmlEntities=false'));
						</code>
					</div>
					<br>
			Here's what it means - let's look at parameters passed to the QDataGridColumn 
			constructor. 
			<ul>
				<li>The title of the column should be <i>Full Name</i>.</li>
				<li>Whenever we want to render this column, call the renderFullName
					method of the QForm ($_FORM) that hosts the QDataGrid.
					Which QForm? This one!</li>
				<li>When calling that renderFullName method, pass in the object that's being
					rendered in the CURRENT row ($_ITEM). That object is, obviously,
					a Person object. That method, in turn, is supposed to accept a Person object 
					and return a string; the string will be rendered in our column. Take a look 
					at the implementation of that method by clicking View Source!</li>
				<li><i>HtmlEntities=false</i> means that the result that the method returns
					can contain HTML, and that the method itself will take care of
					escaping it properly (and thus protecting it against cross-site
					scripting). By default, HtmlEntities are true, so we need to turn
					them off here, as we want the first names to be displayed in
					<i>italics</i>.</li>
			</ul>
		</li>
		
		<b>Image</b> of the person. Notice how the definition of the contents of the column
		is slightly different here then what we saw for the text column: <i>'&lt;?= $_FORM->renderImage($_ITEM->Id) ?&gt'</i>.
		This is just to illustrate that the method that we'll call can accept a variety
		of parameters - in this case, just an ID of the person. Based on that ID, the
		method will have to render the image of the person.<br/><br/>
		
		Inside the renderImage() method, you'll notice that we are checking whether the
		QControl with the pre-formed ID that we create based on the ID of the person has already
		been created. If so, we don't try to re-create it - instead, we just ask it to render again.
		This is good for situations when you have to re-render the datagrid (because of an Ajax
		refresh, for example). If that control doesn't exist yet - and it won't the first time the datagrid is
		rendered - we create the QImageControl, and give it the right Control ID.<br/><br/>
		
		<b>The Edit Column</b> - i.e. the column with a custom Edit QButton - is quite
		similar in its structure to the Image column. Note that we are using an
		ActionParameter to help the click handler determine which row the user clicked on.
	</div>

	<?php $this->dtgPersons->Render(); ?>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>