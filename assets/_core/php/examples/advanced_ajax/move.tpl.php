<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Making a Control Moveable</h1>

	<p>Here we demonstrate the moveable controls capability of QCubed, also known as 
		"Drag and Drop". All dragging, dropping and resizing capabilities are implemented
		through an interface to jQuery UI. Seeing the examples and reviewing the documentation
		on <strong>Draggable</strong>, <strong>Droppable</strong> and <strong>Resizable</strong> at the <a href="http://jqueryui.com/">jQuery UI Web</a> site
		will help you understand more about these capabilities.</p>

	<p>All <strong>QControls</strong> are capable of being moved simply by setting the
		<strong>Moveable</strong> attribute of the control. Controls are also capable of being "move handles".  
		A "move handle" is anything that you can click
		which can begin execution of a move.  For example, in a standard GUI (e.g. Windows
		or the Mac OS), you cannot just click anywhere on a window to make the window move.  You
		can only click on a window's <strong>Title Bar</strong> to get that window to move.  So while
		the window, itself, is a moveable object, the window's <strong>Title Bar</strong> is the "move
		handle".  And in this case, the "move handle" is targeted to move itself as well as the
		window it is connected to.</p>

	<p>In this example, we define a simple <strong>QPanel</strong> and make it Moveable. 
		We also have a <strong>QTextBox</strong> paired with a move handle. 
		If we just made the QTextBox movable, we would no longer be able to click
		in it and edit the text in the box.</p>

	<p>When you make a control Moveable, you can then access the <strong>DragObj</strong> attribute of
		the control to get access to the <strong>draggable</strong> jQuery UI routines.</p>
</div>

<div id="demoZone">
	<?php $this->pnlPanel->Render('Cursor=move', 'BackColor=#f6f6f6', 'Width=130', 'Height=50', 'Padding=10', 'BorderWidth=1'); ?>
	<?php $this->pnlParent->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>