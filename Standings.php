<?php
	include("Base.php");
	require_once("includes/db_connection.php");

	if(!isset($_GET["contest"]) || !is_numeric($_GET["contest"]))
		redirect_to("index.php");

	$contest = find_contest_by_id((int)$_GET["contest"]);

	if(!$contest)
		redirect_to("index.php");

	$judge = false;
	if(logged_in())
		$judge = $_SESSION["id"] == $contest["judge_id"];

	if(strtotime($contest["start_time"]) > time() && !$judge)
	{
		$_SESSION["message"] = "The contest didn't start yet.";
		redirect_to("ContestProblems.php?contest=" . $contest["id"]);
	}

	
	$problems = get_all_problems_in_contest($contest["id"]);
	$standings = get_standings_in_contest($contest["id"]);
?>

<style type="text/css">
.form {
	border-style: groove;

	height: auto;
	border-width: 2px;
	float: right;
	width: 770px;
}
.form table {
	border-collapse: collapse;
	padding: 0px;
	width: 770px;
}
.form td, th{
	border: 2px solid black;
	text-align: center;
}
</style>

<div id="rightPan">
	<h1 style="margin-left: 0px; ">
		<a href="ContestProblems.php?contest=<?php echo $contest["id"]; ?>">
			<?php echo $contest["name"]; ?>
		</a>
	</h1>
	<h2>Standings</h2>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
		$query = "SELECT * from submission ";
		$result = mysqli_query($connection, $query);
		confirm_query($result);
	?>
	<div class="form">
		<table>
			<tr >
				<th>Rank</th>
				<th>Handle</th>
				<th>Penalty</th>
				<?php 
					$problem_number = 1;
					foreach ($problems as $problem) 
					{
						echo "<th><a href=\"Problems.php?problem={$problem["id"]}\">{$problem_number}</a></th>";
						$problem_number++;
					}
				?>
			</tr>
			<?php
				for ($i=$start; $i < $end; $i++) 
				{ 
					echo "<tr>";
					echo "<td>" . ($i+1) . "</td>";
					echo "<td>{$standings[$i]["handle"]}</td>";
					echo "<td>{$standings[$i]["score"]}</td>";
					foreach ($problems as $problem) 
					{
						echo "<td>";
						if(array_key_exists($problem["id"], $standings[$i]["problems"]))
						{
							echo $standings[$i]["problems"][$problem["id"]]["submission_num"];
							echo '/';
							if($standings[$i]["problems"][$problem["id"]]["ac_time"] == '1000-01-01 00:00:00')
								echo '--';
							else
							{
								$time1 = strtotime($contest["start_time"]);
								$time2 = strtotime($standings[$i]["problems"][$problem["id"]]["ac_time"]);
								echo round(($time2 - $time1) / 60);
							}
						}
						else
							echo "0/--";

						echo "</td>";
					}
					echo "</tr>";
				}
			?>
		</table>

	</div>
</div>
<?php include("Footer.php") ?>



