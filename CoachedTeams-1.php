<?php
include("Base.php");
require_once("includes/db_connection.php");
require_once("includes/validation_functions.php");


if(!logged_in())
	redirect_to("Login.php");
	
?>

<style type="text/css">
.form {
	border-style: groove;

	height: auto;
	border-width: 2px;
	float: right;
	width: 750px;
	min-height: 400px;
	padding: 10px;
}
.form table {
	border-collapse: collapse;
	padding: 0px;
	margin: 10px;
}
.form td, th{
	border: 2px solid black;
	text-align: center;
}
</style>

<div id="rightPan">
	<div class="divName">
		<span> My Coached Teams </span>
	</div>
	<?php
		global $connection;

	$query  = "SELECT * FROM contestant, team WHERE coach_id={$_SESSION["id"]} AND contestant.id=team.id";
	
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	$result_arr = query_result_to_array($result);
	$first = true;

	if (! empty($result_arr))
	{
		echo "<div class=\"form\">";
		foreach($result_arr as $team)
		{
			if(!$first)
				echo "<hr />";
			$first = false;

			echo "<h2>".$team["handle"]."</h2>";
			if ($team["coach_id"]==$_SESSION["id"])	
				echo "<h5><a href=\"actions/delete_team.php?team={$team["id"]}\"> delete team</a></h5>";		
			$query  = "SELECT * FROM account join contestant on account.id=contestant.id WHERE team_id={$team["id"]}";
			
			$result = mysqli_query($connection, $query);
			confirm_query($result);
			$members = query_result_to_array($result);
			echo "<table>";
			echo "<th>Handle</th>";
			echo "<th>Name</th>";			

			foreach($members as $member)
			{
				echo "<tr>";
				echo "<td><a href=\"Profile.php?id={$member["id"]}\">" . $member["handle"] . "</a></td><td>" .$member["first_name"]." ".$member["last_name"]."</td>";
				echo "</tr>";				
			}
			echo "</table>";

		}
		echo "</div>";
	}
	?>
</div>
<?php
include("Footer.php");
?>