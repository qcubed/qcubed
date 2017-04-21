<?php

	/**
	 * Created by vaibhav on 1/28/12 (3:34 AM).
	 *
	 * This file contains the QDbBackedSessionHandler class.
	 *
	 * Relies on a SQL database table with the following columns:
	 * 	id - STRING primary key
	 *  last_access_time - INT
	 *  data - can be a BLOB or BINARY or VARBINARY or a TEXT. If TEXT, be sure to leave $blnBase64 on. If you are using
	 * 		a binary field, you can turn that off to save space. Make sure your column is capable of holding the maximum size of
	 * 		session data for your app, which depends on what you are putting in the $_SESSION variable.
	 * 
	 * Below is an example SQL you can use. Make sure you add a PRIMARY KEY on ID and an INDEX on last_access_time
	 *
	 *	CREATE TABLE `qc_session` (
	 *		`id` CHAR(42) NOT NULL DEFAULT '',
	 *		`last_access_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
	 *		`data` BLOB NOT NULL DEFAULT ''
	 *	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 
	 *  ALTER TABLE `qc_session` ADD PRIMARY KEY(`id`);
	 *  ALTER TABLE `qc_session` ADD INDEX `in_qcsession_last_access_time` (`last_access_time`);
	 *
	 *  A little more information. on the "id" column. 
	 *  Column id stores the session id which can be of a different length in your specific installation. It is formed by concatenating the session_name(), a dot(.) and the session_id().
	 *  session_name is by default PHPSESSID=> length 9CHARS
	 *  a dot . => 1 CHARS
	 *	session_id => 32 CHARS (by default)
	 *	BUT:
	 *   In PHP < 7:
	 *		By default, PHP session_id's are MD5 strings => 32CHARS.
	 *		However if you set session.hash_function to 1, this session_id will be SHA1 => 40CHARS
	 *	 In PHP7:
	 *		The length of the session is determined by session.sid_length. Default 32 => 32CHARS.
         *
	 *	  Also check http://php.net/manual/en/function.session-id.php
	 *
	 *	  So as an example: PHPSESSID.6e80a2fb32a996539874f96b6ba460f7 will be 9 + 1 + 32 = 42CHARS -> Column has to be CHAR(42).
	 * 
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
		protected static $strSessionName	= ''; //PHPSESSID on a default PHP installation
		
		/** @var bool Whether to base64 the session data. Required when storing data in a TEXT field. */
		public static $blnBase64			= true;

		/** @var bool Whether to compress the session data. */
		public static $blnCompress			= true;


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
			self::$intDbIndex	= QType::Cast($intDbIndex, QType::Integer);
			self::$strTableName = QType::Cast($strTableName, QType::String);
			// If the database index exists
			if (!isset(QApplication::$Database[self::$intDbIndex]) ) {
				throw new QCallerException('No database defined at DB_CONNECTION index ' . self::$intDbIndex . '. Correct your settings in configuration.inc.php.');
			}
