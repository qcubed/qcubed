<?php

	/**
	 * This will store the formstate in a Redis DB.
	 *
	 * This offers significant speed advantage over PHP SESSION because EACH form state
	 * is saved using a unique key with Redis, and only the form state that is needed for loading will
	 * be accessed (as opposed to with session, ALL the form states are loaded into memory
	 * every time). This is very similar to how DbBackedFormStateHandler works, only this one uses
	 * Redis for storing the FormStates
	 *
	 * Garbage collection is handled by Redis automatically because we set the FormState keys with
	 * an expiration time. The expiration time is determined by self::$intExpireFormstatesAfterDays
	 * variable which is the number of days after which the FormState is automatically deleted by
	 * Redis. As such, the minimum value should be 1.
	 *
	 * This handler can handle asynchronous calls.
	 */
	class QRedisBackedFormStateHandler extends QBaseClass {
		/**
		 * The number of days after which the formstate will expire.
		 * Expiration will happen at exact time (number specified here * 24 hours) after the creation.
		 *
		 * Value must be a positive integer
		 *
		 * @var integer
		 */
		public static $intExpireFormstatesAfterDays = 7;

		/** @var bool Whether to compress the formstate data. */
		public static $blnCompress = true;

		/**
		 * @var bool Whether to base64 encode the formstate data.
		 */
		public static $blnBase64 = false;

		/**
		 * Returns the client using which the formstate handler can work
		 *
		 * @return \Predis\Client
		 * @throws QCallerException
		 */
		private static function GetClient() {
			if (!class_exists('Predis\Client')) {
				throw new QCallerException('Predis library needs to be installed for Redis Formstate Handler to work');
			}

			// There must be keys named 'parameters' and 'options' in the configuration
			if (defined('__REDIS_BACKED_FORM_STATE_HANDLER_CONFIG__')) {
				$objOptionsArray = unserialize(__REDIS_BACKED_FORM_STATE_HANDLER_CONFIG__);
				if (!array_key_exists('parameters', $objOptionsArray) || !array_key_exists('options', $objOptionsArray)) {
					// Needed keys do not exist
					throw new QCallerException('The configuration parameters for creating predis client in the configuration file are wrong. The config array must contain the "parameters" and "options" keys');
				}

				return new Predis\Client($objOptionsArray['parameters'], $objOptionsArray['options']);
			} else {
				return new Predis\Client();
			}
		}

		/**
		 * Save the formstate into the data store
		 *
		 * @static
		 *
		 * @param string $strFormState Serialized Formstate
		 * @param bool $blnBackButtonFlag Back button
		 *
		 * @return string
		 * @throws Exception
		 */
		public static function Save($strFormState, $blnBackButtonFlag) {
			$objClient = self::GetClient();

			$strOriginal = $strFormState;

			// compress (if available)
			if (function_exists('gzcompress') && self::$blnCompress) {
				$strFormState = gzcompress($strFormState, 9);
			}

			if (
				defined('__REDIS_BACKED_FORM_STATE_HANDLER_ENCRYPTION_KEY__') &&
				defined('__REDIS_BACKED_FORM_STATE_HANDLER_IV_HASH_KEY__')
			) {
				try {
					$crypt = new QCryptography(__REDIS_BACKED_FORM_STATE_HANDLER_ENCRYPTION_KEY__, false, null, __REDIS_BACKED_FORM_STATE_HANDLER_IV_HASH_KEY__);
					$strFormState = $crypt->Encrypt($strFormState);
				} catch (Exception $e) {
				}
			}

			if (self::$blnBase64) {
				$encoded = base64_encode($strFormState);
				if ($strFormState && !$encoded) {
					throw new Exception ("Base64 Encoding Failed on " . $strOriginal);
				} else {
					$strFormState = $encoded;
				}
			}

			$strPageId = '';
			if (!empty($_POST['Qform__FormState']) && QApplication::$RequestMode == QRequestMode::Ajax) {
				// update the current form state if possible
				$strPageId = $_POST['Qform__FormState'];
			} else {
				// Figure Out Session Id (if applicable)
				$strSessionId = session_id();

				// Calculate a new unique Page Id
				$strPageId = md5(microtime());

				// Figure Out Page ID to be saved onto the database
				$strPageId = sprintf('%s_%s', $strSessionId, $strPageId);
			}

			if(self::$intExpireFormstatesAfterDays < 1 || self::$intExpireFormstatesAfterDays > 365) {
				self::$intExpireFormstatesAfterDays = 7;
			}

			$objClient->set('qc_formstate:' . $strPageId, $strFormState, 'ex', (self::$intExpireFormstatesAfterDays * 86400));

			// Return the Page Id
			return $strPageId;
		}

		/**
		 * Load the formstate from data store
		 *
		 * @param $strPostDataState
		 *
		 * @return null|string
		 * @throws Exception
		 */
		public static function Load($strPostDataState) {
			$objClient = self::GetClient();

			$strPageId = $strPostDataState;

			$strSerializedForm = $objClient->get('qc_formstate:' . $strPageId);

			if (!$strSerializedForm) {
				// Form does not exist or it expired
				return null;
			}

			if (self::$blnBase64) {
				$strSerializedForm = base64_decode($strSerializedForm);

				if ($strSerializedForm === false) {
					throw new Exception("Failed decoding formstate " . $strSerializedForm);
				}
			}

			if (defined('__REDIS_BACKED_FORM_STATE_HANDLER_ENCRYPTION_KEY__') && defined('__REDIS_BACKED_FORM_STATE_HANDLER_IV_HASH_KEY__')) {
				try {
					$crypt = new QCryptography(__REDIS_BACKED_FORM_STATE_HANDLER_ENCRYPTION_KEY__, false, null, __REDIS_BACKED_FORM_STATE_HANDLER_IV_HASH_KEY__);
					$strSerializedForm = $crypt->Decrypt($strSerializedForm);
				} catch (Exception $e) {
				}
			}

			if (function_exists('gzcompress') && self::$blnCompress) {
				try {
					$strSerializedForm = gzuncompress($strSerializedForm);
				} catch (Exception $e) {
					print ("Error on uncompress of page id " . $strPageId);
					throw $e;
				}
			}

			return $strSerializedForm;
		}

		/**
		 * @static
		 *
		 * If PHP SESSION is enabled, then this method will delete all formstate files specifically
		 * for this SESSION user (and no one else). This can be used in lieu of or in addition to the
		 * standard interval-based garbage collection mechanism.
		 * Also, for standard web applications with logins, it might be a good idea to call
		 * this method whenever the user logs out.
		 */
		public static function DeleteFormStateForSession() {
			$objClient = self::GetClient();
			// Figure Out Session Id (if applicable)
			$strSessionId = session_id();

			$objClient->eval("for i, name in ipairs(redis.call('KEYS', KEYS[1])) do redis.call('DEL', name); end", 1, 'qc_formstate:' . $strSessionId . '*');
		}
	}
