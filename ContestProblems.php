<?php 
	include("Base.php");
	require_once("includes/db_connection.php");

	if(!isset($_GET["contest"]) || !is_numeric($_GET["contest"]))
		redirect_to("index.php");

	$contest = find_contest_by_id($_GET["contest"]);

	if(!$contest)
		redirect_to("index.php");

	$judge = false;
	if(logged_in())
		$judge = $_SESSION["id"] == $contest["judge_id"];

	$contestant = null;

	if(logged_in())
	{
		if($contest["type"] == 0)
			$contestant = find_contestant_in_contest($_SESSION["id"], $contest["id"]);
		if($contest["type"] == 0 && isset($_SESSION["id"]))
			$contestant = find_contestant_in_contest($_SESSION["id"], $contest["id"]);
		else if (isset($_SESSION["team_id"]))
			$contestant = find_contestant_in_contest($_SESSION["team_id"], $contest["id"]);
	}	
?>

<div id="rightPan">
	<h1><?php echo htmlentities($contest["name"]); ?></h1>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();

		echo "<a href=\"Standings.php?contest={$contest["id"]}\">Standings</a>&nbsp";
		echo "<a href=\"submissions.php?contest={$contest["id"]}\">Submissions</a>&nbsp";
		echo "<a href=\"Contest_stat.php?problem={$contest["id"]}\">Statistics</a>&nbsp";

		if(logged_in())
			echo "<a href=\"join_contest.php?contest={$contest["id"]}\">Join Contest</a>&nbsp";

		if($judge)
		{
			echo "&nbsp;|&nbsp;&nbsp;";
			echo "<a href=\"insert_prob.php?contest={$contest["id"]}\">Add problem</a>&nbsp";
			echo "<a href=\"edit_contest.php?contest={$contest["id"]}\">Edit Contest</a>&nbsp";	
		}

		$problems = get_all_problems_in_contest($contest["id"]);		
		if(strtotime($contest["start_time"]) > time() && !$judge)
			echo "<h2>The contest didn't start yet.</h2>";
		else if(empty($problems))
		{
			echo "<h2>No problems added yet</h2>";	
		}
		else
		{
			echo "<h2>All Problems</h2>";
			foreach ($problems as $problem) 
			{ 				
				?>
				<div class="itemDiv">
					<span class="divName">
						<?php echo "<a href=\"Problems.php?problem={$problem["id"]}\">" . htmlentities($problem["title"]) . "</a>"; ?>
					</span>
					<div class="divTopBar">
						level(<?php echo $problem["level"] ?>) 
					</div>
				</div>

			<?php
			}
		}?>
		</div>
</div>
<?php include("Footer.php"); ?>