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
	else
		redirect_to("index.php");

	if(strtotime($contest["start_time"]) > time() && !$judge)
	{
		$_SESSION["message"] = "The contest didn't start yet.";
		redirect_to("ContestProblems.php?contest=" . $contest["id"]);
	}

	$questions = null;
	$contestant = null;

	if($contest["type"] == 0)
	{
		$contestant = find_contestant_in_contest($_SESSION["id"], $contest["id"]);
	}
	else
	{
		$contestant = find_contestant_in_contest($_SESSION["team_id"], $contest["id"]);
	}

	if($judge)
	{
		$questions = get_all_questions_in_contest($contest["id"], true);
	}
	else if($contestant)
	{
		$questions = get_all_questions_by_contestant_in_contest($contestant["contestant_id"], $contest["id"]);
	}
	else
		redirect_to("index.php");

	$page = 1;
	if(isset($_GET["page"]) && is_numeric($_GET["page"]))
		$page = (int)$_GET["page"];

	$number = count($questions);
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

<div id="rightPan">
	<h1 style="margin-left: 0px; ">
		<a href="ContestProblems.php?contest=<?php echo $contest["id"]; ?>">
			<?php echo $contest["name"]; ?>
		</a>
	</h1>
	<h2>Questions</h2>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<div class="form">
		<table>
			<tr >
				<th>Handle</th>
				<th>Text</th>
				<th>Answer</th>
			</tr>
			<?php
				for ($i=$start; $i < $end; $i++) 
				{ 
					echo "<tr>";
					echo "<td>{$questions[$i]["handle"]}</td>";
					echo "<td>". htmlentities($questions[$i]["text"]) . "</td>";
					if($judge)
						echo "<td><a href=\"answer_question.php?question=". $questions[$i]["id"] . "\">Answer</a></td>";
					else
						echo "<td>". htmlentities($questions[$i]["answer"]) . "</td>";
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



