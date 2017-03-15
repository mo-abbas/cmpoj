<?php 
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");
require_once("../includes/validation_functions.php");

if(!isset($_POST["submit"])||!logged_in())
	redirect_to("../index.php");

if (!isset($_GET["contest"]))
	redirect_to("../index.php");

$contest=find_contest_by_id($_GET["contest"]);

$nName=$_POST["contest_name"];
$nStartD=$_POST["contest_starts"];
$nEndD=$_POST["contest_ends"];

$id = mysql_prep($_GET["contest"]);

$query = " UPDATE contest";
$query.= " SET name=\"{$nName}\",";
$query.= " start_time=\"{$nStartD}\",";
$query.= " end_time=\"{$nEndD}\"";
$query.= " WHERE id=\"{$id}\";" ;


$result=mysqli_query($connection, $query);
confirm_query($result);




if(empty($errors))
	$_SESSION["message"] = "Contest edited successfully.";
else
	$_SESSION["errors"] = $errors;	

$id=$_GET["contest"];
redirect_to( "../edit_contest.php?contest={$id}");

?>