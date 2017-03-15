<?php
	include("Base.php");
	require_once("includes/db_connection.php");

	if(!isset($_GET["contest"]) || !is_numeric($_GET["contest"]))
		redirect_to("index.php");

	$contest = find_contest_by_id($_GET["contest"]);

	if(!$contest)
		redirect_to("index.php");

	if(!logged_in() || $_SESSION["id"] != $contest["judge_id"])
		redirect_to("index.php");
?>

<style type="text/css">
.form {
	border-style: groove;
	width: 770px;
	min-height: 500px;
	height: auto;
	padding: 20px 0;
	border-width: 2	px;
	float: right;
}
</style>

<script type="text/javascript">
	var id = 2;
	var id_categories=2;
	function add_sample()
	{
		if(id >= 6)
			return;

		var samples=document.getElementById('samples');
		var row = samples.insertRow();

		var td = row.insertCell();
		td.appendChild(document.createTextNode('Sample input ' + id));

		td = row.insertCell();
		var input = document.createElement('textarea');
		input.setAttribute('rows','5');
		input.setAttribute('cols','35');
		input.setAttribute('name', 'sample_input[]');
		td.appendChild(input);

		td = row.insertCell();
		td.appendChild(document.createTextNode('Sample output ' + id));

		td = row.insertCell();
		var output = document.createElement('textarea');
		output.setAttribute('rows','5');
		output.setAttribute('cols','35');
		output.setAttribute('name', 'sample_output[]');
		td.appendChild(output);

		id = id + 1;
	}
	function add_category()
	{	
		if(id_categories >= 5)
			return;

		var category=document.getElementById('category');
		var row = category.insertRow();

		var td = row.insertCell();
		td.appendChild(document.createTextNode('Problem Category ' + id_categories));

		td = row.insertCell();
		var input = document.createElement('textarea');
		input.setAttribute('rows','1');
		input.setAttribute('cols','30');
		input.setAttribute('name', 'categories[]');
		td.appendChild(input);
		id_categories = id_categories + 1;
	}
</script>

<div id="rightPan">
	<h1 style="margin-left: 0px; ">
		<a href="ContestProblems.php?contest=<?php echo $contest["id"]; ?>">
			<?php echo $contest["name"]; ?>
		</a>
	</h1>
	<h2>Insert your Problem Here</h2>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<div>
		<form class="form" method="POST" action="actions/new_problem.php?contest= <?php echo $_GET["contest"]?>">
			<table>
				<tr>
					<td>Problem name</td>
					<td><input type="text" name="problem_name" /> </td>
				</tr>
				<tr>
					<td> Problem Text</td>
					<td> <textarea rows="10" cols="80" name="problem_text"> </textarea></td>
				</tr>
				<tr>
					<td> Problem Level</td>
					<td>
					<select name="level">
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
						<option>6</option>
						<option>7</option>
						<option>8</option>
						<option>9</option>
						<option>10</option>
						<option>11</option>
					</select>
					</td>					
				</tr>
				<tr>					
					<table id="category">
						<tr>
							<td> Problem Category 1</td>
							<td><textarea rows="1" cols="30" name="categories[]"> </textarea></td>
						</tr>						

					</table>					
					<button type="button" style="margin-left: 50px;" onclick="add_category();">add category</button> 					
				</tr>
				<br /><br />
				<tr>
					<table id="samples">
						<tr>
							<td>Sample input 1</td>
							<td><textarea rows="5" cols="35" name="sample_input[]"> </textarea></td>
							<td>Sample output 1</td>
							<td><textarea rows="5" cols="35" name="sample_output[]"> </textarea></td>
						</tr>
					</table>
				</tr>
			</table>
			<button type="button" style="margin-left: 50px;" onclick="add_sample();">sample</button>
			<br /><br /><br /><br /><br />
			<input type="submit" name="submit" value="Submit" style="margin-left: 365px"/>
		</form>
	</div>
</div>
<?php include("Footer.php") ?>



