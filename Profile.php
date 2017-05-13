<?php
	include("Base.php");
	require_once("includes/db_connection.php");

// graph shows ended contests only

// if(!isset($_SESSION["id"]))
	// redirect_to("Login.php?dest=Profile");
// $id=$_SESSION["id"];
function output()
{
	global $connection;

	$output = [];
	$id = null;
	if (isset($_GET["id"]))
		$id = $_GET ["id"];
	else if(logged_in())
		$id = $_SESSION ["id"];
	else
	{
		$output["redirect"] = "index.php";
		return $output;
	}

	$contestant= find_contestant_by_id($id);
	if (!$contestant)
	{
		$output["redirect"] = "index.php";
		return $output;
	}

	$account = find_account_by_handle($contestant["handle"]);
	if (!$account)
	{
		$output["redirect"] = "index.php";
		return $output;
	}

	$solved_problems=get_categories_sloved_by_contestant($id);
	$team = get_team_handle_by_account_id($id);

	//statistics query
	$query = "SELECT c.*, rank ";
	$query.= "FROM contestant_joins AS cj , contest AS c ";
	$query.= "WHERE c.id=cj.contest_id AND cj.contestant_id={$id} ";

	$result=mysqli_query($connection,$query);
	confirm_query($result);

	$joined_contests = query_result_to_array($result);

	$output["team"] = $team;
	$output["account"] = $account;
	$output["solved_problems"] = $solved_problems;
	$output["joined_contests"] = $joined_contests;

	return $output;
}

$output = output();
if(isset($output["redirect"]))
	redirect_to($output["redirect"]);
else {
	$team = $output["team"];
	$account = $output["account"];
	$solved_problems = $output["solved_problems"];
	$joined_contests = $output["joined_contests"];
?>
<style type="text/css">
.userImage {
	float: right;
	clear: none;
	width: 150px;
	height: 200px;
	border: 1px solid black;
}
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
<div id="rightPan" style="float: right; max-width: 400px;">

<div class="form" style="border:none;">
<h2><?php echo $account["first_name"] . ' ' . $account["last_name"] ?></h2>
<h2>Handle: <?php echo $account["handle"] ?></h2>
	<h2><?php echo "Team:" ?>
	<?php
	if ($team)
	{
		echo "<a href=\"Team.php?id={$team["team_id"]}\">{$team["handle"]}</a> ";
	}
	else
			echo "No Team.";
	?>
	<h3><a href="mySubmissions.php?id=<?php echo $account["id"]; ?>" >Submissions</a></h3>
	<hr />
	<h3>Joined Contests</h3>
	<?php
		foreach ($joined_contests as $contest)
		{
			$c_type = $contest["type"] == 0 ? "Individual" : "Team";
			echo "
			<a href=\"ContestProblems.php?contest={$contest["id"]}\" style=\"text-decoration:none; color:inherit;\">
			<div class=\"itemDiv\" style=\"margin-bottom:20px;\">
				<span class=\"divName\">
					{$contest["name"]}
				</span>
				<div class=\"divTopBar\">
				{$contest["start_time"]} | {$contest["end_time"]} | {$c_type}
				</div>
			</div>
			</a>
			";
		}
	?>
	<h3>Solved Problems</h3>
		<table>
			<tr>
			<th>category</th>
			<th>Number of problems</th>

			</tr>
			<?php
				foreach($solved_problems as $problem)
				{
					echo "<tr>";
					echo "<td>{$problem["category"]}</td>";
					echo "<td>{$problem["count"]}</td>";
					echo "</tr>";
				}
			?>
			</table>
	<br />
	<h3>Stats</h3>
	<div id="chart_div" style="width:400; overfloat-y:auto; float: left; margin-top:00px;"></div>

<div id="chart_div" style="float: left; margin-top:50px;"></div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.

      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

       // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Contest');
      data.addColumn('number', 'Rank');
      var data_size				=<?php echo json_encode(count($joined_contests)); ?>;
      var data_contests_names	=<?php echo json_encode(array_column($joined_contests, "name")); ?>;
      var data_contestant_scores=<?php echo json_encode(array_column($joined_contests, "rank")); ?>;
      document.getElementById("chart_div").innerHTML=data_size;
  	 var i=0;
     for (;i<data_size;i++){
      	var data_array=[data_contests_names[i],Number(data_contestant_scores[i])];
      	data.addRows([data_array]);
      }

      // Set chart options
      var options = {'title':'Contestant\'s Statistics' ,
                     'width':700,
                     'height':300
                 };

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    </script>

<?php
include("Footer.php"); }
?>
