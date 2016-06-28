<?php require('../includes/header.inc.php'); ?>

	<div id="instructions">
		<h1>Persistent Controls</h1>

		<p>Some controls are used for the purpose of controlling how a form looks, rather than
			for the purpose of entering data in a database. If the user is navigating back and forth between
			various forms, it can be very frustrating to have to manually change the controls to the previous values
			just to get back to the data you were viewing.</p>

		<p>For example, if you have a <b>QDataGrid</b> showing multiple pages, and the user is on page 3, and then clicks
			on an item to edit it, and then saves or cancels, your application should take the user back to page 3 of the <b>QDataGrid</b>,
			and not page 1, the default.</p>

		<p>To get a control to restore its prior state, simply set its <b>SaveState</b> attribute to <b>true</b>.
			The control will automatically be set to its previous state. Generally, you would only do this for controls
			that are not getting data directly from the database, but rather controls that change how data is viewed. A
			good example would be a text box you use to filter a list.</p>

		<p>The control state data by default is saved in the session in a variable named by the <b>__SESSION_SAVED_STATE__</b>
			configuration constant (the default value is 'QSavedState'). If your application has authenticated users that
			see private data, you can serialize the contents of this session variable and store it in your database, along with the users credentials, and then
			restore it the next time the user logs in. The user will then see your application in the same state in which
			he used it previously. The data that is stored in the session variable is only the bare minimum to recreate
			the visual state of the control, and takes up very little space.</p>

		<p>In the example, you will see two sets of controls. One has SaveState turned on, and the other does not. When you
			click on the "Reload the Page" button, you will notice that the controls with <b>SaveState</b> set to <b>true</b>
			do not change, but the other controls revert to their default values.</p>

		<h2>Note on Usage</h2>
		<p>The moment that you set <b>SaveState</b> to <b>true</b>, QCubed will look for a previously saved
			state and restore it if one is found. So, if you would like to set your control to a particular default value,
			do that <em>before</em> you set <b>SaveState</b> to <b>true</b>.</p>

		<h2>Note on QCubed V2</h2>
		<p>Persistent controls in version 2 of QCubed were quite different and used as a kind of data cache.
			The old interface and functionality has been removed as no one actually reported using it and it was rather cumbersome.</p>

	</div>

	<div id="demoZone">
		<?php $this->RenderBegin(); ?>
		<fieldset>
			<legend>SavedState = false</legend>
			<?php $this->ddnProjectPicker1->Render(); ?></p>
			<?php $this->fld1->Render(); ?></p>
		</fieldset>
		<fieldset>
			<legend>SavedState = true</legend>
			<?php $this->ddnProjectPicker2->Render(); ?></p>
			<?php $this->fld2->Render(); ?></p>
		</fieldset>
		<?php $this->btnReload->Render(); ?></p>

		<?php $this->RenderEnd(); ?>
	</div>

<?php require('../includes/footer.inc.php'); ?>