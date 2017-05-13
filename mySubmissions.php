<?php 
include("Base.php");
require_once("includes/db_connection.php");
require_once("includes/functions.php");

if (!logged_in()||!isset($_SESSION["id"])||!isset($_GET["id"]))			//if not logged in or the session id does not exist or the account id does not exist
	redirect_to("index.php");

$submissions=get_all_contestant_submissions($_GET["id"]);				//all submissions by contestant
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
				if(logged_in() && $submissions[0]["contestant_id"] == $_SESSION["id"])
					echo "<th>Submission</th>";
					?>
			</tr>
			<?php
				$contestant=find_contestant_by_id($_GET["id"]);
				$handle=$contestant["handle"];
				foreach ($submissions as $problem) 
				{ 
					$problem_record=find_problem_by_id($problem["problem_id"]);												
					echo "<tr>";					
					$title = htmlentities($problem_record["title"]);
					echo "<td><a href=\"Problems.php?problem={$problem_record["id"]}\">{$problem_record["title"]}</a></td>";																									
					echo "<td>{$handle}</td>";
					echo "<td>{$problem["time"]}</td>";
					echo "<td>{$problem["status"]}</td>";	
					if(logged_in() && $problem["contestant_id"] == $_SESSION["id"])
						echo "<td><a href=\"subm.php?id={$problem["id"]}\">View Code</a></td>";
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
<?php include("Footer.php") ?>