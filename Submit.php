<?php
	include("Base.php");
	require_once("includes/db_connection.php");

// error in condition check

	function output()
	{
		$output = [];
		if(!logged_in())
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		if(!isset($_GET["problem"]) || !is_numeric($_GET["problem"]))
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		$problem = find_problem_by_id((int)$_GET["problem"]);
		if(!$problem)
		{
			$output["redirect"] = "index.php";
			return $output;
		}

		$contest = find_contest_by_id($problem["contest_id"]);

		if(strtotime($contest["end_time"]) < time())
		{
			$_SESSION["message"] = "Sorry the contest has already ended.";
			$output["redirect"] = "ContestProblems.php?contest={$contest["id"]}";
			return $output;
		}

		if(strtotime($contest["start_time"]) > time())
		{
			$_SESSION["message"] = "Sorry the contest didn't start yet.";
			$output["redirect"] = "ContestProblems.php?contest={$contest["id"]}";
			return $output;
		}

		$id = 0;
		if($contest["type"] == 0)
			$id = $_SESSION["id"];
		else if(isset($_SESSION["team_id"]))
			$id = $_SESSION["team_id"];

		$contestant = find_contestant_in_contest($id, $problem["contest_id"]);
		if(!$contestant)
		{
			$_SESSION["message"] = "You can't submit as you didn't join this contest";
			$output["redirect"] = "ContestProblems.php?contest={$contest["id"]}";
			return $output;
		}

		$compilers = get_all_compilers_in_contest($problem["contest_id"]);

		$output["compilers"] = $compilers;
		$output["problem"] = $problem;
		$output["contest"] = $contest;

		return $output;
	}

	$output = output();
	if(isset($output["redirect"]))
		redirect_to($output["redirect"]);
	else {
		$compilers = $output["compilers"];
		$problem = $output["problem"];
		$contest = $output["contest"];
?>
<style type="text/css">
	.code
	{
		height: 300px;
		width:  300px;
	}
	.form
	{
		margin-left: 10px;
		border: double;
		border-style: ridge;
		border-color: default;
		border-width: 2px;
		float: right;
		height: auto;
		min-height: 550px;
		width: 770px;
		font-size: 16px;
		background-color: #F5EBEB;
		padding-left: 10px;

	}
</style>

<script language="javascript" type="text/javascript" src="includes/edit_area/edit_area_full.js"></script>
<script language="javascript" type="text/javascript">

	editAreaLoader.init({
		id: "submit_code"	// id of the textarea to transform
		,start_highlight: true	// if start with highlight
		,allow_resize: "both"
		,allow_toggle: true
		,word_wrap: true
		,language: "en"
		,syntax: "cpp"
	});

	function change_editor()
	{
		editAreaLoader.init({
			id: "submit_code"	// id of the textarea to transform
			,start_highlight: true	// if start with highlight
			,allow_resize: "both"
			,allow_toggle: true
			,word_wrap: true
			,language: "en"
			,syntax: document.getElementById('compiler').value
		});
	}
</script>

<div class="rightPan">
	<form class="form" method="POST" action="actions/submit.php?problem=<?php echo $problem["id"]; ?>">
		<?php
			$errors = errors();
			echo form_errors($errors);
			echo message();
		?>
		<h1 style="margin-left: 0px; ">
			<a href="ContestProblems.php?contest=<?php echo $contest["id"]; ?>">
				<?php echo $contest["name"]; ?>
			</a>
		</h1>
		<table  style="float: left;">
			<tr>
				<td> Problem &nbsp;&nbsp;&nbsp;</td>
				<td><a href="Problems.php?problem=<?php echo $problem["id"]; ?>"><?php echo htmlentities($problem["title"]); ?></a></td>
			</tr>
			<tr style="height:10px;"></tr>
			<tr>
				<td> Language </td>
				<td>
					<select id="compiler" name="compiler" onchange="change_editor();">
					<?php
						foreach ($compilers as $compiler)
						{
							echo "<option value=\"{$compiler["code"]}\">{$compiler["name"]} {$compiler["version"]}</option>";
						}
					?>
					</select>
				</td>
			</tr>
			<tr style="height:10px;"></tr>
			<tr>
				<td> Code </td>
				<td><textarea rows="20" cols="80" id="submit_code" name="submit_code"></textarea></td>
			</tr>
			<tr>
				<td></td>
				<td style="padding-left: 250px; padding-top:10px;"><button>Submit</button></td>
			</tr>
		</table>

	</form>
</div>
<?php
include("Footer.php"); }
?>
