<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class MySubmissionsTest extends TestCase
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

	public function test_mySubmissions()
	{
		require("../mySubmissions.php");
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    // contestant not found
    $_GET["id"] = 1;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    // not logged in
    $_GET["id"] = 22;
    $output = output();
    $this->assertFalse($output["showCode"]);
    $this->assertCount(4, $output["submissions"]);
    $this->assertEquals("mohamed", $output["handle"]);

    // logged in as another user
    $_GET["id"] = 22;
    $_SESSION["id"] = 33;
    $output = output();
    $this->assertFalse($output["showCode"]);
    $this->assertCount(4, $output["submissions"]);
    $this->assertEquals("mohamed", $output["handle"]);

    // show code
    $_GET["id"] = 22;
    $_SESSION["id"] = 22;
    $output = output();
    $this->assertTrue($output["showCode"]);
    $this->assertCount(4, $output["submissions"]);
    $this->assertEquals("mohamed", $output["handle"]);

    // no submissions
    $_GET["id"] = 36;
    $_SESSION["id"] = 22;
    $output = output();
    $this->assertFalse($output["showCode"]);
    $this->assertCount(0, $output["submissions"]);
    $this->assertEquals("asad", $output["handle"]);
	}
}
?>
