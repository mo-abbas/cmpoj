<?php
	include("Base.php");
	require_once("includes/db_connection.php");
	require_once("includes/validation_functions.php");

	if(!isset($_GET["problem"]) || !is_numeric($_GET["problem"]))
		redirect_to("index.php");

	$problem = find_problem_by_id($_GET["problem"]);

	if(!$problem)
		redirect_to("index.php");

	if(!logged_in() )
		redirect_to("index.php");

	$tutorial = find_tutorial_in_problem_by_account($problem["id"], $_SESSION["id"]);

	if($tutorial)
	{
		$_SESSION["message"] = "You already submitted a tutorial for this problem.";
		redirect_to("Problems.php?problem={$problem["id"]}");
	}

	if(isset($_POST["submit"]))
	{
		$required_fields = array("text");
		validate_presences($required_fields);

		if(empty($errors))
		{
			$text = mysql_prep($_POST["text"]);
			$problem_id = mysql_prep($problem["id"]);

			$query  = "INSERT into tutorial (";
			$query .= "text, problem_id ,account_id ";
			$query .= ") VALUES (";
			$query .= "'{$text}', {$problem_id} ,{$_SESSION["id"]}";
			$query .= ")";

			$result = mysqli_query($connection, $query);
			confirm_query($result);

			$_SESSION["message"] = "tutorial created successfully.";
			//redirect_to("ContestProblems.php?contest=" . $contest_id);
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
		<h2><?php echo "<td>{$problem["title"]} </td>" ?> </h2>
		<h2>Insert your tutorial Here</h2>
	
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<div>
		<form class="form" method="POST">
			<h3>Tutorial text</h3>
			<textarea name="text" rows="10" cols="90"></textarea>
			<br /><br /><br />
			<input type="submit" name="submit" value="Submit" style="margin-left: 300px"/>
		</form>
	</div>
</div>
<?php include("Footer.php") ?>



