<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Learning about Validation</h1>

	<p>In this example, we extend our calculator application to include Validation.</p>

	<p>As we mentioned earlier, Qforms will go through a validation process just before it executes
		any Server-based actions, if needed.  If the Control that triggers the ServerAction has its
		<strong>CausesValidation</strong> property set to "true", then before executing the ServerAction, the Form will
		go through every visible control in the entire Form and call <strong>Validate()</strong>.  Only after ensuring
		that every control is valid, will the Form go ahead and execute the assigned ServerAction.
		Otherwise, every Control that had its <strong>Validate()</strong> fail will have its ValidationError property
		set with the appropriate error message.</p>

	<p><em>What</em> the validation checks for is dependent on the control you are using.  In general,
		QControls that have their <strong>Required</strong> property set to "true" will check to ensure that data
		was at least entered or selected.  Some controls have additional rules.  For example, we'll use
		<strong>QIntegerTextBox</strong> here to have Qforms ensure that the data entered in our two textboxes are
		valid integers.</p>

	<p>So we will utilize the QForm's validation in our application by doing the following:</p>
	<ul>
		<li>Set our <strong>btnCalculate</strong> button's <strong>CausesValidation</strong> property to true</li>
		<li>Use <strong>QIntegerTextBox</strong> classes</li>
		<li>For those textboxes, we will use <strong>RenderWithError()</strong> instead of <strong>Render()</strong> in the HTML
			template code.  This is because <strong>Render()</strong> only renders the control, itself, with no
			other markers or placeholders.  <strong>RenderWithError()</strong> will be sure to render any error/warning
			messages for that control if needed.</li>
		<li>Lastly, we will add our first "business rule": ensure that the user does not divide by 0.
			This rule will be implemented as an <strong>if</strong> statement in the <strong>Form_Validate</strong> method.</li>
	</ul>

	<p>For more advanced users, note that <strong>CausesValidation</strong> can also be set to <strong>QCausesValidation::SiblingsAndChildren</strong>
		or <strong>QCausesValidation::SiblingsOnly</strong>.  This functionality is geared for developers who are creating more
		complex <strong>QForms</strong> with child controls (either dynamically created, via custom composite controls, custom <strong>QPanels</strong>, etc.),
		and allows for more finely-tuned direction as to specify a specific subset of controls that should be validated, instead
		of validating against all controls on the form.</p>

	<p><strong>SiblingsAndChildren</strong> specifies to validate all sibling controls and their children of the control that is triggering
		the action, while <strong>SiblingsOnly</strong> specifies to validate the triggering control's siblings, only.</p>
</div>

<div id="demoZone">
	<p>Value 1: <?php $this->txtValue1->RenderWithError(); ?></p>

	<p>Value 2: <?php $this->txtValue2->RenderWithError(); ?></p>

	<p>Operation: <?php $this->lstOperation->Render(); ?></p>

	<?php $this->btnCalculate->Render(); ?>
	<hr/>
	<?php $this->lblResult->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>