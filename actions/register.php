<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");
require_once("../includes/validation_functions.php");

if(logged_in())
	redirect_to("../index.php");

if(!isset($_POST["submit"]))
	redirect_to("../Register.php");

$required_fields = array("first_name", "last_name", "handle", "password", "confirm_password", "email");
$handle = $_POST["handle"];
validate_presences($required_fields);

$fields_with_max_length = array("handle" => 20);
validate_max_lengths($fields_with_max_length);

$fields_with_min_length = array("handle" => 4, "password" => 6);
validate_min_lengths($fields_with_min_length);

validate_email($_POST["email"]);

$account = find_account_by_handle($handle);
$email 	 = find_account_by_email ($_POST["email"]);
$unique = array("handle" => $account, "email" => $email);
validate_unique($unique);

does_match($_POST["password"], $_POST["confirm_password"]);

if(empty($errors))
{
	$handle = mysql_prep($_POST["handle"]);
	$fName  = mysql_prep($_POST["first_name"]);
	$lName  = mysql_prep($_POST["last_name"]);
	$email  = mysql_prep($_POST["email"]);						//TODO: Should make more checks on this

	$password = password_encrypt($_POST["password"]);

	$query  = "INSERT INTO contestant (";
	$query .= " handle ";
	$query .= ") VALUES (";
	$query .= " '{$handle}'";
	$query .= ")";
	
	$result = mysqli_query($connection, $query);
	confirm_query($result);

	$id = mysqli_insert_id($connection);

	$query  = "INSERT INTO account (";
	$query .= " id, first_name, last_name, password, email ";
	$query .= ") VALUES (";
	$query .= " $id, '{$fName}', '{$lName}', '{$password}', '{$email}'";
	$query .= ")";

	$result = mysqli_query($connection, $query);
	confirm_query($result);

	$_SESSION["id"] = $id;
	$_SESSION["handle"] = $handle; 
	$_SESSION["team_id"] = $account["team_id"];
	
	$_SESSION["message"] = "Registered successfully.";
	
	redirect_to("../index.php");
}
else
{
	$_SESSION["errors"] = $errors;
	redirect_to("../Register.php");
}
?>

<?php 
	if(isset($connection)) { mysqli_close($connection); } 
?>
