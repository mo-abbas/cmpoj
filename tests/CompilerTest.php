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

	public function test_find_compiler_by_id()
	{
		$id = 1;
		$compiler = find_compiler_by_id($id);
		$this->assertEquals($compiler["id"], 1);
		$this->assertEquals($compiler["name"], "GNU C++");
		$this->assertEquals($compiler["version"], "4.8.2");
		$this->assertEquals($compiler["code"], "cpp");

		$id = 10;
		$compiler = find_compiler_by_id($id);
		$this->assertNull($compiler);
	}

	public function test_find_compiler_by_code()
	{
		$code = "cpp";
		$compiler = find_compiler_by_code($code);
		$this->assertEquals($compiler["id"], 1);
		$this->assertEquals($compiler["name"], "GNU C++");
		$this->assertEquals($compiler["version"], "4.8.2");
		$this->assertEquals($compiler["code"], "cpp");

		$code = "dummy";
		$compiler = find_compiler_by_code($code);
		$this->assertNull($compiler);
	}

	public function test_get_all_compilers()
	{
		$compilers = get_all_compilers();
		$this->assertCount(3, $compilers);
	}

	public function test_get_all_compilers_in_contest()
	{
		$id = 13;
		$compilers = get_all_compilers_in_contest($id);
		$this->assertCount(3, $compilers);
	}
}
?>
