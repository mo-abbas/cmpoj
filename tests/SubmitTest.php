<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class SubmitTest extends TestCase
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

  public function test_Submit()
  {
    require_once("../Submit.php");

    // not logged in
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    //not problem specified
    $_SESSION["id"] = 33;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    //invalid problem
    $_SESSION["id"] = 33;
    $_GET["problem"] = "dummy";
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    //problem doesn't exist
    $_SESSION["id"] = 33;
    $_GET["problem"] = 1;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    //contest ended
    $_SESSION["id"] = 33;
    $_GET["problem"] = 18;
    $output = output();
    $this->assertEquals("Sorry the contest has already ended.", $_SESSION["message"]);
    $this->assertEquals("ContestProblems.php?contest=18", $output["redirect"]);

    //contest didn't start
    $_SESSION["id"] = 33;
    $_GET["problem"] = 23;
    $output = output();
    $this->assertEquals("Sorry the contest didn't start yet.", $_SESSION["message"]);
    $this->assertEquals("ContestProblems.php?contest=22", $output["redirect"]);

    //team and didn't join
    $_SESSION["id"] = 33;
    $_GET["problem"] = 22;
    $output = output();
    $this->assertEquals("You can't submit as you didn't join this contest", $_SESSION["message"]);
    $this->assertEquals("ContestProblems.php?contest=21", $output["redirect"]);

    //individual joined
    $_SESSION["id"] = 33;
    $_GET["problem"] = 10;
    $output = output();
    $this->assertCount(3, $output["compilers"]);
  }
}
?>
