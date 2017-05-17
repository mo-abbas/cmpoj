<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class EditContestActionTest extends TestCase
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

  public function test_edit_contest_action()
  {
    require("../actions/edit_contest_action.php");

    // no post
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);

		// not logged in
		$_POST["submit"] = 1;
		$output = output();
		$this->assertEquals("../index.php", $output["redirect"]);

    // contest not found
    $_SESSION["id"] = 33;
		$_GET["contest"] = 1;
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);

		// not the judge
		$_SESSION["id"] = 33;
		$_GET["contest"] = 13;
		$output = output();
		$this->assertEquals("../index.php", $output["redirect"]);

    //succesfull edit
		$_SESSION["id"] = 21;
		$_GET["contest"] = 13;
    $contest = find_contest_by_id($_GET["contest"]);
    $_POST["contest_name"] = "bla bla";
    $_POST["contest_starts"] = $contest["start_time"];
    $_POST["contest_ends"] = $contest["end_time"];
		$output = output();

		$contest = find_contest_by_id($_GET["contest"]);
    $this->assertEquals("bla bla", $contest["name"]);
		$this->assertEquals("../edit_contest.php?contest={$_GET["contest"]}", $output["redirect"]);
  }
}
?>
