<?php 
	require_once('./qcubed.inc.php');

	//Exit gracefully if called directly or profiling data is missing.
	if ( !isset($_POST['intDatabaseIndex']) && !isset($_POST['strProfileData']) && !isset($_POST['strReferrer']) )
		exit('Nothing to profile. No Database Profiling data recived.');

	if ( !isset($_POST['intDatabaseIndex']) || !isset($_POST['strProfileData']) || !isset($_POST['strReferrer']) )
		throw new Exception('Database Profiling data appears to have been corrupted.');

	$intDatabaseIndex = intval($_POST['intDatabaseIndex']);
	$strReferrer = QApplication::HtmlEntities($_POST['strReferrer']);

	$objProfileArray = unserialize(base64_decode($_POST['strProfileData']));
	$objProfileArray = QType::Cast($objProfileArray, QType::ArrayType);
	$intCount = count($objProfileArray);
?>
<!DOCTYPE html>
<html>
<head>
	<title>QCubed Development Framework - Database Profiling Tool</title>
	<style type="text/css">@import url("<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__); ?>/corepage.css");</style>
	<script type="text/javascript">
		function Toggle(strWhatId, strButtonId) {
			var obj = document.getElementById(strWhatId);
			var objButton = document.getElementById(strButtonId);

			if (obj && objButton) {
				if (obj.style.display == "block") {
					obj.style.display = "none";
					objButton.innerHTML = "Show";
				}
				else {
					obj.style.display = "block";
					objButton.innerHTML = "Hide";
				}
			}
			return false;
		}

		function ShowAll() {
			for (var intIndex = 1; intIndex <= <?php _p($intCount); ?>; intIndex++) {
				var objQuery = document.getElementById('query' + intIndex);
				var objButton = document.getElementById('button' + intIndex);
				objQuery.style.display = "block";
				objButton.innerHTML = "Hide";
			}
			return false;
		}

		function HideAll() {
			for (var intIndex = 1; intIndex <= <?php _p($intCount); ?>; intIndex++) {
				var objQuery = document.getElementById('query' + intIndex);
				var objButton = document.getElementById('button' + intIndex);
				objQuery.style.display = "none";
				objButton.innerHTML = "Show";
			}
			return false;
		}
	</script>
</head>
<body>
	<div id="container">
		<div id="headerContainer">
			<div id="headerBorder">
				<div id="header">
					<div id="hleft">
						<span class="hsmall">QCubed Development Framework <?php echo QCUBED_VERSION_NUMBER_ONLY ?></span><br/>
						<span class="hbig">Database Profiling Tool</span>
					</div>
					<div id="hright">
						<b>Database Index:</b> <?php _p($intDatabaseIndex); ?>&nbsp;&nbsp;
						<b>Database Type:</b> <?php _p(QApplication::$Database[$intDatabaseIndex]->Adapter); ?><br/>
						<b>Database Server:</b> <?php _p(QApplication::$Database[$intDatabaseIndex]->Server); ?>&nbsp;&nbsp;
						<b>Database Name:</b> <?php _p(QApplication::$Database[$intDatabaseIndex]->Database); ?><br/>
						<b>Profile Generated From:</b> <?php _p($strReferrer); ?>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>

	<div id="content">
		<span class="title">
<?php
		switch ($intCount) {
			case 0: _p('<b>There were no queries that were performed.</b>', false); break;
			case 1: _p('<b>There was 1 query that was performed.</b>', false); break;
			default: printf('<b>There were %s queries that were performed.</b>', $intCount); break;
		};
?>
		</span>
		<br/>
		<br/>
		<a href="#" onClick="return ShowAll();" class="smallbutton">Show All</a>
		<a href="#" onClick="return HideAll();" class="smallbutton">Hide All</a>
		<br/>
		<br/>
<?php
		$intIndex = 1;

		foreach( $objProfileArray as $objProfile) {
			$objBacktrace = $objProfile['objBacktrace'];
			$strQuery = $objProfile['strQuery'];
			$dblTimeInfo = $objProfile['dblTimeInfo'];

			$objArgs = (array_key_exists('args', $objBacktrace)) ? $objBacktrace['args'] : array();
			$strClass = (array_key_exists('class', $objBacktrace)) ? $objBacktrace['class'] : null;
			$strType = (array_key_exists('type', $objBacktrace)) ? $objBacktrace['type'] : null;
			$strFunction = (array_key_exists('function', $objBacktrace)) ? $objBacktrace['function'] : null;
			$strFile = (array_key_exists('file', $objBacktrace)) ? $objBacktrace['file'] : null;
			$strLine = (array_key_exists('line', $objBacktrace)) ? $objBacktrace['line'] : null;
?>
			<span class="function">
				Called by <?php _p($strClass . $strType . $strFunction . '(' . implode(', ', $objArgs) . ')'); ?>
				<a href="#" onClick="return Toggle('query<?php _p($intIndex); ?>', 'button<?php _p($intIndex); ?>')" id="button<?php _p($intIndex); ?>" class="smallbutton">
					Show
				</a>
			</span>&nbsp;&nbsp;<br/>
			<span class="function_details">
				<b>File: </b><?php _p($strFile); ?>; &nbsp;&nbsp;<b>Line: </b><?php _p($strLine); ?>
			</span>
			<pre id="query<?php _p($intIndex); ?>" style="display: none"><code><?php _p($strQuery); ?></code></pre>
<?php
			//mark slow query - those that take over 1 second
			if($dblTimeInfo >= 1)
				echo('<div class="time slow">');
			else
				echo'<div class="time">';
			printf("Query took %.1f ms", $dblTimeInfo * 1000);
			echo '</div>';
?>
			<br/>
<?php
			$intIndex++;
		}
?>			</div>
	</div>
	<script>
		if (<?php _p($intCount); ?> <= 5) ShowAll();
	</script>
</body>
</html>
