<?php 
	include("Base.php");	
	require_once("includes/db_connection.php");
	$message="";
	if(isset($_POST["submit"])&&logged_in())
	{
		if (isset($_POST["start2"]) && isset($_POST["contestID"]))
		{
			$rating=mysql_prep($_POST["start2"]);
			$contestID=mysql_prep($_POST["contestID"]);
			$contestantID=mysql_prep($_SESSION["id"]);
			//inserting rating 

			$query 	= "INSERT INTO contest_rating (";
			$query .= "contest_id,account_id,rating) VALUES (";
			$query .= "{$contestID},{$contestantID},{$rating}) ";

			$results=mysqli_query($connection,$query);
			if (!$results)
			{
				$_SESSION["message"]="already rated the contest";
			}
		}		
	}
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
	$query .= "WHERE end_time > '{$time}' AND approved='Approved' ";
	$query .= "ORDER BY start_time ASC LIMIT 10";
	
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	$result_arr = query_result_to_array($result);
	
	if (! empty($result_arr))
		foreach($result_arr as $contest)
		{
			if ($contest["approved"] == "Approved")
			{
				$c_type = $contest["type"] == 0 ? "Individual" : "Team";
				$contest_rating=compute_contest_rating($contest["id"]);
				$output_div= "
				
				<div class=\"itemDiv\">
					<span class=\"divName\">
						<a href=\"ContestProblems.php?contest={$contest["id"]}\" style=\"text-decoration:none; color:inherit;\">
							{$contest["name"]}
						</a>
					</span>	<div class=\"divTopBar\">
					{$contest["start_time"]} | {$contest["end_time"]} | {$c_type} | Current Rating: {$contest_rating["rating"]}<br/>
					";
					if (logged_in())
					{
							$output_div .="
							<form method=\"POST\" action=\"Contests.php?\">
								<input name=\"start2\" type=\"radio\" class=\"star\" value=\"1\"/> 
								<input name=\"start2\" type=\"radio\" class=\"star\" value=\"2\"/>
								<input name=\"start2\" type=\"radio\" class=\"star\" value=\"3\"/>
								<input name=\"start2\" type=\"radio\" class=\"star\" value=\"4\"/>
								<input name=\"start2\" type=\"radio\" class=\"star\" value=\"5\"/>
								<input name=\"contestID\" type=\"hidden\" value=\"{$contest["id"]}\" />
							<button type=\"submit\" name=\"submit\" value=\"submit\">Submit</button>
							</form>
							";
					}
					$output_div .="
					<br/><br/><br/>
					
					</div>
				</div>				
				";
				echo $output_div;
			}
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