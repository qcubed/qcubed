<?php
	/**
	 * Class QFormStateHandler
	 * This is the default FormState handler, storing the base64 encoded session data
	 * (and if requested by QForm, encrypted) as a hidden form variable on the page, itself. It is meant to be a "quick
	 * and dirty" handler that works in limited situations.
	 *
	 * We recommend that you do NOT use this formstate handler in general. It sends the entire formstate back and forth
	 * to the client browser on every server and ajax request, which is slow, and could potentially reach limits quickly. It
	 * encrypts the data, but there are still potential security problems if the data is sensitive.
	 *
	 * To change the formstate handler, define the __FORM_STATE_HANDLER__ in your configuration.inc.php file. See that
	 * file for more detail.
	 *
	 * This form state handler is NOT safe to use when making asynchronous AJAX calls. The reason is that since the entire
	 * formstate is sent to the browser, each ajax call must wait for the return trip to get the new formstate, before
	 * sending the formstate back to the server on the next ajax call.
	 */

	class QFormStateHandler extends QBaseClass {
		public static function Save($strFormState, $blnBackButtonFlag) {
			// Compress (if available)
			if (function_exists('gzcompress'))
				$strFormState = gzcompress($strFormState, 9);

			if (is_null(QForm::$EncryptionKey)) {
				// Don't Encrypt the FormState -- Simply Base64 Encode it
				$strFormState = base64_encode($strFormState);

				// Cleanup FormState Base64 Encoding
				$strFormState = str_replace('+', '-', $strFormState);
				$strFormState = str_replace('/', '_', $strFormState);
			} else {
				// Use QCryptography to Encrypt
				$objCrypto = new QCryptography(QForm::$EncryptionKey, true);
				$strFormState = $objCrypto->Encrypt($strFormState);
			}
			return $strFormState;
		}

		public static function Load($strPostDataState) {
			$strSerializedForm = $strPostDataState;

			if (is_null(QForm::$EncryptionKey)) {
				// Cleanup from FormState Base64 Encoding
				$strSerializedForm = str_replace('-', '+', $strSerializedForm);
				$strSerializedForm = str_replace('_', '/', $strSerializedForm);
				
				$strSerializedForm = base64_decode($strSerializedForm);
			} else {
				// Use QCryptography to Decrypt
				$objCrypto = new QCryptography(QForm::$EncryptionKey, true);
				$strSerializedForm = $objCrypto->Decrypt($strSerializedForm);
			}

			// Uncompress (if available)
			if (function_exists('gzcompress'))
				$strSerializedForm = gzuncompress($strSerializedForm);

			return $strSerializedForm;
		}
	}