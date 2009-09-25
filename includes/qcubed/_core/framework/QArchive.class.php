<?php

class QArchive {

	/**
	 * Extract a ZIP compressed file to a given path
	 *
	 * @param       string  $archive        Path to ZIP archive to extract
	 * @param       string  $destination    Path to extract archive into
	 * @return      boolean True if successful
	 */
	public static function extractZip($archive, $destination) {
		if (!function_exists('zip_open')) {
			throw new Exception("ZIP extension is not enabled on this installation of PHP. Recompile your installation of PHP with --enable-zip parameter.");
		}
		
//		echo "Extracting archive " . $archive . " to " . $destination. "<br>";
		
		if ($zip = zip_open($archive)) {
			if ($zip) {
				// Create the destination folder
				if (!mkdir($destination)) {
					self::$strLastError = "Unable to create extraction destination folder " . $destination;
					return false;
				}

				// Read files in the archive
				$createdFolders = array();
				while ($file = zip_read($zip)) {
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
				}
				zip_close($zip);
			}
		} else {
			self::$strLastError = "Unable to open uploaded archive";
			return false;
		}
		return true;
	}	
}

?>