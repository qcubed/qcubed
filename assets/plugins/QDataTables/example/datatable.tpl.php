<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>
	<script type="text/javascript">
		function valod(aoData) {
			aoData.push({'name': 'Qform__FormId', 'value': jQuery('#Qform__FormId').val()});
			aoData.push({'name': 'Qform__FormState', 'value': jQuery('#Qform__FormState').val()});
			aoData.push({'name': 'Qform__FormCallType', 'value': 'Ajax'});
			aoData.push({'name': 'Qform__FormControl', 'value': 'c1'});
			var x = 1;
			return aoData;
		}
	</script>
<style type="text/css">
	tr.odd_row {
		background-color: #ffddff;
	}

	tr.even_row {
		background-color: #ccccff;
	}

	tr.header_row {
		background-color: #420182;
		color: #ddeeff;
	}

	table.simple_table td, table.simple_table th {
		padding: 5px;
	}

	table.simple_table {
		border-collapse: collapse;
		border-spacing: 0;
	}

	.instructions {
		max-height: none;
	}
</style>

<div class="instructions">
	<h1 class="instruction_title">Using QDataTable</h1>
</div>
<div style="margin-left: 100px">
	<?php $this->tblPersons->Render(); ?>
</div>

<div style="margin: 100px">
	Clicked: <?php $this->lblSelection->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/footer.inc.php'); ?>
