<?php
/*
 * error_page include file
 *
 * expects the following variables to be set:
 * 	$__exc_strType
 * 	$__exc_strMessage
 * 	$__exc_strObjectType
 * 	$__exc_strFilename
 * 	$__exc_intLineNumber
 * 	$__exc_strStackTrace
 *
 * optional:
 * 	$__exc_strRenderedPage
 *  $__exc_objErrorAttributeArray
 */

$__exc_strMessageBody = htmlentities($__exc_strMessage, null, null, false);
$__exc_strMessageBody = str_replace(" ", "&nbsp;", str_replace("\n", "<br/>\n", $__exc_strMessageBody));
$__exc_strMessageBody = str_replace(":&nbsp;", ": ", $__exc_strMessageBody);

if (file_exists($__exc_strFilename)) {
	$__exc_objFileArray = file($__exc_strFilename);
} else {
	$__exc_objFileArray = array();
}

header("HTTP/1.1 500 Internal Server Error");
?>
<!DOCTYPE html>
<?php
if (stristr($__exc_strMessage, "Invalid Form State Data") !== false) {
	// It was a invalid form state data
	// We return this string because invalid form state data error response does not behave like other errors
	// and gets unable to render the QDialogBox for the error. Since qcubed.js searches for '<html>' in the beginning
	// of the response to display it in the new Window, the following line will circumvent that behavior
	echo '<!-- -->';
}
?>
<html>
	<head>
		<title>PHP <?php _p($__exc_strType); ?> - <?php _p($__exc_strMessage); ?></title>
		<style type="text/css">@import url("<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__); ?>/styles.css");</style>
	</head>
	<body>
		<header>
			<span><?php _p($__exc_strType); ?> in PHP Script</span> <?php _p($_SERVER["PHP_SELF"]); ?>
		</header>
		<section id="content">
			<h1><?php _p($__exc_strMessageBody, false); ?></h1>
			<p><strong><?php _p($__exc_strType); ?> Type:</strong> <?php _p($__exc_strObjectType); ?></p>
<?php
			if (isset($__exc_strRenderedPage)) {
				$_SESSION['RenderedPageForError'] = $__exc_strRenderedPage;
?>
				<p><strong>Rendered Page:</strong>
					<a target="_blank" href="<?php _p(__VIRTUAL_DIRECTORY__ . __PHP_ASSETS__); ?>/error_already_rendered_page.php">Click here to view contents able to be rendered.</a>
				</p>
<?php
			}
?>
			<p><strong>Source File:</strong> <?php _p($__exc_strFilename); ?> <strong>Line:</strong> <?php _p($__exc_intLineNumber); ?></p>

			<pre><code><?php
			for ($__exc_intLine = max(1, $__exc_intLineNumber - 5); $__exc_intLine <= min(count($__exc_objFileArray), $__exc_intLineNumber + 5); $__exc_intLine++) {
				if ($__exc_intLineNumber == $__exc_intLine){
					printf("<span class='warning'>Line %s:    %s</span>", $__exc_intLine, htmlentities($__exc_objFileArray[$__exc_intLine - 1]));
				}else{
					printf("Line %s:    %s", $__exc_intLine, htmlentities($__exc_objFileArray[$__exc_intLine - 1]));
				}
			}
?></code></pre>
<?php
			if (defined('ERROR_EMAIL')) {
				$style = '';
			} else {
				$style = 'display: none;';
			}

			if (isset($__exc_objErrorAttributeArray)) {
				foreach ($__exc_objErrorAttributeArray as $__exc_objErrorAttribute) {
					printf("<p><strong>%s:</strong>&nbsp;&nbsp;", $__exc_objErrorAttribute->Label);
					$__exc_strJavascriptLabel = str_replace(" ", "", $__exc_objErrorAttribute->Label);
					if ($__exc_objErrorAttribute->MultiLine) {
						printf("\n<a href=\"#\" onclick=\"ToggleHidden('%s'); return false;\">Show/Hide</a></p>", $__exc_strJavascriptLabel);
						printf('<pre><code id="%s" %s >%s</code></pre>', $__exc_strJavascriptLabel, $style, htmlentities($__exc_objErrorAttribute->Contents));
					} else {
						printf("%s</p>\n", htmlentities($__exc_objErrorAttribute->Contents));
					}
				}
			}
