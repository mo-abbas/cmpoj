<?php
	include("Base.php");
	require_once("includes/db_connection.php");
	require_once("includes/validation_functions.php");

	if(!isset($_GET["contest"]) || !is_numeric($_GET["contest"]))
		redirect_to("index.php");

	$contest = find_contest_by_id($_GET["contest"]);

	if(!$contest)
		redirect_to("index.php");

	if(strtotime($contest["start_time"]) > time())
	{
		$_SESSION["message"] = "The contest didn't start yet.";
		redirect_to("ContestProblems.php?contest=" . $contest["id"]);
	}

	if(!logged_in())
		redirect_to("index.php");

	$contestant = null;

	if($contest["type"] == 0)
	{
		$contestant = find_contestant_in_contest($_SESSION["id"], $contest["id"]);	
	}
	else
	{
		if(isset($_SESSION["team_id"]) && $_SESSION["team_id"] != 0)
			$contestant = find_contestant_in_contest($_SESSION["team_id"], $contest["id"]);
	}
	if(!$contestant)
		redirect_to("index.php");

	if(isset($_POST["submit"]))
	{
		$required_fields = array("text");
		validate_presences($required_fields);

		if(empty($errors))
		{
			$text = mysql_prep($_POST["text"]);
			$contestant_id = mysql_prep($contestant["contestant_id"]);
			$contest_id = mysql_prep($contest["id"]);

			$query  = "INSERT into question (";
			$query .= "text, contest_id, contestant_id ";
			$query .= ") VALUES (";
			$query .= "'{$text}', {$contest_id}, {$contestant_id}";
			$query .= ")";

			$result = mysqli_query($connection, $query);
			confirm_query($result);

			$_SESSION["message"] = "Question added successfully.";
			redirect_to("ContestProblems.php?contest=" . $contest_id);
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
	<h2><a href="ContestProblems.php?contest=<?php echo $contest["id"] ?>"><?php echo $contest["name"] ?></a></h2>
	<h3>Ask your question Here</h3>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<div>
		<form class="form" method="POST">
			<h3>Question text</h3>
			<textarea name="text" rows="10" cols="90"></textarea>
			<br /><br /><br />
			<input type="submit" name="submit" value="Submit" style="margin-left: 300px"/>
		</form>
	</div>
</div>
<?php include("Footer.php") ?>



