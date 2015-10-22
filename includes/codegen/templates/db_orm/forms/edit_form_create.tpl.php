	protected function Form_Create() {
		parent::Form_Create();

		$this->pnl<?= $strPropertyName ?> = new <?= $strPropertyName ?>EditPanel($this);
<?php
	$_INDEX = 0;
	foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {
		if (QCodeGen::$CreateMethod === 'queryString') {
?>
		$<?= $objColumn->VariableName ?> = QApplication::QueryString('<?= $objColumn->VariableName ?>');
<?php
		} else {
?>
		$<?= $objColumn->VariableName ?> = QApplication::PathInfo(<?= $_INDEX ?>);
<?php 		$_INDEX++;
		}
?>
<?php
	}
?>
	    $this->pnl<?= $strPropertyName ?>->Load(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?= $objColumn->VariableName ?>, <?php } GO_BACK(2); ?>);
		$this->CreateButtons();
	}
