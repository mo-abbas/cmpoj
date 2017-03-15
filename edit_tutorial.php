<?php
	include("Base.php");
	require_once("includes/db_connection.php");
	require_once("includes/validation_functions.php");

	if(!logged_in())
		redirect_to("index.php");

	
	if(!isset($_GET["problem"]) || !is_numeric($_GET["problem"]))
		redirect_to("index.php");
	
	$acc_id  = $_SESSION['id'];
	$prob_id = mysql_prep($_GET['problem']);
	
	//getting the tutorial submitted by the current account to this problem 
	//if no tutorial submitted redirect to index 
	//else edit the tutorial


	$query   = "SELECT * ";
	$query  .= "FROM tutorial ";
	$query  .= "WHERE account_id={$acc_id} AND problem_id={$prob_id};";
	
	$results=mysqli_query($connection,$query);
	confirm_query($results);
	
	$row=mysqli_fetch_assoc($results);

	if(!$row)
	{
		$_SESSION["message"] = "You don't have a tutorial to edit";
		redirect_to("Problems.php?problem={$prob_id}");
	}

	if (isset($_POST['submit']))
	{
		$required_fields = array("text");
		validate_presences($required_fields);

		if(empty($errors))
		{
			$text = mysql_prep($_POST["text"]);
			//getting tutorial added by this account 

			$query  = "UPDATE tutorial ";
			$query .= "SET text='{$text}' ";
			$query .= "WHERE id={$row['id']} ";			

			$result = mysqli_query($connection, $query);
			confirm_query($result);

			$_SESSION["message"] = "tutorial UPDATED successfully.";
		}
		else
		{
			$_SESSION["errors"] = $errors;
		}
	}

	$view_text=$row["text"];
	
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
	<h2>Edit your tutorial Here</h2>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<div>
		<form class="form" method="POST">
			<h3>Tutorial text</h3>
			<textarea name="text" rows="10" cols="90"> <?php echo $view_text ?> </textarea>
			<br /><br /><br />
			<input type="submit" name="submit" value="Submit" style="margin-left: 300px"/>
		</form>
	</div>
</div>
<?php include("Footer.php") ?>
