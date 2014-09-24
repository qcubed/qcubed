<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => true,
		'DirectorySuffix' => '',
		'TargetDirectory' => __PANEL_DRAFTS__,
		'TargetFileName' => $objTable->ClassName . 'ListPanel.tpl.php'
	);
?>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for <?php echo $objTable->ClassName  ?>ListPanel.
	// Remember that this is a DRAFT.  It is MEANT to be altered/modified.
	// Be sure to move this out of the drafts/dashboard directory before modifying to ensure that subsequent
	// code re-generations do not overwrite your changes.
?>
	<?php print("<?php"); ?> $_CONTROL->dtg<?php echo $objTable->ClassNamePlural  ?>->Render(); ?>

	<p class="create"><?php print("<?php"); ?> $_CONTROL->btnCreateNew->Render(); ?></p>