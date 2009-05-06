<?php
	// This is the HTML template include file (.tpl.php) for the project_edit.php
	// form DRAFT page.  Remember that this is a DRAFT.  It is MEANT to be altered/modified.

	// Be sure to move this out of the generated/ subdirectory before modifying to ensure that subsequent 
	// code re-generations do not overwrite your changes.

	$strPageTitle = QApplication::Translate('Project') . ' - ' . $this->mctProject->TitleVerb;
	require(__INCLUDES__ . '/header.inc.php');
?>

	<?php $this->RenderBegin() ?>

	<div id="titleBar">
		<h2><?php _p($this->mctProject->TitleVerb); ?></h2>
		<h1><?php _t('Project')?></h1>
	</div>

	<div id="formControls">
		<?php $this->lblId->RenderWithName(); ?>

		<?php $this->lstProjectStatusType->RenderWithName(); ?>

		<?php $this->lstManagerPerson->RenderWithName(); ?>

		<?php $this->txtName->RenderWithName(); ?>

		<?php $this->txtDescription->RenderWithName(); ?>

		<?php $this->calStartDate->RenderWithName(); ?>

		<?php $this->calEndDate->RenderWithName(); ?>

		<?php $this->txtBudget->RenderWithName(); ?>

		<?php $this->txtSpent->RenderWithName(); ?>

		<?php $this->lstProjectsAsRelated->RenderWithName(true, "Rows=7"); ?>

		<?php $this->lstParentProjectsAsRelated->RenderWithName(true, "Rows=7"); ?>

		<?php $this->lstPeopleAsTeamMember->RenderWithName(true, "Rows=7"); ?>

	</div>

	<div id="formActions">
		<div id="save"><?php $this->btnSave->Render(); ?></div>
		<div id="cancel"><?php $this->btnCancel->Render(); ?></div>
		<div id="delete"><?php $this->btnDelete->Render(); ?></div>
	</div>

	<?php $this->RenderEnd() ?>	

<?php require(__INCLUDES__ .'/footer.inc.php'); ?>