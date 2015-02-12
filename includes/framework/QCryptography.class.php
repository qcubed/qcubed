<?php
	// Requires libmcrypt v2.4.x or higher

	/**
	 * Class QCryptographyException: If an exception occurs in the QCryptography class, we use this one to handle
	 */
	class QCryptographyException extends QCallerException {
	}

	/**
	 * Class QCryptography: Helps in encrypting and decrypting data
	 * It depends on the mcrypt module.
	 * Refer: http://php.net/manual/en/book.mcrypt.php
	 */
	class QCryptography extends QBaseClass {
		/** @var resource Mcrypt algorithm module resource */
		protected $objMcryptModule;
		/** @var bool Are we going to use Base 64 encoding? */
		protected $blnBase64;

		/** @var string Key to be used for encryption/decryption - used for mycrypt_generic_init */
		protected $strKey;
		/** @var string Initialization vector for the algorithm */
		protected $strIv;

		/**
		 * Default Base64 mode for any new QCryptography instances that get constructed.
		 * This is similar to MIME-based Base64 encoding/decoding, but is safe to use
		 * in URLs, POST/GET data, and any other text-based stream.
		 * Note that by setting Base64 to true, it will result in an encrypted data string
		 * that is 33% larger.
		 *
		 * @var string Base64
		 */
		public static $Base64 = true;

		/**
		 * Default Key for any new QCryptography instances that get constructed
		 *
		 * @var string Key
		 */
		public static $Key = "qc0Do!d3F@lT.k3Y";

		/**
		 * The Random Number Generator the library uses to generate the IV:
		 *  - MCRYPT_DEV_RANDOM = /dev/random (only on *nix systems)
		 *  - MCRYPT_DEV_URANDOM = /dev/urandom (only on *nix systems)
		 *  - MCRYPT_RAND = the internal PHP srand() mechanism
		 * (on Windows, you *must* use MCRYPT_RAND, b/c /dev/random and /dev/urandom doesn't exist)
		 * TODO: there appears to be some /dev/random locking issues on the QCubed development
		 * environment (using Fedora Core 3 with PHP 5.0.4 and LibMcrypt 2.5.7).  Because of this,
		 * we are using MCRYPT_RAND be default.  Feel free to change to to /dev/*random at your own risk.
		 *
		 * @param null|string $strKey          Encryption key
		 * @param null|bool   $blnBase64       Are we going to use Base 64 encoded data?
		 * @param null|string $strCipher       Cipher to be used (default is MCRYPT_TRIPLEDES)
		 * @param null|string $strMode         Mode (default is MCRYPT_MODE_ECB)
		 * @param null|string $strRandomSource Random source (default is MCRYPT_RAND)
		 *
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QCryptographyException
		 */
		public function __construct($strKey = null, $blnBase64 = null, $strCipher = null, $strMode = null,
		                            $strRandomSource = null) {
			if (!function_exists('mcrypt_module_open')) {
				throw new QCryptographyException("PHP cryptography components (libmcrypt module) are not installed");
			}

			// Get the Key
			if (is_null($strKey)) {
				$strKey = self::$Key;
			}

			// Get the Base64 Flag
			try {
				if (is_null($blnBase64)) {
					$this->blnBase64 = QType::Cast(self::$Base64, QType::Boolean);
				} else {
					$this->blnBase64 = QType::Cast($blnBase64, QType::Boolean);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Get the Cipher
			if (is_null($strCipher)) {
				$strCipher = MCRYPT_TRIPLEDES;
			}

			// Get the Mode
			if (is_null($strMode)) {
				$strMode = MCRYPT_MODE_ECB;
			}

			if (is_null($strRandomSource)) {
				$strRandomSource = MCRYPT_RAND;
			}

			$this->objMcryptModule = mcrypt_module_open($strCipher, null, $strMode, null);
			if (!$this->objMcryptModule) {
				throw new QCryptographyException('Unable to open LibMcrypt Module');
			}

			// Determine IV Size
			$intIvSize = mcrypt_enc_get_iv_size($this->objMcryptModule);

			// Create the IV
			if ($strRandomSource != MCRYPT_RAND) {
				// Ignore All Warnings
				set_error_handler('QcodoHandleError', 0);
				$intCurrentLevel = error_reporting();
				error_reporting(0);
				$strIv = mcrypt_create_iv($intIvSize, $strRandomSource);
				error_reporting($intCurrentLevel);
				restore_error_handler();

				// If the RandomNumGenerator didn't work, we revert back to using MCRYPT_RAND
				if (strlen($strIv) != $intIvSize) {
					srand();
					$strIv = mcrypt_create_iv($intIvSize, MCRYPT_RAND);
				}
			} else {
				srand();
				$strIv = mcrypt_create_iv($intIvSize, MCRYPT_RAND);
			}

			$this->strIv = $strIv;

			// Determine KeySize length
			$intKeySize = mcrypt_enc_get_key_size($this->objMcryptModule);

			// Create the Key Based on Key Passed In
			$this->strKey = substr(md5($strKey), 0, $intKeySize);
		}

		/**
		 * Encrypt the data (depends on the value of class memebers)
		 * @param string $strData
		 *
		 * @return mixed|string
		 * @throws QCryptographyException
		 */
		public function Encrypt($strData) {
			// Initialize Encryption
			$intReturnValue = mcrypt_generic_init($this->objMcryptModule, $this->strKey, $this->strIv);
			if (($intReturnValue === false) || ($intReturnValue < 0)) {
				throw new QCryptographyException('Incorrect Parameters used in LibMcrypt Initialization');
			}
			// Add Length to strData
			$strData = strlen($strData) . '/' . $strData;

			$strEncryptedData = mcrypt_generic($this->objMcryptModule, $strData);
			if ($this->blnBase64) {
				$strEncryptedData = base64_encode($strEncryptedData);
				$strEncryptedData = str_replace('+', '-', $strEncryptedData);
				$strEncryptedData = str_replace('/', '_', $strEncryptedData);
				$strEncryptedData = str_replace('=', '', $strEncryptedData);
			}


			// Deinitialize Encryption
			if (!mcrypt_generic_deinit($this->objMcryptModule)) {
				throw new QCryptographyException('Unable to deinitialize encryption buffer');
			}

			return $strEncryptedData;
		}

		/**
		 * Decrypt the data (depends on the value of class memebers)
		 * @param string $strEncryptedData
		 *
		 * @return string
		 * @throws QCryptographyException
		 */
		public function Decrypt($strEncryptedData) {
			// Initialize Encryption
			$intReturnValue = mcrypt_generic_init($this->objMcryptModule, $this->strKey, $this->strIv);
			if (($intReturnValue === false) || ($intReturnValue < 0)) {
				throw new QCryptographyException('Incorrect Parameters used in LibMcrypt Initialization');
			}

			if ($this->blnBase64) {
				$strEncryptedData = str_replace('_', '/', $strEncryptedData);
				$strEncryptedData = str_replace('-', '+', $strEncryptedData);
				$strEncryptedData = base64_decode($strEncryptedData);
			}
			$intBlockSize = mcrypt_enc_get_block_size($this->objMcryptModule);
			$strDecryptedData = mdecrypt_generic($this->objMcryptModule, $strEncryptedData);

			// Figure Out Length and Truncate
			$intPosition = strpos($strDecryptedData, '/');
			if (!$intPosition) {
				throw new QCryptographyException('Invalid Length Header in Decrypted Data');
			}
			$intLength = substr($strDecryptedData, 0, $intPosition);
			$strDecryptedData = substr($strDecryptedData, $intPosition + 1);
			$strDecryptedData = substr($strDecryptedData, 0, $intLength);

			// Deinitialize Encryption
			if (!mcrypt_generic_deinit($this->objMcryptModule)) {
				throw new QCryptographyException('Unable to deinitialize encryption buffer');
			}

			return $strDecryptedData;
		}

		/**
		 * Encrypt a file (depends on the value of class memebers)
		 *
		 * @param string $strFile Path of the file to be encrypted
		 *
		 * @return mixed|string
		 * @throws QCallerException|QCryptographyException
		 */
		public function EncryptFile($strFile) {
			if (file_exists($strFile)) {
				$strData = file_get_contents($strFile);

				return $this->Encrypt($strData);
			} else {
				throw new QCallerException('File does not exist: ' . $strFile);
			}
		}

		/**
		 * Decrypt a file (depends on the value of class memebers)
		 *
		 * @param string $strFile File to be decrypted
		 *
		 * @return string
		 * @throws QCallerException|QCryptographyException
		 */
		public function DecryptFile($strFile) {
			if (file_exists($strFile)) {
				$strEncryptedData = file_get_contents($strFile);

				return $this->Decrypt($strEncryptedData);
			} else {
				throw new QCallerException('File does not exist: ' . $strFile);
			}
		}

		/**
		 * Closes the mcrypt module
		 * (Some methods just want to watch the world burn!)
		 */
		public function __destruct() {
			if ($this->objMcryptModule) {
				// Ignore All Warnings
				set_error_handler('QcodoHandleError', 0);
				$intCurrentLevel = error_reporting();
				error_reporting(0);
				mcrypt_module_close($this->objMcryptModule);
				error_reporting($intCurrentLevel);
				restore_error_handler();
			}
		}

		public function __sleep() {
			throw new Exception ('Cannot serialize QCryptography');
		}
	}

?>