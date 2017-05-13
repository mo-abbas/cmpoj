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

		$contest = find_contest_by_id((int)$_GET["contest"]);
		if(!$contest)
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		$judge = false;
		if(logged_in())
			$judge = $_SESSION["id"] == $contest["judge_id"];

		if(strtotime($contest["start_time"]) > time() && !$judge)
		{
			$_SESSION["message"] = "The contest didn't start yet.";
			$output["redirect"] = "ContestProblems.php?contest=" . $contest["id"];
			return $output;
		}

		$submissions = null;
		$problem = null;

		if(isset($_GET["problem"]) && ($problem = find_problem_by_id((int)$_GET["problem"])) && $problem["contest_id"] == $contest["id"])
		{
			$submissions = get_all_submissions_in_problem($problem["id"]);
		}
		else
		{
			$submissions = get_all_submissions_in_contest($contest["id"]);
		}

		$contest["problems"] = get_all_problems_in_contest($contest["id"]);

		$output["submissions"] = $submissions;
		$output["problem"] = $problem;
		$output["contest"] = $contest;
		$output["judge"] = $judge;

		return $output;
	}
	$output = output();
	if(isset($output["redirect"]))
		redirect_to($output["redirect"]);
	else {
		$submissions = $output["submissions"];
		$problem = $output["problem"];
		$contest = $output["contest"];
		$judge = $output["judge"];
?>

<style type="text/css">
.form {
	border-style: groove;

	height: auto;
	border-width: 2px;
	float: right;
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
<script type="text/javascript">
	function change()
	{
		var contest = <?php echo $contest["id"]; ?>;
		var location = "submissions.php?contest=" + contest;
		var select = document.getElementById('problem_select');
		if(select.value != 0)
			location = location + "&problem=" + select.value;
		window.location = location;
	}
</script>
<div id="rightPan">
	<h1 style="margin-left: 0px; ">
		<a href="ContestProblems.php?contest=<?php echo $contest["id"]; ?>">
			<?php echo $contest["name"]; ?>
		</a>
	</h1>
	<h2>Submissions</h2>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	Choose problem
	<select id="problem_select">
		<option value="0">All Problems</option>
		<?php
			foreach ($contest["problems"] as $prob)
			{
				echo "<option value=\"{$prob["id"]}\">{$prob["title"]}</option>";
			}
		?>
	</select>
	<button type="button" name="go" onclick="change();">Go</button>
	<br /><br />
	<div class="form">
		<table>
			<tr >
				<th>Problem name</th>
				<th>Handle</th>
				<th>Time</th>
				<th>Status</th>
				<?php
				if($judge)
					echo "<th>Submission</th>";
				?>
			</tr>
			<?php
				for ($i=0; $i < count($submissions); $i++)
				{
					echo "<tr>";
					if($problem)
					{
						$title = htmlentities($problem["title"]);
						echo "<td><a href=\"Problems.php?problem={$problem["id"]}\">{$problem["title"]}</a></td>";
					}
					else
					{
						$title = htmlentities($submissions[$i]["title"]);
						echo "<td><a href=\"Problems.php?problem={$submissions[$i]["problem_id"]}\">{$title}</a></td>";
					}
					$handle = htmlentities($submissions[$i]["handle"]);
					echo "<td>{$handle}</td>";
					echo "<td>{$submissions[$i]["time"]}</td>";
					echo "<td>{$submissions[$i]["status"]}</td>";
					if($judge)
						echo "<td><a href=\"subm.php?id={$submissions[$i]["id"]}\">View Code</a></td>";
					echo "</tr>";
				}
			?>
		</table>

	</div>
</div>
<?php include("Footer.php"); }?>
