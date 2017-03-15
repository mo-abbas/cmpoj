<?php
	include("Base.php");
	require_once("includes/db_connection.php");
	$contestant = get_contestant_rating();
	$number =count ($contestant);
?> 	
<style type="text/css">
.form {
	border-style: groove;
	margin-top: 2em;
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
<div class="form">
	<h3>Contestant Rating</h3>
		<table>
			<tr>
			<th>Rank</th>
			<th>id</th>
			<th>handle</th>
			<th>Solved problems</th>
			<th>View</th>
			</tr>
			<?php 
				for ( $i=1 ; $i <= $number ; $i++)
				{
					echo "<tr>";
					echo "<td>{$i}</td>";
					echo "<td> {$contestant[$i-1]["id"]}</td>";
					echo "<td> {$contestant[$i-1]["handle"]}</td>";
					echo "<td> {$contestant[$i-1]["Score"]}</td>";
					echo "<td> <a href=\"Profile.php?id={$contestant[$i-1]["id"]}\">View</a> </td>";
					echo "</tr>";
				}
			?>
			</table>
</div>
</div>


<?php 
include("Footer.php");
?>