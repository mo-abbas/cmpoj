<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class NewContestActionTest extends TestCase
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

  public function test_new_contest()
  {
    require("../actions/new_contest.php");
    global $errors;

    // no post
    $output = output();
    $this->assertEquals("../create_contest.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

		// not logged in
		$_POST["submit"] = 1;
		$output = output();
		$this->assertEquals("../create_contest.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // contest starts too early, invalid type
    $_SESSION["id"] = 33;
		$_POST["contest_name"] = "Test contest";
    $_POST["contest_starts"] = date("Y-m-d H:i:s", strtotime("yesterday"));
    $_POST["contest_ends"] = date("Y-m-d H:i:s", strtotime("tomorrow"));
    $_POST["type"] = 2;
    $_POST["compilers"] = [1=>"on", 2=>"off", 3=>"on"];
    $output = output();
    $this->assertEquals("../create_contest.php", $output["redirect"]);
    $this->assertEquals("Invalid contest type chosen.", $_SESSION["errors"]["type"]);
    $this->assertEquals("Start time can't be a previous date.", $_SESSION["errors"]["start"]);
    $errors = [];
    $_SESSION = [];

		// contest starts after it ends, type not set
    $_SESSION["id"] = 33;
    $_POST["contest_name"] = "Test contest";
    $_POST["contest_starts"] = date("Y-m-d H:i:s", strtotime("tomorrow"));
    $_POST["contest_ends"] = date("Y-m-d H:i:s", strtotime("yesterday"));
    unset($_POST["type"]);
    $_POST["compilers"] = [1=>"on", 2=>"off", 3=>"on"];
    $output = output();
    $this->assertEquals("../create_contest.php", $output["redirect"]);
    $this->assertEquals("Start time must be before end time.", $_SESSION["errors"]["start"]);
    $errors = [];
    $_SESSION = [];

    // contest ends in less than an hour, type = team
    $_SESSION["id"] = 33;
    $_POST["contest_name"] = "Test contest";
    $_POST["contest_starts"] = date("Y-m-d H:i:s", strtotime("tomorrow"));
    $_POST["contest_ends"] = date("Y-m-d H:i:s", strtotime("tomorrow +1 minute"));
    $_POST["type"] = 1;
    $_POST["compilers"] = [1=>"on", 2=>"off", 3=>"on"];
    $output = output();
    $this->assertEquals("../create_contest.php", $output["redirect"]);
    $this->assertEquals("Contest must last at least 1 hour.", $_SESSION["errors"]["start"]);
    $errors = [];
    $_SESSION = [];

    // contest ends in less than an hour, type = individual
    $_SESSION["id"] = 33;
    $_POST["contest_name"] = "Test contest";
    $_POST["contest_starts"] = date("Y-m-d H:i:s", strtotime("tomorrow"));
    $_POST["contest_ends"] = date("Y-m-d H:i:s", strtotime("tomorrow +1 minute"));
    $_POST["type"] = 0;
    $_POST["compilers"] = [1=>"on", 2=>"off", 3=>"on"];
    $output = output();
    $this->assertEquals("../create_contest.php", $output["redirect"]);
    $this->assertEquals("Contest must last at least 1 hour.", $_SESSION["errors"]["start"]);
    $errors = [];
    $_SESSION = [];

    // Succesfull add
    $_SESSION["id"] = 33;
    $_POST["contest_name"] = "Test contest";
    $_POST["contest_starts"] = date("Y-m-d H:i:s", strtotime("tomorrow"));
    $_POST["contest_ends"] = date("Y-m-d H:i:s", strtotime("+2 days"));
    $_POST["type"] = 0;
    $_POST["compilers"] = [1=>"on", 2=>"off", 3=>"on"];
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);

    $query = "Select * from Contest where name='Test contest'";
    $result = mysqli_query(self::$connection, $query);
		confirm_query($result);
    $contest = mysqli_fetch_assoc($result);
    $this->assertTrue(isset($contest));
    $this->assertEquals("Test contest", $contest["name"]);
    $this->assertEquals(0, $contest["type"]);
  }
}
?>
