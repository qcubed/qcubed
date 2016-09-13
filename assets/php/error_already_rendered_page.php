<?php

//session_start();

if (isset($_SESSION['RenderedPageForError'])) {
	echo $_SESSION['RenderedPageForError'];
	unset($_SESSION['RenderedPageForError']);
} else {
	echo "The rendered page could not be displayed";
}