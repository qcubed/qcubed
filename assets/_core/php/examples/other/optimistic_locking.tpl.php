<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Integrating Optimistic Locking into QForms</h1>

	<p>In Section 2, we showed how by using the TIMESTAMP column types, QCubed will generate
		code to handle <strong>Optimistic Locking</strong>.  In this example, we take this a step further
		to illustrate a more functional approach to utilizing <strong>Optimistic Locking</strong> in your
		web based application.</p>

	<p>In our example below, we have the same <strong>Person</strong> object instantiated twice.  This
		is supposed to mimic two users on two different computers trying to edit the same
		<strong>Person</strong> object at the same time.</p>

	<p>(Note: on some database platforms, including MySQL, no SQL UPDATE will be performed
		unless the data has actually been changed.  It's recommended that you make a change
		to either the <strong>First Name</strong> or the <strong>Last Name</strong> before hitting <strong>Save</strong>
		in order to see this example in action.)</p>

	<p>As you can see, the <strong>Optimstic Locking</strong> functionality will allow both "users" to
		view the data.  But once one user tries to update one of the <strong>Person</strong> objects,
		the other <strong>Person</strong> object is recognized as "stale" (because of a TIMESTAMP
		mismatch).  Any subsequent call to <strong>Save</strong> on the "stale" <strong>Person</strong> will throw
		an exception.  We catch this <strong>QOptimsiticLockingException</strong> in our <strong>QForm</strong>
		in order to present a more graceful response to the user, allowing the user the option to
		override the changes made by the previous <strong>Save</strong> call, forcing the update.</p>
</div>

<div id="demoZone">
	<table cellspacing="10" cellpadding="10" border="0">
		<tr>
			<td align="center" colspan="2">
				Current <strong>Name</strong> and <strong>Timestamp</strong> values in the database for this <strong>PersonWithLock</strong> object:<br/>
				<strong><?php _p($this->objPersonReference->FirstName . ' ' . $this->objPersonReference->LastName); ?></strong>
				&nbsp;|&nbsp;
				<strong><?php _p($this->objPersonReference->SysTimestamp); ?></strong>
				<br/><?php $this->lblMessage->Render('ForeColor=Red', 'FontBold=true'); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" style="width:300px;background-color:#ccffaa">
				<h3>PersonWithLock Instance #1</h3>
				<?php $this->txtFirstName1->RenderWithName('Name=First Name'); ?><br/>
				<?php $this->txtLastName1->RenderWithName('Name=Last Name'); ?><br/>
				<?php $this->lblTimestamp1->RenderWithName('Name=Timestamp Value'); ?><br/>
				<?php $this->btnSave1->Render('Text=Save This Person Object'); ?></p>
				<?php $this->btnForceUpdate1->Render('Text=Save This Person Object (Force Update)'); ?><br/>
			</td>
			<td valign="top" style="width:300px;background-color:#ccffaa">
				<h3>PersonWithLock Instance #2</h3>
				<?php $this->txtFirstName2->RenderWithName('Name=First Name'); ?><br/>
				<?php $this->txtLastName2->RenderWithName('Name=Last Name'); ?><br/>
				<?php $this->lblTimestamp2->RenderWithName('Name=Timestamp Value'); ?><br/>
				<?php $this->btnSave2->Render('Text=Save This Person Object'); ?></p>
				<?php $this->btnForceUpdate2->Render('Text=Save This Person Object (Force Update)'); ?><br/>
			</td>
		</tr>
	</table>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>