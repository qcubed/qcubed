<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __MODEL_GEN__  ?>" TargetFileName="QQN.class.php"/>
<?php print("<?php\n"); ?>
	class QQN {
<?php foreach ($objTableArray as $objTable) { ?>
		/**
		 * @return QQNode<?php echo $objTable->ClassName  ?>

		 */
		static public function <?php echo $objTable->ClassName  ?>() {
			return new QQNode<?php echo $objTable->ClassName  ?>('<?php echo $objTable->Name  ?>', null, null);
		}
<?php } ?>
	}
?>