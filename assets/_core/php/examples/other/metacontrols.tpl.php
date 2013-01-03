<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Generated MetaControl Objects</h1>
	As you build out more and more database-driven <strong>QForms</strong> and <strong>QPanels</strong>, you'll notice
	that you still may spend quite a bit of wasted time coding the same type of Control
	definition, setup and data binding procedures over and over again. This becomes
	especially tedious when you are talking about modifying objects with a large
	number of fields.</p>

<p>Utilizing QControls and the code generator, QCubed can generate <strong>MetaControl</strong> classes for each
	of your ORM classes. <strong>MetaControls</strong> are essentially classes which contains functionality
	to simplify the <strong>QControl</strong> creation/definition, setup and data binding process for you.</p>

<p>Essentially, for each field in a class, you can have the <strong>MetaControl</strong> return for you a data bound
	and setup <strong>QControl</strong> for editing, or even a <strong>QLabel</strong> just for viewing.  But because these MetaControls
	are simply returning standard QControls, you can then modify them (stylizing, adding events, etc.) as you normally would
	any other control.</p>

<p>You'll note in the PHP code that while it doesn't appear that we save that much in terms of Lines of Code,
	you will note that some of the more tedious, non application-specific code of literally making calls like
	<strong>$this->txtFirstName = new QTextBox($this)</strong> and setting up the <strong>Text</strong>, <strong>Required</strong> and <strong>Name</strong> properties
	of <strong>$txtFirstName</strong> is now done for you.</p>

<p>And because the <strong>MetaControl</strong> will be able to keep track <i>which</i> controls have been generated
	thus far, you can call
	(for example) <strong>SavePerson()</strong> on the <strong>MetaControl</strong>, and it will smartly go through any controls
	created thus far and bind the data back to the Person object.</p>

<p>We show this in our example below, where we have clickable labels and hidden textboxes to
	aid with the viewing and/or editing of Person #1.</p>

<p>Finally, note that because <strong>MetaControls</strong> encapsulate all the functionality for a given
	instance of a given object, and because it is able to keep track of and maintain its own
	set of controls, you can easily have multiple <strong>MetaControls</strong> on any <strong>QForm</strong> or <strong>QPanel</strong>,
	if you want to view or edit multiple objects of any class at the same time.</p>
</div>

<div id="demoZone">
	<p>Click on any label to edit:</p>
	<?php $this->lblFirstName->RenderWithName(); ?><?php $this->txtFirstName->RenderWithName(); ?>
	<?php $this->lblLastName->RenderWithName(); ?><?php $this->txtLastName->RenderWithName(); ?>

	<p>
		<?php $this->btnSave->Render(); ?>
		<?php $this->btnCancel->Render(); ?>
	</p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>