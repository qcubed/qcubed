<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Executing Javascript with low/high priority</h1>
	
	<p>In this example you learn about executing javascript with <b>QApplication::ExecuteJavaScript</b>
	with different priority levels.</p>

	<p>When executing JavaScript by using QApplication::ExecuteJavaScript there exist 3 priority-levels: <b> QJsPriority::Low , QJsPriority::Standard and QJsPriority::High</b>
	Scripts with higher priority-level will be placed in the javascript execution-queue before scripts with lower ones 
	and scripts with equal priority level are executed in order. But knowing these three priority levels is not enough. QCubed Controls using JavaScript(i.e.: QJqButton using a JQuery UI control) have their own mechanism to execute JavaScript related to them.</p>
	
	<h2>QCubed task order:</h2>
	
	<ul>
		<li>render/update html</li>
		<li>execute JavaScript returned by the controls' method <b>GetEndScript</b></li>
		<li>execute JavaScript from <b>QApplication::ExecuteJavaScript</b> with <b>QJsPriority::High</b>
		<li>execute JavaScript from <b>QApplication::ExecuteJavaScript</b> with <b>QJsPriority::Standard</b>
		<li>execute JavaScript from <b>QApplication::ExecuteJavaScript</b> with <b>QJsPriority::Low</b>
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
</div>

<div class="demo-zone">
	<style type="text/css">@import url("<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ . "/" . __JQUERY_CSS__); ?>");</style>
	<?php $this->dtgButtons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>