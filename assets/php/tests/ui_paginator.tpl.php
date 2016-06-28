<?php require(__PROJECT__ . '/includes/configuration/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>
<div>
<?php $this->dtg->Render(); ?>
<?php $this->txtCount->RenderWithName(); ?>
<?php $this->txtPageSize->RenderWithName(); ?>
</div>
<?php $this->RenderEnd(); ?>
<?php require(__PROJECT__ . '/includes/configuration/footer.inc.php'); ?>