<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class Contest_statTest extends TestCase
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

	public function test_Contest_stat()
	{
		$_GET["contest"] = 13;
		require_once("../Contest_stat.php");

		$this->assertEquals(5, $Accepted_no);
    $this->assertEquals(2, $wrong_Answer_no);
    $this->assertEquals(1, $pending_no);
    $this->assertEquals(1, $Runtime_error_no);
    $this->assertEquals(0, $Time_limit_no);
    $this->assertEquals(2, $compile_error_no);
	}
}
?>
