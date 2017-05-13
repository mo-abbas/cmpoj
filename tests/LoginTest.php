<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class LoginTest extends TestCase
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
		$this->setOutputCallback(function() {});

		$GLOBALS['connection'] = self::$connection;
		mysqli_query(self::$connection, "BEGIN");
	}

	public function tearDown()
	{
		mysqli_query(self::$connection, "ROLLBACK");
	}

	public function test_Login()
	{
		require_once("../Login.php");
    global $errors;

    $output = output();
    $this->assertEmpty($output);
    $this->assertEmpty($_SESSION);

    $_POST["submit"] = 1;
    $output = output();
    $this->assertEmpty($output);
    $this->assertEquals("Handle/Password do not exist", $_SESSION["message"]);
    $_SESSION = [];
    $errors = [];

    // wrong password
    $_POST["handle"] = "alii";
    $_POST["password"] = "wrong password";
    $output = output();
    $this->assertEmpty($output);
    $this->assertEquals("Handle/Password do not exist", $_SESSION["message"]);
    $_SESSION = [];
    $errors = [];

    // user not found
    $_POST["handle"] = "3bslaam";
    $_POST["password"] = "wrong password";
    $output = output();
    $this->assertEmpty($output);
    $this->assertEquals("Handle/Password do not exist", $_SESSION["message"]);
    $_SESSION = [];
    $errors = [];

    // user without team
    $_POST["handle"] = "alii";
    $_POST["password"] = "123456";
    $output = output();
    $this->assertEquals(33, $_SESSION["id"]);
    $this->assertFalse(isset($_SESSION["team_id"]));
    $this->assertFalse(isset($_SESSION["message"]));
    $this->assertEquals("alii", $_SESSION["handle"]);
    $this->assertFalse(isset($_SESSION["team_handle"]));
    $this->assertEquals("index.php", $output["redirect"]);
    $_SESSION = [];

    // user with team
    $_POST["handle"] = "asad";
    $_POST["password"] = "123456";
    $output = output();
    $this->assertEquals(36, $_SESSION["id"]);
    $this->assertEquals(37, $_SESSION["team_id"]);
    $this->assertFalse(isset($_SESSION["message"]));
    $this->assertEquals("asad", $_SESSION["handle"]);
    $this->assertEquals("index.php", $output["redirect"]);
    $this->assertEquals("Testers", $_SESSION["team_handle"]);
    $_SESSION = [];
	}
}
?>
