<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class SubmissionsTest extends TestCase
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

	public function test_submissions()
	{
		require_once("../submissions.php");

    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    $_GET["contest"] = "dummy";
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    //contest not found
    $_GET["contest"] = 1;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    //not logged in, not the judge and contest started
    $_GET["contest"] = 13;
    $output = output();
    $this->assertCount(11, $output["submissions"]);

    // I am the judge and the contest didn't start
    $_SESSION["id"] = 27;
    $_GET["contest"] = 22;
    $output = output();
    $this->assertTrue($output["judge"]);
    $this->assertCount(0, $output["submissions"]);
    $this->assertCount(1, $output["contest"]["problems"]);

    // not judge and didn't start
    $_SESSION["id"] = 33;
    $_GET["contest"] = 22;
    $output = output();
    $this->assertEquals("ContestProblems.php?contest=22", $output["redirect"]);
    $this->assertEquals("The contest didn't start yet.", $_SESSION["message"]);

    //filter by problem in another contest
    $_GET["contest"] = 13;
    $_GET["problem"] = 20;
    $output = output();
    $this->assertCount(11, $output["submissions"]);

    // filter by problem (not found)
    $_GET["contest"] = 13;
    $_GET["problem"] = 1;
    $output = output();
    $this->assertCount(11, $output["submissions"]);

    // submission not pending
    $_GET["contest"] = 13;
    $_GET["problem"] = 11;
    $output = output();
    echo $output["problem"] == null;
    $this->assertCount(4, $output["submissions"]);
	}
}
?>
