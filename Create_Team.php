<?php 
	include("Base.php"); 
	require_once("includes/db_connection.php");
	if(!logged_in())
		redirect_to("index.php");
?>

<div id="rightPan">
	<h1>Create Your Contest</h1>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<form class="createContest" method="POST" action="actions/new_team.php">
		<table id="team">
			<tr>
				<td>Team Name</td>
				<td><input type="text" name="team_name"></td>
			</tr>
			<tr>
				<td>Team member 1</td>
				<td><input type="text" name="members[]"></td>
			</tr>
			<tr>
				<td>Team member 2</td>
				<td><input type="text" name="members[]"></td>
			</tr>
			<tr>
				<td>Team member 3</td>
				<td><input type="text" name="members[]"></td>
			</tr>
			<tr>
				<td>Team member 4</td>
				<td><input type="text" name="members[]"></td>
			</tr>
		</table>
		<input type="submit" name="submit" value="Create" style="margin: 30px 10em;"/>
	</form>
</div>

<?php include("Footer.php"); ?>
