<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Automatic Refreshing and the QWatcher Classes</h1>

	<p>The <strong>QWatcher</strong> class is used to connect a control to a database table or tables so that
		whenever that database changes, the control automatically refreshes. This can save you from having to
		setup callbacks between edit forms and dialogs in order to refresh a control that is viewing data. In addition,
		in a multi-user environment, when one user changes the data, the other user will automatically see the
		change. This is similar to what you might see in a system like NodeJS, with some caveats</p>

	<p>The current implementation requires the browser to generate either an Ajax event or Server event in order to
		detect the change. In a multi-user environment, it the user is actively using your application, this should
		happen pretty often. However, if your application is such that the user might have long-periods of
		inactivity, but still should see the results of activity from other users, you can do a couple of things:
		<ul>
		<li>
			Set up a QJsTimer to generate periodic events. See the <a href="../other_controls/timer_js.php">QJsTimer example page</a> for help. In that
			example page, it discusses adding actions to the timer. For purposes of generating opportunities for
			the QWatcher to look at the database, you will add a null ajax action to the timer.
		</li>
		<li>
			The other option, which is currently not implemented in QCubed, is create a direct connection between the
			server and the user's browser that will trigger these events. There are a few different technologies to do this,
			but all require a customized html server. Apache will not do this out of the box.
		</li>
		</ul></p>

	<p>To make watcher work, you must edit
		the project/includes/controls/QWatcher.class.php file so that the QWatcher class inherits from
		the watcher type you want. Available types currently let you use a database to track changes, or
		use a QCacheProvider subclass.</p>

	<p>This is another QDatagrid2 example with a couple of fields to add a new person.
		Whenever you add a person, the person will appear in the datagrid immediately.
		It also has a timer to generate periodic events that will check whether another user has changed the database.
		Try opening the page in another browser on your computer to simulate a multi-user environment.
		Whenever you add data to one browser, it will appear in the other browser.</p>



</div>

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
	<p>First:<?php $this->txtFirstName->Render(); ?></p>
	<p>Last:<?php $this->txtLastName->Render(); ?></p>
	<?php $this->btnNew->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>