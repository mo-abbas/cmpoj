<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class ContestProblemsTest extends TestCase
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

	public function test_ContestProblems()
	{
		require("../ContestProblems.php");
    $output = output();
    $this->assertArrayHasKey("redirect", $output);

    $_GET["contest"] = "dummy";
    $output = output();
    $this->assertArrayHasKey("redirect", $output);

    $_GET["contest"] = 1;
    $output = output();
    $this->assertArrayHasKey("redirect", $output);

    // not logged in, contest_time > time, !judge, !showProblems
    $_GET["contest"] = 22;
    $output = output();
		$this->assertFalse($output["judge"]);
    $this->assertFalse($output["showContest"]);
    $this->assertFalse($output["showProblems"]);
    $this->assertEquals($output["contest"]["id"], 22);
    $this->assertEquals($output["contest"]["name"], "contest21");

    // judge, logged_in
    $_GET["contest"] = 22;
    $_SESSION["id"] = 27;
    $output = output();
		$this->assertTrue($output["judge"]);
    $this->assertTrue($output["showContest"]);
    $this->assertFalse($output["showProblems"]);
    $this->assertEquals($output["contest"]["id"], 22);
    $this->assertEquals($output["contest"]["name"], "contest21");

		// logged in, not joined
    $_GET["contest"] = 13;
    $_SESSION["id"] = 27;
    $output = output();
		$this->assertFalse($output["judge"]);
    $this->assertTrue($output["showContest"]);
    $this->assertTrue($output["showProblems"]);
		$this->assertFalse($output["joinedContest"]);
    $this->assertEquals($output["contest"]["id"], 13);
    $this->assertEquals($output["contest"]["name"], "First Contest");

		// joined contest
		$_GET["contest"] = 13;
    $_SESSION["id"] = 33;
    $output = output();
		$this->assertFalse($output["judge"]);
    $this->assertTrue($output["showContest"]);
    $this->assertTrue($output["showProblems"]);
		$this->assertTrue($output["joinedContest"]);
    $this->assertEquals($output["contest"]["id"], 13);
    $this->assertEquals($output["contest"]["name"], "First Contest");
	}
}
?>
