<?php
include("Base.php");
require_once("includes/db_connection.php");
require_once("includes/validation_functions.php");

function output()
{
	$output = [];
	if(!logged_in())
	{
		$output["redirect"] = "Login.php";
		return $output;
	}

	global $connection;

	$query  = "SELECT contest.* ";
	$query .= "FROM contest ";
	$query .= "WHERE judge_id={$_SESSION["id"]} ORDER BY start_time DESC LIMIT 10";

	$result = mysqli_query($connection, $query);
	confirm_query($result);

	$output["result"] = query_result_to_array($result);
	return $output;
}

$output = output();
if(isset($output["redirect"]))
	redirect_to($output["redirect"]);
else {
	$result_arr = $output["result"];
?>
<div id="rightPan">
	<div class="divName">
		<span> My Contests </span>
	</div>
	<?php

	foreach($result_arr as $contest)
	{
		$c_type = $contest["type"] == 0 ? "Individual" : "Team";
		echo "
		<a href=\"ContestProblems.php?contest={$contest["id"]}\" style=\"text-decoration:none; color:inherit;\">
		<div class=\"itemDiv\">
			<span class=\"divName\">
				{$contest["name"]}
			</span>
			<div class=\"divTopBar\">
			{$contest["start_time"]} | {$contest["end_time"]} | {$c_type}
			</div>
		</div>
		</a>
		";
	}
	?>
</div>
<?php
include("Footer.php"); }
?>
