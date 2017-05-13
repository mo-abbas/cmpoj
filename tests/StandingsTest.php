<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class StandingsTest extends TestCase
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

	public function test_Standings()
	{
		require("../Standings.php");
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    $_GET["contest"] = "dummy";
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    $_GET["contest"] = 1;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    // start < time , not judge
    $_GET["contest"] = 13;
    $output = output();
    $this->assertEquals(13, $output["contest"]["id"]);
    $this->assertCount(4, $output["standings"]);
    $this->assertCount(2, $output["problems"]);
    $this->assertFalse($output["judge"]);

    // start > time , not judge
    $_GET["contest"] = 22;
    $output = output();
    $this->assertEquals("ContestProblems.php?contest=22", $output["redirect"]);

    // start > time , judge
    $_SESSION["id"] = 27;
    $_GET["contest"] = 22;
    $output = output();
    $this->assertCount(0, $output["standings"]);
    $this->assertCount(1, $output["problems"]);
    $this->assertTrue($output["judge"]);
	}
}
?>
