<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");
require_once("../includes/validation_functions.php");

// no check on the judge or if the contest exists

function output()
{
	global $connection;

	$output = [];
	if(!isset($_POST["submit"]))
	{
		$output["redirect"] = "../index.php";
		return $output;
	}

	if(!logged_in())
	{
		$output["redirect"] = "../index.php";
		return $output;
	}

	$contest=find_contest_by_id($_GET["contest"]);
	if(!$contest)
	{
		$output["redirect"] = "../index.php";
		return $output;
	}

	if($contest["judge_id"] != $_SESSION["id"])
	{
		$output["redirect"] = "../index.php";
		return $output;
	}

	$id = mysql_prep($_GET["contest"]);
	$nName = mysql_prep($_POST["contest_name"]);
	$nStartD = mysql_prep($_POST["contest_starts"]);
	$nEndD = mysql_prep($_POST["contest_ends"]);

	$query = " UPDATE contest";
	$query.= " SET name=\"{$nName}\",";
	$query.= " start_time=\"{$nStartD}\",";
	$query.= " end_time=\"{$nEndD}\"";
	$query.= " WHERE id=\"{$id}\";" ;


	$result=mysqli_query($connection, $query);
	confirm_query($result);

	$output["redirect"] = "../edit_contest.php?contest={$_GET["contest"]}";
	return $output;
}

$output = output();
redirect_to($output["redirect"]);
?>
