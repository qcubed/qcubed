<?php

	/**
	 * Created by vaibhav on 1/28/12 (3:34 AM).
	 *
	 * This file contains the QDbBackedSessionHandler class.
	 *
	 * Relies on a SQL database table with the following columns:
	 * 	id - STRING primary key
	 *  last_access_time - INT
	 *  data - BLOB or BINARY or VARBINARY. Must be a binary safe column, and capable of holding the maximum size of
	 * 		session data for your app, which depends on what you are putting in the $_SESSION variable.
	 *
	 * @package Sessions
	 */
	class QDbBackedSessionHandler extends QBaseClass {

		/**
		 * @var int The index in the database array
		 */
		protected static $intDbIndex;

		/**
		 * @var string The table name to be used for saving sessions.
		 */
		protected static $strTableName;

		/**
		 * @var string The session name to be used for saving sessions.
		 */
		protected static $strSessionName = '';

		/** @var bool Whether to encrypt the session data. Highly recommended whenever sessions can authenticate a user. */
		public static $blnEncrypt = true;

		/** @var bool Whether to compress the session data. */
		public static $blnCompress = true;


		/**
		 * @static
		 *
		 * @param int    $intDbIndex   The index in the database array
		 * @param string $strTableName The table name to be used for saving sessions.
		 *
		 * @return bool
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
		public static function Initialize($intDbIndex = 1, $strTableName = "qc_session") {
			self::$intDbIndex = QType::Cast($intDbIndex, QType::Integer);
			self::$strTableName = QType::Cast($strTableName, QType::String);
			// If the database index exists
			if (!array_key_exists(self::$intDbIndex, QApplication::$Database)) {
				throw new QCallerException('No database defined at DB_CONNECTION index ' . self::$intDbIndex . '. Correct your settings in configuration.inc.php.');
			}
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			// see if the database contains a table with desired name
			if (!in_array(self::$strTableName, $objDatabase->GetTables())) {
				throw new QCallerException('Table ' . self::$strTableName . ' not found in database at DB_CONNECTION index ' . self::$intDbIndex . '. Correct your settings in configuration.inc.php.');
			}
			// Set session handler functions
			$session_ok = session_set_save_handler(
				'QDbBackedSessionHandler::SessionOpen',
				'QDbBackedSessionHandler::SessionClose',
				'QDbBackedSessionHandler::SessionRead',
				'QDbBackedSessionHandler::SessionWrite',
				'QDbBackedSessionHandler::SessionDestroy',
				'QDbBackedSessionHandler::SessionGarbageCollect'
			);
			// could not register the session handler functions
			if (!$session_ok) {
				throw new QCallerException("session_set_save_handler function failed");
			}
			// Will be called before session ends.
			register_shutdown_function('session_write_close');
			return $session_ok;
		}

		/**
		 * Open the session (used by PHP when the session handler is active)
		 * @param string $save_path
		 * @param string $session_name
		 *
		 * @return bool
		 */
		public static function SessionOpen($save_path, $session_name) {
			self::$strSessionName = $session_name;
			// Nothing to do
			return true;
		}

		/**
		 * Close the session (used by PHP when the session handler is active)
		 * @return bool
		 */
		public static function SessionClose() {
			// Nothing to do.
			return true;
		}

		/**
		 * Read the session data (used by PHP when the session handler is active)
		 * @param string $id
		 *
		 * @return string the session data, base64 decoded
		 */
		public static function SessionRead($id) {
			$id = self::$strSessionName . '.' . $id;
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			$query = '
				SELECT
					' . $objDatabase->EscapeIdentifier('data') . '
				FROM
					' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
				WHERE
					' . $objDatabase->EscapeIdentifier('id') . ' = ' . $objDatabase->SqlVariable($id);

			$result = $objDatabase->Query($query);

			$result_row = $result->FetchArray();


			if (!$result_row) // either the data was empty or the row was not found
				return '';
			$strData = $result_row['data'];
			if (!$strData)
				return '';

			// The session exists and was accessed. Return the data.
			if (self::$blnEncrypt) {
				try {
					$crypt = new QCryptography();
					$strData = $crypt->Decrypt($strData);
				}
				catch(Exception $e) {
					// if mcyrpt not installed, skip this step
				}
			}

			if (self::$blnCompress) {
				$strData = gzuncompress($strData);
			}

			return $strData;
		}

		/**
		 * Tells whether a session by given name exists or not (used by PHP when the session handler is active)
		 * @param string $id Session ID
		 *
		 * @return bool does the session exist or not
		 */
		public static function SessionExists($id) {
			$id = self::$strSessionName . '.' . $id;
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			$query = '
				SELECT 1
				FROM
					' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
				WHERE
					' . $objDatabase->EscapeIdentifier('id') . ' = ' . $objDatabase->SqlVariable($id);

			$result = $objDatabase->Query($query);

			$result_row = $result->FetchArray();

			// either the data was empty or the row was not found
			return !empty($result_row);
		}

		/**
		 * Write data to the session
		 *
		 * @param string $id The session ID
		 * @param string $strSessionData Data to be written to the Session whose ID was supplied
		 *
		 * @return bool
		 */
		public static function SessionWrite($id, $strSessionData) {
			if (self::$blnCompress) {
				$strEncoded = gzcompress($strSessionData);
			}

			if (self::$blnEncrypt) {
				try {
					$crypt = new QCryptography();
					$strEncoded = $crypt->Encrypt($strEncoded);
				}
				catch(Exception $e) {
					// if mcyrpt not installed, skip this step
				}
			}

			assert (!empty($strEncoded));

			$id = self::$strSessionName . '.' . $id;
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			$objDatabase->InsertOrUpdate(
				self::$strTableName,
				array(
					'data' => $strEncoded,
					'last_access_time' => time(),
					'id' => $id
				),
				'id');
			return true;
		}

		/**
		 * Destroy the session for a given session ID
		 *
		 * @param string $id The session ID
		 *
		 * @return bool
		 */
		public static function SessionDestroy($id) {
			$id = self::$strSessionName . '.' . $id;
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			$query = '
				DELETE FROM
					' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
				WHERE
					' . $objDatabase->EscapeIdentifier('id') . ' = ' . $objDatabase->SqlVariable($id);

			$objDatabase->NonQuery($query);
			return true;
		}

		/**
		 * Garbage collect session data (delete/destroy sessions which are older than the max allowed lifetime)
		 *
		 * @param int $intMaxSessionLifetime The max session lifetime (in seconds)
		 *
		 * @return bool
		 */
		public static function SessionGarbageCollect($intMaxSessionLifetime) {
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			$old = time() - $intMaxSessionLifetime;

			$query = '
				DELETE FROM
					' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
				WHERE
					' . $objDatabase->EscapeIdentifier('last_access_time') . ' < ' . $objDatabase->SqlVariable($old);

			$objDatabase->NonQuery($query);
			return true;
		}
	}

?>
