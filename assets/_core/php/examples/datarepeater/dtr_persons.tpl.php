<div class="data_repeater_example">
	<strong>Person #<?php _p($_ITEM->Id); ?></strong><br/>
	First Name: <strong><?php _p($_ITEM->FirstName); ?></strong><br/>
	Last Name: <strong><?php _p($_ITEM->LastName); ?></strong>
</div>

<?php
	if ((($_CONTROL->CurrentItemIndex % 2) != 0) ||
		($_CONTROL->CurrentItemIndex == count($_CONTROL->DataSource) - 1)){
			_p('<br style="clear:both;"/>', false);
		}
?>