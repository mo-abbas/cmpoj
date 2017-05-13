<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class CoachedTeamsTest extends TestCase
{
	private static $connection = null;
	public static function setUpBeforeClass()
	{
		define("DB_SERVER", 'localhost');
		define("DB_USER", 'root');
		define("DB_PASS", '');
		define("DB_NAME", 'cmpoj');
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

	public function test_CoachedTeams()
	{
		$_SESSION["id"] = 33;
		require_once("../CoachedTeams.php");
		$teams = output();
		$this->assertCount(1, $teams);
		$this->assertCount(4, $teams[0]["members"]);
		$this->assertEquals($teams[0]["handle"], "Testers");
		$this->assertEquals($teams[0]["id"], 37);
	}
}
?>
