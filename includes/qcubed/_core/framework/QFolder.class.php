<?php

class QFolder {
	/**
	 * Same as mkdir but correctly implements directory recursion.
	 * At its core, it will use the php MKDIR function.
	 * 
	 * This method does no special error handling.  If you want to use special error handlers,
	 * be sure to set that up BEFORE calling MakeDirectory.
	 *
	 * @param string $strPath actual path of the directoy you want created
	 * @param integer $intMode optional mode
	 * @return boolean the return flag from mkdir
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
	 * @return int number of deleted files
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
}

?>