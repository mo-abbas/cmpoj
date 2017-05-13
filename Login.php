<?php
require_once("includes/functions.php");
require_once("includes/db_connection.php");
require_once("includes/validation_functions.php");
require_once("includes/session.php");

$handle = "";
// returns team instead of team handle

function output()
{
	$dest = "index.php";

	$output = [];
	if(logged_in())
	{
		$output["redirect"] = $dest;
		return $output;
	}

	if(!isset($_POST["submit"]))
		return $output;

	global $errors;
	$required_fields = array("handle", "password");
	validate_presences($required_fields);

	if(!empty($errors))
	{
		$_SESSION["message"] = "Handle/Password do not exist";
		return $output;
	}

	$handle = mysql_prep($_POST["handle"]);
	$password = $_POST["password"];

	$account = find_account_by_handle($handle);
	if($account && password_check($password, $account["password"]))
	{
		$_SESSION["id"] = $account["id"];
		$_SESSION["handle"] = $account["handle"];

		if($account["team_id"])
		{
			$_SESSION["team_id"] = $account["team_id"];
			$_SESSION["team_handle"] = get_team_handle($account["team_id"]);
		}

		$output["redirect"] = $dest;
	}
	else
	{
		$_SESSION["message"] = "Handle/Password do not exist";
	}

	return $output;
}

$output = output();
if(isset($output["redirect"]))
	redirect_to($output["redirect"]);
else {
include("Base.php");
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
				<td><input type="text" name="handle" value="<?php if (isset($_POST["handle"])) echo htmlentities($handle);?>"> </td>
			</tr>
			<tr>
				<td> Password:</td>
				<td><input type="password" name="password" value=""></td>
			</tr>
		</table>
		<input type="submit" name="submit" value="Login"/>
	</form>
</div>
<?php
include("Footer.php"); }
?>
