<?php 
	include("Base.php");	
	require_once("includes/db_connection.php");
	$message="";
?>
<div id="rightPan">
	<h1>Contests</h1>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<h2>Recent Contests</h2>
<?php 
	global $connection;

	$time = date("Y-m-d H:i:s");

	$query  = "SELECT * ";
	$query .= "FROM contest ";
	$query .= "WHERE end_time > '{$time}' ";
	$query .= "ORDER BY start_time ASC LIMIT 10";
	
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	$result_arr = query_result_to_array($result);
	
	if (! empty($result_arr))
		foreach($result_arr as $contest)
		{
			$c_type = $contest["type"] == 0 ? "Individual" : "Team";
			$output_div= "
			
			<div class=\"itemDiv\">
				<span class=\"divName\">
					<a href=\"ContestProblems.php?contest={$contest["id"]}\" style=\"text-decoration:none; color:inherit;\">
						{$contest["name"]}
					</a>
				</span>	<div class=\"divTopBar\">
				{$contest["start_time"]} | {$contest["end_time"]} | {$c_type}<br/>
				<br/><br/><br/>
				
				</div>
			</div>				
			";
			echo $output_div;
		}

?>
</div>
<script type="text/javascript" src="jQuery/StarRating/jquery.js"></script>
<script src='jQuery/StarRating/jquery.js' type="text/javascript"></script>
<script src='jQuery/StarRating/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='jQuery/StarRating/jquery.rating.js' type="text/javascript" language="javascript"></script>
<link href='jQuery/StarRating/jquery.rating.css' type="text/css" rel="stylesheet"/>
<?php 
include("Footer.php");
?>	