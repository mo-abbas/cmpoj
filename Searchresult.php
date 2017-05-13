<?php
	include("Base.php");
	require_once("includes/db_connection.php");
	require_once("includes/functions.php");

	function output()
	{
		$output = [];

		if (isset($_GET["getByCategory"]))
		{
			$category=$_GET["Searchprob"];
			$category = trim($category);
			$problems =get_problem_by_category($category);
			$output["problems"] = $problems;
		}
		else if(isset($_GET["getByLevel"]))
		{
			$level=$_GET["Searchprob"];
			$level = trim($level);
			$problems = get_problem_by_level((int)$level);
			$output["problems"] = $problems;
		}
		else
		{
			$output["redirect"] = "index.php";
		}

		return $output;
	}
	$output = output();
	if (isset($output["redirect"]))
		redirect_to($output["redirect"]);
	else {
		$problems = $output["problems"];
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

		if(count($problems) == 0)
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
		foreach($problems as $problem)
		{

			echo"<tr>";
			echo "<td>{$problem["title"]}</td>";
			echo "<td>{$problem["id"]}</td>";
			echo "<td>{$problem["level"]}</td>";
			echo "<td><a href=\"problems.php?problem={$problem["id"]}\">View</a></td>";
			echo"</tr>";
		}
	?>
	</table>
</div>

<?php } ?>

</div>
<?php include("Footer.php"); }?>
