<?php
include("Base.php");
require_once("includes/db_connection.php");
require_once("includes/validation_functions.php");
$handle = "";

if(logged_in())
	redirect_to("index.php");

if(isset($_POST["submit"]))
{
	$required_fields = array("handle", "password");
	$handle = $_POST["handle"];
	validate_presences($required_fields);

	if(empty($errors))
	{
		$handle = mysql_prep($_POST["handle"]);
		$password = $_POST["password"];

		$account = find_account_by_handle($handle);

		if($account && password_check($password, $account["password"]))
		{
			$_SESSION["id"] = $account["id"];
			$_SESSION["handle"] = $account["handle"];
			
			if(isset($_SESSION["adid"]))
				unset($_SESSION["adid"]);

			if($account["team_id"])
			{
				$_SESSION["team_id"] = $account["team_id"];
				$_SESSION["team_handle"] = find_team_by_id($account["team_id"]);
			}

			if(isset($_POST["remember"]) && $_POST["remember"] == "on")
				setcookie(session_name(), $_COOKIE[session_name()], time() + 3600 * 24 * 30);
			if(isset($_GET["dest"]))
				redirect_to($_GET["dest"].".php");
			else
				redirect_to("index.php");
		}
		else
		{
			$_SESSION["message"] = "Handle/Password do not exist";	
		}
	}
	else
		$_SESSION["errors"] = $errors;
}
?>
<div id="rightPan">
	<div class="divName">
		<span> ONLINE JUDGE Login </span>
	</div>
	<?php
		$errors = errors();
		echo form_errors($errors); 
		echo message(); 
		$dest=false;
		if (isset($_GET["dest"]))
			$dest = $_GET["dest"];
	?>
	<form action="Login.php" class="login" method="POST">
		<h2>Please Login</h2>	
		<table>
			<tr> 
				<td> Handle:</td>
				<td><input type="text" name="handle" value="<?php if (isset($handle)) echo htmlentities($handle);?>"> </td>
			</tr>
			<tr>
				<td> Password:</td>
				<td><input type="password" name="password" value=""></td>
			</tr>
			<tr>
				<td> <input type="checkbox" name="remember" style="float: right;"></td>
				<td> Remember Me</td>
		</table>
		<input type="submit" name="submit" value="Login"/>
	</form>
</div>
<?php
include("Footer.php");
?>