<?php
include ("Base.php");
require_once("includes/db_connection.php");

function output()
{
	$output = [];
	if (!isset($_GET["contest"]))
	{
		$output["redirect"] = "index.php";
		return $output;
	}

	$contest_id=$_GET["contest"];
	$contest=find_contest_by_id($contest_id);

	if ($_SESSION["id"] != $contest["judge_id"])
	{
		$output["redirect"] = "index.php";
		return $output;
	}

	$output["contest"] = $contest;
	return $output;
}

$output = output();

if (isset($output["redirect"]))
	redirect_to($output["redirect"]);
else {
	$contest = $output["contest"];
?>


<div id="rightPan">
	<h1>Edit Your Contest</h1>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<form class="createContest" method="POST" action="<?php echo "actions/edit_contest_action.php?contest={$contest_id}" ?> ">
		<table>
			<tr>
				<td>Contest Name</td>
				<td><input type="text" name="contest_name" value="<?php echo $contest["name"] ?>"></td>
			</tr>
			<tr>
				<td>Contest starts</td>
				<td><input type="datetime-local" name="contest_starts" value="<?php echo $contest["start_time"] ?>"></td>
			</tr>
			<tr>
				<td>Contest ends</td>
				<td><input type="datetime-local" name="contest_ends"  value="<?php echo $contest["end_time"] ?>"></td>
			</tr>

		</table>
	<input type="submit" name="submit" value="Edit" style="margin: 30px 10em;"/>
</form>
</div>
<?php include("Footer.php"); }?>
