<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div id="instructions">
		<h1>Making a Control Moveable</h1>
		Here we demonstrate the moveable controls capability of QCubed, also known as 
		"Drag and Drop". All dragging, dropping and resizing capabilities are implemented
		through an interface to JQuery UI. Seeing the examples and reviewing the documentation
		on <b>Draggable</b>, <b>Droppable</b> and <b>Resizable</b> at the <a href="http://jqueryui.com/">JQuery UI Web</a> site
		will help you understand more about these capabilities.<br/>
  <br/>

		All <b>QControls</b> are capable of being moved simply by setting the
		<b>Moveable</b> attribute of the control. Controls are also capable of being "move handles".  
		A "move handle" is anything that you can click
		which can begin execution of a move.  For example, in a standard GUI (e.g. Windows
		or the Mac OS), you cannot just click anywhere on a window to make the window move.  You
		can only click on a window's <b>Title Bar</b> to get that window to move.  So while
		the window, itself, is a moveable object, the window's <b>Title Bar</b> is the "move
		handle".  And in this case, the "move handle" is targeted to move itself as well as the
		window it is connected to.<br/><br/>
		
		In this example, we define a simple <b>QPanel</b> and make it Moveable. 
		We also have a <b>QTextBox</b> paired with a move handle. 
		If we just made the QTextBox movable, we would no longer be able to click
		in it and edit the text in the box.<br/><br/>
		
		When you make a control Moveable, you can then access the <b>DragObj</b> attribute of
		the control to get access to the <b>draggable</b> JQuery UI routines.
	</div>

	<?php $this->pnlPanel->Render('Cursor=move', 'BackColor=#eeccff', 'Width=130', 'Height=50', 'Padding=10', 'BorderWidth=1'); ?>
	<?php $this->pnlParent->Render(); ?>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>