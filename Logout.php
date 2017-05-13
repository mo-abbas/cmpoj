<?php require_once("includes/session.php");?>
<?php require_once("includes/db_connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php

	$_SESSION = array();
	session_destroy();

	redirect_to("index.php");
?>
