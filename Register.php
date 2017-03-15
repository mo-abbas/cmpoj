<?php 
	include("Base.php");
	if(logged_in())
		redirect_to("index.php");
?>

<style type="text/css">
	.form
	{
		width: 670px;
		height: 290px;
		border-style: groove;
		border-width: 2px;
		padding-top: 100px;
		padding-left: 100px; 
		font-size: 20px;
		background-color: #EBD6D6;
	}
	.input
	{
		margin-left: 60px;
		padding-left: 10px;
	}
</style>



<div id="rightPan">
	<h1>Pleasured you are here</h1>
	<h2>Sign Up form</h2>
	<?php
		$errors = errors();
		echo form_errors($errors); 
		echo message(); 
	?>
	<form class="form" method="POST" action="actions/register.php">
		<table>
			<tr>
				<td>First Name</td>
				<td><input class="input" type="text" name="first_name" /></td>
			</tr>
			<tr>
				<td>Last Name</td>
				<td><input class="input" type="text" name="last_name" /></td>
			</tr>
			<tr>
				<td>Handle</td>
				<td><input class="input" type="text" name="handle"/></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input class="input" type="password" name="password"/></td>
			</tr class="tabler">
			<tr>
				<td>Confirm Password</td>
				<td><input class="input" type="password" name="confirm_password"/></td>
			</tr>
			<tr class="tabler">
				<td>Email</td>
				<td><input class="input" type="email" name="email"/></td>
			</tr>
		</table>
		<input type="submit" name="submit" style="margin-left: 350px; margin-top: 20px;" value="SignUp"/>
	</form>	
</div>

<?php  include("Footer.php") ?>
