<?php 

include ("../Base.php");
require_once("../includes/db_connection.php");

if (!logged_in() || !isset($_SESSION["id"]) || !isset($_GET["team"]))
	redirect_to("../CoachedTeams.php");

echo $_GET["team"];	
$query  ="DELETE FROM team ";
$query .="WHERE id={$_GET["team"]}";

$result=mysqli_query($connection,$query);
confirm_query($result);


$_SESSION["message"]="Action succeeded";

redirect_to("../CoachedTeams.php");
?>