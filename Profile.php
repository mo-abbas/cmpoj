<?php
	include("Base.php");
	require_once("includes/db_connection.php");

// graph shows ended contests only

// if(!isset($_SESSION["id"]))
	// redirect_to("Login.php?dest=Profile");
// $id=$_SESSION["id"];
$id=NULL;
if (isset($_GET["id"]))
{
	$id=$_GET ["id"];
}
else if(isset($_SESSION["id"]) )
{
	$id=$_SESSION ["id"];
}
else {
	redirect_to("index.php");
}

$account= find_contestant_by_id($id);

if (!$account)
{
	redirect_to("index.php");
}

$contestant = find_account_by_handle($account["handle"]);

$solved_problems=get_categories_sloved_by_contestant($id);
$number =count($solved_problems);
$team = get_team_handle_by_account_id($id);

$time = mysql_prep(date("Y-m-d H:i:s"));

//statistics query
$query = "SELECT name,contest_id, rank ";
$query.= "FROM contestant_joins AS cj , contest AS c ";
$query.= "WHERE c.id=cj.contest_id AND cj.contestant_id={$id} ";
$query.= "GROUP BY contest_id;";

$result=mysqli_query($connection,$query);
confirm_query($result);

$contests_names=array();					//array to store names of all contests the contestant participated in
$contestant_score=array();				    //~~~~~~~~~~~~~~ scores ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$size=0;
while ($row=mysqli_fetch_row($result))
{
	$contests_names[]=$row[0];
	$contestant_score[]=$row[2];
	$size++;							   //storing size of the result to Output them
}
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
<h2><?php echo $contestant["first_name"] . ' ' . $contestant["last_name"] ?></h2>
<h2>Handle: <?php echo $account["handle"] ?></h2>
	<h2><?php echo "Team:" ?>
	<?php
	if (!is_null($team["handle"]))
			echo "<a href=\"Team.php?id={$team["team_id"]}\">{$team["handle"]}</a> ";
	else
			echo "No Team.";
	?>
	<h3><a href="mySubmissions.php?id=<?php echo $id; ?>" >Submissions</a></h3>
	<hr />
	<h3>Joined Contests</h3>
	<?php
		global $connection;
		$query  = "SELECT contest_id FROM contestant_joins WHERE contestant_id={$id} LIMIT 10";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		$result = query_result_to_array($result);
		if (!empty($result))
		{
			foreach ($result as $contest_id)
			{
				$query  = "SELECT * FROM contest WHERE id={$contest_id["contest_id"]} LIMIT 1";
				$result_contest = mysqli_query($connection, $query);
				confirm_query($result_contest);
				$result_arr = query_result_to_array($result_contest);

				if (! empty($result_arr))
				foreach($result_arr as $contest)
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
			}
		}
	?>
	<h3>Solved Problems</h3>
		<table>
			<tr>
			<th>category</th>
			<th>Number of problems</th>

			</tr>
			<?php
				for ( $i=0 ; $i < $number ; $i++)
				{
					echo "<tr>";
					echo "<td>{$solved_problems[$i]["category"]}</td>";
					echo "<td>{$solved_problems[$i]["count"]}</td>";
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
      var data_size				=<?php echo json_encode($size); ?>;
      var data_contests_names	=<?php echo json_encode(array_values($contests_names)); ?>;
      var data_contestant_scores=<?php echo json_encode(array_values($contestant_score)); ?>;
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
include("Footer.php");
?>
