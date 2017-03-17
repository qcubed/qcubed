<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Optimistic Locking and TIMESTAMP Columns</h1>

	<p>By including a self-updating TIMESTAMP column in your table, QCubed can automatically
	generate the functionality to perform <strong>Optimistic Locking</strong> for that database object. In this example,
	the <strong>person_with_lock</strong> table is defined with a TIMESTAMP column so that we can demonstrate
	<strong>Optimistic Locking</strong>.</p>

	<p><strong>Optimistic Locking</strong> is the loosest form of row- or object-level locking that, in general,
	works best for database-driven web-based applications.  In short, everyone is always
	allowed to read any row/object.  However, on save, you are only allowed to save if your
	object is considered "fresh".  Once your object is "stale", then you are locked out from being
	able to save that stale object. (Note that this is sometimes also called a
	"mid-air collision".) Objects might go stale if two browsers are editing the same object in the database,
    or in certain situations if the user presses the back button on the browser to re-edit a record already edited.</p>

	<p>So whenever you <strong>Load</strong> an object, you also get the latest TIMESTAMP information.  On
	<strong>Save</strong>, the TIMESTAMP in your object will be checked against the TIMESTAMP in the database.
	If they match, then the framework knows your data is still fresh, and it will allow the <strong>Save</strong>.
	If they do not match, then it is safe to say that the data in the object is now stale, and QCubed
	will throw an <strong>Optimistic Locking Exception</strong>.</p>

	<p>The <strong>Optimistic Locking</strong> constraint can be overridden at any time by simply
	passing in the optional <strong>$blnForceUpdate</strong> as true when calling <strong>Save</strong>.
    However, if you override it, or you do not provide a TIMESTAMP column for a table, then you are allowing
    the user to overwrite data that someone else changed, but the user did not get a chance to see.</p>

	<p>How you create your TIMESTAMP column will depend on the database you are using. MySQL TIMESTAMP columns by
	default will have the current time inserted into them, and be automatically updated, provided there are no other
	timestamp columns in that table. QCubed will automatically detect a column set up this way.</p>

	<p>However, other databases, like PostgresSQL, require you to set up a trigger to auto-update a TIMESTAMP
	column, which QCubed cannot detect. You can work around this limitation by specifying in a comment on the
	column in the database that you would like a column to be considered a Timestamp for purposes of
	<strong>Optimistic Locking</strong>. You can also tell QCubed to automatically update such a column with
	the current timestamp. To do this, you enter a JSON expression into the comments field that sets the Timestampand
	the AutoUpdate values to 1. You can find an example of how to do this in the <strong>pgsql.sql</strong> file for the
	examples database. Essentially, you want your comment to contain this:
	<code>
		{"Timestamp": 1, "AutoUpdate": 1}
	</code></p>

</div>

<div id="demoZone">
	<h2>Object Save and Double Saves on the PersonWithLock Object</h2>
	<form method="post" action="<?php _p(QApplication::$ScriptName); ?>">
		<p>Saving a Single Object will perform the save normally<br />
		<input type="submit" id="single" name="single" value="Save 1 Object"/></p>

		<p>Attempting to save a Two Instances of the Same Object will throw an <strong>Optimistic Locking Exception</strong><br />
		<input type="submit" id="double" name="double" value="Save 2 Objects (same Instance)"/></p>

		<p>Using <strong>$blnForceUpdate</strong> to avoid the <strong>Optimistic Locking Exception</strong><br/>
		<input type="submit" id="double_force" name="double_force" value="Force Update Second Object"/></p>
	</form>
<?php
	// Load the Two Person objects (same instance -- let them both be Person ID #4)
	$objPerson1 = PersonWithLock::Load(5);
	$objPerson2 = PersonWithLock::Load(5);

	// Some RDBMS Vendors' TIMESTAMP is only precise to the second
	// Let's force a delay to the next second to ensure timestamp functionality
	// Note: on most web applications, because Optimistic Locking are more application user-
	// level constraints instead of systematic ones, this delay is inherit with the web
	// application paradigm.  The following delay is just to simulate that paradigm.
	$dttNow = new QDateTime(QDateTime::Now);
	while ($objPerson1->SysTimestamp == $dttNow->qFormat(QDateTime::FormatIso)) {
		$dttNow = new QDateTime(QDateTime::Now);
	}

	// Make Changes to the Object so that the Save Will Update Something
	if ($objPerson1->FirstName == 'Al') {
		$objPerson1->FirstName = 'Alfred';
		$objPerson2->FirstName = 'Fred';
	} else {
		$objPerson1->FirstName = 'Fred';
		$objPerson2->FirstName = 'Bob';
	}

	switch (true) {
		case array_key_exists('single', $_POST):
			$objPerson1->Save();
			_p('Person Id #' . $objPerson1->Id . ' saved.  Name is now ' . $objPerson1->FirstName);
			_p('.<br/>', false);
			break;


		case array_key_exists('double', $_POST):
			$objPerson1->Save();
			_p('Person Id #' . $objPerson1->Id . ' saved.  Name is now ' . $objPerson1->FirstName);
			_p('.<br/>', false);

			// Try Saving Person #2 -- this should fail and throw an exception
			try {
				$objPerson2->Save();
				_p('Person Id #' . $objPerson2->Id . ' saved.  Name is now ' . $objPerson2->FirstName);
				_p('.<br/>', false);
			} catch (QOptimisticLockingException $ex) {
				QApplication::DisplayAlert('The optimistic locking exception was caught: ' . $ex->getMessage());
			}
			break;


		case array_key_exists('double_force', $_POST):
			$objPerson1->Save();
			_p('Person Id #' . $objPerson1->Id . ' saved.  Name is now ' . $objPerson1->FirstName);
			_p('.<br/>', false);

			// Try Saving Person #2 -- use $blnForceUpdate to avoid an exception
			$objPerson2->Save(false, true);
			_p('Person Id #' . $objPerson2->Id . ' saved.  Name is now ' . $objPerson2->FirstName);
			_p('.<br/>', false);
			break;
	}
?>
</div>

<?php require('../includes/footer.inc.php'); ?>