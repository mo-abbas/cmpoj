<?php
	include("Base.php");
	require_once("includes/db_connection.php");

function output()
{
	$output = [];

	if(!isset($_GET["contest"]) || !is_numeric($_GET["contest"]))
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

	$judge = false;
	if(logged_in())
		$judge = $_SESSION["id"] == $contest["judge_id"];

	$problems = get_all_problems_in_contest($contest["id"]);
	$showContest = strtotime($contest["start_time"]) <= time() || $judge;
	$showProblems = !empty($problems);

	$output["judge"] = $judge;
	$output["contest"] = $contest;
	$output["problems"] = $problems;
	$output["showContest"] = $showContest;
	$output["showProblems"] = $showProblems;

	return $output;
}

$output = output();
if(isset($output["redirect"]))
	redirect_to($output["redirect"]);
else
{
	$judge = $output["judge"];
	$contest = $output["contest"];
	$problems = $output["problems"];
	$showContest = $output["showContest"];
	$showProblems = $output["showProblems"];

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

		if(!$showContest)
			echo "<h2>The contest didn't start yet.</h2>";
		else if(!$showProblems)
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
<?php include("Footer.php"); }?>
