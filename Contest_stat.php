<?php
		include("Base.php");
		require_once("includes/db_connection.php");
		$id=$_GET["problem"];
		$Accepted = get_stat_problem_in_contest($id,"Accepted");
		$wrong_Answer = get_stat_problem_in_contest($id,"Wrong Answer");
		$pending = get_stat_problem_in_contest($id,"pending");
		$Time_limit = get_stat_problem_in_contest($id,"Time limit exceeded");
		$compile_error = get_stat_problem_in_contest($id,"Compile error");
		
		$Accepted_no = count($Accepted);
		$wrong_Answer_no = count($wrong_Answer);
		$pending_no = count($pending);
		$Time_limit_no = count($Time_limit);
		$compile_error_no = count($compile_error);
		
		$stat = $Accepted_no +$wrong_Answer_no +$pending_no +$Time_limit_no+$compile_error_no ;
		if(! $stat)
			echo "No Statistics Availble ";
		
?>
<html>
  <head>
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
  </head>
  <body>
<div id="piechart_3d" align="left" style="height: 300px; position: relative;width: 750px;padding-left: 220px;padding-right: 0px;">…</div>
</body>
</html><?php
include("Footer.php");
?>