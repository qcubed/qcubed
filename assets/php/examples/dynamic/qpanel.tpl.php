<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Introduction to QPanel and QLabel</h1>
	
	<p>It may seem funny that we are "introducing" the <strong>QPanel</strong> and <strong>QLabel</strong> controls
	now, especially since we have already been using them a lot throughout the examples.</p>

	<p>On the surface, it may seem that <strong>QLabel</strong> is very simple -- you specify the <strong>Text</strong>
	that you want it to display and maybe some styles around it, and then you can just <strong>Render</strong>
	it out.  And while <strong>QLabel</strong> and <strong>QPanel</strong> controls should certainly be used for 
	that purpose, they also offer a lot more in functionality.</p>

	<p>Both the <strong>QLabel</strong> and <strong>QPanel</strong> controls extend from the <strong>QBlockControl</strong> class.
	The only difference between the two is that labels will render as a &lt;span&gt; and panels will render
	as a &lt;div&gt;.  And in fact, because in HTML there is very little difference between &lt;span&gt;
	and &lt;div&gt; anyway, it is safe to say that a <strong>QLabel</strong> with its <strong>DisplayStyle</strong> set to
	"block" will be equivalent to a <strong>QPanel</strong> with its <strong>DisplayStyle</strong> set to "inline".</p>

	<p>In addition to defining the <strong>Text</strong> to display, these controls can also use a <strong>Template</strong> file.
	Moreover, these controls can also have any of its unrendered child controls auto-rendered out.  This offers
	a <i>lot</i> of power and flexibility, basically allowing you to render out an arbitrary number of dynamically
	created controls, without needing to hard code or specify these controls anywhere or on any template.</p>

	<p>The order of rendering for block controls are:</p>
	<ul>
		<li>Display the <strong>Text</strong> (if applicable)</li>
		<li>Pull in the <strong>Template</strong> and render it (if applicable)</li>
		<li>If <strong>AutoRenderChildren</strong> is set to true, then get all child controls and call <strong>Render</strong> on all of them
		that have not been rendered yet</li>
	</ul>

	<p>In our example below, we define a <strong>QPanel</strong> and assign textboxes as child controls.  We specify
	a <strong>Text</strong> value and also setup a <strong>Template</strong>.  Finally, we render that entire panel out (complete
	with the text, template and child controls) with a single <strong>Render</strong> call.</p>

	<p>Note that even though 10 textboxes are being rendered, we never explicitly code a <strong>QTextBox->Render</strong>
	call <em>anywhere</em> in our code.</p>

	<p>Within the template file, the <em>$this</em> variable refers to the control being rendered.</em></p>


	<p>Another type of block control to mention here is the  <strong>QFieldset</strong>
		which draws a panel as an html fieldset, and has a legend. Otherwise, it is the same as a QPanel.</p>


</div>

<div id="demoZone">
	<?php $this->pnlPanel->Render(); ?>
	<?php $this->pnlFieldset->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>