<?php
	include("Base.php");
	require_once("includes/db_connection.php");
	require_once("includes/validation_functions.php");

	if(!isset($_GET["contest"]) || !is_numeric($_GET["contest"]))
		redirect_to("index.php");

	$contest = find_contest_by_id($_GET["contest"]);

	if(!$contest)
		redirect_to("index.php");

	if(!logged_in() || $_SESSION["id"] != $contest["judge_id"])
		redirect_to("index.php");

	if(isset($_POST["submit"]))
	{
		$required_fields = array("text");
		validate_presences($required_fields);

		if(empty($errors))
		{
			$text = mysql_prep($_POST["text"]);
			$contest_id = mysql_prep($contest["id"]);

			$query  = "INSERT INTO announcement (";
			$query .= "text, contest_id ";
			$query .= ") VALUES (";
			$query .= "'{$text}', {$contest_id}";
			$query .= ")";

			$result = mysqli_query($connection, $query);
			confirm_query($result);

			$_SESSION["message"] = "Announcement created successfully.";
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
	<h1 style="margin-left: 0px; ">
		<a href="ContestProblems.php?contest=<?php echo $contest["id"]; ?>">
			<?php echo $contest["name"]; ?>
		</a>
	</h1>
	<h2>Insert your announcment Here</h2>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<div>
		<form class="form" method="POST">
			<h3>Announcement text</h3>
			<textarea name="text" rows="10" cols="70"></textarea>
			<br /><br /><br />
			<input type="submit" name="submit" value="Submit" style="margin-left: 300px"/>
		</form>
	</div>
</div>
<?php include("Footer.php") ?>



