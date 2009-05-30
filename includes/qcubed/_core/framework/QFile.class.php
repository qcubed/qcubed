<?php

class QFile {
	public static function readFile($strFilePath) {
		$result = "";
		$handle = fopen($strFilePath, "r");
		while(!feof($handle)) {
			$result .= fread($handle, 8000);
		}		
		fclose($handle);
		
		return $result;
	}
	
	public static function writeFile($strFilePath, $strContents) {
		$fileHandle = fopen($strFilePath, "w");
		if (fwrite($fileHandle, $strContents, strlen($strContents)) == false) {
			throw new Exception("Unable to write file: ");
		}
		fclose($fileHandle);
	}	
}

?>