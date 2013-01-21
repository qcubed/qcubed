<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Understanding the QForm Process Flow</h1>

	<p>First of all, don't adjust your screen. =)</p>

	<p>The "Form_blah called" messages you see are
		showing up to illustrate how the <strong>QForm</strong> process flow works.</p>

	<p>As we mentioned earlier, <strong>QForm</strong> objects are stateful, with the state persisting through
		all the user interactions (e.g. ServerActions, etc.).  But note that <strong>QForm</strong> objects are also
		event-driven.  This is why the we state that QForms is a "stateful, event-driven architecture
		for web-based forms."  On every execution of a <strong>QForm</strong>, the following actions happen:</p>

	<ol>
		<li>The first thing the Form object does is internally determine if we are viewing this
			page fresh (e.g. not via a post back) or if we have actually posted back (e.g. via the
			triggering of a control's action which would post back to the server).</li>
		<li>If it is posted back, then it will retrieve the form's state from the <strong>FormState</strong>,
			which is a hidden form variable containing the serialized data for the actual Form instance.
			It will then go through all the controls and update their values according to the user-entered
			data submitted via the post, itself.</li>
		<li>Next, regardless if we're post back or not, the <strong>Form_Run</strong> method (if defined) will be
			triggered. Again, this will be run regardless if we're viewing the page fresh or if we've
			re-posted back to the page.</li>
		<li>Next, if we are viewing the page fresh (e.g. not via a post back), the <strong>Form_Create</strong>
			method (if defined) will be run (<strong>Form_Create</strong> is typically where you would define and
			instantiate your various <strong>QForm</strong> controls).  Otherwise, the <strong>Form_Load</strong> (if defined) will
			be run.</li>
		<li>Next, if we're posted back because of a <strong>QServerAction</strong> or <strong>QAjaxAction</strong> that points to a
			specific PHP method, then the following will happen:
			<ul>
				<li>First, if the control that triggered the event has its <strong>CausesValidation</strong> property set, then
					the form will go through validation.  The form will call <strong>Validate()</strong> on the relavent controls,
					and then it will call <strong>Form_Validate</strong> on itself.  (More information on validation can be seen in the upcoming Calculator examples.)</li>
				<li>Next, if validation runs successfully <strong>or</strong> if no validation is requested
					(because <strong>CausesValidation</strong> was set to false), then the PHP method that the action points to will be run.</li>
			</ul>
			So in this repeat of the "Hello World" example, when you click on <strong>btnButton</strong>, the <strong>btnButton_Click</strong> method
			will be excuted during this step.</li>
		<li>If defined, the <strong>Form_PreRender</strong> method will then be run.</li>
		<li>The HTML include template file is included (to render out the HTML).</li>
		<li>And finally, the <strong>Form_Exit</strong> (if defined) is run after the HTML has been completely outputted.</li>
	</ol>

	<p>So, basically, a <strong>QForm</strong> can have any combination of the five following methods defined to help
		customize <strong>QForm</strong> and <strong>QControl</strong> processing:</p>
	<ul>
		<li>Form_Run</li>
		<li>Form_Load</li>
		<li>Form_Create</li>
		<li>Form_Validate</li>
		<li>Form_PreRender</li>
		<li>Form_Exit</li>
	</ul>
</div>

<div id="demoZone">
	<p><?php $this->lblMessage->Render(); ?></p>
	<p><?php $this->btnButton->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
