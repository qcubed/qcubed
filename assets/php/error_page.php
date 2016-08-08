<?php
	// Generate the Error Dump
	if (!ob_get_level()) { ob_start(); }
	require(__DOCROOT__ . __PHP_ASSETS__ . '/error_dump.php');

	// Do We Log???
	if (defined('ERROR_LOG_PATH') && ERROR_LOG_PATH && defined('ERROR_LOG_FLAG') && ERROR_LOG_FLAG) {
		// Log to File in ERROR_LOG_PATH
		$strContents = ob_get_contents();

		QApplication::MakeDirectory(ERROR_LOG_PATH, 0777);
		$strFileName = ERROR_LOG_PATH . '/' . date('Y-m-d-H-i-s-' . rand(100,999)) . '.html';
		file_put_contents($strFileName, $strContents);
		@chmod($strFileName, 0666);
	}

	if (defined('ERROR_EMAIL')) {
		$objEmail = new QEmailMessage();
		$objEmail->From = ERROR_EMAIL_FROM;
		$objEmail->Subject = ERROR_EMAIL_SUBJECT;
		$objEmail->To = ERROR_EMAIL;
		$strContents = ob_get_contents();
		$objEmail->HtmlBody = $strContents;

		QEmailServer::Send($objEmail);
	}

	if (QApplication::$RequestMode == QRequestMode::Ajax) {
		if (defined('ERROR_FRIENDLY_AJAX_MESSAGE') && ERROR_FRIENDLY_AJAX_MESSAGE) {
			// Reset the Buffer
			while(ob_get_level()) ob_end_clean();

			//$strAlertMsg = str_replace('"', '\\"', ERROR_FRIENDLY_AJAX_MESSAGE);
			$strMsg = str_replace('\r\n', '<br />', ERROR_FRIENDLY_AJAX_MESSAGE);
			echo '<p>' . $strMsg . '</p>';
		}
	} else {
		if (defined('ERROR_FRIENDLY_PAGE_PATH') && ERROR_FRIENDLY_PAGE_PATH) {
			// Reset the Buffer
			while(ob_get_level()) ob_end_clean();
			header("HTTP/1.1 500 Internal Server Error", null, 500);
			require(__DOCROOT__ . ERROR_FRIENDLY_PAGE_PATH);
		}
	}
