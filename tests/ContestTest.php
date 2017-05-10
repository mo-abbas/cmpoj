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

	public function test_find_contest_by_id()
	{
		$GLOBALS['connection'] = self::$connection;

		$id = 13;
		$contest = find_contest_by_id($id);
		$this->assertEquals($contest["name"], "First Contest");
		$this->assertEquals($contest["start_time"], "2015-01-09 03:14:00");
		$this->assertEquals($contest["end_time"], "2018-01-10 01:00:00");
		$this->assertEquals($contest["type"], 0);
		$this->assertEquals($contest["judge_id"], 21);

		$id = 1;
		$contest = find_contest_by_id($id);
		$this->assertNull($contest);
	}

	public function test_get_standings_in_contest()
	{
		$GLOBALS['connection'] = self::$connection;

		$id = 13;
		$standings = get_standings_in_contest(13);
		$this->assertCount(4, $standings);
		$expected = [
			["id" => 23, "score" => 130, "problems" => [
				10 => ["contestant_id" => 23,  "ac_time" => "2015-01-09 04:19:28" , "submission_num" => 1],
				11 => ["contestant_id" => 23,  "ac_time" => "2015-01-09 04:19:39" , "submission_num" => 1]
				]
			],
			["id" => 22, "score" => 142, "problems" => [
				10 => ["contestant_id" => 22,  "ac_time" => "2015-01-09 03:55:06" , "submission_num" => 3],
				11 => ["contestant_id" => 22,  "ac_time" => "2015-01-09 04:14:56" , "submission_num" => 1]
				]
			],
			["id" => 33, "score" => 7490, "problems" => [
				10 => ["contestant_id" => 33,  "ac_time" => "2015-01-11 17:27:46" , "submission_num" => 1],
				11 => ["contestant_id" => 33,  "ac_time" => "2015-01-11 17:31:19" , "submission_num" => 2]
				]
			],
			["id" => 25, "score" => 0, "problems" => [
				10 => ["contestant_id" => 25,  "ac_time" => "1000-01-01 00:00:00" , "submission_num" => 1],
				]
			]
		];
		$this->assertArraySubset($expected, $standings);
	}

	public function test_get_all_submissions_in_contest()
	{
		$GLOBALS['connection'] = self::$connection;

		$id = 13;
		$submissions = get_all_submissions_in_contest($id);
		$this->assertCount(11, $submissions);
	}

	public function test_get_stat_problem_in_contest()
	{
		$GLOBALS['connection'] = self::$connection;
		
		$id = 13;
		$status = "Accepted";
		$result = get_stat_problem_in_contest($id, $status);
		$this->assertCount(5, $result);
	}
}
?>
