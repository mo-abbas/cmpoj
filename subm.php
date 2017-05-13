<?php
	include("Base.php");
	require_once("includes/db_connection.php");

	function output()
	{
		$output = [];
		if(!isset($_GET["id"]) || !is_numeric($_GET["id"]))
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		if(!logged_in())
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		$submission = find_submission_by_id((int)$_GET["id"]);
		if(!$submission)
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		$contestant = find_contestant_by_id($submission["contestant_id"]);
		$compiler = find_compiler_by_id($submission["compiler_id"]);
		$problem = find_problem_by_id($submission["problem_id"]);
		$contest = find_contest_by_id($problem["contest_id"]);
		$judge = $contest["judge_id"];

		if($submission["contestant_id"] != $_SESSION["id"] && $judge != $_SESSION["id"])
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		$allowJudge = $submission["status"] == ' Pending' && $_SESSION["id"] == $judge;

		$output["submission"] = $submission;
		$output["contestant"] = $contestant;
		$output["allowJudge"] = $allowJudge;
		$output["compiler"] = $compiler;
		$output["problem"] = $problem;
		$output["contest"] = $contest;
		$output["judge"] = $judge;

		return $output;
	}

	function post($submission)
	{
		global $connection;
		
		$output = [];
		if(!isset($_POST["status"]))
		{
			return $output;
		}

		if($submission["status"] != ' Pending')
		{
			return $output;
		}

		$allowed = ["Accepted", "Runtime error", "Time limit exceeded", "Compile error", "Wrong answer"];
		$status = $_POST["status"];
		if(!in_array($status, $allowed))
		{
			$output["redirect"] = "?id={$submission["id"]}";
			return $output;
		}

		$status = mysql_prep($status);

		$query  = "UPDATE submission ";
		$query .= "SET status='{$status}' ";
		$query .= "WHERE id={$submission["id"]} ";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		$_SESSION["message"] = "Verdict set successfully.";
		$submission["status"] = $status;
		$output["submission"] = $submission;

		return $output;
	}

	$output = output();
	if(isset($output["redirect"]))
		redirect_to($output["redirect"]);
	else {
		$submission = $output["submission"];
		$contestant = $output["contestant"];
		$allowJudge = $output["allowJudge"];
		$compiler = $output["compiler"];
		$problem = $output["problem"];
		$contest = $output["contest"];
		$judge = $output["judge"];

		$output = post($submission);
		if(isset($output["redirect"]))
			redirect_to($output["redirect"]);
		else {
			if(isset($output["submission"]))
				$submission = $output["submission"];
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
				if($allowJudge)
				{ ?>
					<form method="POST" action="">
						<select name="status">
							<option value="Accepted">Accepted</option>
							<option value="Wrong answer">Wrong Answer</option>
							<option value="Runtime error">Runtime Error</option>
							<option value="Time limit exceeded">Time Limit Exceeded</option>
							<option value="Compile error">Compilation Error</option>
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
<?php include("Footer.php"); }}?>
