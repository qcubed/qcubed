<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Customizing How FormState is Saved</h1>

	<p>By default, the <strong>QForm</strong> engine will store the state of the actual <strong>QForm</strong> objects as a rather
		long <strong>Base64</strong> encoded string.  While this is a very simple, straightforward and very maintenance-free
		approach, it does cause some complications, especially for more enterprise-level application
		architectures:</p>
	<ul>
		<li><strong>Performance</strong>: for really complex forms, formstate could account for as much as 10KB ~ 15KB or more of
			extra data being sent over the pipe.  Especially for highly interactive AJAX-based applications, where you
			can have potentially multiple simultaneous operations, this can become a major performance bottleneck.</li>
		<li><strong>Security</strong>: with just simple <strong>Base64</strong> encoding, a hacker could alter their own formstate and modify
			private member variables in the form that you don't intend to have modified.</li>
	</ul>

	<p>QCubed resolves this by offering the ability to store/handle the formstate in various ways.  You can store
		the formstate data in PHP Sessions or you can store the formstate data directly on the
		filesystem.  For both methods, you end up only passing a small key back to the user.  Moreover, the formstate,
		itself, or the key can even be encrypted, using the
		<strong><a href="../communication/crypto.php" class="bodyLink">QCryptography</a></strong> class.</p>

	<p>Finally, because the FormState handler is encapsulated in its own class, you can even define your own formstate
		handler, to store the formstate data on a shared server, in a database, or even in server memory.</p>

	<p>In our example below, we use <strong>QSessionFormStateHandler</strong> to store the formstate data in PHP Session, and we
		will only store the session key (in this case, just a simple integer) on the page as a hidden form variable.
		For an added level of security, we will also encrypt the key.</p>

	<p>If you use your browser's "View Source" functionality, you will see that the <strong>Qform__FormState</strong> hidden
		form variable is now a <strong>lot</strong> shorter (likely about 10 - 20 bytes).  Compare this to the
		<a href="../basic_qform/intro.php" class="bodyLink">first example</a> where the form state was easily over 1 KB.  This is because
		the bulk of the form state is being stored as a PHP Session Variable, which is located on the server, itself.</p>
</div>

<div id="demoZone">
	<?php
	// We will override the ForeColor, FontBold and the FontSize.  Note how we can optionally
	// add quotes around our value.
	?>
	<p><?php $this->lblMessage->Render(); ?></p>
	<p><?php $this->btnButton->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>