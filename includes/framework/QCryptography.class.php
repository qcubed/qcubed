<?php
	// Requires libmcrypt v2.4.x or higher

	/**
	 * Class QCryptographyException: If an exception occurs in the QCryptography class, we use this one to handle
	 */
	class QCryptographyException extends QCallerException {
	}

	/**
	 * Class QCryptography: Helps in encrypting and decrypting data
	 * Uses the openssl_* methods
	 */
	class QCryptography extends QBaseClass {
		/**
		 * Constant to indicate that Random IV is to be automatically generated.
		 * To generate a valid random IV, pass it in the class constuctor at the place of IV
		 *
		 * We use a single digit because no cipher algorithm requires an IV of length 1.
		 */
		const IV_RANDOM = 'R';

		/** @var resource Mcrypt algorithm module resource */
		protected $objMcryptModule;
		/** @var bool Are we going to use Base 64 encoding? */
		protected $blnBase64;
		/** @var string Key to be used for encryption/decryption - used for mycrypt_generic_init */
		protected $strKey;
		/**
		 * @var string Initialization vector for the algorithm
		 *
		 *             Note that this is NOT used in ECB modes
		 */
		protected $strIv = '';
		/** @var  string Cipher to use when creating the encryption object */
		protected $strCipher;
		/** @var  string Mode to use when creating the encryption object */
		protected $strMode;


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
		 * The Random Number Generator the library uses to generate the IV:
		 *  - MCRYPT_DEV_RANDOM = /dev/random (only on *nix systems)
		 *  - MCRYPT_DEV_URANDOM = /dev/urandom (only on *nix systems)
		 *  - MCRYPT_RAND = the internal PHP srand() mechanism
		 * (on Windows, you *must* use MCRYPT_RAND, b/c /dev/random and /dev/urandom doesn't exist)
		 * TODO: there appears to be some /dev/random locking issues on the QCubed development
		 * environment (using Fedora Core 3 with PHP 5.0.4 and LibMcrypt 2.5.7).  Because of this,
		 * we are using MCRYPT_RAND be default.  Feel free to change to to /dev/*random at your own risk.
		 *
		 * @param null|string $strKey    Encryption key
		 * @param null|bool   $blnBase64 Are we going to use Base 64 encoded data?
		 * @param null|string $strCipher Cipher to be used (default is AES-256-CBC)
		 * @param null|string $strIv     Initialization Vector
		 *
		 * @throws QCallerException
		 * @throws QCryptographyException
		 */
		public function __construct($strKey = null, $blnBase64 = null, $strCipher = null, $strIv = null) {

			// Get the Key
			if (is_null($strKey)) {
				$this->strKey = QCRYPTOGRAPHY_DEFAULT_KEY;
			} else {
				$this->strKey = $strKey;
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
				$this->strCipher = 'AES-256-CBC';
			} else {
				// User has supplied a cipher-name
				// We make sure that the Cipher name was correct/exists
				try {
					// Set the cipher
					$this->strCipher = $strCipher;

					// The following method will automatically test for availability of the supplied cipher name
					$strIvLength = openssl_cipher_iv_length($strCipher);
				} catch (Exception $e) {
					throw new QCallerException('No Cipher with name ' . $strCipher . ' could be found in openssl library');
				}
			}

			// Set the correct IV
			$strIvLength = openssl_cipher_iv_length($this->strCipher);
			if($strIvLength == 0) {
				// IV is not needed for the selected algorithm (it could be a ECB algorithm)
				$this->strIv = null;
			} elseif(!$strIv) {
				// If the IV was not supplied, we will use the default
				$this->strIv = QCRYPTOGRAPHY_DEFAULT_IV;
			} elseif($strIv == self::IV_RANDOM) {
				// If Random IV was requested
				$this->strIv = openssl_random_pseudo_bytes($strIvLength);
			} else {
				// set whatever was supplied
				$this->strIv = $strIv;
			}

			// Finally test that the selected IV length suits the supplied IV
			if(strlen($this->strIv) != openssl_cipher_iv_length($this->strCipher)) {
				throw new QCallerException($this->strCipher . ' needs a cipher of ' . $strIvLength . ' characters. IV of ' . strlen($this->strIv) . ' suppled.');
			}
		}

		/**
		 * Encrypt the data (depends on the value of class members)
		 * @param string $strData
		 *
		 * @return mixed|string
		 * @throws QCryptographyException
		 */
		public function Encrypt($strData) {
			$strEncryptedData = openssl_encrypt($strData, $this->strCipher, $this->strKey, 0, $this->strIv);

			if ($this->blnBase64) {
				$strEncryptedData = base64_encode($strEncryptedData);
				$strEncryptedData = str_replace('+', '-', $strEncryptedData);
				$strEncryptedData = str_replace('/', '_', $strEncryptedData);
				$strEncryptedData = str_replace('=', '', $strEncryptedData);
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
			if ($this->blnBase64) {
				$strEncryptedData = str_replace('_', '/', $strEncryptedData);
				$strEncryptedData = str_replace('-', '+', $strEncryptedData);
				$strEncryptedData = base64_decode($strEncryptedData);
			}
			$strDecryptedData = openssl_decrypt($strEncryptedData, $this->strCipher, $this->strKey, 0, $this->strIv);

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

		}

		public function __sleep() {
			return array_diff(array_keys(get_object_vars($this)), array('objMcryptModule')); // can't serialize the module, must recreate
		}

		public function __wakeup() {
			$this->objMcryptModule = mcrypt_module_open($this->strCipher, null, $this->strMode, null);
		}

	}