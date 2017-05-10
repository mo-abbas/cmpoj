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

	public function test_find_problem_by_id()
	{
		$id = 10;
		$problem = find_problem_by_id($id);
		$this->assertEquals($problem["id"], 10);
		$this->assertEquals($problem["title"], "Problem 1");
		$this->assertEquals($problem["level"], 1);

		$id = 1;
		$problem = find_problem_by_id($id);
		$this->assertNull($problem);
	}

	public function test_get_all_problems_in_contest()
	{
		$id = 13;
		$problems = get_all_problems_in_contest($id);
		$this->assertCount(2, $problems);
		$expected = [["title"=>"Problem 1", "id"=>10], ["title"=>"Problem 2", "id"=>11]];
		$this->assertArraySubset($expected, $problems);
	}

	public function test_get_all_samples_in_problem()
	{
		$id = 11;
		$samples = get_all_samples_in_problem($id);
		$this->assertCount(2, $samples);
		$expected = [["id"=>14], ["id"=>15]];
		$this->assertArraySubset($expected, $samples);
	}

	public function test_get_all_categories_in_problem()
	{
		$id = 11;
		$cats = get_all_categories_in_problem($id);
		$this->assertCount(1, $cats);
		$this->assertEquals($cats[0]["category"], "Greedy");
	}

	public function test_get_problem_by_level()
	{
		$level = 1;
		$problems = get_problem_by_level($level);
		$this->assertCount(8, $problems);
	}

	public function test_get_problem_by_category()
	{
		$category = "Greedy";
		$problems = get_problem_by_category($category);
		$this->assertCount(4, $problems);
	}
}
?>
