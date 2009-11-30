<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

	<div class="instructions">
		<h1 class="instruction_title">Integrating QForms and the Code Generator</h1>

		When you code generate your objects, QCubed will actually provide a starting
		point for this integration in the generated <b>Drafts</b>.  These generated
		scripts are definitely <i>drafts</i> or starting points from which you can create
		more elaborate, useful and functional <b>QForms</b> or <b>QPanels</b> for your application.<br/><br/>

		At a high level, this concept is very similar to the <b>scaffolding</b> which
		is provided by many other frameworks.  But note that because of the object-oriented
		approach of the <b>MetaControls</b> and <b>Meta DataGrids</b>, these <b>Drafts</b> can offer much more
		power and functionality over <b>scaffolding</b>.<br/><br/>

		It is difficult to show this in a one-page example, so if you would like to
		see this in action, we recommend that you check out
		the introductory <b><a href="http://qcu.be/files/screencasts/qcubed_ui_intro/qcubed_ui_intro.html" class="bodyLink">Screencast 
		on Code Generation</a></b>.
	</div>

	To view one of the generated <b>Form Drafts</b>, please click here to
	view the <b><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__); ?>/person_list.php"
		class="bodyLink">Person List</a></b> page (available only if you are running examples locally).

<?php require('../includes/footer.inc.php'); ?>
