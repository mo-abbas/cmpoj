<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/validation_functions.php");
require_once("../includes/functions.php");
require_once("../includes/session.php");

class AccountTest extends TestCase
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
		mysqli_query(self::$connection, "BEGIN");
	}
	
	public function tearDown()
	{
		mysqli_query(self::$connection, "ROLLBACK");
	}

	public function test_find_account_by_handle()
	{
		$GLOBALS['connection'] = self::$connection;

		$handle = "alii";
		$account = find_account_by_handle($handle);
		$this->assertEquals($account["id"], 33);
		$this->assertEquals($account["first_name"], "Ali");
		$this->assertEquals($account["last_name"], "Hussein");
		$this->assertEquals($account["password"], "$2y$10\$YTBkMmFkNzMzZjc0NGQzN.a/MymeYUjytD5l3M5YlB06sr6jRK5eq");
		$this->assertEquals($account["email"], "ali@whatever.com");
		$this->assertEquals($account["team_id"], null);

		$handle = "muahahaha";
		$account = find_account_by_handle($handle);
		$this->assertNull($account);
	}

	public function test_find_account_by_email()
	{
		$GLOBALS['connection'] = self::$connection;

		$email = "ali@whatever.com";
		$account = find_account_by_email($email);
		$this->assertEquals($account["id"], 33);
		$this->assertEquals($account["first_name"], "Ali");
		$this->assertEquals($account["last_name"], "Hussein");
		$this->assertEquals($account["password"], "$2y$10\$YTBkMmFkNzMzZjc0NGQzN.a/MymeYUjytD5l3M5YlB06sr6jRK5eq");
		$this->assertEquals($account["email"], "ali@whatever.com");
		$this->assertEquals($account["team_id"], null);

		$email = "muahahaha";
		$account = find_account_by_email($email);
		$this->assertNull($account);

	}

	public function test_get_team_handle_by_account_id()
	{
		$GLOBALS['connection'] = self::$connection;

		$id = 36;
		$team = get_team_handle_by_account_id($id);
		$this->assertEquals($team["team_id"], 37);
		$this->assertEquals($team["handle"], "Testers");
	}
}
?>
