<?php 
	include("Base.php"); 
	require_once("includes/db_connection.php");
	require_once("includes/functions.php");
	
	if (isset($_GET["getByCategory"]))
	{
		$category=$_GET["Searchprob"];
		$category = trim($category);
		$problems =get_problem_by_category($category);
	}
	else if(isset($_GET["getByLevel"]))
	{
		$level=$_GET["Searchprob"];
		$level = trim($level);
		$problems = get_problem_by_level((int)$level);
	}
	else
	{
		redirect_to("index.php");
	}
	
	
	$number =count($problems);
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

<div id="rightban">
	<h2>Problems  </h2>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();

		if($number == 0)
			echo "<h2>No results found</h2>";
		else {
	?>

<div class="form">
	<table>
		<tr>
			<th>Title</th>
			<th>Id</th>
			<th>level</th>
			<th>View</th>
		</tr>
	<?php
		for ($i=0 ;$i<$number ;$i++)
		{
			
			echo"<tr>";
			echo "<td>{$problems[$i]["title"]}</td>";
			echo "<td>{$problems[$i]["id"]}</td>";
			echo "<td>{$problems[$i]["level"]}</td>";
			echo "<td><a href=\"problems.php?problem={$problems[$i]["id"]}\">View</a></td>";
			echo"</tr>";
		}
	?>
	</table>
</div>

<?php } ?>

</div>
<?php include("Footer.php"); ?>