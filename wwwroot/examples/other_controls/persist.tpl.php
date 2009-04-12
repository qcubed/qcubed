<?php require('../includes/header.inc.php'); ?>

<div class="instructions">
	<div class="instruction_title">Persistent Controls: Performance for Reusable Components</div>
	
	What do you do if you have an element that's shared between multiple pages
	that's computationally intensive to produce? For example, what would you do
	in a situation where you have a dropdown control in the navigation, and that
	dropdown is populated with a list of projects from a database? Or worse,
	with a result of some heavy query?<br /><br />
	
	An obvious answer is to try to cache the results of the query so that you
	don't have to run it every time a page is loaded. QCubed comes with a
	feature called Persistent Controls that might save you time in certain
	situations. It works exactly as it sounds: all control metadata is
	cached by QCubed in the session state ($_SESSION). The next time a page is
	loaded, control state for persistent controls will be loaded from the
	session (which is much faster than trying to execute the query again).<br /><br />
	
	Try <a href="persist.php">reloading this page</a> and observe the status
	label underneath the dropdown. Note that if you run this example again,
	as long as your session state is not wiped, you'll never see the query
	executed - control storage is, well, persistent. <br /><br />
	
	Several notes:
	<ol>
		<li>If you want to create a reusable component that will work
		across user sessions (and will reuse the data from the session of one user to
		the session of another), Persistent Controls won't help. Use the <b>QCache</b>
		instead.</li>
		<li>Be mindful of the memory footprint of storing the <i>entire control</i>
		in the $_SESSION. In the example below, the footprint of the tiny dropdown
		with four values is 4KBytes. Persistent Controls can easily turn into a
		memory hog if you don't watch for it.</li>
	</ol>

</div>

	<?php $this->RenderBegin(); ?>    
	<?php $this->ddnProjectPicker->Render(); ?><br /><br />
	<?php $this->lblStatus->Render(); ?><br /><br />
	
	<a href="persist.php">Reload the page</a>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>