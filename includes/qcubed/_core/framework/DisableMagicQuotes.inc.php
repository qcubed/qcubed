<?php
/*Taken from :
jfrim at idirect dot com
27-Jan-2006 07:31
http://php.net/magic_quotes
And modified to perfection */

//Prevent Magic Quotes from affecting scripts, regardless of server settings

//Make sure when reading file data,
//PHP doesn't "magically" mangle backslashes!
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
	set_magic_quotes_runtime(FALSE);

	if (get_magic_quotes_gpc()) {
		/*
		All these global variables are slash-encoded by default,
		because    magic_quotes_gpc is set by default!
		(And magic_quotes_gpc affects more than just $_GET, $_POST, and $_COOKIE)
		*/
		global $HTTP_SERVER_VARS, $HTTP_GET_VARS,$HTTP_POST_VARS,$HTTP_COOKIE_VARS,$HTTP_POST_FILES,$HTTP_ENV_VARS;
		//don't strip slashes from the remote user var
		if(isset($_SERVER['REMOTE_USER']))
			$remote_user = $_SERVER['REMOTE_USER'];
		$_SERVER = stripslashes_array($_SERVER);
		if(isset($_SERVER['REMOTE_USER']))
			$_SERVER['REMOTE_USER'] = $remote_user;
	
		$_GET = stripslashes_array($_GET);
		$_POST = stripslashes_array($_POST);
		$_COOKIE = stripslashes_array($_COOKIE);
		//$_Files[*]['tmp_name'] is NOT escaped. Don't try to unescape it
		$tmp_names = array();
		foreach($_FILES as $key=>$file)
			$tmp_names[$key] = $file['tmp_name'];
		$_FILES = stripslashes_array($_FILES);
		foreach($tmp_names as $key=>$file)
			$_FILES[$key]['tmp_name'] = $file;
		
		$_ENV = stripslashes_array($_ENV);
		$_REQUEST = stripslashes_array($_REQUEST);
		$HTTP_SERVER_VARS = stripslashes_array($HTTP_SERVER_VARS);
		$HTTP_GET_VARS = stripslashes_array($HTTP_GET_VARS);
		$HTTP_POST_VARS = stripslashes_array($HTTP_POST_VARS);
		$HTTP_COOKIE_VARS = stripslashes_array($HTTP_COOKIE_VARS);
		$HTTP_POST_FILES = stripslashes_array($HTTP_POST_FILES);
		$HTTP_ENV_VARS = stripslashes_array($HTTP_ENV_VARS);
		if (isset($_SESSION)) {    #These are unconfirmed (?)
			$_SESSION = stripslashes_array($_SESSION, '');
			$HTTP_SESSION_VARS = stripslashes_array($HTTP_SESSION_VARS, '');
		}
		/*
		The $GLOBALS array is also slash-encoded, but when all the above are
		changed, $GLOBALS is updated to reflect those changes.  (Therefore
		$GLOBALS should never be modified directly).  $GLOBALS also contains
		infinite recursion, so it's dangerous...
		*/
	}
}

function stripslashes_array($data) {
	if (is_array($data)){
		foreach ($data as $key => $value){
			$data[$key] = stripslashes_array($value);
		}
		return $data;
	}else{
		return stripslashes($data);
	}
}
?>
