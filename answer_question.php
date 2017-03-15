<?php
	include("Base.php");
	require_once("includes/db_connection.php");
	require_once("includes/validation_functions.php");

	if(!isset($_GET["question"]) || !is_numeric($_GET["question"]))
		redirect_to("index.php");

	$question = find_question_by_id($_GET["question"]);

	if(!$question)
		redirect_to("index.php");

	$contest = find_contest_by_id($question["contest_id"]);

	if(!logged_in() || $_SESSION["id"] != $contest["judge_id"])
		redirect_to("index.php");

	if(isset($_POST["submit"]))
	{
		$required_fields = array("text");
		validate_presences($required_fields);

		if(empty($errors))
		{
			$answer = mysql_prep($_POST["text"]);
			$question_id = mysql_prep($question["id"]);

			$query  = "UPDATE question ";
			$query .= "SET ";
			$query .= "answer='{$answer}' ";
			$query .= "WHERE id={$question_id} ";

			$result = mysqli_query($connection, $query);
			confirm_query($result);

			$_SESSION["message"] = "Answer added successfully.";
			redirect_to("ContestProblems.php?contest=" . $question["contest_id"]);
		}
		else
		{
			$_SESSION["errors"] = $errors;
		}
	}
?>

<style type="text/css">
.form
{
	border-style: groove;
	width: 700px;
	min-height: 300px;
	height: auto;
	padding: 20px 20px;
	border-width: 2	px;
	float: right;
}
</style>

<div id="rightPan">
	<h2>Answer question</h2>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<div>
		<form class="form" method="POST">
			<h3>Question</h3>
			<p style="border: 1px solid black; width: 500px;"><?php echo htmlentities($question["text"]); ?></p>
			<h3>Answer</h3>
			<textarea name="text" rows="10" cols="90"></textarea>
			<br /><br /><br />
			<input type="submit" name="submit" value="Submit" style="margin-left: 300px"/>
		</form>
	</div>
</div>
<?php include("Footer.php") ?>



