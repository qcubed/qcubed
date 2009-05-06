<?php
	// This is the HTML template include file (.tpl.php) for projectEditPanel.
	// Remember that this is a DRAFT.  It is MEANT to be altered/modified.
	// Be sure to move this out of the drafts/dashboard subdirectory before modifying to ensure that subsequent 
	// code re-generations do not overwrite your changes.
?>
	<div id="formControls">
		<?php $_CONTROL->lblId->RenderWithName(); ?>

		<?php $_CONTROL->lstProjectStatusType->RenderWithName(); ?>

		<?php $_CONTROL->lstManagerPerson->RenderWithName(); ?>

		<?php $_CONTROL->txtName->RenderWithName(); ?>

		<?php $_CONTROL->txtDescription->RenderWithName(); ?>

		<?php $_CONTROL->calStartDate->RenderWithName(); ?>

		<?php $_CONTROL->calEndDate->RenderWithName(); ?>

		<?php $_CONTROL->txtBudget->RenderWithName(); ?>

		<?php $_CONTROL->txtSpent->RenderWithName(); ?>

		<?php $_CONTROL->lstProjectsAsRelated->RenderWithName(true, "Rows=7"); ?>

		<?php $_CONTROL->lstParentProjectsAsRelated->RenderWithName(true, "Rows=7"); ?>

		<?php $_CONTROL->lstPeopleAsTeamMember->RenderWithName(true, "Rows=7"); ?>

	</div>

	<div id="formActions">
		<div id="save"><?php $_CONTROL->btnSave->Render(); ?></div>
		<div id="cancel"><?php $_CONTROL->btnCancel->Render(); ?></div>
		<div id="delete"><?php $_CONTROL->btnDelete->Render(); ?></div>
	</div>
