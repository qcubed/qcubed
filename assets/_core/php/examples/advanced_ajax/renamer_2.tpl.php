<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>More "J" and Less "A" in AJAX</h1>

	<p>Because our Renameable Labels make full use of <strong>QAjaxActions</strong>, any clicking (including
		just selecting a label) involves an asynchronous server hit.</p>

	<p>Of course, by having all your functionality and display logic in one place, we show
		how you can quickly and rapidly develop Ajax interactions with very little PHP code,
		and in fact with <em>no</em> custom Javascript whatsoever.  This allows developers
		the ability to rapidly prototype not just web-based
		applications, but also web-based applications with full Ajax functionality.</p>

	<p>But as your application matures, you may want to have some fully server-side Ajax functionality
		be converted into more performance-efficient client-side-only Javascript functionality.
		This example shows how you can easily change an existing <strong>QForm</strong> that uses all QCubed-based Ajax
		interactions into a more blended server- and client-side Javascript/Ajax form.  Because the API for
		<strong>QServerActions</strong>, <strong>QJavaScriptActions</strong> and <strong>QAjaxActions</strong> are all the same, the
		process for rewriting specific nuggets of functionality in this manner is straightforward,
		and the action types (from Ajax- to JavaScript- to Server-) should be very interchangable.</p>

</div>

<div id="demoZone">
	<?php for ($intIndex = 0; $intIndex < 10; $intIndex++) { ?>
		<p style="height: 16px;">
			<?php $this->lblArray[$intIndex]->Render(); ?>
			<?php $this->txtArray[$intIndex]->Render('BorderWidth=1px', 'BorderColor=gray', 'BorderStyle=Solid'); ?>
		</p>
	<?php } ?>
</div>

<script type="text/javascript">
	var intSelectedIndex = -1;
	var objSelectedLabel;

	function lblArray_Click(objControl) {
		var strControlId = objControl.id,
			intIndex = strControlId.substr(5),
			objTextbox;

		// Is the Label being clicked already selected?
		if (intSelectedIndex == intIndex) {
			// It's already selected -- go ahead and replace it with the textbox
			qc.getW(strControlId).toggleDisplay('hide');
			qc.getW('textbox' + intIndex).toggleDisplay('show');

			objTextbox = qcubed.getControl('textbox' + intIndex);
			objTextbox.value = objControl.innerHTML;
			objTextbox.focus();
			objTextbox.select();
		} else {
			// Nope -- not yet selected

			// First, unselect everything else
			if (objSelectedLabel){
				objSelectedLabel.className = 'renamer_item';
			}
			// Now, make this item selected
			objControl.className = 'renamer_item renamer_item_selected';
			objSelectedLabel = objControl;
			intSelectedIndex = intIndex;
		}
	}
</script>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>