<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class ProblemsTest extends TestCase
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

	public function test_Problems()
	{
		require("../Problems.php");

    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    // problem not found
    $_GET["problem"] = 1;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    // contest didn't start
    $_GET["problem"] = 23;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);	

    // problem found
    $_GET["problem"] = 10;
    $output = output();
    $this->assertEquals(10, $output["problem"]["id"]);
    $this->assertEquals("Problem 1", $output["problem"]["title"]);
    $this->assertCount(1, $output["samples"]);
    $this->assertEquals(13, $output["samples"][0]["id"]);
    $this->assertCount(1, $output["categories"]);
    $this->assertFalse($output["is_judge"]);
    $this->assertFalse($output["joinedContest"]);
  }
}
?>
