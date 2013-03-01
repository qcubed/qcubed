<!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php _p(QApplication::$EncodingType); ?>" />
<title>Build a Page</title>
<style type="text/css">
	@import url("<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__); ?>/styles.css");
	body { padding: 1em; }
	.control-tool {
		background: #fefefe;
		border: 1px solid #ddd;
		border-radius: 3px;
		cursor: move;
		margin: .5em auto;
		padding: .5em;
	}
	#Application {
		border: 3px dashed #ccc;
		padding: 2em;
		width: auto;
	}
	.drop-hint { color: #ddd; text-align: center; }
</style>
</head>
<body>
<?php $this->RenderBegin(); ?>
	<?php $this->pnlApp->Render(); ?>
	<?php $this->dlgToolbox->Render(); ?>
	<?php $this->dlgEdit->Render(); ?>
<?php $this->RenderEnd(); ?>
</body>
</html>