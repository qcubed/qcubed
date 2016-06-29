<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Executing Javascript with low/high priority</h1>
	
	<p>In this example you learn about executing javascript with <b>QApplication::ExecuteJsFunction</b> and
		<b>QApplication::ExecuteSelectorFunction</b> with different priority levels.</p>

	<p>You can execute JavaScript using one of three priority-levels: <b> QJsPriority::Low , QJsPriority::Standard and QJsPriority::High</b>
	Scripts with higher priority-level will be placed in the javascript execution-queue before scripts with lower ones 
	and scripts with equal priority level are executed in the order you send them. </p>
	
	<h2>QCubed task order:</h2>
	
	<ul>
		<li>Render/update html</li>
		<li>Execute JavaScript functions with <b>QJsPriority::High</b>
		<li>Execute QActions attached to controls with QEvents</li>
		<li>Execute JavaScript functions with <b>QJsPriority::Standard</b>
		<li>Execute JavaScript functions with <b>QJsPriority::Low</b>
	</ul>

	<p>Take a look at the example below. By clicking on one of the buttons the
	datagrid gets updated and an alert box will show up.
	Try clicking on buttons of both rows and look at the different update-behaviour.<br/>
	The interesting code resides in the methods <b>renderButton_Click</b> and <b>renderLowPriorityButton_Click</b></p>
	
	<p>In these methods the datagrid is marked as modified (render it again, including all the buttons),
	some JavaScript alert boxes will show up and the color of the buttons changes due to
	adding a css class via JavaScript.
	The parameter <b>QJsPriority::Low</b> forces the script to be executed after all scripts with higher priority.</p>
	
	
	<p>When the buttons are (re)rendered they get their standard color applied (and the JavaScript returned by GetEndScript is executed again).
	If you hit a <b>update & low priority alert</b> button the alert boxes have low priority,
	the JavaScript for adding the new css class is executed before the alerts show up
	and the color is changed immediately.
	When hitting a <b>update & alert</b> button the color will be changed after the alert boxes show up because
	all scripts are executed with standard priority.</p>

	<h2>Strategies for executing Javascript</h2>
	The <b>QApplication::ExecuteJsFunction</b>, <b>QApplication::ExecuteSelectorFunction</b> and
	<b>QApplication::ExecuteControlCommand</b> functions are available to use invoke javascript in a number of ways.
	If these are not adequate, we recommend you put your javascript in a file, and invoke that javascript using one of the
	above functions.
</div>

<div id="demoZone">
	<style type="text/css">@import url("<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ . "/" . __JQUERY_CSS__); ?>");</style>
	<?php $this->dtgButtons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>