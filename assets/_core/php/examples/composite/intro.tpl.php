<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Creating a Control of Controls</h1>

	<p>Often times you will want to be able to combine a bunch of small controls into a larger control,
		also known as creating a <strong>Composite Control</strong>.  In addition to this composite control
		containing many smaller controls, the composite control would be able to define
		its own layout, as well as handling its own server- or ajax-based actions.</p>

	<p>With a modularized set of smaller controls, layout, and events/actions, an architecture
		utilizing <strong>Composite Controls</strong> can see a lot of modularity and reuse for commonly
		used, more-complex interactions throughout your entire web application.</p>

	<p>With a modularized set of smaller controls, layout, and events/actions, an architecture
		utilizing <strong>Composite Controls</strong> can see a lot of modularity and reuse for commonly
		used, more-complex interactions throughout your entire web application.</p>

	<p>Now, notice how even though we seem to have a lot of small controls on the page (e.g. 7 buttons, each
		with their own event handlers!), the actual form is quite simple, because we are using the
		<strong>SampleComposite</strong> control over and over again.</p>

	<p>Be sure and view the source of <strong>SampleComposite.class.php</strong>, which of course will contain the code
		for the composite control which is doing the bulk of the work in this example.</p>
</div>

<div id="demoZone">
	<table border="0">
		<tr>
			<td><?php $this->objCounter1->Render(); ?></td>
			<td align="center" style="width:40px;font-weight: bold; font-size: 28px;">+</td>
			<td><?php $this->objCounter2->Render(); ?></td>
			<td align="center" style="width:40px;font-weight: bold; font-size: 28px;">+</td>
			<td><?php $this->objCounter3->Render(); ?></td>
		</tr>
	</table>

	<p><?php $this->btnButton->Render(); ?></p>
	<p><?php $this->lblMessage->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>