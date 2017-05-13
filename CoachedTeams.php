<?php
include("Base.php");
require_once("includes/db_connection.php");
require_once("includes/validation_functions.php");

function output()
{
	global $connection;
	
	$query  = "SELECT * FROM contestant, team WHERE coach_id={$_SESSION["id"]} AND contestant.id=team.id";

	$result = mysqli_query($connection, $query);
	confirm_query($result);
	$teams = query_result_to_array($result);
	foreach($teams as &$team)
	{
		$team["members"] = get_teams_member($team["id"]);
	}
	unset($team);

	return $teams;
}

if(!logged_in())
	redirect_to("Login.php");

$teams = output();
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
	$first = true;

	if (! empty($teams))
	{
		echo "<div class=\"form\">";
		foreach($teams as $team)
		{
			if(!$first)
				echo "<hr />";
			$first = false;

			echo "<h2><a href=\"Team.php?id={$team["id"]}\">".$team["handle"]."</a></h2>";
			echo "<h5><a href=\"actions/delete_team.php?team={$team["id"]}\"> delete team</a></h5>";

			echo "<table>";
			echo "<th>Handle</th>";
			echo "<th>Name</th>";

			foreach($team["members"] as $member)
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
