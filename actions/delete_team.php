<?php

require_once("../includes/functions.php");
require_once("../includes/session.php");
require_once("../includes/db_connection.php");

// doesn't check on judge

function output()
{
	global $connection;
	$output = [];
	if (!logged_in() || !isset($_GET["team"]))
	{
		$output["redirect"] = "../index.php";
		return $output;
	}

	$team = find_team_by_id($_GET["team"]);
	if (!$team)
	{
		$output["redirect"] = "../index.php";
		return $output;
	}

	if ($team["coach_id"] != $_SESSION["id"])
	{
		$output["redirect"] = "../index.php";
		return $output;
	}

	$query  ="DELETE FROM team ";
	$query .="WHERE id={$_GET["team"]}";

	$result=mysqli_query($connection,$query);
	confirm_query($result);

	$_SESSION["message"]="Action succeeded";
	$output["redirect"] = "../CoachedTeams.php";

	return $output;
}

$output = output();
if(isset($output["redirect"]))
	redirect_to($output["redirect"]);
?>
