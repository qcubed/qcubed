<?php
/**
* QArchive class helps extract archives (ex. zip)
*/
class QArchive {
	private static $strLastError;
	
	/**
	* Static Method to return any errors that occured
	* @return $strLastError
	*/
	public static function getLastError() {
		return self::$strLastError;
	}

	/**
	 * Extract a ZIP compressed file to a given path
	 * @param       string  $archive        Path to ZIP archive to extract
	 * @param       string  $destination    Path to extract archive into
	 * @return      boolean True if successful
	 */
	public static function extractZip($archive, $destination) {
		if (!function_exists('zip_open')) {
			throw new Exception("ZIP extension is not enabled on this installation of PHP. Recompile your installation of PHP with --enable-zip parameter.");
		}
		
//		echo "Extracting archive " . $archive . " to " . $destination. "<br>";

		$zip = zip_open($archive);
		if (is_resource($zip)) {
			// Create the destination folder
			if (!mkdir($destination)) {
				self::$strLastError = "Unable to create extraction destination folder " . $destination;
				return false;
			}
	
			// Read files in the archive
			$createdFolders = array();
			while ($file = zip_read($zip)) {
				if(is_resource($file)) {
					if (zip_entry_open($zip, $file, "r")) {							
						if (substr(zip_entry_name($file), strlen(zip_entry_name($file)) - 1) != "/") {
//							echo zip_entry_name($file) . "<br>";
							
							$folderStack = explode("/", zip_entry_name($file));
							if (sizeof($folderStack) > 1) {
								for ($i = 0; $i < sizeof($folderStack) - 1; $i++) {
									$arraySubsection = array_slice($folderStack, 0, $i + 1);
									$item = implode("/", $arraySubsection);
									
									if (!in_array($item, $createdFolders)) {
//										echo "- Creating folder: " . $destination . $item . "<br>";
										$createdFolders[] = $item;
										mkdir($destination . $item);
									}
								}
							}
							
							$strSectionToAppend = zip_entry_read($file, zip_entry_filesize($file));
							$strSavePath = $destination . zip_entry_name($file);
							
							QFile::writeFile($strSavePath, $strSectionToAppend);
							
							zip_entry_close($file);
						}
					} else {
						self::$strLastError = "Unable to read zip entry";
						return false;
					}
				} else {
					self::$strLastError = self::zipFileErrMsg($file);
					return false;
				}
			}
			zip_close($zip);
		} else {
			self::$strLastError = self::zipFileErrMsg($zip);
			return false;
		}
		return true;
	}

        /**
        * Private method that gives a more in-depth error code based on the 
        * value returned by zip_open and zip_read on failure.
        *
        * @param	int  $errNo Error code returned by the zip library
        * @return	string	Error explanation string
        */
        private static function zipFileErrMsg($errno) {
			// using constant name as a string to make this function PHP4 compatible
			$zipFileFunctionsErrors = array(
				'ZIPARCHIVE::ER_MULTIDISK' => 'Multi-disk zip archives not supported.',
				'ZIPARCHIVE::ER_RENAME' => 'Renaming temporary file failed.',
				'ZIPARCHIVE::ER_CLOSE' => 'Closing zip archive failed',
				'ZIPARCHIVE::ER_SEEK' => 'Seek error',
				'ZIPARCHIVE::ER_READ' => 'Read error',
				'ZIPARCHIVE::ER_WRITE' => 'Write error',
				'ZIPARCHIVE::ER_CRC' => 'CRC error',
				'ZIPARCHIVE::ER_ZIPCLOSED' => 'Containing zip archive was closed',
				'ZIPARCHIVE::ER_NOENT' => 'No such file.',
				'ZIPARCHIVE::ER_EXISTS' => 'File already exists',
				'ZIPARCHIVE::ER_OPEN' => 'Can\'t open file',
				'ZIPARCHIVE::ER_TMPOPEN' => 'Failure to create temporary file.',
				'ZIPARCHIVE::ER_ZLIB' => 'Zlib error',
				'ZIPARCHIVE::ER_MEMORY' => 'Memory allocation failure',
				'ZIPARCHIVE::ER_CHANGED' => 'Entry has been changed',
				'ZIPARCHIVE::ER_COMPNOTSUPP' => 'Compression method not supported.',
				'ZIPARCHIVE::ER_EOF' => 'Premature EOF',
				'ZIPARCHIVE::ER_INVAL' => 'Invalid argument',
				'ZIPARCHIVE::ER_NOZIP' => 'Not a zip archive',
				'ZIPARCHIVE::ER_INTERNAL' => 'Internal error',
				'ZIPARCHIVE::ER_INCONS' => 'Zip archive inconsistent',
				'ZIPARCHIVE::ER_REMOVE' => 'Can\'t remove file',
				'ZIPARCHIVE::ER_DELETED' => 'Entry has been deleted');

			foreach ($zipFileFunctionsErrors as $constName => $errorMessage) {
				if (defined($constName) and constant($constName) === $errno) {
					return 'Zip File error: ' . $errorMessage;
				}
			}
			return 'Zip File Function error: unknown';
        }
}