<?php
	$_CONTROL->Navigation1->Render();
?>
<div class="ib_main_image_pnl">
<?php 
	$_CONTROL->MainImage->Render();
?>
</div>
<?php
	$_CONTROL->Caption->Render();
	if ($_CONTROL->SaveButton)
		$_CONTROL->SaveButton->Render();
	$_CONTROL->Thumbnails->Render();
?>
