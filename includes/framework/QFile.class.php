<?php

	/**
	 * Class QFile: Handles reading and writing of files on the file system
	 */
	class QFile {
		/**
		 * Read the file from disk
		 *
		 * @param string $strFilePath Path of the file to be read
		 *
		 * @return string|mixed read data. Can return binary data
		 */
		public static function readFile($strFilePath) {
			$result = "";
			$handle = fopen($strFilePath, "r");
			while (!feof($handle)) {
				$result .= fread($handle, 8000);
			}
			fclose($handle);

			return $result;
		}

		/**
		 * Write data into file
		 *
		 * @param string $strFilePath Path of the file into which to write
		 * @param string $strContents The contents that should be written into the file
		 *
		 * @throws Exception
		 */
		public static function writeFile($strFilePath, $strContents) {
			$fileHandle = fopen($strFilePath, "w");
			if (!$fileHandle) {
				throw new Exception("Cannot open file for writing: " . $strFilePath);
			}

			if (fwrite($fileHandle, $strContents, strlen($strContents)) === false) {
				throw new Exception("Unable to write file: " . $strFilePath);
			}
			fclose($fileHandle);
		}

		/**
		 * Will work despite of Windows ACLs bug
		 * NOTE: use a trailing slash for folders!!!
		 * See http://bugs.php.net/bug.php?id=27609 AND http://bugs.php.net/bug.php?id=30931
		 * Source: <http://www.php.net/is_writable#73596>

		 */
		public static function isWritable($path) {
			// recursively return a temporary file path
			if ($path{strlen($path) - 1} == '/') {
				return self::isWritable($path . uniqid(mt_rand()) . '.tmp');
			} elseif (is_dir($path)) {
				return self::isWritable($path . '/' . uniqid(mt_rand()) . '.tmp');
			}

			// check file for read/write capabilities
			$rm = file_exists($path);
			$handle = @fopen($path, 'a');

			if ($handle === false) {
				return false;
			}

			fclose($handle);

			if (!$rm) {
				unlink($path);
			}

			return true;
		}
	}

?>