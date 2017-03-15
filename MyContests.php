<?php
include("Base.php");
require_once("includes/db_connection.php");
require_once("includes/validation_functions.php");


if(!logged_in())
	redirect_to("Login.php?dest=MyContests");
	
?>
<div id="rightPan">
	<div class="divName">
		<span> My Contests </span>
	</div>
	<h3>Approved</h3>
	<?php
		global $connection;

	$query  = "SELECT contest.*, admin.username as admin ";
	$query .= "FROM contest LEFT OUTER JOIN admin ON contest.admin_id=admin.id ";
	$query .= "WHERE judge_id={$_SESSION["id"]} ORDER BY start_time DESC LIMIT 10";

	$result = mysqli_query($connection, $query);
	confirm_query($result);

	$result_arr = query_result_to_array($result);
	//print_r($result_arr);
	if (! empty($result_arr))
		foreach($result_arr as $contest)
		{
			if ($contest["approved"] == "Approved")
			{
				$c_type = $contest["type"] == 0 ? "Individual" : "Team";
				echo "
				<a href=\"ContestProblems.php?contest={$contest["id"]}\" style=\"text-decoration:none; color:inherit;\">
				<div class=\"itemDiv\">
					<span class=\"divName\">
						{$contest["name"]}
					</span>
					<div class=\"divTopBar\">
					{$contest["start_time"]} | {$contest["end_time"]} | {$c_type} | Admin: {$contest["admin"]}
					</div>
				</div>
				</a>
				";
			}
		}
	?>
	<h3>Pending</h3>
	<?php
		if (! empty($result_arr))
		foreach($result_arr as $contest)
		{
			if ($contest["approved"] == "")
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
		}
	?>
	<h3>Disapproved</h3>
	<?php
		if (! empty($result_arr))
		foreach($result_arr as $contest)
		{
			if ($contest["approved"] == "Disapproved")
			{
				$c_type = $contest["type"] == 0 ? "Individual" : "Team";
				echo "
				<a href=\"ContestProblems.php?contest={$contest["id"]}\" style=\"text-decoration:none; color:inherit;\">
				<div class=\"itemDiv\">
					<span class=\"divName\">
						{$contest["name"]}
					</span>
					<div class=\"divTopBar\">
					{$contest["start_time"]} | {$contest["end_time"]} | {$c_type} | Admin: {$contest["admin"]}
					</div>
				</div>
				</a>
				";
			}
		}
	?>
</div>
<?php
include("Footer.php");
?>