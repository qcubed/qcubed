<template OverwriteFlag="true" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<?php echo __FORM_DRAFTS__  ?>" TargetFileName="<?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_edit.tpl.php"/>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for the <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_edit.php
	// form DRAFT page.  Remember that this is a DRAFT.  It is MEANT to be altered/modified.

	// Be sure to move this out of the generated/ subdirectory before modifying to ensure that subsequent
	// code re-generations do not overwrite your changes.

	$strPageTitle = QApplication::Translate('<?php echo $objTable->ClassName  ?>') . ' - ' . $this->pnl<?php echo $objTable->ClassName  ?>->TitleVerb;
	require(__CONFIGURATION__ . '/header.inc.php');
?>

	<?php print("<?php"); ?> $this->RenderBegin() ?>

	<div id="titleBar">
		<h2><?php print("<?php"); ?> _p($this->pnl<?php echo $objTable->ClassName  ?>->TitleVerb); ?></h2>
		<h1><?php print("<?php"); ?> _t('<?php echo $objTable->ClassName  ?>')?></h1>
	</div>

	<div id="formControls">
		<?php print("<?php"); ?> $this->pnl<?php echo $objTable->ClassName  ?>->Render(); ?>
	</div>

	<?php print("<?php"); ?> $this->RenderEnd() ?>

<?php print("<?php"); ?> require(__CONFIGURATION__ .'/footer.inc.php'); ?>
