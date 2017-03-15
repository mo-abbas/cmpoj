<?php 
	include("Base.php");
	require_once("includes/db_connection.php");
?>
<div id="rightPan">
	<h1>Contests</h1>
	<h2>Pending</h2>
<?php 
	global $connection;

	$query  = "SELECT * ";
	$query .= "FROM contest ";
	$query .= "WHERE approved='' ";
	$query .= "ORDER BY start_time ASC LIMIT 25";
	
	$result = mysqli_query($connection, $query);
	confirm_query($result);

	$result_arr = query_result_to_array($result);
	//print_r($result_arr);
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
					<br>
					<div class=\"divTopBar\">
					{$contest["start_time"]} | {$contest["end_time"]} | {$c_type} 
					<a href=\"AdminApprove.php?cid={$contest["id"]}\">Approve Contest</a>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a href=\"AdminApprove.php?cid={$contest["id"]}&appr=dis\">Disapprove Contest</a>
					</div>
				</div>
				</a>
				";
			}
		/*
			echo "
			<div class=\"itemDiv\">
				<span class=\"divName\">
					Awesome itemDiv
				</span>
				<div class=\"divTopBar\">
				StartDate | EndDate | #Problems | individual/team | Registerers
				</div>
			</div>
			";*/
		}
	else
	{
		echo "<h2>No pending contests</h2>";
	}
?>
<!-- 	<h2>Disapproved</h2>
<?php
// if (! empty($result_arr))
// 		foreach($result_arr as $contest)
// 		{
// 			if ($contest["approved"] != "Approved" && $contest["approved"] != "")
// 			{
// 				$c_type = $contest["type"] == 0 ? "Individual" : "Team";
// 				echo "
// 				<a href=\"ContestProblems.php?contest={$contest["id"]}\" style=\"text-decoration:none; color:inherit;\">
// 				<div class=\"itemDiv\">
// 					<span class=\"divName\">
// 						{$contest["name"]}
// 					</span>
// 					<br>
// 					<div class=\"divTopBar\">
// 					{$contest["start_time"]} | {$contest["end_time"]} | {$c_type} 
// 					<a href=\"AdminApprove.php?cid={$contest["id"]}\">Approve Contest</a>
// 					&nbsp;&nbsp;&nbsp;&nbsp;
// 					<a href=\"AdminApprove.php?cid={$contest["id"]}&appr=dis\">Disapprove Contest</a>
// 					</div>
// 				</div>
// 				</a>
// 				";
// 			}

// 		}
?>-->
</div>
<?php 
include("Footer.php");
?>