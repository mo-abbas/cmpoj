<?php

include("Base.php");
require_once("includes/db_connection.php");

if (!isset($_GET["problem"]))
	redirect_to("index.php");

$problem = find_problem_by_id($_GET["problem"]);

$query  = "SELECT * ";
$query .= "FROM tutorial ";
$query .= "WHERE problem_id={$problem["id"]}";


$results=mysqli_query($connection,$query);
confirm_query($results);

$tutorials=query_result_to_array($results);
$message="";
$tutorialID = 0;
if(isset($_POST["submit"]))
{
	if (isset($_POST["start2"]) && isset($_POST["tutorialID"]) && isset($_POST["contestantID"]))
		{
			$rating=mysql_prep($_POST["start2"]);
			$tutorialID=mysql_prep($_POST["tutorialID"]);
			$contestantID=mysql_prep($_POST["contestantID"]);
			//inserting rating 

			$query 	= "INSERT INTO tutorial_rating (";
			$query .= "tutorial_id,account_id,rating) VALUES (";
			$query .= "{$tutorialID},{$contestantID},{$rating}) ";

			$results=mysqli_query($connection,$query);
			if (!$results)
			{
				$message="already rated this tutorial";
			}
		}		

}
?>

<script type="text/javascript" src="jQuery/StarRating/jquery.js"></script>
<script src='jQuery/StarRating/jquery.js' type="text/javascript"></script>
<script src='jQuery/StarRating/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='jQuery/StarRating/jquery.rating.js' type="text/javascript" language="javascript"></script>
<link href='jQuery/StarRating/jquery.rating.css' type="text/css" rel="stylesheet"/>

<?php 
if (! empty($tutorials))
{

	$i=1;
	foreach($tutorials as $element)
	{
		$account_handle=find_account_by_id($element["account_id"]);			
		$tutorial_rating=compute_tutorial_rating($element["id"]);		
		$output_div= "<div class=\"itemDiv\">";

		if($element["id"] == $tutorialID)
			$output_div .= $message;

		$output_div .=
				"<br/>

				<span class=\"divName\">
					tutorial {$i}<br/>
					current rating: {$tutorial_rating["rating"]}
				</span>										
				<div>
				<h2>Description</h2>
				{$element["text"]}
				<br/><br/><br/>
				<span>
				tutorial by: {$account_handle["handle"]}
				</span>
				<br/><br/><br/>
				";
				if (logged_in())
				{
					$output_div.="
						<form method=\"POST\" action=\"view_tutorial.php?problem={$problem["id"]}\">
							<input name=\"start2\" type=\"radio\" class=\"star\" value=\"1\"/> 
							<input name=\"start2\" type=\"radio\" class=\"star\" value=\"2\"/>
							<input name=\"start2\" type=\"radio\" class=\"star\" value=\"3\"/>
							<input name=\"start2\" type=\"radio\" class=\"star\" value=\"4\"/>
							<input name=\"start2\" type=\"radio\" class=\"star\" value=\"5\"/>						
							<input name=\"problem\" type=\"hidden\" value=\"{$_GET["problem"]}\">
							<input name=\"tutorialID\" type=\"hidden\" value=\"{$element["id"]}\" />
							<input name=\"contestantID\" type=\"hidden\" value=\"{$_SESSION["id"]}\" />
						<button type=\"submit\" name=\"submit\" value=\"submit\">Submit</button>
						</form>";
				}
				$output_div.="
				</div>
			</div>				
			";
			echo $output_div;
			$i++;
	}
}
else
	echo "<h2>Not tutorials found.</h2>";
	include("Footer.php");
?>
