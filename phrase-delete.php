<?php
include_once ('config/functions.php');

$id = isset($_GET['id']) ? $_GET['id'] : '';
$s = isset($_GET['s']) ? $_GET['s'] : '';

$GLOBALS = array();

if(!empty($id))
{
	session_start();

	if(!empty($s) && $s == 1)
	{// delete phrase selected for today
		deletePhraseSelected($id, $GLOBALS, $link);

		if(!isset($GLOBALS['post_error']))
			$_SESSION['post_error'] = true;

		$_SESSION['message'] = $GLOBALS['post_message'];

		header('Location:'.SITE_URL.'phrase-selected.php');
	}
	else
	{// delete phrase 
		deletePhrase($id, $GLOBALS, $link);
		if(!isset($GLOBALS['post_error']))
			$_SESSION['post_error'] = true;

		$_SESSION['message'] = $GLOBALS['post_message'];

		header('Location:'.SITE_URL.'phrase.php');
	}
}
else
{
	header('Location:'.SITE_URL.'phrase.php');
}
exit;?>