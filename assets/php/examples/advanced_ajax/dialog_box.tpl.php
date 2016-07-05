<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Extending QPanels to Create Modal "Dialog Boxes"</h1>
	
	<p>In general UI programming, there are two kinds of dialog boxes: modal and non-modal.  Modal dialog boxes are
		the most common. When a program displays a modal dialog box, the user cannot switch between the dialog box
		and the program's main UI.  The user must explicitly close the dialog box, usually by clicking either "Ok" or "Cancel".</p>

	<p>Obviously, with the current state of HTML and browser technologies, the <em>alert()</em> Javascript method
		is still the only <em>true</em> way to have any level of a modal dialog interaction.  And unfortunately,
		<em>alert()</em> has very few features in terms of functionality.</p>

	<p><strong>QCubed</strong> implements the JQuery UI dialog box as a standard extension to the <strong>QPanel</strong>, which gives you
		the ability to create modal and modeless dialog boxes with a wide range of capabilities and complexities.</p>

	<p>Because it extends the <strong>QPanel</strong> control, you have full use of all the <strong>QPanel's</strong> resources
		to build and design the content of the dialog box itself, including using separate template files and
		adding child controls, events, actions and validation.</p>

	<p>And since it also uses the JQuery UI <strong>Dialog</strong> control, you have full access to all of the JQuery UI
		capabilities as well, and a few extra extensions. In particular,
		you can call <strong>AddButton()</strong> to add buttons to the dialog that will be placed in standard
		dialog locations and colored with the current theme. Attach actions to the <strong>QDialog_ButtonEvent</strong> event to react
		to a user's click of the buttons on the dialog,
		and use the <strong>strParameter</strong> variable to detect which of these buttons were clicked. If for some reason
		you don't want to use the JQuery UI buttons through AddButton, you
		can put standard QCubed buttons on the dialog instead.</p>


	<p>The four examples show:
		<ol>
		<li>A simple "display only" dialog box.</li>
		<li>A modal dialog that asks for user input.</li>
		<li>A more complex dialog box that is meant to be a
		"calculator widget" with intra-control communication, where the contents of the calculator in the dialog box
		can be copied into a textbox on the main form.</li>
		<li>A dialog that demonstrates how to specify validation, confirmation and additional styling
		with the AddButton method.</li>
		<li>An alert dialog that can be styled as an error or info message using the
			Themeroller styles provided with JQueryUI.</li>
	</ol></p>

	<p>Note that <strong>QDialog</strong>s are rendered automatically once they are attached to a form. You should not
		call Render on the dialog instance.</p>
</div>

<style type="text/css">
	.calculator_display { text-align: right; padding: 4px; width: 208px; border-width: 1px; border-style: solid; border-color: black; background-color: white; font: 24px verdana, arial, helvetica; }
	.calculator_button { width: 50px; height: 45px; font: 20px verdana, arial, helvetica; font-weight: bold; border-width: 1px; background-color: #eeffdd; }
	.calculator_top_button { width: 78px; height: 45px; font: 10px verdana, arial, helvetica; color: white; border-width: 1px; background-color: #336644; }
</style>

<div id="demoZone">
	<fieldset style="width: 400px;">
		<legend>Simple Message Example</legend>
		<p><?php $this->btnDisplaySimpleMessage->Render(); ?></p>
		<p><?php $this->btnDisplaySimpleMessageJsOnly->Render(); ?></p>
	</fieldset>

	<fieldset style="width: 400px;">
		<legend>Yes/No Example</legend>
		<?php $this->btnDisplayYesNo->Render(); ?>
		<?php $this->pnlAnswer->Render(); ?>
	</fieldset>

	<fieldset style="width: 400px;">
		<legend>Calculator Widget Example</legend>
		<p>Current Value: <?php $this->txtValue->Render(); ?></p>
		<p><?php $this->btnCalculator->Render(); ?></p>
	</fieldset>

	<fieldset style="width: 400px;">
		<legend>Validation Example</legend>
		<p><?php $this->btnValidation->Render(); ?></p>
	</fieldset>

	<fieldset style="width: 400px;">
		<legend>Alert Examples</legend>
		<p>
			<?php $this->btnErrorMessage->Render(); ?>
			<?php $this->btnInfoMessage->Render(); ?>
		</p>
	</fieldset>

</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>