<?php
include("Base.php");
require_once("includes/db_connection.php");
require_once("includes/validation_functions.php");
$adid = in_array("adid", $_POST) ? $_POST["adid"] : false;
$adpass = in_array("adpass", $_POST) ? $_POST["adpass"] : false;
if (!isset($_POST["adid"]) || !isset( $_POST["adpass"])) redirect_to("index.php");

$admin = admin_login($_POST["adid"], $_POST["adpass"]);

if ($admin)
{
	$_SESSION["adid"] = $admin["id"];
	if(isset($_SESSION["id"]))
	{
		unset($_SESSION["id"]);
		unset($_SESSION["handle"]);
	}
	//$_SESSION["id"] = 0;
}
redirect_to("index.php");
