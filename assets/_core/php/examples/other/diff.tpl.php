<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<div class="instruction_title">QObjectDiff: What fields changed in my form?</div>

		What if you want to easily tell which fields have changed in your form after the 
        user has saved it? What if you need it for some kind of logging, or an audit trail?
        <b>QObjectDiff</b> lets you do exactly that.<br /><br />
        
        Imagine that you have a form that allows different members of the team edit project
        details; you want to keep a log of all project detail modifications, so that at the
        end of the project, you could easily tell how the dates/budgets/other things have
        changed over time. So, you present the user with just a simple form - the form below;
        when they click on Save, you pass both the old AND the modified instances of the
        Project object to the <b>QObjectDiff</b>, and it spits out a detailed report on
        what fields, if any, have changed. It also tells you what the old and new values of
        those fields are, so that you can write it out nicely to your audit trail.<br /><br />
        
        Note that <b>QObjectDiff</b> can be used to compare the state of CodeGen-generated
        ORM objects, or to compare two custom objects of any class.<br /><br />
		
		There are two important limitations:
		<ul>
			<li><b>QObjectDiff</b> cannot compare private fields between the objects. This is a
			fundamental limitation of reflection in PHP5.</li>
			<li><b>QObjectDiff</b> compares the values of the actual objects only - not child
			objects. If you want to compare child objects, you'll have to call
			<b>QObjectDiff::Compare()</b>on them explicitly.</li>
		</ul>
	</div>
    
    First name:<br /><?php $this->txtFirstName->Render() ?><br /><br />
    Last name:<br /><?php $this->txtLastName->Render() ?><br /><br />
    
    <?php $this->btnCompare->Render() ?><br /><br />
    
    <?php $this->lblComparisonResult->Render() ?>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
