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

	$submissions = null;
	$problem = null;

	if(isset($_GET["problem"]) && $problem = find_problem_by_id((int)$_GET["problem"]))
	{
		$submissions = get_all_submissions_in_problem($problem["id"]);
	}
	else
	{
		$submissions = get_all_submissions_in_contest($contest["id"]);	//true is used to get pending submissions only
	}

	$page = 1;
	if(isset($_GET["page"]) && is_numeric($_GET["page"]))
		$page = (int)$_GET["page"];

	$number = count($submissions);
	$NoPages = ceil($number / 15);

	if($page > $NoPages || $page < 1)
		$page = 1;

	$start = ($page - 1) * 15;
	$end = min($number, $start + 15);
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
			$problems = get_all_problems_in_contest($contest["id"]);
			foreach ($problems as $prob) 
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
				for ($i=$start; $i < $end; $i++) 
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
	<div style="text-align: center; ">
	<?php
		$prev = $page - 1;
		$next = $page + 1;
		
		if(!($page <= 1))
		{
			echo "<a href='?contest={$contest["id"]}";
			if($problem)
				echo "&problem={$problem["id"]}";
			echo "&page=1'>First</a> ";

			echo "<a href='?contest={$contest["id"]}";
			if($problem)
				echo "&problem={$problem["id"]}";
			echo "&page={$prev}'>Prev</a> ";
		}

		if($page > 3)
			echo ".. ";

		if($NoPages >= 1 && $page <= $NoPages)
		{
		
			for($x = max(1, $page - 2); $x <= min($NoPages, $page + 2); $x++)
			{
				if($x == $page)
					echo "{$x} ";
				else
				{
					echo "<a href=\"?contest={$contest["id"]}";
					if($problem)
						echo "&problem={$problem["id"]}";
					echo "&page={$x}\">{$x}</a> ";
				}
			}
		
		}
		
		if($page + 2 < $NoPages)
			echo ".. ";

		if(!($page >= $NoPages))
		{
			echo "<a href='?contest={$contest["id"]}";
			if($problem)
				echo "&problem={$problem["id"]}";
			echo "&page={$next}'>Next</a> ";

			echo "<a href='?contest={$contest["id"]}";
			if($problem)
				echo "&problem={$problem["id"]}";
			echo "&page={$NoPages}'>Last</a> ";
		}
		
	?>
	</div>
</div>
<?php include("Footer.php") ?>



