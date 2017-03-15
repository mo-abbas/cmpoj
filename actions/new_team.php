<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");
require_once("../includes/validation_functions.php");

if(!isset($_POST["submit"]) || !logged_in())
	redirect_to("../Create_Team.php");

$required_fields = array("team_name", "members");
validate_presences($required_fields);

$fields_with_max_length = array("team_name" => 20);
validate_max_lengths($fields_with_max_length);

$fields_with_min_length = array("team_name" => 4);
validate_min_lengths($fields_with_min_length);

$team = find_contestant_by_handle($_POST["team_name"]);
if($team)
	$errors[] = "The handle '" . $_POST["team_name"] . "' is already taken.";

if(count($_POST["members"]) > 4)
	redirect_to("../Create_Team.php");

$accounts = array();
$empty = false;
foreach ($_POST["members"] as $member) 
{
	if(!has_presence(trim($member)))
		$empty = true;

	$account = find_account_by_handle($member);
	if(!$account)
		$errors[] = $member . " doesn't exist.";

	if($account["id"] == $_SESSION["id"])
		$errors[] = "You can't add yourself to a team.";
	if($account["team_id"])
		$errors[] = $account["handle"] . " already has a team.";
	if(key_exists($account["handle"], $accounts))
		$errors[] = "'" . $account["handle"] . "' you can't add a member twice.";

	$accounts[$account["handle"]] = $account;
}

if($empty)
	$errors[] = "You must enter four members.";

if(empty($errors))
{
	$name 	= mysql_prep($_POST["team_name"]);
	$coach	= mysql_prep($_SESSION["id"]);

	$query  = "INSERT INTO contestant (";
	$query .= " handle ";
	$query .= ") VALUES (";
	$query .= " '{$name}'";
	$query .= ")";

	$result = mysqli_query($connection, $query);
	confirm_query($result);

	$id = mysqli_insert_id($connection);

	$query  = "INSERT INTO team (";
	$query .= " id, coach_id ";
	$query .= ") VALUES (";
	$query .= " {$id}, {$coach}";
	$query .= ")";

	$result = mysqli_query($connection, $query);
	confirm_query($result);

	foreach ($accounts as $account) 
	{
		$query  = "UPDATE account ";
		$query .= "SET team_id = {$id} ";
		$query .= "WHERE id={$account["id"]} ";

		$result = mysqli_query($connection, $query);
		confirm_query($result);
	}

	$_SESSION["message"] = "Team created successfully.";
	redirect_to("../index.php");
}
else
{
	$_SESSION["errors"] = $errors;
	redirect_to("../Create_Team.php");
}
print_r($errors);
?>

<?php 
	if(isset($connection)) { mysqli_close($connection); } 
?>
