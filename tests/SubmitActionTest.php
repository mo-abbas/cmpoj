<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class SubmitActionTest extends TestCase
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

  public function test_submit()
  {
    require("../actions/submit.php");
    global $errors;
    global $connection;

    // not logged in
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // no problem
    $_SESSION["id"] = 33;
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // no compiler
    $_SESSION["id"] = 33;
    $_GET["problem"] = 1;
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // problem not found
    $_SESSION["id"] = 33;
    $_GET["problem"] = 1;
    $_POST["compiler"] = "bla";
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // contestant didn't join
    $_SESSION["id"] = 21;
    $_GET["problem"] = 10;
    $_POST["compiler"] = "bla";
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // compiler not found
    $_SESSION["id"] = 33;
    $_GET["problem"] = 10;
    $_POST["compiler"] = "bla";
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // no code
    $_SESSION["id"] = 33;
    $_GET["problem"] = 10;
    $_POST["compiler"] = "cpp";
    $output = output();
    $this->assertEquals("../Submit.php?problem=" . $_GET["problem"], $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // success
    $_SESSION["id"] = 33;
    $_GET["problem"] = 10;
    $_POST["compiler"] = "cpp";
    $_POST["submit_code"] = "int x = 2;";
    $output = output();
    $this->assertEquals("../Problems.php?problem=" . $_GET["problem"], $output["redirect"]);
    $errors = [];
    $_SESSION = [];
  }
}
?>
