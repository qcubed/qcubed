<?php

/**
 * Class QCryptographyException: If an exception occurs in the QCryptography class, we use this one to handle
 */
class QCryptographyException extends QCallerException {
}

/**
 * Class QCryptography: Helps in encrypting and decrypting data using block ciphers.
 *
 * Uses the openssl_* methods
 *
 * Can use a variety of encryption methods, but the default method, AES-256-CBC, is recommended.
 *
 * When using a CBC method, an initialization vector("IV") must be generated and saved. Note that this should NOT use a static value,
 * as it will defeat the purpose of the IV. It must be random. This class will automatically generate a random IV
 * for you, but you must be aware of how this IV is saved. There are two ways to do it:
 *
 * 1) Serialize the instance of this class and save it after you initialize it. You must be sure to save it in a safe place since it
 * 	  contains your encryption key. For example, you can make it a form variable, or a session variable,
 * 	  making sure your form or session data is secure and
 *    cannot be seen by a user, then when the instance gets unserialized, the IV will be restored automatically.
 * 	  Storing the instance in a QApplication or global variable will not work, since these things are reinitialized every
 *    time PHP starts up, and you will get a different IV at that time. If you do not correctly
 * 	  restore the IV that was used to Encrypt, that you will not be able to Decrypt.
 *
 * 2) Pass a value to $strIvHashKey in the constructor, and the initialization vector will be appended to the resulting encrypted data.
 *    This hash key SHOULD be a static value that is part of your app and must be passed to the constructor of any instance of
 * 	  QCryptography that will be used to decrypt the data. This gives you the ability to decrypt the value without needing to save
 * 	  the IV or rely on serialized instance of this class.
 * 	  Note that appending the IV to the encrypted data does not compromise the encrypted data at all, but it will make the data
 * 	  larger. If you are doing block-level ciphering and you want the resulting encryption to be the same size as the given data,
 * 	  you must be aware of that.
 */

class QCryptography extends QBaseClass {
	/**
	 * Constant to indicate that Random IV is to be automatically generated.
	 * To generate a valid random IV, pass it in the class constuctor at the place of IV
	 *
	 * We use a single digit because no cipher algorithm requires an IV of length 1.
	 */
	const IV_RANDOM = 'R';

	/** @var bool Are we going to use Base 64 encoding? */
	protected $blnBase64;
	/** @var string Key to be used for encryption/decryption */
	protected $strKey;
	/**
	 * @var string Initialization vector for the algorithm
	 *
	 *             Note that this is NOT used in ECB modes
	 */
	protected $strIv = '';
	/** @var  string Cipher to use when creating the encryption object */
	protected $strCipher;

	/** @var  string The hash key to use when protecting the embedded IV, if requested. */
	protected $strIvHashKey;

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

	/*
	 * @param null|string $strKey    Encryption key
	 * @param null|bool   $blnBase64 Are we going to Base 64 the encoded data?
	 * @param null|string $strCipher Cipher to be used (default is AES-256-CBC)
	 * @param null|string $strIvHash  A hash key to use. If given, will cause the IV to be added to the end of encrypted values so its
	 * 								  possible to decrypt the value if the IV is lost. See above discussion.
	 *
	 * @throws QCallerException
	 * @throws QCryptographyException
	 */
	public function __construct($strKey = null, $blnBase64 = null, $strCipher = null, $strIvHashKey = null) {

		$this->strIvHashKey = $strIvHashKey;

		// Get the Key
		if (is_null($strKey)) {
			if (!defined('QCRYPTOGRAPHY_DEFAULT_KEY')) {
				throw new Exception('To use QCryptography, either pass in a key, or define QCRYPTOGRAPHY_DEFAULT_KEY in your config file');
			}
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
		if (defined('QCRYPTOGRAPHY_DEFAULT_CIPHER')) {
			$this->strCipher = QCRYPTOGRAPHY_DEFAULT_CIPHER;
		} elseif ($strCipher) {
			$this->strCipher = $strCipher;
		} else {
			$this->strCipher = 'AES-256-CBC';
		}
		// User has supplied a cipher-name
		// We make sure that the Cipher name was correct/exists
		try {
			// The following method will automatically test for availability of the supplied cipher name
			$strIvLength = openssl_cipher_iv_length($this->strCipher);
		} catch (Exception $e) {
			throw new QCallerException('No Cipher with name ' . $this->strCipher . ' could be found in openssl library');
		}

		if($strIvLength == 0) {
			// IV is not needed for the selected algorithm (it could be a ECB algorithm)
			$this->strIv = null;
		} else {
			// Generate random IV
			$this->strIv = openssl_random_pseudo_bytes($strIvLength);
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

		if ($this->strIvHashKey) {
			// User has asked to include the IV with the encrypted string so that we do not have to store the IV somewhere else.
			/**
			 * Based on http://pastebin.com/sN6buivY
			 * True will append the initialization vector and a hash value to the end of the cryptography so that:
			 * a) The crypto can be unencrypted without having to save the IV
			 * b) The crypto can be tested for tampering using the hash.
			 *
			 * Will increase the size of the resulting value by the size of the IV + about 50%. The other option is to serialize the class or in
			 * some other way save the IV and restore it later.
			 */
			$strEncryptedData .= '=' . bin2hex($this->strIv);
			$strEncryptedData .= '=' . hash_hmac('sha256', $strEncryptedData, $this->strIvHashKey);
		}

		if ($this->blnBase64) {
			$strEncryptedData = QString::Base64UrlSafeEncode($strEncryptedData);
		}

		return $strEncryptedData;
	}

	/**
	 * Decrypt the data
	 *
	 * @param string $strEncryptedData
	 *
	 * @return string
	 * @throws QCryptographyException
	 */
	public function Decrypt($strEncryptedData) {
		if ($this->blnBase64) {
			$strEncryptedData = QString::Base64UrlSafeDecode($strEncryptedData);
		}
		$strIv = $this->strIv;
		if ($this->strIvHashKey) {
			$offset = strrpos($strEncryptedData, "=");
			if ($offset === null) {
				throw new QCryptographyException("Hash value not found.");
			}
			$hash1 = substr($strEncryptedData, $offset + 1);
			$strEncryptedData = substr($strEncryptedData, 0, $offset);

			// check for tampering
			$hash2 = hash_hmac('sha256', $strEncryptedData, $this->strIvHashKey);
			if ($hash1 != $hash2) {
				throw new QCryptographyException("Encryption tampering detected");
			}

			$offset2 = strrpos($strEncryptedData, "=");

			if ($offset2 === null) {
				throw new QCryptographyException("IV not found.");
			}
			$strIv = substr($strEncryptedData, $offset2 + 1);
			$strIv = hex2bin($strIv);	// undo our serialization encoding
			$strEncryptedData = substr($strEncryptedData, 0, $offset2);
		}
		$strDecryptedData = openssl_decrypt($strEncryptedData, $this->strCipher, $this->strKey, 0, $strIv);

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
}