<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");
require_once("../includes/validation_functions.php");

if(!isset($_POST["submit"]) || !logged_in())
	redirect_to("../create_contest.php");

$required_fields = array("contest_name", "contest_starts", "contest_ends", "type", "compilers");
validate_presences($required_fields);

$fields_with_max_length = array("contest_name" => 20);
validate_max_lengths($fields_with_max_length);

$fields_with_min_length = array("contest_name" => 4);
validate_min_lengths($fields_with_min_length);

validate_date_time($_POST["contest_starts"]);
validate_date_time($_POST["contest_ends"]);

date_default_timezone_set('Africa/Cairo');

$start = strtotime($_POST["contest_starts"]);
$end   = strtotime($_POST["contest_ends"  ]);

if($start < time())
{
	$errors["start"] = "Start time can't be a previous date.";
}
else if($start > $end)
{
	$errors["start"] = "Start time must be before end time.";
}
else if($start + 3600 > $end)
{
	$errors["start"] = "Contest must last at least 1 hour.";
}

if(empty($errors))
{
	$start  = date("Y-m-d H:i:s", $start);
	$end    = date("Y-m-d H:i:s", $end  );

	$name 	= mysql_prep($_POST["contest_name"]);
	$start  = mysql_prep($start);
	$end   	= mysql_prep($end);
	$type 	= mysql_prep($_POST["type"]);
	$judge  = mysql_prep($_SESSION["id"]);

	$query  = "INSERT INTO contest (";
	$query .= " name, start_time, end_time, type, judge_id ";
	$query .= ") VALUES (";
	$query .= " '{$name}', '{$start}', '{$end}', {$type}, {$judge}";
	$query .= ")";

	echo $query;
	$result = mysqli_query($connection, $query);
	confirm_query($result);

	$id = mysqli_insert_id($connection);

	foreach ($_POST["compilers"] as $compiler_id => $state) 
	{
		if($state != "on")
			continue;

		$compiler_id = mysql_prep($compiler_id);
		$state = mysql_prep($state);
		
		$query  = "INSERT INTO available_compiler (";
		$query .= " contest_id, compiler_id ";
		$query .= ") VALUES (";
		$query .= " {$id}, {$compiler_id}";
		$query .= ")";

		$result = mysqli_query($connection, $query);
		confirm_query($result);
	}

	$_SESSION["message"] = "Contest created successfully.";
	redirect_to("../index.php");
}
else
{
	$_SESSION["errors"] = $errors;
	redirect_to("../create_contest.php");
}
?>

<?php 
	if(isset($connection)) { mysqli_close($connection); } 
?>
