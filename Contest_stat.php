<?php
		include("Base.php");
		require_once("includes/db_connection.php");

		// wrong pending number
		// no runtime error
		$id=$_GET["contest"];
		$Accepted = get_stat_problem_in_contest($id,"Accepted");
		$wrong_Answer = get_stat_problem_in_contest($id,"Wrong Answer");
		$pending = get_stat_problem_in_contest($id," Pending");
		$Time_limit = get_stat_problem_in_contest($id,"Time limit exceeded");
		$compile_error = get_stat_problem_in_contest($id,"Compile error");
		$Runtime_error = get_stat_problem_in_contest($id,"Runtime error");

		$Accepted_no = count($Accepted);
		$wrong_Answer_no = count($wrong_Answer);
		$pending_no = count($pending);
		$Time_limit_no = count($Time_limit);
		$compile_error_no = count($compile_error);
		$Runtime_error_no = count($Runtime_error);

		$stat = $Accepted_no +$wrong_Answer_no +$pending_no +$Time_limit_no+$compile_error_no ;
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
		<?php echo"
			['Submissions', 'number'],
			['Accepted ',    {$Accepted_no}],
			['Wrong Answer',  {$wrong_Answer_no}],
			['Pending',  {$pending_no}],
			['Time Limit exceed', {$Time_limit_no}],
			['Compile error', {$compile_error_no}]
		"?>
    ]);

    var options = {
      title: 'Problem Statistics',
      is3D: true,
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
    chart.draw(data, options);
  }
</script>
<div id="rightPan">
<?php
	if(! $stat)
		echo "<h1>No Statistics Availble</h1>";
	else {?>
		<div id="piechart_3d" align="left" style="height: 300px; position: relative;width: 750px;padding-right: 0px;">…</div>
	<?php }?>
</div>
<?php include("Footer.php"); ?>
