<?php
include("Base.php");
require_once("includes/db_connection.php");
require_once("includes/validation_functions.php");

if(!isset($_GET["cid"]))
	redirect_to("index.php");

$contest = find_contest_by_id($_GET["cid"]);

if (!isset($_SESSION["adid"]) || !$contest) redirect_to("index.php");

$appr = "Approved";
if (isset($_GET["appr"]))
	$appr = "Disapproved";

$query  = "UPDATE contest SET approved='{$appr}', admin_id={$_SESSION["adid"]} WHERE id={$_GET["cid"]}"; //CHECK IT IS A NUMBER FIRST

echo $query;

$result = mysqli_query($connection, $query);
confirm_query($result);

redirect_to("PendingContests.php");
?>