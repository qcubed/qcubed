<?php
	/**
	 * This will store the formstate in a pre-specified table in the DB.
	 * This offers significant speed advantage over PHP SESSION because EACH form state
	 * is saved in its own row in the DB, and only the form state that is needed for loading will
	 * be accessed (as opposed to with session, ALL the form states are loaded into memory
	 * every time).
	 * 
	 * The downside is that because it doesn't utilize PHP's session management subsystem,
	 * this class must take care of its own garbage collection/deleting of old/outdated
	 * formstate files.
	 * 
	 * Because the index is randomly generated and MD5-hashed, there is no benefit from
	 * encrypting it -- therefore, the QForm encryption preferences are ignored when using
	 * QFileFormStateHandler.
	 * 
	 * This handler can handle asynchronous calls.
	 */
	class QDbBackedFormStateHandler extends QBaseClass {

		/**
		 * The database index in configuration.inc.php where the formstates have to be managed
		 */
		public static $intDbIndex = __DB_BACKED_FORM_STATE_HANDLER_DB_INDEX__;

		/**
		 * The table name which will handle the formstates. It must have the following columns:
		 * 1. page_id: varchar(80)
		 * 2. save_time: integer
		 * 3. state_data: text
		 * 4. session_id: varchar(32)
		 */
		public static $strTableName = __DB_BACKED_FORM_STATE_HANDLER_TABLE_NAME__;
		/**
		 * The interval of hits before the garbage collection should kick in to delete
		 * old FormState files, or 0 if it should never be run.  The higher the number,
		 * the less often it runs (better aggregated-average performance, but requires more
		 * hard drive space).  The lower the number, the more often it runs (slower aggregated-average
		 * performance, but requires less hard drive space).
		 * @var integer GarbageCollectInterval
		 */
		public static $intGarbageCollectOnHitCount = 20000;

		/**
		 * The minimum age (in days) a formstate file has to be in order to be considered old enough
		 * to be garbage collected.  So if set to "1.5", then all formstate files older than 1.5 days
		 * will be deleted when the GC interval is kicked off.
		 * Obviously, if the GC Interval is set to 0, then this GC Days Old value will be never used.
		 * @var integer GarbageCollectDaysOld
		 */
		public static $intGarbageCollectDaysOld = 2;

		/** @var bool Whether to compress the formstate data. */
		public static $blnCompress = true;

		/** @var bool Whether to base64 encode the formstate data. Encoding is required if storing in a TEXT field. */
		public static $blnBase64 = false;


		private static function Initialize() {
			self::$intDbIndex = QType::Cast(self::$intDbIndex, QType::Integer);
			self::$strTableName = QType::Cast(self::$strTableName, QType::String);

			// If the database index exists
			if (!array_key_exists(self::$intDbIndex, QApplication::$Database)) {
				throw new QCallerException('No database defined at DB_CONNECTION index ' . self::$intDbIndex . '. Correct your settings in configuration.inc.php.');
			}
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			// see if the database contains a table with desired name
			if (!in_array(self::$strTableName, $objDatabase->GetTables())) {
				throw new QCallerException('Table ' . self::$strTableName . ' not found in database at DB_CONNECTION index ' . self::$intDbIndex . '. Correct your settings in configuration.inc.php.');
			}
		}

		/**
		 * @static
		 * This function is responsible for removing the old values from
		 */
		public static function GarbageCollect() {
			// Its not perfect and not sure but should be executed on expected intervals
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			$query = '
	                                DELETE FROM
	                                        ' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
	                                WHERE
                                                ' . $objDatabase->EscapeIdentifier('save_time') . ' < ' . $objDatabase->SqlVariable(time() - 60 * 60 * 24 * self::$intGarbageCollectDaysOld);

			$objDatabase->NonQuery($query);
		}

		/**
		 * @static
		 * If PHP SESSION is enabled, then this method will delete all formstate files specifically
		 * for this SESSION user (and no one else).  This can be used in lieu of or in addition to the
		 * standard interval-based garbage collection mechanism.
		 * Also, for standard web applications with logins, it might be a good idea to call
		 * this method whenever the user logs out.
		 */

		public static function DeleteFormStateForSession() {
			// Figure Out Session Id (if applicable)
			$strSessionId = session_id();

			//Get database
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			// Create the query
			$query = '
                                DELETE FROM
                                        ' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
                                WHERE
                                        ' . $objDatabase->EscapeIdentifier('session_id') . ' = ' . $objDatabase->SqlVariable($strSessionId);

			$result = $objDatabase->NonQuery($query);
		}

		/**
		 * @static
		 *
		 * @param $strFormState
		 * @param $blnBackButtonFlag
		 *
		 * @return string
		 */
		public static function Save($strFormState, $blnBackButtonFlag) {
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			$strOriginal = $strFormState;

			// compress (if available)
			if (function_exists('gzcompress') && self::$blnCompress) {
				$strFormState = gzcompress($strFormState, 9);
			}

			if (defined('__DB_BACKED_FORM_STATE_HANDLER_ENCRYPTION_KEY__')) {
				try {
					$crypt = new QCryptography(__DB_BACKED_FORM_STATE_HANDLER_ENCRYPTION_KEY__, false, null, __DB_BACKED_FORM_STATE_HANDLER_HASH_KEY__);
					$strFormState = $crypt->Encrypt($strFormState);
				}
				catch(Exception $e) {
				}
			}

			if (self::$blnBase64) {
				$encoded = base64_encode($strFormState);
				if ($strFormState && !$encoded) {
					throw new Exception ("Base64 Encoding Failed on " . $strOriginal);
				}
				else {
					$strFormState = $encoded;
				}
			}

			if (!empty($_POST['Qform__FormState']) && QApplication::$RequestMode == QRequestMode::Ajax) {
				// update the current form state if possible
				$strPageId = $_POST['Qform__FormState'];

				$strQuery = '
                                UPDATE
                                        ' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
                                SET
                                        ' . $objDatabase->EscapeIdentifier('save_time') . ' = ' . $objDatabase->SqlVariable(time()) . ',
                                        ' . $objDatabase->EscapeIdentifier('state_data') . ' = ' . $objDatabase->SqlVariable($strFormState) . '
                                WHERE
                                        ' . $objDatabase->EscapeIdentifier('page_id') . ' = ' . $objDatabase->SqlVariable($strPageId);

				$objDatabase->NonQuery($strQuery);
				if ($objDatabase->AffectedRows > 0) {
					return $strPageId;	// successfully updated the current record. No need to create a new one.
				}
			}
			// First see if we need to perform garbage collection
			// Decide for garbage collection
			if ((self::$intGarbageCollectOnHitCount > 0) && (rand(1, self::$intGarbageCollectOnHitCount) == 1)) {
				self::GarbageCollect();
			}

			//*/

			// Figure Out Session Id (if applicable)
			$strSessionId = session_id();

			// Calculate a new unique Page Id
			$strPageId = md5(microtime());

			// Figure Out Page ID to be saved onto the database
			$strPageId = sprintf('%s_%s',
				$strSessionId,
				$strPageId);

			// Save THIS formstate to the database
			//Get database
			// Create the query
			$strQuery = '
                                INSERT INTO
                                        ' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
                                (
                                        ' . $objDatabase->EscapeIdentifier('page_id') . ',
                                        ' . $objDatabase->EscapeIdentifier('session_id') . ',
                                        ' . $objDatabase->EscapeIdentifier('save_time') . ',
                                        ' . $objDatabase->EscapeIdentifier('state_data') . '
                                )
                                VALUES
                                (
                                        ' . $objDatabase->SqlVariable($strPageId) . ',
                                        ' . $objDatabase->SqlVariable($strSessionId) . ',
                                        ' . $objDatabase->SqlVariable(time()) . ',
                                        ' . $objDatabase->SqlVariable($strFormState) . '
                                )';

			$result = $objDatabase->NonQuery($strQuery);

			// Return the Page Id
			// Because of the MD5-random nature of the Page ID, there is no need/reason to encrypt it
			return $strPageId;
		}

		public static function Load($strPostDataState) {
			// Pull Out strPageId
			$strPageId = $strPostDataState;

			//Get database
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			// The query to run
			$strQuery = '
                                SELECT
                                        ' . $objDatabase->EscapeIdentifier('state_data') . '
				FROM
                                        ' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
                                WHERE
                                        ' . $objDatabase->EscapeIdentifier('page_id') . ' = ' . $objDatabase->SqlVariable($strPageId);

			if ($strSessionId = session_id()) {
				$strQuery .= ' AND ' . $objDatabase->EscapeIdentifier('session_id') . ' = ' . $objDatabase->SqlVariable($strSessionId);
			}


			// Perform the Query
			$objDbResult = $objDatabase->Query($strQuery);

			$strFormStateRow = $objDbResult->FetchRow()[0];

			if (empty($strFormStateRow)) {
				// The formstate with that page ID was not found, or session expired.
				return null;
			}
			$strSerializedForm = $strFormStateRow;


			if (self::$blnBase64) {
				$strSerializedForm = base64_decode($strSerializedForm);

				if ($strSerializedForm === false) {
					throw new Exception("Failed decoding formstate " . $strSerializedForm);
				}
			}

			if (defined('__DB_BACKED_FORM_STATE_HANDLER_ENCRYPTION_KEY__')) {
				try {
					$crypt = new QCryptography(__DB_BACKED_FORM_STATE_HANDLER_ENCRYPTION_KEY__, false, null, __DB_BACKED_FORM_STATE_HANDLER_HASH_KEY__);
					$strSerializedForm = $crypt->Decrypt($strSerializedForm);
				}
				catch(Exception $e) {
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
	}