// We don't want a GetTables everytime I do a request. This will slow the overal system down. Yes, it will crash in a slighty less clear way, does this matter?
//			$objDatabase = QApplication::$Database[self::$intDbIndex];
//			// see if the database contains a table with desired name
//			if (!in_array(self::$strTableName, $objDatabase->GetTables())) {
//				throw new QCallerException('Table ' . self::$strTableName . ' not found in database at DB_CONNECTION index ' . self::$intDbIndex . '. Correct your settings in configuration.inc.php.');
//			}
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
		 * @param string $strSessionId
		 *
		 * @return string the session data, base64 decoded
		 * @throws QCallerException
		 */
		public static function SessionRead($strSessionId) {
			$objDatabase	= QApplication::$Database[self::$intDbIndex];
			$query			= '
				SELECT
					' . $objDatabase->EscapeIdentifier('data') . '
				FROM
					' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
				WHERE
					' . $objDatabase->EscapeIdentifier('id') . ' = ' . $objDatabase->SqlVariable(self::GetFullSessionId($strSessionId));

			$result = $objDatabase->Query($query);

			$result_row = $result->FetchRow();


			if (!$result_row) { // either the data was empty or the row was not found
				return '';
			}
			$strData = $result_row[0];

			/** A kludge to fix a particular problem. Would require a complete rewrite of our database adapters to do this right. */
			if(!static::$blnBase64 && strstr($objDatabase->Adapter, 'PostgreSql')) {
				if(function_exists('pg_unescape_bytea')) {
					$strData = pg_unescape_bytea($strData);
				} else {
					throw new QCallerException('pg_unescape_bytea method needed for DbBackedSessionHandler to operate on a PostgreSQL database. Please install the "pgsql" PHP extension.');
				}
			}

			if (!$strData) {
				return '';
			}

			if (self::$blnBase64) {
				$strData = base64_decode($strData);

				if ($strData === false) {
					throw new Exception("Failed decoding formstate " . $strData);
				}
			}

			// The session exists and was accessed. Return the data.
			if (defined('DB_BACKED_SESSION_HANDLER_ENCRYPTION_KEY')) {
				try {
					$crypt = new QCryptography(DB_BACKED_SESSION_HANDLER_ENCRYPTION_KEY, false, null, DB_BACKED_SESSION_HANDLER_HASH_KEY);
					$strData = $crypt->Decrypt($strData);
				}
				catch(Exception $e) {
				}
			}

			if (self::$blnCompress) {
				$strData = gzuncompress($strData);
			}

			
			return $strData;
		}

		/**
		 * Tells whether a session by given name exists or not (used by PHP when the session handler is active)
		 * @param string $strSessionId Session ID
		 *
		 * @return bool does the session exist or not
		 */
		public static function SessionExists($strSessionId) {
			$objDatabase	= QApplication::$Database[self::$intDbIndex];
			$query			= '
				SELECT 1
				FROM
					' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
				WHERE
					' . $objDatabase->EscapeIdentifier('id') . ' = ' . $objDatabase->SqlVariable(self::GetFullSessionId($strSessionId));

			$result			= $objDatabase->Query($query);

			$result_row		= $result->FetchArray();

			// either the data was empty or the row was not found
			return !empty($result_row);
		}

		/**
		 * Write data to the session
		 *
		 * @param string $strSessionId The session ID
		 * @param string $strSessionData Data to be written to the Session whose ID was supplied
		 *
		 * @return bool
		 */
		public static function SessionWrite($strSessionId, $strSessionData) {
			if (empty($strSessionData)) {
				static::SessionDestroy($strSessionId);
				return true;
			}

			$strEncoded = $strSessionData;

			if (self::$blnCompress) {
				$strEncoded = gzcompress($strSessionData);
			}

			if (defined('DB_BACKED_SESSION_HANDLER_ENCRYPTION_KEY')) {
				try {
					$crypt		= new QCryptography(DB_BACKED_SESSION_HANDLER_ENCRYPTION_KEY, false, null, DB_BACKED_SESSION_HANDLER_HASH_KEY);
					$strEncoded = $crypt->Encrypt($strEncoded);
				}
				catch(Exception $e) {
				}
			}

			if (self::$blnBase64) {
				$encoded = base64_encode($strEncoded);
				if ($strEncoded && !$encoded) {
					throw new Exception ("Base64 Encoding Failed on " . $strSessionData);
				}
				else {
					$strEncoded = $encoded;
				}
			}

			assert (!empty($strEncoded));

			$objDatabase	= QApplication::$Database[self::$intDbIndex];
			$objDatabase->InsertOrUpdate(
				self::$strTableName,
				array(
					'data'				=> $strEncoded,
					'last_access_time'	=> time(),
					'id'				=> self::GetFullSessionId($strSessionId)
				),
				'id');
			return true;
		}

		/**
		 * Destroy the session for a given session ID
		 *
		 * @param string $strSessionId The session ID
		 *
		 * @return bool
		 */
		public static function SessionDestroy($strSessionId) {
			$objDatabase	= QApplication::$Database[self::$intDbIndex];
			$query			= '
				DELETE FROM
					' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
				WHERE
					' . $objDatabase->EscapeIdentifier('id') . ' = ' . $objDatabase->SqlVariable(self::GetFullSessionId($strSessionId));

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
			$objDatabase	= QApplication::$Database[self::$intDbIndex];
			$old			= time() - $intMaxSessionLifetime;

			$query			= '
				DELETE FROM
					' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
				WHERE
					' . $objDatabase->EscapeIdentifier('last_access_time') . ' < ' . $objDatabase->SqlVariable($old);

			$objDatabase->NonQuery($query);
			return true;
		}
		
		/**
		 * Concatenates the session_name with the session_id
		 * 
		 * @param string $strSessionId
		 * @return string
		 */
		private static function GetFullSessionId($strSessionId) {
			return self::$strSessionName.'.'.$strSessionId;
		}
	}
