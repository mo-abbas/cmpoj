<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");
require_once("../includes/validation_functions.php");

if(!isset($_POST["submit"]) || !logged_in() || !isset($_GET["contest"]))
	redirect_to("../create_problem.php");

$contest = find_contest_by_id($_GET["contest"]);

if(!$contest || $contest["judge_id"] != $_SESSION["id"])
	redirect_to("../index.php");

$required_fields = array("problem_name", "problem_text");
validate_presences($required_fields);

$arrays = array("sample_input", "sample_output", "categories");
validate_arrays($arrays);

$fields_with_max_length = array("problem_name" => 30);
validate_max_lengths($fields_with_max_length);

$fields_with_min_length = array("problem_name" => 4);
validate_min_lengths($fields_with_min_length);

if(empty($errors))
{
	$name 		= mysql_prep($_POST["problem_name"]);
	$text   	= mysql_prep($_POST["problem_text"]);
	$contest_id = mysql_prep($_GET["contest"]);
	$level		= mysql_prep($_POST["level"]);	

	$query  = "INSERT INTO problem (";
	$query .= " title, level, text, contest_id ";
	$query .= ") VALUES (";
	$query .= " '{$name}', {$level}, '{$text}', {$contest_id}";
	$query .= ")";

	$result = mysqli_query($connection, $query);
	confirm_query($result);

	$id = mysqli_insert_id($connection);
	$sample_input  = $_POST["sample_input"];
	$sample_output = $_POST["sample_output"];
	$category	   = $_POST["categories"];

	for ($i=0; $i < count($sample_input); $i++) 
	{ 
	//	if(empty(trim($sample_input[$i])) || empty(trim($sample_output[$i])))
	//		continue;

		$input  = mysql_prep($sample_input[$i] );
		$output = mysql_prep($sample_output[$i]);

		$query  = "INSERT INTO samples (";
		$query .= " problem_id, input, output ";
		$query .= ") VALUES (";
		$query .= " {$id}, '{$input}', '{$output}'";
		$query .= ")";
		
		$result = mysqli_query($connection, $query);
		confirm_query($result);
	}

	for ($i=0; $i < count($category); $i++) 
	{ 
		if(empty(trim($sample_input[$i])) || empty(trim($sample_output[$i])))
			continue;

		$category_text  = mysql_prep($category[$i]);
		$category_text  = trim($category_text);

		$insertion_condition=true;					//to avoid duplicate categories

		if (isset($old_category))
		{	
			if ($old_category==$category_text)				
				$insertion_condition=false;

		}

		$query  = "INSERT INTO problem_category (";
		$query .= " problem_id, category ";
		$query .= ") VALUES (";
		$query .= " {$id}, '{$category_text}'";
		$query .= ")";
		
		$old_category=$category_text;

		if ($insertion_condition)
		{
			$result = mysqli_query($connection, $query);
			confirm_query($result);
		}
	}

	$_SESSION["message"] = "Problem created successfully.";
	redirect_to("../ContestProblems.php?contest=" . $contest_id);
}
else
{
	$_SESSION["errors"] = $errors;
	redirect_to("../insert_prob.php?contest=" . $contest["id"]);
}
?>

<?php 
	if(isset($connection)) { mysqli_close($connection); } 
?>
