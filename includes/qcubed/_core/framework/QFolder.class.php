<?php

class QFolder {
	/**
	 * Same as mkdir but correctly implements directory recursion.
	 * At its core, it will use the php MKDIR function.
	 * 
	 * This method does no special error handling.  If you want to use special error handlers,
	 * be sure to set that up BEFORE calling MakeDirectory.
	 *
	 * @param 	string 	$strPath actual path of the directoy you want created
	 * @param 	integer	$intMode optional mode
	 * @return 	boolean	the return flag from mkdir
	 */
	public static function MakeDirectory($strPath, $intMode = null) {
		if (is_dir($strPath)) {
			// Directory Already Exists
			return true;
		}

		// Check to make sure the parent(s) exist, or create if not
		if (!QApplicationBase::MakeDirectory(dirname($strPath), $intMode)) {
			return false;
		}

		// Create the current node/directory, and return its result
		$blnReturn = mkdir($strPath);

		if ($blnReturn && !is_null($intMode)) {
			// Manually CHMOD to $intMode (if applicable)
			// mkdir doesn't do it for mac, and this will error on windows
			// Therefore, ignore any errors that creep up
			QApplication::SetErrorHandler(null);
			chmod($strPath, $intMode);
			QApplication::RestoreErrorHandler();
		}

		return $blnReturn;
	}
	
	/**
	 * Allows for deletion of non-empty directories - takes care of
	 * recursion appropriately.
	 *
	 * @param 	string 	$strPath Full path to the folder to be deleted
	 * @return 	int 	number of deleted files
	 */
	public static function DeleteFolder($strPath) {
		if (!is_dir($strPath)) {
			unlink($strPath);
			return 1;
		}

		$d = dir($strPath);
		$count = 0;
		while($entry = $d->read()) { 
			if ($entry!= "." && $entry != "..") { 
				if (is_dir($strPath)) {
					$count += QFolder::DeleteFolder($strPath . "/" . $entry);
				} 
			} 
		} 

		$d->close(); 
		rmdir($strPath);
		
		return $count;
	}

	public static function isWritable($strPath) {
		if ($strPath[strlen($strPath) - 1] != "/") {
			$strPath .= "/";
		}
		
		return QFile::isWritable($strPath);
	}
	
	/**
	 * Helper function for getRecursiveFolderContents.
	 * 
	 * Returns the list of files in the folder that match a given pattern
	 * Result is an array of strings of form "filename.extension".
	 *
	 * Not recursive!
	 *
	 * @param string $strPath full path to the folder to be processed
	 */
	private static function getFilesInFolderHelper ($strPath) {
		// Remove trailing slash if it's there
		if ($strPath[sizeof($strPath) - 1] == "/") {
				$strPath = substr($strPath, 0, -1);
		}
		$result = array();
		$dh = opendir($strPath);
		while (($file = readdir($dh)) !== false) {
				if($file != "." && $file != "..") {
						if (!is_dir($file)) {
								array_push($result, $file);
						}
				}
		}
		closedir($dh);

		return $result;
	}

	/**
	 * Traverse a particular path and get a list of files and folders
	 * underneath that path. Optionally, also provide a regular
	 * expression that specifies the pattern that the returned files must match.
	 *
	 * @param 	string 	$strPath full path to the folder to be processed
	 * @param 	boolean	$blnSkipFolders If this is set to true, only the FILES will be returned - not the folders. 
	 * @param 	string 	$strFilenamePattern: optional string; regular expression that the files must match in order to be returned. If it's not set, all files in that folder will be returned.
	 */
	public static function listFilesInFolder($strPath, $blnSkipFolders = true, $strFilenamePattern = null) {
		// strip off the trailing slash if it's there
		if ($strPath[strlen($strPath) - 1] == "/") {
			$strPath = substr($strPath, 0, -1);
		}
		
		$result = array();
		
		$originalSet = self::getFilesInFolderHelper($strPath);
		foreach ($originalSet as $item) {
			$childPath = $strPath . "/" . $item;
			if (is_dir($childPath)) {
					$childItems = self::listFilesInFolder($childPath);
					foreach ($childItems as $child) {
							$result[] = $item . "/" . $child;
					}
			}

			if (!$blnSkipFolders || !is_dir($childPath)) {
				if (!$strFilenamePattern || ($strFilenamePattern && preg_match($strFilenamePattern, $item))) {
					$result[] = $item;
				}
			}
		}
		return $result;
	} 
}

?>