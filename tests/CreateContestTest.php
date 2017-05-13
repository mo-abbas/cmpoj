<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class CreateContestTest extends TestCase
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

	public function test_create_contest()
	{
		require("../create_contest.php");
    $output = output();
    $this->assertArrayHasKey("redirect", $output);

    $_SESSION["id"] = 33;
    $output = output();
    $expected = [["id"=>1], ["id"=>2], ["id"=>3]];
    $this->assertCount(3, $output["compilers"]);
    $this->assertArraySubset($expected, $output["compilers"]);
	}
}
?>
