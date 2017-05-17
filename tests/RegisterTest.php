<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class RegisterTest extends TestCase
{
	private static $connection = null;
	public static function setUpBeforeClass()
	{
    if(!defined("DB_SERVER"))
    {
      define("DB_SERVER", 'localhost');
      define("DB_USER", 'root');
      define("DB_PASS", '');
      define("DB_NAME", 'cmpoj');
    }
		self::$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

		// Test if connection succeeded
		if(mysqli_connect_errno()) {
			die("Database connection failed: " .
				mysqli_connect_error() .
				" (" . mysqli_connect_errno() . ")"
	    );
	  }
	}

	public static function tearDownAfterClass()
	{
		if(isset(self::$connection))
		{
			mysqli_close(self::$connection);
		}
	}

	public function setUp()
	{
		//$this->setOutputCallback(function() {});

		$GLOBALS['connection'] = self::$connection;
		mysqli_query(self::$connection, "BEGIN");
	}

	public function tearDown()
	{
		mysqli_query(self::$connection, "ROLLBACK");
	}

  public function test_register()
  {
    require("../actions/register.php");
    global $errors;
    global $connection;
    
    // logged in
    $_SESSION["id"] = 33;
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // no post
    $output = output();
    $this->assertEquals("../Register.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // handle taken
		$_POST["submit"] = 1;
    $_POST["first_name"] = "test";
    $_POST["last_name"] = "test";
    $_POST["handle"] = "alii";
    $_POST["password"] = "123456";
    $_POST["confirm_password"] = "123456";
    $_POST["email"] = "b@a.com";
		$output = output();
		$this->assertEquals("../Register.php", $output["redirect"]);
    $this->assertEquals("alii is already taken.", $_SESSION["errors"]["handle"]);
    $errors = [];
    $_SESSION = [];

    // succes
		$_POST["submit"] = 1;
    $_POST["first_name"] = "test";
    $_POST["last_name"] = "test";
    $_POST["handle"] = "testinghandle";
    $_POST["password"] = "123456";
    $_POST["confirm_password"] = "123456";
    $_POST["email"] = "b@a.com";
	$output = output();
	$this->assertEquals("../index.php", $output["redirect"]);
    $this->assertEquals("Registered successfully.", $_SESSION["message"]);
	
    $account = find_account_by_handle($_POST["handle"]);
    $this->assertEquals("testinghandle", $account["handle"]);
    $errors = [];
    $_SESSION = [];
  }
}
?>