?>

			<p><strong>Call Stack:</strong></p>
			<pre><code><?php _p($__exc_strStackTrace); ?></code></pre>

			<p><strong>Variable Dump:</strong> <a href="#" onclick="ToggleHidden('VariableDump'); return false;">Show/Hide</a></p>
			<pre><code id="VariableDump" <?php if (!defined('ERROR_EMAIL')) { ?> style="display: none;" <?php } ?> ><?php
				// Dump All Variables
				foreach ($GLOBALS as $__exc_Key => $__exc_Value) {
					// TODO: Figure out why this is so strange
					if (isset($__exc_Key))
						if ($__exc_Key != "_SESSION")
							global $$__exc_Key;
				}

				$__exc_ObjVariableArray = get_defined_vars();
				$__exc_ObjVariableArrayKeys = array_keys($__exc_ObjVariableArray);
				sort($__exc_ObjVariableArrayKeys);

				$__exc_StrToDisplay = "";
				$__exc_StrToScript = "";
				$varCounter = 0;
				foreach ($__exc_ObjVariableArrayKeys as $__exc_Key) {
					if ((strpos($__exc_Key, "__exc_") === false) && (strpos($__exc_Key, "_DATE_") === false) && ($__exc_Key != "GLOBALS") && !($__exc_ObjVariableArray[$__exc_Key] instanceof QForm)) {
						try {
							if (($__exc_Key == 'HTTP_SESSION_VARS') || ($__exc_Key == '_SESSION')) {
								$__exc_ObjSessionVarArray = array();
								foreach ($$__exc_Key as $__exc_StrSessionKey => $__exc_StrSessionValue) {
									if (strpos($__exc_StrSessionKey, 'qform') !== 0)
										$__exc_ObjSessionVarArray[$__exc_StrSessionKey] = $__exc_StrSessionValue;
								}
								$__exc_StrVarExport = htmlentities(var_export($__exc_ObjSessionVarArray, true));
							} else if (($__exc_ObjVariableArray[$__exc_Key] instanceof QControl) || ($__exc_ObjVariableArray[$__exc_Key] instanceof QForm)) {
								$__exc_StrVarExport = htmlentities($__exc_ObjVariableArray[$__exc_Key]->VarExport());
							} else {
								$__exc_StrVarExport = htmlentities(var_export($__exc_ObjVariableArray[$__exc_Key], true));
							}

							$__exc_StrToDisplay .= sprintf("<a style='display:block' href='#%s' onclick='javascript:ToggleHidden(\"%s\"); return false;'>%s</a>", $varCounter, $varCounter, $__exc_Key);

							$__exc_StrToDisplay .= sprintf("<span id=\"%s\" %s >%s</span>", $varCounter, $style, $__exc_StrVarExport);
							$varCounter++;
						} catch (Exception $__exc_objExcOnVarDump) {
							$__exc_StrToDisplay .= sprintf("Fatal error:  Nesting level too deep - recursive dependency?\n", $__exc_objExcOnVarDump->getMessage());
						}
					}
				}

				_p($__exc_StrToDisplay, false);
?></code></pre>
		</section>

		<hr />
		
		<p style="text-align: center;"><em><?php _p($__exc_strType); ?> Report Generated:&nbsp;<?php _p(date('l, F j Y, g:i:s A')); ?></em></p>
		
		<footer>
			<strong>PHP Version:</strong> <?php _p(PHP_VERSION); ?>;&nbsp;<strong>Zend Engine Version:</strong> <?php _p(zend_version()); ?>;&nbsp;<strong>QCubed Version:</strong> <?php _p(QCUBED_VERSION); ?><br />
			<?php if (array_key_exists('OS', $_SERVER)) printf('<strong>Operating System:</strong> %s;&nbsp;&nbsp;', $_SERVER['OS']); ?><strong>Application:</strong> <?php _p($_SERVER['SERVER_SOFTWARE']); ?>;&nbsp;<strong>Server Name:</strong> <?php _p($_SERVER['SERVER_NAME']); ?><br />
			<strong>HTTP User Agent:</strong> <?php _p(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'N/A'); ?>
		</footer>
	<?php printf('<script type="text/javascript">%s</script>', $__exc_StrToScript); ?>
	<script type="text/javascript">
		function ToggleHidden(strDiv) {
			var obj = document.getElementById(strDiv);
			var stlSection = obj.style;
			var isCollapsed = obj.style.display.length;
			if (isCollapsed) { stlSection.display = ''; }else{ stlSection.display = 'none'; }
		}
	</script>
</body>
</html>
