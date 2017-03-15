<?php 
	include("Base.php"); 
	require_once("includes/db_connection.php");
	if(!logged_in())
		redirect_to("index.php");
?>

<style type="text/css">

</style>
<div id="rightPan">
	<h1>Create Your Contest</h1>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>
	<form class="createContest" method="POST" action="actions/new_contest.php">
		<table>
			<tr>
				<td>Contest Name</td>
				<td><input type="text" name="contest_name"></td>
			</tr>
			<tr>
				<td>Contest starts</td>
				<td><input type="datetime-local" name="contest_starts"></td>
			</tr>
			<tr>
				<td>Contest ends</td>
				<td><input type="datetime-local" name="contest_ends"></td>
			</tr>
			<tr>
				<td>Contest type</td>
				<td>
					<input type="radio" name="type" value="0" /> Individual
					&nbsp;
					<input type="radio" name="type" value="1" /> Team
				</td>
			</tr>
			<tr>
				<td>Available compiler:</td>
				<td>
					&nbsp;&nbsp;
					<table>
						<?php
							$compilers = get_all_compilers();

							foreach ($compilers as $compiler)
							{ ?>
								<tr>
									<td> 
									<?php echo $compiler["name"] . " " . $compiler["version"]; ?>
									</td>
									<td>
										<input type="checkbox" name="compilers[ <?php  echo htmlentities($compiler["id"]); ?> ]"/>
									</td>
								</tr>
						<?php
							}
						?>
						
					</table>
				</td>
			</tr>
		</table>
		<input type="submit" name="submit" value="Create" style="margin: 30px 10em;"/>
	</form>
</div>

<?php include("Footer.php"); ?>
