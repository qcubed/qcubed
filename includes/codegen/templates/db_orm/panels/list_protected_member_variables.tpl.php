<?php if (!isset($objTable->Options['CreateFilter']) || $objTable->Options['CreateFilter'] !== false) { ?>
	/** @var QPanel **/
	protected $pnlFilter;

	/** @var QTextBox **/
	protected $txtFilter;
<?php } ?>

	/** @var QPanel **/
	protected $pnlButtons;

	/** @var QButton **/
	protected $btnNew;

	/** @var <?= $strPropertyName ?>List **/
	protected $<?= $strListVarName ?>;

<?php if ($blnUseDialog) { ?>
	/** @var <?= $objTable->ClassName ?>EditDlg **/
	protected $dlgEdit;
<?php }