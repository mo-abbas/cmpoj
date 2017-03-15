<?php
include("Base.php");
require_once("includes/db_connection.php");
require_once("includes/validation_functions.php");
$errorDiv = "";
if (isset($_POST["id"]) && isset($_POST["username"]) && isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["password"]) && isset($_POST["email"]))
{
	$password = password_encrypt($_POST["password"]);
	$query  = "INSERT INTO admin (id, username, first_name, last_name, password, email) VALUES ({$_POST["id"]}, '{$_POST["username"]}', '{$_POST["firstname"]}', '{$_POST["lastname"]}', '{$password}', '{$_POST["email"]}')"; //CHECK IT IS A NUMBER FIRST
	$result = mysqli_query($connection, $query);
	if (!$result)
		$errorDiv = "
		<div class=\"error\">
			<ul>
				<li>Some values are incorrect.</li>
			</ul>
		</div>
		";
	else
		redirect_to("index.php");
		
}
else if (isset($_POST["submit"]))
{
	$errorDiv = "
	<div class=\"error\">
		<ul>
			<li>Some values are missing.</li>
		</ul>
	</div>
	";
}
?>
<div id="rightPan">
	<div class="divName">
		<span> Add Admin </span>
	</div>
	<?php echo $errorDiv; ?>
	<form action="AddAdmin.php" class="login" method="POST">
		<h2>Admin Data</h2>	
		<table>
			<tr> 
				<td> ID:</td>
				<td><input type="text" name="id" value=""> </td>
			</tr>
			<tr> 
				<td> Username:</td>
				<td><input type="text" name="username" value=""> </td>
			</tr>
			<tr> 
				<td> Firstname:</td>
				<td><input type="text" name="firstname" value=""> </td>
			</tr>
			<tr> 
				<td> Lastname:</td>
				<td><input type="text" name="lastname" value=""> </td>
			</tr>
			<tr> 
				<td> Email:</td>
				<td><input type="text" name="email" value=""> </td>
			</tr>
			<tr>
				<td> Password:</td>
				<td><input type="password" name="password" value=""></td>
			</tr>
		</table>
		<input type="submit" name="submit" value="AdminCreate"/>
	</form>
</div>
<?php
include("Footer.php");
?>