<?php
	include("Base.php");
	require_once("includes/db_connection.php");

	function output()
	{
		global $connection;

		$output = [];
		if(!isset($_GET["id"]))
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		$id = $_GET["id"];
		$team = get_team_handle($id);
		if(!$team)
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		$members = get_teams_member($id);
		$time = mysql_prep(date("Y-m-d H:i:s"));

		//statistics query
		$query = "SELECT c.*, rank ";
		$query.= "FROM contestant_joins AS cj , contest AS c ";
		$query.= "WHERE c.id=cj.contest_id AND cj.contestant_id={$id} AND c.end_time < '{$time}' ";
		$query.= "GROUP BY contest_id;";

		$result=mysqli_query($connection,$query);
		confirm_query($result);

		$joined_contests = query_result_to_array($result);
		$solved_problems = get_categories_sloved_by_contestant($id);

		$output["team"] = $team;
		$output["members"] = $members;
		$output["solved_problems"] = $solved_problems;
		$output["joined_contests"] = $joined_contests;

		return $output;
	}

	$output = output();
	if(isset($output["redirect"]))
		redirect_to($output["redirect"]);
	else {
		$team = $output["team"];
		$members = $output["members"];
		$solved_problems = $output["solved_problems"];
		$joined_contests = $output["joined_contests"];
?>

<style type="text/css">
.form {
	border-style: groove;
	height: auto;
	border-width: 2px;
	float: right;
	padding: 0 10px;
}
.form table {
	border-collapse: collapse;
	padding: 0px;
	width: 750px;
}
.form td, th{
	border: 2px solid black;
	text-align: center;
}
</style>
<div id="rightPan">
	<h2><?php echo $team["handle"] ?> </h2>


<div class="form">
	<h3>Members</h3>
		<table>
			<tr>
			<th>id</th>
			<th>Handle</th>
			</tr>
			<?php
				for ( $i=0 ; $i < count($members) ; $i++)
				{
					echo "<tr>";
					echo "<td>{$members[$i]["id"]}</td>";
					echo "<td>{$members[$i]["handle"]}</td>";
					echo "</tr>";
				}
			?>
			</table>
	<br />
	<h3><a href="mySubmissions.php?id=<?php echo $id; ?>" >Submissions</a></h3>
	<h3>Solved Problems</h3>
	<table>
		<tr>
		<th>category</th>
		<th>Number of problems</th>

		</tr>
		<?php
			for ( $i=0 ; $i < count($solved_problems) ; $i++)
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
	<div id="chart_div" style="width:400; height:300; float: left; margin-top:300px;"></div>


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
      data.addColumn('number', 'Score');
			var data_size				=<?php echo json_encode(count($joined_contests)); ?>;
      var data_contests_names	=<?php echo json_encode(array_column($joined_contests, "name")); ?>;
      var data_contestant_scores=<?php echo json_encode(array_column($joined_contests, "rank")); ?>;
  	 // document.write(data_size);
  	  //document.write(data_contests_names[0]);
  	  //document.write(data_contestant_scores);


  	 var i=0;
     for (;i<data_size;i++){
      	var data_array=[data_contests_names[i],Number(data_contestant_scores[i])];
      	data.addRows([data_array]);
      	//document.write([data_array]);
      }

		// Set chart options
      var options = {'title':'Contestant\'s Statistics' ,
                     'width':400,
                     'height':300};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }


</script>
</div>
<?php
include("Footer.php"); }
?>
