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
		$GLOBALS['connection'] = self::$connection;
		mysqli_query(self::$connection, "BEGIN");
	}

	public function tearDown()
	{
		mysqli_query(self::$connection, "ROLLBACK");
	}

	public function test_find_submission_by_id()
	{
		$id = 374;
		$submission = find_submission_by_id($id);
		$this->assertEquals($submission["id"], 374);
		$this->assertEquals($submission["contestant_id"], 22);
		$this->assertEquals($submission["problem_id"], 10);

		$id = 1;
		$submission = find_problem_by_id($id);
		$this->assertNull($submission);
	}

	public function test_get_all_submissions_in_problem()
	{
		$id = 10;
		$submission = get_all_submissions_in_problem($id);
		$this->assertCount(7, $submission);
	}

	public function test_get_status_submissions()
	{
		$id = 10;
		$status = "Accepted";
		$submissions = get_status_submissions($id, $status);
		$this->assertCount(3, $submissions);
	}
}
?>
