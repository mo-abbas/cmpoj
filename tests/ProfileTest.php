<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class ProfileTest extends TestCase
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

	public function test_profile()
	{
		require("../Profile.php");
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    // contestant not found
    $_GET["id"] = 1;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    // contestant is a team
    $_GET["id"] = 37;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    // custom user
    $_GET["id"] = 33;
    $output = output();
    $this->assertCount(2, $output["solved_problems"]);
    $this->assertEquals(1, $output["solved_problems"][0]["count"]);
    $this->assertEquals(1, $output["solved_problems"][1]["count"]);
    $this->assertCount(1, $output["joined_contests"]);
    $this->assertNull($output["team"]);
    $this->assertEquals(33, $output["account"]["id"]);

    // logged in as another user
    unset($_GET["id"]);
    $_SESSION["id"] = 33;
    $output = output();
    $this->assertCount(2, $output["solved_problems"]);
    $this->assertequals(1, $output["solved_problems"][0]["count"]);
    $this->assertequals(1, $output["solved_problems"][1]["count"]);
    $this->assertCount(1, $output["joined_contests"]);
    $this->assertNull($output["team"]);
    $this->assertEquals(33, $output["account"]["id"]);
	}
}
?>
