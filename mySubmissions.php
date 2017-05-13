<?php
include("Base.php");
require_once("includes/db_connection.php");
require_once("includes/functions.php");

//didn't check if the contestant was not there

function output()
{
	$output = [];

	if (!isset($_GET["id"]))			//if the account id does not exist
	{
		$output["redirect"] = "index.php";
		return $output;
	}

	$contestant = find_contestant_by_id($_GET["id"]);
	if (!$contestant)			//if the account id does not exist
	{
		$output["redirect"] = "index.php";
		return $output;
	}

	$handle=$contestant["handle"];

	// all submissions by contestant
	$submissions=get_all_contestant_submissions($_GET["id"]);
	$showCode = logged_in() && $_SESSION["id"] == $_GET["id"];
	foreach ($submissions as &$submission) {
		$submission["problem"] = find_problem_by_id($submission["problem_id"]);
	}

	unset($submission);

	$output["submissions"] = $submissions;
	$output["showCode"] = $showCode;
	$output["handle"] = $handle;

	return $output;
}

$output = output();
if (isset($output["redirect"]))
	redirect_to($output["redirect"]);
else {
	$submissions = $output["submissions"];
	$handle = $output["handle"];
	$showCode = $output["showCode"];
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
	<h2>Submissions</h2>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<br /><br />
	<div class="form">
		<?php
		if(!empty($submissions))
		{ ?>
		<table>
			<tr >
				<th>Problem name</th>
				<th>Handle</th>
				<th>Time</th>
				<th>Status</th>
				<?php
				if($showCode)
					echo "<th>Submission</th>";
					?>
			</tr>
			<?php

				foreach ($submissions as $submission)
				{
					$problem = $submission["problem"];
					echo "<tr>";
					$title = htmlentities($problem["title"]);
					echo "<td><a href=\"Problems.php?problem={$problem["id"]}\">{$title}</a></td>";
					echo "<td>{$handle}</td>";
					echo "<td>{$submission["time"]}</td>";
					echo "<td>{$submission["status"]}</td>";
					if($showCode)
						echo "<td><a href=\"subm.php?id={$submission["id"]}\">View Code</a></td>";
					echo "</tr>";
				}
			?>
		</table>
		<?php }
		else
			echo "<h2>No submissions found.</h2>";
		?>
	</div>
</div>
<?php include("Footer.php"); }?>
