<?php
	include("Base.php");
	require_once("includes/db_connection.php");

	//inserts in team contest even if the contesant doesn't have a team
	function output()
	{
		$output = [];
		if(!isset($_GET["contest"]) || !logged_in())
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		$contest = find_contest_by_id($_GET["contest"]);
		if(!$contest)
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		$output["contest"] = $contest;
		$output["showSubmit"] = $contest["type"] == 0 || $_SESSION["team_id"] != 0;
		$output["inTeam"] = $_SESSION["team_id"] != 0;
		return $output;
	}

	function post($contest)
	{
		global $connection;
		global $error;

		if(isset($_POST["submit"]))
		{
			if($contest["type"] == 0)
			{

				$id = mysql_prep($_SESSION["id"]);

				$query  = "SELECT * ";
				$query .= "FROM contestant_joins ";
				$query .= "WHERE contestant_id={$id} AND contest_id={$contest["id"]} ";
				$query .= "LIMIT 1";

				$result = mysqli_query($connection, $query);
				confirm_query($result);

				if ($team = mysqli_fetch_assoc($result))
				{
					$_SESSION["message"] = "You already joined this contest.";
					redirect_to("ContestProblems.php?contest={$contest["id"]}");
					return;
				}

				$query  = "INSERT INTO ";
				$query .= "contestant_joins ";
				$query .= "(contestant_id, contest_id ";
				$query .= ") VALUES (";
				$query .= "{$id}, {$contest["id"]})";

				$result = mysqli_query($connection, $query);
				confirm_query($result);

				$_SESSION["message"] = "You joined the contest successfully.";
				redirect_to("ContestProblems.php?contest={$contest["id"]}");
			}
			else
			{
				if($_SESSION["team_id"] == 0)
				{
					$error[] = "You must be in a team to join this contest.";
					return;
				}

				$id = mysql_prep($_SESSION["team_id"]);

				$query  = "SELECT * ";
				$query .= "FROM contestant_joins ";
				$query .= "WHERE contestant_id={$id} AND contest_id={$contest["id"]} ";
				$query .= "LIMIT 1";

				$result = mysqli_query($connection, $query);
				confirm_query($result);

				if ($team = mysqli_fetch_assoc($result))
				{
					$_SESSION["message"] = "You already joined this contest.";
					redirect_to("ContestProblems.php?contest={$contest["id"]}");
					return;
				}

				$query  = "INSERT INTO ";
				$query .= "contestant_joins ";
				$query .= "(contestant_id, contest_id ";
				$query .= ") VALUES (";
				$query .= "{$id}, {$contest["id"]})";

				$result = mysqli_query($connection, $query);
				confirm_query($result);

				$_SESSION["message"] = "You joined the contest successfully.";
				redirect_to("ContestProblems.php?contest={$contest["id"]}");
			}
		}
	}

	$output = output();
	if(isset($output["redirect"]))
		redirect_to($output["redirect"]);
	else {
		$contest = $output["contest"];
		$inTeam = output["inTeam"];
		$showSubmit = output["showSubmit"];

		post($contest);
?>

<style type="text/css">
	.rules {
		border: 1px solid black;
		font-family: Helvetica;
		padding: 1em;
		font-weight: bold;
		background-color: #FF9955;
		margin: 1em;
	}
</style>
<div id="rightPan">
	<h1>Create Your Contest</h1>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<form style="padding:20px; width: 730px" class="createContest" method="POST" action="">
		<table>
			<div class="rules">
				<p>
					By joining this contest you agree to the following rules:<br />
					<ul style="font-weight: normal;">
						<li>You'll participate in the contest by yourself or with your team only if this is a team contest</li>
						<li>You'll never copy answers of another person.</li>
						<li>You'll never publish your answers to another contestant or anywhere on the internet during the contest.</li>
						<li>You'll not submit any harmful code that may harm the judge, site, a contestant or anything else.</li>
						<li>You'll accept the results of the contest whatever they were.</li>
					</ul>
				</p>
			</div>
			<br/>
			<tr>
				<td>
					<?php
						if($contest["type"] == 0)
							echo "<h3>Join the contest as Individual.</h3>";
						else
						{
							if($inTeam)
								echo "<h3>Join the contest as " . $_SESSION["team_handle"] . "</h3>";
							else
								echo "<h3>You must be in a team to join this contest.</h3>";
						}
					?>
				</td>
				<td>
					<?php
						if($showSubmit)
							echo "<input type=\"submit\" name=\"submit\" value=\"Join\" style=\"margin: 30px 10em;\"/>";
					?>
				</td>
			</tr>
		</table>
	</form>
</div>

<?php include("Footer.php"); }?>
