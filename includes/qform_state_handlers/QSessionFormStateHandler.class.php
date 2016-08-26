<?php
	/**
	 * Session-based FormState handler.  Uses PHP Sessions to store the form state.
	 * 
	 * Stores the variables in the following format:
	 * $_SESSION['qformstate'][form_uniquid][state#]
	 *   where the form_uniquid is a unique id that sticks with the window that the
	 *   form is on, and state# is the formstate associated with that window. Multiple
	 *   formstates need to be saved to support the browser back button.
	 * 
	 * If requested by QForm, the index will be encrypted.
	 * 
	 * This incorporates a system of garbage collection that will allow for at most BackButtonMax
	 * formstates to be saved in the session.
	 *
	 * This handler is compatible with asynchronous ajax calls.
	 *
	 */
	class QSessionFormStateHandler extends QBaseClass {
		
		public static $BackButtonMax = 20; // maximum number of back button states we remember
		
		public static function Save($strFormState, $blnBackButtonFlag) {
			// Compress (if available)
			if (function_exists('gzcompress'))
				$strFormState = gzcompress($strFormState, 9);
				
			if (empty($_POST['Qform__FormState'])) {
				// no prior form state, so create a new one.
				$strFormInstance = uniqid();
				$intFormStateIndex = 1;
			} else {
				$strPriorState = $_POST['Qform__FormState'];
				
				if (!is_null(QForm::$EncryptionKey)) {
					// Use QCryptography to Decrypt
					$objCrypto = new QCryptography(QForm::$EncryptionKey, true);
					$strPriorState = $objCrypto->Decrypt($strPriorState);
				}
				
				$a = explode ('_', $strPriorState);
				if (count ($a) == 2 && 
						is_numeric ($a[1]) &&
						!empty($_SESSION['qformstate'][$a[0]]['index'])) {
					$strFormInstance = $a[0];
					$intFormStateIndex = $_SESSION['qformstate'][$a[0]]['index'];
					if ($blnBackButtonFlag) { // can we reuse current state info?
						$intFormStateIndex ++; // nope
						
						// try to garbage collect
						if (count($_SESSION['qformstate'][$a[0]]) > self::$BackButtonMax) {
							foreach ($_SESSION['qformstate'][$a[0]] as $key=>$val) {
								if (is_numeric($key) && $key < $_SESSION['qformstate'][$a[0]]['index'] - self::$BackButtonMax) {
									unset ($_SESSION['qformstate'][$a[0]][$key]);
								}
							}
						}
					}
				} else {
					// couldn't find old session variables, so create new one
					$strFormInstance = uniqid();
					$intFormStateIndex = 1;
				}
			}
				
			// Setup current state variable
			if (empty($_SESSION['qformstate'])) {
				$_SESSION['qformstate'] = array();
			} 
			if (empty($_SESSION['qformstate'][$strFormInstance])) {
				$_SESSION['qformstate'][$strFormInstance] = array();
			} 
			
			$_SESSION['qformstate'][$strFormInstance]['index'] = $intFormStateIndex;
			$_SESSION['qformstate'][$strFormInstance][$intFormStateIndex] = $strFormState;
			
			$strPostDataState = $strFormInstance . '_' . $intFormStateIndex;
			
			// Return StateIndex
			if (!is_null(QForm::$EncryptionKey)) {
				// Use QCryptography to Encrypt
				$objCrypto = new QCryptography(QForm::$EncryptionKey, true);
				return $objCrypto->Encrypt($strPostDataState);
			} else
				return $strPostDataState;
		}

		public static function Load($strPostDataState) {
			// Pull Out intStateIndex
			if (!is_null(QForm::$EncryptionKey)) {
				// Use QCryptography to Decrypt
				$objCrypto = new QCryptography(QForm::$EncryptionKey, true);
				$strPostDataState = $objCrypto->Decrypt($strPostDataState);
			}
		
			$a = explode ('_', $strPostDataState);
			if (count ($a) == 2 && 
					is_numeric ($a[1]) &&
					!empty($_SESSION['qformstate'][$a[0]][$a[1]])) {
				$strSerializedForm = $_SESSION['qformstate'][$a[0]][$a[1]];
			} else {
				return null;
			}
			
			// Uncompress (if available)
			// NOTE: if gzcompress is used, we are restoring the *BINARY* data stream of the compressed formstate
			// In theory, this SHOULD work.  But if there is a webserver/os/php version that doesn't like
			// binary session streams, you can first base64_decode before restoring from session (see note above).
			if (function_exists('gzcompress')) {
				$strSerializedForm = gzuncompress($strSerializedForm);
			}

			return $strSerializedForm;
		}
	}