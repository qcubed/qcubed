<?php require(__PROJECT__ . '/includes/configuration/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>
<p>This is a stress test for the various ways that data can be retrieved from client and delivered to the server. Put text
in the field and press the submit button. Try international characters in particular.
The results of the various attempts will display. They should all show
the same text as in the field, and a null value. Try international characters in particular. The page also does not
use UTF-8 encoding to make sure we can decode into any encoding.</p>
<?php $this->txtText->RenderWithName(); ?>
<?php $this->txt2->RenderWithName(); ?>
<?php $this->pnlTest->RenderWithName(); ?>
<?php $this->lstCheckables->Render(); ?>
<div>
	<?php $this->btnSubmit->Render(); ?>
	<?php $this->btnAjax->Render(); ?>

</div>
<?php $this->RenderEnd(); ?>
<?php require(__PROJECT__ . '/includes/configuration/footer.inc.php'); ?>