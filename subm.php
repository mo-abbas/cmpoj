<?php 
	include("Base.php");
	require_once("includes/db_connection.php");

	if(!isset($_GET["id"]) || !is_numeric($_GET["id"]))
		redirect_to("index.php");

	$submission = null;
	if(!($submission = find_submission_by_id((int)$_GET["id"])))
		redirect_to("index.php");

	$contestant = find_contestant_by_id($submission["contestant_id"]);
	$compiler = find_compiler_by_id($submission["compiler_id"]);
	$problem = find_problem_by_id($submission["problem_id"]);
	$contest = find_contest_by_id($problem["contest_id"]);
	$judge = $contest["judge_id"];

	if($submission["contestant_id"] != $_SESSION["id"] && $judge != $_SESSION["id"])
		redirect_to("index.php");

	if(isset($_POST["submit"]) && $submission["status"] == ' Pending')
	{
		$status = "";
		switch ($_POST["status"]) {
			case 'acc':
				$status = "Accepted";
				break;
			case 'rte':
				$status = "Runtime error";
				break;
			case 'tle':
				$status = "Time limit exceeded";
				break;
			case 'ce':
				$status = "Compile error";
				break;
			case 'wa':
				$status = "Wrong answer";
				break;

			default:
				redirect_to("?id={$submission["id"]}");
				break;
		}

		$status = mysql_prep($status);

		$query  = "UPDATE submission ";
		$query .= "SET status='{$status}' ";
		$query .= "WHERE id={$submission["id"]} ";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		$_SESSION["message"] = "Verdict set successfully.";
		$submission["status"] = $status;
	}
?>

<style type="text/css">
.form {
	border-style: groove;
	width: 730px;
	min-height: 500px;
	height: auto;
	padding: 0px 20px;
	border-width: 2	px;
	float: right;
}

.submission_info {
	font-size: 15px; 
	font-weight: bold; 
	text-align: left;
}

#code_box {
	padding: 10px
}
</style>

<div id="rightPan">
	<h1><a href="ContestProblems.php?contest=<?php echo $contest["id"]; ?>"><?php echo htmlentities($contest["name"]); ?></a></h1>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<div class="form">
		<div class="submission_info">
			<span style="text-decoration: underline; font-size: 20px;">Submission #<?php echo $submission["id"]?></span>
			<span style="float: right; margin-right: 50px">Submitted by: <?php echo htmlentities($contestant["handle"]); ?></span>
			<br /><br />
			<span>Contest: <?php echo htmlentities($contest["name"]); ?></span>
			<span style="float: right; margin-right: 50px">Problem: <?php echo htmlentities($problem["title"]); ?></span>
			<br />
			<span>Compiler used: <?php echo htmlentities($compiler["name"] . ' ' . $compiler["version"]); ?> </span>
			<br /><br />
			<?php
				if($submission["status"] == ' Pending' && logged_in() && $_SESSION["id"] == $judge)
				{ ?>
					<form method="POST" action="">
						<select name="status">
							<option value="acc">Accepted</option>
							<option value="wa">Wrong Answer</option>
							<option value="rte">Runtime Error</option>
							<option value="tle">Time Limit Exceeded</option>
							<option value="ce">Compilation Error</option>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" name="submit" value="Submit" />
					</form>
		<?php   }
				else
				{
					echo "Verdict: " . $submission["status"];
				} ?>
		</div>
		<pre id="code_box" class="prettyprint" style="font-weight: default; "><?php echo htmlentities($submission["code"]); ?></pre>
	</div>
</div>
<?php include("Footer.php"); ?>