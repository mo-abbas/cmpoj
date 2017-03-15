<?php

include ("Base.php");
require_once("includes/db_connection.php");
require_once("includes/functions.php");

if(!isset($_GET))
	redirect_to("index.php");

//retriveing data before update

$id=$_GET["problem"];
$problem= find_problem_by_id($id);
$problem_name=$problem["title"];

//updating data if update required
if (isset($_POST['submit']))
{
		
	$query = "UPDATE problem ";
	$query.= "SET text='{$_POST["problem_text"]}' ";
	$query.= "WHERE id={$id}";

	$result=mysqli_query($connection,$query);
	confirm_query($result);
	
	

}
	
	$id=$_GET["problem"];
	$problem= find_problem_by_id($id);
	$problem_name=$problem["title"];
	$problem_discription=$problem["text"]; //updating old text 



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
		float: 			  right;
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
<br/><br/>

<div class="rightPan">
		<?php
			$errors = errors();
			echo form_errors($errors); 
			echo message(); 
		?>
		<h2 style="margin-left: 10px; float: left;">Edit_Problems</h2>
	    <div class="itemDiv" style="float: right;">
         	<span class="divName">
            	<?php echo $problem["title"]; ?>
         	</span>	
		</div>
		<form class="form" method="POST" action="edit_problem.php?<?php echo "problem={$id}" ?>">
		<div class="problem">
		<!-- NOTE don't mess with the indentation. its meant to be like that  -->
			<textarea rows="10" cols="80" name="problem_text" >
<?php echo $problem_discription ?>					
				
			</textarea>
		<br/>
		<input type="submit" name="submit" style="margin-top: 20px;"/>
		</div>
		</form>

</div>