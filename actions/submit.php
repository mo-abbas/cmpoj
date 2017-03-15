<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");
require_once("../includes/validation_functions.php");

if(!logged_in())
{
	redirect_to("../index.php");
}

if(!isset($_GET["problem"]))
	redirect_to("../index.php");

$problem = find_problem_by_id((int)$_GET["problem"]);
$compiler = find_compiler_by_code($_POST["compiler"]);
$contest = find_contest_by_id($problem["contest_id"]);

$id = 0;
if($contest["type"] == 0)
	$id = $_SESSION["id"];
else
	$id = $_SESSION["team_id"];

$contestant = find_contestant_in_contest($id, $problem["contest_id"]);

if(!$contestant || !$problem || !$compiler)
{
	redirect_to("../index.php");
}

$required_fields = array("submit_code");
validate_presences($required_fields);

if(empty($errors))
{
	date_default_timezone_set('Africa/Cairo');
	
	$time = date("Y-m-d H:i:s");
	$code = mysql_prep($_POST["submit_code"]);
	$contestant_id = mysql_prep($id);
	$problem_id = $problem["id"];
	$compiler_id = $compiler["id"];

	$query  = "INSERT INTO submission (";
	$query .= " time, code, compiler_id, contestant_id, problem_id ";
	$query .= ") VALUES (";
	$query .= " '{$time}', '{$code}', {$compiler_id}, {$contestant_id}, {$problem_id} ";
	$query .= ")";
	
	$result = mysqli_query($connection, $query);
	confirm_query($result);

	$_SESSION["message"] = "Submitted successfully.";
	redirect_to("../Problems.php?problem=" . $problem["id"]);
}
else
{
	$_SESSION["errors"] = $errors;
	redirect_to("../Submit.php?problem=" . $problem["id"]);
}
?>

<?php 
	if(isset($connection)) { mysqli_close($connection); } 
?>
