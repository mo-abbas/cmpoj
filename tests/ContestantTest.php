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

	public function test_find_contestant_by_handle()
	{
		$GLOBALS['connection'] = self::$connection;

		$handle = "alii";
		$contestant = find_account_by_handle($handle);
		$this->assertEquals($contestant["id"], 33);
		$this->assertEquals($contestant["handle"], "alii");

		$handle = "muahahaha";
		$contestant = find_account_by_handle($handle);
		$this->assertNull($contestant);
	}

	public function test_find_contestant_by_id()
	{
		$GLOBALS['connection'] = self::$connection;

		$id = 33;
		$contestant = find_contestant_by_id($id);
		$this->assertEquals($contestant["id"], 33);
		$this->assertEquals($contestant["handle"], "alii");

		$id = 40;
		$contestant = find_contestant_by_id($id);
		$this->assertNull($contestant);
	}

	public function test_find_contestant_in_contest()
	{
		$GLOBALS['connection'] = self::$connection;

		$contestantId = 22;
		$contestId = 13;
		$contestant = find_contestant_in_contest($contestantId, $contestId);
		$this->assertEquals($contestant["contestant_id"], 22);
		$this->assertEquals($contestant["contest_id"], 13);
		$this->assertEquals($contestant["rank"], 2);
		$this->assertEquals($contestant["score"], 142);
		$this->assertEquals($contestant["acc_problems"], 2);

		$contestantId = 27;
		$contestId = 13;
		$contestant = find_contestant_in_contest($contestantId, $contestId);
		$this->assertNull($contestant);
	}

	public function test_get_all_contestants()
	{
		$GLOBALS['connection'] = self::$connection;

		$contestants = get_all_contestants();
		$this->assertCount(17, $contestants);
		$this->assertArrayHasKey("id", $contestants[0]);
		$this->assertArrayHasKey("handle", $contestants[0]);
	}

	public function test_get_contestant_rating()
	{
		$GLOBALS['connection'] = self::$connection;

		$contestants = get_contestant_rating();
		$this->assertCount(4, $contestants);
		$this->assertArrayHasKey("id", $contestants[0]);
		$this->assertArrayHasKey("handle", $contestants[0]);
		$this->assertArrayHasKey("Score", $contestants[0]);
		$this->assertEquals(2, $contestants[0]["Score"]);
		$this->assertEquals("alii", $contestants[0]["handle"]);
		$this->assertEquals(0, $contestants[3]["Score"]);
		$this->assertEquals("test2", $contestants[3]["handle"]);
	}

	public function test_get_all_contestant_submissions()
	{
		$GLOBALS['connection'] = self::$connection;

		$id = 22;
		$submissions = get_all_contestant_submissions($id);
		$this->assertCount(4, $submissions);
		foreach($submissions as $submission)
			$this->assertEquals($id, $submission["contestant_id"]);
	}
}
?>
