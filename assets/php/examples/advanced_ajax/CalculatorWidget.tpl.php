<?php $_CONTROL->pnlValueDisplay->Render(); ?>
<table>
	<tr>
		<td colspan="3"><?php $_CONTROL->btnUpdate->Render('CssClass=calculator_top_button'); ?> <?php $_CONTROL->btnCancel->Render('CssClass=calculator_top_button'); ?></td>
		<td><?= $this->pxyOperationControl->RenderAsButton('/', '/', ['class'=>"calculator_button"]); ?></td>
	</tr>
	<tr>
		<td><?= $this->pxyNumberControl->RenderAsButton('7', 7, ['class'=>"calculator_button"]); ?></td>
		<td><?= $this->pxyNumberControl->RenderAsButton('8', 8, ['class'=>"calculator_button"]); ?></td>
		<td><?= $this->pxyNumberControl->RenderAsButton('9', 9, ['class'=>"calculator_button"]); ?></td>
		<td><?= $this->pxyOperationControl->RenderAsButton('*', '*', ['class'=>"calculator_button"]); ?></td>
	</tr>
	<tr>
		<td><?= $this->pxyNumberControl->RenderAsButton('4', 4, ['class'=>"calculator_button"]); ?></td>
		<td><?= $this->pxyNumberControl->RenderAsButton('5', 5, ['class'=>"calculator_button"]); ?></td>
		<td><?= $this->pxyNumberControl->RenderAsButton('6', 6, ['class'=>"calculator_button"]); ?></td>
		<td><?= $this->pxyOperationControl->RenderAsButton('-', '-', ['class'=>"calculator_button"]); ?></td>
	</tr>
	<tr>
		<td><?= $this->pxyNumberControl->RenderAsButton('1', 1, ['class'=>"calculator_button"]); ?></td>
		<td><?= $this->pxyNumberControl->RenderAsButton('2', 2, ['class'=>"calculator_button"]); ?></td>
		<td><?= $this->pxyNumberControl->RenderAsButton('3', 3, ['class'=>"calculator_button"]); ?></td>
		<td><?= $this->pxyOperationControl->RenderAsButton('+', '+', ['class'=>"calculator_button"]); ?></td>
	</tr>
	<tr>
		<td><input type="button" value="0" class="calculator_button" <?php $_CONTROL->pxyNumberControl->RenderAttributes(0); ?>/></td>
		<td><?php $_CONTROL->btnPoint->Render('CssClass=calculator_button'); ?></td>
		<td><?php $_CONTROL->btnClear->Render('CssClass=calculator_button'); ?></td>
		<td><?php $_CONTROL->btnEqual->Render('CssClass=calculator_button'); ?></td>
	</tr>
</table>