<?php
include("Base.php");
require_once("includes/db_connection.php");

// didn't check for the problem existance
// didn't check for start time

function output()
{
	$output = [];

	if(!isset($_GET["problem"]))
	{
		$output["redirect"] = "index.php";
		return $output;
	}

	$id = $_GET["problem"];
	$problem = find_problem_by_id($id);

	if(!$problem)
	{
		$output["redirect"] = "index.php";
		return $output;
	}

	$contest_id=$problem["contest_id"];
	$contest = find_contest_by_id($contest_id);

	$time = date("Y-m-d H:i:s");
	if($contest["start_time"] > $time)
	{
		$output["redirect"] = "index.php";
		return $output;
	}

	$judge_id=$contest["judge_id"];
	$is_judge= logged_in() && $_SESSION["id"] == $judge_id;
	$joinedContest = logged_in() && find_contestant_in_contest($_SESSION["id"], $contest["id"]) != null;

	$samples 	= get_all_samples_in_problem($id);
	$categories = get_all_categories_in_problem($id);

	$output["problem"] = $problem;
	$output["samples"] = $samples;
	$output["is_judge"] = $is_judge;
	$output["categories"] = $categories;
	$output["joinedContest"] = $joinedContest;

	return $output;
}

$output = output();
if(isset($output["redirect"]))
	redirect_to("index.php");
else {
	$problem = $output["problem"];
	$samples = $output["samples"];
	$is_judge = $output["is_judge"];
	$categories = $output["categories"];
	$joinedContest = $output["joinedContest"];

	$id = $problem["id"];
?>
<style>
	.problem
	{
		width: 			  70%;
		min-width: 		  700px;
		min-height: 	  300px;
		clear: 			  none;
		margin-top: 	  5px;
		height: 		  auto;
		padding: 		  10px;
		border-radius: 	  5px;
		border: 		  1px solid black;
	}
	.problem p {
		font-family: 	Helvetica;
		font-size: 	 	20px;
	}
	.sample {
		width: 			  70%	;
		min-width: 		  500px;
		clear: 			  none;
		height: 		  auto;
		padding: 		  10px;
		background-color: #ddd;
		border-radius: 	  5px;
		border: 		  1px solid black;
	}

	.sample h2 {
		padding: 2px;
		margin: 2px;
	}
	.sample h3 {
		padding: 1px;
		margin: 1px;
	}
</style>

<div id="rightPan">
	<?php
		$errors = errors();
		echo form_errors($errors);
		echo message();
	?>
   <div class="itemDiv">
         <span class="divName">
            <?php echo $problem["title"]; ?>
         </span>
         <div class="divTopBar">
            <?php
            if ($is_judge)
            	echo " | <a href=\"edit_problem.php?problem={$id}\">edit problem </a> ";
						if ($joinedContest)
							echo " | <a href=\"Submit.php?problem={$id}\">Submit </a> ";
            ?>
         </div>
   </div>
   <div class="problem">
   		<span>
			Problem Categories:
   		</span>
   		<?php
   			$count_categories=count($categories);
   			$i=0;
   			foreach ($categories as $it)
   			{
   				echo $it["category"];
   				if ($i!=$count_categories-1)
   					echo ", ";
   				else
   					echo "<br />";
   				$i++;
   			}
   		?>
   		<h2>Description:</h2>
		<?php
			echo "<p style=\"\">";
			echo nl2br(htmlentities($problem["text"]));
			echo "</p>";

			$idx = 1;
			foreach ($samples as $sample)
			{
				echo "<div class=\"sample\">";
				echo "<h2>Case" . $idx++ . "</h2>";
				echo "<h3>Input:</h3>" . nl2br(htmlentities($sample["input"])) . "<br />";
				echo "<h3>Output:</h3>" . nl2br(htmlentities($sample["input"])) . "<br />";
				echo "</div><br />";
			}
		?>
   </div>
</div>

<?php
include("Footer.php"); }
?>
